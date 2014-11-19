$().ready(function() {
    var formData = $('#FormDataGral');
    var dError   = $('#divErrorForm1', formData);

    formData.validate({
        errorElement: 'span', //default input error message container
        errorClass  : 'help-block', // default input error message class
        focusInvalid: false, // do not focus the last invalid input
        ignore      : "",
        rules: {
            txtNameCompany  : "required",
            txtNameRazon    : "required",
            txtNameDir      : "required",
            txtNameResp     : "required",
            txtNameTel      : {
                required: true,
                number: true,
                minlength: 10,
                maxlength: 10
            },
            txtNameEMail    : {
                required: true,
                email: true
            }
        },
        messages: {
            txtNameCompany  : "Campo Requerido",
            txtNameRazon    : "Campo Requerido",
            txtNameDir      : "Campo Requerido",
            txtNameResp     : "Campo Requerido",
            txtNameTel      : {
                required  : "Campo Requerido",
                number    : "Este campo acepta solo números",
                minlength : "El Teléfono debe de ser de 10 dígitos",
                maxlength : "El Teléfono debe de ser de 10 dígitos"
            }, 
            txtNameEMail    : {
                required: "Campo Requerido",
                email: "Debe de ingresar un mail válido"
            }                         
        },

        invalidHandler: function (event, validator) { //display error alert on form submit              
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
            label
                .closest('.form-group').removeClass('has-error'); // set success class to the control group
        },

        submitHandler: function (form) {
            App.startPageLoading();         
            $('.btnSave').attr("disabled", true);
            form.submit();             
        } 
    });

    var formData2 = $('#FormConfTel');
    var dError2   = $('#divErrorForm2', formData2);

    formData2.validate({
        errorElement: 'span', //default input error message container
        errorClass  : 'help-block', // default input error message class
        focusInvalid: false, // do not focus the last invalid input
        ignore      : "",
        rules: {
            cboLocalizar    : "required",
            txtTimeReporte  : {
                required: true,
                number: true
            },
            txtTimeEncendido: {
                required: true,
                number: true
            },
            txtTimeApagado  : {
                required: true,
                number: true
            },
            txtTimeSinRep   : {
                required: true,
                number: true
            },
            txtTituloReporteX: "required",
            txtTimeReporteX: {
                required: true,
                number: true
            },
        },
        messages: {
            cboLocalizar    :  "Campo Requerido",
            txtTimeReporte  : {
                required  : "Campo Requerido",
                number    : "Este campo acepta solo números"
            }, 
            txtTimeEncendido: {
                required  : "Campo Requerido",
                number    : "Este campo acepta solo números"
            }, 
            txtTimeApagado: {
                required  : "Campo Requerido",
                number    : "Este campo acepta solo números"
            }, 
            txtTimeSinRep: {
                required  : "Campo Requerido",
                number    : "Este campo acepta solo números"
            },
            txtTituloReporteX: "Campo Requerido",
            txtTimeReporteX: {
                required  : "Campo Requerido",
                number    : "Este campo acepta solo números"
            },            
        },

        invalidHandler: function (event, validator) { //display error alert on form submit              
            dError2.show();
            App.scrollTo(dError2, -200);
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

    if($("#boolLocalizar").val()=="0"){
        $("#txtTimeReporte").rules("remove", "required");
        $("#txtTimeEncendido").rules("remove", "required");
        $("#txtTimeApagado").rules("remove", "required");
        $("#txtTimeSinRep").rules("remove", "required");
        $("#txtTituloReporteX").rules("remove", "required");
        $("#txtTimeReporteX").rules("remove", "required");
    }
});

function onChangeLoc(value){
    if(value==1){
        $("#divOptionsTime").show('slow');
        $("#txtTimeReporte").rules("add", "required");
        $("#txtTimeEncendido").rules("add", "required");
        $("#txtTimeApagado").rules("add", "required");
        $("#txtTimeSinRep").rules("add", "required");
        $("#txtTituloReporteX").rules("add", "required");
        $("#txtTimeReporteX").rules("add", "required");
    }else{
        $("#divOptionsTime").hide('slow');
        $("#txtTimeReporte").rules("remove", "required");
        $("#txtTimeEncendido").rules("remove", "required");
        $("#txtTimeApagado").rules("remove", "required");
        $("#txtTimeSinRep").rules("remove", "required");
        $("#txtTituloReporteX").rules("remove", "required");
        $("#txtTimeReporteX").rules("remove", "required");        
    }
}

function optionAll(inputCheck){
    if(inputCheck){
        $('.chkOn').prop('checked', true);         
    }else{
        $('.chkOn').prop('checked', false);
    }
}

function validateModules(){
    var selected = '';    
    $('#FormData3 input[type=checkbox]').each(function(){
        if (this.checked) {
            selected += $(this).val()+', ';
        }
    }); 

    if (selected != ''){
        $("#FormData3").submit();
    }else{
        $('#divErrorForm3').show();
    }       

    return false;    
}
