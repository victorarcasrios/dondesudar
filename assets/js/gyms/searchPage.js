$(document).ready(function() {

    // Related to other scripts
    $("[name='comunidadAutonoma']").on("change", actionShowProvinciasForCA);
    $accordionsTriggers = $(".panel-heading");
    $accordionsTriggers.on("click", actionToggleRespectiveAccordion);

    // Related to this script
    NO_INFO = $("[name='noInfo']").val(); 
    
    $filterButton = $("[name='filter']");
    $ca = $("[name='comunidadAutonoma']");
    $provincia = $("[name='provincia']");
    $postcode = $("[name='postcode']");
    $searchResults = $("#searchResults");

    $filterButton.on("click", filterSearch);
    $ca.on("change", filterSearch);
    $provincia.on("change", filterSearch);
    $postcode.on("keyup", filterSearch);
    $("#accordion").on("click", "[type='checkbox']", filterSearch);
});

function filterSearch() {
    console.log("Search");

    var data = getDataToSearch();
    data.action = "search";

    $.ajax({
        url: "controllers/ajax/gymsAjaxController.php",
        type: "POST",
        data: data
    }).done(function(response) {
        console.log(response);
        var results = $.parseJSON(response);
        console.log(results);
        refreshResults(results);
    });
}

function getDataToSearch() {
    var data = new Object();
    var search = getSearchedText();
    var ca = $ca.val() || false;
    var provincia = $provincia.val() || false;
    var postcode = $postcode.val() || false;
    var features = new Array();
    $("[name='feature[]']:checked").each(function(i) {
        features[i] = $(this).val();
    });
    if (!features.length) {
        features = false;
    }

    return {search: search, ca: ca, provincia: provincia, postcode: postcode, features: features};
}

function getSearchedText() {
    var text = false;
    var params = new Array();

    if (location.search.indexOf("searchInput=") > -1) {
        text = location.href.split("searchInput=")[1];
        if (text.indexOf("%20") > -1) {
            params = text.split("%20");
            text = params.join(' ');
        }
    }
    console.log(text);
    return text;
}

function refreshResults(results) {
    var html = "";
    if (results !== "false") {
        $.each(results, function(key, val) {
            html += "<div class='col-lg-6'><div class='thumbnail'>";
            html += "<img data-src='#'><div class='caption'><h3>" + val.name + "</h3>";
            html += "<p>" + val.domicilio + "<span class='label label-warning pull-right'>" + val.provincia + "</span></p>";
            html += "<p><img src='" + val.primaryImage + "' class='img-responsive'></p><p>";
            if (!val.features) {
                html += "<span class='label label-info'>"+NO_INFO+"</span>";
            }
            else {
                $.each(val.features, function(key, val) {
                    html += "<span class='label label-success'>" + val.nombre + "</span> ";
                });
            }
            html += "</p><p><a href='?r=gyms/viewGym&gym'" + val.id + " class='btn btn-primary' role='button'>Ver más</a></p>";
            html += "</div></div></div>";
        });
    }
    $searchResults.html(html);
}