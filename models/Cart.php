<?php

namespace app\models;

use Yii;
use app\models\Product;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%cart}}".
 *
 * @property string $cartid
 * @property string $productid
 * @property string $productnum
 * @property string $price
 * @property string $userid
 * @property integer $cretetime
 */
class Cart extends \yii\db\ActiveRecord
{


    public function behaviors()
    {
        return [
                    [
                        'class' => TimestampBehavior::className(),
                        'createdAtAttribute' => 'createtime',
                        'updatedAtAttribute' => 'updatetime',
                        //'value' => new Expression('NOW()'),
                        'value' => time(),
                        'attributes'=>[
                            ActiveRecord::EVENT_BEFORE_INSERT => ['createtime', 'updatetime'],
                            ActiveRecord::EVENT_BEFORE_UPDATE => 'updatetime',
                        ]
                    ],
        ];
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cart}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['productid', 'productnum', 'userid', 'createtime'], 'integer'],
            [['price'], 'number'],
        ];
    }
    //获取购物车中的商品信息
    public function getProduct(){
        return $this->hasOne(Product::className(),['productid'=>'productid']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cartid' => 'Cartid',
            'productid' => 'Productid',
            'productnum' => 'Productnum',
            'price' => 'Price',
            'userid' => 'Userid',
            'createtime' => 'Createtime',
        ];
    }
}
