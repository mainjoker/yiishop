<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%express}}".
 *
 * @property string $expressid
 * @property string $name
 * @property string $fee
 */
class Express extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%express}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fee'], 'number'],
            [['name'], 'string', 'max' => 120],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'expressid' => 'Expressid',
            'name' => 'Name',
            'fee' => 'Fee',
        ];
    }
}
