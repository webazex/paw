<?php
require WP_PLUGIN_DIR.'\\personal-account\\authoload.php';
use PersonalAccount\Core\Db as DB;
add_action( 'plugins_loaded', '__pageSettings' );
function getPluginRequestsUrl(){
	return plugins_url().'/personal-account/requests/requests-center.php';
}
function __pageSettings() {
	function  __renderSettingsPage() {
		$db = new DB();
		if(empty($db->getUserForLogin(get_option('admin_email')))){
			echo '
			<main class="pa__page">
				<p class="page__info">
					Для тестування плагіну - можете здійснити швидку реєстрацію. Без цього Ви не 
					зможете авторизуватись в особистому кабінеті. І, ні. Обліковий запис адміністратора 
					сайту, тут не допоможе :)
				</p>
				<form method="post" class="page__form" action="" id="speedRF" data-sender="'.getPluginRequestsUrl().'">
					<input type="email" name="login" placeholder="Логін">
					<input type="password" name="psw" placeholder="Пароль">
					<div class="form__row-btns">
						<button type="submit">
							<span>
								Створити
							</span>
						</button>
						<button type="reset">
							<span>
								Очистити
							</span>
						</button>
					</div>
				</form>
			</main>';
		}else{
			echo '<main>
			<h1>ssss</h1>
			<h2>ssss2</h2>
			<h3>ssss3</h3>
			</main>';
		}

	}
	add_action( 'admin_menu', 'admin_menu');
	function admin_menu () {
		$iconUrl = WP_PLUGIN_DIR."/personal-account/assets/icon.png";
		add_menu_page(
			'Налаштування персонального кабінету',
			'Налаштування ПК',
			'manage_options',
			'personal-account',
			'__renderSettingsPage',
			plugins_url($iconUrl),
			66
		);
	}
}
add_action( 'admin_enqueue_scripts', 'paSources' );
function paSources() {
	// подключаем файл стилей темы
	wp_enqueue_style( 'pa-css', plugins_url().'/personal-account/assets/main.css' );

	// подключаем js файл темы
	wp_enqueue_script( 'pa-jq', 'https://code.jquery.com/jquery-3.6.0.min.js' , '', '1.0', true );
	wp_enqueue_script( 'pa-js', plugins_url().'/personal-account/assets/main.js' , array('pa-jq'), '1.0', true );
}