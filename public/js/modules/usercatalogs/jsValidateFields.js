var validationControl =  null;
/*
$().ready(function(){

});
*/
function addFieldForm(){
    var countElement = $("#inputCountElements").val();
    var cboStatus    = $("#divSelectStatus").html();

    $('#FormData3 tr:last').before('<tr>'+    
                            '<td>'+
                                '<input name="aElements['+countElement+'][id]" type="hidden" value="-1"/>'+
                                '<input id="inputOp'+countElement+'" name="aElements['+countElement+'][op]" type="hidden" value="new"/>'+
                                '<input id="inputDesc'+countElement+'" name="aElements['+countElement+'][desc]" type="text" class="input-inline form-control col-xd-2"  value=""  autocomplete="off">'+
                            '</td>'+
                            '<td>'+ 
                                '<select id="inputStat'+countElement+'" name="aElements['+countElement+'][status]">'+ 
                                    cboStatus+ 
                                '</select>'+
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