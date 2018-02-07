<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "test".
 *
 * @property integer $id
 * @property string $name
 * @property integer $time
 */
class Test extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'test';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'time'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'required', 'message' => '姓名不能为空','on'=>['add','mod']],
            //[['name'], 'required', 'message' => '姓名不能为空'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'time' => 'Time',
        ];
    }
    public function add($data){
        $this->scenario='add';
        if ($this->load($data) && $this->validate()) {
            if ($this->save(false)) {
                return true;
            }
        }
        return false;
    }
    public function mod($data,$id){
        $model=$this->findOne($id);        
        $this->scenario='mod';
        //$model->scenario='mod';
       // var_dump($this->scenario);exit;
        if ($model->load($data) && $model->validate()) {
            if ($model->save(false)) {
                return true;
            }
        }
        return false;
    }
}
