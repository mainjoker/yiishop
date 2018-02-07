<?php 
namespace app\controllers;
use yii;
use yii\web\controller;
class ProductController extends controller{
	public $layout =false;
	/*商品列表*/
	public function actionIndex(){
		return $this->render('index');
	}
	/*商品详情*/
	public function actionDetail(){
		return $this->render('detail');
	}
}


 ?>