<?php

namespace tasks;

class Registry {

    use TSingletone;

    protected static $properties = [];

    public function setProperty($name, $value){
        self::$properties[$name] = $value;
    }

    public function getProperty($name){
        if(isset(self::$properties[$name])){
            return self::$properties[$name];
        }
        return null;
    }

    // для дебага
    public function getProperties(){
        return self::$properties;
    }

}