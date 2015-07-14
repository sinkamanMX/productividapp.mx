var validationControl =  null;
var aOptions = Array();

$().ready(function(){
    validateFormA();
    $("#tableElements .deleteLink").on("click",function() {
        var td = $(this).parent();
        var tr = td.parent();
        //change the background color to red before removing
        tr.css("background-color","#FF3700");

        tr.fadeOut(400, function(){
            tr.remove();
        });
    }); 
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
}

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
}

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

function validateSelected(inputValue,idInput){
    var options = aOptions[inputValue];
    $('.iDivsopts'+idInput).hide();

    if(options==0){
        $("#trOptions"+idInput).hide('slow');
        $("#buttonOps"+idInput).hide('slow');
    }else{
        $("#trOptions"+idInput).show('slow');
        $("#buttonOps"+idInput).show('slow');
    }

    if(options==1){
        $("#divOptions"+idInput).show('slow');
    }else if(options==2){
        $("#divOptsMins"+idInput).show('slow');
    }else if(options==3){
        $("#divOptsCat"+idInput).show('slow');
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

    $('#tableDepend'+inputIdRow+' tr:last').after(
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
                        '<button id="buttonOps'+inputIdRow+'_'+countElement+'" class="btn btn-default btn-sm icon-only" onClick="showCloseSubOptions('+countElement+');return false;"><i id="spanSubOptions'+countElement+'" class="fa fa-sort-down"></i></button>'+
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