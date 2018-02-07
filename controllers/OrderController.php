<?php 
namespace app\controllers;
use app\controllers\CommonController;
use yii;
use yii\db\transaction;
use app\models\User;
use app\models\Address;
use app\models\Orderinfo;
use app\models\Cart;
use app\models\Product;
use app\models\Express;
use app\models\Category;
use app\models\Order;
use yii\data\Pagination;

class OrderController extends CommonController{

		//订单列表
		public function actionIndex(){
			$this->layout = "layout1";
			//前台订单列表
			/*$user=yii::$app->session['User']['user'];
			$userid=User::find()->where("username=:username",[':username'=>$user])->one()->userid;*/
			$userid=yii::$app->user->id;
			$data=Order::find()->joinwith('orderinfo')->where("userid=:userid",[':userid'=>$userid])->orderBy('createtime DESC')->asArray()->all();

			foreach ($data as $k1 => $v1) {
				$data[$k1]['express_fee']=Express::find()->where("name=:name",[':name'=>$data[$k1]['express']])->one()->fee;
				foreach ($v1['orderinfo'] as $k2 => $v2) {
					$res=Product::findOne($v2['productid']);
					$data[$k1]['orderinfo'][$k2]['cover']=$res->cover;
					$data[$k1]['orderinfo'][$k2]['cate']=Category::findOne($res->cateid)->title;
				}	
			}
			foreach ($data as $key => $value) {
				$data[$key]=(object)$data[$key];
			}
			/*$data=(object)$data;
			echo '<pre>';
			print_r($data);exit;
			echo '</pre>';*/
			//var_dump($data);exit;
			$orderCount=count($data);
			$pageSize=1;
			$pager=new pagination(['totalCount'=>$orderCount,'pageSize'=>$pageSize]);
			//return $this->render('list',['orders'=>$data,'pager'=>$pager]);
			return $this->render('index',['orders'=>$data]);
		

		}
	

	   public function actionCheck(){

        $this->layout = "layout1";
    	$loginname = Yii::$app->session['User']['user'];
     	$userid = User::find()->where('username = :name or useremail = :email', [':name' => $loginname, ':email' => $loginname])->one()->userid;
     	$addresses = Address::find()->where('userid = :uid', [':uid' => $userid])->asArray()->all();
        $details = Cart::find()->where('userid = :oid', [':oid' => $userid])->asArray()->all();

        $data = [];
        foreach($details as $detail) {
            $model = Product::find()->where('productid = :pid' , [':pid' => $detail['productid']])->one();
            $detail['title'] = $model->title;
            $detail['cover'] = $model->cover;
            $data[] = $detail;
        }
        $expressinfo=Express::find()->asArray()->all();
        $express=[];
        $expressPrice=[];
        foreach ($expressinfo as $k => $val) {
        	$express[$val['expressid']]=$val['name'];
        	$expressPrice[$val['expressid']]=$val['fee'];
        	
        }
        return $this->render("check", ['express' => $express, 'expressPrice' => $expressPrice, 'addresses' => $addresses, 'products' => $data]);   
       
    }
    //创建订单
	public function actionAdd(){
	
		if (yii::$app->request->isPost) {
			$loginname = Yii::$app->session['User']['user'];
     		$userid = User::find()->where('username = :name or useremail = :email', [':name' => $loginname, ':email' => $loginname])->one()->userid;
			$details = Cart::find()->where('userid = :oid', [':oid' => $userid])->asArray()->all();
			$count=0;
			foreach ($details as $key => $val) {
				$count+=$val['price']*$val['productnum'];
			}
			$post=yii::$app->request->post();
			$address=Address::findOne($post['addressid']);
			$express=Express::findOne($post['expressid']);
			$fee=$express->fee;
			$count+=$fee;
			$data=[];
			$data['Order']['address']=$address['address'];
			$data['Order']['name']=$address['firstname'].$address['lastname'];
			$data['Order']['ordersn']=date('ymdhis',time()).rand(1,10000).$userid.rand(0,0.000099999)*100;
			$data['Order']['phone']=$address['telephone'];
			$data['Order']['createtime']=time();
			$data['Order']['count']=$count;
			$data['Order']['express']=$express['name'];
			$data['Order']['userid']=$userid;

			/*echo '<pre>';
			print_r($data);exit;
			echo '</pre>';*/
			$transaction = Yii::$app->db->beginTransaction();

			try {
				//先插入order表 再插入orderinfo表
				$order=new Order;
				$orderinfo=new Orderinfo;
				$order->scenario='add';
				if ($order->load($data) && $order->save()) {
					$orderid=$order->getPrimaryKey();
					foreach ($details as $key => $val) {
						$dataorderinfo=[];
						$dataorderinfo['Orderinfo']['productid']=$val['productid'];
						$dataorderinfo['Orderinfo']['orderid']=$orderid;
						$dataorderinfo['Orderinfo']['num']=$val['productnum'];
						$dataorderinfo['Orderinfo']['price']=$val['price'];
						$dataorderinfo['Orderinfo']['product_name']=Product::findOne($val['productid'])->name;
						$orderinfo->isNewRecord=true;
						$orderinfo->load($dataorderinfo);
						$orderinfo->orderinfoid='';
						if ($orderinfo->save()) {
							//清空购物车 并更新库存
							Cart::deleteAll('productid = :pid' , [':pid' => $val['productid']]);
							Product::updateAllCounters(['num' => -$val['productnum']], 'productid = :pid', [':pid' => $val['productid']]);
						}
					}
					$transaction->commit();
				}
				$transaction->rollback();
			} catch (\Exception $e) {
				$transaction->rollback();
				//回到购物车
				return $this->redirect(['cart/index']);

			}
			//跳到支付页面(省略)
			//return $this->pay();
			//订单列表	
			return $this->redirect(['order/index']);
		}


	}

}


 ?>
