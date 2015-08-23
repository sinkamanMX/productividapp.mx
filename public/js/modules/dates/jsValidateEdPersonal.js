$( document ).ready(function() {            
  var oTable = $('.table').dataTable({
      /*"sDom": "Tflt<'row DTTTFooter'<'col-sm-4'i><'col-sm-8'p>>",*/
      "bPaginate": true,
      "bDestroy": true,
      "bLengthChange": false,
      "iDisplayLength": 10,
      "oTableTools": {
          "aButtons": [
              "print"
          ],
          "sSwfPath": "assets/swf/copy_csv_xls_pdf.swf"
      },
      "aLengthMenu": [],
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

  var iSelectOption = $('#inputAsignado').val();
  $("input[name=bRadioInit][value=" + iSelectOption + "]").prop('checked', true);
});   

function validateUser(){
    var selected = '';    
    $('#formDbman input[type=radio]').each(function(){
        if (this.checked) {
            selected += $(this).val()+', ';
        }
    }); 

    if (selected != ''){        
        $("#formDbman").submit();
    }else{
        Notify('Debe asignar un personal para esta cita', 'top-right', '5000', 'danger', 'fa-exclamation-circle', true); 
    }

    return false;    
}