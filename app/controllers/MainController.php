<?php

/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 11.06.2018
 * Time: 20:38
 */
namespace app\controllers;

use RedBeanPHP\R;
use tasks\App;

class MainController extends AppController
{


    public function indexAction()
    {
        $posts = \R::findAll('test');
        $post = \R::findAll('test', 'id = ?', [2]);
        $this->setMeta(App::$app->getProperty('app_name'), '', '') ;
        //передача данных

        $this->set(compact ('posts'));
    }

}