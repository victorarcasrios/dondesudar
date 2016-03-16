// QUE HACER -> Generalizar funciones para que afecten tanto a los campos de signin como de profile

$(document).ready(function() {
    USER_ID = false;
    
    $email = $("[name='email']")
    $passwords = $("[name$='assword']");
    $nick = $("[name='nick']");
    $form = $("form");

    $email.on( "keyup", emailIsAvailable);
    $passwords.on("keyup", passwordsMatch);
    $nick.on( "keyup", nickIsAvailable);
    $form.on( "submit", validateForm);
});

/**
 * Valida que ambas contraseñas introducidad coincidan
 * @returns {undefined}
 */
function passwordsMatch() {
    if ($("[name='password']").val().length > 0 && $("[name='repeatPassword']").val().length > 0) {
        if ($("[name='password']").val() !== $("[name='repeatPassword']").val()) {
            $("#passwordsMatch").toggleClass("hide", true);
            $("#passwordsDoesNotMatch").toggleClass("hide", false);
            $("[name='password']").css("border", "1px solid red");
            $("[name='repeatPassword']").css("border", "1px solid red");
        }
        else {
            $("#passwordsMatch").toggleClass("hide", false);
            $("#passwordsDoesNotMatch").toggleClass("hide", true);
            $("[name='password']").css("border", "1px solid green");
            $("[name='repeatPassword']").css("border", "1px solid green");
        }
    } else {
        $("#passwordsMatch").toggleClass("hide", true);
        $("#passwordsDoesNotMatch").toggleClass("hide", true);
        $("[name='password']").css("none");
        $("[name='repeatPassword']").css("none");
    }
}

/**
 * Evita el envío del formulario y muestra un aviso si no esta bien rellenado
 * @param {event} e
 * @returns {undefined}
 */
function validateForm(e) {
    if ($("[class*='alert-danger']").is(":visible") || $(".popover").is(":visible")) {
        e.preventDefault();
        $("#formNotFilledCorrectly").fadeIn();
        setTimeout(function() {
            $("#formNotFilledCorrectly").fadeOut();
        }, 3000);
    }
}