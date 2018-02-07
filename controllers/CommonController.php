<?php 
namespace app\controllers;
use yii;
use yii\web\Controller;
use app\models\Category;
use app\models\Product;

class CommonController extends Controller{



	public function init(){
		
		$category=new category;
		$menu=$category->getMenu();
		$this->view->params['menu']=$menu;

	}


}



 ?>
