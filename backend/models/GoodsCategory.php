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
           ['parent_id','check']
        ];
    }
    //自定义验证规则
    public function check(){
        $parent=GoodsCategory::findOne(['id'=>$this->parent_id]);
        //处理验证不通过
        if ($parent->isChildOf($this)){
            //只添加错误信息
            $this->addError('parent_id','不能修改为自己的子孙节点');
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


}