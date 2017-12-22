<?php
namespace backend\models;
use yii\db\ActiveRecord;
class Brand extends ActiveRecord
{
    //1.制定规则
    public function rules()
    {
        return [
            [['name', 'intro', 'sort', 'status'], 'required'],
            [['sort','status'], 'integer'],
            ['sort', 'unique'],
            ['logo', 'default', 'value' => null],
      ];
    }
    //定义字段的标签名称
    public function attributeLabels()
    {
        return [
            'name' => '姓名',
            'intro' => '简介',
            'sort' => '排序',
            'status' => '状态',

        ];
    }
}