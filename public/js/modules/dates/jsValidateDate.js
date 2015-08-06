$().ready(function(){
    $("#inputTipo").select2({
        placeholder: "Seleccione un tipo",
        allowClear: true
    });

    $("#inputCliente").select2({
        placeholder: "Seleccione un Cliente",
        allowClear: true
    });

    $('#inputFecha').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    });

    $('#inputHora').timepicker({ 
        showMeridian:false,
        template: 'dropdown',
        showSeconds:true,
        showInputs:false,
        modalBackdrop:true,
    });

    $('#formDbman')
        .bootstrapValidator({
            live: 'true',
            excluded: [':disabled', ':hidden'],
            resetFormData: true,
            feedbackIcons: {
                valid: 'icon-checkmark-circle',
                invalid: 'icon-cancel-circle',
                validating: 'icon-spinner7'
            },
            fields: {
                inputCliente: {
                    validators: {
                        callback: {
                            message: 'Debe de seleccionar un Cliente',
                            callback: function(value, validator, $field) {
                                var bRespuesta = false;
                                if(value>-1){
                                    bRespuesta = true;
                                }
                                return bRespuesta;
                            }
                        }
                    }
                },
                inputTipo: {
                    validators: {
                        callback: {
                            message: 'Debe de seleccionar un Tipo de Cita',
                            callback: function(value, validator, $field) {
                                var bRespuesta = false;
                                if(value>-1){
                                    bRespuesta = true;
                                }
                                return bRespuesta;
                            }
                        }
                    }
                }
            }
        });


});

function validateTipo(inputValue){
    var options     = aTcitas[inputValue];
    $('#ulElements').empty();
    if(options!=""){
        var misOpciones = options.split(',');
        for(var i = 0; i < misOpciones.length;i++){
            $('#ulElements').append('<li class="list-group-item"> ' + misOpciones[i] + '</li>');
        }  

        $('.divOpsShow').slimscroll({
            position: 'right',
            size: '4px',
            color: themeprimary,
            height: "100px",
        });
    }else{
        $("#formDataOpts").hide('slow');
        $('#ulElements').append('<li class="list-group-item"> Sin formularios</li>');        
    }

}