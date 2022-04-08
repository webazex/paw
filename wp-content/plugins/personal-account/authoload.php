<?php
spl_autoload_register(function ($class_name) {
	if(!(strrpos($class_name, "PersonalAccount") === false)){
		$str = str_replace("PersonalAccount", 'personal-account', $class_name);
		require(WP_PLUGIN_DIR.'\\'.$str.'.php');
	}
});