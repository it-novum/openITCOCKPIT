/*
 * Lazy Load - jQuery plugin for lazy loading images
 *
 * Copyright (c) 2007-2013 Mika Tuupola
 *
 * Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 * Project home:
 *   http://www.appelsiini.net/projects/lazyload
 *
 * Version:  1.9.3
 *
 */

(function($, window, document, undefined){
    var $window = $(window);

    $.fn.lazyload = function(options){
        var elements = this;
        var $container;
        var settings = {
            threshold: 0,
            failure_limit: 0,
            event: "scroll",
            effect: "show",
            container: window,
            data_attribute: "original",
            skip_invisible: true,
            appear: null,
            load: null,
            placeholder: "data:image/gif;base64,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"
        };

        function update(){
            var counter = 0;

            elements.each(function(){
                var $this = $(this);
                if(settings.skip_invisible && !$this.is(":visible")){
                    return;
                }
                if($.abovethetop(this, settings) ||
                    $.leftofbegin(this, settings)){
                    /* Nothing. */
                }else if(!$.belowthefold(this, settings) && !$.rightoffold(this, settings)){
                    $this.trigger("appear");
                    /* if we found an image we'll load, reset the counter */
                    counter = 0;
                }else{
                    if(++counter > settings.failure_limit){
                        return false;
                    }
                }
            });

        }

        if(options){
            /* Maintain BC for a couple of versions. */
            if(undefined !== options.failurelimit){
                options.failure_limit = options.failurelimit;
                delete options.failurelimit;
            }
            if(undefined !== options.effectspeed){
                options.effect_speed = options.effectspeed;
                delete options.effectspeed;
            }

            $.extend(settings, options);
        }

        /* Cache container as jQuery as object. */
        $container = (settings.container === undefined ||
            settings.container === window) ? $window : $(settings.container);

        /* Fire one scroll event per scroll. Not one scroll event per image. */
        if(0 === settings.event.indexOf("scroll")){
            $container.bind(settings.event, function(){
                return update();
            });
        }

        this.each(function(){
            var self = this;
            var $self = $(self);

            self.loaded = false;

            /* If no src attribute given use data:uri. */
            if($self.attr("src") === undefined || $self.attr("src") === false){
                if($self.is("img")){
                    $self.attr("src", settings.placeholder);
                }
            }

            /* When appear is triggered load original image. */
            $self.one("appear", function(){
                if(!this.loaded){
                    if(settings.appear){
                        var elements_left = elements.length;
                        settings.appear.call(self, elements_left, settings);
                    }
                    $("<img />")
                        .bind("load", function(){

                            var original = $self.attr("data-" + settings.data_attribute);
                            $self.hide();
                            if($self.is("img")){
                                $self.attr("src", original);
                            }else{
                                $self.css("background-image", "url('" + original + "')");
                            }
                            $self[settings.effect](settings.effect_speed);

                            self.loaded = true;

                            /* Remove image from array so it is not looped next time. */
                            var temp = $.grep(elements, function(element){
                                return !element.loaded;
                            });
                            elements = $(temp);

                            if(settings.load){
                                var elements_left = elements.length;
                                settings.load.call(self, elements_left, settings);
                            }
                        })
                        .attr("src", $self.attr("data-" + settings.data_attribute));
                }
            });

            /* When wanted event is triggered load original image */
            /* by triggering appear.                              */
            if(0 !== settings.event.indexOf("scroll")){
                $self.bind(settings.event, function(){
                    if(!self.loaded){
                        $self.trigger("appear");
                    }
                });
            }
        });

        /* Check if something appears when window is resized. */
        $window.bind("resize", function(){
            update();
        });

        /* With IOS5 force loading images when navigating with back button. */
        /* Non optimal workaround. */
        if((/(?:iphone|ipod|ipad).*os 5/gi).test(navigator.appVersion)){
            $window.bind("pageshow", function(event){
                if(event.originalEvent && event.originalEvent.persisted){
                    elements.each(function(){
                        $(this).trigger("appear");
                    });
                }
            });
        }

        /* Force initial check if images should appear. */
        $(document).ready(function(){
            update();
        });

        return this;
    };

    /* Convenience methods in jQuery namespace.           */
    /* Use as  $.belowthefold(element, {threshold : 100, container : window}) */

    $.belowthefold = function(element, settings){
        var fold;

        if(settings.container === undefined || settings.container === window){
            fold = (window.innerHeight ? window.innerHeight : $window.height()) + $window.scrollTop();
        }else{
            fold = $(settings.container).offset().top + $(settings.container).height();
        }

        return fold <= $(element).offset().top - settings.threshold;
    };

    $.rightoffold = function(element, settings){
        var fold;

        if(settings.container === undefined || settings.container === window){
            fold = $window.width() + $window.scrollLeft();
        }else{
            fold = $(settings.container).offset().left + $(settings.container).width();
        }

        return fold <= $(element).offset().left - settings.threshold;
    };

    $.abovethetop = function(element, settings){
        var fold;

        if(settings.container === undefined || settings.container === window){
            fold = $window.scrollTop();
        }else{
            fold = $(settings.container).offset().top;
        }

        return fold >= $(element).offset().top + settings.threshold + $(element).height();
    };

    $.leftofbegin = function(element, settings){
        var fold;

        if(settings.container === undefined || settings.container === window){
            fold = $window.scrollLeft();
        }else{
            fold = $(settings.container).offset().left;
        }

        return fold >= $(element).offset().left + settings.threshold + $(element).width();
    };

    $.inviewport = function(element, settings){
        return !$.rightoffold(element, settings) && !$.leftofbegin(element, settings) && !$.belowthefold(element, settings) && !$.abovethetop(element, settings);
    };

    /* Custom selectors for your convenience.   */
    /* Use as $("img:below-the-fold").something() or */
    /* $("img").filter(":below-the-fold").something() which is faster */

    $.extend($.expr[":"], {
        "below-the-fold": function(a){
            return $.belowthefold(a, {threshold: 0});
        },
        "above-the-top": function(a){
            return !$.belowthefold(a, {threshold: 0});
        },
        "right-of-screen": function(a){
            return $.rightoffold(a, {threshold: 0});
        },
        "left-of-screen": function(a){
            return !$.rightoffold(a, {threshold: 0});
        },
        "in-viewport": function(a){
            return $.inviewport(a, {threshold: 0});
        },
        /* Maintain BC for couple of versions. */
        "above-the-fold": function(a){
            return !$.belowthefold(a, {threshold: 0});
        },
        "right-of-fold": function(a){
            return $.rightoffold(a, {threshold: 0});
        },
        "left-of-fold": function(a){
            return !$.rightoffold(a, {threshold: 0});
        }
    });

})(jQuery, window, document);
