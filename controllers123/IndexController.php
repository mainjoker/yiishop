<?php 
namespace app\controllers;
use yii;
use yii\web\controller;
class IndexController extends Controller{
	public $layout='layout1';
	public function actionIndex(){

		return $this->render('index');
	}
}



 ?>