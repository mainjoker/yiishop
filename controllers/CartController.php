<?php 
namespace app\controllers;
use app\controllers\CommonController;
use yii;
use app\models\Cart;
use app\models\User;
use app\models\Category;
use app\models\Product;

class CartController extends CommonController{

/*	public function init(){
		
		parent::init();
		if (!yii::$app->session['User']['isLogin']) {
			//$view="//index/login";
			//Yii::$app->view->render($view);
			$url='http://127.0.0.15/index.php?r=index%2Flogin';
			header("location:$url");exit;
		}
	}*/
	public function beforeAction($action){
		if (!yii::$app->session['userid']) {
			//$url='http://127.0.0.15/index.php?r=index%2Flogin';
			$url='http://127.0.0.15/index/login';
			header("location:$url");exit;
		}
		return true;
	}

	public function actionIndex()
    {
        /*if (Yii::$app->session['isLogin'] != 1) {
            return $this->redirect(['member/auth']);
        }
        $userid = User::find()->where('username = :name', [':name' => Yii::$app->session['loginname']])->one()->userid;
         */
        $userid = Yii::$app->user->id;
        $user=new user;
        $cart=new cart;
       // $username=yii::$app->session['User']['user'];
        //$userid=$user->find()->where("username=:username",[':username'=>$username])->one()->userid;
        $key='cart';
        $cache=yii::$app->cache->get($key);
        if (!$cache) {
        	$cart = Cart::find()->where('userid = :uid', [':uid' => $userid])->asArray()->all();
	        $data = [];
	        foreach ($cart as $k=>$pro) {
	            $product = Product::find()->where('productid = :pid', [':pid' => $pro['productid']])->one();
	            $data[$k]['cover'] = $product->cover;
	            $data[$k]['title'] = $product->title;
	            $data[$k]['productnum'] = $pro['productnum'];
	            $data[$k]['price'] = $pro['price'];
	            $data[$k]['productid'] = $pro['productid'];
	            $data[$k]['cartid'] = $pro['cartid'];
	        }
	         
	        $dep=new yii\caching\DbDependency([
	        	'sql'=>'select max(updatetime) from {{%cart}} where userid=:uid',
	        	'params'=>[
	        		':uid'=>$userid,
	        	]
	        ]);
	        $res=yii::$app->cache->set($key,$data,60,$dep);
	       // var_dump($res);
        }else{
        	$data=$cache;
        	/*echo '<pre>';        	
        	print_r(json_decode($cache));exit;

        	echo '</pre>';*/
        }
     	//var_dump(yii::$app->cache->get($key));exit;
        $this->layout = 'layout1';
        return $this->render("index", ['data' => $data]);
    }
/*
	public function actionIndex(){
		$this->layout='layout1';
		$category=new category;
		$user=new user;
		$cart=new cart;
		$username=yii::$app->session['User']['user'];
		$userid=$user->find()->where("username=:username",[':username'=>$username])->one()->userid;
		$data=$cart->find()->joinwith('product')->where("userid=:userid",[':userid'=>$userid])->asArray()->all();
		//$data['product']['tag']=$category->findOne($data['product']['cateid'])->title;
		$len=count($data);
		for ($i=0; $i <$len ; $i++) { 
			$data[$i]['product']['tag']=$category->findOne($data[$i]['product']['cateid'])->title;
		}
		#var_dump($data);exit;
		//$model=$cart->find()->where("userid=:userid",[':userid'=>$userid])->all();
		return $this->render('index',['model'=>$data]);	
	}*/
	//加入购物车
	public function actionAdd(){
		
		$cart=new cart;
		$user=new user;
		$data=[];
		if (yii::$app->request->isPost) {
			$data['Cart']=yii::$app->request->post();
			$userid=yii::$app->user->id;
			//$username=yii::$app->session['User']['user'];
			//$userid=$user->find()->where("username=:username",[':username'=>$username])->one()->userid;
			$data['Cart']['userid']=$userid;
			//$data['Cart']['createtime']=time();
			//如果购物车中存在相同商品 则合并 将数量想加即可
			$res=$cart->find()->where("userid=:userid and productid=:productid",[':userid'=>$userid,':productid'=>$data['Cart']['productid']])->asArray()->one();
			if ($res) {
				//判断是否是相同的价格
				if ($res['price']==$data['Cart']['price']) {
					$cart=$cart->findOne($res['cartid']);
					//数量想加
					$data['Cart']['productnum']=$data['Cart']['productnum']+$res['productnum'];
					//$data['Cart']['createtime']=time();
				}
			}
			if ($cart->load($data) && $cart->save()) {
				$this->redirect(['cart/index']);
			}

		}

	}
	  public function actionMod()
    {
        $cartid = Yii::$app->request->get("cartid");
        $productnum = Yii::$app->request->get("productnum");
        //$updatetime=time();
        $model = Cart::find()->where('cartid=:cid',[':cid'=>$cartid])->one();
        if ($productnum==$model->productnum) {
        	return ;
        }
        $model->productnum=$productnum;
        $model->save();
        //$cart->update(['productnum' => $productnum], 'cartid = :cid', [':cid' => $cartid]);
    }
    public function actionDel()
    {
        $cartid = Yii::$app->request->get("cartid");
        Cart::deleteAll('cartid = :cid', [':cid' => $cartid]);
        return $this->redirect(['cart/index']);
    }
}



 ?>
