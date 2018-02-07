<?php 
namespace app\modules\models;
use yii\db\ActiveRecord;
use yii;

class Admin extends ActiveRecord implements yii\web\IdentityInterface{
	public $rememberMe=false;
	public $repass;
	public static function TableName(){
		return "{{%admin}}";
	}
	public function rules(){

		return [
			['adminuser','required','message'=>'管理员账号不能为空','on'=>['adminadd','login','seekpass','changepass']],
			['adminuser','unique','message'=>'管理员账号已被注册','on'=>['adminadd']],
			['adminpass','required','message'=>'管理员密码不能为空','on'=>['login','changepass','adminadd']],
			['rememberMe','boolean','on'=>'login'],
			['adminpass','validatePass','on'=>'login'],
			['adminemail','required','message'=>'管理员邮箱不能为空','on'=>['seekpass','adminadd']],
			['adminemail','email','message'=>'管理员邮箱格式错误','on'=>['seekpass','adminadd']],
			['adminemail','unique','message'=>'管理员邮箱已被注册','on'=>['adminadd']],
			['repass','required','message'=>'确认密码不能为空','on'=>['changepass','adminadd']],
			['repass','compare','compareAttribute'=>'adminpass','message'=>'确认密码错误','on'=>['changepass','adminadd']],
		];

	}
	//密码验证
	public function validatePass(){
		if (!$this->hasErrors()) {
			$data=$this->find()->where("adminuser=:user and adminpass=:pass",[':user'=>$this->adminuser,':pass'=>md5($this->adminpass)])->one();
			$data1=$this->find()->where("adminemail=:user and adminpass=:pass",[':user'=>$this->adminuser,':pass'=>md5($this->adminpass)])->one();
			if (!$data && !$data1) {
				$this->addError('adminpass','用户名或密码错误');
			}
		}
	}

	//邮箱验证 找回密码
	public function validateEmail(){
		if (!$this->hasErrors()) {
			$data=$this->find()->where("adminuser=:user and adminemail=:email",[':user'=>$this->adminuser,':email'=>$this->adminemail])->one();
			if (is_null($data)) {
				$this->addError('adminemail','邮箱与账号不匹配');
			}
		}
	}

	public function login($data){
		$this->scenario='login';
		if ($this->load($data) && $this->validate()) {
			//登录成功
			/*$lifetime=$this->rememberMe?24*3600:0;
			session_set_cookie_params($lifetime);
			$session=yii::$app->session;
			$session['admin']=[
				'adminuser'=>$this->adminuser,
				'isLogin'=>1,
			];*/
			$lifetime=$this->rememberMe?24*3600:0;
			if(yii::$app->admin->login($this->getAdmin(),$lifetime)){
				//更新登录信息
				//$model=yii::$app->identity->
				$this->updateAll(['logintime'=>time(),'loginip'=>ip2long(yii::$app->request->userIp)] ,   
				"adminuser=:user",[':user'=>$this->adminuser]);
				return true;
			}
						
		}
		return false;

	}
	//获取一个管理员实例
	public function getAdmin(){
		return $this->find()->where('adminuser=:adminuser',[':adminuser'=>$this->adminuser])->one();
	}
	public function seekPass($data){
		$this->scenario='seekpass';
		if ($this->load($data) && $this->validate()) {
			//邮箱验证成功 授权码 hkbtest123
			//qq邮箱 fcypovgielpebicg
			$adminuser=$data['Admin']['adminuser'];
			$adminemail=$data['Admin']['adminemail'];
			$time=time();
			$mail= Yii::$app->mailer->compose('seekpass',['adminuser'=>$adminuser,'time'=>$time,'token'=>SELF::createToken($adminuser,$time)]);
		    $mail->setTo($adminemail);
		    $mail->setFrom('hkbmail@126.com');
		    $mail->setSubject('找回密码');
		    if ($mail->send()) {
		    	return true;
		    }
		}
		return false;
	}
	public function createToken($adminuser,$time){
		return md5(md5($adminuser).base64_encode(yii::$app->request->userIp).md5($time));
	}
	//修改密码
	public function changepass($data){
		$this->scenario="changepass";
		if ($this->load($data) && $this->validate()) {

			return (bool)$this->updateAll(['adminpass'=>md5($this->adminpass)],'adminuser=:user',[':user'=>$data['Admin']['adminuser']]);
			
		}
		return false;
	}

	public function attributeLabels(){
		return [
			'adminuser'=>'管理员账户',
			'adminemail'=>'管理员邮箱',
			'adminpass'=>'管理员密码',
			'repass'=>'确认密码',
		];
	}
	public function reg($data){

		$this->scenario='adminadd';
		//save()包含validate()方法 并且将自动识别更新和插入操作
		//save(false)表示让save方法不再执行验证
		if ($this->load($data) && $this->validate()) {
			 $this->adminpass=md5($this->adminpass);
			 $this->repass=md5($this->repass);
			 $this->createtime=time();
			if ($this->save(false)) {
				return true;		
			}else{
				return false;
			}
		}
		return false;

	}
	public static function findIdentity($adminid)
      {
          return static::findOne($adminid);
      }
 
      public static function findIdentityByAccessToken($token, $type = null)
      {
      	return ;
          //return static::findOne(['access_token' => $token]);
      }
 
      public function getId()
     {
         return $this->adminid;
     }

     public function getAuthKey()
    {
    	return ;
    }

     public function validateAuthKey($authKey)
   {
   		return true;
       //return $this->authKey === $authKey;
    }

}


 ?>