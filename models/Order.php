<?php

namespace app\models;
use app\models\Orderinfo;
use Yii;

/**
 * This is the model class for table "{{%order}}".
 *
 * @property string $orderid
 * @property string $ordersn
 * @property string $userid
 * @property integer $order_status
 * @property integer $pay_status
 * @property string $name
 * @property string $address
 * @property string $phone
 * @property string $count
 * @property integer $createtime
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userid', 'order_status', 'pay_status', 'count', 'createtime'], 'integer'],
            [['ordersn'], 'string', 'max' => 20],
            [['name', 'address'], 'string', 'max' => 120],
            ['address','required','message'=>'地址不能为空','on'=>['add']],
            ['name','required','message'=>'收件人不能为空','on'=>['add']],
            [['phone'], 'string', 'max' => 60],
            [['ordersn'], 'unique'],
            ['express','safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'orderid' => 'Orderid',
            'ordersn' => 'Ordersn',
            'userid' => 'Userid',
            'order_status' => 'Order Status',
            'pay_status' => 'Pay Status',
            'name' => 'Name',
            'address' => 'Address',
            'phone' => 'Phone',
            'count' => 'Count',
            'createtime' => 'Createtime',
        ];
    }
    public function getorderinfo(){
        $orderinfo=new Orderinfo;
        return $this->hasmany($orderinfo::className(),['orderid'=>'orderid']);
    }
}
