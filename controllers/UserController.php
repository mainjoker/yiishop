<?php 
namespace app\controllers;
use yii\web\controller;
use app\models\User;
use app\models\profile;
use yii\data\pagination;
use yii;

class UserController extends Controller{

	//调试
	//public $layout='main.php';

	public $layout='layout.php';


	function actionUserlist(){
		//用户列表

		$model=User::find()->joinWith('profile');
		//echo $model->createCommand->getRawSql();exit;
		$count=$model->count();
		$pageSize=yii::$app->params['pageSize']['user'];
		$pager=new pagination([
			'totalCount'=>$count,
			'pageSize'=>$pageSize,
		]);

		$users=$model->offset($pager->offset)->limit($pager->limit)->all();
	/*	foreach ($users as $key => $value){
		echo '<pre>';
		print_r($value['profile']['nickname']);
		echo '</pre>'; 
		}
		exit;*/
		return $this->render('userlist',['users'=>$users,'pager'=>$pager]);	

	}
	function actionUserdel(){

		if (yii::$app->request->isGet) {
			$userid=(int)yii::$app->request->get('userid');
			if (empty($userid)) {
				throw new \Exception();
			}
			$transaction=yii::$app->db->beginTransaction();
			$profile=new profile;
			$user=new user;
			try {				

				$res1=$profile->deleteAll("userid=:userid",[':userid'=>$userid]);
				$res2=$user->deleteAll("userid=:userid",[':userid'=>$userid]);
				if ($res1 && $res2) {
					$transaction->commit();
					$this->redirect(['user/userlist']);
				}else{
					$transaction->rollBack();
					return $this->render('Userdel',['info'=>'删除失败']);

				}
			} catch (\Exception $e) {
					$transaction->rollBack();
					return $this->render('Userdel',['info'=>'删除失败']);
			}
			
		}else{
			throw new \Exception();
		}
	}
	function actionAdduser(){
		$model=new user;
		$profile=new profile;
		//$data=yii::$app->request->post();$model->adduser($data);$model->adduser($data);
		if (yii::$app->request->isPost) {
			$data=yii::$app->request->post();
			$data['User']['password']=md5($data['User']['password']);
			$data['User']['repass']=md5($data['User']['repass']);
			if ($model->adduser($data)) {
				yii::$app->session->setFlash('useradd','添加成功');
			}else{				
				yii::$app->session->setFlash('useradd','添加失败');
			}
		}
		return $this->render('adduser',['model'=>$model,'userinfo'=>$profile]);
	}

}
?>