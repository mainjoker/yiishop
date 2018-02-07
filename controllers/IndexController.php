<?php 
namespace app\controllers;
use yii;
use app\controllers\CommonController;
use app\models\User;
use app\models\Product;
use app\models\Cart;

class IndexController extends CommonController{
	public $layout='layout1';
	public function actionIndex(){

/*	$redis=yii::$app->cache;

	$redis->set('test',123);

	var_dump($redis->keys('*'));*/


	/*	$mail=yii::$app->mailer->compose('seekpass',['adminuser'=>'admin','time'=>time(),'token'=>'sldsd'])->setTo('280594803@qq.com')->setFrom('hkbmail@126.com')->setSubject('just for test');


		if ($mail->queue()) {
			echo  'yeah';exit;
		}*/
		/*echo '<pre>';
		print_r($mail);exit;
		echo '</pre>';*/
		//var_dump(yii::$app->user);exit;


		$model=Product::find();
		$where="onsale='1'";
		$res['tui'] = $model->Where($where . ' and isbest = \'1\'')->orderby('addtime desc')->limit(4)->asArray()->all();
		$res['new'] = $model->Where($where . ' and isnew = \'1\'')->orderby('addtime desc')->limit(4)->asArray()->all();
		$res['hot'] = $model->Where($where . ' and ishot = \'1\'')->orderby('addtime desc')->limit(4)->asArray()->all();
		return $this->render('index',['model'=>$res]);
	}
	//前台登录
	public function actionLogin(){
		$model=new user;
		if (yii::$app->request->isPost) {
			$data=yii::$app->request->post();
			if ($model->login($data)) {
				$this->redirect(['index']);
			}	
		}
		return $this->render('login',['model'=>$model]);

	}
	public function actionLogout(){
		yii::$app->user->logout();
		$this->GoBack(yii::$app->request->referrer);
		/*yii::$app->session->removeAll();
		if (!isset(yii::$app->session['User']['isLogin'])) {
			$this->redirect(['index']);
		}*/
	}

}



 ?>
