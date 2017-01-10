<?php
require_once(dirname(__FILE__).'/Model.php');

class Order extends Model {
	protected $_tableName = 'orders';

	public $id;
	public $user_id;
	public $username;
	public $code;
	public $status;
	public $updated;
	public $products = array();
	public $total=0;

	public function __construct($attributes=null){
		$this->_pdo = $GLOBALS['pdo'];
		if($attributes !== null)
			$this->_massAssignment($this,$attributes);
	}

	public function attributesLabels() {
		return [
			'id'     => 'id',
			'user'   => 'usuari',
			'code'   => 'códi',
			'status' => 'estat',
			'updated' => 'darrera modificación',
			'products' => 'productes',
			'total'=>'total',
		];
	}

	protected function _validate($scenario) {	

		// validate status 
		if(in_array($scenario, ['create','update']) && !isset($this->status)) $this->status = '1';

		// validate status
		if (in_array($scenario, ['create','update']) && !preg_match("/[0-9]/", $this->status))  {
			$this->_addError('status', "Sencer entre 0 y 9.");
		}	
		
		$this->_validated = true;
	}

	public function save() {
		if ($this->isValid('create')) {

		    $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		    $sql = "INSERT INTO {$this->_tableName} (user_id, code, created, updated, status) VALUES (:user_id, :code, now(), now(), :status)";
		    $stmt = $this->_pdo->prepare($sql);

		    $stmt->bindParam(':user_id', $this->user_id, PDO::PARAM_STR); 
		    $stmt->bindParam(':code', $this->code, PDO::PARAM_STR); 
		    $stmt->bindParam(':status', $this->status, PDO::PARAM_STR); 
		    $stmt->execute();

			if($stmt->rowCount()==0) {
		    	return null;
		    }

		    $this->id = $this->_pdo->lastInsertId();
		    $this->products = $this->_saveOrderProducts();
			$this->total = $this->_computeTotal();
			$this->_updateTotal();
			return $this;
		}
		throw new ValidationException($this->getErrors(), "Order is not valid.");
	}

	private function _saveOrderProducts() {
		$products = $this->_getProductsInCart();

		$sql = "INSERT INTO order_product (order_id, product_id, amount) VALUES (:order_id, :product_id, :amount)";
		$stmt = $this->_pdo->prepare($sql);

		$result = array();
		foreach ($products as $product_id => $amount) {
		    $stmt->bindParam(':order_id', $this->id, PDO::PARAM_STR);
		    $stmt->bindParam(':product_id', $product_id, PDO::PARAM_STR); 
		    $stmt->bindParam(':amount', $amount, PDO::PARAM_INT ); 
		    $stmt->execute();

			if($stmt->rowCount()==1) {
				if($this->_reduceStock($product_id, $amount))				
		    		$result[] = array('order_id'=>$this->id, 'product_id'=>$product_id, 'amount'=>$amount);
		    }
		}
		return $result;
	}

	private function _reduceStock($product_id, $amount) {
		$sql = "UPDATE products SET stock = stock - :amount WHERE id = :product_id";
		$stmt = $this->_pdo->prepare($sql);

		$result = array();
	    $stmt->bindParam(':product_id', $product_id, PDO::PARAM_STR); 
	    $stmt->bindParam(':amount', $amount, PDO::PARAM_INT ); 
	    $stmt->execute();

		return $stmt->rowCount()==1;
	}

	public function _computeTotal() {

		$this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "Select sum(p.`price` * op.`amount`) as total  from order_product op, products p WHERE op.`order_id` = :id AND op.`product_id` = p.`id` GROUP BY op.`order_id`";

		$stmt = $this->_pdo->prepare($sql);
		$stmt->bindParam(':id', $this->id, PDO::PARAM_STR);
		$stmt->execute();

		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			return $row['total'];
		}

		return 0;
	}

	private function _getProductsInCart() {
		if(!isset($_SESSION['cart']))
			$_SESSION['cart'] = array();

		return $_SESSION['cart'];
	}

	public function update() {}
	public function _updateTotal() {
		    // set the PDO error mode to exception
		    $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		    $sql = "UPDATE {$this->_tableName} 
		    		SET total = :total
		    		WHERE id = :id";

		    $stmt = $this->_pdo->prepare($sql);
		    $stmt->bindParam(':total', $this->total, PDO::PARAM_STR);
		    $stmt->bindParam(':id', $this->id, PDO::PARAM_STR);
		    $stmt->execute();

		    if($stmt->rowCount()==0) {
		    	return null;
		    }
		    return $this;
	}

	public function findProductsInOrder($orderId) {

		$this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    $sql = "Select op.`order_id`, o.`updated`, p.`id`, p.`name`, p.`price`, op.`amount`, p.`price` * op.`amount` as subtotal  from orders o, order_product op, products p WHERE o.`id` = :id AND op.`order_id` = o.`id` AND op.`product_id` = p.`id`";

	    $stmt = $this->_pdo->prepare($sql);
		$stmt->bindParam(':id', $orderId, PDO::PARAM_STR); 
		$stmt->execute();

		$result = array();
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			$result[] = $row;
		}
		
		$result = count($result)==0?null:$result;
		return $result;
	}

	public function findAll($conditions = null) {
		$condString = "";
		if(count($conditions) > 0){
			$condArray = array();
			foreach($conditions as $column => $value) {
				$condArray[] = "`$column` = :".$column;
			}
			$condString = " AND ".implode(" AND ", $condArray);
		}

		try {
		    // set the PDO error mode to exception
		    $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		    $sql = "Select o.*, u.username, u.status as user_status from orders o, users u WHERE u.`id`=o.`user_id` ".$condString;

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

	public function delete($id) {

	    // set the PDO error mode to exception
	    $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    $sql1 = "DELETE FROM ".$this->_tableName." WHERE id = :id"; 
	    $sql2 = "DELETE FROM order_product WHERE order_id = :id"; 

	    $stmt = $this->_pdo->prepare($sql2);
		$stmt->bindParam(':id', $id, PDO::PARAM_STR); 
		$stmt->execute();

		if($stmt->rowCount() >= 0) {
			$stmt = $this->_pdo->prepare($sql1);
			$stmt->bindParam(':id', $id, PDO::PARAM_STR); 
			$stmt->execute();
			if($stmt->rowCount() > 0) {
				return true;
			}
		} 
		
		return false;
	}
}