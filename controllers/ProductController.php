<?php 
namespace app\controllers;
use yii;
use yii\web\Controller;
use app\models\Product;
use app\models\Category;
use app\models\ProductSearch;
use yii\data\pagination;
use app\controllers\CommonController;

class ProductController extends CommonController{
	public $layout='/layout'; 
	#public $layout='/main'; 
	#后台所有商品显示
	function actionIndex(){
		$model=Product::find()->asArray()->all();
		return $this->render('index',['model'=>$model]);
	}
	//前台商品列表
	function actionList(){

		$this->layout = "layout1";
		//$this->layout = "main";
        $cid = Yii::$app->request->get("cateid");
        #$where = "cateid = :cid and onsale = '1'";
        #$params = [':cid' => $cid];
        $category=new category;
        $topid=$category->getTopid($cid);
        $children=$category->getChildren($cid);
        #$where = "cateid = :ids and onsale = '1'";
       /*	$where=[
       		'cateid'=>[9,10,11],
       		'onsale'=>1,
       	];
        $model = Product::find()->where($where);
        $all = $model->asArray()->all();*/
        $model=new yii\db\query;
        $res=$model->select('*')->from('shop_product')->where([
        	'onsale'=>'1',
        	'cateid'=>$children,
        ])->orderby('addtime desc')->all();
        $count = count($res);
        $pageSize = Yii::$app->params['pageSize']['frontproduct'];
        $pager = new Pagination(['totalCount' => $count, 'pageSize' => $pageSize]);    
        $all = $model->offset($pager->offset)->limit($pager->limit)->all();
        //精品推荐
        $tui=[];
        $hot=[];
        $sale=[];
        foreach ($res as $key => $val) {
        	if ($val['isbest']=='1') {
        		$tui[]=$val;
        	}
        	if ($val['ishot']=='1') {
        		$hot[]=$val;
        	}
        	if ($val['forsale']=='1') {
        		$sale[]=$val;
        	}
        }
      /*  $tui = $model->Where($where . ' and isbest = \'1\'', $params)->orderby('addtime desc')->limit(5)->asArray()->all();
        $hot = $model->Where($where . ' and ishot = \'1\'', $params)->orderby('addtime desc')->limit(5)->asArray()->all();
        $sale = $model->Where($where . ' and forsale = \'1\'', $params)->orderby('addtime desc')->limit(5)->asArray()->all();*/
        return $this->render("list", ['sale' => $sale, 'tui' => $tui, 'hot' => $hot, 'all' => $all, 'pager' => $pager, 'count' => $count]);
	}
	//商品详情
	function actionDetail(){
		$this->layout='layout1';
		$productid=yii::$app->request->get('productid');
		$model=Product::findOne($productid);
		$data['all']=Product::find()->all();

		return $this->render('detail',['product'=>$model,'data'=>$data]);


	}
	function actionAdd(){
		$category=new category;
		$model=new product;
		$data=$category->find()->all();
		$list=$category->getlist($data);
		//初始化
		$list[0]='请选择分类';
		$model->ishot='1';
		$model->onsale='1';
		$model->forsale='0';
		$model->isnew='1';
		$model->isbest='1';
		if (yii::$app->request->isPost) {
			$post=yii::$app->request->post();	
			$post['addtime']=time();		
			$pics=$this->upload();
			if (!$pics) {
				$model->addError('cover','封面不能为空');
			}else{
				$post['Product']['cover']=$pics['cover'];
				$post['Product']['pics']=$pics['pics'];
			}		
			if ($pics && $model->addProduct($post)) {
				yii::$app->session->setFlash('info','添加成功');
			}else{
				yii::$app->session->setFlash('info','添加失败');			
			}
		}
		return $this->render('add',['list'=>$list,'model'=>$model]);

	}
	private function upload(){
		if ($_FILES['Product']['error']['cover']>0) {
			return false;
		}
		$qiniu = Yii::$app->qiniu; 
		$key = uniqid(); 
		$qiniu->uploadFile($_FILES['Product']['tmp_name']['cover'],$key); 
		$cover = $qiniu->getLink($key); 
		$pics=[];
		foreach ($_FILES['Product']['tmp_name']['pics'] as $k => $val) {
			if ($_FILES['Product']['error']['cover']>0) {
				continue;
			 }
			 $key=uniqid();
			 $qiniu->uploadFile($val,$key);
			 $pics[$key]=$qiniu->getLink($key);

		}
		return ['cover'=>$cover,'pics'=>json_encode($pics)];
		//$post[$model->formName()]['img']=$url;
	}
	public function actionMod(){
		$id=yii::$app->request->get('id');
		$category=new category;
		$data=$category->find()->all();
		$list=$category->getlist($data);
		//初始化
		$list[0]='请选择分类';
		$model=Product::find()->where("productid=:id",[':id'=>$id])->one();
		if (yii::$app->request->isPost) {
			$post=yii::$app->request->post();
			$qiniu = Yii::$app->qiniu; 
			//判断是否有文件上传（封面） 0：上传成功无错误 4：无文件上传
			if ($_FILES['Product']['error']['cover']==0) {
				//有修改封面 则覆盖 否则不修改
				$key=uniqid();
				$qiniu->uploadFile($_FILES['Product']['tmp_name']['cover'],$key);
				$post['Product']['cover']=$qiniu->getLink($key);
				//新的封面上传成功 则删除旧的封面
				$qiniu->delete(basename($model->cover)); 
			}else{
				$post['Product']['cover']=$model->cover;
			}

			//判断是否有文件上传（商品图片列表） 0：上传成功无错误 4：无文件上传
			$pics=[];


			foreach ($_FILES['Product']['tmp_name']['pics'] as $k => $file) {
				if ($_FILES['Product']['error']['pics'][$k]>0) {
					continue;
				}
				$key=uniqid();
				$qiniu->uploadFile($file,$key);
				$pics[$key]=$qiniu->getLink($key);
			}
			$post['Product']['pics'] = json_encode(array_merge((array)json_decode($model->pics, true), $pics));
			//$post['Product']['pics']=json_encode($pics);
			 if ($model->load($post) && $model->save()) {
                Yii::$app->session->setFlash('info', '修改成功');
            }

		}
		return $this->render('mod',['model'=>$model,'list'=>$list]);
	}
	//删除一个商品
	public function actionDel(){
		$id=yii::$app->request->get('productid');
		$model=Product::findOne($id);
		$cover=$model->cover;
		$pics=json_decode($model->pics,true);
		$qiniu=yii::$app->qiniu;
		$qiniu->delete(basename($cover));
		foreach ($pics as $k => $file) {
			$qiniu->delete($k);
		}
		Product::deleteAll('productid = :pid', [':pid' => $id]);
		return $this->redirect(['product/index']);

	}
	//删除商品图片
	public function actionRemovepic(){
		$id=yii::$app->request->get('productid');
		$key=yii::$app->request->get('key');
		$model=Product::find()->where('productid=:id',[':id'=>$id])->one();
		$qiniu=Yii::$app->qiniu; 
		$qiniu->delete($key);
		$pics=json_decode($model->pics,true);
		unset($pics[$key]);
		$model->updateAll(['pics'=>json_encode($pics)],'productid=:id',[':id'=>$id]);
		return $this->redirect(['product/mod','id'=>$id]);
	}
	public function actionOn(){
		$this->layout='main';
		$id=yii::$app->request->get('productid');
		$model=Product::findOne($id);
		$model->onsale='1';
		$count=$model->update(['onsale']);
	/*	if ($count) {
			return $this->redirect(['product/index']);
		}else{
			echo ("Error Processing Request");exit;
		}*/
		return $this->redirect(['product/index']);
	}
	public function actionOff(){
		$id=yii::$app->request->get('productid');
		$model=Product::findOne($id);
		$model->onsale='0';
		$count=$model->update(['onsale']);
		/*if ($count) {
			return $this->redirect(['product/index']);
		}else{
			echo ("Error Processing Request");exit;
			
		}*/
		return $this->redirect(['product/index']);
	}
	public function actionTest(){

		return $this->render('test');
	}
	//search
	public function actionSearch(){
		//ES索引名称shop 索引类型product
		$keyword=htmlspecialchars(yii::$app->request->get('keyword'));
		$model=ProductSearch::find();
		$res=$model->query([
			'multi_match'=>[
				'query'=>$keyword,
				'fields'=>['name','description'],
			]
		])->all();
		var_dump($res);exit;
		
	}

}




 ?>
