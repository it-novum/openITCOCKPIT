/**
 * Make our Types available directly in the App namespace
 */
if(typeof appData.Types != 'object'){
    appData.Types = {};
}
App.Types = appData.Types;
App.Helpers = {};
App.ModuleController = {};

function debug(){
    return console.log.apply(console, Array.prototype.slice.call(arguments));
}
