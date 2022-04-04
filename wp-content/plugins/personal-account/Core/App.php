<?php


namespace PersonalAccount\Core;
use PersonalAccount\Core\Db as DB;

class App
{
    static function start(){
       echo '<div style="width: 100%; height: 50px; background: green; color: blue;">activated</div>';
    }
    static function stop(){
        echo '<div style="width: 100%; height: 50px; background: red; color: black;">deactivated</div>';
    }
}