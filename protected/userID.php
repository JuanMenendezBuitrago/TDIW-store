<?php

class UserID {
	public $id       = 0;
	public $userName = 'guest';
	public $isAdmin  = false;

	public function __construct__($id, $userName, $isAdmin) {
		$this->id       = $id;
		$this->userName = $userName;
		$this->isAdmin  = $isAdmin;
	}
}