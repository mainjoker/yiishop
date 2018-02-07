<?php 
namespace app\modules\controllers;
use yii;
use yii\web\Controller;
use app\modules\models\Admin;

class PublicController extends controller{
	public $layout=false;
	public function actionLogin(){
		$model=new admin;
		$post=yii::$app->request->post();
		if ($model->login($post)) {
			$this->redirect(['default/index']);
		}
		return $this->render('login',['model'=>$model]);
	}
	public function actionLogout(){
		//退出
		/*yii::$app->session->removeAll();
		if (!isset(yii::$app->session['admin']['isLogin'])){
			$this->redirect(['public/login']);
			yii::$app->end();
		}*/
		yii::$app->admin->logout();
		$this->redirect([yii::$app->admin->loginUrl]);
		//$this->goBack();
	}
	public function actionSeekpassword(){
		$model=new admin;
		if (yii::$app->request->isPost) {
			$post=yii::$app->request->post();
			if ($model->seekPass($post)) {
				yii::$app->session->setFlash('mailInfo','邮件发送成功，请注意查收');
			}

		}
		return $this->render('seekpassword',['model'=>$model]);
	}

	public function actionDel(){
		echo 'del';exit;
	}

}


 ?>
