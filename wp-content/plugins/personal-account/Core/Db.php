<?php

namespace PersonalAccount\Core;

require_once WP_PLUGIN_DIR . "\\personal-account\\libs\\RB572\\rb.php";

class Db {
	protected $dName;
	protected $dUser;
	protected $dHost;
	protected $dPasw;

	protected $tables;

	private static $whiteListTables;

	private static function __setWhiteListTables(){
		self::$whiteListTables = ['users', 'logs', 'pasettings'];
	}


	static protected function __checkPermitted( string $tableName): bool{
		try {
			if(!empty($tableName)){
				$whiteList = self::getWhiteListTables();
				if(in_array($tableName, $whiteList)){
					return  true;
				}else{
					return false;
				}
			}else{
				throw new \Exception('Метод верифікації не отримав необхідних аргументів', 13);
			}
		}catch (\Exception $e) {
			$errMsg = $e->getFile().'<br>'.
			          $e->getCode().'<br>'.
			          $e->getLine().'<br>'.
			          $e->getMessage();
			die($errMsg);
		}
	}

	private static function getWhiteListTables(): array {
		return self::$whiteListTables;
	}

	protected function __getConnectData(): array {
		return [
			'host' => $this->dHost,
			'name' => $this->dName,
			'user' => $this->dUser,
			'pasw' => $this->dPasw,
			'type' => 'mysql',
		];
	}

	protected  function __setConnectData(){
		$this->dHost = DB_HOST;
		$this->dName = DB_NAME;
		$this->dUser = DB_USER;
		$this->dPasw = DB_PASSWORD;
	}

	public function __construct()
	{
		try {
			$this->__setConnectData();
			self::__setWhiteListTables();
			$conStr = $this->__getConnectData()['type'].':host='.$this->dHost.';dbname='.$this->dName;
			if ( !\R::testConnection() ) {
				\R::setup($conStr, $this->dUser, $this->dPasw);
			}
		}catch (\Exception $e) {
			\R::debug( TRUE );
			die($e->getMessage());
		}
	}

	static function __insert($tableName, array $data){
		try {
			if(empty($tableName)){
				throw new \Exception('Ім’я таблиці не може бути порожнім', '2');
			}
			if(empty($data)){
				throw new \Exception('Оновлюваний параметр пошуку, повинен бути заданий непустим рядком, 
						цілим числом, чи масивом з полем та значенням', '3');
			}
			if(self::__checkPermitted($tableName)){
				$insert = \R::dispense($tableName);
				foreach ( $data as $k => $v ) {
					if(empty($k)){
						throw new \Exception('Поле таблиці не може бути порожнім', '1');
					}else{
						$insert->$k = $v;
					}
				}
				if(isset($insert)){
					\R::store($insert);
					\R::close();
				}else{
					return false;
				}
			}else{
				throw new \Exception('Доступ заборонено', '12');
			}
		}catch (\Exception $e){
			$errMsg = $e->getFile().'<br>'.
			          $e->getCode().'<br>'.
			          $e->getLine().'<br>'.
			          $e->getMessage();
			echo($errMsg);
		}
	}
	static function __getListTables(string $tableName = ''):array{
		try {
			if(!empty($tableName)){
				if(self::__checkPermitted($tableName)){
					return \R::inspect($tableName);
				}else{
					throw new \Exception('Доступ заборонено', '12');
				}
			}else{
				$listTables = \R::inspect();
				if(empty($listTables)){
					return [];
				}else{
					return array_intersect($listTables, self::getWhiteListTables());
				}
			}
		}catch (\Exception $e) {
			die('<pre>'.$e->getMessage().'</pre>');
		}
	}

	static function getList(string $tableName = ''):array{
		try {
			$ret = [];
			if(empty($tableName)){
				$ret = self::__getListTables();
			}else{
				$ret = self::__getListTables($tableName);
			}
		}catch (\Exception $e){
			$errMsg = $e->getFile().'<br>'.
			          $e->getCode().'<br>'.
			          $e->getLine().'<br>'.
			          $e->getMessage();
			echo($errMsg);
		}
		return $ret;
	}

	static function __getId($tableName, $field, $val){
		try {
			if(empty($tableName)){
				throw new \Exception('Ім’я таблиці не може бути порожнім', '2');
			}
			if(empty($field)){
				throw new \Exception('Поле таблиці не може бути порожнім', '1');
			}
			$searched = \R::findOne($tableName, $field.' = :val', [
				':val' => $val
			]);
			if(is_null($searched)){
				return null;
			}else{
				return $searched->id;
			}
		}catch (\Exception $e){
			die($e->getMessage());
		}
	}

	static function getId($tableName, array $data){
		try {
			$resultArray = [];
			if(empty($tableName) OR empty($data)){
				throw new \Exception('Метод для отримання даних не отримав обов‘язкових аргументів', '7');
			}else{
				foreach ($data as $f => $v){
					if(empty($f)){
						throw new \Exception('Поле таблиці не може бути порожнім', 1);
					}else{
						if(is_array($v)){
							echo "this";
							foreach ($v as $item){
								$id = self::__getId($tableName, $f, $item);
								if(is_null($id)){
									$resultArray[] = [
										'id' => false,
										$f   => $item,
									];
								}else{
									$resultArray[] = [
										'id' => intval( $id ),
										$f   => $item,
									];
								}
							}
						}else{
							echo "what i this??";
							$id = self::__getId($tableName, $f, $v);
							if(is_null($id)){
								$resultArray[] = [
									'id' => false,
									$f   => $v,
								];
							}else{
								$resultArray[] = [
									'id' => intval( $id ),
									$f   => $v,
								];
							}
						}
					}
				}
				return $resultArray;
			}
		}catch (\Exception $e){
			$errMsg = $e->getFile().'<br>'.
			          $e->getCode().'<br>'.
			          $e->getLine().'<br>'.
			          $e->getMessage();
			echo($errMsg);
		}
	}

	static function __exsist($tableName, $f, $v){
		try {
			if(empty($tableName) OR empty($f)){
				throw new \Exception('Метод для отримання даних не отримав обов‘язкових аргументів', '7');
			}else{
				if(self::__checkPermitted($tableName)){
					return self::__getId($tableName, $f, $v);
				}else{
					throw new \Exception('Доступ заборонено', '12');
				}
			}
		}catch (\Exception $e){
			$errMsg = $e->getFile().'<br>'.
			          $e->getCode().'<br>'.
			          $e->getLine().'<br>'.
			          $e->getMessage();
			echo($errMsg);
		}
	}

	static function read($search, $tableName, $returnType = 'ARRAY', bool $expression = false){
		try {
			if(!(($returnType == 'ARRAY') OR ($returnType == 'OBJ'))){
				throw new \Exception('Вказаного типу повернення даних, 
				немає в переліку дозволених типів', '8');
			}
			$returned = null;
			if((!empty($search)) AND (!empty($tableName))){
				if(is_array($search)){
					if($expression){
						$tableFields = \R::inspect($tableName);
						foreach ( $search as $f => $v ) {
							if(array_key_exists($f, $tableFields)){
								$id = self::__getId($tableName, $f, $v);
								if(!(is_null($id))){
									$id = intval($id);
									if($returnType == 'ARRAY'){
										$bean = \R::load($tableName, $id);
										$returned = $bean->export();
									}elseif($returnType == 'OBJ'){
										$returned = \R::load($tableName, $id);
									}
								}else{
									throw new \Exception("Даних по $f зі значенням $v немає", '10');
								}
							}else{
								throw new \Exception('Дане поле не існує', '6');
							}
						}
					}else{
						foreach ( $search as $id ) {
							if(!(is_int($id))){
								throw new \Exception('Очікується інший тип вхідних даних.', '9');
							}
						}
						if($returnType !== 'ARRAY'){
							$returnType = 'ARRAY';
						}
						$returned = \R::loadAll($tableName, $search);
					}
				}
				if(is_string($search) OR is_int($search)){
					$id = intval($search);
					if($returnType == 'ARRAY'){
						$bean = \R::load($tableName, $id);
						$returned = $bean->export();
					}elseif($returnType == 'OBJ'){
						$returned = \R::load($tableName, $id);
					}
				}
				\R::close();
				return $returned;
			}else{
				throw new \Exception('Метод для отримання даних не отримав обов‘язкових
				аргументів', '7');
			}
		}catch (\Exception $e){
			$errMsg = $e->getFile().'<br>'.
			          $e->getCode().'<br>'.
			          $e->getLine().'<br>'.
			          $e->getMessage();
			die($errMsg);
		}
	}

	static function update($search, array $updated, $tableName){
		try {
			if(empty($updated)){
				throw new \Exception('Дані для оновлення є обов’язковим параметром', '5');
			}else{
				foreach ( $updated as $kUpd => $fUpd ) {
					if(empty($kUpd)){
						throw new \Exception('Поле таблиці не може бути пустим', '1');
					}else{
						if(!empty($tableName)){
							if(!empty($search)){
								if(is_array($search)){
									foreach ( $search as $f => $v) {
										if(empty($f)){
											throw new \Exception('Поле таблиці не може бути пустим', '1');
										}else{
											$searched = \R::findOne($tableName, $f.' = :val', [':val' => $v]);
											if(is_null($searched)){
												throw new \Exception('Даний запис відсутній', '4');
											}else{
												$id = self::__getId($tableName, $f, $v);
												if(!(is_null($id))){
													$id = intval($id);
													$obj = \R::load($tableName, $id);
													$tableFields = \R::inspect($tableName);
													foreach ( $updated as $fUpd => $vUpd) {
														if(array_key_exists($fUpd, $tableFields)){
															$obj->$fUpd = $vUpd;
														}else{
															throw new \Exception('Дане поле не існує', '6');
														}
													}
													\R::store($obj);
													\R::close();
												}
											}
										}
									}
								}
								if((is_string($search)) or (is_int($search))){
									$id = intval($search);
									$searched = \R::findOne($tableName, 'id = :val', [':val' => $id]);
									if(is_null($searched)){
										throw new \Exception('Даний запис відсутній', '4');
									}else{
										$obj = \R::load($tableName, $id);
										$tableFields = \R::inspect($tableName);
										foreach ( $updated as $fUpd => $vUpd) {
											if(array_key_exists($fUpd, $tableFields)){
												$obj->$fUpd = $vUpd;
											}else{
												throw new \Exception('Дане поле не існує', '6');
											}
										}
										\R::store($obj);
										\R::close();
									}
								}
							}else{
								throw new \Exception(
									'Оновлюваний параметр пошуку, повинен бути заданий непустим рядком, 
											цілим числом, чи масивом з полем та значенням', '3');
							}
						}else{
							throw new \Exception('Ім’я таблиці не може бути порожнім', '2');
						}
					}
				}
			}
		}catch (\Exception $e){
			$errMsg = $e->getFile().'<br>'.
					$e->getCode().'<br>'.
					$e->getLine().'<br>'.
					$e->getMessage();
			die($errMsg);
		}
	}

	static function delete($tableName, $id){
		try {
			if(empty($tableName) OR empty($id)){
				throw new \Exception('Метод видалення не отримав необхідних аргументів', '11');
			}else{
				$bean = \R::load($tableName, $id);
				\R::trash($bean);
				\R::close();

			}
		}catch (\Exception $e){
			$errMsg = $e->getFile().'<br>'.
			          $e->getCode().'<br>'.
			          $e->getLine().'<br>'.
			          $e->getMessage();
			echo($errMsg);
		}
	}

	static function drop($tableNames){
		try {
			if(!empty($tableNames)){
				if(is_array($tableNames)){
					foreach ($tableNames as $tableName){
						if(!empty($tableName)){
							\R::exec('DROP TABLE :tablename;', [':tablename' => $tableName]);
						}else{
							throw new \Exception('Метод видалення не отримав необхідних аргументів', '11');
						}
					}
					\R::close();
				}else{
					\R::exec('DROP TABLE :tablename;', [':tablename' => $tableNames]);
					\R::close();
				}
			}else{
				throw new \Exception('Метод видалення не отримав необхідних аргументів', '11');
			}
		}catch (\Exception $e){
			$errMsg = $e->getFile().'<br>'.
			          $e->getCode().'<br>'.
			          $e->getLine().'<br>'.
			          $e->getMessage();
			echo($errMsg);
		}
	}
}