$( document ).ready(function(){
    $('.login-form').validate({
        errorElement: 'span', //default input error message container
        errorClass: 'help-block', // default input error message class
        focusInvalid: false, // do not focus the last invalid input
        rules: {
            inputUsuario: {
                required: true
            },
            inputPassword: {
                required: true
            },
            remember: {
                required: false
            }
        },

        messages: {
            inputUsuario: {
                required: "Debe de ingresar un nombre de usuario."
            },
            inputPassword: {
                required: "Debe de ingresar una contraseña."
            }
        },

        invalidHandler: function (event, validator) { //display error alert on form submit   
            $('.alert-danger', $('.login-form')).show();
        },

        highlight: function (element) { // hightlight error inputs
            $(element)
                .closest('.form-group').addClass('has-error'); // set error class to the control group
        },

        success: function (label) {
            label.closest('.form-group').removeClass('has-error');
            label.remove();
        },

        errorPlacement: function (error, element) {
            error.insertAfter(element.closest('.input-icon'));
        },

        submitHandler: function (form) {
            validateLogin()
        }
    });

    $('.login-form input').keypress(function (e) {
        if (e.which == 13) {
            if ($('.login-form').validate().form()) {
                validateLogin()
            }
            return false;
        }
    });

    $('.forget-form').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",
            rules: {
                email: {
                    required: true,
                    email: true
                }
            },

            messages: {
                email: {
                    required: "Email es requerido."
                }
            },

            invalidHandler: function (event, validator) { //display error alert on form submit   

            },

            highlight: function (element) { // hightlight error inputs
                $(element)
                    .closest('.form-group').addClass('has-error'); // set error class to the control group
            },

            success: function (label) {
                label.closest('.form-group').removeClass('has-error');
                label.remove();
            },

            errorPlacement: function (error, element) {
                error.insertAfter(element.closest('.input-icon'));
            },

            submitHandler: function (form) {
                form.submit();
            }
        });

        $('.forget-form input').keypress(function (e) {
            if (e.which == 13) {
                if ($('.forget-form').validate().form()) {
                    $('.forget-form').submit();
                }
                return false;
            }
        });

        jQuery('#forget-password').click(function () {
            jQuery('.login-form').hide();
            jQuery('.forget-form').show();
        });

        jQuery('#back-btn').click(function () {
            jQuery('.login-form').show();
            jQuery('.forget-form').hide();
        });

        $.backstretch([
            "assets/img/bg/3.jpg",
            ], {
              fade: 1000,
              duration: 8000
        });        
});

function validateLogin(){
    $("#divpErrorLogin").hide('slow');
    var form = $( ".login-form" );
    $.ajax( {
      type: "POST",
      url: "/main/main/login",
      dataType : 'json',
      data: form.serialize(),
        success: function(data) {
            var result = data.answer;

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