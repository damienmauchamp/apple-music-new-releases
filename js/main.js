$(function () {

    /*
	$('body').bind('touchend', function (e) {
        e.preventDefault();
        // Add your code here.
        $(this).click();
        // This line still calls the standard click event, in case the user needs to interact with the element that is being clicked on, but still avoids zooming in cases of double clicking.
    });*/

    const addArtist = $(".add-artists");
    // const addArtistSubmit = $(".add-artists-submit");
    const addArtistSubmit = $(".add-artists-label-after");
    const artistDataId = "data-am-artist-id";
    var flag = false;

    function template(data) {
        if ($(data.html).length === 0)
            return data.text;
        return $(data.html);
    }

    addArtist.select2({
        ajax: {
            url: "./ajax/search.php",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term // search term
                };
            },
            processResults: function (data) {
                // parse the results into the format expected by Select2.
                // since we are using custom formatting functions we do not need to
                // alter the remote JSON data
                return {
                    results: data
                };
            },
            cache: true
        },
        minimumInputLength: 1,
        placeholder: 'Ajouter un artiste',
        allowClear: true,
        // data: data,
        templateResult: template,
        templateSelection: template
    });


    addArtistSubmit.on("click", function () {
        $.ajax({
            url: "./ajax/addArtists.php",
            method: "GET",
            dataType: 'json',
            data: {
                artists: addArtist.val()
            },
            complete: function () {
                addArtist.val('').trigger('change');
            }
        });
    });

    /**
     * update
     * @param id
     * @param f
     */
    function update(id, f) {
        console.log("f", f, "id", id);
        var c = $("#artist-" + id).find(".section-body");
        $.ajax({
            url: "./ajax/update.php",
            // dataType: 'json',
            method: "POST",
            data: {
                f: f,
                id: id
            },
            success: function (data) {
                // console.log("success", data);
                var html = "";
                if (parseInt(f) === 1 && data["albumCount"] > 0) {
                    $(data["albums"]).each(function (key, val) {
                        html += val;
                    })
                } else {
                    html = "Vous êtes à jour.";
                }
                c.empty().append(html);
            }
        });
    }


    // $(document)
    //     .on('touchstart click', '.maj-link', function (e) {
    // alert(e.type + " maj " + $(this).attr(artistDataId));

    $(".maj-link").on("click", function () {
        if (!flag) {
            flag = true;
            setTimeout(function () {
                flag = false;
            }, 100);

            var id = $(this).attr(artistDataId);
            update(id, 1);
        }
    }).bind('touchend', function (e) {
        e.preventDefault();
        $(this).click();
    });
    // .on('touchstart click', '.suppr-link', function (e) {
    // alert(e.type + " suppr " + $(this).attr(artistDataId));

    $(".suppr-link").on("click", function () {
        if (!flag) {
            flag = true;
            setTimeout(function () {
                flag = false;
            }, 100);
            var id = $(this).attr(artistDataId);
            $("#artist-" + id).addClass("--invisible");
            update(id, 2);
        }
    }).bind('touchend', function (e) {
        e.preventDefault();
        $(this).click();
    });
    // .on('touchstart click', '.rm-artist', function (e) {

    $(".rm-artist").on("click", function () {
        var id = $(this).attr("data-artist-id");
        $.ajax({
            url: "./ajax/removeArtist.php",
            method: "GET",
            dataType: 'json',
            data: {
                artist: id
            },
            success: function () {
                $("#artist-" + id).hide();
            }, error: function (e) {
                console.log(e);
            }
        });
    }).bind('touchend', function (e) {
        e.preventDefault();
        $(this).click();
    });


    $('#nav-icon').click(function () {
        $(this).toggleClass('open');
        var mobilemenu = $('#mobile-menu');
        mobilemenu.toggle();
        mobilemenu.toggleClass('menu-open');
    });

    // $(".album").on("touchstart click", function () {
    //     alert(e.type + " album " + $(this).attr("id"));
    // });

    //$(".maj-link").on("touchstart click", function (e) {
    // $(".suppr-link").on("touchstart click", function (e) {
    // $(".rm-artist").on("click", function (e) {
});

var getNewReleases = function () {

    function getArtists() {
        return $.ajax({
            url: "./ajax/update.php",
            method: "POST",
            dataType: 'json',
            data: {f: 3}
        });
    }

    $.when(getArtists()).done(function (str) {
        var artists = JSON.parse(str);
        var count = artists.length;
        const spinner = $("#loading-spinner");
        $(artists).each(function (i, artist) {
            $.ajax({
                url: "./ajax/update.php",
                method: "POST",
                data: {
                    f: 4,
                    artist: artist
                }, success: function (data) {
                    // $("#new-albums").append(data);
                    // $(data).insertBefore(spinner);
                    $(data).insertBefore(spinner, $("#new-albums"));
                }, complete: function () {
                    if (!--count) {
                        $("#loading-spinner").hide();
                    }
                }
            });
        });
    });

};
