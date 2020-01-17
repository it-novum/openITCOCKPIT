/**
 * http://stackoverflow.com/questions/3326650/console-is-undefined-error-for-internet-explorer
 */
(function () {
    "use strict";
    /*global window, Raphael, console */
    // union of Chrome, FF, IE, and Safari console methods
    var m = [
            "log", "info", "warn", "error", "debug", "trace", "dir", "group",
            "groupCollapsed", "groupEnd", "time", "timeEnd", "profile", "profileEnd",
            "dirxml", "assert", "count", "markTimeline", "timeStamp", "clear"
        ],
        index,
        noOpFunction = function () {
            return;
        };

    if (window && !window.console) {
        window.console = {};
    }
    // define undefined methods as no-ops to prevent errors
    for (index = 0; index < m.length; index += 1) {
        if (!window.console[m[index]]) {
            window.console[m[index]] = noOpFunction;
        }
    }
}());

/**
 * @license
 * Pielicious: donut-pie charting library, based on Raphaël JS by Dmitry Baranovskiy (http://raphaeljs.com)
 *
 * Copyright (c) 2014 Alexander Zagniotov
 * Licensed under the MIT (http://www.opensource.org/licenses/mit-license.php) license.
 */
(function () {
    "use strict";

    var RADIAN = (Math.PI / 180),
        WHITE_COLOR = "#ffffff",
        BOUNCE_EFFECT_NAME = "bounce",
        BACKOUT_EFFECT_NAME = "backOut",
        ELASTIC_EFFECT_NAME = "elastic",
        cos = Math.cos,
        sin = Math.sin,
        min = Math.min,
        max = Math.max,
        random = Math.random,
        ceil = Math.ceil,
        abs = Math.abs,
        is = Raphael.is;

    function Pielicious(paper, cx, cy, R1, opts) {
        opts = opts || {};

        var data = opts.data || [],
            wholePie = (data && data.length === 1) || false,
            slicedPie = !wholePie,
            gradient = (opts.gradient && is(opts.gradient, "object")) || false,
            gradientDarkness = (gradient && opts.gradient.darkness ? opts.gradient.darkness : 0),
            gradientLightness = (gradient && opts.gradient.lightness ? opts.gradient.lightness : 0),
            gradientDegrees = (gradient && opts.gradient.degrees ? abs(opts.gradient.degrees) : 180),
            colors = opts.colors || [],
            titles = opts.titles || [],
            handles = opts.handles || [],
            hrefs = opts.hrefs || [],
            threeD = (opts.threeD && is(opts.threeD, "object")) || false,
            height3d = (threeD && opts.threeD.height ? (abs(opts.threeD.height) > 100 ? 100 : abs(opts.threeD.height)) : 25),
            tilt3d = (threeD && opts.threeD.tilt ? (abs(opts.threeD.tilt) > 0.9 ? 0.9 : abs(opts.threeD.tilt)) : 0.5),
            donut = (opts.donut && is(opts.donut, "object")) || false,
            donutDiameter = (donut && opts.donut.diameter ? (abs(opts.donut.diameter) > 0.9 ? 0.9 : abs(opts.donut.diameter)) : 0.5),
            tiltDonut = (donut && opts.donut.tilt ? (abs(opts.donut.tilt) > 0.9 ? 0.9 : abs(opts.donut.tilt)) : false),
            legend = (opts.legend && is(opts.legend, "object")) || false,
            legendLabels = (legend && opts.legend.labels ? opts.legend.labels : []),
            legendXstart = (legend && opts.legend.x ? opts.legend.x : cx + R1 + 30),
            legendYstart = (legend && opts.legend.y ? opts.legend.y : cy - R1),
            legendEvents = (legend && opts.legend.events ? true : false),
            legendLabelXstart = legendXstart + 38,
            legendLabelYstart = legendYstart,
            fontSize = (legend && opts.legend.fontSize ? opts.legend.fontSize : "14"),
            fontFamily = (legend && opts.legend.fontFamily ? opts.legend.fontFamily : "Arial"),
            cursor = opts.cursor || "normal",
            marker = opts.marker || "circle",
            evolution = opts.evolution || false,
            animation = opts.animation || "",
            orientable = slicedPie ? (opts.orientation ? true : false) : false,
            orientation = (orientable && abs(opts.orientation) > 360 ? 360 : (orientable ? abs(opts.orientation) : 0)),
            shiftDistance = (threeD ? 15 : 10),
            total = 0,
            animationDelay = (slicedPie ? 600 : 1500),
            slices = paper.set(),
            markers = paper.set(),
            descriptions = paper.set(),
            index,
            bucket = [],
            startX = cx,
            startY = cy,
            terminalAngle = 0,
            initialAngle = terminalAngle,
            defaultOutlineRingThickness = 10,
            timeout,
            shuffler,
            currentBucket,
            Animator = function Animator(bucket, threeD, sliceAnimationOut, sliceAnimationIn, bordersAnimationOut, bordersAnimationIn) {
                if (!(this instanceof Animator)) {
                    return new Animator(bucket, threeD, sliceAnimationOut, sliceAnimationIn, bordersAnimationOut, bordersAnimationIn);
                }
                this.bucket = bucket;
                this.threeD = threeD;
                this.slice = bucket.slice;
                this.arc = bucket.arc;
                this.initialSideBorder = bucket.initialSideBorder;
                this.terminalSideBorder = bucket.terminalSideBorder;
                this.sliceAnimationOut = sliceAnimationOut;
                this.sliceAnimationIn = sliceAnimationIn;
                this.bordersAnimationOut = bordersAnimationOut;
                this.bordersAnimationIn = bordersAnimationIn;
            },

        // Adopted from https://bgrins.github.io/TinyColor/
            PieColor = function PieColor() {
                if (!(this instanceof PieColor)) {
                    return new PieColor();
                }
                var toHsl = function (color) {
                        var rgb = Raphael.getRGB(color),
                            r = rgb.r / 255,
                            g = rgb.g / 255,
                            b = rgb.b / 255,
                            maxValue = max(r, g, b),
                            minValue = min(r, g, b),
                            h,
                            s,
                            l = (maxValue + minValue) / 2,
                            d = maxValue - minValue;

                        if (maxValue === minValue) {
                            h = s = 0; // achromatic
                        } else {
                            s = l > 0.5 ? d / (2 - maxValue - minValue) : d / (maxValue + minValue);
                            if (maxValue === r) {
                                h = (g - b) / d + (g < b ? 6 : 0);
                            } else if (maxValue === g) {
                                h = (b - r) / d + 2;
                            } else if (maxValue === b) {
                                h = (r - g) / d + 4;
                            }
                            h /= 6;
                        }
                        return {h: h, s: s, l: l};
                    },

                    lighten = function (color, amount) {
                        //return calculateLightColor(color);
                        amount = (amount === 0 || amount < 0) ? 0 : (amount || 10);
                        var hsl = toHsl(color);
                        hsl.l += amount / 100;
                        hsl.l = min(1, max(0, hsl.l));

                        return Raphael.hsl2rgb(hsl.h, hsl.s, hsl.l).hex;
                    },

                    darken = function (color, amount) {
                        //return calculateDarkColor(color);
                        amount = (amount === 0 || amount < 0) ? 0 : (amount || 10);
                        var hsl = toHsl(color);
                        hsl.l -= amount / 100;
                        hsl.l = min(1, max(0, hsl.l));

                        return Raphael.hsl2rgb(hsl.h, hsl.s, hsl.l).hex;
                    };

                this.gradient = function (angle, color, darkAmount, lightAmount) {
                    return angle + "-" + darken(color, darkAmount) + "-" + lighten(color, lightAmount);
                };

                this.randomRgb = function (limit) {
                    var goldenRatioConjugate = 1.618033988749895,
                        randomColors = [],
                        hueStart = random();
                    for (index = 0; index < limit; index += 1) {
                        hueStart = (hueStart + goldenRatioConjugate) % 1;
                        randomColors.push(Raphael.hsb2rgb(hueStart, 0.5, 0.95).hex);
                    }
                    return randomColors;
                };
            },
            pieColor = new PieColor(),

            RaphaelConfigurator = function RaphaelConfigurator(paper) {
                if (!(this instanceof RaphaelConfigurator)) {
                    return new RaphaelConfigurator(paper);
                }
                var raphael = paper,
                    customAttribs = raphael.customAttributes || {},
                    calculateX = function (startX, R, angle) {
                        return startX + R * cos(angle * RADIAN);
                    },
                    calculateAngledX = function (startX, R, initialAngle, terminalAngle) {
                        return startX + R * cos((initialAngle + (terminalAngle - initialAngle)) * RADIAN);
                    },
                    calculateY = function (startY, R, angle) {
                        return startY + R * sin(angle * RADIAN);
                    },
                    calculateAngledY = function (startY, R, initialAngle, terminalAngle) {
                        return startY + R * sin((initialAngle + (terminalAngle - initialAngle)) * RADIAN);
                    };

                this.configure = function () {
                    customAttribs.slice = function (startX, startY, R1, initialAngle, terminalAngle) {
                        if (initialAngle === 0 && terminalAngle === 0) {
                            return [];
                        }
                        var R2 = (donut && !threeD) ? (tiltDonut ? R1 * tiltDonut : R1) : (threeD ? R1 * tilt3d : R1),
                            innerR1 = (R1 * donutDiameter),
                            innerR2 = (R2 * donutDiameter),
                            x1start = calculateX(startX, innerR1, initialAngle),
                            y1start = calculateY(startY, innerR2, initialAngle),
                            x1end = calculateX(startX, R1, initialAngle),
                            y1end = calculateY(startY, R2, initialAngle),
                            x2end = calculateX(startX, R1, terminalAngle),
                            y2end = calculateY(startY, R2, terminalAngle),
                            x2start = calculateAngledX(startX, innerR1, initialAngle, terminalAngle),
                            y2start = calculateAngledY(startY, innerR2, initialAngle, terminalAngle),
                            largeArcFlag = (abs(terminalAngle - initialAngle) > 180),
                            sweepFlagPositiveAngle = 1; // positive angle

                        if (donut && !threeD) {
                            if (slicedPie) {
                                return {
                                    path: [
                                        ["M", x1start, y1start ],
                                        ["L", x1end, y1end ],
                                        ["A", R1, R2, 0, +largeArcFlag, sweepFlagPositiveAngle, x2end, y2end ],
                                        ["L", x2start, y2start ],
                                        ["A", innerR1, innerR2, 0, +largeArcFlag, (sweepFlagPositiveAngle - 1), x1start, y1start ],
                                        ["z"]
                                    ]
                                };
                            }

                            if (wholePie) {
                                return {
                                    path: [
                                        ["M", startX, startY - R2 ],
                                        ["a", R1, R2, 0, +largeArcFlag, (sweepFlagPositiveAngle - 1), 1, 0 ],
                                        ["M", x1start - innerR1, y1start - innerR2 ],
                                        ["a", innerR1, innerR2, 0, +largeArcFlag, sweepFlagPositiveAngle, -1, 0 ]
                                    ]
                                };
                            }
                        }

                        if (slicedPie) {
                            return {
                                path: [
                                    ["M", startX, startY ],
                                    ["L", x1end, y1end ],
                                    ["A", R1, R2, 0, +largeArcFlag, sweepFlagPositiveAngle, x2end, y2end ],
                                    ["z"]
                                ]
                            };
                        }

                        if (wholePie) {
                            return {
                                path: [
                                    ["M", startX, startY - R2],
                                    ["a", R1, R2, 0, +largeArcFlag, (sweepFlagPositiveAngle - 1), 1, 0],
                                    ["z"]
                                ]
                            };
                        }
                    };

                    customAttribs.arc = function (startX, startY, R1, initialAngle, terminalAngle) {

                        if (initialAngle === 0 && terminalAngle === 0) {
                            return [];
                        }

                        if (shuffler.isResetArcTerminalAngle(initialAngle, terminalAngle)) {
                            terminalAngle = terminalAngle > 540 ? 540 : 180;
                        }

                        if (shuffler.isResetArcInitialAngle(initialAngle, terminalAngle)) {
                            initialAngle = terminalAngle > 360 ? 360 : initialAngle;
                        }

                        var R2 = threeD ? R1 * tilt3d : R1,
                            x1start = calculateX(startX, R1, initialAngle),
                            y1start = calculateY(startY, R2, initialAngle),
                            y1end = calculateY(startY + height3d, R2, initialAngle),
                            x2start = calculateAngledX(startX, R1, initialAngle, terminalAngle),
                            y2start = calculateAngledY(startY, R2, initialAngle, terminalAngle),
                            y2end = calculateAngledY(startY + height3d, R2, initialAngle, terminalAngle),
                            largeArcFlag = (abs(terminalAngle - initialAngle) > 180),
                            sweepFlagPositiveAngle = 1,
                            sweepFlagNegativeAngle = 0;

                        return {
                            path: [
                                ["M", x1start, y1start ],
                                ["L", x1start, y1end ], // draw down
                                ["A", R1, R2, 0, +largeArcFlag, sweepFlagPositiveAngle, x2start, y2end ],
                                ["L", x2start, y2start ],
                                ["A", R1, R2, 0, +largeArcFlag, sweepFlagNegativeAngle, x1start, y1start ],
                                ["z"]
                            ]
                        };
                    };

                    customAttribs.outline = function (startX, startY, R1, initialAngle, terminalAngle) {
                        var innerR1 = R1 + (threeD ? 3 : 1),
                            innerR2 = (threeD ? innerR1 * tilt3d : (donut && tiltDonut ? innerR1 * tiltDonut : innerR1)),
                            outlineThickness =
                                (threeD
                                    ? ((defaultOutlineRingThickness + defaultOutlineRingThickness * tilt3d) > 12
                                    ? 12
                                    : (defaultOutlineRingThickness + defaultOutlineRingThickness * tilt3d))
                                    : defaultOutlineRingThickness),
                            outerR1 = innerR1 + outlineThickness,
                            outerR2 = innerR2 + (threeD ? (outlineThickness / 2) : outlineThickness),
                            x1start = calculateX(startX, innerR1, initialAngle),
                            y1start = calculateY(startY, innerR2, initialAngle),
                            x1end = calculateX(startX, outerR1, initialAngle),
                            y1end = calculateY(startY, outerR2, initialAngle),
                            x2start = calculateAngledX(startX, innerR1, initialAngle, terminalAngle),
                            y2start = calculateAngledY(startY, innerR2, initialAngle, terminalAngle),
                            x2end = calculateAngledX(startX, outerR1, initialAngle, terminalAngle),
                            y2end = calculateAngledY(startY, outerR2, initialAngle, terminalAngle),
                            largeArcFlag = (abs(terminalAngle - initialAngle) > 180),
                            sweepFlagPositiveAngle = 1,
                            sweepFlagNegativeAngle = 0;

                        return {
                            path: [
                                ["M", x1start, y1start ],
                                ["L", x1end, y1end ],
                                ["A", outerR1, outerR2, 0, +largeArcFlag, sweepFlagPositiveAngle, x2end, y2end ],
                                ["L", x2start, y2start ],
                                ["A", innerR1, innerR2, 0, +largeArcFlag, sweepFlagNegativeAngle, x1start, y1start ],
                                ["z"]
                            ]
                        };
                    };

                    customAttribs.wall = function (startX, startY, R1, angle) {
                        if (angle === 0) {
                            return [];
                        }

                        var R2 = threeD ? R1 * tilt3d : R1,
                            x = calculateX(startX, R1, angle),
                            y = calculateY(startY, R2, angle);

                        return {
                            path: [
                                ["M", startX, startY ],
                                ["L", startX, startY + height3d ],
                                ["L", x, y + height3d ],
                                ["L", x, y ],
                                ["z"]
                            ]
                        };
                    };
                };
            },

            Shuffler = function Shuffler(isThreeDMode) {
                this.isThreeDMode = isThreeDMode;
                if (!(this instanceof Shuffler)) {
                    return new Shuffler(isThreeDMode);
                }
            };

        Shuffler.prototype = {
            shuffleBorders: function (bucket) {
                if (!this.isThreeDMode) {
                    return;
                }
                this.setZ(bucket.initialSibling.terminalAngle, function () {
                    bucket.initialSibling.terminalSideBorder.toFront();
                }, function () {
                    bucket.initialSibling.terminalSideBorder.toBack();
                });
                this.setZ(bucket.terminalSibling.initialAngle, function () {
                    bucket.terminalSibling.initialSideBorder.toBack();
                }, function () {
                    bucket.terminalSibling.initialSideBorder.toFront();
                });
                this.setZ(bucket.initialAngle, function () {
                    bucket.initialSideBorder.toBack();
                }, function () {
                    bucket.initialSideBorder.toFront();
                });
                this.setZ(bucket.terminalAngle, function () {
                    bucket.terminalSideBorder.toFront();
                }, function () {
                    bucket.terminalSideBorder.toBack();
                });
                this.cover();
            },

            setZ: function (angle, zIndexRightSideFunc, zIndexLeftSideFunc) {
                var quadrant = this.quadrant(angle);
                if (quadrant === 1 || quadrant === 4) {
                    if (is(zIndexRightSideFunc, "function")) {
                        zIndexRightSideFunc();
                    }
                } else if (quadrant === 2 || quadrant === 3) {
                    if (is(zIndexLeftSideFunc, "function")) {
                        zIndexLeftSideFunc();
                    }
                }
            },

            cover: function () {
                if (!this.isThreeDMode) {
                    return;
                }
                var quadrant;
                for (index = 0; index < data.length; index += 1) {
                    currentBucket = bucket[index];
                    quadrant = this.quadrant(currentBucket.initialAngle);
                    if (quadrant === 1 || quadrant === 2 || this.isResetArcInitialAngle(currentBucket.initialAngle, currentBucket.terminalAngle)) {
                        currentBucket.arc.toFront();
                    } else {
                        currentBucket.arc.toBack();
                    }
                }
                for (index = 0; index < data.length; index += 1) {
                    currentBucket = bucket[index];
                    currentBucket.slice.toFront();
                }
            },

            quadrant: function (angle) {
                var quadrantNumber;
                if (angle === 0 || angle === 360) {
                    return 1;
                }
                quadrantNumber = ceil(angle / 90) % 4;
                if (quadrantNumber === 0) {
                    return 4;
                }
                return quadrantNumber;
            },

            isResetArcInitialAngle: function (initialAngle, terminalAngle) {
                return (this.quadrant(initialAngle) > 2
                    && this.quadrant(terminalAngle) >= 1
                    && this.quadrant(terminalAngle) < 3);
            },

            isResetArcTerminalAngle: function (initialAngle, terminalAngle) {
                return (this.quadrant(initialAngle) < 3 && this.quadrant(terminalAngle) > 2);
            }
        };
        shuffler = new Shuffler(threeD);

        Animator.prototype = {
            bind: function () {
                var self = this,
                    sliceMouseOverHandler = function () {
                        self.slice.stop();
                        self.slice.animate(self.sliceAnimationOut);
                        if (self.threeD) {
                            self.arc.stop();
                            self.initialSideBorder.stop();
                            self.terminalSideBorder.stop();
                            self.arc.animateWith(self.slice, self.sliceAnimationOut, self.bordersAnimationOut.arc);
                            self.initialSideBorder.animateWith(self.slice, self.sliceAnimationOut, self.bordersAnimationOut.initialSideBorder);
                            self.terminalSideBorder.animateWith(self.slice, self.sliceAnimationOut, self.bordersAnimationOut.terminalSideBorder);
                        }
                        shuffler.shuffleBorders(self.bucket);
                    },
                    sliceMouseOutHandler = function () {
                        self.slice.animate(self.sliceAnimationIn);
                        if (self.threeD) {
                            self.arc.animateWith(self.slice, self.sliceAnimationIn, self.bordersAnimationIn.arc);
                            self.initialSideBorder.animateWith(self.slice, self.sliceAnimationIn, self.bordersAnimationIn.initialSideBorder);
                            self.terminalSideBorder.animateWith(self.slice, self.sliceAnimationIn, self.bordersAnimationIn.terminalSideBorder);
                        }
                        shuffler.cover();
                    };

                this.slice.mouseover(sliceMouseOverHandler);
                this.slice.mouseout(sliceMouseOutHandler);
                if (self.threeD) {
                    this.arc.mouseover(sliceMouseOverHandler);
                    this.arc.mouseout(sliceMouseOutHandler);
                }
            }
        };

        function fill(color) {
            if (!gradient) {
                return color;
            }
            return pieColor.gradient(gradientDegrees, color, gradientDarkness, gradientLightness);
        }

        function calculateDarkColor(color) {
            var c = Raphael.getRGB(color);
            var r = parseInt(c.r) - 36;
            var g = parseInt(c.g) - 30;
            var b = parseInt(c.b) - 24;
            return "#" + toHex(r) + toHex(g) + toHex(b);
        }

        function calculateLightColor(color) {
            var c = Raphael.getRGB(color);
            var r = parseInt(c.r) + 36;
            var g = parseInt(c.g) + 30;
            var b = parseInt(c.b) + 24;
            return "#" + toHex(r) + toHex(g) + toHex(b);
        }

        function toHex(N) {
            if (N == null)
                return "00";
            N = parseInt(N);
            if (N == 0 || isNaN(N))
                return "00";
            N = Math.max(0, N);
            N = Math.min(N, 255);
            N = Math.round(N);
            return "0123456789ABCDEF".charAt((N - N % 16) / 16)
                + "0123456789ABCDEF".charAt(N % 16);
        }

        function findWH(obj) {
            var curw  = 0;
            var curh = 0;
            // reset css
            obj.style.display = 'block';
            obj.style.visibility = 'hidden';
            // find width and height
            curw = parseInt(obj.offsetWidth);
            curh = parseInt(obj.offsetHeight);
            // restore css
            obj.style.display = 'none';
            obj.style.visibility = '';
            return {width : curw, height : curh};
        }

        function attr(shape, bucket, data) {
            var baseAttr = {
                //"stroke": calculateDarkColor(bucket.color),
                "stroke": (threeD)?calculateLightColor(bucket.color):"#cccccc",
                "stroke-width": (threeD)?0.4:1.0,
                "stroke-linejoin": "round",
                "fill-rule": "evenodd",
                "fill": fill(bucket.color),
                "title": bucket.title,
                "cursor": cursor
            };
            baseAttr[shape] = data;

            return baseAttr;
        }

        function bindEffectHandlers(bucket) {
            var shortAnimationDelay = animationDelay / 4,
                shiftOutCoordinates = [bucket.shiftX, bucket.shiftY, R1, bucket.initialAngle, bucket.terminalAngle],
                startCoordinates = [bucket.startX, bucket.startY, R1, bucket.initialAngle, bucket.terminalAngle],
                scaleOut = {transform: "s1.1 1.1 " + startX + " " + startY},
                scaleNormal = {transform: "s1 1 " + startX + " " + startY},
                transformOut = {transform: "T" + (bucket.shiftX - cx) + ", " + (bucket.shiftY - cy)},
                transformNormal = {transform: "T 0, 0"},
                shiftOut = Raphael.animation(transformOut, shortAnimationDelay),
                shiftIn = Raphael.animation(transformNormal, shortAnimationDelay);

            if (animation === "shift-fast") {
                new Animator(bucket, threeD, shiftOut, shiftIn,
                    {
                        arc: shiftOut,
                        initialSideBorder: shiftOut,
                        terminalSideBorder: shiftOut
                    },
                    {
                        arc: shiftIn,
                        initialSideBorder: shiftIn,
                        terminalSideBorder: shiftIn
                    }).bind();

            } else if (animation === ELASTIC_EFFECT_NAME) {
                shiftOut = Raphael.animation(transformOut, animationDelay, ELASTIC_EFFECT_NAME);
                shiftIn = Raphael.animation(transformNormal, animationDelay, ELASTIC_EFFECT_NAME);
                new Animator(bucket, threeD, shiftOut, shiftIn,
                    {
                        arc: shiftOut,
                        initialSideBorder: shiftOut,
                        terminalSideBorder: shiftOut
                    },
                    {
                        arc: shiftIn,
                        initialSideBorder: shiftIn,
                        terminalSideBorder: shiftIn
                    }).bind();

            } else if (animation === "shift-slow") {
                shiftOut = Raphael.animation(transformOut, animationDelay);
                shiftIn = Raphael.animation(transformNormal, animationDelay);
                new Animator(bucket, threeD, shiftOut, shiftIn,
                    {
                        arc: shiftOut,
                        initialSideBorder: shiftOut,
                        terminalSideBorder: shiftOut
                    },
                    {
                        arc: shiftIn,
                        initialSideBorder: shiftIn,
                        terminalSideBorder: shiftIn
                    }).bind();

            } else if (animation === "shift-bounce") {
                new Animator(bucket, threeD,
                    Raphael.animation({slice: shiftOutCoordinates}, shortAnimationDelay),
                    Raphael.animation({slice: startCoordinates}, animationDelay, BOUNCE_EFFECT_NAME),
                    {
                        arc: Raphael.animation({arc: shiftOutCoordinates}, shortAnimationDelay),
                        initialSideBorder: Raphael.animation({wall: [bucket.shiftX, bucket.shiftY, R1, bucket.initialAngle]}, shortAnimationDelay),
                        terminalSideBorder: Raphael.animation({wall: [bucket.shiftX, bucket.shiftY, R1, bucket.terminalAngle]}, shortAnimationDelay)
                    },
                    {
                        arc: Raphael.animation({arc: startCoordinates}, animationDelay, BOUNCE_EFFECT_NAME),
                        initialSideBorder: Raphael.animation({wall: [bucket.startX, bucket.startY, R1, bucket.initialAngle]}, animationDelay, BOUNCE_EFFECT_NAME),
                        terminalSideBorder: Raphael.animation({wall: [bucket.startX, bucket.startY, R1, bucket.terminalAngle]}, animationDelay, BOUNCE_EFFECT_NAME)
                    }).bind();
            } else if (animation === "scale") {
                new Animator(bucket, threeD,
                    scaleOut,
                    scaleNormal,
                    {
                        arc: scaleOut,
                        initialSideBorder: scaleOut,
                        terminalSideBorder: scaleOut
                    },
                    {
                        arc: scaleNormal,
                        initialSideBorder: scaleNormal,
                        terminalSideBorder: scaleNormal
                    }).bind();
            } else if (animation === "scale-bounce") {
                new Animator(bucket, threeD,
                    scaleOut,
                    Raphael.animation(scaleNormal, animationDelay, BOUNCE_EFFECT_NAME),
                    {
                        arc: scaleOut,
                        initialSideBorder: scaleOut,
                        terminalSideBorder: scaleOut
                    },
                    {
                        arc: Raphael.animation(scaleNormal, animationDelay, BOUNCE_EFFECT_NAME),
                        initialSideBorder: Raphael.animation(scaleNormal, animationDelay, BOUNCE_EFFECT_NAME),
                        terminalSideBorder: Raphael.animation(scaleNormal, animationDelay, BOUNCE_EFFECT_NAME)
                    }).bind();
            } else if (animation === "outline") {
                bucket.slice.mouseover(function () {
                    bucket.slice.outline.show();
                    bucket.slice.outline.toFront();
                });

                bucket.slice.mouseout(function () {
                    bucket.slice.outline.hide();
                });
            } else if (animation && animation !== "") {
                console.error("Unknown hover effect name: " + animation);
            }
        }

        function renderChartLegend(bucket) {
            if (bucket.label === "") {
                return;
            }
            legendLabelYstart += 10;
            var text,
                radius = 6.5,
                markerElement = null,
                markerAttrs = {"title": bucket.label, "fill": bucket.color, "fill-rule": "nonzero", "stroke": WHITE_COLOR, "stroke-width": "0.1", "cursor": cursor};

            if (marker === "rect") {
                markerElement = paper.path("M " + legendXstart + ", " + legendYstart + " l 28,0  0,16  -28,0  0,-16z");
            } else if (marker === "circle") {
                markerElement = paper.circle(legendXstart + (2 * radius), legendYstart + radius, radius);
                markerAttrs = {
                    stroke: "none",
                    fill: "180-" + calculateDarkColor(bucket.color) + "-" + calculateLightColor(bucket.color)
                };
            } else if (marker === "ellipse") {
                radius = 10;
                markerElement = paper.ellipse(legendXstart + (2 * radius), legendYstart + radius, 1.25 * radius, radius * 0.75);
            } else if (marker === "square") {
                markerElement = paper.path("M " + legendXstart + ", " + legendYstart + " l 16,0  0,16  -16,0  0,-16z");
            }
            if (markerElement) {
                markerElement.attr(markerAttrs);
                markerElement.handle = bucket.handle;
                markers.push(markerElement);
            }

            text = paper.text(legendLabelXstart, legendLabelYstart, bucket.label);
            text.attr({
                "title": bucket.label,
                "font-family": fontFamily,
                "font-weight": "normal",
                "fill": "#474747",
                "cursor": cursor,
                "font-size": fontSize,
                "text-anchor": "start",
                "href": bucket.href
            });
            text.handle = bucket.handle;
            descriptions.push(text);

            legendYstart += 30;
            legendLabelYstart = legendYstart;
        }




        function calculateTotal() {
            for (index = 0; index < data.length; index += 1) {
                total += data[index];
            }
        }

        function fillDataBuckets() {
            var currentSliceOutline,
                currentValue,
                currentColor,
                currentLabel,
                currentTitle,
                currentHref,
                currentHandle,
                currentSliceAngle,
                currentSliceShiftX,
                currentSliceShiftY;
            colors = colors.length > 0 ? colors : pieColor.randomRgb(data.length);
            for (index = 0; index < data.length; index += 1) {
                currentValue = data[index] || 0;
                currentColor = colors[index];
                currentLabel = legendLabels[index] || "";
                currentTitle = titles[index] || "";
                currentHref = hrefs[index] || "";
                currentHandle = handles[index] || "";
                initialAngle = terminalAngle;
                if (!index) {
                    initialAngle = orientation;
                }
                currentSliceAngle = 360 * currentValue / total;
                terminalAngle = initialAngle + currentSliceAngle;
                currentSliceShiftX = startX + shiftDistance * cos((initialAngle + (terminalAngle - initialAngle) / 2) * RADIAN);
                currentSliceShiftY = startY + shiftDistance * sin((initialAngle + (terminalAngle - initialAngle) / 2) * RADIAN);

                bucket[index] = {};
                bucket[index].color = currentColor;
                bucket[index].label = currentLabel;
                bucket[index].title = currentTitle;
                bucket[index].href = currentHref;
                bucket[index].handle = currentHandle;
                bucket[index].initialAngle = initialAngle;
                bucket[index].terminalAngle = terminalAngle;
                bucket[index].startX = startX;
                bucket[index].startY = startY;
                bucket[index].shiftX = currentSliceShiftX;
                bucket[index].shiftY = currentSliceShiftY;
                bucket[index].sliceOrigin = [startX, startY, R1, initialAngle, terminalAngle];
                bucket[index].sliceOriginZero = [startX, startY, R1, 0, 0];

                if (threeD) {
                    bucket[index].arcOrigin = bucket[index].sliceOrigin;
                    bucket[index].initialSideBorderOrigin = [startX, startY, R1, initialAngle];
                    bucket[index].terminalSideBorderOrigin = [startX, startY, R1, terminalAngle];
                    bucket[index].arcOriginZero = bucket[index].sliceOriginZero;
                    bucket[index].initialSideBorderOriginZero = [startX, startY, R1, 0];
                    bucket[index].terminalSideBorderOriginZero = [startX, startY, R1, 0];
                }

                currentBucket = bucket[index];
                if (threeD) {
                    if (slicedPie) {
                        bucket[index].initialSideBorder = paper.path().attr(attr("wall", currentBucket, (evolution ? currentBucket.initialSideBorderOriginZero : currentBucket.initialSideBorderOrigin)));
                        bucket[index].terminalSideBorder = paper.path().attr(attr("wall", currentBucket, (evolution ? currentBucket.terminalSideBorderOriginZero : currentBucket.terminalSideBorderOrigin)));
                    }
                    bucket[index].arc = paper.path().attr(attr("arc", currentBucket, (evolution ? currentBucket.arcOriginZero : currentBucket.arcOrigin)));
                }
                bucket[index].slice = paper.path().attr(attr("slice", currentBucket, (evolution ? currentBucket.sliceOriginZero : currentBucket.sliceOrigin)));
                bucket[index].slice.handle = bucket[index].handle;
                if (slicedPie && animation.indexOf("outline") !== -1) {
                    currentSliceOutline = paper.path().attr(attr("outline", currentBucket, bucket[index].sliceOrigin));
                    bucket[index].slice.outline = currentSliceOutline;
                    currentSliceOutline.hide();
                }
                slices.push(bucket[index].slice);
                if (slicedPie) {
                    bindEffectHandlers(bucket[index]);
                }
                renderChartLegend(bucket[index]);
            }
        }

        function setSiblings() {
            var previousIndex, nextIndex;
            for (index = 0; index < data.length; index += 1) {
                previousIndex = (index - 1 < 0 ? data.length - 1 : index - 1);
                nextIndex = (index + 1 > data.length - 1 ? 0 : index + 1);
                bucket[index].initialSibling = bucket[previousIndex];
                bucket[index].terminalSibling = bucket[nextIndex];
                //console.log(bucket[index].terminalSibling.handle + " <= " + bucket[index].handle + " => " + bucket[index].initialSibling.handle);
            }
        }

        function setLegendEvents() {
            if (legendEvents && animation) {
                var events;
                for (index = 0; index < slices.items.length; index += 1) {
                    events = slices.items[index].events;
                    if (is(events, "undefined")) {
                        break;
                    }
                    if (events[0] && events[0].name === "mouseover" && is(events[0].f, "function")) {
                        markers.items[index].mouseover(events[0].f);
                        descriptions.items[index].mouseover(events[0].f);
                    }
                    if (events[1] && events[1].name === "mouseout" && is(events[1].f, "function")) {
                        markers.items[index].mouseout(events[1].f);
                        descriptions.items[index].mouseout(events[1].f);
                    }
                }
            }
        }

        function startEvolution() {
            if (evolution) {
                timeout = window.setTimeout(function () {
                    for (index = 0; index < bucket.length; index += 1) {
                        if (threeD) {
                            bucket[index].arc.animate({arc: bucket[index].arcOrigin}, animationDelay, BACKOUT_EFFECT_NAME);
                            if (slicedPie) {
                                bucket[index].initialSideBorder.animate({wall: bucket[index].initialSideBorderOrigin}, animationDelay, BACKOUT_EFFECT_NAME);
                                bucket[index].terminalSideBorder.animate({wall: bucket[index].terminalSideBorderOrigin}, animationDelay, BACKOUT_EFFECT_NAME);
                            }
                        }
                        bucket[index].slice.animate({slice: bucket[index].sliceOrigin}, animationDelay, BACKOUT_EFFECT_NAME);
                    }

                    window.clearTimeout(timeout);
                    shuffler.cover();
                }, 200);
            } else {
                shuffler.cover();
            }
        }

        new RaphaelConfigurator(paper).configure();
        calculateTotal();
        fillDataBuckets();
        setSiblings();
        setLegendEvents();
        startEvolution();

        return {slices: slices.items, markers: markers.items, descriptions: descriptions.items};
    }

    Raphael.fn.pielicious = function (cx, cy, R, opts) {
        return new Pielicious(this, cx, cy, R, opts);
    };
}());

