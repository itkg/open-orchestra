var dialog_parameter = {};

function dt_0(){
	dialog_parameter = $.extend(dialog_parameter, {"addArray" : []});
	$( "#dialog-node" ).dialog(dialog_parameter);
	loadScript(urlJs + "pagegenerator/model.js", dt_1);
}
function dt_1(){
	$('#rightbox-content').model({"type" : "node"});
}

loadScript(urlJs + "pagegenerator/dialogNode.js?" + Math.random(), dt_0);