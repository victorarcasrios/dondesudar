/**
 * Controla el cambio de idioma mediante el select #lang
 */

$(document).ready(function() {
    document.getElementById("lang").onchange = changeLang;
});

/**
 * Cambia el idioma cambiando la url actual
 */
function changeLang() {
    var selected = this.selectedIndex;
    var lang = this.getElementsByTagName("option")[selected].value;

    window.location.href = formUrl() + lang;
}

/**
 * Prepara la url para ser usada correctamente en la función
 * changeLang de este mismo script
 */
function formUrl() {
    var url = document.URL;

    if (url.indexOf("?") === -1) // No tiene parámetro alguno
        url += "?lang=";
    else { // Tiene al menos un parámetro
        if (url.indexOf("&") === -1)// Solo tiene un parámetro
            url = (url.indexOf("lang") === -1) ? url + "&lang=" : url.split("?")[0] + "?lang="; // no es lang o si
        else { // Tiene más de un parámetro
            var params = (url.split("?")[1]).split("&"); // Metemos todos los parámetros en un array
            url = url.split("?")[0] + "?";
            for (var p in params) {
                if (params[p].indexOf("lang") === -1) // Si no es lang
                    url += "&" + params[p];
            }
            url += "&lang=";
        }
    }
    return url;
}