<?php

include_once __DIR__ . '/../classes/Display.php';
include_once __DIR__ . '/../classes/Section.php';


if (isset($page->content) && isset($page->content['sections'])) {
	foreach ($page->content['sections'] as $section) {
		echo "<pre>" . print_r($section, true) . "</pre>";
	}
}
//echo "<pre>" . print_r($page->content, true) . "</pre>";



//echo "<pre>PAGE : {$page->name}:INDEX\n\n" . print_r($page, true) . "</pre>";
//print_r(get_defined_vars());