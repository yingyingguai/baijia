<?php
namespace backend\models;
use yii\db\ActiveRecord;

class Gallery extends ActiveRecord{

    public function rules()
    {
        return [
            ['path','required']
        ];
    }
    //关联两张表
//    public function get()
//    {
//        return $this->hasOne(ArticleCategory::className(), ['id'=>'article_category_id']);
//    }
}

