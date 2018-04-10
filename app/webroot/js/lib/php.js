/* 
 * More info at: http://phpjs.org
 * 
 * This is version: 3.26
 * php.js is copyright 2011 Kevin van Zonneveld.
 * 
 * Portions copyright Brett Zamir (http://brett-zamir.me), Kevin van Zonneveld
 * (http://kevin.vanzonneveld.net), Onno Marsman, Theriault, Michael White
 * (http://getsprink.com), Waldo Malqui Silva, Paulo Freitas, Jack, Jonas
 * Raoni Soares Silva (http://www.jsfromhell.com), Philip Peterson, Legaev
 * Andrey, Ates Goral (http://magnetiq.com), Alex, Ratheous, Martijn Wieringa,
 * Rafał Kukawski (http://blog.kukawski.pl), lmeyrick
 * (https://sourceforge.net/projects/bcmath-js/), Nate, Philippe Baumann,
 * Enrique Gonzalez, Webtoolkit.info (http://www.webtoolkit.info/), Carlos R.
 * L. Rodrigues (http://www.jsfromhell.com), Ash Searle
 * (http://hexmen.com/blog/), Jani Hartikainen, travc, Ole Vrijenhoek,
 * Erkekjetter, Michael Grier, Rafał Kukawski (http://kukawski.pl), Johnny
 * Mast (http://www.phpvrouwen.nl), T.Wild, d3x,
 * http://stackoverflow.com/questions/57803/how-to-convert-decimal-to-hex-in-javascript,
 * Rafał Kukawski (http://blog.kukawski.pl/), stag019, pilus, WebDevHobo
 * (http://webdevhobo.blogspot.com/), marrtins, GeekFG
 * (http://geekfg.blogspot.com), Andrea Giammarchi
 * (http://webreflection.blogspot.com), Arpad Ray (mailto:arpad@php.net),
 * gorthaur, Paul Smith, Tim de Koning (http://www.kingsquare.nl), Joris, Oleg
 * Eremeev, Steve Hilder, majak, gettimeofday, KELAN, Josh Fraser
 * (http://onlineaspect.com/2007/06/08/auto-detect-a-time-zone-with-javascript/),
 * Marc Palau, Kevin van Zonneveld (http://kevin.vanzonneveld.net/), Martin
 * (http://www.erlenwiese.de/), Breaking Par Consulting Inc
 * (http://www.breakingpar.com/bkp/home.nsf/0/87256B280015193F87256CFB006C45F7),
 * Chris, Mirek Slugen, saulius, Alfonso Jimenez
 * (http://www.alfonsojimenez.com), Diplom@t (http://difane.com/), felix,
 * Mailfaker (http://www.weedem.fr/), Tyler Akins (http://rumkin.com), Caio
 * Ariede (http://caioariede.com), Robin, Kankrelune
 * (http://www.webfaktory.info/), Karol Kowalski, Imgen Tata
 * (http://www.myipdf.com/), mdsjack (http://www.mdsjack.bo.it), Dreamer,
 * Felix Geisendoerfer (http://www.debuggable.com/felix), Lars Fischer, AJ,
 * David, Aman Gupta, Michael White, Public Domain
 * (http://www.json.org/json2.js), Steven Levithan
 * (http://blog.stevenlevithan.com), Sakimori, Pellentesque Malesuada,
 * Thunder.m, Dj (http://phpjs.org/functions/htmlentities:425#comment_134018),
 * Steve Clay, David James, Francois, class_exists, nobbler, T. Wild, Itsacon
 * (http://www.itsacon.net/), date, Ole Vrijenhoek (http://www.nervous.nl/),
 * Fox, Raphael (Ao RUDLER), Marco, noname, Mateusz "loonquawl" Zalega, Frank
 * Forte, Arno, ger, mktime, john (http://www.jd-tech.net), Nick Kolosov
 * (http://sammy.ru), marc andreu, Scott Cariss, Douglas Crockford
 * (http://javascript.crockford.com), madipta, Slawomir Kaniecki,
 * ReverseSyntax, Nathan, Alex Wilson, kenneth, Bayron Guevara, Adam Wallner
 * (http://web2.bitbaro.hu/), paulo kuong, jmweb, Lincoln Ramsay, djmix,
 * Pyerre, Jon Hohle, Thiago Mata (http://thiagomata.blog.com), lmeyrick
 * (https://sourceforge.net/projects/bcmath-js/this.), Linuxworld, duncan,
 * Gilbert, Sanjoy Roy, Shingo, sankai, Oskar Larsson Högfeldt
 * (http://oskar-lh.name/), Denny Wardhana, 0m3r, Everlasto, Subhasis Deb,
 * josh, jd, Pier Paolo Ramon (http://www.mastersoup.com/), P, merabi, Soren
 * Hansen, Eugene Bulkin (http://doubleaw.com/), Der Simon
 * (http://innerdom.sourceforge.net/), echo is bad, Ozh, XoraX
 * (http://www.xorax.info), EdorFaus, JB, J A R, Marc Jansen, Francesco, LH,
 * Stoyan Kyosev (http://www.svest.org/), nord_ua, omid
 * (http://phpjs.org/functions/380:380#comment_137122), Brad Touesnard, MeEtc
 * (http://yass.meetcweb.com), Peter-Paul Koch
 * (http://www.quirksmode.org/js/beat.html), Olivier Louvignes
 * (http://mg-crea.com/), T0bsn, Tim Wiel, Bryan Elliott, Jalal Berrami,
 * Martin, JT, David Randall, Thomas Beaucourt (http://www.webapp.fr), taith,
 * vlado houba, Pierre-Luc Paour, Kristof Coomans (SCK-CEN Belgian Nucleair
 * Research Centre), Martin Pool, Kirk Strobeck, Rick Waldron, Brant Messenger
 * (http://www.brantmessenger.com/), Devan Penner-Woelk, Saulo Vallory, Wagner
 * B. Soares, Artur Tchernychev, Valentina De Rosa, Jason Wong
 * (http://carrot.org/), Christoph, Daniel Esteban, strftime, Mick@el, rezna,
 * Simon Willison (http://simonwillison.net), Anton Ongson, Gabriel Paderni,
 * Marco van Oort, penutbutterjelly, Philipp Lenssen, Bjorn Roesbeke
 * (http://www.bjornroesbeke.be/), Bug?, Eric Nagel, Tomasz Wesolowski,
 * Evertjan Garretsen, Bobby Drake, Blues (http://tech.bluesmoon.info/), Luke
 * Godfrey, Pul, uestla, Alan C, Ulrich, Rafal Kukawski, Yves Sucaet,
 * sowberry, Norman "zEh" Fuchs, hitwork, Zahlii, johnrembo, Nick Callen,
 * Steven Levithan (stevenlevithan.com), ejsanders, Scott Baker, Brian Tafoya
 * (http://www.premasolutions.com/), Philippe Jausions
 * (http://pear.php.net/user/jausions), Aidan Lister
 * (http://aidanlister.com/), Rob, e-mike, HKM, ChaosNo1, metjay, strcasecmp,
 * strcmp, Taras Bogach, jpfle, Alexander Ermolaev
 * (http://snippets.dzone.com/user/AlexanderErmolaev), DxGx, kilops, Orlando,
 * dptr1988, Le Torbi, James (http://www.james-bell.co.uk/), Pedro Tainha
 * (http://www.pedrotainha.com), James, Arnout Kazemier
 * (http://www.3rd-Eden.com), Chris McMacken, gabriel paderni, Yannoo,
 * FGFEmperor, baris ozdil, Tod Gentille, Greg Frazier, jakes, 3D-GRAF, Allan
 * Jensen (http://www.winternet.no), Howard Yeend, Benjamin Lupton, davook,
 * daniel airton wermann (http://wermann.com.br), Atli Þór, Maximusya, Ryan
 * W Tenney (http://ryan.10e.us), Alexander M Beedie, fearphage
 * (http://http/my.opera.com/fearphage/), Nathan Sepulveda, Victor, Matteo,
 * Billy, stensi, Cord, Manish, T.J. Leahy, Riddler
 * (http://www.frontierwebdev.com/), Rafał Kukawski, FremyCompany, Matt
 * Bradley, Tim de Koning, Luis Salazar (http://www.freaky-media.com/), Diogo
 * Resende, Rival, Andrej Pavlovic, Garagoth, Le Torbi
 * (http://www.letorbi.de/), Dino, Josep Sanz (http://www.ws3.es/), rem,
 * Russell Walker (http://www.nbill.co.uk/), Jamie Beck
 * (http://www.terabit.ca/), setcookie, Michael, YUI Library:
 * http://developer.yahoo.com/yui/docs/YAHOO.util.DateLocale.html, Blues at
 * http://hacks.bluesmoon.info/strftime/strftime.js, Ben
 * (http://benblume.co.uk/), DtTvB
 * (http://dt.in.th/2008-09-16.string-length-in-bytes.html), Andreas, William,
 * meo, incidence, Cagri Ekin, Amirouche, Amir Habibi
 * (http://www.residence-mixte.com/), Luke Smith (http://lucassmith.name),
 * Kheang Hok Chin (http://www.distantia.ca/), Jay Klehr, Lorenzo Pisani,
 * Tony, Yen-Wei Liu, Greenseed, mk.keck, Leslie Hoare, dude, booeyOH, Ben
 * Bryan
 * 
 * Dual licensed under the MIT (MIT-LICENSE.txt)
 * and GPL (GPL-LICENSE.txt) licenses.
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a
 * copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included
 * in all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
 * OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL KEVIN VAN ZONNEVELD BE LIABLE FOR ANY CLAIM, DAMAGES
 * OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE,
 * ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
 * OTHER DEALINGS IN THE SOFTWARE.
 */

function date(format, timestamp){
	// Format a local date/time
	//
	// version: 1109.2015
	// discuss at: http://phpjs.org/functions/date
	// +   original by: Carlos R. L. Rodrigues (http://www.jsfromhell.com)
	// +      parts by: Peter-Paul Koch (http://www.quirksmode.org/js/beat.html)
	// +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	// +   improved by: MeEtc (http://yass.meetcweb.com)
	// +   improved by: Brad Touesnard
	// +   improved by: Tim Wiel
	// +   improved by: Bryan Elliott
	//
	// +   improved by: Brett Zamir (http://brett-zamir.me)
	// +   improved by: David Randall
	// +      input by: Brett Zamir (http://brett-zamir.me)
	// +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	// +   improved by: Brett Zamir (http://brett-zamir.me)
	// +   improved by: Brett Zamir (http://brett-zamir.me)
	// +   improved by: Theriault
	// +  derived from: gettimeofday
	// +      input by: majak
	// +   bugfixed by: majak
	// +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	// +      input by: Alex
	// +   bugfixed by: Brett Zamir (http://brett-zamir.me)
	// +   improved by: Theriault
	// +   improved by: Brett Zamir (http://brett-zamir.me)
	// +   improved by: Theriault
	// +   improved by: Thomas Beaucourt (http://www.webapp.fr)
	// +   improved by: JT
	// +   improved by: Theriault
	// +   improved by: Rafał Kukawski (http://blog.kukawski.pl)
	// +   bugfixed by: omid (http://phpjs.org/functions/380:380#comment_137122)
	// +      input by: Martin
	// +      input by: Alex Wilson
	// %        note 1: Uses global: php_js to store the default timezone
	// %        note 2: Although the function potentially allows timezone info (see notes), it currently does not set
	// %        note 2: per a timezone specified by date_default_timezone_set(). Implementers might use
	// %        note 2: this.php_js.currentTimezoneOffset and this.php_js.currentTimezoneDST set by that function
	// %        note 2: in order to adjust the dates in this function (or our other date functions!) accordingly
	// *     example 1: date('H:m:s \\m \\i\\s \\m\\o\\n\\t\\h', 1062402400);
	// *     returns 1: '09:09:40 m is month'
	// *     example 2: date('F j, Y, g:i a', 1062462400);
	// *     returns 2: 'September 2, 2003, 2:26 am'
	// *     example 3: date('Y W o', 1062462400);
	// *     returns 3: '2003 36 2003'
	// *     example 4: x = date('Y m d', (new Date()).getTime()/1000);
	// *     example 4: (x+'').length == 10 // 2009 01 09
	// *     returns 4: true
	// *     example 5: date('W', 1104534000);
	// *     returns 5: '53'
	// *     example 6: date('B t', 1104534000);
	// *     returns 6: '999 31'
	// *     example 7: date('W U', 1293750000.82); // 2010-12-31
	// *     returns 7: '52 1293750000'
	// *     example 8: date('W', 1293836400); // 2011-01-01
	// *     returns 8: '52'
	// *     example 9: date('W Y-m-d', 1293974054); // 2011-01-02
	// *     returns 9: '52 2011-01-02'
	var that = this,
		jsdate, f, formatChr = /\\?([a-z])/gi,
		formatChrCb,
	// Keep this here (works, but for code commented-out
	// below for file size reasons)
	//, tal= [],
		_pad = function(n, c){
			if((n = n + '').length < c){
				return new Array((++c) - n.length).join('0') + n;
			}
			return n;
		},
		txt_words = ["Sun", "Mon", "Tues", "Wednes", "Thurs", "Fri", "Satur", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
	formatChrCb = function(t, s){
		return f[t] ? f[t]() : s;
	};
	f = {
		// Day
		d: function(){ // Day of month w/leading 0; 01..31
			return _pad(f.j(), 2);
		},
		D: function(){ // Shorthand day name; Mon...Sun
			return f.l().slice(0, 3);
		},
		j: function(){ // Day of month; 1..31
			return jsdate.getDate();
		},
		l: function(){ // Full day name; Monday...Sunday
			return txt_words[f.w()] + 'day';
		},
		N: function(){ // ISO-8601 day of week; 1[Mon]..7[Sun]
			return f.w() || 7;
		},
		S: function(){ // Ordinal suffix for day of month; st, nd, rd, th
			var j = f.j();
			return j > 4 && j < 21 ? 'th' : {1: 'st', 2: 'nd', 3: 'rd'}[j % 10] || 'th';
		},
		w: function(){ // Day of week; 0[Sun]..6[Sat]
			return jsdate.getDay();
		},
		z: function(){ // Day of year; 0..365
			var a = new Date(f.Y(), f.n() - 1, f.j()),
				b = new Date(f.Y(), 0, 1);
			return Math.round((a - b) / 864e5) + 1;
		},

		// Week
		W: function(){ // ISO-8601 week number
			var a = new Date(f.Y(), f.n() - 1, f.j() - f.N() + 3),
				b = new Date(a.getFullYear(), 0, 4);
			return _pad(1 + Math.round((a - b) / 864e5 / 7), 2);
		},

		// Month
		F: function(){ // Full month name; January...December
			return txt_words[6 + f.n()];
		},
		m: function(){ // Month w/leading 0; 01...12
			return _pad(f.n(), 2);
		},
		M: function(){ // Shorthand month name; Jan...Dec
			return f.F().slice(0, 3);
		},
		n: function(){ // Month; 1...12
			return jsdate.getMonth() + 1;
		},
		t: function(){ // Days in month; 28...31
			return (new Date(f.Y(), f.n(), 0)).getDate();
		},

		// Year
		L: function(){ // Is leap year?; 0 or 1
			return new Date(f.Y(), 1, 29).getMonth() === 1 | 0;
		},
		o: function(){ // ISO-8601 year
			var n = f.n(),
				W = f.W(),
				Y = f.Y();
			return Y + (n === 12 && W < 9 ? -1 : n === 1 && W > 9);
		},
		Y: function(){ // Full year; e.g. 1980...2010
			return jsdate.getFullYear();
		},
		y: function(){ // Last two digits of year; 00...99
			return (f.Y() + "").slice(-2);
		},

		// Time
		a: function(){ // am or pm
			return jsdate.getHours() > 11 ? "pm" : "am";
		},
		A: function(){ // AM or PM
			return f.a().toUpperCase();
		},
		B: function(){ // Swatch Internet time; 000..999
			var H = jsdate.getUTCHours() * 36e2,
			// Hours
				i = jsdate.getUTCMinutes() * 60,
			// Minutes
				s = jsdate.getUTCSeconds(); // Seconds
			return _pad(Math.floor((H + i + s + 36e2) / 86.4) % 1e3, 3);
		},
		g: function(){ // 12-Hours; 1..12
			return f.G() % 12 || 12;
		},
		G: function(){ // 24-Hours; 0..23
			return jsdate.getHours();
		},
		h: function(){ // 12-Hours w/leading 0; 01..12
			return _pad(f.g(), 2);
		},
		H: function(){ // 24-Hours w/leading 0; 00..23
			return _pad(f.G(), 2);
		},
		i: function(){ // Minutes w/leading 0; 00..59
			return _pad(jsdate.getMinutes(), 2);
		},
		s: function(){ // Seconds w/leading 0; 00..59
			return _pad(jsdate.getSeconds(), 2);
		},
		u: function(){ // Microseconds; 000000-999000
			return _pad(jsdate.getMilliseconds() * 1000, 6);
		},

		// Timezone
		e: function(){ // Timezone identifier; e.g. Atlantic/Azores, ...
			// The following works, but requires inclusion of the very large
			// timezone_abbreviations_list() function.
			/*              return this.date_default_timezone_get();
			 */
			throw 'Not supported (see source code of date() for timezone on how to add support)';
		},
		I: function(){ // DST observed?; 0 or 1
			// Compares Jan 1 minus Jan 1 UTC to Jul 1 minus Jul 1 UTC.
			// If they are not equal, then DST is observed.
			var a = new Date(f.Y(), 0),
			// Jan 1
				c = Date.UTC(f.Y(), 0),
			// Jan 1 UTC
				b = new Date(f.Y(), 6),
			// Jul 1
				d = Date.UTC(f.Y(), 6); // Jul 1 UTC
			return 0 + ((a - c) !== (b - d));
		},
		O: function(){ // Difference to GMT in hour format; e.g. +0200
			var tzo = jsdate.getTimezoneOffset(),
				a = Math.abs(tzo);
			return (tzo > 0 ? "-" : "+") + _pad(Math.floor(a / 60) * 100 + a % 60, 4);
		},
		P: function(){ // Difference to GMT w/colon; e.g. +02:00
			var O = f.O();
			return (O.substr(0, 3) + ":" + O.substr(3, 2));
		},
		T: function(){ // Timezone abbreviation; e.g. EST, MDT, ...
			// The following works, but requires inclusion of the very
			// large timezone_abbreviations_list() function.
			/*              var abbr = '', i = 0, os = 0, default = 0;
			 if (!tal.length) {
			 tal = that.timezone_abbreviations_list();
			 }
			 if (that.php_js && that.php_js.default_timezone) {
			 default = that.php_js.default_timezone;
			 for (abbr in tal) {
			 for (i=0; i < tal[abbr].length; i++) {
			 if (tal[abbr][i].timezone_id === default) {
			 return abbr.toUpperCase();
			 }
			 }
			 }
			 }
			 for (abbr in tal) {
			 for (i = 0; i < tal[abbr].length; i++) {
			 os = -jsdate.getTimezoneOffset() * 60;
			 if (tal[abbr][i].offset === os) {
			 return abbr.toUpperCase();
			 }
			 }
			 }
			 */
			return 'UTC';
		},
		Z: function(){ // Timezone offset in seconds (-43200...50400)
			return -jsdate.getTimezoneOffset() * 60;
		},

		// Full Date/Time
		c: function(){ // ISO-8601 date.
			return 'Y-m-d\\Th:i:sP'.replace(formatChr, formatChrCb);
		},
		r: function(){ // RFC 2822
			return 'D, d M Y H:i:s O'.replace(formatChr, formatChrCb);
		},
		U: function(){ // Seconds since UNIX epoch
			return jsdate.getTime() / 1000 | 0;
		}
	};
	this.date = function(format, timestamp){
		that = this;
		jsdate = ((typeof timestamp === 'undefined') ? new Date() : // Not provided
			(timestamp instanceof Date) ? new Date(timestamp) : // JS Date()
				new Date(timestamp * 1000) // UNIX timestamp (auto-convert to int)
		);
		return format.replace(formatChr, formatChrCb);
	};
	return this.date(format, timestamp);
}

function in_array(needle, haystack, argStrict){
	//  discuss at: http://phpjs.org/functions/in_array/
	// original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	// improved by: vlado houba
	// improved by: Jonas Sciangula Street (Joni2Back)
	//    input by: Billy
	// bugfixed by: Brett Zamir (http://brett-zamir.me)
	//   example 1: in_array('van', ['Kevin', 'van', 'Zonneveld']);
	//   returns 1: true
	//   example 2: in_array('vlado', {0: 'Kevin', vlado: 'van', 1: 'Zonneveld'});
	//   returns 2: false
	//   example 3: in_array(1, ['1', '2', '3']);
	//   example 3: in_array(1, ['1', '2', '3'], false);
	//   returns 3: true
	//   returns 3: true
	//   example 4: in_array(1, ['1', '2', '3'], true);
	//   returns 4: false

	var key = '',
		strict = !!argStrict;

	//we prevent the double check (strict && arr[key] === ndl) || (!strict && arr[key] == ndl)
	//in just one for, in order to improve the performance
	//deciding wich type of comparation will do before walk array
	if(strict){
		for(key in haystack){
			if(haystack[key] === needle){
				return true;
			}
		}
	}else{
		for(key in haystack){
			if(haystack[key] == needle){
				return true;
			}
		}
	}

	return false;
}

function strip_tags(input, allowed){
	//  discuss at: http://phpjs.org/functions/strip_tags/
	// original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	// improved by: Luke Godfrey
	// improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	//    input by: Pul
	//    input by: Alex
	//    input by: Marc Palau
	//    input by: Brett Zamir (http://brett-zamir.me)
	//    input by: Bobby Drake
	//    input by: Evertjan Garretsen
	// bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	// bugfixed by: Onno Marsman
	// bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	// bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	// bugfixed by: Eric Nagel
	// bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	// bugfixed by: Tomasz Wesolowski
	//  revised by: Rafał Kukawski (http://blog.kukawski.pl/)
	//   example 1: strip_tags('<p>Kevin</p> <br /><b>van</b> <i>Zonneveld</i>', '<i><b>');
	//   returns 1: 'Kevin <b>van</b> <i>Zonneveld</i>'
	//   example 2: strip_tags('<p>Kevin <img src="someimage.png" onmouseover="someFunction()">van <i>Zonneveld</i></p>', '<p>');
	//   returns 2: '<p>Kevin van Zonneveld</p>'
	//   example 3: strip_tags("<a href='http://kevin.vanzonneveld.net'>Kevin van Zonneveld</a>", "<a>");
	//   returns 3: "<a href='http://kevin.vanzonneveld.net'>Kevin van Zonneveld</a>"
	//   example 4: strip_tags('1 < 5 5 > 1');
	//   returns 4: '1 < 5 5 > 1'
	//   example 5: strip_tags('1 <br/> 1');
	//   returns 5: '1  1'
	//   example 6: strip_tags('1 <br/> 1', '<br>');
	//   returns 6: '1 <br/> 1'
	//   example 7: strip_tags('1 <br/> 1', '<br><br/>');
	//   returns 7: '1 <br/> 1'

	allowed = (((allowed || '') + '')
		.toLowerCase()
		.match(/<[a-z][a-z0-9]*>/g) || [])
		.join(''); // making sure the allowed arg is a string containing only tags in lowercase (<a><b><c>)
	var tags = /<\/?([a-z][a-z0-9]*)\b[^>]*>/gi,
		commentsAndPhpTags = /<!--[\s\S]*?-->|<\?(?:php)?[\s\S]*?\?>/gi;
	return input.replace(commentsAndPhpTags, '')
		.replace(tags, function($0, $1){
			return allowed.indexOf('<' + $1.toLowerCase() + '>') > -1 ? $0 : '';
		});
}

function str_replace(search, replace, subject, count){
	//  discuss at: http://phpjs.org/functions/str_replace/
	// original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	// improved by: Gabriel Paderni
	// improved by: Philip Peterson
	// improved by: Simon Willison (http://simonwillison.net)
	// improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	// improved by: Onno Marsman
	// improved by: Brett Zamir (http://brett-zamir.me)
	//  revised by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
	// bugfixed by: Anton Ongson
	// bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	// bugfixed by: Oleg Eremeev
	//    input by: Onno Marsman
	//    input by: Brett Zamir (http://brett-zamir.me)
	//    input by: Oleg Eremeev
	//        note: The count parameter must be passed as a string in order
	//        note: to find a global variable in which the result will be given
	//   example 1: str_replace(' ', '.', 'Kevin van Zonneveld');
	//   returns 1: 'Kevin.van.Zonneveld'
	//   example 2: str_replace(['{name}', 'l'], ['hello', 'm'], '{name}, lars');
	//   returns 2: 'hemmo, mars'

	var i = 0,
		j = 0,
		temp = '',
		repl = '',
		sl = 0,
		fl = 0,
		f = [].concat(search),
		r = [].concat(replace),
		s = subject,
		ra = Object.prototype.toString.call(r) === '[object Array]',
		sa = Object.prototype.toString.call(s) === '[object Array]';
	s = [].concat(s);
	if(count){
		this.window[count] = 0;
	}

	for(i = 0, sl = s.length; i < sl; i++){
		if(s[i] === ''){
			continue;
		}
		for(j = 0, fl = f.length; j < fl; j++){
			temp = s[i] + '';
			repl = ra ? (r[j] !== undefined ? r[j] : '') : r[0];
			s[i] = (temp)
				.split(f[j])
				.join(repl);
			if(count && s[i] !== temp){
				this.window[count] += (temp.length - s[i].length) / f[j].length;
			}
		}
	}
	return sa ? s : s[0];
}

function html_entity_decode(string, quote_style){
	//  discuss at: http://phpjs.org/functions/html_entity_decode/
	// original by: john (http://www.jd-tech.net)
	//    input by: ger
	//    input by: Ratheous
	//    input by: Nick Kolosov (http://sammy.ru)
	// improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	// improved by: marc andreu
	//  revised by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	//  revised by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	// bugfixed by: Onno Marsman
	// bugfixed by: Brett Zamir (http://brett-zamir.me)
	// bugfixed by: Fox
	//  depends on: get_html_translation_table
	//   example 1: html_entity_decode('Kevin &amp; van Zonneveld');
	//   returns 1: 'Kevin & van Zonneveld'
	//   example 2: html_entity_decode('&amp;lt;');
	//   returns 2: '&lt;'

	var hash_map = {},
		symbol = '',
		tmp_str = '',
		entity = '';
	tmp_str = string.toString();

	if(false === (hash_map = this.get_html_translation_table('HTML_ENTITIES', quote_style))){
		return false;
	}

	// fix &amp; problem
	// http://phpjs.org/functions/get_html_translation_table:416#comment_97660
	delete(hash_map['&']);
	hash_map['&'] = '&amp;';

	for(symbol in hash_map){
		entity = hash_map[symbol];
		tmp_str = tmp_str.split(entity)
			.join(symbol);
	}
	tmp_str = tmp_str.split('&#039;')
		.join("'");

	return tmp_str;
}

function get_html_translation_table(table, quote_style){
	//  discuss at: http://phpjs.org/functions/get_html_translation_table/
	// original by: Philip Peterson
	//  revised by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	// bugfixed by: noname
	// bugfixed by: Alex
	// bugfixed by: Marco
	// bugfixed by: madipta
	// bugfixed by: Brett Zamir (http://brett-zamir.me)
	// bugfixed by: T.Wild
	// improved by: KELAN
	// improved by: Brett Zamir (http://brett-zamir.me)
	//    input by: Frank Forte
	//    input by: Ratheous
	//        note: It has been decided that we're not going to add global
	//        note: dependencies to php.js, meaning the constants are not
	//        note: real constants, but strings instead. Integers are also supported if someone
	//        note: chooses to create the constants themselves.
	//   example 1: get_html_translation_table('HTML_SPECIALCHARS');
	//   returns 1: {'"': '&quot;', '&': '&amp;', '<': '&lt;', '>': '&gt;'}

	var entities = {},
		hash_map = {},
		decimal;
	var constMappingTable = {},
		constMappingQuoteStyle = {};
	var useTable = {},
		useQuoteStyle = {};

	// Translate arguments
	constMappingTable[0] = 'HTML_SPECIALCHARS';
	constMappingTable[1] = 'HTML_ENTITIES';
	constMappingQuoteStyle[0] = 'ENT_NOQUOTES';
	constMappingQuoteStyle[2] = 'ENT_COMPAT';
	constMappingQuoteStyle[3] = 'ENT_QUOTES';

	useTable = !isNaN(table) ? constMappingTable[table] : table ? table.toUpperCase() : 'HTML_SPECIALCHARS';
	useQuoteStyle = !isNaN(quote_style) ? constMappingQuoteStyle[quote_style] : quote_style ? quote_style.toUpperCase() :
		'ENT_COMPAT';

	if(useTable !== 'HTML_SPECIALCHARS' && useTable !== 'HTML_ENTITIES'){
		throw new Error('Table: ' + useTable + ' not supported');
		// return false;
	}

	entities['38'] = '&amp;';
	if(useTable === 'HTML_ENTITIES'){
		entities['160'] = '&nbsp;';
		entities['161'] = '&iexcl;';
		entities['162'] = '&cent;';
		entities['163'] = '&pound;';
		entities['164'] = '&curren;';
		entities['165'] = '&yen;';
		entities['166'] = '&brvbar;';
		entities['167'] = '&sect;';
		entities['168'] = '&uml;';
		entities['169'] = '&copy;';
		entities['170'] = '&ordf;';
		entities['171'] = '&laquo;';
		entities['172'] = '&not;';
		entities['173'] = '&shy;';
		entities['174'] = '&reg;';
		entities['175'] = '&macr;';
		entities['176'] = '&deg;';
		entities['177'] = '&plusmn;';
		entities['178'] = '&sup2;';
		entities['179'] = '&sup3;';
		entities['180'] = '&acute;';
		entities['181'] = '&micro;';
		entities['182'] = '&para;';
		entities['183'] = '&middot;';
		entities['184'] = '&cedil;';
		entities['185'] = '&sup1;';
		entities['186'] = '&ordm;';
		entities['187'] = '&raquo;';
		entities['188'] = '&frac14;';
		entities['189'] = '&frac12;';
		entities['190'] = '&frac34;';
		entities['191'] = '&iquest;';
		entities['192'] = '&Agrave;';
		entities['193'] = '&Aacute;';
		entities['194'] = '&Acirc;';
		entities['195'] = '&Atilde;';
		entities['196'] = '&Auml;';
		entities['197'] = '&Aring;';
		entities['198'] = '&AElig;';
		entities['199'] = '&Ccedil;';
		entities['200'] = '&Egrave;';
		entities['201'] = '&Eacute;';
		entities['202'] = '&Ecirc;';
		entities['203'] = '&Euml;';
		entities['204'] = '&Igrave;';
		entities['205'] = '&Iacute;';
		entities['206'] = '&Icirc;';
		entities['207'] = '&Iuml;';
		entities['208'] = '&ETH;';
		entities['209'] = '&Ntilde;';
		entities['210'] = '&Ograve;';
		entities['211'] = '&Oacute;';
		entities['212'] = '&Ocirc;';
		entities['213'] = '&Otilde;';
		entities['214'] = '&Ouml;';
		entities['215'] = '&times;';
		entities['216'] = '&Oslash;';
		entities['217'] = '&Ugrave;';
		entities['218'] = '&Uacute;';
		entities['219'] = '&Ucirc;';
		entities['220'] = '&Uuml;';
		entities['221'] = '&Yacute;';
		entities['222'] = '&THORN;';
		entities['223'] = '&szlig;';
		entities['224'] = '&agrave;';
		entities['225'] = '&aacute;';
		entities['226'] = '&acirc;';
		entities['227'] = '&atilde;';
		entities['228'] = '&auml;';
		entities['229'] = '&aring;';
		entities['230'] = '&aelig;';
		entities['231'] = '&ccedil;';
		entities['232'] = '&egrave;';
		entities['233'] = '&eacute;';
		entities['234'] = '&ecirc;';
		entities['235'] = '&euml;';
		entities['236'] = '&igrave;';
		entities['237'] = '&iacute;';
		entities['238'] = '&icirc;';
		entities['239'] = '&iuml;';
		entities['240'] = '&eth;';
		entities['241'] = '&ntilde;';
		entities['242'] = '&ograve;';
		entities['243'] = '&oacute;';
		entities['244'] = '&ocirc;';
		entities['245'] = '&otilde;';
		entities['246'] = '&ouml;';
		entities['247'] = '&divide;';
		entities['248'] = '&oslash;';
		entities['249'] = '&ugrave;';
		entities['250'] = '&uacute;';
		entities['251'] = '&ucirc;';
		entities['252'] = '&uuml;';
		entities['253'] = '&yacute;';
		entities['254'] = '&thorn;';
		entities['255'] = '&yuml;';
	}

	if(useQuoteStyle !== 'ENT_NOQUOTES'){
		entities['34'] = '&quot;';
	}
	if(useQuoteStyle === 'ENT_QUOTES'){
		entities['39'] = '&#39;';
	}
	entities['60'] = '&lt;';
	entities['62'] = '&gt;';

	// ascii decimals to real symbols
	for(decimal in entities){
		if(entities.hasOwnProperty(decimal)){
			hash_map[String.fromCharCode(decimal)] = entities[decimal];
		}
	}

	return hash_map;
}

function strtotime(text, now){
	//  discuss at: http://phpjs.org/functions/strtotime/
	//     version: 1109.2016
	// original by: Caio Ariede (http://caioariede.com)
	// improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	// improved by: Caio Ariede (http://caioariede.com)
	// improved by: A. Matías Quezada (http://amatiasq.com)
	// improved by: preuter
	// improved by: Brett Zamir (http://brett-zamir.me)
	// improved by: Mirko Faber
	//    input by: David
	// bugfixed by: Wagner B. Soares
	// bugfixed by: Artur Tchernychev
	//        note: Examples all have a fixed timestamp to prevent tests to fail because of variable time(zones)
	//   example 1: strtotime('+1 day', 1129633200);
	//   returns 1: 1129719600
	//   example 2: strtotime('+1 week 2 days 4 hours 2 seconds', 1129633200);
	//   returns 2: 1130425202
	//   example 3: strtotime('last month', 1129633200);
	//   returns 3: 1127041200
	//   example 4: strtotime('2009-05-04 08:30:00 GMT');
	//   returns 4: 1241425800

	var parsed, match, today, year, date, days, ranges, len, times, regex, i, fail = false;

	if(!text){
		return fail;
	}

	// Unecessary spaces
	text = text.replace(/^\s+|\s+$/g, '')
		.replace(/\s{2,}/g, ' ')
		.replace(/[\t\r\n]/g, '')
		.toLowerCase();

	// in contrast to php, js Date.parse function interprets:
	// dates given as yyyy-mm-dd as in timezone: UTC,
	// dates with "." or "-" as MDY instead of DMY
	// dates with two-digit years differently
	// etc...etc...
	// ...therefore we manually parse lots of common date formats
	match = text.match(
		/^(\d{1,4})([\-\.\/\:])(\d{1,2})([\-\.\/\:])(\d{1,4})(?:\s(\d{1,2}):(\d{2})?:?(\d{2})?)?(?:\s([A-Z]+)?)?$/);

	if(match && match[2] === match[4]){
		if(match[1] > 1901){
			switch(match[2]){
				case '-':
				{
					// YYYY-M-D
					if(match[3] > 12 || match[5] > 31){
						return fail;
					}

					return new Date(match[1], parseInt(match[3], 10) - 1, match[5],
							match[6] || 0, match[7] || 0, match[8] || 0, match[9] || 0) / 1000;
				}
				case '.':
				{
					// YYYY.M.D is not parsed by strtotime()
					return fail;
				}
				case '/':
				{
					// YYYY/M/D
					if(match[3] > 12 || match[5] > 31){
						return fail;
					}

					return new Date(match[1], parseInt(match[3], 10) - 1, match[5],
							match[6] || 0, match[7] || 0, match[8] || 0, match[9] || 0) / 1000;
				}
			}
		}else if(match[5] > 1901){
			switch(match[2]){
				case '-':
				{
					// D-M-YYYY
					if(match[3] > 12 || match[1] > 31){
						return fail;
					}

					return new Date(match[5], parseInt(match[3], 10) - 1, match[1],
							match[6] || 0, match[7] || 0, match[8] || 0, match[9] || 0) / 1000;
				}
				case '.':
				{
					// D.M.YYYY
					if(match[3] > 12 || match[1] > 31){
						return fail;
					}

					return new Date(match[5], parseInt(match[3], 10) - 1, match[1],
							match[6] || 0, match[7] || 0, match[8] || 0, match[9] || 0) / 1000;
				}
				case '/':
				{
					// M/D/YYYY
					if(match[1] > 12 || match[3] > 31){
						return fail;
					}

					return new Date(match[5], parseInt(match[1], 10) - 1, match[3],
							match[6] || 0, match[7] || 0, match[8] || 0, match[9] || 0) / 1000;
				}
			}
		}else{
			switch(match[2]){
				case '-':
				{
					// YY-M-D
					if(match[3] > 12 || match[5] > 31 || (match[1] < 70 && match[1] > 38)){
						return fail;
					}

					year = match[1] >= 0 && match[1] <= 38 ? +match[1] + 2000 : match[1];
					return new Date(year, parseInt(match[3], 10) - 1, match[5],
							match[6] || 0, match[7] || 0, match[8] || 0, match[9] || 0) / 1000;
				}
				case '.':
				{
					// D.M.YY or H.MM.SS
					if(match[5] >= 70){
						// D.M.YY
						if(match[3] > 12 || match[1] > 31){
							return fail;
						}

						return new Date(match[5], parseInt(match[3], 10) - 1, match[1],
								match[6] || 0, match[7] || 0, match[8] || 0, match[9] || 0) / 1000;
					}
					if(match[5] < 60 && !match[6]){
						// H.MM.SS
						if(match[1] > 23 || match[3] > 59){
							return fail;
						}

						today = new Date();
						return new Date(today.getFullYear(), today.getMonth(), today.getDate(),
								match[1] || 0, match[3] || 0, match[5] || 0, match[9] || 0) / 1000;
					}

					// invalid format, cannot be parsed
					return fail;
				}
				case '/':
				{
					// M/D/YY
					if(match[1] > 12 || match[3] > 31 || (match[5] < 70 && match[5] > 38)){
						return fail;
					}

					year = match[5] >= 0 && match[5] <= 38 ? +match[5] + 2000 : match[5];
					return new Date(year, parseInt(match[1], 10) - 1, match[3],
							match[6] || 0, match[7] || 0, match[8] || 0, match[9] || 0) / 1000;
				}
				case ':':
				{
					// HH:MM:SS
					if(match[1] > 23 || match[3] > 59 || match[5] > 59){
						return fail;
					}

					today = new Date();
					return new Date(today.getFullYear(), today.getMonth(), today.getDate(),
							match[1] || 0, match[3] || 0, match[5] || 0) / 1000;
				}
			}
		}
	}

	// other formats and "now" should be parsed by Date.parse()
	if(text === 'now'){
		return now === null || isNaN(now) ? new Date()
			.getTime() / 1000 | 0 : now | 0;
	}
	if(!isNaN(parsed = Date.parse(text))){
		return parsed / 1000 | 0;
	}

	date = now ? new Date(now * 1000) : new Date();
	days = {
		'sun': 0,
		'mon': 1,
		'tue': 2,
		'wed': 3,
		'thu': 4,
		'fri': 5,
		'sat': 6
	};
	ranges = {
		'yea': 'FullYear',
		'mon': 'Month',
		'day': 'Date',
		'hou': 'Hours',
		'min': 'Minutes',
		'sec': 'Seconds'
	};

	function lastNext(type, range, modifier){
		var diff, day = days[range];

		if(typeof day !== 'undefined'){
			diff = day - date.getDay();

			if(diff === 0){
				diff = 7 * modifier;
			}else if(diff > 0 && type === 'last'){
				diff -= 7;
			}else if(diff < 0 && type === 'next'){
				diff += 7;
			}

			date.setDate(date.getDate() + diff);
		}
	}

	function process(val){
		var splt = val.split(' '), // Todo: Reconcile this with regex using \s, taking into account browser issues with split and regexes
			type = splt[0],
			range = splt[1].substring(0, 3),
			typeIsNumber = /\d+/.test(type),
			ago = splt[2] === 'ago',
			num = (type === 'last' ? -1 : 1) * (ago ? -1 : 1);

		if(typeIsNumber){
			num *= parseInt(type, 10);
		}

		if(ranges.hasOwnProperty(range) && !splt[1].match(/^mon(day|\.)?$/i)){
			return date['set' + ranges[range]](date['get' + ranges[range]]() + num);
		}

		if(range === 'wee'){
			return date.setDate(date.getDate() + (num * 7));
		}

		if(type === 'next' || type === 'last'){
			lastNext(type, range, num);
		}else if(!typeIsNumber){
			return false;
		}

		return true;
	}

	times = '(years?|months?|weeks?|days?|hours?|minutes?|min|seconds?|sec' +
		'|sunday|sun\\.?|monday|mon\\.?|tuesday|tue\\.?|wednesday|wed\\.?' +
		'|thursday|thu\\.?|friday|fri\\.?|saturday|sat\\.?)';
	regex = '([+-]?\\d+\\s' + times + '|' + '(last|next)\\s' + times + ')(\\sago)?';

	match = text.match(new RegExp(regex, 'gi'));
	if(!match){
		return fail;
	}

	for(i = 0, len = match.length; i < len; i++){
		if(!process(match[i])){
			return fail;
		}
	}

	// ECMAScript 5 only
	// if (!match.every(process))
	//    return false;

	return (date.getTime() / 1000);
}

function sprintf(){
	//  discuss at: http://phpjs.org/functions/sprintf/
	// original by: Ash Searle (http://hexmen.com/blog/)
	// improved by: Michael White (http://getsprink.com)
	// improved by: Jack
	// improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	// improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	// improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	// improved by: Dj
	// improved by: Allidylls
	//    input by: Paulo Freitas
	//    input by: Brett Zamir (http://brett-zamir.me)
	//   example 1: sprintf("%01.2f", 123.1);
	//   returns 1: 123.10
	//   example 2: sprintf("[%10s]", 'monkey');
	//   returns 2: '[    monkey]'
	//   example 3: sprintf("[%'#10s]", 'monkey');
	//   returns 3: '[####monkey]'
	//   example 4: sprintf("%d", 123456789012345);
	//   returns 4: '123456789012345'
	//   example 5: sprintf('%-03s', 'E');
	//   returns 5: 'E00'

	var regex = /%%|%(\d+\$)?([-+\'#0 ]*)(\*\d+\$|\*|\d+)?(\.(\*\d+\$|\*|\d+))?([scboxXuideEfFgG])/g;
	var a = arguments;
	var i = 0;
	var format = a[i++];

	// pad()
	var pad = function(str, len, chr, leftJustify){
		if(!chr){
			chr = ' ';
		}
		var padding = (str.length >= len) ? '' : new Array(1 + len - str.length >>> 0)
			.join(chr);
		return leftJustify ? str + padding : padding + str;
	};

	// justify()
	var justify = function(value, prefix, leftJustify, minWidth, zeroPad, customPadChar){
		var diff = minWidth - value.length;
		if(diff > 0){
			if(leftJustify || !zeroPad){
				value = pad(value, minWidth, customPadChar, leftJustify);
			}else{
				value = value.slice(0, prefix.length) + pad('', diff, '0', true) + value.slice(prefix.length);
			}
		}
		return value;
	};

	// formatBaseX()
	var formatBaseX = function(value, base, prefix, leftJustify, minWidth, precision, zeroPad){
		// Note: casts negative numbers to positive ones
		var number = value >>> 0;
		prefix = prefix && number && {
			'2': '0b',
			'8': '0',
			'16': '0x'
		}[base] || '';
		value = prefix + pad(number.toString(base), precision || 0, '0', false);
		return justify(value, prefix, leftJustify, minWidth, zeroPad);
	};

	// formatString()
	var formatString = function(value, leftJustify, minWidth, precision, zeroPad, customPadChar){
		if(precision != null){
			value = value.slice(0, precision);
		}
		return justify(value, '', leftJustify, minWidth, zeroPad, customPadChar);
	};

	// doFormat()
	var doFormat = function(substring, valueIndex, flags, minWidth, _, precision, type){
		var number, prefix, method, textTransform, value;

		if(substring === '%%'){
			return '%';
		}

		// parse flags
		var leftJustify = false;
		var positivePrefix = '';
		var zeroPad = false;
		var prefixBaseX = false;
		var customPadChar = ' ';
		var flagsl = flags.length;
		for(var j = 0; flags && j < flagsl; j++){
			switch(flags.charAt(j)){
				case ' ':
					positivePrefix = ' ';
					break;
				case '+':
					positivePrefix = '+';
					break;
				case '-':
					leftJustify = true;
					break;
				case "'":
					customPadChar = flags.charAt(j + 1);
					break;
				case '0':
					zeroPad = true;
					customPadChar = '0';
					break;
				case '#':
					prefixBaseX = true;
					break;
			}
		}

		// parameters may be null, undefined, empty-string or real valued
		// we want to ignore null, undefined and empty-string values
		if(!minWidth){
			minWidth = 0;
		}else if(minWidth === '*'){
			minWidth = +a[i++];
		}else if(minWidth.charAt(0) == '*'){
			minWidth = +a[minWidth.slice(1, -1)];
		}else{
			minWidth = +minWidth;
		}

		// Note: undocumented perl feature:
		if(minWidth < 0){
			minWidth = -minWidth;
			leftJustify = true;
		}

		if(!isFinite(minWidth)){
			throw new Error('sprintf: (minimum-)width must be finite');
		}

		if(!precision){
			precision = 'fFeE'.indexOf(type) > -1 ? 6 : (type === 'd') ? 0 : undefined;
		}else if(precision === '*'){
			precision = +a[i++];
		}else if(precision.charAt(0) == '*'){
			precision = +a[precision.slice(1, -1)];
		}else{
			precision = +precision;
		}

		// grab value using valueIndex if required?
		value = valueIndex ? a[valueIndex.slice(0, -1)] : a[i++];

		switch(type){
			case 's':
				return formatString(String(value), leftJustify, minWidth, precision, zeroPad, customPadChar);
			case 'c':
				return formatString(String.fromCharCode(+value), leftJustify, minWidth, precision, zeroPad);
			case 'b':
				return formatBaseX(value, 2, prefixBaseX, leftJustify, minWidth, precision, zeroPad);
			case 'o':
				return formatBaseX(value, 8, prefixBaseX, leftJustify, minWidth, precision, zeroPad);
			case 'x':
				return formatBaseX(value, 16, prefixBaseX, leftJustify, minWidth, precision, zeroPad);
			case 'X':
				return formatBaseX(value, 16, prefixBaseX, leftJustify, minWidth, precision, zeroPad)
					.toUpperCase();
			case 'u':
				return formatBaseX(value, 10, prefixBaseX, leftJustify, minWidth, precision, zeroPad);
			case 'i':
			case 'd':
				number = +value || 0;
				// Plain Math.round doesn't just truncate
				number = Math.round(number - number % 1);
				prefix = number < 0 ? '-' : positivePrefix;
				value = prefix + pad(String(Math.abs(number)), precision, '0', false);
				return justify(value, prefix, leftJustify, minWidth, zeroPad);
			case 'e':
			case 'E':
			case 'f': // Should handle locales (as per setlocale)
			case 'F':
			case 'g':
			case 'G':
				number = +value;
				prefix = number < 0 ? '-' : positivePrefix;
				method = ['toExponential', 'toFixed', 'toPrecision']['efg'.indexOf(type.toLowerCase())];
				textTransform = ['toString', 'toUpperCase']['eEfFgG'.indexOf(type) % 2];
				value = prefix + Math.abs(number)[method](precision);
				return justify(value, prefix, leftJustify, minWidth, zeroPad)[textTransform]();
			default:
				return substring;
		}
	};

	return format.replace(regex, doFormat);
}

function trim(str, charlist) {
	//  discuss at: http://phpjs.org/functions/trim/
	// original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	// improved by: mdsjack (http://www.mdsjack.bo.it)
	// improved by: Alexander Ermolaev (http://snippets.dzone.com/user/AlexanderErmolaev)
	// improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	// improved by: Steven Levithan (http://blog.stevenlevithan.com)
	// improved by: Jack
	//    input by: Erkekjetter
	//    input by: DxGx
	// bugfixed by: Onno Marsman
	//   example 1: trim('    Kevin van Zonneveld    ');
	//   returns 1: 'Kevin van Zonneveld'
	//   example 2: trim('Hello World', 'Hdle');
	//   returns 2: 'o Wor'
	//   example 3: trim(16, 1);
	//   returns 3: 6

	var whitespace, l = 0,
		i = 0;
	str += '';

	if (!charlist) {
		// default list
		whitespace =
			' \n\r\t\f\x0b\xa0\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u200b\u2028\u2029\u3000';
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

function sizeof(mixed_var, mode) {
  // discuss at: http://phpjs.org/functions/sizeof/
  // original by: Philip Peterson
  // depends on: count
  // example 1: sizeof([[0,0],[0,-4]], 'COUNT_RECURSIVE');
  // returns 1: 6
  // example 2: sizeof({'one' : [1,2,3,4,5]}, 'COUNT_RECURSIVE');
  // returns 2: 6
  return this.count(mixed_var, mode);
}

function count(mixed_var, mode) {
  //  discuss at: http://phpjs.org/functions/count/
  // original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  //    input by: Waldo Malqui Silva (http://waldo.malqui.info)
  //    input by: merabi
  // bugfixed by: Soren Hansen
  // bugfixed by: Olivier Louvignes (http://mg-crea.com/)
  // improved by: Brett Zamir (http://brett-zamir.me)
  //   example 1: count([[0,0],[0,-4]], 'COUNT_RECURSIVE');
  //   returns 1: 6
  //   example 2: count({'one' : [1,2,3,4,5]}, 'COUNT_RECURSIVE');
  //   returns 2: 6

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
      if (mode == 1 && mixed_var[key] && (mixed_var[key].constructor === Array || mixed_var[key].constructor ===
        Object)) {
        cnt += this.count(mixed_var[key], 1);
      }
    }
  }

  return cnt;
}

function htmlspecialchars (string, quoteStyle, charset, doubleEncode) {
    //       discuss at: http://locutus.io/php/htmlspecialchars/
    //      original by: Mirek Slugen
    //      improved by: Kevin van Zonneveld (http://kvz.io)
    //      bugfixed by: Nathan
    //      bugfixed by: Arno
    //      bugfixed by: Brett Zamir (http://brett-zamir.me)
    //      bugfixed by: Brett Zamir (http://brett-zamir.me)
    //       revised by: Kevin van Zonneveld (http://kvz.io)
    //         input by: Ratheous
    //         input by: Mailfaker (http://www.weedem.fr/)
    //         input by: felix
    // reimplemented by: Brett Zamir (http://brett-zamir.me)
    //           note 1: charset argument not supported
    //        example 1: htmlspecialchars("<a href='test'>Test</a>", 'ENT_QUOTES')
    //        returns 1: '&lt;a href=&#039;test&#039;&gt;Test&lt;/a&gt;'
    //        example 2: htmlspecialchars("ab\"c'd", ['ENT_NOQUOTES', 'ENT_QUOTES'])
    //        returns 2: 'ab"c&#039;d'
    //        example 3: htmlspecialchars('my "&entity;" is still here', null, null, false)
    //        returns 3: 'my &quot;&entity;&quot; is still here'
    var optTemp = 0
    var i = 0
    var noquotes = false
    if (typeof quoteStyle === 'undefined' || quoteStyle === null) {
        quoteStyle = 2
    }
    string = string || ''
    string = string.toString()
    if (doubleEncode !== false) {
        // Put this first to avoid double-encoding
        string = string.replace(/&/g, '&amp;')
    }
    string = string
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
    var OPTS = {
        'ENT_NOQUOTES': 0,
        'ENT_HTML_QUOTE_SINGLE': 1,
        'ENT_HTML_QUOTE_DOUBLE': 2,
        'ENT_COMPAT': 2,
        'ENT_QUOTES': 3,
        'ENT_IGNORE': 4
    }
    if (quoteStyle === 0) {
        noquotes = true
    }
    if (typeof quoteStyle !== 'number') {
        // Allow for a single string or an array of string flags
        quoteStyle = [].concat(quoteStyle)
        for (i = 0; i < quoteStyle.length; i++) {
            // Resolve string input to bitwise e.g. 'ENT_IGNORE' becomes 4
            if (OPTS[quoteStyle[i]] === 0) {
                noquotes = true
            } else if (OPTS[quoteStyle[i]]) {
                optTemp = optTemp | OPTS[quoteStyle[i]]
            }
        }
        quoteStyle = optTemp
    }
    if (quoteStyle & OPTS.ENT_HTML_QUOTE_SINGLE) {
        string = string.replace(/'/g, '&#039;')
    }
    if (!noquotes) {
        string = string.replace(/"/g, '&quot;')
    }
    return string
}

function rawurlencode (str) {
    //       discuss at: http://locutus.io/php/rawurlencode/
    //      original by: Brett Zamir (http://brett-zamir.me)
    //         input by: travc
    //         input by: Brett Zamir (http://brett-zamir.me)
    //         input by: Michael Grier
    //         input by: Ratheous
    //      bugfixed by: Kevin van Zonneveld (http://kvz.io)
    //      bugfixed by: Brett Zamir (http://brett-zamir.me)
    //      bugfixed by: Joris
    // reimplemented by: Brett Zamir (http://brett-zamir.me)
    // reimplemented by: Brett Zamir (http://brett-zamir.me)
    //           note 1: This reflects PHP 5.3/6.0+ behavior
    //           note 1: Please be aware that this function expects \
    //           note 1: to encode into UTF-8 encoded strings, as found on
    //           note 1: pages served as UTF-8
    //        example 1: rawurlencode('Kevin van Zonneveld!')
    //        returns 1: 'Kevin%20van%20Zonneveld%21'
    //        example 2: rawurlencode('http://kvz.io/')
    //        returns 2: 'http%3A%2F%2Fkvz.io%2F'
    //        example 3: rawurlencode('http://www.google.nl/search?q=Locutus&ie=utf-8')
    //        returns 3: 'http%3A%2F%2Fwww.google.nl%2Fsearch%3Fq%3DLocutus%26ie%3Dutf-8'

    str = (str + '')

    // Tilde should be allowed unescaped in future versions of PHP (as reflected below),
    // but if you want to reflect current
    // PHP behavior, you would need to add ".replace(/~/g, '%7E');" to the following.
    return encodeURIComponent(str)
        .replace(/!/g, '%21')
        .replace(/'/g, '%27')
        .replace(/\(/g, '%28')
        .replace(/\)/g, '%29')
        .replace(/\*/g, '%2A')
}
