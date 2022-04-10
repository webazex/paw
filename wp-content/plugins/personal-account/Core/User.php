<?php

namespace PersonalAccount\Core;
use PersonalAccount\Core\Db as DB;

class User {
	public $id;
	public $login;
	static function __addUser(array $user){
		try {
			if(!empty($user)){
				$exsist = User::exsistUser($user['login']);
				if($exsist){
					throw new \Exception('Користувач вже існує', '3');
				}else{
					$db = new DB();
					DB::__insert('users', $user);
				}
			}else{
				throw new \Exception('Відсутні обов’язкві дані', '2');
			}
		}catch (\Exception $e){
			$errMsg = $e->getCode().' '.$e->getMessage();
			echo($errMsg);
		}
	}
	static function addUser(array $user):bool{
		if(!empty($user)){
			$user['psw'] = password_hash($user['psw'], PASSWORD_BCRYPT);
			User::__addUser($user);
			if(User::exsistUser($user['login'])){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	static function exsistUser(string $login):bool{
		try {
			if(!empty($login)){
				$db = new DB();
				$exist = $db::__exsist('users', 'login', $login);
				if(is_null($exist)){
					return false;
				}else{
					return true;
				}
			}else{
				throw new \Exception('Логін користувача не може бути пустим', '1');
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