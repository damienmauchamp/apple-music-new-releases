<!DOCTYPE html>
<html>
<head>
    <script src="./libs/jquery/jquery-1.11.3.min.js"></script>
    <script src="./libs/jquery/jquery-migrate-1.2.1.min.js"></script>
    <link href="./libs/select2/select2.min.css" rel="stylesheet"/>
    <script src="./libs/select2/select2.min.js"></script>
    <script>
        $(document).ready(function () {

            $(".test").select2({
                ajax: {
                    url: "./ajax/search.php",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        console.log(params);
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
                minimumInputLength: 2,
                placeholder: 'Select an option'
            });


            $(".testGo").on("click", function () {
                var select2 = $(".test");
                $.ajax({
                    url: "./ajax/addArtists.php",
                    dataType: 'json',
                    data: {
                        artists: select2.val()
                    },
                    success: function (data) {
                        console.log(data);
                        select2.select2("val", "");
                    }
                })
            })


        });
    </script>
</head>
<body>


<select class="test" name="artists[]" multiple="multiple" style="width:100%"></select>
<button class="testGo">Ajouter</button>

<!--<form action="." method="post">-->
<!--    <select class="test" name="artists[]" multiple="multiple" style="width:100%">-->
<!--    </select>-->
<!--    <input type="submit" value="GO">-->
<!--</form>-->


<?php
require __DIR__ . '/vendor/autoload.php';

use AppleMusic\DB as db;
use AppleMusic\API as api;

$db = new db;

// Récupération d'un artiste
//echo $db->getUsersArtists();
$artists = json_decode($db->getUsersArtists());

foreach ($artists as $artist) {
    $id = $artist->id;
    $lastUpdate = $artist->lastUpdate;
    $api = new api($id);
    $new = $api->update($lastUpdate);
    // MAJ de l'artiste
//    $db->updated($id);

    if ($new) {
        echo "
    <h3>$artist->name</h3>
    <pre>";
        print_r($new);
        echo "</pre>
    <hr/>";
    }

//    var_dump($artist->name);
//    var_dump($new);
}
//echo $db->getUserReleases();


//var_dump($artist);

//echo $db->addArtist();
?>
</body>
</html>
