<?php
/**
 * Created by PhpStorm.
 * User: Asus
 * Date: 23/06/2018
 * Time: 20:30
 */

require dirname(__DIR__) . '/vendor/autoload.php';

//use AppleMusic\DB as db;

//$db = new db();

ini_set("allow_url_fopen", 1);
ini_set("max_execution_time", 0);

header("Content-type:application/json");


// Init
$id = 481488005;
$country = "fr";
$entity = "album";
$limit = 5;
$sort = "recent";

// make URL
$url = "https://itunes.apple.com/lookup?id=$id&entity=$entity&limit=$limit&sort=$sort&country=$country";

// cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$output = curl_exec($ch);

// process
$albums = array();

$results = json_decode($output, true);
foreach ($results["results"] as $collection) {
    if ($collection["wrapperType"] === "collection") {
        $album = array(
//            "_id" => null,
            "id" => $collection["collectionId"],
            "name" => $collection["collectionName"],
            "artistName" => $collection["artistName"],
            "date" => $collection["releaseDate"],
            "artwork" => $collection["artworkUrl100"],
//            "link" => $collection["collectionViewUrl"],
        );
        $albums[] = $album;
    }
}

// display
echo json_encode($albums);


/* TODO : requêtes

GET
- recup de la liste des albums

POST
- nouvel artiste
- nouvel album

*/