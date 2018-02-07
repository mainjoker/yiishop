<?php

namespace app\models;

use Yii;
use yii\data\Pagination;

/**
 * This is the model class for table "{{%category}}".
 *
 * @property string $cateid
 * @property string $title
 * @property integer $parentid
 * @property integer $createtime
 */
class Category extends \yii\db\ActiveRecord
{
 //   public $zero=0;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%category}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parentid', 'createtime','cateid','topid'], 'integer'],
            ['title','required','message'=>'请输入分类名称','on'=>['modMenu','addMenu']],
            //['title','required','message'=>'请输入分类名称'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cateid' => '分类目录',
            'title' => '分类名称',
            'parentid' => '上级分类',
            'createtime' => 'Createtime',
        ];
    }
    //获取按parentid排列的数据
    public function getTree($data,$pid=0){
        $tree=[];
        foreach ($data as $cate) {
            if ($cate['parentid']==$pid) {
                $tree[]=$cate;
                $tree=array_merge($tree,$this->getTree($data,$cate['cateid']));
        }
    }
        return $tree;
    }
    //按层级输出菜单
    public function getlist($data){
        $treelist=$this->getTree($data);
        $list[0]='添加顶级分类';
        foreach ($treelist as $val) {
            $num=$this->ifTop($val['cateid']);
            $list[$val['cateid']]=$this->navlist($num).$val['title'];
        }
        return $list;
    }
    //输出--
    public function navlist($num,$p='── '){

        return str_repeat($p, $num);

    }
    //判断当前栏目的上级栏目 parentid是不是顶级栏目 否则继续
    public function ifTop($cateid,$num=0){
        $res=$this->find()->select("parentid")->where("cateid=:cateid",['cateid'=>$cateid])->asArray()->one();
        if ($res['parentid']!=0) {
            $num=$num+1;
            return $this->ifTop($res['parentid'],$num);
        }
           return $num;
    }
    //获取topid
    public function getTopid($cateid){
        $res=$this->find()->select("cateid,parentid")->where("cateid=:cateid",['cateid'=>$cateid])->asArray()->one();
        if ($res['parentid']!=0) {
             return $this->getTopid($res['parentid']);
        }
        return $res['cateid'];

    }
    //获取某个分类下的所有子类
    public function getChildren($cateid){
        static $children=[];
        $data=$this->find()->where("parentid=:pid",[':pid'=>$cateid])->asArray()->all();
        foreach ($data as $key => $val) {
           $children[]=$val['cateid'];
           if (!$this->find()->where("parentid=:pid",[':pid'=>$val['cateid']])->all()) {
               continue;
           }
           $this->getChildren($val['cateid']);
        }
        if (!$children) {
            return $cateid;
        }
        return $children;

    }
    //添加分类
    public function addMenu($data){
        $this->scenario='addMenu';
        $data['Category']['topid']=$this->getTopid($data['Category']['cateid']);        
        $data['Category']['parentid']=$data['Category']['cateid'];
        $data['Category']['cateid']='';
        $data['Category']['createtime']=time();
        if ($this->load($data) && $this->validate()) {
            if ($this->save(false)) {
                return true;
            }else{
                return false;
            }
            
        }
        return false;
    }
    //编辑分类
    public function modMenu($data,$cateid){

         $data['Category']['topid']=(int)$this->getTopid($data['Category']['parentid']);
         $data['Category']['createtime']=time();
         $model=$this->findOne($cateid);
         $model->scenario='modMenu';
        // $model=$this->find()->where('cateid=:cateid',[':cateid'=>$cateid])->one();
         if ($model->load($data) && $model->validate()) {
           
            if ( $model->save(false)) {
                 return true;    
            }
          
        }
        return false;
    }
    //删除分类
    public function delMenu($cateid){
        $model=$this->findOne($cateid);
        $data=$this->find()->where("parentid=:parentid",[':parentid'=>$model['cateid']])->one();
        //var_dump($data);exit;
        if ($data) {
            return 2;
        }else{
            if ($this->deleteAll("cateid=:cateid",[':cateid'=>$cateid])) {
                return true;
            }else{
                return false;
            }
        }
    }
    //获取所有的顶级栏目以及二级栏目
    public function getMenu(){
        //顶级分类
        $cates=$this->find()->where("topid=:pid",[':pid'=>0])->asArray()->all();
        //二级分类
        $data=[];
        foreach ($cates as $key => $cate) {
            $cate['children']=self::find()->where('parentid=:pid',[':pid'=>$cate['cateid']])->asArray()->all();
            $data[$key]=$cate;
        }

        return $data;
    }

    //Jstree获取顶级栏目
    public function tree(){
        //yii::$app->response->format=Response::FORMAT_JSON;
        $cates=self::find()->where("parentid=:pid",[':pid'=>0]);
        if (!$cates) {
            return [];
        }
        $pageSize=yii::$app->params['pageSize']['category'];
        $pagers=new Pagination(['totalCount'=>$cates->count(),'pageSize'=>$pageSize]);
        $data=$cates->offset($pagers->offset)->limit($pagers->limit)->asArray()->all();
        $primary=[];
        foreach ($data as $cate) {
            $primary[]=[
                'id'=>$cate['cateid'],
                'text'=>$cate['title'],
                'children'=>$this->childrenTree($cate['cateid'])
            ];
        }
        return $primary;
    }
    //jstree获取子级栏目
    public function childrenTree($cateid){
        $cates=self::find()->where("parentid=:pid",[':pid'=>$cateid])->asArray()->all();
        if (!$cates) {
            return [];
        }
        $data=[];
        foreach ($cates as $cate) {
            $data[]=[
                'id'=>$cate['cateid'],
                'text'=>$cate['title'],
                'children'=>$this->childrenTree($cate['cateid'])
            ];
        }
        return $data;
    }
    
}
