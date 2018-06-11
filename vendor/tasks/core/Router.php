<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 11.06.2018
 * Time: 18:53
 */

namespace tasks;


class Router
{
    // таблица маршрутов
    protected static $routes = [];
    //текущий маршрут
    protected static $route = [];

    public static function add($regexp, $route = []){
        self::$routes[$regexp] = $route;
    }

    public static function getRoutes(){
        return self::$routes;
    }

    public static function getRoute(){
        return self::$route;
    }
    //возвращает контроллер либо ошибку 404
    public static function dispatch($url){
        $url = self::removeQueryString($url);
        if(self::matchRoute($url)){
            $controller = 'app\controllers\\' . self::$route['prefix'] . self::$route['controller'] . 'Controller';
            //проверка на наличие такого Controller  класса
            if (class_exists($controller)){
                // создадим объект класса Controller (текущий маршрут)
                $controllerObj = new $controller(self::$route);

                $action = self::$route['action'];
                $action = self::lowerCamelCase($action);
                $action = $action . 'Action';

                if(method_exists($controllerObj, $action)){
                    $controllerObj->$action();
                    $controllerObj->getView();
                }else{
                    throw new \Exception("Метод $controller::$action не найден", 404);
                }

            }else{
                throw new \Exception("Контроллер $controller не найден", 404);
            }
        }else{
            throw new \Exception("Страница не найдена", 404);
        }
    }
    //ищет соответсвия в таблице маршрутов
    public static function matchRoute($url){
        foreach (self::$routes as $pattern => $route){
            if (preg_match("#{$pattern}#", $url, $matches)){
                foreach ($matches as $k => $v){
                    if (is_string($k)){
                        $route[$k] = $v; // временная переменная
                    }
                }
                if (empty($route['action'])){
                    $route['action'] = 'index';
                }
                if (!isset($route['prefix'])){
                    $route['prefix'] = '';
                }else{
                    $route['prefix'] .= '\\';
                }
                $route['controller'] = self::upperCamelCase($route['controller']);
                self::$route = $route;
                return true;
            }
        }
        return false;
    }

    // CamelCase
    protected static function upperCamelCase($name){
        $name =  str_replace('-', ' ', $name);
        $name = ucwords($name);
        $name =  str_replace(' ', '', $name);
        return $name;
    }

    // camelCase
    protected static function lowerCamelCase($name){
        return lcfirst(self::upperCamelCase($name));
    }

    protected static function removeQueryString($url){
        if($url){
            $params = explode('&', $url, 2);
            if(false === strpos($params[0], '=')){
                return rtrim($params[0], '/');
            }else{
                return '';
            }
        }
    }

}