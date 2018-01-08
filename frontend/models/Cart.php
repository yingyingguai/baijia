<?php

use backend\models\Goods;
use yii\db\ActiveRecord;

class Cart extends ActiveRecord{

    public function rules()
    {
        return [
          [['goods_id','amount','member_id'],'exist']  ,
        ];
    }
    //关联商品表
    public function getGoods()
    {
        return $this->hasOne(Goods::className(), ['id'=>'goods_id']);
    }
    //关联用户表
      public function getUser()
    {
        return $this->hasOne(\frontend\models\Member::className(), ['id'=>'member_id']);
    }





}