var bCheckMessages = false;

parent.App.startPageLoading();
$("body").modalmanager('loading');
$( document ).ready(function() {
    App.stopPageLoading();
    $("body").modalmanager('removeLoading'); 
    
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

    var idFirst = $("#txtContactFirst").val();
    if(idFirst>-1){
    	setNameContact($("#txtNameFirst").val());
    	getConversation(idFirst,'','',0);  	    	 
    }else{
      App.stopPageLoading();
      $("body").modalmanager('removeLoading');
      checkMessages();  
    }

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
    $("#txtInput").val(inputContacto);  
    $('#inputMessage').attr("disabled", true);
    $('#buttonSend').attr("disabled", true);
    parent.App.startPageLoading();
    $("body").modalmanager('loading');
    $('#divChat').html(''); 
    $('#list-group-item').removeClass('listSelect');
    $('#aList'+inputContacto).removeClass('newMessage');
    $('#aList'+inputContacto).addClass('listSelect');        

    var iTimeShow = $("#txtInputTime").val();

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
          checkMessages();              
        }
    });     
    App.stopPageLoading();
    $("body").modalmanager('removeLoading');     
}

function sendMessage(){
  parent.App.startPageLoading();
  $("body").modalmanager('loading');  
  var inputCatId = $("#txtInput").val();
  var sMessage   = $("#inputMessage").val();

  $.ajax( {
    type: "POST",
    url: "/messages/main/sendmessages",
    data: {idSendTo: inputCatId, inputMsg: sMessage, optReg: 'sendMessage'},
    dataType : 'json',    
      success: function(data){
        var iStatus = data.ResponseStatus;

        if(iStatus!=1){
          var shortCutFunction= 'error';
          var msg             = "Ocurrio un error al enviar el mensaje.";
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
        }else{        
          $('#inputMessage').attr("disabled", false);
          $('#buttonSend').attr("disabled", false);
          $("#inputMessage").val("");
          var dataMensaje = data.DataMessage;

          var MsgLeido    = '';

          if(dataMensaje.LEIDO !="" && dataMensaje.PROCESADO!="LEIDO"){
              MsgLeido = '<span class="badge badge-success badge-roundless pand0">'+
                            '<i class="fa fa-check "></i>'+
                          '</span>';
          }else if(dataMensaje.PROCESADO !="" && dataMensaje.PROCESADO!="NULL"){
              MsgLeido =  '<span class="badge badge-default badge-roundless pand0">'+
                            '<i class="fa fa-check"></i>'+                
                          '</span> ';
          }else{
              MsgLeido = '<i class="fa fa-clock-o"></i>';
          } 

          $('<li class="out">'+
              '<img class="avatar img-responsive" alt="" src="/images/icons/icon-user.png"/>'+
              '<div class="message">'+
                '<span class="arrow"></span>'+
                '<span class="body">'+ dataMensaje.MENSAJE+
                '</span>'+
                '<div class="name">'+
                  '<span class="aTimeAgoMsg">'+MsgLeido+
                    +dataMensaje.CREADO+
                  '</span>'+
                '</div>'+
              '</div>'+
            '</li>').hide().appendTo( "#listChats" ).fadeIn('slow');
          scrolling();
        }

        App.stopPageLoading();
        $("body").modalmanager('removeLoading');   
        console.log(data);
      }
  });
}

var lastMessage = -1;

function checkMessages(){
  lastMessage = $("#txtLastMessage").val();
  $.ajax( {
    type: "POST",
    url: "/messages/main/checkmessage",
    dataType : 'json',
    success: function(data) {
      var sAnswer = data;
      if(sAnswer.ID_MENSAJE != lastMessage){
        if ($('#aList'+sAnswer.ID).length > 0) {
           $('#aList'+sAnswer.ID).remove();
        }

        var sMsg = sAnswer.MENSAJE;
        var sTimeAgo = '';
        if(sAnswer.HAGODAYS>0){
          sTimeAgo = 'Mas de '+sAnswer.HAGODAYS+' dia (s).';
        }else if(sAnswer.HAGOHOURS>0){
          sTimeAgo = 'Hace '+sAnswer.HAGOHOURS +' hora (s).';
        }else if(sAnswer.HAGOMINS>0){          
          sTimeAgo = 'Hace '+sAnswer.HAGOMINS  +' minuto (s).';
        }else{
          sTimeAgo = 'Menos de 1 minuto.';
        }
        
        $("#txtContactFirst").val(sAnswer.ID);
        $("#txtLastMessage").val(sAnswer.ID_MENSAJE);
        $("#txtNameFirst").val(sAnswer.NOMBRE);
        $('#list-group-item').removeClass('listSelect');
        $('<a <a id="aList'+sAnswer.ID+'"  class="list-group-item minHeight50 newMessage" href="javascript:getConversation('+sAnswer.ID+',\' \',\' \');setNameContact(\' '+sAnswer.NOMBRE+' \');">'+
                '<img src="/images/icons/icon-user.png" class="avatar" alt="">'+
                '<span  class="tittleContact">'+sAnswer.NOMBRE+'</span>'+
                '<span class="aTimeAgoMsg">'+sMsg.substring(0, 32)+'...</span>'+
                '<span class="aTimeAgo">'+
                   '<i class="fa fa-clock-o"></i>'+sTimeAgo+
                '</span>'+
              '</a>').hide().prependTo( "#classContacts" ).fadeIn('slow');             

        var idSelect = $("#txtInput").val();
        if(data.ID==idSelect){
          $('#inputMessage').attr("disabled", false);
          $('#buttonSend').attr("disabled", false);
          
          var dataMensaje = sAnswer.MENSAJE;

          var MsgLeido    = '';

          $('<li class="in">'+
            '<img class="avatar img-responsive" alt="" src="/images/icons/icon-user.png"/>'+
            '<div class="message">'+
              '<span class="arrow">'+
              '</span>'+
              '<span class="body">'+sAnswer.MENSAJE+'</span>'+
              '<div class="name">'+sAnswer.NOMBRE+
                '<span class="aTimeAgoMsg">'+
                  '<span class="badge badge-success badge-roundless pand0">'+
                    '<i class="fa fa-check "></i>'+
                  '</span>'
                  +sAnswer.CREADO+                              
                '</span>'+
              '</div>'+
            '</div>'+          
          '</li>').hide().appendTo( "#listChats" ).fadeIn('slow');
          scrolling();
        }
      }      

      setTimeout(function(){ 
        checkMessages()
      }, 10000);
      }
  });  
}