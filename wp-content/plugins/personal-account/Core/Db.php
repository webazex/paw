<?php

namespace PersonalAccount\Core;

use PHPMailer\PHPMailer\Exception;

require_once WP_PLUGIN_DIR . "\\personal-account\\libs\\RB572\\rb.php";

class Db {
	protected $dName;
	protected $dUser;
	protected $dHost;
	protected $dPasw;
	protected function __getConnectData(){
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
			$conStr = $this->__getConnectData()['type'].':host='.$this->dHost.';dbname='.$this->dName;
			if ( !\R::testConnection() ) {
				\R::setup($conStr, $this->dUser, $this->dPasw);
			}
		}catch (\Exception $e) {
			\R::debug( TRUE );
			die($e->getMessage());
		}
	}

	static function __getListTables(){
		try {
			$listTables = \R::inspect();
		}catch (\Exception $e) {
			die('<pre>'.$e->getMessage().'</pre>');
		}
		if(empty($listTables)){
			return [];
		}else{
			return $listTables;
		}
	}



	static function create($tableName, $data = []){
		try {
			if(!empty($tableName)){
				$table = \R::dispense($tableName);
				if(!empty($data)){
					foreach ($data as $k => $v){
						if(empty($k)){
							throw new \Exception('Поле таблиці не може бути порожнім', '1');
						}else{
							$table->$k = $v;
						}
					}
				}
				\R::store($table);
				\R::close();
			}else{
				throw new \Exception('Ім’я таблиці не може бути порожнім', '2');
			}
		}catch (\Exception $e){
			die($e->getMessage());
		}

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
}