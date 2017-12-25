<?php
namespace backend\models;
use yii\db\ActiveRecord;

class GoodsDay extends  ActiveRecord{

    public function rules()
    {
        return [
          [['day','count'],'required']
        ];
    }
    public function attributeLabels(){
        return [
          'day'=>'日期'  ,
            'count'=>'数量'
        ];
    }
}