<?php 
namespace app\controllers;
use yii;
use yii\web\controller;
class CartController extends controller{
	public $layout = false;
	public function actionIndex(){
		return $this->render('index');
	}
}


 ?>