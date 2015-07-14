var validationControl =  null;
/*
$().ready(function(){

});
*/
function addFieldForm(){
    var countElement = $("#inputCountElements").val();        
    var cboStatus    = $("#divSelectStatus").html();
    var cboTipos     = $("#divSelectTipos").html(); 
    var cboValidas   = $("#divSelectVal").html();
    var cboOptions   = $("#divSelectOpts").html();

    $('#FormData3 tr:last').before('<tr>'+
                                    '<td>'+
                                        '<input name="aElements['+countElement+'][id]" type="hidden" value="-1"/>'+
                                        '<input id="inputOp'+countElement+'" name="aElements['+countElement+'][op]" type="hidden" value="new"/>'+
                                        '<input id="inputElement'+countElement+'" name="aElements['+countElement+'][orden]" type="text" class="input-inline form-control col-xd-2"  value="'+(parseInt(countElement)+1)+'"  autocomplete="off">'+
                                    '</td>'+
                                    '<td>'+
                                        '<select id="inputTipo'+countElement+'" name="aElements['+countElement+'][tipo]">'+
                                            cboTipos+
                                        '</select>'+
                                    '</td>'+
                                    '<td> '+
                                        '<select id="inputVal'+countElement+'" name="aElements['+countElement+'][validacion]">'+
                                            cboValidas+
                                        '</select>'+
                                    '</td>'+
                                    '<td>'+
                                        '<input id="inputDesc'+countElement+'" name="aElements['+countElement+'][desc]" type="text" class="input-inline form-control col-xd-2"  value=""  autocomplete="off">'+
                                    '</td>'+
                                    '<td>'+
                                        '<input id="inputNameBd'+countElement+'" name="aElements['+countElement+'][namebd]" type="text" class="input-inline form-control col-xd-2"  value=""  autocomplete="off">'+
                                    '</td>'+
                                    '<td>'+
                                        '<select id="inputStat'+countElement+'" name="aElements['+countElement+'][status]">'+
                                            cboStatus+
                                        '</select>'+
                                    '</td>'+
                                    '<td>'+
                                        '<select id="inputVisible'+countElement+'" name="aElements['+countElement+'][visible]">'+
                                            cboOptions+
                                        '</select>'+
                                    '</td>'+                                     
                                    '<td>'+
                                        '<div class="col-xs-12 no-margin-l">'+
                                                '<div class="col-xs-6 no-margin-l">'+
                                                    '<button class="btn btn-default btn-sm icon-only" onClick="showCloseOptions('+countElement+');return false;"><i id="spanOptions'+countElement+'" class="fa fa-sort-down"></i></button>'+
                                                '</div>'+

                                                '<div class="col-xs-6 no-margin-l">'+
                                                    '<button class="btn btn-default btn-sm icon-only deleteLink" onClick="deleteFieldForm(this,'+countElement+';return false;"><i class="fa  fa-times-circle-o"></i></button>'+
                                                '</div>'+                                                
                                        '</div>'+
                                    '</td>'+
                                '</tr>'+
                                '<tr id="trOptions'+countElement+'" style="background-color:#f5f5f5;display:none;">'+
                                    '<td colspan="8">'+
                                        '<table style="width:100%;" class="table table-striped ">'+
                                            '<tr>'+
                                                '<th>On Div</th>'+
                                                '<th>On Inserts</th>'+
                                                '<th>On Updates</th>'+
                                                '<th>Input Name</th>'+
                                                '<th>Tipo Dato</th>'+
                                                '<th>Input Value</th>'+
                                                '<th>Accion</th>'+
                                                '<th>Ops Ajax</th>'+
                                                '<th>Ops Función</th>'+
                                            '</tr>'+
                                            '<tr>'+
                                                '<td>'+
                                                    '<select id="inputContenedor'+countElement+'" name="aElements['+countElement+'][contenedor]">'+
                                                        cboOptions+
                                                    '</select>'+
                                                '</td>'+
                                                '<td>'+
                                                    '<select id="inputOninserts'+countElement+'" name="aElements['+countElement+'][oninserts]">'+
                                                        cboOptions+
                                                    '</select>'+
                                                '</td>'+
                                                '<td>'+
                                                    '<select id="inputOnupdates'+countElement+'" name="aElements['+countElement+'][onupdates]">'+
                                                        cboOptions+
                                                    '</select>'+
                                                '</td>'+                                     
                                                '<td>'+
                                                    '<input id="inputNamei'+countElement+'" name="aElements['+countElement+'][namein]" type="text" class="input-inline form-control col-xd-2"  value=""  autocomplete="off">'+
                                                '</td>'+
                                                '<td>'+
                                                    '<input id="inputTdato'+countElement+'" name="aElements['+countElement+'][tdato]" type="text" class="input-inline form-control col-xd-2"  value=""  autocomplete="off">'+
                                                '</td>'+
                                                '<td>'+
                                                    '<input id="inputIvalue'+countElement+'" name="aElements['+countElement+'][invalue]" type="text" class="input-inline form-control col-xd-2"  value=""  autocomplete="off">'+
                                                '</td>'+
                                                '<td>'+
                                                    '<input id="inputAccion'+countElement+'" name="aElements['+countElement+'][accion]" type="text" class="input-inline form-control col-xd-2"  value=""  autocomplete="off">'+
                                                '</td>'+
                                                '<td>'+
                                                    '<input id="inputOpsAjax'+countElement+'" name="aElements['+countElement+'][opsajax]" type="text" class="input-inline form-control col-xd-2"  value=""  autocomplete="off">'+
                                                '</td>'+
                                                '<td>'+
                                                    '<input id="inputFuncion'+countElement+'" name="aElements['+countElement+'][opsfuncion]" type="text" class="input-inline form-control col-xd-2"  value=""  autocomplete="off">'+
                                                '</td>'+
                                            '</tr>'+
                                            '<tr>'+
                                                '<th colspan="1">Usar Query</th>'+
                                                '<th colspan="2">Opciones Query</th>'+
                                                '<th colspan="2">Replace on Ops Query</th>'+
                                                '<th colspan="2">Validación Query</th>'+
                                                '<th colspan="2">Mensaje Validacion</th>'+                                        
                                            '</tr>'+                                   
                                            '<tr>'+
                                                '<th colspan="1">'+
                                                    '<select id="inputUsequery'+countElement+'" name="aElements['+countElement+'][usequery]">'+
                                                       cboOptions+
                                                    '</select></th>'+                                        
                                                '<td colspan="2">'+
                                                    '<textarea  id="inputOpsquery'+countElement+'" name="aElements['+countElement+'][opsquery]" rows="5" style="width:100%;"></textarea>'+
                                                '</td>'+
                                                '<td colspan="2">'+
                                                    '<textarea  id="inputOpsreplace'+countElement+'" name="aElements['+countElement+'][opsreplace]" rows="5" style="width:100%;"></textarea>'+
                                                '</td>'+                          
                                                '<th colspan="2">'+
                                                    '<textarea  id="inputvalquery'+countElement+'" name="aElements['+countElement+'][valquery]" rows="5" style="width:100%;"></textarea>'+
                                                '</th>'+
                                                '<th colspan="2">'+
                                                    '<textarea  id="inputMsgVal'+countElement+'" name="aElements['+countElement+'][msgval]"  rows="5" style="width:100%;"></textarea>'+
                                                '</th>'+                                                      
                                            '</tr>'+
                                        '</table>'+
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