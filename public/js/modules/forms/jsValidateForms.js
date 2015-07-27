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

/*
function addFieldForm(){
    var countElement = $("#inputCountElements").val();

    var cboStatus    = $("#divSelectStatus").html();
    var cboCats      = $("#divSelectCats").html();
    var cboType      = $("#divSelectTipos").html();
    var cboReq       = $("#divSelectReq").html();

    $('#FormData4 tr:last').before('<tr>'+
                        '<td>'+
                            '<input name="aElements['+countElement+'][id]" type="hidden" value="-1"/>'+
                            '<input id="inputOp'+countElement+'" name="aElements['+countElement+'][op]" type="hidden" value="new"/>'+
                            '<input id="inputElement'+countElement+'" name="aElements['+countElement+'][orden]" type="text" class="input-inline form-control col-xd-2"    value="'+(parseInt(countElement)+1)+'"  autocomplete="off">'+                                
                        '</td>'+
                        '<td>'+
                            '<input id="inputDesc'+countElement+'" name="aElements['+countElement+'][desc]" type="text" class="input-inline form-control col-xd-2"  value=""  autocomplete="off">'+                                                 
                        '</td>'+
                        '<td>'+
                           '<select id="inputReq'+countElement+'" name="aElements['+countElement+'][requerido]">'+
                                cboReq+
                            '</select>'+
                        '</td>'+
                        '<td>'+ 
                            '<select id="inputStat'+countElement+'" name="aElements['+countElement+'][status]">'+
                                cboStatus+
                            '</select>'+
                        '</td>'+                        
                        '<td>'+
                            '<select id="inputTipo'+countElement+'" name="aElements['+countElement+'][tipo]" onChange="validateSelected(this.value,'+countElement+');">'+
                                cboType+
                            '</select>'+
                        '</td>'+
                        '<td>'+
                            '<div class="col-xs-12 no-margin-l">'+
                                '<div class="col-xs-4 no-margin-l">'+                                    
                                    '<button id="buttonOps'+countElement+'"  style="display:none;" class="btn btn-default btn-sm icon-only" onClick="showCloseOptions('+countElement+');return false;"><i id="spanOptions'+countElement+'" class="fa fa-sort-down"></i></button>'+
                                '</div>'+
                                '<div class="col-xs-4 no-margin-l">'+
                                    '<button class="btn btn-default btn-sm icon-only deleteLink" onClick="deleteFieldForm(this,'+countElement+');return false;"><i class="fa  fa-times-circle-o"></i></button>'+
                                '</div>'+                                                
                            '</div>'+ 
                        '</td>'+
                    '</tr>'+
                    '<tr id="trOptions'+countElement+'" style="background-color:#f5f5f5;">'+
                        '<td colspan="6">'+
                            '<div id="divOptions'+countElement+'" class="col-md-12 iDivsopts'+countElement+'" style="display:none;">'+
                                '<div class="col-md-2">'+ 
                                    '<span>Opciones (Delimitados por comas <i>ej:uno,dos,tres</i>):</span>'+
                                '</div>'+
                                '<div class="col-md-10">'+ 
                                    '<textarea id="inputOps'+countElement+'" name="aElements['+countElement+'][options]" rows="4" class="col-xs-12 no-padding"></textarea>'+                               
                                '</div>'+                            
                            '</div>'+
                            '<div id="divOptsCat'+countElement+'" class="col-md-12 iDivsopts'+countElement+'" style="display:none;">'+
                                '<div class="col-md-2">'+ 
                                    '<span>Catálogo</span>'+
                                '</div>'+
                                '<div class="col-md-4">'+ 
                                    '<select id="inputCatalogo'+countElement+'" name="aElements['+countElement+'][idcatalog]">'+
                                        cboCats+
                                    '</select>'+
                                '</div>'+
                            '</div>'+
                            '<div id="divOptsMins'+countElement+'" class="col-md-12 iDivsopts'+countElement+'" style="display:none;">'+
                                '<div class="col-md-2">'+ 
                                    '<span>Valor Mínimo</span>'+
                                '</div>'+
                                '<div class="col-md-4">'+
                                    '<input type="text" id="inputMin'+countElement+'" name="aElements['+countElement+'][inputmin]" value="" class="input-inline form-control col-xd-2"/>'+
                                '</div>'+
                                '<div class="col-md-2">'+
                                    '<span>Valor Máximo</span>'+
                                '</div>'+
                                '<div class="col-md-4">'+
                                    '<input type="text" id="inputMax'+countElement+'" name="aElements['+countElement+'][inputmax]" value="" class="input-inline form-control col-xd-2"/>'+
                                '</div>'+
                            '</div>'+
                        '</td>'+
                    '</tr>');               
    countElement++;
    $("#inputCountElements").val(countElement);
}*/



/*
function deleteFieldForm(objectTable,idInput){   
    $("#inputOp"+idInput).val('del');
    var td = $(objectTable).parent().parent().parent();    
    var tr = td.parent();
        tr.fadeOut(400, function(){
            tr.hide('slow');
            $("#trOptions"  +idInput).hide('slow');
        });
}

function showCloseOptions(idInput){
    var open  = $("#spanOptions"+idInput).hasClass('fa-sort-down');
    var close = $("#spanOptions"+idInput).hasClass('fa-sort-up');
    if(open && close == false){
        $("#spanOptions"+idInput).removeClass('fa-sort-down').addClass('fa-sort-up');
        $("#trOptions"  +idInput).show();
    }
    
    if(close && open == false){
        $("#spanOptions"+idInput).removeClass('fa-sort-up').addClass('fa-sort-down');
        $("#trOptions"  +idInput).hide();        
    }
}



function showCloseSubOptions(idInput){
    var open  = $("#spanSubOptions"+idInput).hasClass('fa-sort-down');
    var close = $("#spanSubOptions"+idInput).hasClass('fa-sort-up');
    if(open && close == false){
        $("#spanSubOptions"+idInput).removeClass('fa-sort-down').addClass('fa-sort-up');
        $("#trSubOptions"  +idInput).show();
    }
    
    if(close && open == false){
        $("#spanSubOptions"+idInput).removeClass('fa-sort-up').addClass('fa-sort-down');
        $("#trSubOptions"  +idInput).hide();        
    }
}

function deleteSubFieldForm(objectTable,idInput,inputIdRow){   
    $('.iDivSubsopts'+idInput).hide(); 
    $("#inputSubOp"+idInput).val('del');

    var td = $(objectTable).parent().parent().parent();    
    var tr = td.parent();
        tr.fadeOut(400, function(){
            tr.hide('slow');
            $("#trSubOptions"  +idInput).hide('slow');
        });

    var countElement = $("#icountSubEl"+inputIdRow).val();
    $("#icountSubEl"+inputIdRow).val(parseInt(countElement)-1);

    if((parseInt(countElement)-1)==0){
        $("#divTableSubs"+inputIdRow).hide('slow');
    }
}

function addSubElements(inputIdRow,idDepende){
    var countElement = $("#icountSubEl"+inputIdRow).val();
    var cboStatus    = $("#divSelectStatus").html();
    var cboCats      = $("#divSelectCats").html();
    var cboType      = $("#divSelectTipos").html();
    var cboReq       = $("#divSelectReq").html();

    $('#divTableSubs'+inputIdRow+' tr:last').after(
        '<tr>'+
            '<td>'+
                '<i class="fa fa-minus"></i>'+
            '</td>'+
            '<td>'+
                '<input name="aElements['+inputIdRow+'][subs]['+countElement+'][id]" type="hidden" value="-1"/>'+
                '<input id="inputSubOp'+inputIdRow+'_'+countElement+'"    name="aElements['+inputIdRow+'][subs]['+countElement+'][op]"        type="hidden"   value="new"/>'+
                '<input id="inputElement'+inputIdRow+'_'+countElement+'"  name="aElements['+inputIdRow+'][subs]['+countElement+'][orden]"     type="hidden"   class="input-inline form-control col-xd-2"  value="'+inputIdRow+'"  autocomplete="off">'+
                '<input id="inputSubOrden'+inputIdRow+'_'+countElement+'" name="aElements['+inputIdRow+'][subs]['+countElement+'][suborden]"  type="text"     class="input-inline form-control col-xd-2"  value="'+(parseInt(countElement)+1)+'"  autocomplete="off">'+
                '<input id="inputDepende'+inputIdRow+'_'+countElement+'"  name="aElements['+inputIdRow+'][subs]['+countElement+'][depende]"   type="hidden"   class="input-inline form-control col-xd-2"  value="'+idDepende+'"  autocomplete="off">'+
            '</td>'+
            '<td>'+
                '<input id="inputDesc'+inputIdRow+'_'+countElement+'" name="aElements['+inputIdRow+'][subs]['+countElement+'][desc]" type="text" class="input-inline form-control col-xd-2"  value=""  autocomplete="off">'+                                                
            '</td>'+
            '<td>'+ 
                '<select id="inputReq'+inputIdRow+'_'+countElement+'" name="aElements['+inputIdRow+'][subs]['+countElement+'][requerido]">'+
                   cboReq+
                '</select>'+
            '</td>'+                       
            '<td>'+
                '<select id="inputStat'+inputIdRow+'_'+countElement+'" name="aElements['+inputIdRow+'][subs]['+countElement+'][status]">'+
                    cboStatus+
                '</select>'+
            '</td>'+
            
            '<td>'+
                '<select id="inputTipo'+inputIdRow+'_'+countElement+'" name="aElements['+inputIdRow+'][subs]['+countElement+'][tipo]" onChange="validateSubSelected(this.value,'+countElement+');">'+
                    cboType+
                '</select>'+
            '</td>'+
            '<td>'+
                '<div class="col-xs-12 no-margin-l">'+
                    '<div class="col-xs-4 no-margin-l">'+
                        '<button id="buttonOps'+inputIdRow+'_'+countElement+'" class="btn btn-default btn-sm icon-only" style="display:none;"onClick="showCloseSubOptions('+countElement+');return false;"><i id="spanSubOptions'+countElement+'" class="fa fa-sort-down"></i></button>'+
                    '</div>'+
                    '<div class="col-xs-4 no-margin-l">'+
                        '<button class="btn btn-default btn-sm icon-only deleteLink" onClick="deleteSubFieldForm(this,'+countElement+','+inputIdRow+');return false;"><i class="fa  fa-times-circle-o"></i></button>'+
                    '</div>'+
                '</div>'+
            '</td>'+                                                                    
        '</tr>'+
        '<tr id="trSubOptions'+countElement+'" style="background-color:#f5f5f5;display:none;">'+
            '<td colspan="6">'+
                '<div id="divSubOptions'+countElement+'" class="col-md-12 iDivSubsopts'+countElement+'" style="display:none;">'+
                    '<div class="col-md-2">'+ 
                        '<span>Opciones (Delimitados por comas <i>ej:uno,dos,tres</i>):</span>'+
                    '</div>'+
                    '<div class="col-md-10">'+
                        '<textarea id="inputOps'+inputIdRow+'_'+countElement+'" name="aElements['+inputIdRow+'][subs]['+countElement+'][options]" rows="4" class="col-xs-12 no-padding"></textarea>'+                                
                    '</div>'+                            
                '</div>'+
                '<div id="divSubOptsCat'+countElement+'" class="col-md-12 iDivsopts'+countElement+'"  style="display:none;">'+
                    '<div class="col-md-2">'+
                        '<span>Catálogo</span>'+
                    '</div>'+
                    '<div class="col-md-4"> '+
                        '<select id="inputCatalogo'+countElement+'" name="aElements['+inputIdRow+'][subs]['+countElement+'][idcatalog]">'+
                            cboCats+
                        '</select>'+
                    '</div>'+
                '</div>'+
                '<div id="divSubOptsMins'+countElement+'" class="col-md-12 iDivsopts'+countElement+'" style="display:none;">'+
                    '<div class="col-md-2"> '+
                        '<span>Valor Mínimo</span>'+
                    '</div>'+
                    '<div class="col-md-4"> '+
                        '<input type="text" id="inputMininputOps'+inputIdRow+'_'+countElement+'" name="aElements['+inputIdRow+'][subs]['+countElement+'][inputmin]" value="" class="input-inline form-control col-xd-2"/>'+
                    '</div>'+
                    '<div class="col-md-2">'+
                        '<span>Valor Máximo</span>'+
                    '</div>'+
                    '<div class="col-md-4">'+
                        '<input type="text" id="inputMaxinputOps'+inputIdRow+'_'+countElement+'" name="aElements['+inputIdRow+'][subs]['+countElement+'][inputmax]" value="" class="input-inline form-control col-xd-2"/>'+
                    '</div>'+
                '</div>'+
            '</td>'+
        '</tr>');
    countElement++;
    $("#icountSubEl"+inputIdRow).val(countElement); 
    $("#divTableSubs"+inputIdRow).show('slow');
}

function validateSubSelected(inputValue,idInput){
    var options = aOptions[inputValue];
    $('.iDivSubsopts'+idInput).hide();

    if(options==0){
        $("#trSubOptions"+idInput).hide('slow');
        $("#buttonSubOps"+idInput).hide('slow');
    }else{
        $("#trSubOptions"+idInput).show('slow');
        $("#buttonSubOps"+idInput).show('slow');
    }

    if(options==1){
        $("#divSubOptions"+idInput).show('slow');
    }else if(options==2){
        $("#divSubOptsMins"+idInput).show('slow');
    }else if(options==3){
        $("#divSubOptsCat"+idInput).show('slow');
    }
}
*/