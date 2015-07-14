$().ready(function(){
    var pageHeigth = (($("#divBodyPage").height())/2)-5;
    var oTable = $('.table').dataTable({
        //"sDom": "Tflt<'row DTTTFooter'<'col-sm-10'i><'col-sm-2'p>>",
        "scrollY": pageHeigth+"px",
        "scrollCollapse": true,
        "paging": false,
        "bLengthChange": false,
        "iDisplayLength": 10,
        "buttonSet": [],
        "bFilter": false,
        "language": {
            "sLengthMenu": "",
            "sInfo": "Mostrando _TOTAL_ registros (_START_ a _END_)",
            "sEmptyTable": "Sin registros.",
            "sInfoEmpty" : "Sin registros.",
            "sInfoFiltered": " - Filtrado de un total de  _MAX_ registros",
            "sLoadingRecords": "Leyendo información",
            "sProcessing": "Procesando",
            "sSearch": "",
            "sZeroRecords": "Sin registros",
            "oPaginate": {
              "sPrevious": "Anterior",
              "sNext": "Siguiente"
            }
        }
    }); 
});

function selectOption(inputCheck,nameInput){
    if(inputCheck){
        $("#divCheckini"+nameInput).show('slow');
    }else{
        $("#divCheckini"+nameInput).hide('slow');
    }
}
/*
function unselectAll(){

    if(inputCheck){
        $('.chkOn').prop('checked', true);         
    }else{
        $('.chkOn').prop('checked', false);
    }    
}
*/
function validateListCheksCustom(sNameForm){
    var selected = '';    
    $('#'+sNameForm+' input[type=checkbox]').each(function(){
        if (this.checked) {
            selected += $(this).val()+', ';
        }
    }); 

    if (selected != ''){
        validateList(sNameForm);
    }else{
        Notify('Debe de seleccionar al menos un modulo', 'top-right', '5000', 'danger', 'fa-exclamation-circle', true); 
    }       

    return false;    
}

function validateList(sNameForm){
    var selected = '';    
    $('#'+sNameForm+' input[type=radio]').each(function(){
        if (this.checked) {
            selected += $(this).val()+', ';
        }
    }); 

    if (selected != ''){        
        $("#"+sNameForm).submit();
    }else{
        Notify('Debe de seleccionar el modulo de inicio', 'top-right', '5000', 'danger', 'fa-exclamation-circle', true); 
    }

    return false;    
}