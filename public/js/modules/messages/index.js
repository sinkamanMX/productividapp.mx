parent.App.startPageLoading();
$("body").modalmanager('loading');

$( document ).ready(function() {
    var idFirst = $("#txtContactFirst").val();
    if(idFirst>-1){
    	setNameContact($("#txtNameFirst").val());
    	getConversation(idFirst,'','',0);  	    	 
    }else{
      App.stopPageLoading();
      $("body").modalmanager('removeLoading');
    }

  	setTimeout(function() {
  	     scrolling()
  	}, 100);

    $("#inputMessage").keypress(function (e) {
        if (e.which == 13) {
            if ($("#inputMessage").val()!="") {
                sendMessage();
            }else{
	            var shortCutFunction= 'error';
	            var msg             = "Debe de ingresar un mensaje a enviar.";
	            var title           = "Atención!"

	            toastr.options = {
	              "closeButton": true,
	              "debug": false,
	              "positionClass": "toast-top-right",
	              "showDuration": "1000",
	              "hideDuration": "1000",
	              "timeOut": "5000",
	              "extendedTimeOut": "1000",
	              "showEasing": "swing",
	              "hideEasing": "linear",
	              "showMethod": "fadeIn",
	              "hideMethod": "fadeOut"
	            }
	            var $toast = toastr[shortCutFunction](msg, title);      
            }
            return false;
        }
    });

    var showNotif = $("#inputShowAlert").val();
    if(showNotif==1){
        var shortCutFunction= 'success';
        var msg             = "El Mensaje se ha enviado Correctamente.";
        var title           = "Atención!"

        toastr.options = {
          "closeButton": true,
          "debug": false,
          "positionClass": "toast-top-right",
          "showDuration": "1000",
          "hideDuration": "1000",
          "timeOut": "5000",
          "extendedTimeOut": "1000",
          "showEasing": "swing",
          "hideEasing": "linear",
          "showMethod": "fadeIn",
          "hideMethod": "fadeOut"
        }
        var $toast = toastr[shortCutFunction](msg, title);
    }           
});

function scrolling(){
    var cont = $('#chats');
    var list = $('.chats', cont);
    $('.scroller', cont).slimScroll({
        scrollTo: list.height()
    }); 

	$('#inputMessage').attr("disabled", false);
	$('#buttonSend').attr("disabled", false);
}

function setNameContact(nameContact){
	$("#txtNameContacto").html("Mensajes de "+nameContact);
}

function getConversation(inputContacto,Message,action){
  $("body").modalmanager('loading');
	$("#txtInput").val(inputContacto);	
	$('#inputMessage').attr("disabled", true);
	$('#buttonSend').attr("disabled", true);
	var iTimeShow = $("#txtInputTime").val();

	App.startPageLoading();	
	$('#divChat').html('');	
	validation = false;

  	if(action==""){
  		validation = true;	    
  	}else if(action=="new" && Message!="" ){
		  validation = true;
    }else{
        var shortCutFunction= 'error';
        var msg             = "Debe de ingresar un mensaje a enviar.";
        var title           = "Atención!"

        toastr.options = {
          "closeButton": true,
          "debug": false,
          "positionClass": "toast-top-right",
          "showDuration": "1000",
          "hideDuration": "1000",
          "timeOut": "5000",
          "extendedTimeOut": "1000",
          "showEasing": "swing",
          "hideEasing": "linear",
          "showMethod": "fadeIn",
          "hideMethod": "fadeOut"
        }
        var $toast = toastr[shortCutFunction](msg, title);      
    }

    if(validation){
	    $.ajax( {
	      type: "POST",
	      url: "/messages/main/chatmessages",
	      data: {catId: inputContacto, inputMsg: Message, optReg: action, iTime : iTimeShow},
	        success: function(data) {            
	        	$('#divChat').html(data);
	        	$("#inputMessage").val("");            
            setTimeout(function() {
                           scrolling()
                      }, 100);
            App.stopPageLoading();
            $("body").modalmanager('removeLoading');
	        }
	    });    	
      App.stopPageLoading();
      $("body").modalmanager('removeLoading'); 
    }
}

function setTimetoShow(iTime){
	if(!$(".time"+iTime).hasClass("active")){
		$(".btnTime").removeClass("active");
		$("#txtInputTime").val(iTime);
		$(".time"+iTime).addClass("active");
		var idContacto = $("#txtInput").val();	
		getConversation(idContacto,'','');			
	}
}

function sendMessage(){
	var inputCatId = $("#txtInput").val();
	var message    = $("#inputMessage").val();
	
	getConversation(inputCatId,message,'new');
}