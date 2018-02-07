<?php
namespace app\models;
use yii\elasticsearch\ActiveRecord;
//ES索引名称shop 索引类型product
class ProductSearch extends ActiveRecord
{
  public static $currentIndex;
  
  # 定义db链接
  public static function getDb()
  {
    return \Yii::$app->get('elasticsearch');
  }
  
  
  # db
  public static function index()
  {
    return 'shop';
  }
  # table
  public static function type()
  {
    return 'product';
  }
  
  # 属性
  public function attributes()
    {
        $mapConfig = self::mapConfig();
        return array_keys($mapConfig['properties']);
    }
  # mapping配置
  public static function mapConfig(){
   /* return [
      'properties' => [
        'customer_id'  => ['type' => 'long',  "index" => "not_analyzed"],
        'uuids'      => ['type' => 'string',  "index" => "not_analyzed"],
        'updated_at'  => ['type' => 'long',  "index" => "not_analyzed"],
        'emails'    => ['type' => 'string',"index" => "not_analyzed"],
      ]
    ];*/
    return [
      'propertiies'=>[
          'name'=>['type'=>'string'],
          'description'=>['type'=>'string'],
      ]
    ];
  }
  
  public static function mapping()
    {
        return [
            static::type() => self::mapConfig(),
        ];
    }
    /**
     * Set (update) mappings for this model
     */
    public static function updateMapping(){
        $db = self::getDb();
        $command = $db->createCommand();
    if(!$command->indexExists(self::index())){
      $command->createIndex(self::index());
    }
        $command->setMapping(self::index(), self::type(), self::mapping());
    }
  
  public static function getMapping(){
    $db = self::getDb();
        $command = $db->createCommand();
    return $command->getMapping();
  }
  
  
  
}