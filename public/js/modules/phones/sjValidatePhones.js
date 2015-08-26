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