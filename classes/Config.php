<?php

class Config{

    public static function start(){
        self::set_config('started', '1');
    }

    public static function stop(){
        self::set_config('started', '0');
    }

    public static function set_config($field, $value){
        $config = self::read_config();
        $config[$field] = $value;

        self::write_config($config);
    }

    public static function get_config($field){
        $config = self::read_config();
        if(isset($config[$field])){
            return $config[$field];
        }
        return false;
    }

    private static function write_config($config){
        $f = fopen(CONFIG_FILE, 'w');
        if (fwrite($f, serialize($config)) === FALSE) {
            echo "Не могу записать в файл (" . CONFIG_FILE . ")";
            exit;
        }
    }

    private static function read_config(){
        return unserialize(file_get_contents(CONFIG_FILE));
    }
}