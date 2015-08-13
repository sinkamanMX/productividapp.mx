$( document ).ready(function() {
    $("#sidebar").addClass("menu-compact");
    $(".sidebar-toggler").toggleClass("active");

    var nowTemp = new Date();
    var now     = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
    var dateInter  = parseInt(nowTemp.getMonth())+1;  
    var todayMonth = (dateInter<10) ? "0"+dateInter : dateInter;
    var todayDay   = (nowTemp.getDate()<10) ? "0"+nowTemp.getDate(): nowTemp.getDate();        

    if($("#inputFechaIn").val()==""){
      $("#inputFechaIn").val(nowTemp.getFullYear()+"-"+todayMonth+"-"+todayDay+ ' 00:00');      
    }

    if($("#inputFechaFin").val()==""){
      $("#inputFechaFin").val(nowTemp.getFullYear()+"-"+todayMonth+"-"+todayDay+ ' 23:59');    
    }
    
    var checkin = $('#inputFechaIn').datetimepicker({
        format: "yyyy-mm-dd hh:ii",
        showMeridian: false,
        autoclose: true,
        todayBtn: true,
        startDate:"2000-01-01 01:01"
    }).on('changeDate', function(ev) {
      if(ev.date.valueOf() > $('#inputFechaFin').datetimepicker('getDate').valueOf()){
        $('#inputFechaFin').datetimepicker('setDate', ev.date);   
      }

      $('#inputFechaFin').datetimepicker('setStartDate', ev.date);      
      $('#inputFechaFin').prop('disabled', false);
      $('#inputFechaFin')[0].focus();      
    });

    var checkout = $('#inputFechaFin').datetimepicker({
        format: "yyyy-mm-dd hh:ii",
        showMeridian: false,
        autoclose: true,
        todayBtn: true,
        startDate:"1920-01-01 01:01"
    }).on('changeDate', function(ev) {
      $('#inputFechaIn').datetimepicker('setEndDate', ev.date);
    });

    $('.chzn-select').select2({
        placeholder: "Select an option",
        allowClear: true
    });  


    $('#formDbman')
        .bootstrapValidator({
            live: 'true',
            excluded: [ ':hidden'],
            resetFormData: true,
            feedbackIcons: {
                valid: 'icon-checkmark-circle',
                invalid: 'icon-cancel-circle',
                validating: 'icon-spinner7'
            },
            fields: {
              inputFechaIn: {
                    validators: {
                        notEmpty: {
                            message: 'Campo requerido'
                        }
                    }
                },
              inputFechaFin: {
                    validators: {
                        notEmpty: {
                            message: 'Campo requerido'
                        }
                    }
                },
              cboInstalacion: {
                    validators: {
                        notEmpty: {
                            message: 'Campo requerido'
                        }
                    }
                },
              cboPersonal: {
                    validators: {
                        notEmpty: {
                            message: 'Campo requerido'
                        }
                    }
                },
              cboTipoCita: {
                    validators: {
                        notEmpty: {
                            message: 'Campo requerido'
                        }
                    }
                },
              cboEstatus: {
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
        }).on('err.field.fv', function(e, data) {
              data.fv.disableSubmitButtons(false);
            })
        .on('success.field.fv', function(e, data) {
            data.fv.disableSubmitButtons(false);
        });

  var oTable = $('.table').dataTable({
      "sDom": "Tflt<'row DTTTFooter'<'col-sm-6'i><'col-sm-6'p>>",
      "bPaginate": true,
      "bLengthChange": false,
      "iDisplayLength": 7,
      "oTableTools": {
          "aButtons": [
              "pdf"
          ],
          "sSwfPath": "/assets/swf/copy_csv_xls_pdf.swf"
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
});

function showPersonal(inputValue){
  $('#cboPersonal').children().remove();
  $('#cboPersonal').append('<option value="-1">Todos</option>');
  if(inputValue!="-1"){

    var options = aPersonal[inputValue];
    if(options!="SP"){
        var aDataPersonal = options.split(',');
        for(var i = 0; i < aDataPersonal.length;i++){
            var valueSelected = aDataPersonal[i];
            var sepValues     = valueSelected.split('|');
            $('#cboPersonal').append('<option value="'+sepValues[0]+'"">'+sepValues[1]+'</option>');
        }
    }else if(options!=""){
        var msg = 'Esta sucursal no cuenta con personal registrado.'
        var shortCutFunction= 'error';        
        var title           = "Atención!"

        toastr.options = {
          "closeButton": true,
          "debug": false,
          "positionClass": "toast-top-right",
          "showDuration": "1000",
          "hideDuration": "1000",
          "timeOut": "5000",
          "extendedTimeOut": "1000",
          "showEasing": "swing",
          "hideEasing": "linear",
          "showMethod": "fadeIn",
          "hideMethod": "fadeOut"
        }
        var $toast = toastr[shortCutFunction](msg, title);  
    }      
  }

  $("#cboPersonal").trigger("chosen:updated");
}