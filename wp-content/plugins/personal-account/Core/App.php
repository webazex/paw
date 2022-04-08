<?php


namespace PersonalAccount\Core;
use PersonalAccount\Core\Db as DB;

class App
{
	static $status;
	static $settings;
	static $db;
    static function start(){
	    self::$status = 'activated';
		self::$db = new DB();
		self::$db->createTable('users');
	    self::$db->createTable('logs');
	    self::$db->createTable('pasettings');
    }
    static function stop(){
	    self::$status = 'stopped';
    }
	static function getStatus(){
		return self::$status;
	}
	static private function __setDefaultSetting(){
		$settings = self::$db::findAll('pasettings');
		if(empty($settings)){
			$data = [
				'loginpage' => "0",
				'registrationpage' => "0",
				'profilepage' => "0",
				'restorepage' => "0",
				'savelogs' => "3",
				'savelogsdatatype' => "d",
			];
			self::$db->createTable('pasettings', $data);
		}

	}
	static function changeSetting($field, $val){
		$settings = self::$db::findAll('pasettings');
		if((!empty($field)) and (!empty($val))){
			$existSetting = self::$db::findOne('pasettings', ':field = :val', [
				':field' => $field,
				':val' => $val,
			]);
			if(empty($existSetting)){
				self::$db->createTable('pasettings', [$field => $val]);
			}else{
				//Update settings
			}
		}else{
			return false;
		}
	}
}