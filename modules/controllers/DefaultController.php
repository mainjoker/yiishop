<?php

namespace app\modules\controllers;

use yii\web\Controller;
use yii;

/**
 * Default controller for the `admin` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public $layout='/layout';
    /*后台首页*/
    public function actionIndex()
    {
        //var_dump($_SESSION);exit;
    /*	$session=yii::$app->session;
    	if (!$session['admin']['isLogin']) {
    		$this->redirect(['public/login']);
    	}*/
        //var_dump(yii::$app->admin->isGuest);exit;
        if(yii::$app->admin->isGuest){
            $this->redirect(['public/login']);
            //$this->redirect([yii::$app->admin->loginUrl]);
        }
        return $this->render('index');
    }
}
