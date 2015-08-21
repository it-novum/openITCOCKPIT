	if(typeof window.appData == 'object' && Frontend) {
		window.App.Main = new Frontend.App(appData);

		$(document).ready(function() {
			window.App.Main.startup();
		});
	}
	else {
		console.debug('fail');
	}
