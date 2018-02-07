<?php 
namespace app\modules\controllers;

use yii;
use yii\web\Controller;
use app\models\order;
use yii\data\pagination;


class OrderController extends Controller{
	public $layout='/layout';
	public function actionList(){
		//后台订单列表
		$data=Order::find()->all();
		/*echo '<pre>';
		print_r($data);exit;
		echo '</pre>';*/
		$orderCount=count($data);
		$pageSize=1;
		$pager=new pagination(['totalCount'=>$orderCount,'pageSize'=>$pageSize]);
		//echo $pager->offset;exit;
		$orders=Order::find()->joinwith('orderinfo')->offset($pager->offset)->limit($pager->limit)->groupby('orderid')->all();
		/*$orders=Order::find()->joinwith('orderinfo')->asArray()->all();
		echo '<pre>';
		print_r($orders);exit;echo '</pre>';*/
		return $this->render('list',['orders'=>$orders,'pager'=>$pager]);
	}

}



 ?>