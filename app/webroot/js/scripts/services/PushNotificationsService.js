angular.module('openITCOCKPIT')
    .service('PushNotificationsService', function($interval){

        var _connection = null;
        var _url = '';
        var _key = '';
        var _userId = 0;
        var _uuid = null;

        var _success = false;

        var _onSuccess = function(event){
            _success = true;
            var data = JSON.parse(event.data);

            console.info('Connection to push notification service established successfully');
            _uuid = data.data.uuid;

            //Filter multiple tabs in same browser window
            var browserUuid = localStorage.getItem('browserUuid');
            if(browserUuid === null){
                browserUuid = UUID.generate();
                localStorage.setItem('browserUuid', browserUuid);
            }

            //console.log(browserUuid);

            _send(JSON.stringify({
                task: 'register',
                key: _key,
                uuid: _uuid,
                data: {
                    userId: _userId,
                    browserUuid: browserUuid
                }
            }));

        };

        var _onError = function(event){
            _success = false;
            console.error(event);
        };

        var _onResponse = function(event){
            //console.log(event);
        };

        var _onDispatch = function(event){
            //console.log(event);
        };

        var keepAlive = function(){
            _send(JSON.stringify({
                task: 'keepAlive',
                key: _key,
                uuid: _uuid
            }));
        };

        var _connect = function(){
            _connection = new WebSocket(_url);


            _connection.onopen = _onResponse;
            _connection.onmessage = _parseResponse;
            _connection.onerror = _onError;


            var keepAliveInterval = $interval(function(){
                if(_success){
                    keepAlive();
                }
            }, 30000);

        };

        var _send = function(json){
            _connection.send(json);
        };

        var _parseResponse = function(event){
            var transmitted = JSON.parse(event.data);
            switch(transmitted.type){
                case 'connection':
                    //Trigger _onSuccess callback and start keepAlive
                    _onSuccess(event);
                    break;

                case 'message':
                    //Server send us a message
                    _onResponse(event);
                    break;


                case 'keepAlive':
                    // Server is still alive :-)
                    break;
            }
        };

        return {
            toJson: function(task, data){
                var jsonArr = [];
                jsonArr = JSON.stringify({
                    task: task,
                    data: data,
                    uniqid: _uniqid,
                    key: _key
                });
                return jsonArr;
            },
            setUserId: function(userId){
                _userId = userId;
            },
            setApiKey: function(key){
                _key = key;
            },
            setUrl: function(url){
                _url = url;
            },
            connect: function(){
                _connect();
            },
            onSuccess: function(callback){
                _onSuccess = callback;
            },
            onError: function(callback){
                _onError = callback;
            },
            onResponse: function(callback){
                _onResponse = callback;
            },
            onDispatch: function(callback){
                _onDispatch = callback;
            },
            send: function(json){
                _send(json);
            }
        }
    });