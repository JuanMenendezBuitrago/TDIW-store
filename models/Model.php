<?php

abstract class Model {
	protected $_tableName = '';

	protected $_pdo = null;

	protected $_validated = false;

	protected $_isNewRecord;

	public function __construct(PDO $pdo, $attributes = null) {
		$this->_pdo = $pdo;
	}

	abstract public function save();
	abstract public function update();
	abstract public function find($conditions);
	abstract public function isValid($field = null);
	abstract public function attributesLabels();
}