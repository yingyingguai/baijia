<?php
namespace backend\models;
use yii\db\ActiveRecord;

class Goods extends ActiveRecord{
    public $content;
    public $path;
    //1.规则
    public function rules()
    {
        return [
            [['name','logo','market_price','goods_category_id','shop_price','is_on_sale','status','content'],'required'],
            [['stock','sn','goods_category_id','brand_id','sort'],'integer'],
            ['view_time', 'default', 'value' => 0],
    ];
    }
    //定义字段的标签名称
    public function attributeLabels()
    {
        return [
            'name' => '商品名',
            'sn' => '货号',
            'logo' => 'LOGO',
            'goods_category_id' => '商品分类',
            'brand_id' => '品牌分类',
            'market_price' => '市场价格',
            'shop_price' => '商场价格',
            'stock' => '库存',
            'is_on_sale' => '是否在售',
            'status' => '状态',
            'sort' => '排序',
            'create_time' => '创建时间',
            'view_time' => '浏览次数',

        ];
    }
    //关联两张表 Goods_category goods
    public function getGoodsCategory()
    {
        return $this->hasOne(GoodsCategory::className(), ['id'=>'goods_category_id']);
    }
  //关联两张表 brand goods
    public function getBrand()
    {
        return $this->hasOne(Brand::className(), ['id'=>'brand_id']);
    }



}