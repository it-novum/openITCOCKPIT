/**
 * Create our Frontend and App namespaces
 */
var Frontend = {};
var App = {
    Controllers: {},
    Components: {}
};

/**
 * If we dont have bind() in this browser, implement it.
 *
 */
if (!Function.prototype.bind) {
    Function.prototype.bind = function () {
        var fn = this, args = Array.prototype.slice.call(arguments),
            object = args.shift();
        return function () {
            return fn.apply(object,
                args.concat(Array.prototype.slice.call(arguments))
            );
        };
    };
}

function is_scalar(expr) {
    return (typeof expr === 'number' || typeof expr === 'string' || typeof expr === 'boolean');
}

function is_array(mixed_var) {
    //  discuss at: http://phpjs.org/functions/is_array/
    // original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // improved by: Legaev Andrey
    // improved by: Onno Marsman
    // improved by: Brett Zamir (http://brett-zamir.me)
    // improved by: Nathan Sepulveda
    // improved by: Brett Zamir (http://brett-zamir.me)
    // bugfixed by: Cord
    // bugfixed by: Manish
    // bugfixed by: Brett Zamir (http://brett-zamir.me)
    //        note: In php.js, javascript objects are like php associative arrays, thus JavaScript objects will also
    //        note: return true in this function (except for objects which inherit properties, being thus used as objects),
    //        note: unless you do ini_set('phpjs.objectsAsArrays', 0), in which case only genuine JavaScript arrays
    //        note: will return true
    //   example 1: is_array(['Kevin', 'van', 'Zonneveld']);
    //   returns 1: true
    //   example 2: is_array('Kevin van Zonneveld');
    //   returns 2: false
    //   example 3: is_array({0: 'Kevin', 1: 'van', 2: 'Zonneveld'});
    //   returns 3: true
    //   example 4: is_array(function tmp_a(){this.name = 'Kevin'});
    //   returns 4: false

    var ini,
        _getFuncName = function (fn) {
            var name = (/\W*function\s+([\w\$]+)\s*\(/)
                .exec(fn);
            if (!name) {
                return '(Anonymous)';
            }
            return name[1];
        };
    _isArray = function (mixed_var) {
        // return Object.prototype.toString.call(mixed_var) === '[object Array]';
        // The above works, but let's do the even more stringent approach: (since Object.prototype.toString could be overridden)
        // Null, Not an object, no length property so couldn't be an Array (or String)
        if (!mixed_var || typeof mixed_var !== 'object' || typeof mixed_var.length !== 'number') {
            return false;
        }
        var len = mixed_var.length;
        mixed_var[mixed_var.length] = 'bogus';
        // The only way I can think of to get around this (or where there would be trouble) would be to have an object defined
        // with a custom "length" getter which changed behavior on each call (or a setter to mess up the following below) or a custom
        // setter for numeric properties, but even that would need to listen for specific indexes; but there should be no false negatives
        // and such a false positive would need to rely on later JavaScript innovations like __defineSetter__
        if (len !== mixed_var.length) {
            // We know it's an array since length auto-changed with the addition of a
            // numeric property at its length end, so safely get rid of our bogus element
            mixed_var.length -= 1;
            return true;
        }
        // Get rid of the property we added onto a non-array object; only possible
        // side-effect is if the user adds back the property later, it will iterate
        // this property in the older order placement in IE (an order which should not
        // be depended on anyways)
        delete mixed_var[mixed_var.length];
        return false;
    };

    if (!mixed_var || typeof mixed_var !== 'object') {
        return false;
    }

    // BEGIN REDUNDANT
    this.php_js = this.php_js || {};
    this.php_js.ini = this.php_js.ini || {};
    // END REDUNDANT

    ini = this.php_js.ini['phpjs.objectsAsArrays'];

    return _isArray(mixed_var) ||
        // Allow returning true unless user has called
        // ini_set('phpjs.objectsAsArrays', 0) to disallow objects as arrays
        ((!ini || ( // if it's not set to 0 and it's not 'off', check for objects as arrays
            (parseInt(ini.local_value, 10) !== 0 && (!ini.local_value.toLowerCase || ini.local_value.toLowerCase() !==
            'off')))) && (
            Object.prototype.toString.call(mixed_var) === '[object Object]' && _getFuncName(mixed_var.constructor) ===
            'Object' // Most likely a literal and intended as assoc. array
        ));
}

function is_array_equal(arr1, arr2) {
    return $(arr1).not(arr2).length == 0 &&
        $(arr2).not(arr1).length == 0;
}

function getObjectKeyByValue(object, value) {
    for (var key in object) {
        if (object.hasOwnProperty(key) && object[key] === value) {
            return key;
        }
    }
}

function ucfirst(str) {
    // http://kevin.vanzonneveld.net
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   bugfixed by: Onno Marsman
    // +   improved by: Brett Zamir (http://brett-zamir.me)
    // *     example 1: ucfirst('kevin van zonneveld');
    // *     returns 1: 'Kevin van zonneveld'
    str += '';
    var f = str.charAt(0).toUpperCase();
    return f + str.substr(1);
}

function stringUnderscore(str) {
    var res = str.replace(/([A-Z])/g, function ($1) {
        return "_" + $1.toLowerCase();
    });
    if (res.substr(0, 1) == '_') {
        res = res.substr(1);
    }
    return res;
}

/**
 * Converts a string from string_with_underscores to StringWithUnderscores.
 * @param string str Underscored string
 * @return string
 */
function camelCase(str) {
    if (typeof str != 'string') {
        return str;
    }
    var parts = str.split('_');
    var res = '';
    for (var i in parts) {
        res += ucfirst(parts[i]);
    }
    return res;
}

function trim(str, charlist) {
    // http://kevin.vanzonneveld.net
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: mdsjack (http://www.mdsjack.bo.it)
    // +   improved by: Alexander Ermolaev (http://snippets.dzone.com/user/AlexanderErmolaev)
    // +      input by: Erkekjetter
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +      input by: DxGx
    // +   improved by: Steven Levithan (http://blog.stevenlevithan.com)
    // +    tweaked by: Jack
    // +   bugfixed by: Onno Marsman
    // *     example 1: trim('    Kevin van Zonneveld    ');
    // *     returns 1: 'Kevin van Zonneveld'
    // *     example 2: trim('Hello World', 'Hdle');
    // *     returns 2: 'o Wor'
    // *     example 3: trim(16, 1);
    // *     returns 3: 6
    var whitespace, l = 0,
        i = 0;
    str += '';

    if (!charlist) {
        // default list
        whitespace = " \n\r\t\f\x0b\xa0\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u200b\u2028\u2029\u3000";
    } else {
        // preg_quote custom list
        charlist += '';
        whitespace = charlist.replace(/([\[\]\(\)\.\?\/\*\{\}\+\$\^\:])/g, '$1');
    }

    l = str.length;
    for (i = 0; i < l; i++) {
        if (whitespace.indexOf(str.charAt(i)) === -1) {
            str = str.substring(i);
            break;
        }
    }

    l = str.length;
    for (i = l - 1; i >= 0; i--) {
        if (whitespace.indexOf(str.charAt(i)) === -1) {
            str = str.substring(0, i + 1);
            break;
        }
    }

    return whitespace.indexOf(str.charAt(0)) === -1 ? str : '';
}

function count(mixed_var, mode) {
    // http://kevin.vanzonneveld.net
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +      input by: Waldo Malqui Silva
    // +   bugfixed by: Soren Hansen
    // +      input by: merabi
    // +   improved by: Brett Zamir (http://brett-zamir.me)
    // +   bugfixed by: Olivier Louvignes (http://mg-crea.com/)
    // *     example 1: count([[0,0],[0,-4]], 'COUNT_RECURSIVE');
    // *     returns 1: 6
    // *     example 2: count({'one' : [1,2,3,4,5]}, 'COUNT_RECURSIVE');
    // *     returns 2: 6
    var key, cnt = 0;

    if (mixed_var === null || typeof mixed_var === 'undefined') {
        return 0;
    } else if (mixed_var.constructor !== Array && mixed_var.constructor !== Object) {
        return 1;
    }

    if (mode === 'COUNT_RECURSIVE') {
        mode = 1;
    }
    if (mode != 1) {
        mode = 0;
    }

    for (key in mixed_var) {
        if (mixed_var.hasOwnProperty(key)) {
            cnt++;
            if (mode == 1 && mixed_var[key] && (mixed_var[key].constructor === Array || mixed_var[key].constructor === Object)) {
                cnt += this.count(mixed_var[key], 1);
            }
        }
    }

    return cnt;
}

function getObjectKeys(obj) {
    var keys = [];
    for (var key in obj) {
        keys.push(key);
    }
    return keys;
}

function getFormValues($form) {
    var paramObj = {};
    $.each($form.serializeArray(), function (_, kv) {
        if (paramObj.hasOwnProperty(kv.name)) {
            paramObj[kv.name] = $.makeArray(paramObj[kv.name]);
            paramObj[kv.name].push(kv.value);
        }
        else {
            paramObj[kv.name] = kv.value;
        }
    });
    return paramObj;
}

/**
 * Function : dump()
 * Arguments: The data - array,hash(associative array),object
 *    The level - OPTIONAL
 * Returns  : The textual representation of the array.
 * This function was inspired by the print_r function of PHP.
 * This will accept some data as the argument and return a
 * text that will be a more readable version of the
 * array/hash/object that is given.
 * Docs: http://www.openjs.com/scripts/others/dump_function_php_print_r.php
 */
function dump(arr, level) {
    var dumped_text = "";
    if (!level) level = 0;

    //The padding given at the beginning of the line.
    var level_padding = "";
    for (var j = 0; j < level + 1; j++) level_padding += "    ";

    if (typeof(arr) == 'object') { //Array/Hashes/Objects 
        for (var item in arr) {
            var value = arr[item];

            if (typeof(value) == 'object') { //If it is an array,
                dumped_text += level_padding + "'" + item + "' ...\n";
                dumped_text += dump(value, level + 1);
            } else {
                dumped_text += level_padding + "'" + item + "' => \"" + value + "\"\n";
            }
        }
    } else { //Stings/Chars/Numbers etc.
        dumped_text = "===>" + arr + "<===(" + typeof(arr) + ")";
    }
    return dumped_text;
}

function sortSelect($select) {
    var cl = $select.get(0);
    var clTexts = new Array();

    for (i = 2; i < cl.length; i++) {
        clTexts[i - 2] =
            cl.options[i].text.toUpperCase() + "," +
            cl.options[i].text + "," +
            cl.options[i].value;
    }

    clTexts.sort();

    for (i = 2; i < cl.length; i++) {
        var parts = clTexts[i - 2].split(',');

        cl.options[i].text = parts[1];
        cl.options[i].value = parts[2];
    }
}
function urldecode(str) {
    // http://kevin.vanzonneveld.net
    // +   original by: Philip Peterson
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +      input by: AJ
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: Brett Zamir (http://brett-zamir.me)
    // +      input by: travc
    // +      input by: Brett Zamir (http://brett-zamir.me)
    // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: Lars Fischer
    // +      input by: Ratheous
    // +   improved by: Orlando
    // +   reimplemented by: Brett Zamir (http://brett-zamir.me)
    // +      bugfixed by: Rob
    // +      input by: e-mike
    // +   improved by: Brett Zamir (http://brett-zamir.me)
    // +      input by: lovio
    // +   improved by: Brett Zamir (http://brett-zamir.me)
    // %        note 1: info on what encoding functions to use from: http://xkr.us/articles/javascript/encode-compare/
    // %        note 2: Please be aware that this function expects to decode from UTF-8 encoded strings, as found on
    // %        note 2: pages served as UTF-8
    // *     example 1: urldecode('Kevin+van+Zonneveld%21');
    // *     returns 1: 'Kevin van Zonneveld!'
    // *     example 2: urldecode('http%3A%2F%2Fkevin.vanzonneveld.net%2F');
    // *     returns 2: 'http://kevin.vanzonneveld.net/'
    // *     example 3: urldecode('http%3A%2F%2Fwww.google.nl%2Fsearch%3Fq%3Dphp.js%26ie%3Dutf-8%26oe%3Dutf-8%26aq%3Dt%26rls%3Dcom.ubuntu%3Aen-US%3Aunofficial%26client%3Dfirefox-a');
    // *     returns 3: 'http://www.google.nl/search?q=php.js&ie=utf-8&oe=utf-8&aq=t&rls=com.ubuntu:en-US:unofficial&client=firefox-a'
    // *     example 4: urldecode('%E5%A5%BD%3_4');
    // *     returns 4: '\u597d%3_4'
    return decodeURIComponent((str + '').replace(/%(?![\da-f]{2})/gi, function () {
        // PHP tolerates poorly formed escape sequences
        return '%25';
    }).replace(/\+/g, '%20'));
}


if (!window.console) {
    var names = ["log", "debug", "info", "warn", "error",
        "assert", "dir", "dirxml", "group",
        "groupEnd", "time", "timeEnd", "count",
        "trace", "profile", "profileEnd"];

    window.console = {};
    for (var i = 0; i < names.length; ++i)
        window.console[names[i]] = function () {
        }
}
