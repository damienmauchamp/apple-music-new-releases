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
    const mailAlert = $("#mail-alert");
    var flag = false;

    function template(data) {
        if ($(data.html).length === 0)
            return data.text;
        return $(data.html);
    }

    mailAlert.on("change", function() {
        var state = $(this).prop("checked");
        $.ajax({
            url: "./ajax/update.php",
            method: "POST",
            data: {
                f: 5,
                notif: state
            },
            success(response) {
                if (!response)
                    $(this).prop("checked", !state);
            }
        });
    });

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

    /*
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
    });*/
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


    /* CONTEXT MENU */
    // Trigger action when the contexmenu is about to be shown
    $(document).bind("contextmenu", function (event) {

        var $album;
        if ($(this).hasClass('album')) {
            $album = $(this);
        } else if (event.target.closest('.album')) {
            $album = $(event.target.closest('.album'));
        } else {
            return true;
        }
        
        // Avoid the real one
        event.preventDefault();

        $(".custom-menu li[data-action='open-itunes'] a").attr('href', $album.data('itunes-link'));
        $(".custom-menu li[data-action='open-browser'] a").attr('href', $album.attr('href'));

        // Show contextmenu
        $(".custom-menu").finish().toggle(100).
        
        // In the right position (the mouse)
        css({
            top: event.pageY + "px",
            left: event.pageX + "px"
        });
    });

    // If the document is clicked somewhere
    /**
     * @todo: if right-click, OK but hide.
     * @todo: if wheel-click, nothing happens
     * @todo: if mousewheel, prevent and hide
     */
    $(document).bind("mousedown", function (e) {
        
        // If the clicked element is not the menu
        if (!$(e.target).parents(".custom-menu").length > 0) {
            
            // Hide it
            $(".custom-menu").hide(100);

            $(".custom-menu li a").attr('href', '#');
        }
    });

    // If the menu element is clicked
    $(document).on('click', '.custom-menu li', function(e){
        
        // This is the triggered action name
        switch($(this).attr("data-action")) {
            
            // A case for each action. Your actions here
            case "first":
                alert("first");
                break;
            case
                "second":
                alert("second");
                break;
            case
                "third":
                alert("third");
                break;
        }
      
        // Hide it AFTER the action was triggered
        $(".custom-menu").hide(100);
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
