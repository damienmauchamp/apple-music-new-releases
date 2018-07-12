$(function () {

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
        var c = $(".artist[" + artistDataId + "=\"" + id + "\"").find(".section-body");
        $.ajax({
            url: "./ajax/update.php",
            // dataType: 'json',
            method: "POST",
            data: {
                f: f,
                id: id
            },
            success: function (data) {
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

    $(".maj-link").on("touchstart click", function () {
        if (!flag) {
            flag = true;
            setTimeout(function () {
                flag = false;
            }, 100);

            var id = $(this).attr(artistDataId);
            update(id, 1);
        }
    });
    $(".suppr-link").on("touchstart click", function () {
        if (!flag) {
            flag = true;
            setTimeout(function () {
                flag = false;
            }, 100);
            var id = $(this).attr(artistDataId);
            update(id, 2);
            $(".artist[" + artistDataId + "=\"" + id + "\"").hide();
        }
    });

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
