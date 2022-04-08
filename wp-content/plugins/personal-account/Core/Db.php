<?php
namespace PersonalAccount\Core;
require WP_PLUGIN_DIR.'\\personal-account\\libs\\rb\\rb.php';
class Db
{
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
            \R::setup($conStr, $this->dUser, $this->dPasw);
        }catch (\Exception $e) {
            die($e->getMessage());
        }
    }
    public function checkConnect(){
        if ( !\R::testConnection() ) {
            return false;
        }else{
            return true;
        }
    }
	public function createTable($tableName, $data = array()){
		$table = \R::dispense($tableName);
		if(!empty($data)){
			foreach ($data as $k => $v){
				$table->$k = $v;
			}
		}
		\R::store($table);
		\R::close();
	}
	public function getUserForLogin($login){
		if(empty($login)){
			return false;
		}else{
			$user = \R::find('users', 'login Like :login', [':login' => $login]);
			return $user;
		}
	}
	public function update($data = []){

	}
	static function getId($table, $where){

	}
}