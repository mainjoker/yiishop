<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%product}}".
 *
 * @property string $productid
 * @property string $cateid
 * @property string $name
 * @property string $price
 * @property string $isbest
 * @property string $ishot
 * @property string $isnew
 * @property string $onsale
 * @property string $·forsale
 * @property string $·saleprice·
 * @property string $description
 * @property string $title
 * @property integer $num
 * @property string $addtime
 * @property string $cover
 * @property string $pics
 */
class Product extends \yii\db\ActiveRecord
{
    public $zero=0;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cateid', 'num'], 'integer'],
            #['cateid', 'required','message'=>'请选择分类','on'=>['addProduct']],
            ['cateid', 'validateCateid','on'=>['addProduct']],
            ['name', 'required','message'=>"商品名称不能为空",'on'=>['addProduct']],
            ['price', 'required','message'=>"商品价格不能为空",'on'=>['addProduct']],
            ['num', 'required','message'=>"商品库存不能为空",'on'=>['addProduct']],
            [['price', 'saleprice'], 'number'],
            [['isbest', 'ishot', 'isnew', 'onsale', 'forsale', 'pics'], 'string'],
            [['addtime'], 'safe'],
            [['pics'], 'required'],
            [['name', 'title'], 'string', 'max' => 120],
            [['description'], 'string', 'max' => 200],
            [['cover'], 'string', 'max' => 255],
            ['saleprice','safe'],
        ];
    }
    public function validateCateid(){
        if (!$this->hasErrors()) {
            if ($this->cateid==0) {
                $this->addError('cateid','请选择分类');
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'productid' => 'Productid',
            'cateid' => '分类名称',
            'name' => '商品名称',
            'price' => '商品价格',
            'isbest' => '是否精品',
            'ishot' => '是否热卖',
            'isnew' => '是否新品',
            'onsale' => '是否上架',
            'forsale' => '是否促销',
            'saleprice' => '促销价格',
            'description' => '商品描述',
            'title' => '标题',
            'num' => '商品库存',
            'addtime' => '添加时间',
            'cover' => '商品封面',
            'pics' => '商品图片',
        ];
    }
    //添加新商品
    public function addProduct($data){
        $this->scenario="addProduct";
        if ($this->load($data) && $this->validate()) {
            if ($this->save(false)) {
                return true;
            }
        }
        return false;
    }
  
}
