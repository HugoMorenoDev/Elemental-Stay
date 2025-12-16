$("#nombre_pais").on("input", function() {
    var term = $(this).val();
    
    if (term.length >= 2) {
        $.ajax({
            url: 'search_pais.php', 
            type: 'GET',
            data: { term: term }, 
            success: function(response) {
                var paises = JSON.parse(response);
                $("#suggestions").empty().show();
                paises.forEach(function(pais) {
                    $("#suggestions").append("<li>" + pais + "</li>");
                });
            }
        });
    } else {
        $("#suggestions").empty().hide();
    }
});

$(document).on("click", "#suggestions li", function() {
    $("#nombre_pais").val($(this).text());
    $("#suggestions").empty().hide();
});