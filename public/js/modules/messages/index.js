parent.App.startPageLoading();
$("body").modalmanager('loading');

$( document ).ready(function() {
    App.stopPageLoading();
    $("body").modalmanager('removeLoading');
});

function getConversation(inputContacto){
	$("#chats").modalmanager('loading');
	parent.App.startPageLoading();

	$( "#chats" ).load( "/messages/main/chatmessages?catId="+inputContacto, function() {
		var cont = $('#chats');
		var list = $('.chats', cont);
		$('.scroller', cont).slimScroll({
        	scrollTo: list.height()
        });
		$("#chats").modalmanager('removeLoading');
		App.stopPageLoading();
	});
}

function sendMessage(){
	var inputCatId = $("#txtInput").val();
	var message    = $("#inputMessage").val();

	$("#chats").modalmanager('loading');
	parent.App.startPageLoading();

	$( "#chats" ).load("/messages/main/chatmessages?catId="+inputCatId+"&optReg=new&inputMsg="+message, function() {
		var cont = $('#chats');
		var list = $('.chats', cont);
		$('.scroller', cont).slimScroll({
        	scrollTo: list.height()
        });
		$("#chats").modalmanager('removeLoading');
		App.stopPageLoading();
	});	
}