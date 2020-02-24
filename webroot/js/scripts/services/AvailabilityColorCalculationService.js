angular.module('openITCOCKPIT')
    .service('AvailabilityColorCalculationService', function(){
        var defaultColors = {
            green: '#449D44',
            orange: '#eaba0b',
            red: '#C9302C'
        };

        //from #ff0000 to array [255, 0, 0]
        var hexToRgb = function(hex){
            var red = parseInt(hex.substr(1, 2), 16);
            var green = parseInt(hex.substr(3, 2), 16);
            var blue = parseInt(hex.substr(5, 2), 16);
            return [red, green, blue];
        };

        var rgbToHex = function(red, green, blue){
            red = Number(red).toString(16);
            if(red.length < 2){
                red = "0" + red;
            }
            green = Number(green).toString(16);
            if(green.length < 2){
                green = "0" + green;
            }
            blue = Number(blue).toString(16);
            if(blue.length < 2){
                blue = "0" + blue;
            }
            return red + green + blue;
        };



        return {
            getBackgroundColor: function(currentAvailabilityInPercent){
                var currentAvailabilityInPercentFloat = (currentAvailabilityInPercent / 100).toFixed(3);
                var weight1 = (1 - currentAvailabilityInPercentFloat).toFixed(3);
                var weight2 = currentAvailabilityInPercentFloat;

                if(currentAvailabilityInPercent >= 50){
                    var colorFrom = hexToRgb(defaultColors.orange);
                    var colorTo = hexToRgb(defaultColors.green);

                }else{
                    var colorFrom = hexToRgb(defaultColors.red);
                    var colorTo = hexToRgb(defaultColors.orange);
                }


                var colors = [
                    Math.round(colorFrom[0] * weight1 + colorTo[0] * weight2),
                    Math.round(colorFrom[1] * weight1 + colorTo[1] * weight2),
                    Math.round(colorFrom[2] * weight1 + colorTo[2] * weight2)
                ];

                return '#' + rgbToHex(colors[0], colors[1], colors[2]);
            }
        }
    });