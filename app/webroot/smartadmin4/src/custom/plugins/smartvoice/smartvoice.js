/*
 * SMART VOICE
 * Author: MyOrange | @bootstraphunt
 * http://www.myorange.ca
 */

SpeechRecognition = root.SpeechRecognition || root.webkitSpeechRecognition || root.mozSpeechRecognition || root.msSpeechRecognition || root.oSpeechRecognition;

// ref: http://updates.html5rocks.com/2013/01/Voice-Driven-Web-Apps-Introduction-to-the-Web-Speech-API
if (SpeechRecognition && voice_command) {

	// commands are pulled from app.config file

	// add function to button
	$.root_.on('click', '[data-action="voiceCommand"]', function(e) {

		if ($.root_.hasClass("voice-command-active")) {
			$.speechApp.stop();
			//$('#speech-btn > span > a > i').removeClass().addClass('fa fa-microphone-slash');
		} else {
			$.speechApp.start();
			//add popover
			$('#speech-btn .popover').fadeIn(350);
			//$('#speech-btn > span > a > i').removeClass().addClass('fa fa-microphone')

		}

		e.preventDefault();
	});

	//remove popover
	$(document).mouseup(function(e) {
		if (!$('#speech-btn .popover').is(e.target) && $('#speech-btn .popover').has(e.target).length === 0) {
			$('#speech-btn .popover').fadeOut(250);
		}
	});

	// create dynamic modal instance
	var modal = $('<div class="modal fade" id="voiceModal" tabindex="-1" role="dialog" aria-labelledby="remoteModalLabel" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"></div></div></div>');
	// attach to body
	modal.appendTo("body");

	//debugState
	if (debugState) {
		root.console.log("This browser supports Voice Command");
	}

	// function
	$.speechApp = (function(speech) {

		speech.start = function() {

			// Add our commands to smartSpeechRecognition
			smartSpeechRecognition.addCommands(commands);

			if (smartSpeechRecognition) {
				// activate plugin
				smartSpeechRecognition.start();
				// add btn class
				$.root_.addClass("voice-command-active");
				// play sound
				$.speechApp.playON();
				// set localStorage when switch is on manually
				if (voice_localStorage) {
					localStorage.setItem('sm-setautovoice', 'true');
				}

			} else {
				// if plugin not found
				alert("speech plugin not loaded");
			}

		};
		speech.stop = function() {

			if (smartSpeechRecognition) {
				// deactivate plugin
				smartSpeechRecognition.abort();
				// remove btn class
				$.root_.removeClass("voice-command-active");
				// sound
				$.speechApp.playOFF();
				// del localStorage when switch if off manually
				if (voice_localStorage) {
					localStorage.setItem('sm-setautovoice', 'false');
				}
				// remove popover if visible
				if ($('#speech-btn .popover').is(':visible')) {
					$('#speech-btn .popover').fadeOut(250);
				}
			}

		};

		// play sound
		speech.playON = function() {

			var audioElement = document.createElement('audio');

			if (navigator.userAgent.match('Firefox/'))
				audioElement.setAttribute('src', $.sound_path + 'voice_on' + ".ogg");
			else
				audioElement.setAttribute('src', $.sound_path + 'voice_on' + ".mp3");

			//$.get();
			audioElement.addEventListener("load", function() {
				audioElement.play();
			}, true);

			if ($.sound_on) {
				audioElement.pause();
				audioElement.play();
			}
		};

		speech.playOFF = function() {

			var audioElement = document.createElement('audio');

			if (navigator.userAgent.match('Firefox/'))
				audioElement.setAttribute('src', $.sound_path + 'voice_off' + ".ogg");
			else
				audioElement.setAttribute('src', $.sound_path + 'voice_off' + ".mp3");

			$.get();
			audioElement.addEventListener("load", function() {
				audioElement.play();
			}, true);

			if ($.sound_on) {
				audioElement.pause();
				audioElement.play();
			}
		};

		speech.playConfirmation = function() {

			var audioElement = document.createElement('audio');

			if (navigator.userAgent.match('Firefox/'))
				audioElement.setAttribute('src', $.sound_path + 'voice_alert' + ".ogg");
			else
				audioElement.setAttribute('src', $.sound_path + 'voice_alert' + ".mp3");

			$.get();
			audioElement.addEventListener("load", function() {
				audioElement.play();
			}, true);

			if ($.sound_on) {
				audioElement.pause();
				audioElement.play();
			}
		};

		return speech;

	})({});

} else {
	$("#speech-btn").addClass("display-none");
}

/*
 * SPEECH RECOGNITION ENGINE
 * Copyright (c) 2013 Tal Ater
 * Modified by MyOrange
 * All modifications made are hereby copyright (c) 2014 MyOrange
 */

(function(undefined) {"use strict";

	// Check browser support
	// This is done as early as possible, to make it as fast as possible for unsupported browsers
	if (!SpeechRecognition) {
		root.smartSpeechRecognition = null;
		return undefined;
	}

	var commandsList = [], recognition, callbacks = {
		start : [],
		error : [],
		end : [],
		result : [],
		resultMatch : [],
		resultNoMatch : [],
		errorNetwork : [],
		errorPermissionBlocked : [],
		errorPermissionDenied : []
	}, autoRestart, lastStartedAt = 0,
	//debugState = false, // decleared in app.config.js
	//debugStyle = 'font-weight: bold; color: #00f;', // decleared in app.config.js

	// The command matching code is a modified version of Backbone.Router by Jeremy Ashkenas, under the MIT license.
	optionalParam = /\s*\((.*?)\)\s*/g, optionalRegex = /(\(\?:[^)]+\))\?/g, namedParam = /(\(\?)?:\w+/g, splatParam = /\*\w+/g, escapeRegExp = /[\-{}\[\]+?.,\\\^$|#]/g, commandToRegExp = function(command) {
		command = command.replace(escapeRegExp, '\\$&').replace(optionalParam, '(?:$1)?').replace(namedParam, function(match, optional) {
			return optional ? match : '([^\\s]+)';
		}).replace(splatParam, '(.*?)').replace(optionalRegex, '\\s*$1?\\s*');
		return new RegExp('^' + command + '$', 'i');
	};

	// This method receives an array of callbacks to iterate over, and invokes each of them
	var invokeCallbacks = function(callbacks) {
		callbacks.forEach(function(callback) {
			callback.callback.apply(callback.context);
		});
	};

	var initIfNeeded = function() {
		if (!isInitialized()) {
			root.smartSpeechRecognition.init({}, false);
		}
	};

	var isInitialized = function() {
		return recognition !== undefined;
	};

	root.smartSpeechRecognition = {
		// Initialize smartSpeechRecognition with a list of commands to recognize.
		// e.g. smartSpeechRecognition.init({'hello :name': helloFunction})
		// smartSpeechRecognition understands commands with named variables, splats, and optional words.
		init : function(commands, resetCommands) {

			// resetCommands defaults to true
			if (resetCommands === undefined) {
				resetCommands = true;
			} else {
				resetCommands = !!resetCommands;
			}

			// Abort previous instances of recognition already running
			if (recognition && recognition.abort) {
				recognition.abort();
			}

			// initiate SpeechRecognition
			recognition = new SpeechRecognition();

			// Set the max number of alternative transcripts to try and match with a command
			recognition.maxAlternatives = 5;
			recognition.continuous = true;
			// Sets the language to the default 'en-US'. This can be changed with smartSpeechRecognition.setLanguage()
			recognition.lang = voice_command_lang || 'en-US';

			recognition.onstart = function() {
				invokeCallbacks(callbacks.start);
				//debugState
				if (debugState) {
					root.console.log('%c ✔ SUCCESS: User allowed access the microphone service to start ', debugStyle_success);
					root.console.log('Language setting is set to: ' + recognition.lang, debugStyle);
				}
				$.root_.removeClass("service-not-allowed");
				$.root_.addClass("service-allowed");
			};

			recognition.onerror = function(event) {
				invokeCallbacks(callbacks.error);
				switch (event.error) {
					case 'network':
						invokeCallbacks(callbacks.errorNetwork);
						break;
					case 'not-allowed':
					case 'service-not-allowed':
						// if permission to use the mic is denied, turn off auto-restart
						autoRestart = false;
						$.root_.removeClass("service-allowed");
						$.root_.addClass("service-not-allowed");
						//debugState
						if (debugState) {
							root.console.log('%c WARNING: Microphone was not detected (either user denied access or it is not installed properly) ', debugStyle_warning);
						}
						// determine if permission was denied by user or automatically.
						if (new Date().getTime() - lastStartedAt < 200) {
							invokeCallbacks(callbacks.errorPermissionBlocked);
						} else {
							invokeCallbacks(callbacks.errorPermissionDenied);
							//console.log("You need your mic to be active")
						}
						break;
				}
			};

			recognition.onend = function() {
				invokeCallbacks(callbacks.end);
				// smartSpeechRecognition will auto restart if it is closed automatically and not by user action.
				if (autoRestart) {
					// play nicely with the browser, and never restart smartSpeechRecognition automatically more than once per second
					var timeSinceLastStart = new Date().getTime() - lastStartedAt;
					if (timeSinceLastStart < 1000) {
						setTimeout(root.smartSpeechRecognition.start, 1000 - timeSinceLastStart);
					} else {
						root.smartSpeechRecognition.start();
					}
				}
			};

			recognition.onresult = function(event) {
				invokeCallbacks(callbacks.result);

				var results = event.results[event.resultIndex], commandText;

				// go over each of the 5 results and alternative results received (we've set maxAlternatives to 5 above)
				for (var i = 0; i < results.length; i++) {
					// the text recognized
					commandText = results[i].transcript.trim();
					if (debugState) {
						root.console.log('Speech recognized: %c' + commandText, debugStyle);
					}

					// try and match recognized text to one of the commands on the list
					for (var j = 0, l = commandsList.length; j < l; j++) {
						var result = commandsList[j].command.exec(commandText);
						if (result) {
							var parameters = result.slice(1);
							if (debugState) {
								root.console.log('command matched: %c' + commandsList[j].originalPhrase, debugStyle);
								if (parameters.length) {
									root.console.log('with parameters', parameters);
								}
							}
							// execute the matched command
							commandsList[j].callback.apply(this, parameters);
							invokeCallbacks(callbacks.resultMatch);

							// for commands "sound on", "stop" and "mute" do not play sound or display message
							//var myMatchedCommand = commandsList[j].originalPhrase;

							var ignoreCallsFor = ["sound on", "mute", "stop"];

							if (ignoreCallsFor.indexOf(commandsList[j].originalPhrase) < 0) {
								// play sound when match found
								$.smallBox({
									title : (commandsList[j].originalPhrase),
									content : "loading...",
									color : "#333",
									sound_file : 'voice_alert',
									timeout : 2000
								});

								if ($('#speech-btn .popover').is(':visible')) {
									$('#speech-btn .popover').fadeOut(250);
								}
							}// end if

							return true;
						}
					} // end for
				}// end for

				invokeCallbacks(callbacks.resultNoMatch);
				//console.log("no match found for: " + commandText)
				$.smallBox({
					title : "Error: <strong>" + ' " ' + commandText + ' " ' + "</strong> no match found!",
					content : "Please speak clearly into the microphone",
					color : "#a90329",
					timeout : 5000,
					icon : "fa fa-microphone"
				});
				if ($('#speech-btn .popover').is(':visible')) {
					$('#speech-btn .popover').fadeOut(250);
				}
				return false;
			};

			// build commands list
			if (resetCommands) {
				commandsList = [];
			}
			if (commands.length) {
				this.addCommands(commands);
			}
		},

		// Start listening (asking for permission first, if needed).
		// Call this after you've initialized smartSpeechRecognition with commands.
		// Receives an optional options object:
		// { autoRestart: true }
		start : function(options) {
			initIfNeeded();
			options = options || {};
			if (options.autoRestart !== undefined) {
				autoRestart = !!options.autoRestart;
			} else {
				autoRestart = true;
			}
			lastStartedAt = new Date().getTime();
			recognition.start();
		},

		// abort the listening session (aka stop)
		abort : function() {
			autoRestart = false;
			if (isInitialized) {
				recognition.abort();
			}
		},

		// Turn on output of debug messages to the console. Ugly, but super-handy!
		debug : function(newState) {
			if (arguments.length > 0) {
				debugState = !!newState;
			} else {
				debugState = true;
			}
		},

		// Set the language the user will speak in. If not called, defaults to 'en-US'.
		// e.g. 'fr-FR' (French-France), 'es-CR' (Español-Costa Rica)
		setLanguage : function(language) {
			initIfNeeded();
			recognition.lang = language;
		},

		// Add additional commands that smartSpeechRecognition will respond to. Similar in syntax to smartSpeechRecognition.init()
		addCommands : function(commands) {
			var cb, command;

			initIfNeeded();

			for (var phrase in commands) {
				if (commands.hasOwnProperty(phrase)) {
					cb = root[commands[phrase]] || commands[phrase];
					if ( typeof cb !== 'function') {
						continue;
					}
					//convert command to regex
					command = commandToRegExp(phrase);

					commandsList.push({
						command : command,
						callback : cb,
						originalPhrase : phrase
					});
				}
			}
			if (debugState) {
				root.console.log('Commands successfully loaded: %c' + commandsList.length, debugStyle);
			}
		},

		// Remove existing commands. Called with a single phrase, array of phrases, or methodically. Pass no params to remove all commands.
		removeCommands : function(commandsToRemove) {
			if (commandsToRemove === undefined) {
				commandsList = [];
				return;
			}
			commandsToRemove = Array.isArray(commandsToRemove) ? commandsToRemove : [commandsToRemove];
			commandsList = commandsList.filter(function(command) {
				for (var i = 0; i < commandsToRemove.length; i++) {
					if (commandsToRemove[i] === command.originalPhrase) {
						return false;
					}
				}
				return true;
			});
		},

		// Lets the user add a callback of one of 9 types:
		// start, error, end, result, resultMatch, resultNoMatch, errorNetwork, errorPermissionBlocked, errorPermissionDenied
		// Can also optionally receive a context for the callback function as the third argument
		addCallback : function(type, callback, context) {
			if (callbacks[type] === undefined) {
				return;
			}
			var cb = root[callback] || callback;
			if ( typeof cb !== 'function') {
				return;
			}
			callbacks[type].push({
				callback : cb,
				context : context || this
			});
		}
	};

}).call(this);

var autoStart = function() {

	smartSpeechRecognition.addCommands(commands);

	if (smartSpeechRecognition) {
		// activate plugin
		smartSpeechRecognition.start();
		// add btn class
		$.root_.addClass("voice-command-active");
		// set localStorage when switch is on manually
		if (voice_localStorage) {
			localStorage.setItem('sm-setautovoice', 'true');
		}

	} else {
		// if plugin not found
		alert("speech plugin not loaded");
	}
}
// if already running with localstorage
if (SpeechRecognition && voice_command && localStorage.getItem('sm-setautovoice') == 'true') {
	autoStart();
}

// auto start
if (SpeechRecognition && voice_command_auto && voice_command) {
	autoStart();
}