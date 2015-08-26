var toastCount  = 0;
$( document ).ready(function(){
    var showNotif = $("#inputShowAlert").val();
    if(showNotif==1){
      var shortCutFunction= 'error';
      var msg             = "Ocurrio un error al enviar el mensaje.";
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

jQuery('#dataTable .group-checkable').change(function () {
  var set = jQuery(this).attr("data-set");
  var checked = jQuery(this).is(":checked");

  jQuery(set).each(function () {
          if (checked) {
              $(this).attr("checked", true);
              $(this).parents('tr').addClass("active");
          } else {
              $(this).attr("checked", false);
              $(this).parents('tr').removeClass("active");
          }                    
      });
      jQuery.uniform.update(set);
  });

  jQuery('#dataTable').on('change', 'tbody tr .checkboxes', function(){
       $(this).parents('tr').toggleClass("active");
  });
    $('#dataTable').dataTable( {
          "aoColumnDefs": [
              { 'bSortable': false, 'aTargets': [0] },
              { "bSearchable": false, "aTargets": [ 0 ] }
          ],      
        "bDestroy": true,
        "bLengthChange": false,
        "bPaginate": true,
        "bFilter": true,
        "bSort": true,
        "iDisplayLength": 10,      
        "bProcessing": true,
        "bAutoWidth": true,
        "bSortClasses": false,
          "oLanguage": {
              "sInfo": "Mostrando _TOTAL_ registros (_START_ a _END_)",
              "sEmptyTable": "Sin registros.",
              "sInfoEmpty" : "Sin registros.",
              "sInfoFiltered": " - Filtrado de un total de  _MAX_ registros",
              "sLoadingRecords": "Leyendo información",
              "sProcessing": "Procesando",
              "sSearch": "Buscar:",
              "sZeroRecords": "Sin registros",
              "oPaginate": {
                "sPrevious": "Anterior",
                "sNext": "Siguiente"
              }          
          }
    } ); 

    var formData = $('#FormData');
    var dError   = $('.alert-danger' , formData);
    var dSucess  = $('.alert-success', formData);

    formData.validate({
        errorElement: 'span', //default input error message container
        errorClass  : 'help-block', // default input error message class
        focusInvalid: false, // do not focus the last invalid input
        ignore      : "",
        rules: {
            inputMessage:  "required"
        },
        messages: {
            inputMessage   : "Debe de ingresar un texto para enviar.",                           
        },

        invalidHandler: function (event, validator) { //display error alert on form submit
            var shortCutFunction= 'error';
            var msg             = "El registro contiene uno o mas errores, favor de verificarlos.";
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
        },

        highlight: function (element) { // hightlight error inputs
            $(element)
                .closest('.form-group').addClass('has-error'); // set error class to the control group
        },

        unhighlight: function (element) { // revert the change done by hightlight
            $(element)
                .closest('.form-group').removeClass('has-error'); // set error class to the control group
        },

        success: function (label) {
            label
                .closest('.form-group').removeClass('has-error'); // set success class to the control group
        },

        submitHandler: function (form) {
          var validation = false;

            if($('#checkAll').is(':checked')){
              $("#idContacts").val('all');
              validation = true;
            }else{
              var aContactos = Array();

              $( ".checkboxes" ).each(function() {
                if($( this ).is(":checked")){
                  aContactos.push($(this).val());
                }                
              });

              if(aContactos.length>0){
                $("#idContacts").val(aContactos);
                validation = true;
              }else{
                var shortCutFunction= 'error';
                var msg             = "Debe de seleccionar un contacto para enviar el mensaje.";
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

            if(validation){
              App.startPageLoading();
              $('.btnSave').attr("disabled", true);  
              form.submit();
            }
        } 
    });  

    $("#inputMessage").keypress(function (e) {
        if (e.which == 13) {
            $("#FormData").validate();
        }
    });
});