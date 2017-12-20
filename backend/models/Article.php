<?php
namespace backend\models;
use yii\db\ActiveRecord;

class Article extends ActiveRecord{

    //1.设置规则
    public function rules()
    {
        return [
          [['name','intro','sort','article_category_id'],'required'],
        ];
    }
    //关联两张表 article_category article
    public function getArticle_category()
    {
        return $this->hasOne(Article_category::className(), ['id'=>'article_category_id']);
    }
}