var validationControl =  null;
var aOptions = Array();

$().ready(function(){
    validateFormA();

    $('.dd').nestable();
    $('.dd-handle a').on('mousedown', function (e) {
        e.stopPropagation();
    });
    
    $('.listElements').slimscroll({
        position: 'right',
        size: '4px',
        color: themeprimary,
        height: $( window ).height() - 200,
    }); 
    
    $(".widget-body").height( $( window ).height() - 200);

    $('.dd').on('change', function() {
        setTimeout(reOrderFields(), 2000);
    });    

    $("#formDbmanOrders").submit(function() {
        $.ajax({
               type: "POST",
               url:  "/forms/main/getinfo",
               data: $("#formDbmanOrders").serialize(), // serializes the form's elements.
               dataType: 'json',
               success: function(data){
                    var result = data.answer;
                    $('.loading-container').addClass('loading-inactive');            

                    if(result == 're-ordered'){
                        Notify('Los cambios se han guardado correctamente.', 'top-right', '5000', 'success', 'fa-check', true);                 
                        //location.reload();                
                    }else{
                        Notify('Ocurrio un error al eliminar el registro.', 'top-right', '5000', 'warning', 'fa-warning', true);
                    }   
               }
             });

        return false; // avoid to execute the actual submit of the form.
    });  

    /*
    $('#iFrameElement').load(function(){
        $("#loader1").hide();
        $('#iFrameElement').show();
    });
    

    $('#myLargeModalEl').bind('hide', function () {
        var catId = $("#catId").val();
        location.href='/forms/main/getinfo?catId='+catId;
     });
     */
});


function validateFormA(){  
    $('#formDbman').bootstrapValidator({
        live: 'true',
        excluded: [':disabled'],
        resetFormData: true,
        feedbackIcons: {
            valid: 'icon-checkmark-circle',
            invalid: 'icon-cancel-circle',
            validating: 'icon-spinner7'
        },
        fields: {
            inputTitulo: {
                validators: {
                    notEmpty: {
                        message: 'Campo requerido'
                    }
                }
            },
            inputDescripcion: {
                validators: {
                    notEmpty: {
                        message: 'Campo requerido'
                    }
                }
            },
            inputEstatus: {
                validators: {
                    notEmpty: {
                        message: 'Campo requerido'
                    }
                }
            },
            inputLocate: {
                validators: {
                    notEmpty: {
                        message: 'Campo requerido'
                    }
                }
            },
            inputPhotos: {
                validators: {
                    notEmpty: {
                        message: 'Campo requerido'
                    }
                }
            },
            inputQrs: {
                validators: {
                    notEmpty: {
                        message: 'Campo requerido'
                    }
                }
            },
            inputFirma: {
                validators: {
                    notEmpty: {
                        message: 'Campo requerido'
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
        }); 

    if($("#catId").val()>-1){
        $("#divInfo").removeClass('col-md-12').addClass('col-md-7');
        $("#divElementos").show('slow');
    }
    
}

function deleteElement(idElement,idForm){
    var idItem      = idElement;
    var moduleForm  = idForm;   
    $('.loading-container').removeClass('loading-inactive');
    $.ajax({
        url       : "/forms/main/delelement",
        type    : "GET",
        dataType: 'json',
        data    : { catId : idItem,
                    optReg: 'delete',
                    ssIdource: moduleForm},
        success: function(data) {
            var result = data.answer;
            $('.loading-container').addClass('loading-inactive');            

            if(result == 'deleted'){
                Notify('El registro fue eliminado correctamente.', 'top-right', '5000', 'success', 'fa-check', true);                 
                location.reload();                
            }else{
                Notify('Ocurrio un error al eliminar el registro.', 'top-right', '5000', 'warning', 'fa-warning', true);
            }            
        }
    });
}

function showinfoElement(inputElement){
    var idFormulario = $("#catId").val();    
    $("#iFrameElement").contents().find("body").html('<img alt="loading gif" src="/images/loading.gif" id="loader1" style="margin-left: 40%">');    
    $('#iFrameElement').attr('src','/forms/main/getinfoelement?catId='+inputElement+'&inputFormulario='+idFormulario);
    $("#myLargeModalEl").modal("show");
}

function closeElementDiv(iOption){
    $("#myLargeModalEl").modal("hide");  
    if(iOption==1){
        var catId = $("#catId").val();
        location.href='/forms/main/getinfo?catId='+catId;
    }
}

function reOrderFields(){
    var control = 1;
    $( "#listElements > li" ).each(function( index ) {
        $("#"+$(this).attr("id")+"_span").html(control);
        $("#"+$(this).attr("id")+"_ordn").val(control);
        $("#"+$(this).attr("id")+"_depd").val('');
        $("#"+$(this).attr("id")+"_subordn").val('null');
        var idDepende   = $("#"+$(this).attr("id")+"_id").val();
        
        var controlInt  = 1;
        $("#"+$(this).attr("id")+" .item1" ).each(function( index ){
            $("#"+$(this).attr("id")+"_depd").val(idDepende);
            $("#"+$(this).attr("id")+"_ordn").val(control);
            $("#"+$(this).attr("id")+"_subordn").val(controlInt);
            $("#"+$(this).attr("id")+"_span").html(control+"-"+controlInt);    
            controlInt++;       
        });
        control++;
    });
    setTimeout(sendNewOrder(), 2500);
}

function sendNewOrder(){
    $("#formDbmanOrders").submit();
}