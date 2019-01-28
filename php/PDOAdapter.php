<?php
class PDOAdapter {
  private $dbh;
  private $errorLogger;
  public $rowCount;
  public $errorInfo;
//  public $lastInsertId;

	/*function __construct($dbh, $errorLogger) {
    $this->dbh 				 = $dbh;
    $this->errorLogger = $errorLogger;
	}*/
	function __construct($dsn, $username, $password, $errorLogger) {
		try {
            $this->dbh = new PDO($dsn, $username, $password);
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->dbh->exec("SET NAMES 'utf8'");
            $this->dbh->exec("SET character_set_client = utf8");
        } catch (PDOException $e) {
			echo 'PDO Error: '.$e->getMessage();
		}

		$this->errorLogger = $errorLogger;
	}
	
	public function getDbh() {
    return $this->dbh;
	}

  public function getLastInsertId() {
//  	return $this->dbh->query('SELECT LAST_INSERT_ID()')->fetchColumn();
    return $this->dbh->lastInsertId();
  }

  public function execute($method, $sql, $args=null) {
    try {
    	$this->errorInfo = null;
      switch ($method) {
      case 'selectAll':
      	return $this->selectAll($sql, $args);
      case 'selectOne':
      	return $this->selectOne($sql, $args);
      case 'execute':
      	return $this->execute_($sql, $args);
      }

    } catch (PDOException $e) {
    			$this->errorLogger->info("PDOAdapter $method() error: sql=\n$sql");
			$this->errorLogger->info("PDOAdapter $method() error: sql=\n$sql".
					(isset($args) ? "\nargs=".print_r($args, true) : '')."\n".
					$e->__toString());
      $this->errorInfo = $e->errorInfo;
      //return null;
      throw $e;
    }
  }
  
  public function rowCount() {
  	return $this->stmt->rowCount();
  }

  private function selectAll($sql, $args=null) {
//$this->errorLogger->info('%%%selectAll() $sql='.$sql. ' $args='.print_r($args, true));
   	$result = null;
  	if (isset($args)) {
			$stmt = $this->dbh->prepare($sql);
	    $result = $stmt->execute($args);
//      if ($result)
				$result = $stmt->fetchAll(PDO::FETCH_OBJ);
    } else {
			$result = $this->dbh->query($sql, PDO::FETCH_OBJ)->fetchAll();
    }
//    if ($result === false)
//			$this->errorLogger->info('<<<<<<<<<<<<<<<<<<<<<<'.print_r($this->dbh->errorInfo(), true));
    return $result;
  }

  private function selectOne($sql, $args=null) {
   	$result = null;
  	if (isset($args)) {
			$stmt = $this->dbh->prepare($sql);
	    $stmt->execute($args);
			$result = $stmt->fetch(PDO::FETCH_OBJ);
    } else {
			$result = $this->dbh->query($sql, PDO::FETCH_OBJ)->fetch();
    }
    return $result;
  }

  private function selectColumn($sql, $args=null) {
   	$result = null;
  	if (isset($args))
    	$column_number = $args[0];
    else
    	$column_number = 0;
    $result = $this->dbh->query($sql)->fetchColumn($column_number);
    return $result;
  }

  private function execute_($sql, $args=null) {
	$stmt = $this->dbh->prepare($sql);
    $success = $stmt->execute($args);
    if ($success)
    	$this->rowCount = $stmt->rowCount();
    else {
    	$this->rowCount = 0;
      	$this->errorInfo = $e->errorInfo;
    }
    return $success;
  }

/*private function errorResult($message, $retcode=1) {
  	return array('retcode'=>$retcode, 'message'=>$message);
  }

  private function successResult($result) {
  	return array('retcode'=>0, 'result'=>$result);
  }*/


	 public function prepare($sql){
	      return $this->dbh->prepare($sql);
	 }
					
	  public function executePrepared($stmt, $args=null) {
    	try {
    		$this->errorInfo = null;
    		$success = $stmt->execute($args);
		    if ($success)
		    	$this->rowCount = $stmt->rowCount();
		    else {
		    	$this->rowCount = 0;
		      	$this->errorInfo = $e->errorInfo;
		    }
		    return $success;
	    } catch (PDOException $e) {
				@$this->errorLogger->info("PDOAdapter $method() error: sql=\n$sql".
						(isset($args) ? "\nargs=".print_r($args, true) : '')."\n".
						$e->__toString());
	      $this->errorInfo = $e->errorInfo;
	      return null;
	    }
  	}
  	
	  public function selectPrepared($stmt, $args=null) {
      $stmt->execute($args);
      return $stmt->fetchAll(PDO::FETCH_OBJ);
    }


		public function getColumnMetadata($tableName, $dbName=null) {
			if (!$dbName) {
				$dbName = $this->selectColumn('select database()');
			}
	//$this->info("getColumnMetadata($tableName, $dbName)");
		  $sql = "select column_name, column_default, is_nullable, data_type
							from information_schema.columns
							where table_schema='$dbName' and table_name='$tableName'";
			$rows = $this->selectAll($sql);
	//$this->info("getColumnMetadata(): rows=".print_r($rows, true));
		  $colmeta = array();
		  foreach ($rows as $row) {
				$row = (array) $row;
				$cm = new stdclass();
				$cm->default = $row['column_default'];
				$cm->nullable = $row['is_nullable']=='YES';
		    switch ($row['data_type']) {
		    	case 'varchar':
		    	case 'longtext':
		    	case 'char':
		    	case 'mediumtext':
		    	case 'text':
		      	$type = 'string';
		        break;
		    	case 'datetime':
		    	case 'timestamp':
		    	case 'time':
		    	case 'date':
		      	$type = 'datetime';
		        break;
		    	case 'bigint':
		    	case 'int':
		    	case 'tinyint':
		    	case 'smallint':
		    	case 'mediumint':
		      	$type = 'integer';
		        break;
		    	case 'decimal':
		      	$type = 'double';
		        break;
		    	case 'bigint':
		    	case 'int':
		    	case 'tinyint':
		    	case 'smallint':
		    	case 'mediumint':
		      	$type = 'integer';
		        break;
		      default:
		      	$type = 'unknown type';
		    }
				$cm->type = $type;
		    $colmeta[$row['column_name']] = $cm;
		  }
		  return $colmeta;
		}

		public function nullToEmptyStrings($values) {
			foreach ($values as $key=>$value) {
		  	if (!isset($value)) {
		    	$values[$key] = '';
		    }
		  }
		  return $values;
		}

		public function quot_insert_values($values, $fieldNameOrder, $colmeta) {
		//		foreach($fields as $fieldName => $value){

			foreach ($fieldNameOrder as $name => $order) {
				$cm = $colmeta[$name];
				$value = $values[$order];
		//$this->info("format_insert_values() \$fieldName=$fieldName \$value=$value \$cm->type=$cm->type gettype(\$value)=".gettype($value));
		/*		if(gettype($value)=='NULL')
				 	$value = 'NULL';
				else*/
				if ($value) {
				 	if ($cm->type == 'string' || $cm->type == 'datetime')
				    $value = trim($value); //  $value = $conn->quote($value);
				} else {
				 	if ($cm->type == 'string')
				    ; // $value = $conn->quote($value);
				  else if ($cm->type == 'integer' || $cm->type == 'double') {
				  	if (!($cm->default))
					    $value = 0;
				  } else if ($cm->type == 'datetime')
				  	$value = null;
				  else
				    $value = $cm->default;

				}
		//$this->info("format_insert_values() \$value=$value");
				$values[$order] = $value;
			}
			return $values;
		}
	
}
?>
