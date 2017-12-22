<?php
namespace backend\models;
use yii\db\ActiveRecord;

class Article extends ActiveRecord{
   public  $content;
    //1.设置规则
    public function rules()
    {
        return [
          [['name','intro','sort','article_category_id','status','content'],'required'],
        ];
    }
    //关联两张表 article_category article
    public function getArticleCategory()
    {
        return $this->hasOne(ArticleCategory::className(), ['id'=>'article_category_id']);
    }


    //定义字段的标签名称
    public function attributeLabels()
    {
        return [
            'name' => '姓名',
            'intro' => '简介',
            'sort' => '排序',
            'status' => '状态',
            'article_category_id' => '文章分类',
            'content' => '内容',

        ];
    }
}