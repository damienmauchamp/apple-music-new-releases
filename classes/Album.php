<?php

namespace AppleMusic;

use AppleMusic\DB as db;

class Album
{
    private $id;
    private $name;
    private $artistName;
    private $date;
    private $artwork;
    private $link;
    private $explicit;

    public function __construct()
    {

    }

    public static function withArray($array)
    {
        $instance = new self();
        $instance->fill($array);
        return $instance;
    }

    protected function fill($array)
    {
        $this->id = $array["id"];
        $this->name = $array["name"];
        $this->artistName = $array["artistName"];
        $this->date = $array["date"];
        $this->artwork = $array["artwork"];
        $this->link = "https://itunes.apple.com/fr/album/" . $array["id"];
        $this->explicit = $array["explicit"];
    }

    public function addAlbum($idArtist)
    {
        $db = new db;
        return $db->addAlbum($this, $idArtist);
    }

    public static function objectToArray($obj)
    {
        return array(
            "id" => $obj->id,
            "name" => $obj->name,
            "artistName" => $obj->artistName,
            "date" => $obj->date,
            "artwork" => $obj->artwork,
            "explicit" => $obj->explicit
        );
    }

    public function getDate($option = "")
    {
        if ($option === "string") {
            $timestamp = strtotime($this->date);
            return date("d", $timestamp) . " " . getMonth(date("m", $timestamp), true) . " " . date("Y", $timestamp);
        }
        return $this->date;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getArtistName()
    {
        return $this->artistName;
    }

    /**
     * @param int $width
     * @return mixed
     */
    public function getArtwork($width = 100)
    {
        return str_replace("100x100bb.jpg", "{$width}x{$width}bb.jpg", $this->artwork);
    }

    public function getLink()
    {
        return $this->link;
    }

    /**
     * @return bool
     */
    public function isExplicit()
    {
        return $this->explicit;
    }

    public function isOnPreorder()
    {
        return strtotime(date(DEFAULT_DATE_FORMAT . " 00:00:00")) < strtotime(fixTZDate($this->date));
    }

    public function toString($newDisplay = null)
    {
        global $display;

        $display = $newDisplay ? $newDisplay : $display;
        $preorder = $this->isOnPreorder();
        $style = '<style>#album-' . $this->id . ' .artwork:after { content: "' . $this->getDate("string") . '" }</style>';

        return '
        <a href="' . $this->getLink() . '" target="_blank"
           id="album-' . $this->id . '"
           data-am-kind="album" data-am-album-id="' . $this->id . '"
           class="album ' . ($preorder ? "preorder" : null) . ' we-lockup ' . ($display == "row" ? null : "l-column--grid") . ' targeted-link l-column small-' . ($display == "row" ? "2" : "6") . ' medium-3 large-2 ember-view"
           title="' . $this->name . ' by ' . $this->artistName . '">
            <picture
                    class="artwork we-lockup__artwork we-artwork--lockup we-artwork--fullwidth we-artwork ember-view">
                <img src="' . $this->getArtwork(500) . '"
                     style="background-color: transparent;" class="we-artwork__image artwork-img" alt="">
            </picture>

            <h3 class="album-title we-lockup__title ' . ($this->isExplicit() ? "icon icon-after icon-explicit" : null) . '">
                <div class="we-truncate targeted-link__target we-truncate--single-line ember-view">
                    ' . $this->name . '
                </div>
            </h3>

            <h4 class="album-subtitle we-truncate we-truncate--single-line we-lockup__subtitle targeted-link__target">
                ' . $this->artistName . '
            </h4>
            
            ' . ($preorder ? $style : null) . '
        </a>';
    }
}