<?php

namespace backend\models;

use yii\db\ActiveRecord;
use creocoder\nestedsets\NestedSetsBehavior;

class  GoodsCategory extends ActiveRecord
{

    public function rules()
    {
        return [
            [['tree', 'lft', 'rgt', 'depth', 'parent_id'], 'integer'],
            ['intro', 'string'],
            ['name', 'string'],
            ['parent_id', 'check']
        ];
    }

    //自定义验证规则
    public function check()
    {
        if ($this->parent_id) {
            $parent = GoodsCategory::findOne(['id' => $this->parent_id]);
            //处理验证不通过
            if ($parent->isChildOf($this)) {
                //只添加错误信息
                $this->addError('parent_id', '不能修改为自己的子孙节点');
            }
        }

    }

    //定义字段的标签名称
    public function attributeLabels()
    {
        return [
            'name' => '姓名',
            'intro' => '简介',
            'parent_id' => '上级分类',
            'tree' => '树id',
            'lft' => '左值',
            'rgt' => '右值',
            'depth' => '层级',

        ];
    }

    //以下为插件
    public function behaviors()
    {
        return [
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                'treeAttribute' => 'tree',
                // 'leftAttribute' => 'lft',
                // 'rightAttribute' => 'rgt',
                // 'depthAttribute' => 'depth',
            ],
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find()
    {
        return new CategoryQuery(get_called_class());
    }

    //静态方法 获取子节点
    public static function getNodes()
    {
        $nodes = self::find()->select(['id', 'parent_id', 'name'])->asArray()->all();
        array_unshift($nodes, ['id' => 0, 'parent_id' => 0, 'name' => '【顶级分类】']);
        return json_encode($nodes);
    }

    //获取视图加载到 前台首页
    public static function getCategorys(){

        //开启redis
        $redis = new \Redis();
        $redis->connect('127.0.0.1');
        $html=$redis->get('category_html');
        if ($html==false){

        //    $html = '';


            $categorys1 = \backend\models\GoodsCategory::find()->where(['parent_id' => 0])->all();
            //点击一级分类 二级分类 三级分类

            foreach ($categorys1 as $k1 => $category1){
                $html .= '<div class="cat ' . ($k1 ? '' : 'item1') . '">';
                $html .= '<h3><a href="'.\yii\helpers\Url::to(['list/index','id'=>$category1->id]).'">' . ($category1->name) . '</a><b></b></h3>';
                $html .= '<div class="cat_detail">';
                $categorys2 = \backend\models\GoodsCategory::find()->where(['parent_id' => $category1->id])->all();
                foreach ($categorys2 as $k2 => $category2){
                    $html .= ' <dl class="' . ($k2 ? '' : 'dl_1st') . '">';
                    $html .= ' <dt><a href="'.\yii\helpers\Url::to(['list/index','id'=>$category2->id]).'">' . ($category2->name) . ' </a></dt>  <dd>';
                    $categorys3 = \backend\models\GoodsCategory::find()->where(['parent_id' => $category2->id])->all();
                    foreach ($categorys3 as $k3 => $category3){
                        $html .= '<a href="'.\yii\helpers\Url::to(['list/index','id'=>$category3->id]).'">' . ($category3->name) . '</a>';
                    }
                    $html .= ' </dd></dl>';
                }
                $html .= '</div></div>';
            }

            $redis->set('category_html',$html,24*3600);
        }

        return $html;
    }


}