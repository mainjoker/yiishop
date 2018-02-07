<?php 
namespace app\controllers;
use yii;
use yii\web\controller;

class MemberController extends controller{
	public $layout=false;
	public function actionAuth(){
		return $this->render('auth');
	}
}


 ?>