$( document ).ready(function(){
    $('#accountForm').bootstrapValidator({
         live: 'enabled',
        // Only disabled elements are excluded
        // The invisible elements belonging to inactive tabs must be validated
        excluded: [':disabled'],
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            inputUsuario: {
                validators: {
                    notEmpty: {
                        message: 'Campo requerido'
                    },
                    emailAddress: {
                        message: 'Favor de ingresar un email válido'
                    }
                }
            },
            inputPassword: {
                validators: {
                    notEmpty: {
                        message: 'Campo requerido'
                    }
                }
            }
        }
    }).on('success.form.bv', function(e) {
        validateLogin()
        e.preventDefault();
    });
});

function validateLogin(){
    $("#divpErrorLogin").hide('slow');
    var form = $( '#accountForm');
    $.ajax( {
      type: "POST",
      url: "/main/main/login",
      dataType : 'json',
      data: form.serialize(),
        success: function(data) {
            var result = data.answer;
            $("#bSubmit").prop( "disabled", false );

            if(result == 'logged'){
                location.href='/main/main/inicio';
            }else if(result == 'problem'){
                $("#pErrorLogin").html("Por cuestion de seguridad solo se puede ingresar una vez por usuario.");
                $("#divpErrorLogin").addClass('alert-error').show('slow');
            }else{
                $("#divpErrorLogin").addClass('alert-error').show('slow');
                $("#pErrorLogin").html("Usuario y/o contraseña incorrectos");
            }
        }
    });
}