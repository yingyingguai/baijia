<?php
namespace backend\models;
use yii\db\ActiveRecord;

class Menu extends ActiveRecord{
    //定义规则
    public function rules()
    {
        return [
            [['label'], 'required'],
            [['label'], 'unique'],//唯一
            [['parent_id', 'sort'], 'integer'],
            [['label','url'], 'string'],
        ];
    }
    //静态方法 获取上级菜单
    //  自定义 id =0  parent_id = 0  顶级菜单
              public static function getMenu()
    {
        $menu = self::find()->select(['id', 'parent_id', 'label'])->where(['=', 'parent_id', 0])->asArray()->all();
        array_unshift($menu, ['id' => 0, 'parent_id' => 0, 'label' => '顶级菜单']);
        return $menu;
    }

    //关联子孙
    public function getChildren(){

    }
}