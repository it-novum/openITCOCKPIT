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

App.Controllers.CurrentstatereportsCreateHtmlReportController = Frontend.AppController.extend({
    components: ['Ajaxloader'],

    _initialize: function(){
        var self = this;
        $(document).on('click', '.group-result', function(){
            // Get unselected items in this group
            var unselected = $(this).nextUntil('.group-result').not('.result-selected');
            if(unselected.length){
                // Select all items in this group
                unselected.trigger('mouseup');
            }else{
                $(this).nextUntil('.group-result').each(function(){
                    // Deselect all items in this group
                    $('a.search-choice-close[data-option-array-index="' + $(this).data('option-array-index') + '"]').trigger('click');
                });
            }
        });
        $('.perfdataContainerShowDetails').click(function(){
            self.showHidePerformanceDetails(this);
        });
        self.renderPerfdataMeter();

    },
    renderPerfdataMeter: function(){
        var self = this;
        $('.perfdataContainer').each(function(){
            $(this).css('background', self.createBackgroundForPerfdataMeter(this.attributes));
        });
    },
    createBackgroundForPerfdataMeter: function(attributes){
        var background = 'none';

        if(!(attributes.min && attributes.current_value && attributes.warning && attributes.critical && attributes.min && attributes.max)){
            return background;
        }
        var linearGradientArray = ['to right'];
        var start = (attributes.min.value != "") ? attributes.min.value : 0;
        var end = (attributes.max.value != "") ? attributes.max.value : (attributes.critical.value != "") ? attributes.critical.value : 0;
        var currentValue = Number(attributes.current_value.value);
        var warningValue = Number(attributes.warning.value);
        var criticalValue = Number(attributes.critical.value);

        //if warning value < critical value, inverse
        if(!isNaN(warningValue) && !isNaN(criticalValue) && warningValue < criticalValue){
            var curValPosInPercent = currentValue / (end - start) * 100;
            curValPosInPercent = (curValPosInPercent > 100) ? 100 : curValPosInPercent;
            if((!isNaN(warningValue) && currentValue >= warningValue) &&
                (!isNaN(criticalValue) && currentValue < criticalValue)
            ){
                //if current state > warning and current state < critical
                linearGradientArray.push(
                    '#5CB85C 0%',
                    '#F0AD4E ' + curValPosInPercent + '%'
                );
            }else if((!isNaN(warningValue) && currentValue > warningValue) &&
                (!isNaN(criticalValue) && currentValue >= criticalValue)
            ){
                //if current state > warning and current state > critical
                linearGradientArray.push(
                    '#5CB85C 0%',
                    '#F0AD4E ' + (warningValue / (end - start) * 100) + '%',
                    '#D9534F ' + curValPosInPercent + '%'
                );
            }else if(currentValue < warningValue){
                linearGradientArray.push('#5CB85C ' + curValPosInPercent + '%');
            }
            //set white color for gradient for empty area
            if(curValPosInPercent > 0 && curValPosInPercent < 100){
                linearGradientArray.push('#ffffff ' + curValPosInPercent + '%');
            }
        }
        return 'linear-gradient(' + linearGradientArray.join(', ') + ')';
    },
    showHidePerformanceDetails: function(element){
        if($(element).hasClass('fa-plus-square-o')){
            $(element).removeClass('fa-plus-square-o').addClass('fa-minus-square-o');
            $('.' + $(element).attr('uuid')).removeClass('hidden');
        }else{
            $(element).removeClass('fa-minus-square-o').addClass('fa-plus-square-o');
            $('.' + $(element).attr('uuid')).addClass('hidden');
        }
    }
});
