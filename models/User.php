<?php 
namespace app\models;
use yii;
use yii\db\ActiveRecord;
use app\models\profile;



class User extends ActiveRecord implements \yii\web\IdentityInterface{
	public $repass;
/*	public $nickname;
	public $sex;
	public $truename;*/
	public static function TableName(){
		return "{{%user}}";
	}
	public function rules(){
		return [
				['username','required','message'=>'用户名不能为空','on'=>['login','register','adduser']],
				['username','unique','message'=>'该用户名已被注册','on'=>['register','adduser']],
				['password','required','message'=>'密码不能为空','on'=>['login','register','adduser']],
				['password','validatePass','on'=>['login']],
				['repass','compare','compareAttribute'=>'password','message'=>'确认密码错误','on'=>['register','changepass','seekpass','adduser']],
				['repass','required','message'=>'确认密码不能为空','on'=>['register','adduser']],
				['useremail','unique','message'=>'邮箱已被注册','on'=>['register','adduser']],
				['useremail','required','message'=>'用户邮箱不能为空','on'=>['register','adduser']],
				['useremail','email','message'=>'邮箱格式错误','on'=>['register','adduser']],
			];
	}
	public function getProfile(){
		$model=new profile;
		return $this->hasOne($model::className(),['userid'=>'userid']);
	}
	//密码验证
	public function validatePass(){
		if (!$this->hasErrors()) {
			//账号或邮箱登录
			$data1=$this->find()->where("username=:user and password=:pass",[':user'=>$this->username,':pass'=>md5($this->password)])->one();

			if (!$data1) {
				$this->useremail=$this->username;
				$this->username='';
			}

			$data2=$this->find()->where("useremail=:useremail and password=:pass",[':useremail'=>$this->useremail,':pass'=>md5($this->password)])->one();
		
			if (!$data1 && !$data2 ) {
				$this->addError('password','账号或密码错误');
			}
		}
		
	}
	public function attributeLabels(){
		return [
			'username'=>'用户名',
			'useremail'=>'用户邮箱',
			'password'=>'密码',
			'repass'=>'确认密码',
			'nickname'=>'用户昵称',
			'sex'=>'用户性别',
			'birthday'=>'用户生日',
			'truename'=>'用户真实姓名',

		];
	}
	public function login($data){
		$this->scenario='login';

		//用户登录
		if ($this->load($data) && $this->validate()) {
			//登录成功
			//自动登录
			/*$lifetime=24*3600;
			session_set_cookie_params($lifetime);
			$session=yii::$app->session;*/
			$lifetime=24*3600;
			if(yii::$app->user->login($this->getUser(),$lifetime)){
				//更新登录信息
				if ($this->username) {
					$this->updateAll(['loginip'=>ip2long(yii::$app->request->userIp),'logintime'=>time()],'username=:username',[':username'=>$this->username]);
				}else{
					$data=$this->find()->where("useremail=:useremail",[':useremail'=>$this->useremail])->one();
					$username=$data->username;
					$this->updateAll(['loginip'=>ip2long(yii::$app->request->userIp),'logintime'=>time()],'useremail=:useremail',[':useremail'=>$this->useremail]);
				}
				return true;
			}
		return false;
		}	
	}
	//添加用户
	public function adduser($data){
		$this->scenario='adduser';
		$profile=new profile;
		//多模型输入
		if ($this->load($data) && $this->validate()) {
			//开启事务
			$transaction=yii::$app->db->beginTransaction();
			try {
				$profile=new profile;
				$res1=$this->save(false);
				$userid=$this->userid;
				$data['Profile']['userid']=$userid;
				$profile->load($data);
				$res2=$profile->save();
				if ($res1 && $res2) {
					 $transaction->commit();
					 $this->password='';
					 $this->repass='';
					 return true;
				}else{
					$transaction->rollBack();
				}
				
			} catch (\Exception $e) {
					$transaction->rollBack();
			}
		}
	}
	public function getUser(){

		$loginname=$this->username?$this->username:$this->useremail;

		return $this->find()->where("useremail=:loginname or username=:loginname",[':loginname'=>$loginname])->one();
	}
	public static function findIdentity($userid){
		return static::findOne($userid);

	}
	public  static function findIdentityByAccessToken($token, $type = NULL){
		return null;
	}
	public  function getId(){
		return $this->userid;
	}
	public  function getAuthKey(){
		 return '';
		  //return $this->authKey;
	}
	public  function validateAuthKey($authKey)
    {
    	return true;
        //return $this->authKey === $authKey;
    }




}




 ?>