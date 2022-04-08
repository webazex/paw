<?php

namespace PersonalAccount\Core;

class App {
	static $app;
	static $db;
	static $settings;
	static function start(){
		self::$db = new Db();
		$tables = self::$db::__getListTables();
		$searchUsersTable = in_array('users', $tables);
		$searchLogsTable = in_array('logs', $tables);
		if(!$searchUsersTable){
			self::$db::create('users', [
				'login' => "jhasd@mail.com",
				'psw' => password_hash("1234", PASSWORD_DEFAULT),
			]);
		}
		if(!$searchLogsTable){
			self::$db::create('logs',
				[
					'user' => "jhasd@mail.com",
					'action' => "created",
					'datetime' => date("Y#m#d#H#i#s"),
				]
			);
		}
	}
	static function test(){
		$db = new Db();
		$db::update('1', ['login' => 'jhasd@mail.com'], 'users');
	}
}