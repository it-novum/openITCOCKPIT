angular.module('openITCOCKPIT')
    .service('BBParserService', function(){

        return {
            parse: function(bbCode){
                //ITC-732 This is not default bb code!
                var resString = bbCode;
                resString = resString.replace(/(?:\r\n|\r|\n)/g, '<br />');
                resString = resString.replace(/\[b\]/gi, '<strong>');
                resString = resString.replace(/\[\/b\]/gi, '</strong>');
                resString = resString.replace(/\[i\]/gi, '<i>');
                resString = resString.replace(/\[\/i\]/gi, '</i>');
                resString = resString.replace(/\[u\]/gi, '<u>');
                resString = resString.replace(/\[\/u\]/gi, '</u>');

                resString = resString.replace(/\[left\]/gi, '<div class="text-left">');
                resString = resString.replace(/\[\/left\]/gi, '</div>');
                resString = resString.replace(/\[right\]/gi, '<div class="text-right">');
                resString = resString.replace(/\[\/right\]/gi, '</div>');
                resString = resString.replace(/\[center\]/gi, '<div class="text-center">');
                resString = resString.replace(/\[\/center\]/gi, '</div>');
                resString = resString.replace(/\[justify\]/gi, '<div class="text-justify">');
                resString = resString.replace(/\[\/justify\]/gi, '</div>');

                resString = resString.replace(/\[color ?= ?'(#[\w]{6})' ?\]/gi, '<span style="color:$1">');
                resString = resString.replace(/\[\/color\]/gi, '</span>');

                resString = resString.replace(/\[text ?= ?'([\w\-]+)' ?\]/gi, '<span style="font-size:$1">');
                resString = resString.replace(/\[\/text\]/gi, '</span>');

                resString = resString.replace(/\[url ?= ?'([\w\-:\/\[\]\.\#\! ]+)' ?tab ?\]/gi, '<a href="$1" target="_blank">');
                resString = resString.replace(/\[url ?= ?'([\w\-:\/\[\]\.\#\! ]+)' ?\]/gi, '<a href="$1">');
                resString = resString.replace(/\[\/url\]/gi, '</a>');
                return resString;
            }
        }
    });
