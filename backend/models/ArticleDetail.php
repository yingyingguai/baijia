<?php

namespace backend\models;

use yii\db\ActiveRecord;

class ArticleDetail extends ActiveRecord
{

    public function rules()
    {
        return [
                ['content','required']
        ];
    }

    //关联两张表 article_detail article
    public function getArticle()
    {
        return $this->hasOne(Article::className(), ['id' => 'A_id']);
    }
    public function attributeLabels()
    {
        return [

            'content' => '内容',

        ];
    }
}
