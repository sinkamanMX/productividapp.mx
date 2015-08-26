$().ready(function(){
    var formData = $('#FormDataGral');
    $('#FormDataGral').bootstrapValidator({
        live: 'enabled',
        excluded: [':disabled'],
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            txtNameCompany  : {
                validators: {
                    notEmpty: {
                        message: 'Campo requerido'
                    }
                }
            },
            txtNameRazon : {
                validators: {
                    notEmpty: {
                        message: 'Campo requerido'
                    }
                }
            },
            txtNameDir  : {
                validators: {
                    notEmpty: {
                        message: 'Campo requerido'
                    }
                }
            },
            txtNameResp  : {
                validators: {
                    notEmpty: {
                        message: 'Campo requerido'
                    }
                }
            },
            txtNameTel      : {
                validators: {
                    notEmpty: {
                        message: 'Campo requerido'
                    },
                    numeric: {
                        message: 'Este campo acepta solo números'
                    },
                    stringLength: {
                        message: 'El Teléfono debe de ser de 10 dígitos',
                        max: 10 ,
                        min: 10 ,                        
                    },
                }
            },
            txtNameEMail : {
                validators: {
                    notEmpty: {
                        message: 'Campo requerido'
                    },
                    emailAddress: {
                        message: 'Favor de ingresar un email válido'
                    }
                }
            }
        }
    }).on('success.form.fv', function(e) {
        e.preventDefault();
        var $form = $(e.target);
        var fv = $form.data('FormDataGral');
            fv.defaultSubmit();
    });

    $('#FormConfTel').bootstrapValidator({
        live: 'enabled',
        excluded: [':disabled'],
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            cboLocalizar  : {
                validators: {
                    notEmpty: {
                        message: 'Campo requerido'
                    }
                }
            },
            txtTimeReporte : {
                validators: {
                    notEmpty: {
                        message: 'Campo requerido'
                    },
                    numeric: {
                        message: 'Este campo acepta solo números'
                    }
                }
            },
            txtTimeEncendido  : {
                validators: {
                    notEmpty: {
                        message: 'Campo requerido'
                    },
                    numeric: {
                        message: 'Este campo acepta solo números'
                    }
                }
            },
            txtTimeApagado  : {
                validators: {
                    notEmpty: {
                        message: 'Campo requerido'
                    },
                    numeric: {
                        message: 'Este campo acepta solo números'
                    }
                }
            },
            txtTimeSinRep      : {
                validators: {
                    notEmpty: {
                        message: 'Campo requerido'
                    },
                    numeric: {
                        message: 'Este campo acepta solo números'
                    }
                }
            },
            txtTituloReporteX : {
                validators: {
                    notEmpty: {
                        message: 'Campo requerido'
                    }
                }
            },
            txtTimeReporteX: {
                validators: {
                    notEmpty: {
                        message: 'Campo requerido'
                    }
                }
            }
        }
    }).on('success.form.fv', function(e) {
        e.preventDefault();
        var $form = $(e.target);
        var fv = $form.data('FormDataGral');
            fv.defaultSubmit();
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
