// Copyright (C) <2015>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//	This program is free software: you can redistribute it and/or modify
//	it under the terms of the GNU General Public License as published by
//	the Free Software Foundation, version 3 of the License.
//
//	This program is distributed in the hope that it will be useful,
//	but WITHOUT ANY WARRANTY; without even the implied warranty of
//	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//	GNU General Public License for more details.
//
//	You should have received a copy of the GNU General Public License
//	along with this program.  If not, see <http://www.gnu.org/licenses/>.
//

// 2.
//	If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//	under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//	License agreement and license key will be shipped with the order
//	confirmation.

App.Controllers.PacketmanagerIndexController = Frontend.AppController.extend({
    $advancedPackagingToolTextarea: null,
    $packageManagerTextarea: null,

    /**
     * @type {Array}
     */
    components: ['WebsocketSudo'],

    /**
     * @constructor
     * @return {void}
     */
    _initialize: function(){
        var self = this;

        /*
         * Tag search
         */
        $('#tagSearch').keyup(function(){
            var searchString = $(this).val().toLowerCase();
            if(searchString != ''){
                $('.tags').each(function(key, object){
                    var $object = $(object);
                    if(!$object.html().toLowerCase().match(searchString)){
                        //Hide elements that do not match to current search request
                        $object.parents('.jarviswidget').hide();
                    }
                });
            }else{
                //No search string given, display everything
                $('.tags').parents('.jarviswidget').show();
            }
        });

        self.$advancedPackagingToolTextarea = $('.advanced-packaging-tool textarea');
        self.$packageManagerTextarea = $('#package-manager-log textarea');

        //self.WebsocketSudo.setup(self.getVar('websocket_host'), self.getVar('websocket_port'));
        self.WebsocketSudo.setup(self.getVar('websocket_url'), self.getVar('akey'));

        self.WebsocketSudo._errorCallback = function(){
            $('#error_msg').html('<div class="alert alert-danger alert-block"><a href="#" data-dismiss="alert" class="close">×</a><h5 class="alert-heading"><i class="fa fa-warning"></i> Error</h5>Could not connect to SudoWebsocket Server</div>');
        }

        self.WebsocketSudo.connect();

        // When the socket has been successfully established, do this once...
        self.WebsocketSudo._success = function(e){
            // openITCOCKPIT tab

            // When the modal window is closed (after all animations), reload the page.
            $('#package-manager-log').on('hidden.bs.modal', function(e){
                document.location = document.location;
            });

            $('.uninstall').click(function(){
                var packageName = $(this).data('package-name');
                if(!packageName){
                    return;
                }

                if($(this).data('package-enterprise') == 1){
                    // We want to remove a enterprise module, so we let apt do the dirty job
                    self.WebsocketSudo.send(self.WebsocketSudo.toJson('5238f8e57e72e81d44119a8ffc3f98ea', {name: $(this).data('package-apt-name')}));
                }else{
                    // This is not an enterprise module, so we remove the folder and run the uninstall.php and stuff like this
                    self.WebsocketSudo.send(self.WebsocketSudo.toJson('package_uninstall', {name: packageName}));
                }

            });

            $('.install').click(function(){
                var $this = $(this);
                var packageUrl = $this.data('package-url');
                var packageName = $this.data('package-name');
                if(!packageUrl || !packageName){
                    return;
                }
                if($(this).data('package-enterprise') == 0){
                    // We want to install a non enterprise module, so we need to download the zip unzip it copy files, run the install.php and stuff like this
                    self.WebsocketSudo.send(self.WebsocketSudo.toJson('package_install', {
                        url: packageUrl,
                        name: packageName
                    }));
                }else{
                    // We want to install a enterprise module, so we let apt do the dirty job
                    self.WebsocketSudo.send(self.WebsocketSudo.toJson('d41d8cd98f00b204e9800998ecf8427e', {name: $(this).data('package-apt-name')}));
                }
            });

            // OS/Ubuntu tab

            $('#apt_update').removeAttr('disabled');
            $('#apt_update').click(function(){
                self.WebsocketSudo.send(self.WebsocketSudo.toJson('apt_get_update', ''));
            });
        };

        // On message
        self.WebsocketSudo._callback = function(transmitted){
            if(transmitted.category && transmitted.category === 'action'){
                switch(transmitted.payload){
                    case 'reload':
                        document.location = document.location;
                        break;

                    default:
                        break;
                }
            }

            switch(transmitted.task){
                case 'package_uninstall':
                case 'package_install':
                case '5238f8e57e72e81d44119a8ffc3f98ea':
                case 'd41d8cd98f00b204e9800998ecf8427e':
                    if(transmitted.category != 'notification'){
                        break;
                    }

                    $('#package-manager-log').modal('show');
                    // var text = transmitted.task + ' ' + transmitted.payload;
                    var text = transmitted.payload;

                    // Append message
                    if(!text.match(/\n$/)){
                        text += "\n";
                    }
                    self.$packageManagerTextarea.append(text);

                    // Scroll to bottom
                    var value = self.$packageManagerTextarea[0].scrollHeight -
                        self.$packageManagerTextarea.height();
                    self.$packageManagerTextarea.scrollTop(value);

                    break;

                default:
                    self.$advancedPackagingToolTextarea.append(transmitted.payload);
                    var value = self.$advancedPackagingToolTextarea[0].scrollHeight -
                        self.$advancedPackagingToolTextarea.height();
                    self.$advancedPackagingToolTextarea.scrollTop(value);
            }
        };
    }
});
