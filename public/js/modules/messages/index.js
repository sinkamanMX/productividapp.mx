parent.App.startPageLoading();
$("body").modalmanager('loading');

$( document ).ready(function() {
    App.stopPageLoading();
    $("body").modalmanager('removeLoading');

    var idFirst = $("#txtContactFirst").val();
    if(idFirst>-1){
    	getConversation(idFirst,'','');
    }
});

function getConversation(inputContacto,Message,action){
	$("#chats").modalmanager('loading');
	App.startPageLoading();	
	$('#chats').html('');
    $.ajax( {
      type: "POST",
      url: "/messages/main/chatmessages",
      data: {catId: inputContacto, inputMsg: Message, optReg: action},
        success: function(data) {
        	$('#chats').html(data);

			var cont = $('#chats');
			var list = $('.chats', cont);
			$('.scroller', cont).slimScroll({
	        	scrollTo: list.height()
	        });

			$("#chats").modalmanager('removeLoading');
			App.stopPageLoading();
        }
    });	
}

function sendMessage(){
	var inputCatId = $("#txtInput").val();
	var message    = $("#inputMessage").val();
	
	getConversation(inputCatId,message,'new');
}