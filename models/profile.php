<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%profile}}".
 *
 * @property string $id
 * @property string $userid
 * @property string $nickname
 * @property string $headimg
 * @property string $truename
 * @property string $birthday
 * @property string $sex
 */
class Profile extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%profile}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userid'], 'integer'],
            [['nickname'], 'required','message'=>'用户昵称不能为空'],
            [['birthday'], 'safe'],
            [['sex'], 'string'],
            [['nickname'], 'string', 'max' => 32],
            [['headimg', 'truename'], 'string', 'max' => 50],
            [['userid'], 'unique'],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'userid' => 'Userid',
            'nickname' => '用户昵称',
            'headimg' => 'Headimg',
            'truename' => '真实姓名',
            'birthday' => 'Birthday',
            'sex' => '用户性别',
        ];
    }
}
