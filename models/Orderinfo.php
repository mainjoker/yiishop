<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%orderinfo}}".
 *
 * @property string $orderinfoid
 * @property string $productid
 * @property string $orderid
 * @property string $num
 * @property string $price
 * @property string $product_name
 */
class Orderinfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%orderinfo}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['productid', 'orderid', 'num'], 'integer'],
            [['price'], 'number'],
            [['product_name'], 'string', 'max' => 120],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'orderinfoid' => 'Orderinfoid',
            'productid' => 'Productid',
            'orderid' => 'Orderid',
            'num' => 'Num',
            'price' => 'Price',
            'product_name' => 'Product Name',
        ];
    }
}
