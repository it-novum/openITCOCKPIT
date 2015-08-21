/**
 * Make our Types available directly in the App namespace
 */
if(typeof appData.Types != 'object') {
	appData.Types = {};
}
App.Types = appData.Types;
App.Helpers = {};
App.ModuleController = {};

function __(msg) {
	if(typeof appData.jsonData.localeStrings == 'object' && typeof appData.jsonData.localeStrings[ msg ] != 'undefined') {
		return appData.jsonData.localeStrings[ msg ];
	} else {
		return msg;
	}
}

function debug(){
	return console.log.apply(console, Array.prototype.slice.call(arguments));
}


$(document).ready(function(){
	//Fix left-panel height
	$('#left-panel').css('height', parseInt($(document).innerHeight())+'px');
	
	//Fix drop down issue in mobile tables
	//$('.dropdown-toggle').off();
	
	/*
	 * Set an id for all the drop-down menus
	 */
	$('.dropdown-menu').each(function(key, object){
		$(object).attr('id',(Math.floor(Math.random() * (100000000 - 1)) + 1));
	});
	
	$('table .dropdown-toggle').click(function (){
		//This is hacky shit and need to get frefactored ASAP!!
		
		if($('#uglyDropdownMenuHack').html() != ''){
			//Avoid that the menu distry it self if the user press twice on the 'open menu' arrow
			return false;
		}
		
		var $ul = $(this).next('ul');
		//console.log($ul);
		//$ul.hide();
		var offset = $(this).offset();
		$('#uglyDropdownMenuHack').attr('sourceId', $ul.attr('id'));
		$('#uglyDropdownMenuHack').html($ul.clone().attr('id', 'foobarclonezilla'));
		
		//Remove orginal element for postLinks (duplicate form is bad)
		$ul.html('');
		
		$('#uglyDropdownMenuHack')
		.children('ul')
			.addClass('animated flipInX')
			.show()
			.css({
				'position': 'absolute',
				'top': parseInt(offset.top + 20)+'px',
				'left': parseInt(offset.left - 20)+'px',
				'animation-duration': '0.4s'
			});
	});
	
	
	$(document).on('hidden.bs.dropdown', function(){
		//Restore orginal menu content
		$('#'+$('#uglyDropdownMenuHack').attr('sourceId')).html($('#foobarclonezilla').html());
		$('#uglyDropdownMenuHack').html('');
	});
	
	jQuery.ajax({
		url: "https://project.it-novum.com/s/d41d8cd98f00b204e9800998ecf8427e/de_DE-awd9nr-1988229788/6261/8/1.4.7/_/download/batch/com.atlassian.jira.collector.plugin.jira-issue-collector-plugin:issuecollector-embededjs/com.atlassian.jira.collector.plugin.jira-issue-collector-plugin:issuecollector-embededjs.js?collectorId=aabdde90",
		type: "get",
		cache: true,
		dataType: "script"
	});
	

});