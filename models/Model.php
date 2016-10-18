<?php

abstract class Model {
	protected $_tableName = '';

	protected $_pdo = null;

	protected $_validated = false;

	public function __construct(PDO $pdo, $attributes = null) {
		$this->_pdo = $pdo;
	}

	abstract public function save();
	abstract public function isValid($field = null);
	abstract public function attributesLabels();
}