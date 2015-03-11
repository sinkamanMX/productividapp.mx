App.startPageLoading();
$().ready(function() { 
    App.stopPageLoading();
    var msg ='';
    if($("#txtErrorType").val()!=''){
        if($("#txtErrorType").val()=='imei'){
            msg = 'El IMEI no se encuentra disponible.';
        }else{
            msg = 'Ocurrio un error al insertar el registro..';
        }
        var shortCutFunction= 'error';        
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
    }else if($("#txtResultOk").val()=='okRegister'){
        var shortCutFunction= 'success';
        var msg             = "Datos se almacenaron correctamente.";
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


    $('#iFrameSearch').load(function(){
        $("#loader1").hide();
        $('#iFrameSearch').show();
        App.stopPageLoading();
    })       
    $("#btnSearch").click(function() { openSearch(); return false; });
    $("#btnDelRel").click(function() { deleteRowRel(); return false; });

    $( "#FormData" ).submit(function( event ) {
      event.preventDefault();
    });

    var formData = $('#FormData');
    var dError   = $('.alert-danger' , formData);
    var dSucess  = $('.alert-success', formData);

    formData.validate({
        errorElement: 'span', //default input error message container
        errorClass  : 'help-block', // default input error message class
        focusInvalid: false, // do not focus the last invalid input
        ignore      : "",
        rules: {
            inputMarca  : "required",
            inputModelo : "required",
            inputDesc   : "required",
            inputEstatus: "required",
            inputImei   : {
                required: true,
                minlength: 12,
            },
            inputTel    : {
                required: true,
                number: true,
                minlength: 10,
                maxlength: 10
            }      
        },
        messages: {
            inputMarca  : "Campo Requerido",
            inputModelo : "Campo Requerido",
            inputDesc   : "Campo Requerido",
            inputEstatus: "Campo Requerido",
            inputImei   : {
                required  : "Campo Requerido",
                minlength : "El Identificador debe mímimo de 12 dígitos",
              }, 
            inputTel    : {
                required  : "Campo Requerido",
                number    : "Este campo acepta solo números",
                minlength : "El Teléfono debe de ser de 10 dígitos",
                maxlength : "El Teléfono debe de ser de 10 dígitos"
              }                         
        },

        invalidHandler: function (event, validator) { //display error alert on form submit              
            var shortCutFunction= 'error';
            var msg             = "El registro contiene uno o mas errores, favor de verificarlos.";
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
        },

        highlight: function (element) { // hightlight error inputs
            $(element)
                .closest('.form-group').addClass('has-error'); // set error class to the control group
        },

        unhighlight: function (element) { // revert the change done by hightlight
            $(element)
                .closest('.form-group').removeClass('has-error'); // set error class to the control group
        },

        success: function (label) {
            label
                .closest('.form-group').removeClass('has-error'); // set success class to the control group
        },

        submitHandler: function (form) {
            App.startPageLoading();         
            $('.btnSave').attr("disabled", true);
            form.submit();             
        } 
    });
});

function backToMain(){
	var mainPage = $("#hRefLinkMain").val();
	location.href= mainPage;
}

function deleteRow(){	
App.startPageLoading();    
	var idItem = $("#inputDelete").val();
    $.ajax({
        url: "/main/phones/getinfo",
        type: "GET",
        dataType : 'json',
        data: { catId : idItem, 
        		optReg: 'delete'},
        success: function(data) {
            App.stopPageLoading();
            var result = data.answer; 

            if(result == 'deleted'){
            	$("#modalConfirmDelete").modal('hide'); 
            }else if(result == 'problem'){
                alert("hubo problema");          
            }else{
                alert("no hay data");          
            }
        }
    });    
}

function deleteRowRel(){   
App.startPageLoading();    
    var idItem = $("#catId").val();
    $.ajax({
        url: "/main/phones/getinfo",
        type: "GET",
        dataType : 'json',
        data: { catId : idItem, 
                optReg: 'deleteRel'},
        success: function(data) {
            App.stopPageLoading();            
            var result = data.answer; 

            if(result == 'deleted'){
                var shortCutFunction= 'success';
                var msg             = "El registro fue eliminado correctamente.";
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
                location.href = '/main/phones/getinfo?catId='+idItem;
            }else if(result == 'problem'){
              var shortCutFunction= 'error';
              var msg             = "Ocurrio un error al eliminar el registro.";
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
              var shortCutFunction= 'error';
              var msg             = "Ocurrio un error al eliminar el registro.";
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
        }
    });    
}

function openSearch(){
    parent.App.startPageLoading();  
    $("#loader1").show(); 
    $('#iFrameSearch').hide();
    $('#iFrameSearch').attr('src','/main/phones/searchactivos');
    $("#MyModalSearch").modal("show");
}

function assignValue(nameValue,IdValue){
    $("#inputIdAssign").val(IdValue);
    $("#inputSearch").val(nameValue);
    $("#static").modal("hide");
}

function backToMainModule(){
    var url = $("#hRefLinkMain").val();
    location.href=url;    
}