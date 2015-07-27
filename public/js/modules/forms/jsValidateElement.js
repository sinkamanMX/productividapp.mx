var validationControl =  null;
var aOptions = Array();

$().ready(function(){
    validateFormA()

    var rowCount = $('#table-relation tr').length;
    
    if(rowCount==1){
        $(".icon-drag").hide('slow');
        $(".icon-add").show('slow');
    }

        $('.table-sortable tbody').sortable({
            handle: 'span',
            connectWith: '.connected'
        }).bind('dragend.h5s', function(e, ui) {
            var rowCount = $('#table-relation tr').length;

            if(rowCount==1){
                $(".icon-drag").hide('slow');
                $(".icon-add").show('slow');
            }else{
                $(".icon-drag").show('slow');
                $(".icon-add").hide('slow');     

                $('.table-sortable tbody').sortable({
                    handle: 'span',
                    connectWith: '.connected'
                });
            }
        });      
});

function validateFormA(){  
    $('#formDbman').bootstrapValidator({
        live: 'true',
        excluded: [':disabled', ':hidden'],
        resetFormData: true,
        feedbackIcons: {
            valid: 'icon-checkmark-circle',
            invalid: 'icon-cancel-circle',
            validating: 'icon-spinner7'
        },
        fields: {
            inputOrden: {
                validators: {
                    notEmpty: {
                        message: 'Campo requerido'
                    },
                    numeric: {
                        message: 'Este campo acepta solo números'
                    },
                }
            },
            inputDescripcion: {
                validators: {
                    notEmpty: {
                        message: 'Campo requerido'
                    }
                }
            },
            inputSubOrden: {
                validators: {
                    notEmpty: {
                        message: 'Campo requerido'
                    },
                    numeric: {
                        message: 'Este campo acepta solo números'
                    },
                }
            },
            inputRequerido: {
                validators: {
                    notEmpty: {
                        message: 'Campo requerido'
                    }
                }
            },
            inputStatus: {
                validators: {
                    notEmpty: {
                        message: 'Campo requerido'
                    }
                }
            },
            inputEspera: {
                validators: {
                    notEmpty: {
                        message: 'Campo requerido'
                    }
                }
            },
            inputTipo: {
                validators: {
                    notEmpty: {
                        message: 'Campo requerido'
                    }
                }
            },
            inputDepende: {
                validators: {
                    notEmpty: {
                        message: 'Campo requerido'
                    }
                }
            },
            inputOpciones: {
                validators: {
                    notEmpty: {
                        message: 'Campo requerido'
                    },
                    callback: {
                        message: 'El listado debe tener al menos 2 opciones',
                        callback: function (value, validator, $field) {
                            var answer = false;
                            if(value!=""){                                
                                var aOpciones = value.split(/[(,)]+/);

                                if(aOpciones.length>1){
                                    answer =  true;
                                }
                            }
                            return answer;
                        }
                    }
                }
            },
            inputCatalogo: {
                validators: {
                    notEmpty: {
                        message: 'Campo requerido'
                    }
                }
            },
            inputMin: {
                validators: {
                    notEmpty: {
                        message: 'Campo requerido'
                    },
                    numeric: {
                        message: 'Este campo acepta solo números'
                    },
                    lessThan: {
                        value    : "inputMax",
                        message  : 'Debe de ser menor que el No. Máximo.',
                    }  
                }
            },
            inputMax: {
                validators: {
                    notEmpty: {
                        message: 'Campo requerido'
                    },
                    numeric: {
                        message: 'Este campo acepta solo números'
                    },
                    greaterThan: {
                        value    : "inputMin",                         
                        message  : 'Debe de ser mayor que el No. Mínimo.',
                    }                    
                }
            },
        }        
    }).on('success.form.fv', function(e) {
        $('.loading-container').removeClass('loading-inactive');
            e.preventDefault();
            var $form = $(e.target);
            var fv = $form.data('FormDataGral');
                fv.defaultSubmit();
    }).on('err.field.fv', function(e, data) {
            data.fv.disableSubmitButtons(false);
        })
    .on('success.field.fv', function(e, data) {
        data.fv.disableSubmitButtons(false);
    });

    if($("#catId").val()>-1 && $("#inputDepende").val() ==-1){
        validateDepend($("#inputDepende").val());
        validateOption($("#inputTipo").val());        
    }

    if($("#inhiddenOpts").val()!=""){
        $("#formDataOpts").show('slow');
        $('.divOpsShow').slimscroll({
            position: 'right',
            size: '4px',
            color: themeprimary,
            height: "100px",
        });
    }

    $('.tableRel').slimscroll({
        position: 'right',
        size: '4px',
        color: themeprimary,
        height: $(".tab-content").height() - 120,
    });
}

function validateOption(inputValue){    
    var options = aOptions[inputValue];
    $('.iDivsopts').hide();

    if(options==1){
        $("#divOptions").show('slow');
        $('#formDbman').bootstrapValidator('enableFieldValidators', 'inputOpciones', true); 
        $('#formDbman').bootstrapValidator('enableFieldValidators', 'inputCatalogo', false); 
        $('#formDbman').bootstrapValidator('enableFieldValidators', 'inputMin', false); 
        $('#formDbman').bootstrapValidator('enableFieldValidators', 'inputMax', false);     
    }else if(options==2){
        $(".divOptsMins").show('slow');
        $('#formDbman').bootstrapValidator('enableFieldValidators', 'inputOpciones', false); 
        $('#formDbman').bootstrapValidator('enableFieldValidators', 'inputCatalogo', false); 
        $('#formDbman').bootstrapValidator('enableFieldValidators', 'inputMin', true); 
        $('#formDbman').bootstrapValidator('enableFieldValidators', 'inputMax', true); 
    }else if(options==3){
        $("#divOptsCat").show('slow');
        $('#formDbman').bootstrapValidator('enableFieldValidators', 'inputOpciones', false); 
        $('#formDbman').bootstrapValidator('enableFieldValidators', 'inputCatalogo', true); 
        $('#formDbman').bootstrapValidator('enableFieldValidators', 'inputMin', false); 
        $('#formDbman').bootstrapValidator('enableFieldValidators', 'inputMax', false);
    }

    $('#formDbman').bootstrapValidator('updateStatus', 'inputCatalogo', 'NOT_VALIDATED');
    $('#formDbman').bootstrapValidator('updateStatus', 'inputMin', 'NOT_VALIDATED');
    $('#formDbman').bootstrapValidator('updateStatus', 'inputMax', 'NOT_VALIDATED');
    $('#formDbman').bootstrapValidator('updateStatus', 'inputOpciones', 'NOT_VALIDATED');
}

function validateDepend(inputValue){
    if(inputValue==-1){
        $("#inputOrden").attr('readonly', false);
        $("#inputEspera").val("");
        $("#inputSubOrden").val("-1");
        $("#divRespuesta").hide('slow');   
        $("#divSubOrden").hide('slow');
        $("#formDataOpts").hide('slow');

        $("#divOpsShow").html('');

        $('#formDbman').bootstrapValidator('enableFieldValidators', 'inputEspera', false); 
        $('#formDbman').bootstrapValidator('enableFieldValidators', 'inputSubOrden', false);               
    }else{
        var options = OptionsDepende[inputValue];
        var misOpciones = options.split('|');
        $("#inputOrden").val(misOpciones[1]).attr('readonly', true);    
        $("#divSubOrden").show('slow');

        $("#divRespuesta").show('slow');
        $("#formDataOpts").show('slow');
        $('#formDbman').bootstrapValidator('enableFieldValidators', 'inputEspera', true); 
        $('#formDbman').bootstrapValidator('enableFieldValidators', 'inputSubOrden', true);        
        
        drawOpts(misOpciones[0]);
    }

    $('#formDbman').bootstrapValidator('updateStatus', 'inputEspera', 'NOT_VALIDATED');
    $('#formDbman').bootstrapValidator('updateStatus', 'inputSubOrden', 'NOT_VALIDATED');
}

function drawOpts(dataOpciones){        
    $('#ulElements').empty();
    if(dataOpciones!=""){
        var aDatos = dataOpciones.split(',');    
        for(var i = 0; i < aDatos.length;i++){
            $('#ulElements').append('<li onClick="setOptionValue(\'' + aDatos[i] + '\')" class="list-group-item"><span class="cursor-hand glyphicon glyphicon-arrow-left"></span> ' + aDatos[i] + '</li>');
        }       
        $('.divOpsShow').slimscroll({
            position: 'right',
            size: '4px',
            color: themeprimary,
            height: "100px",
        });
    }else{
        $("#formDataOpts").hide('slow');
        $("#divOpsShow").html('');        
    }
}

function setOptionValue(valueElement){
    $("#inputEspera").val(valueElement);
}

function copyElement(idElement){
    $('#table-relation tbody').append($( "#tr"+idElement ).clone());
    
    $( "#tr"+idElement ).fadeOut(400, function(){
        $( "#tr"+idElement ).remove();
    });

    $(".icon-drag").show('slow');
    $(".icon-add").hide('slow');

    $('.table-sortable tbody').sortable({
        handle: 'span',
        connectWith: '.connected'
    });    
}