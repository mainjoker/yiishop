<?php 
namespace app\controllers;
use yii\web\controller;
use app\models\Test;
use app\models\Order;
use app\models\Orderinfo;
use app\models\Cart;
use app\models\Product;
use yii;

class TestController extends Controller{

	public function actionIndex(){
		$this->layout='main';

		$Order=new Order;
		$orderinfo=new Orderinfo;
		$Cart=new Cart;
		$userid=1;
		$res=$Cart->find()->asArray()->all();
		$orderid=53;
		/*echo '<pre>';
		print_r($res);exit;
		echo '</pre>';*/
		foreach ($res as $key => $val) {
			$dataorderinfo=[];
			$dataorderinfo['Orderinfo']['productid']=$val['productid'];
			$dataorderinfo['Orderinfo']['orderid']=$orderid;
			$dataorderinfo['Orderinfo']['orderinfoid']=isset($orderinfoid)?$orderinfoid:'';
			$dataorderinfo['Orderinfo']['num']=$val['productnum'];
			$dataorderinfo['Orderinfo']['price']=$val['price'];
			$dataorderinfo['Orderinfo']['product_name']=Product::findOne($val['productid'])->name;
			/*$dataorderinfo['productid']=$val['productid'];
			$dataorderinfo['orderid']=$orderid;
			$dataorderinfo['orderinfoid']=isset($orderinfoid)?$orderinfoid:'';
			$dataorderinfo['num']=$val['productnum'];
			$dataorderinfo['price']=$val['price'];
			$dataorderinfo['product_name']=Product::findOne($val['productid'])->name;*/
			//var_dump($dataorderinfo);echo '<hr>';
			$orderinfo->isNewRecord=true;
			//$orderinfo->setAttributes($dataorderinfo);
			$orderinfo->load($dataorderinfo);
			$orderinfo->orderinfoid='';
			if ($orderinfo->save()) {
				echo 'good';
			}else{
				echo 'fuck';
			}
		}
		exit;
		return $this->render('test');
			

	}
	public function actionAdd(){
		$test=new test;
		if (yii::$app->request->isPost) {
			$data=yii::$app->request->post();
			if ($test->add($data)) {
				yii::$app->session->setFlash('info','修改成功');
			}else{
				yii::$app->session->setFlash('info','修改失败');

			}
		}
		return $this->render('add',['model'=>$test]);
	}
	public function actionMod(){
		$this->layout='layout';
		$test=new test;
		$id=yii::$app->request->get('id');
		$model=$test->findOne($id);
		/*$model=$test->findOne($id);
		$model->scenario='mod';
		$data=yii::$app->request->post();
		if (yii::$app->request->isPost) {
			if ($model->load($data) && $model->validate()) {
			if ($model->save(false)) {
				yii::$app->session->setFlash('info','修改成功123');
			}else{
				yii::$app->session->setFlash('info','修改失败123');
			}
			}
		}*/
		if (yii::$app->request->isPost) {
			$data=yii::$app->request->post();
			if ($model->mod($data,$id)) {
				yii::$app->session->setFlash('info','修改成功');
			}else{
				yii::$app->session->setFlash('info','修改失败');

			}
		}
		return $this->render('mod',['model'=>$model]);


	}
}



 ?>