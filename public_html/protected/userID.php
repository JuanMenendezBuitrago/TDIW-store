<?php

class UserID {
	public $id       = 0;
	public $username = 'guest';
	public $isAdmin  = false;

	const GUEST = 0;
	const REGISTERED = 1;
	const ADMIN = 2;

	public function __construct($id=0, $username='guest', $isAdmin=false) {
		$this->id       = $id;
		$this->username = $username;
		$this->isAdmin  = $isAdmin;
	}

	public static function newGuest() {
		$instance = new static();
		return $instance;
	}

	public static function newAdmin($id, $username) {
		$instance = new static($id, $username, true);
		return $instance;
	}

	public static function newUser($id, $username) {
		$instance = new static($id, $username, false);
		return $instance;	
	}

	public function isAdmin() {
		return $this->getRole() == self::ADMIN;
	}

	public function isGuest() {
		return $this->getRole() == self::GUEST;
	}

	public function isRegistered() {
		return $this->getRole() == self::REGISTERED;
	}

	public function getRole() {
		if($this->isAdmin)
			return self::ADMIN;

		if($this->username == 'guest')
			return self::GUEST;

		return self::REGISTERED;
	}
}