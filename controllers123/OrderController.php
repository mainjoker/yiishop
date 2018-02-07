<?php 
namespace app\controllers;
use yii;
use yii\web\controller;
class OrderController extends controller{
	public $layout= false;
	/*订单列表*/
	public function actionIndex(){
		return $this->render('index');
	}
	/*订单核对*/
	public function actionCheck(){
		return $this->render('check');
	}
}

 ?>