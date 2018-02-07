<?php 
namespace app\controllers;
use yii;
use yii\web\Controller;
use app\models\Category;
use yii\data\Pagination;
use yii\web\Response;


class CategoryController extends Controller{
	public $layout='/layout';
	#public $layout='/main';
	#后台显示分类列表
	public function actionIndex(){
		/*$res=self::actionTree();
		echo '<pre>';
		print_r($res);
		echo '<pre>';
		var_dump($res);
		exit;*/
		$cates=new Category;
		/*$pageSize=yii::$app->params['pageSize']['category'];
		$totalCount=$cates->find()->where("parentid=:pid",[':pid'=>0])->count();
		$pager=new pagination([
			'totalCount'=>$totalCount,
			'pageSize'=>$pageSize,
		]);
		$data=$cates->find()->offset($pager->offset)->limit($pager->limit)->all();
*/
		$pageSize=yii::$app->params['pageSize']['category'];
		$totalCount=$cates->find()->where("parentid=:pid",[':pid'=>0])->count();
		$pager=new pagination([
			'totalCount'=>$totalCount,
			'pageSize'=>$pageSize,
		]);
		$data=$cates->tree();
		$page=(int)yii::$app->request->get('page')?(int)yii::$app->request->get('page'):1;
		$perpage=(int)yii::$app->request->get('per-page')?(int)yii::$app->request->get('per-page'):$pageSize;
		return $this->render('index',['model'=>$cates,'data'=>$data,'pager'=>$pager,'page'=>$page,'perpage'=>$perpage]);
	}
	//添加分类
	public function actionAdd(){
		$cates=new Category;
		if (yii::$app->request->isPost) {
			$postData=yii::$app->request->post();
			if ($cates->addMenu($postData)) {
				yii::$app->session->setFlash('info','添加成功');
			}else{
				yii::$app->session->setFlash('info','添加失败');
			}
		}
		$data=$cates->find()->asArray()->all();
		$list=$cates->getlist($data);
		return $this->render('add',['list'=>$list,'model'=>$cates]);
	}
	//编辑分类
	public function actionMod(){
		$cates=new Category;
		$cateid=yii::$app->request->get('cateid');
		$model=$cates->find()->where('cateid=:cateid',[':cateid'=>$cateid])->one();
		$data=$cates->find()->asArray()->all();
		$list=$cates->getlist($data);
		//$model->scenario='modMenu';
		/*if (yii::$app->request->isPost) {
			$postData=yii::$app->request->post();
			//var_dump($postData);exit;
			if ($model->load($postData) && $model->save()) {
				yii::$app->session->setFlash('info','修改成功');
			}else{
				yii::$app->session->setFlash('info','修改失败');
			}
		}*/	
		if (yii::$app->request->isPost) {
			$postData=yii::$app->request->post();
			if ($model->modMenu($postData,$cateid)) {
				yii::$app->session->setFlash('info','修改成功');
				//改变model的值
				$model=$cates->find()->where('cateid=:cateid',[':cateid'=>$cateid])->one();
			}else{
				yii::$app->session->setFlash('info','修改失败');
			}
		}
		return $this->render('mod',['list'=>$list,'model'=>$model]);
	}
	//删除分类
	public function actionDel(){

		$cateid=(int)yii::$app->request->get('cateid');
		$model=new category;
		$data=$model->delMenu($cateid);
		if ($data==1) {
			yii::$app->session->setFlash('info','删除成功');
		}elseif ($data==2) {
			yii::$app->session->setFlash('info','该分类下存在子类，不能删除');
		}else{
			yii::$app->session->setFlash('info','删除失败');
		}
		//var_dump(Yii::$app->request->getReferrer());exit;
		return $this->goBack(Yii::$app->request->getReferrer());
		//$this->redirect(['index']);

	}

	public function actionTree(){
		Yii::$app->response->format=Response::FORMAT_JSON;
		$model=new Category;
		$data=$model->tree();
		if ($data) {
			return $data;
		}
		return [];

	}

	//jstree rename
	public function actionRename(){
		yii::$app->response->format=Response::FORMAT_JSON;
		if (!yii::$app->request->isPost) {
			$status=0;
		}
		$post=yii::$app->request->post();
		//return $post;
		$cate=new Category;
		//新标题不能为空
		if (empty($post['text'])) {
			$status=0;
		}
		$model=$cate->findOne($post['id']);
		$model->title=$post['new'];
		if ($model->save(false)) {
			$status=1;
		}
		return  $status;
	}

	
}

 ?>
