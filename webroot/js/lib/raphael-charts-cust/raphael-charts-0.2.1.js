/*
 * Raphael Charts plugin - version 0.2.1
 * Copyright (c) 2010 Boris Kuzmic (boris.kuzmic@gmail.com)
 * Licensed under the MIT (http://www.opensource.org/licenses/mit-license.php) license.
 *
 */

(function() {
	Raphael.fn.pie = function(canvasWidth, canvasHeight, R1, R2, values, options) {
        var paper = this;
        var rootElement = this.canvas.parentNode;
        options = options || {};
        var o = {
            cx : canvasWidth / 2,
            cy : canvasHeight / 2,
            R1 : R1,
            R2 : R2,
            val : values,
            numberOfValues : values.length,
            total : 0,
            colors : options.colors || [],
            darkColors : [],
            lightColors: [],
            show3d : options.show3d || false,
            size3d : options.size3d || 15,
            animation : options.animation || false,
            explode : options.explode || false,
            tooltip : options.tooltip || false,
            labels : options.labels || [],
            backgroundFill : options.backgroundFill || [ "#fff", "#fff" ],
            legend: options.legend || false,
            legendLineColor: options.legendLineColor || "#000",
            legendTextColor: options.legendTextColor || "#000",
            legendFont: options.legendFont || "10px Arial",
            legendContainerCssClass: options.legendContainerCssClass || "raphael-charts-legend-container",
            legendLabelCssClass: options.legendLabelCssClass || "raphael-charts-legend-label",
            legendShowValues: options.legendShowValues || "" // possible values are "normal" and "percentage"
        };

        var slices = [];

        function slice() {
            this.side1 = "";
            this.side2 = "";
            this.border = "";
            this.top = "";
            this.tx = 0;
            this.ty = 0;
            this.txm = 0;
            this.tym = 0;
            this.alphaM = 0;
        };

        function slicePart() {
            this.paths = [];
            this.params = [];
            this.indexes = [];
        };

        calculateTotalAndUsedColors();

        var explodeFactor = 4;

        var aTotal = 360 / o.total; // total in scale of degrees
        var rad = Math.PI / 180;

        var valSum = 0; // will hold sum of all values

        var draw3dBorder = true;
        var bPart = new slicePart();
        var sPart = new slicePart();
        var topPart = new slicePart();

        // first point in drawing slice
        var x = o.cx + o.R1;
        var y = o.cy;


        if (!o.show3d) {
            o.R2 = o.R1;
            explodeFactor = 8;
        }

        if (o.numberOfValues == 1) {
            s = new slice();
            var e = paper.ellipse(o.cx, o.cy, o.R1, o.R2);
            e.attr({stroke: "none", fill: o.colors[0]});
            s.top = e;
            if (o.show3d) {
                p = createPathForBorderPart(o.cx - o.R1, o.cy, o.cx + o.R1, o.cy, 0);
                borderParams = {stroke : "none", fill: "90-" + o.darkColors[0] + "-" + o.colors[0]};
                s.border = createPart(p, borderParams);
            }
            slices[0] = s;
        } else {
            for ( var i = 0; i < o.numberOfValues; i++) {
                if(o.val[i] === 0){
                    continue;
                }
                    s = new slice();
                    valSum += o.val[i];

                    sideParams = {stroke: "none", fill: o.darkColors[i]};
                    borderParams = {stroke: "none", fill: "90-" + o.darkColors[i] + "-" + o.colors[i]};
                    topParams = {stroke: "#ccc", fill: "180-" + o.darkColors[i] + "-" + o.lightColors[i]};

                    var endX = x;
                    var endY = y;

                    alpha = aTotal * valSum;


                    var largeAngleFlag = calculateLargeArcFlag(o.val[i], aTotal);

                    x = o.cx + o.R1 * Math.cos(alpha * rad);
                    y = o.cy + o.R2 * Math.sin(alpha * rad);
                    if(values.filter(function(v){ return (v > 0);}).length === 1){
                        y-=0.00001;
                    }

                    // calculate translation coordinates for explode effect
                    alphaM = aTotal * (valSum - (o.val[i] / 2));
                    x4 = o.cx + (o.R1 / explodeFactor) * Math.cos(alphaM * rad);
                    y4 = o.cy + (o.R2 / explodeFactor) * Math.sin(alphaM * rad);
                    xm = o.cx + o.R1 * Math.cos(alphaM * rad);
                    ym = o.cy + o.R2 * Math.sin(alphaM * rad);

                    s.tx = x4;
                    s.ty = y4;
                    s.txm = xm;
                    s.tym = ym;
                    s.alphaM = alphaM;

                    if (o.show3d) {
                        topParams.stroke = "none";

                        var side1Path = createPathFromPoint(endX,endY);
                        var side2Path = createPathFromPoint(x,y);

                        if (alpha >= 90 && alpha <= 270) {
                            s.side1 = createPartAndSendToBack(side1Path, sideParams);
                        } else {
                            if (alpha > 180 && alpha <= 360) {
                                if (aTotal * (valSum - o.val[i]) < 270) {
                                    s.side2 = createPartAndSendToBack(side1Path, sideParams);
                                }
                            }
                            if (alpha < 90) {
                                s.side1 = createPart(side2Path, sideParams);
                            } else {
                                storePartForLater(sPart, side2Path, sideParams, i);
                            }
                        }

                        if (draw3dBorder) {
                            var borderPath = createPathForBorderPart(x, y, endX, endY, largeAngleFlag);

                            if (alpha <= 90) {
                                s.border = createPart(borderPath, borderParams);
                            } else {
                                if (alpha >= 180) {
                                    borderPath = createPathForBorderPart(o.cx - o.R1, o.cy, endX, endY, 0);
                                    s.border = createPart(borderPath, borderParams);
                                    draw3dBorder = false;
                                } else {
                                    storePartForLater(bPart, borderPath, borderParams, i);
                                }
                            }
                        }
                    }
                    topPath = createPathForTopPart(x, y, endX, endY, largeAngleFlag);

                    storePartForLater(topPart, topPath, topParams, i);
                    slices[i] = s;
                }


            drawSlicePart(sPart, "side1");
            drawSlicePart(bPart, "border");
            drawSlicePart(topPart, "top");
        }

		function drawSlicePart(slicePart, side) {
			for ( var i = slicePart.paths.length - 1; i >= 0; i--) {
				s = slices[slicePart.indexes[i]];
				if (side == "side1") {
					s[side] = createPartAndSendToBack(slicePart.paths[i], slicePart.params[i]);
				} else {
					s[side] = createPart(slicePart.paths[i], slicePart.params[i]);
				}
				slices[slicePart.indexes[i]] = s;
			}
		}

		function storePartForLater(slicePart, path, params, index) {
            slicePart.paths.push(path);
            slicePart.params.push(params);
            slicePart.indexes.push(index);

		}

		function createPathFromPoint(startX, startY) {
			return ["M", startX, startY,
			        "L", o.cx, o.cy,
			        "L", o.cx, o.cy + o.size3d,
			        "L", startX, startY + o.size3d, "z"].join(",");
		}

		function createPathForBorderPart(startX, startY, endX, endY, largeArcFlag) {
			return ["M", startX, startY,
			        "A", o.R1, o.R2, "0", largeArcFlag, "0", endX, endY,
			        "L", endX, endY + o.size3d,
			        "A", o.R1, o.R2, "0", largeArcFlag, "1", startX, startY + o.size3d,
			        "L", startX, startY, "z" ].join(",");
		}

		function createPathForTopPart(startX, startY, endX, endY, largeArcFlag) {
			return ["M", o.cx, o.cy,
			        "L", startX, startY,
			        "A", o.R1, o.R2, "0", largeArcFlag, "0", endX, endY,
			        "L", o.cx, o.cy, "z" ].join(",");
		}


		function createPartAndSendToBack(path, params) {
			part = createPart(path, params);
			part.toBack();
			return part;
		}

		function createPart(path, params) {
			part = paper.path(path);
			part.attr(params);
			return part;
		}

		// draw background
		var background = paper.rect(0, 0, canvasWidth, canvasHeight);
		var backgroundParams = (o.backgroundFill[0] == o.backgroundFill[1]) ?
				{stroke : "none", fill : o.backgroundFill[0]} :
				{stroke : "none", fill : "90-" + o.backgroundFill[0] + "-" + o.backgroundFill[1]};
		background.attr(backgroundParams);
		background.toBack();
		if (o.animation && values.filter(function(v){ return (v > 0);}).length > 1) {
			for ( var i = 0; i <  o.numberOfValues; i++) {
                if(values[i] === 0){
                    continue;
                }
				slices[i].top.num = i;
				slices[i].top.mouseover(function() {
					if (o.tooltip)
						showTooltip(this.num, true);
					highlightOn(slices[this.num]);
					animateSliceOut(slices[this.num], (o.animation.speed !== undefined)?o.animation.speed:1000);
				}).mouseout(function() {
					if (o.tooltip)
						showTooltip(this.num, false);
					highlightOff(slices[this.num]);
					animateSliceIn(slices[this.num], ((o.animation.speed !== undefined)?o.animation.speed:1000)/4);
				});
			}
		}

		if (o.explode && !o.animation) {
			for ( var i = 0; i < o.numberOfValues; i++) {
                if(slices[i] > 0){
                    explodeSlice(slices[i]);
                }

			}
		}

    if (o.legend) {
      // create legend container elements
      var legendContainerDiv = document.createElement("div");
      var legendLabels = document.createElement("ul");
      legendContainerDiv.className = o.legendContainerCssClass;
      rootElement.parentNode.insertBefore(legendContainerDiv, rootElement.nextSibling);
      legendContainerDiv.appendChild(legendLabels);
      for (var i = 0; i < o.numberOfValues; i++) {
        drawLegendLabels(slices[i], i, legendLabels, valSum);
      }
    }

		function showTooltip(num, show) {
			var tooltip = document.getElementById('tooltip');
			if (show) {
				var s = slices[num];
				var v = Math.round((o.val[num] / o.total) * 100) + "%";
				var lbl = o.labels[num] ? o.labels[num] + " - " : "";
				lbl = lbl + v;

				var cur = findPos(paper.canvas);

				// adjust values for left and top position
				cur.left = cur.left + o.cx - o.R1;
				cur.top = cur.top + o.cy - o.R2;

				var wh = findWH(tooltip);

				var dirx = o.cx - s.txm;
				var pw = (dirx < 0) ? 0 : wh.width + 5;
				var xt = cur.left + Math.round(o.R1 - dirx) - pw;

				var diry = o.cy - s.tym;
				var ph = (diry < 0) ? 0 : wh.height + 5;
				var yt = cur.top + Math.round(o.R2 - diry) - ph;

				tooltip.style.borderColor = o.colors[num];
                tooltip.innerHTML = lbl;
				tooltip.style.left = xt + "px";
				tooltip.style.top = yt + "px";
				tooltip.style.display = 'block';
			} else {
				tooltip.style.display = 'none';
			}
		}

		function highlightOn(s) {
			//s.top.attr("fill", o.lightColors[s.top.num]);
			s.top.attr("fill", "90-" + o.lightColors[s.top.num] + "-" + o.colors[s.top.num]);
		}

		function highlightOff(s) {
			//s.top.attr("fill", o.colors[s.top.num]);
			s.top.attr("fill", "180-" + o.darkColors[s.top.num] + "-" + o.lightColors[s.top.num]);
		}

		function animateSliceOut(s, speed) {
			var translateX = s.tx - o.cx;
			var translateY = s.ty - o.cy;
			animateSlice(s, translateX, translateY, speed, o.animation.easingOut);
		}

		function animateSliceIn(s, speed) {
			s.top.stop();
			if (s.side1) s.side1.stop();
			if (s.side2) s.side2.stop();
			if (s.border) s.border.stop();
			animateSlice(s, 0, 0, speed, o.animation.easingIn);
		}

		function animateSlice(s, xcord, ycord, speed, easing) {
			var sliceAnimation = Raphael.animation({
                transform : "T" + xcord + "," + ycord,
                easing: easing
            },
            speed);
			if (s.side1) {
				s.top.animate(sliceAnimation);
				s.side1.animateWith(s.top, sliceAnimation, sliceAnimation);
				if (s.side2) s.side2.animateWith(s.top, sliceAnimation, sliceAnimation);
				if (s.border) s.border.animateWith(s.top, sliceAnimation, sliceAnimation);
			} else {
				s.top.animate(sliceAnimation);
			}
		}

		function explodeSlice(s) {
			var translateX = s.tx - o.cx;
			var translateY = s.ty - o.cy;
			(s.s1) ? s.s1.transform("T" + translateX + "," +translateY) : false;
			(s.s2) ? s.s2.transform("T" + translateX + "," +translateY)  : false;
			(s.s3) ? s.s3.transform("T" + translateX + "," +translateY)  : false;
			s.top.transform("T" + translateX + "," +translateY);
		}

    function drawLegendLabels(s, sliceIndex, legendLabels, valSum) {
      var legendLabel = document.createElement("li");
      legendLabel.className = o.legendLabelCssClass;
      var legendLabelText = document.createElement("span");
      var legendValue = "";
      if (o.legendShowValues == "normal") {
        legendValue = " (" + o.val[i] + ")";
      } else if (o.legendShowValues == "percentage") {
        legendValue = " (" + Math.round((o.val[i] / valSum)*100) + "%)";
      }
      legendLabelText.appendChild(document.createTextNode(" " + o.labels[sliceIndex] + legendValue));
      legendLabel.appendChild(legendLabelText);
      legendLabels.appendChild(legendLabel);

      var lwh = findWH(legendLabel);
      legendLabel.style.display = 'block';
      lwh.height = lwh.height - 1;
      var legendColor = Raphael(legendLabel, lwh.height, lwh.height);
      legendColor.circle(lwh.height/2, lwh.height/2, lwh.height/2).attr(
        {stroke: "none",
         fill: "180-" + o.darkColors[sliceIndex] + "-" + o.lightColors[sliceIndex]});
      legendColor.canvas.style.top = '1px';
      legendColor.canvas.style.display = 'inline';
      legendColor.canvas.style.zoom = '1';
      legendColor.canvas.style.overflow = 'visible';
      // only draw legend lines and text if pie is not exploded or animated
      if (!o.explode && !o.animation) {
        if(s !== undefined){
            var xLineEnd = o.cx + (o.R1 + 20) * Math.cos(s.alphaM * Math.PI / 180);
            var yLineEnd = o.cy + (o.R2 + 20) * Math.sin(s.alphaM * Math.PI / 180);

            var legendLine = ["M", s.txm, s.tym, "L", xLineEnd, yLineEnd].join(",");
            createPart(legendLine, {stroke : o.legendLineColor});
            paper.circle(xLineEnd, yLineEnd, 3).attr({stroke: "none", fill: o.legendLineColor});

            var legendText = paper.text(Math.round(xLineEnd), Math.round(yLineEnd), o.labels[sliceIndex]);
            legendText.attr("fill", o.legendTextColor);
            legendText.attr("font", o.legendFont);
            // calculate offset of text based on pie center and line start
            var legendTextBox = legendText.getBBox();
            var dirx = o.cx - s.txm;
            var pw = (dirx >= 0) ? 5 : - (legendTextBox.width + 5);
            var diry = o.cy - s.tym;
            var ph = (diry >= 0) ? 0 : -legendTextBox.height;
            // move textbox to fit line position
            legendText.attr("x", legendTextBox.x - pw);
            legendText.attr("y", legendTextBox.y - ph);
        }
      }
    }

		function calculateLargeArcFlag(value, total) {
			return (total * value) < 180 ? 0 : 1;
		}

		function calculateTotalAndUsedColors() {
			Raphael.getColor.reset();
			for ( var i = 0; i < o.numberOfValues; i++) {
                if(o.val[i] > 0){
                    o.total += o.val[i];
                }
				(o.colors[i] === undefined) ? o.colors[i] = Raphael.getColor() : false;
				o.darkColors[i] = calculateDarkColor(o.colors[i]);
				o.lightColors[i] = calculateLightColor(o.colors[i]);
			}
		}
	};

	Raphael.fn.bar = function(cx, cy, values, options) {
		var paper = this;
        options = options || {};
        var o = {
            val: values,
            numberOfValues: values.length,
            max: 0,
            colors: options.colors || [],
            darkColors: [],
            lightColors: [],
            show3d: options.show3d || false,
            size3d: options.size3d || -1,
            horizontal: options.horizontal || false,
            tooltip: options.tooltip || false,
            labels: options.labels || [],
            backgroundFill: options.backgroundFill || ["#fff", "#fff"]
        };
        var bars = [];
        calculateMaxValueAndUsedColors();
        var padding = 5;
        var np = 0;
        var maxp = 0;
        var x = y = w = h = 0;
        var grad_angle = 180;
        if (o.horizontal) {
            grad_angle = 90;
            np = (cy - padding * 2) / o.numberOfValues;
            if (o.size3d == -1) o.size3d = np / 4;
            maxp = cx - o.size3d - padding * 3;
            y = padding + o.size3d;
            x = padding;
            h = np / 1.5;
        } else {
            np = (cx - padding * 2) / o.numberOfValues;
            if (o.size3d == -1) o.size3d = np / 4;
            maxp = cy - o.size3d - padding * 2;
            x = padding;
            w = np / 1.5;
        }
        for (var i = 0; i < o.numberOfValues; i++) {
            topParams = {
            stroke: "none",
                "stroke-width": 0.5,
                fill: o.colors[i]
            };
            sideParams = {
                 stroke: "none",
                "stroke-width": 0.5,
                fill: o.darkColors[i]
            };
            frontParams = {
                 stroke: "none",
                "stroke-width": 0.5,
                fill: grad_angle + "-" + o.darkColors[i] + "-" + o.colors[i]
            };
            if (o.horizontal) {
                w = (maxp / o.max) * o.val[i];
            } else {
                h = (maxp / o.max) * o.val[i];
                y = padding + maxp - h + o.size3d;
            }
            frontPart = paper.rect(x, y, w, h);
            frontPart.attr(frontParams);
            bars[i] = frontPart;
            bars[i].num = i;

/*
            if (o.tooltip) {
                bars[i].mouseover(function () {
                    highlightOn(this);
                    showTooltip(this.num, true);
                }).mouseout(function () {
                    highlightOff(this);
                    showTooltip(this.num, false);
                });
            }
*/
            if (o.show3d) {
                topPath = createTopPath(x, y, w, h);
                createPart(topPath, topParams);
                sidePath = createSidePath(x, y, w, h);
                createPart(sidePath, sideParams);
            }
            (o.horizontal) ? y += np : x += np;
        }

        function createTopPath(x, y, w, h) {
            return ["M", x, y, "L", x + o.size3d, y - o.size3d, "L", x + w + o.size3d, y - o.size3d, "L", x + w, y, "z"].join(",");
        }

        function createSidePath(x, y, w, h) {
            return ["M", x + w + o.size3d, y - o.size3d, "L", x + w + o.size3d, y + h - o.size3d, "L", x + w, y + h, "L", x + w, y, "z"].join(",");
        }

        function createPart(path, params) {
            var part = paper.path(path);
            part.attr(params);
        }

        function highlightOn(b) {
            b.attr("fill", grad_angle + "-" + o.darkColors[b.num] + "-" + o.lightColors[b.num]);
        }

        function highlightOff(b) {
            b.attr("fill", grad_angle + "-" + o.darkColors[b.num] + "-" + o.colors[b.num]);
        }

        function showTooltip(num, show) {
            var tooltip = document.getElementById('tooltip');
            if (show) {
                b = bars[num];
                lbl = o.labels[num] ? o.labels[num] + " - " : "";
                lbl = lbl + o.val[num];
                tooltip.style.borderColor = o.colors[num];
                tooltip.innerHTML = lbl;
                cur = findPos(paper.canvas);
                wh = findWH(tooltip);
                var xt = yt = 0;
                if (o.horizontal) {
                    xt = cur.left + b.attr('x') + o.size3d / 2 + b.attr('width');
                    yt = cur.top + b.attr('y') + b.attr('height') / 2 - wh.height / 2;
                } else {
                    xt = cur.left + b.attr('x') + o.size3d / 2 + b.attr('width') / 2 - (wh.width / 2);
                    yt = cur.top + b.attr('y') - o.size3d / 2 - wh.height;
                }
                tooltip.style.left = xt + "px";
                tooltip.style.top = yt + "px";
                tooltip.style.display = 'block';
            } else {
                tooltip.style.display = 'none';
            }
        }
        if (o.backgroundFill[0] != o.backgroundFill[1]) {
            attrGrid = {
                stroke: "none",
                fill: "45-" + o.backgroundFill[0] + "-" + o.backgroundFill[1]
            };
            p3 = ["M", 0, padding + o.size3d, "L", padding + o.size3d, 0, "L", padding + o.size3d, cy - o.size3d - padding, "L", 0, cy, "z"].join(",");
            var grid1 = paper.path(p3);
            grid1.attr(attrGrid);
            grid1.toBack();
            p3 = ["M", padding + o.size3d, 0, "L", cx, 0, "L", cx, cy - o.size3d - padding, "L", padding + o.size3d, cy - o.size3d - padding, "z"].join(",");
            var grid2 = paper.path(p3);
            grid2.attr(attrGrid);
            grid2.toBack();
            p3 = ["M", padding + o.size3d, cy - o.size3d - padding, "L", cx, cy - o.size3d - padding, "L", cx - padding - o.size3d - 1, cy, "L", 0, cy, "z"].join(",");
            var grid3 = paper.path(p3);
            grid3.attr(attrGrid);
            grid3.toBack();
        } else {
            var background = paper.rect(0, 0, cx, cy);
            background.attr({
                stroke: "none",
                fill: o.backgroundFill[0]
            });
            background.toBack();
        }

        function calculateMaxValueAndUsedColors() {
            Raphael.getColor.reset();
            for (var i = 0; i < o.numberOfValues; i++) {
                if (o.val[i] > o.max) {
                    o.max = o.val[i];
                }
                (o.colors[i] === undefined) ? o.colors[i] = Raphael.getColor() : false;
                o.darkColors[i] = calculateDarkColor(o.colors[i]);
                o.lightColors[i] = calculateLightColor(o.colors[i]);
            }
        }
	};

	// ---- helper functions ----
	function findPos(obj) {
		var curleft = curtop = 0;
		if ("getBoundingClientRect" in document.documentElement	&& !window.opera) {
			var box = obj.getBoundingClientRect();
			var doc = obj.ownerDocument;
			var body = doc.body;
			var docElem = doc.documentElement;
			var clientTop = docElem.clientTop || body.clientTop || 0;
			var clientLeft = docElem.clientLeft || body.clientLeft || 0;

			curtop = box.top + (window.pageYOffset || docElem.scrollTop || body.scrollTop) - clientTop;
			curleft = box.left	+ (window.pageXOffset || docElem.scrollLeft || body.scrollLeft)	- clientLeft;
		} else {
			if (obj.offsetParent) {
				do {
					curleft += obj.offsetLeft;
					curtop += obj.offsetTop;
				} while (obj = obj.offsetParent);
			}
		}
		return {left : curleft,	top : curtop};
	}

	function findWH(obj) {
		var curw = curh = 0;
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
})();
