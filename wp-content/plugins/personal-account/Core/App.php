<?php

namespace PersonalAccount\Core;

class App {
	static $app;
	static $db;
	static $settings;
	static function start(){
		self::$db = new Db();
	}
}