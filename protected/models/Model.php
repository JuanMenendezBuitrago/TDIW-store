<?php

abstract class Model {
	protected $_tableName = '';

	protected $_pdo = null;

	protected $_validated = false;

	protected $_isNewRecord;


	public function isValid($field = null) {
		if(!$this->_validated){
			$this->validate();
		}

		if ($field == null) {
			return $this->_validated && (sizeof($this->errors) == 0);
		}

		return $this->_validated && (sizeof($this->errors[$field]) == 0);
	}


	public function findById($id) {
		try {
		    // set the PDO error mode to exception
		    $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		    $sql = "Select * from ".$this->_tableName." WHERE id = :id"; 

		    $stmt = $this->_pdo->prepare($sql);
			$stmt->bindParam(':id', $id, PDO::PARAM_STR); 
			$stmt->execute();

			$result = $stmt->fetch(PDO::FETCH_ASSOC); 
			// $time = new DateTime($result['created'],new DateTimeZone('UTC'));
			// $time->setTimeZone(new DateTimeZone('Europe/Madrid'));
			return $result;
			// return $sql;
		} catch (PDOException $e) {
			return $sql . "<br>" . $e->getMessage();
		}
			  

	}
	public function findAll($conditions = null) {
		$condString = "";
		if($conditions !== null){
			$condArray = array();
			foreach($conditions as $column => $value) {
				$condArray[] = "`$column` = :".$column;
			}
			$condString = " WHERE ".implode(" AND ", $condArray);
		}

		try {
		    // set the PDO error mode to exception
		    $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		    $sql = "Select * from ".$this->_tableName.$condString; 

		    $stmt = $this->_pdo->prepare($sql);
		    foreach($conditions as $column => $value) {
				$stmt->bindParam(':'.$column, $value, PDO::PARAM_STR); 
		    }

			$stmt->execute();

			$result = $stmt->fetchAll(PDO::FETCH_ASSOC); 
			// $time = new DateTime($result['created'],new DateTimeZone('UTC'));
			// $time->setTimeZone(new DateTimeZone('Europe/Madrid'));
			return $result;
			// return $sql;
		} catch (PDOException $e) {
			return $sql . "<br>" . $e->getMessage();
		}
	}

	protected function _massAssignment($obj,$attributes){
		foreach($attributes as $name=>$value) {
			if(property_exists($obj, $name)){
				$this->$name = $value;
			}
		}
	}

	abstract public function save();
	abstract public function update();
	abstract public function attributesLabels();
}