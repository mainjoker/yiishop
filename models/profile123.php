<?php 
namespace app\models;
use yii\db\ActiveRecord;

class profile extends ActiveRecord{
	public static function tableName(){
		return "{{%profile}}";
	}
	public function rules(){

		return [
			['nickname','required','message'=>'用户昵称不能为空'],
		];
	}
	public function attributeLabels(){
		return [
			'nickname'=>'用户昵称',
			'sex'=>'性别',
			'birthday'=>"生日",
			'truename'=>"真实姓名",
		];
	}

}



 ?>