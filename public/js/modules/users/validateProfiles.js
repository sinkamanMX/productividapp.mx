$().ready(function() {

	/*$("#FormData").validate({
        rules: {
            inputDescripcion:  "required",    
        },
        messages: {
            inputDescripcion   : "Campo Requerido",                           
        },
        submitHandler: function(form) {
            form.submit();
        }
    });	*/

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
            dSucess.hide();
            dError.show();
            App.scrollTo(dError, -200);
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
            App.startPageLoading();
            form.submit();
        },

        submitHandler: function (form) {
            dSucess.show();
            dError.hide();
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