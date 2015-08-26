$().ready(function(){
     
});


function deleteFieldForm(objectTable,idInput){ 
    if($("#inputOp"+idInput).val()=='new'){
        var tr = $(objectTable).closest('tr');
            tr.fadeOut(400, function(){
                tr.remove();
            });
    }else{
        $("#inputOp"+idInput).val('del');
        var tr = $(objectTable).closest('tr');
            tr.fadeOut(400, function(){
                tr.hide('slow');
            });    
    }
}

function addFieldForm(){
    var countElement= $("#inputCountElements").val();        
    var cboStatus   = $("#divSelectStatus").html();

    $('#FormData3 tr:last').before('<tr>'+
                            '<td>'+
                                '<input name="aElements['+countElement+'][id]" type="hidden" value="-1"/>'+
                                '<input id="inputLat'+countElement+'" name="aElements['+countElement+'][lat]" type="hidden" value="0.000"/>'+
                                '<input id="inputLon'+countElement+'" name="aElements['+countElement+'][lon]" type="hidden" value="0.000"/>'+
                                '<input id="inputOp'+countElement+'"    name="aElements['+countElement+'][op]" type="hidden" value="new"/>'+
                                '<input id="inputDesc'+countElement+'"  name="aElements['+countElement+'][desc]" type="text" class="input-inline form-control col-xd-2"  value=""  autocomplete="off">'+
                            '</td>'+
                            '<td>'+
                                '<input id="inputEdo'+countElement+'" name="aElements['+countElement+'][edo]" type="text" class="input-inline form-control col-xd-2"  value=""  autocomplete="off">'+
                            '</td>'+
                            '<td>'+
                                '<input id="inputMun'+countElement+'" name="aElements['+countElement+'][mun]" type="text" class="input-inline form-control col-xd-2"  value=""  autocomplete="off">'+
                            '</td>'+
                            '<td>'+
                                '<input id="inputCol'+countElement+'" name="aElements['+countElement+'][col]" type="text" class="input-inline form-control col-xd-2"  value=""  autocomplete="off">'+
                            '</td>'+
                            '<td>'+
                                '<input id="inputCalle'+countElement+'" name="aElements['+countElement+'][calle]" type="text" class="input-inline form-control col-xd-2"  value=""  autocomplete="off">'+
                            '</td>'+
                            '<td>'+
                                '<input id="inputNoint'+countElement+'" name="aElements['+countElement+'][noint]" type="text" class="input-inline form-control col-xd-2"  value=""  autocomplete="off">'+
                            '</td>'+
                            '<td>'+
                                '<input id="inputCP'+countElement+'" name="aElements['+countElement+'][cp]" type="text" class="input-inline form-control col-xd-2"  value=""  autocomplete="off">'+
                            '</td>'+
                            '<td>'+
                                '<input id="inputNoext'+countElement+'" name="aElements['+countElement+'][noext]" type="text" class="input-inline form-control col-xd-2"  value=""  autocomplete="off">'+
                            '</td>'+
                            '<td>'+
                                '<input id="inputRefs'+countElement+'" name="aElements['+countElement+'][refs]" type="text" class="input-inline form-control col-xd-2"  value=""  autocomplete="off">'+
                            '</td>'+
                            '<td>'+
                                '<select id="inputStat'+countElement+'" name="aElements['+countElement+'][status]">'+
                                    cboStatus+
                                '</select>'+
                            '</td>'+
                            '<td>'+
                                '<a class="btn btn-danger btn-sm icon-only white" onClick="deleteFieldForm(this,'+countElement+');" href="javascript:void(0)"><i class="fa fa-times"></i></a>'+
                            '</td>'+
                        '</tr>').fadeIn("slow");                                     
    countElement++;
    $("#inputCountElements").val(countElement);
}