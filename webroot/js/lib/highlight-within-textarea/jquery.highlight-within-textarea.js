/*
 * highlight-within-textarea
 *
 * @author  Will Boyd
 * @github  https://github.com/lonekorean/highlight-within-textarea
 */

(function($) {
	let ID = 'hwt';

	let HighlightWithinTextarea = function($el, config) {
		this.init($el, config);
	};

	HighlightWithinTextarea.prototype = {
		init: function($el, config) {
			this.$el = $el;

			// backwards compatibility with v1 (deprecated)
			if (this.getType(config) === 'function') {
				config = { highlight: config };
			}

			if (this.getType(config) === 'custom') {
				this.highlight = config;
				this.generate();
			} else {
				console.error('valid config object not provided');
			}
		},

		// returns identifier strings that aren't necessarily "real" JavaScript types
		getType: function(instance) {
			let type = typeof instance;
			if (!instance) {
				return 'falsey';
			} else if (Array.isArray(instance)) {
				if (instance.length === 2 && typeof instance[0] === 'number' && typeof instance[1] === 'number') {
					return 'range';
				} else {
					return 'array';
				}
			} else if (type === 'object') {
				if (instance instanceof RegExp) {
					return 'regexp';
				} else if (instance.hasOwnProperty('highlight')) {
					return 'custom';
				}
			} else if (type === 'function' || type === 'string') {
				return type;
			}

			return 'other';
		},

		getHighlightsDivCssFix: function(textareaStyle) {
			return {
				'font-size': textareaStyle.getPropertyValue('font-size'),
				'font-family': textareaStyle.getPropertyValue('font-family'),
				'font-weight': textareaStyle.getPropertyValue('font-weight'),
				'line-height': textareaStyle.getPropertyValue('line-height'),
				'letter-spacing': textareaStyle.getPropertyValue('letter-spacing'),
				'border': textareaStyle.getPropertyValue('border'),
				'padding-top': textareaStyle.getPropertyValue('padding-top'),
				'padding-right': textareaStyle.getPropertyValue('padding-right'),
				'padding-bottom': textareaStyle.getPropertyValue('padding-bottom'),
				'padding-left': textareaStyle.getPropertyValue('padding-left'),
			};
		},

		getBackdropDivCssFix: function(textareaStyle) {
			return {
				'background-color': textareaStyle.getPropertyValue('background-color'),
				'margin': '0px'
			};
		},

		getContainerDivCssFix: function(textareaStyle) {
			return {
				'margin-top': textareaStyle.getPropertyValue('margin-top'),
				'margin-right': textareaStyle.getPropertyValue('margin-right'),
				'margin-bottom': textareaStyle.getPropertyValue('margin-bottom'),
				'margin-left': textareaStyle.getPropertyValue('margin-left')
			};
		},

		getTextareaCssFix: function(textareaStyle) {
			// Percent-based width and height will result in different values,
			// 	so we should fix the computed width and height (unless already set,
			//	which would later break resizable areas after plugin 'destroy' event)
			// This value also needs to include the possibility of a scrollbar
			const cssFix = {};
			const elementStyle = this.$el.get(0).style;
			if (!elementStyle.width) {
				const width = parseInt(textareaStyle.getPropertyValue('width'), 10);
				const borderLeftWidth = parseInt(textareaStyle.getPropertyValue('border-left-width'), 10);
				const borderRightWidth = parseInt(textareaStyle.getPropertyValue('border-right-width'), 10);
				const scrollBarWidth = (this.$el.get(0).offsetWidth -
					this.$el.get(0).clientWidth - borderLeftWidth - borderRightWidth);
				cssFix.width = (width + scrollBarWidth) + 'px';
				this.$el.data('fix-width', true);
			}
			if (!elementStyle.height) {
				const height = parseInt(textareaStyle.getPropertyValue('height'), 10);
				const borderTopWidth = parseInt(textareaStyle.getPropertyValue('border-top-width'), 10);
				const borderBottomWidth = parseInt(textareaStyle.getPropertyValue('border-bottom-width'), 10);
				const scrollBarHeight = (this.$el.get(0).offsetHeight -
					this.$el.get(0).clientHeight - borderTopWidth - borderBottomWidth);
				cssFix.height = (height + scrollBarHeight) + 'px';
				this.$el.data('fix-height', true);
			}
			return cssFix;
		},

		getTextareaCssFixReversed: function() {
			/* Used to remove the fixed applied width and height properties */
			const cssFix = {};
			if (this.$el.data('fix-width')) {
				cssFix.width = ""; // unset
				this.$el.removeData('fix-width');
			}
			if (this.$el.data('fix-height')) {
				cssFix.height = ""; // unset
				this.$el.removeData('fix-height');
			}
			return cssFix;
		},

		generate: function() {
			// First save styles from existing textarea element
			const textareaStyle = window.getComputedStyle(this.$el.get(0));
			const highlightsDivCssFix = this.getHighlightsDivCssFix(textareaStyle);
			const backdropDivCssFix = this.getBackdropDivCssFix(textareaStyle);
			const containerDivCssFix = this.getContainerDivCssFix(textareaStyle);
			const textareaCssFix = this.getTextareaCssFix(textareaStyle);

			this.$el
				.addClass(ID + '-input ' + ID + '-content')
				.css(textareaCssFix)
				.on('input.' + ID, this.handleInput.bind(this))
				.on('scroll.' + ID, this.handleScroll.bind(this));

			this.$highlights = $('<div>', { class: ID + '-highlights ' + ID + '-content' })
				.css(highlightsDivCssFix);

			this.$backdrop = $('<div>', { class: ID + '-backdrop' })
				.css(backdropDivCssFix)
				.append(this.$highlights);

			this.$container = $('<div>', { class: ID + '-container' })
				.css(containerDivCssFix)
				.insertAfter(this.$el)
				.append(this.$backdrop, this.$el) // moves $el into $container
				.on('scroll', this.blockContainerScroll.bind(this));

			this.browser = this.detectBrowser();
			switch (this.browser) {
				case 'firefox':
					this.fixFirefox();
					break;
				case 'ios':
					this.fixIOS();
					break;
			}

			// plugin function checks this for success
			this.isGenerated = true;

			// trigger input event to highlight any existing input
			this.handleInput();
		},

		// browser sniffing sucks, but there are browser-specific quirks to handle
		// that are not a matter of feature detection
		detectBrowser: function() {
			let ua = window.navigator.userAgent.toLowerCase();
			if (ua.indexOf('firefox') !== -1) {
				return 'firefox';
			} else if (!!ua.match(/msie|trident\/7|edge/)) {
				return 'ie';
			} else if (!!ua.match(/ipad|iphone|ipod/) && ua.indexOf('windows phone') === -1) {
				// Windows Phone flags itself as "like iPhone", thus the extra check
				return 'ios';
			} else {
				return 'other';
			}
		},

		// Firefox doesn't show text that scrolls into the padding of a textarea, so
		// rearrange a couple box models to make highlights behave the same way
		fixFirefox: function() {
			// take padding and border pixels from highlights div
			let padding = this.$highlights.css([
				'padding-top', 'padding-right', 'padding-bottom', 'padding-left'
			]);
			let border = this.$highlights.css([
				'border-top-width', 'border-right-width', 'border-bottom-width', 'border-left-width'
			]);
			this.$highlights.css({
				'padding': '0',
				'border-width': '0'
			});

			this.$backdrop
				.css({
					// give padding pixels to backdrop div
					'margin-top': '+=' + padding['padding-top'],
					'margin-right': '+=' + padding['padding-right'],
					'margin-bottom': '+=' + padding['padding-bottom'],
					'margin-left': '+=' + padding['padding-left'],
				})
				.css({
					// give border pixels to backdrop div
					'margin-top': '+=' + border['border-top-width'],
					'margin-right': '+=' + border['border-right-width'],
					'margin-bottom': '+=' + border['border-bottom-width'],
					'margin-left': '+=' + border['border-left-width'],
				});
		},

		// iOS adds 3px of (unremovable) padding to the left and right of a textarea,
		// so adjust highlights div to match
		fixIOS: function() {
			this.$highlights.css({
				'padding-left': '+=3px',
				'padding-right': '+=3px'
			});
		},

		handleInput: function() {
			let input = this.$el.val();
			let ranges = this.getRanges(input, this.highlight);
			let unstaggeredRanges = this.removeStaggeredRanges(ranges);
			let boundaries = this.getBoundaries(unstaggeredRanges);
			this.renderMarks(boundaries);
		},

		getRanges: function(input, highlight) {
			let type = this.getType(highlight);
			switch (type) {
				case 'array':
					return this.getArrayRanges(input, highlight);
				case 'function':
					return this.getFunctionRanges(input, highlight);
				case 'regexp':
					return this.getRegExpRanges(input, highlight);
				case 'string':
					return this.getStringRanges(input, highlight);
				case 'range':
					return this.getRangeRanges(input, highlight);
				case 'custom':
					return this.getCustomRanges(input, highlight);
				default:
					if (!highlight) {
						// do nothing for falsey values
						return [];
					} else {
						console.error('unrecognized highlight type');
					}
			}
		},

		getArrayRanges: function(input, arr) {
			let ranges = arr.map(this.getRanges.bind(this, input));
			return Array.prototype.concat.apply([], ranges);
		},

		getFunctionRanges: function(input, func) {
			return this.getRanges(input, func(input));
		},

		getRegExpRanges: function(input, regex) {
			let ranges = [];
			let match;
			while (match = regex.exec(input), match !== null) {
				ranges.push([match.index, match.index + match[0].length]);
				if (!regex.global) {
					// non-global regexes do not increase lastIndex, causing an infinite loop,
					// but we can just break manually after the first match
					break;
				}
			}
			return ranges;
		},

		getStringRanges: function(input, str) {
			let ranges = [];
			let inputLower = input.toLowerCase();
			let strLower = str.toLowerCase();
			let index = 0;
			while (index = inputLower.indexOf(strLower, index), index !== -1) {
				ranges.push([index, index + strLower.length]);
				index += strLower.length;
			}
			return ranges;
		},

		getRangeRanges: function(input, range) {
			return [range];
		},

		getCustomRanges: function(input, custom) {
			let ranges = this.getRanges(input, custom.highlight);
			if (custom.className) {
				ranges.forEach(function(range) {
					// persist class name as a property of the array
					if (range.className) {
						range.className = custom.className + ' ' + range.className;
					} else {
						range.className = custom.className;
					}
				});
			}
			return ranges;
		},

		// prevent staggered overlaps (clean nesting is fine)
		removeStaggeredRanges: function(ranges) {
			let unstaggeredRanges = [];
			ranges.forEach(function(range) {
				let isStaggered = unstaggeredRanges.some(function(unstaggeredRange) {
					let isStartInside = range[0] > unstaggeredRange[0] && range[0] < unstaggeredRange[1];
					let isStopInside = range[1] > unstaggeredRange[0] && range[1] < unstaggeredRange[1];
					return isStartInside !== isStopInside; // xor
				});
				if (!isStaggered) {
					unstaggeredRanges.push(range);
				}
			});
			return unstaggeredRanges;
		},

		getBoundaries: function(ranges) {
			let boundaries = [];
			ranges.forEach(function(range) {
				boundaries.push({
					type: 'start',
					index: range[0],
					className: range.className
				});
				boundaries.push({
					type: 'stop',
					index: range[1]
				});
			});

			this.sortBoundaries(boundaries);
			return boundaries;
		},

		sortBoundaries: function(boundaries) {
			// backwards sort (since marks are inserted right to left)
			boundaries.sort(function(a, b) {
				if (a.index !== b.index) {
					return b.index - a.index;
				} else if (a.type === 'stop' && b.type === 'start') {
					return 1;
				} else if (a.type === 'start' && b.type === 'stop') {
					return -1;
				} else {
					return 0;
				}
			});
		},

		renderMarks: function(boundaries) {
			let input = this.$el.val();
			boundaries.forEach(function(boundary, index) {
				let markup;
				if (boundary.type === 'start') {
					markup = '{{hwt-mark-start|' + index + '}}';
				} else {
					markup = '{{hwt-mark-stop}}';
				}
				input = input.slice(0, boundary.index) + markup + input.slice(boundary.index);
			});

			// this keeps scrolling aligned when input ends with a newline
			input = input.replace(/\n(\{\{hwt-mark-stop\}\})?$/, '\n\n$1');

			// encode HTML entities
			input = input.replace(/</g, '&lt;').replace(/>/g, '&gt;');

			if (this.browser === 'ie') {
				// IE/Edge wraps whitespace differently in a div vs textarea, this fixes it
				input = input.replace(/ /g, ' <wbr>');
			}

			// replace start tokens with opening <mark> tags with class name
			input = input.replace(/\{\{hwt-mark-start\|(\d+)\}\}/g, function(match, submatch) {
				var className = boundaries[+submatch].className;
				if (className) {
					return '<mark class="' + className + '">';
				} else {
					return '<mark>';
				}
			});

			// replace stop tokens with closing </mark> tags
			input = input.replace(/\{\{hwt-mark-stop\}\}/g, '</mark>');

			this.$highlights.html(input);
		},

		handleScroll: function() {
			let scrollTop = this.$el.scrollTop();
			this.$backdrop.scrollTop(scrollTop);

			// Chrome and Safari won't break long strings of spaces, which can cause
			// horizontal scrolling, this compensates by shifting highlights by the
			// horizontally scrolled amount to keep things aligned
			let scrollLeft = this.$el.scrollLeft();
			this.$backdrop.css('transform', (scrollLeft > 0) ? 'translateX(' + -scrollLeft + 'px)' : '');
		},

		// in Chrome, page up/down in the textarea will shift stuff within the
		// container (despite the CSS), this immediately reverts the shift
		blockContainerScroll: function() {
			this.$container.scrollLeft(0);
		},

		destroy: function() {
			this.$backdrop.remove();
			const textareaCssFixReversed = this.getTextareaCssFixReversed();
			this.$el
				.unwrap()
				.removeClass(ID + '-content ' + ID + '-input')
				.css(textareaCssFixReversed)
				.off(ID)
				.removeData(ID);
		},
	};

	// register the jQuery plugin
	$.fn.highlightWithinTextarea = function(options) {
		return this.each(function() {
			let $this = $(this);
			let plugin = $this.data(ID);

			if (typeof options === 'string') {
				if (plugin) {
					switch (options) {
						case 'update':
							plugin.handleInput();
							break;
						case 'destroy':
							plugin.destroy();
							break;
						default:
							console.error('unrecognized method string');
					}
				} else {
					console.error('plugin must be instantiated first');
				}
			} else {
				if (plugin) {
					plugin.destroy();
				}
				plugin = new HighlightWithinTextarea($this, options);
				if (plugin.isGenerated) {
					$this.data(ID, plugin);
				}
			}
		});
	};
})(jQuery);
