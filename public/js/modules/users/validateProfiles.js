$().ready(function() {
    var formData = $('#FormData');
    var dError   = $('.alert-danger' , formData);
    var dSucess  = $('.alert-success', formData);

    formData.validate({
        errorElement: 'span', //default input error message container
        errorClass  : 'help-block', // default input error message class
        focusInvalid: false, // do not focus the last invalid input
        ignore      : "",
        rules: {
            inputDescripcion:  "required"
        },
        messages: {
            inputDescripcion   : "Campo Requerido",                           
        },

        invalidHandler: function (event, validator) { //display error alert on form submit              
            /*success1.hide();
            error1.show();
            App.scrollTo(error1, -200);*/
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

function optionAll(inputCheck){
    if(inputCheck){
        $('.chkOn').prop('checked', true);         
    }else{
        $('.chkOn').prop('checked', false);
    }
}