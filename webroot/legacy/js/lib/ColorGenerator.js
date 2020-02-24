'use strict';

/**
 * Generates hex colors.
 *
 * The Colors are generated in HSV foramt, but the generate() function takes only saturation and value as parameters.
 * The value is automatically generated and depends on the amount of colors to be generated.
 *
 * @author Patrick Nawracay
 * @requires colr - https://github.com/stayradiated/colr
 * @example
 * <code><pre>
 *     var color_generator = new ColorGenerator(),
 *         colors = color_generator.generate(12, 90, 90);
 * </pre></code>
 */
var ColorGenerator = (function(){
    function ColorGenerator(){
        this.colr = new Colr();
    }

    /**
     * Static utility function to generate random numbers.
     *
     * @param min
     * @param max
     * @returns {number}
     */
    ColorGenerator.getRandomInt = function(min, max){
        return Math.floor(Math.random() * (max - min) + min);
    };

    /**
     * Generates the desired amount of colors and returns it as array.
     *
     * The hue is automatically generated out of the the given amount of colors which should be generated.
     *
     * @param amount [12] - The amount of colors to generate.
     * @param saturation [90] - Values from 0 to 100.
     * @param value [90] - Values from 0 to 100.
     * @returns {Array} - An array of hex colors.
     */
    ColorGenerator.prototype.generate = function(amount, saturation, value){
        var colors, color, step, current_hue, i;

        amount = amount != null ? amount : 12;
        saturation = saturation != null ? saturation : 90;
        value = value != null ? value : 90;

        step = current_hue = parseInt(360 / amount, 10);

        colors = [];
        for(i = 0; i < amount; i++){
            // colr.fromHsv accepts a hue value from 0 to 360.
            color = this.colr.fromHsv(current_hue, saturation, value).toHex();
            colors.push(color);

            current_hue += step;
        }

        return colors;
    };

    return ColorGenerator;
}).call(this);

