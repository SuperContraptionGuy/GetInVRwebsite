$(document).ready(function() {

	function getPageName(url) {
	    var index = url.lastIndexOf("/") + 1;
	    var filenameWithExtension = url.substr(index);
	    var filename = filenameWithExtension.split(".")[0]; // <-- added this line
	    return filename;                                    // <-- added this line
	}

	//	search for any element with class 'keepStats' and add click detector
	$(".keepStats").click(function(){

		//	Testing only
		// console.log("click detected");

		//	Get Session ID
		mySessionId= '<%=Session.SessionID%>';

		//	Get the elem ID of button clicked
		actionID = $(this).attr('id');

		//	Testing only
		// console.log("id of elem: ");
		// console.log(actionID);

		//	Send info to server for Database storage
		$.get("/_phpBackend/recordAction.php", {pageName: currentPageName, actionID: actionID}, function(data) {
			//	Testing only
			// $("#testObj").html(data);
		});
	});
});