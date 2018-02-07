<?php 
namespace app\modules\controllers;
use yii\web\controller;
use yii;
use app\modules\models\admin;
use yii\data\pagination;


class ManageController extends Controller{
	public $layout='/layout';
	function actionMailchangepass(){
		$admin=new admin;
		$time=yii::$app->request->get('tamp');
		$token=yii::$app->request->get('token');
		$adminuser=yii::$app->request->get('adminuser');
		/*if ($token!=$admin->createToken($adminuser,$time) || time()-$time>3000) {
			$this->redirect(['public/login']);
		}*/
		if ($token!=$admin->createToken($adminuser,$time) ) {
			echo 'token验证失败';exit;
		}
		if (time()-$time>3000) {
			echo '该链接已失效';exit;
		}
		if (yii::$app->request->isPost) {
			if($admin->changepass(yii::$app->request->post())){
				//修改密码成功
				yii::$app->session->setFlash('cgps','密码修改成功');
			}else{
				yii::$app->session->setFlash('cgps','密码修改失败');
			}
		}
		return $this->render('changepass',['model'=>$admin]);

	}
	//后台管理员列表
	public function actionManagers(){
		$model=admin::find();
		$count=$model->count();
		//分页
		//分页大小
		$pageSize=yii::$app->params['pageSize']['manage'];
		
		$pager=new pagination(['totalCount'=>$count,'pageSize'=>$pageSize]);

		$managers=$model->offset($pager->offset)->limit($pager->limit)->all();


		return $this->render('managers',['managers'=>$managers,'pager'=>$pager]);
	}

	//删除管理员
	public function actionManagerdel(){
		$adminid=(int)yii::$app->request->get('adminid');

		$model=new admin;

		if ($model->deleteAll('adminid=:adminid',[':adminid'=>$adminid])) {
			yii::$app->session->setFlash('info','删除成功');
			$this->redirect(['managers']);
		}else{
			yii::$app->session->setFlash('info','删除失败');
			$this->redirect(['managers']);
		}


	}
	//添加管理员
	public function actionReg(){
		$model=new admin;
		if (yii::$app->request->isPost) {
			$data=yii::$app->request->post();
			if ($model->reg($data)) {
				//添加成功
				yii::$app->session->setFlash('regadd','添加成功');
			}else{
				//添加失败
				yii::$app->session->setFlash('regadd','添加失败');	
			}
		}
		return $this->render('reg',['model'=>$model]);
	}
}



 ?>