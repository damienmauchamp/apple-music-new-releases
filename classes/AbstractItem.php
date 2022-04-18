<?php

namespace AppleMusic;

use DateTime;

abstract class AbstractItem {

	protected $date;

	public function isNew(): ?bool {

		if(!$this->date) {
			return null;
		}

		$date = new DateTime($this->date);
		$diff = $date->diff(new DateTime());

		return $diff->days < DAYS
			&& $diff->y < 1;
	}

}