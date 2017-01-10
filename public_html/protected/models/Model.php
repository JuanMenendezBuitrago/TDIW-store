<?php
require_once(dirname(__FILE__)."/../exceptions/validationException.php");

abstract class Model {

	protected $_pdo = null;

	protected $_validated = false;

	protected $_isNewRecord;

	// protected $_errors = array();
	private $_errors = array();

	protected function _isInUse($attribute, $value) {
        $result = $this->findAll([$attribute => $value]);
		if (is_array($result) && count($result) > 0)
            return true;
        return $result;
	}
	
	protected function _addError($tag, $message) {
		if(!isset($this->_errors[$tag])) {
			$this->_errors[$tag] = array();
		}
		$this->_errors[$tag][] = $message;
	}

	public function getErrors($tag = null) {
		// return all errors
		if($tag === null) {
			return $this->_errors;
		}
		// return specific errors
		if(isset($this->_errors[$tag])) {
			return $this->_errors[$tag];
		}
		// return nothing
		return null;
	}

	public function hasErrors($tag = null) {
		// return all errors
		if($tag === null) {
			return count($this->getErrors()) > 0;
		}
		// return specific errors
		if(isset($this->_errors[$tag])) {
			return count($this->getErrors($tag)) > 0;
		}
		return false;
	}

	public function setIsNewRecord($val) {
		$this->_isNewRecord = $val;
	}

	public function getIsNewRecord() {
		return $this->_isNewRecord;
	}

	public function isValid($scenario = [], $field = null) {
		if(!$this->_validated){
			$this->_validate($scenario);
		}

		if ($field == null) {
			return $this->_validated && (count($this->getErrors()) == 0);
		}

		return $this->_validated && (count($this->getErrors($field)) == 0);
	}

	public function delete($id) {

	    // set the PDO error mode to exception
	    $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    $sql = "DELETE FROM ".$this->_tableName." WHERE id = :id"; 

	    $stmt = $this->_pdo->prepare($sql);
		$stmt->bindParam(':id', $id, PDO::PARAM_STR); 
		$stmt->execute();

		if($stmt->rowCount() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function findById($id) {
	    // set the PDO error mode to exception
	    $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    $sql = "Select * from ".$this->_tableName." WHERE id = :id"; 

	    $stmt = $this->_pdo->prepare($sql);
		$stmt->bindParam(':id', $id, PDO::PARAM_STR); 
		$stmt->execute();

		if($stmt->rowCount() > 0) {
			$result = $stmt->fetch(PDO::FETCH_ASSOC); 
			$obj = new static($result);
			$obj->setIsNewRecord(false);
			return $obj;
		}
		else {
			return null;
		}
		// $time = new DateTime($result['created'],new DateTimeZone('UTC'));
		// $time->setTimeZone(new DateTimeZone('Europe/Madrid'));
		// return $sql;
	}

	public function findAll($conditions = null) {
		$condString = "";
		if(count($conditions) > 0){
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
		    if($conditions !== null) {
			    foreach($conditions as $column => $value) {
					$stmt->bindParam(':'.$column, $value, PDO::PARAM_STR); 
			    }		    	
		    }

			$stmt->execute();

			$result = [];
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				$obj = new static($row);
				$obj->setIsNewRecord(false);
				$result[] = $obj;
			}
			$result = count($result)==0?null:$result;
			return $result;
			// return $sql;
		} catch (PDOException $e) {
			return false;
		}
	}

	protected function _massAssignment($obj,$attributes){
		foreach($attributes as $name=>$value) {
			if(property_exists($obj, $name)){
				$this->$name = $value;
			}
		}
	}


	abstract protected function _validate($scenario);
	abstract public function save();
	abstract public function update();
	abstract public function attributesLabels();
}