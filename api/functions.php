<?php

function album_filters(array $res, bool $only_explicit = true):array {
	$return = [];
	foreach($res as $i => $item) {
		foreach($item as $key => $value) {
			if(is_int($key)) {
				unset($res[$i][$key]);
			}
		}
		$res[$i]['id'] = intval($res[$i]['id']);
		$res[$i]['explicit'] = boolval($res[$i]['explicit']);
		$res[$i]['link'] = "https://music.apple.com/fr/album/".preg_replace('/-{2,}/', '-', trim(preg_replace('/[^\w-]/', '-', strtolower($item["name"])), "-"))."/".$item["id"];

		if($only_explicit) {
			// find duplicates
			$indexes = array_keys(array_filter($res, function ($element) use ($item) {
				return $element['name'] === $item['name'] && $element['artistName'] === $item['artistName'];
			}))[0];
			$return[] = $res[$indexes];
		}
	}
	return $return;
}