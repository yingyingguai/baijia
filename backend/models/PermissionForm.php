<?php

namespace backend\models;

use yii\base\Model;

class PermissionForm extends Model
{
    public $name;//路由
    public $description;//描述
    //场景
    const SCENARIO_ADD_PERMISSION = 'add-permission'; //添加权限场景
    const SCENARIO_EDIT_PERMISSION = 'edit-permission'; //修改权限场景

    public function rules()
    {
        return [
            [['name', 'description'], 'required'],
            ['name','theone','on'=>[self::SCENARIO_ADD_PERMISSION]],
            //修改时验证权限名称
           ['name','updateName','on'=>self::SCENARIO_EDIT_PERMISSION]
        ];
    }
    //判断添加的name是否唯一
    public function theone()
    {
        //添加的名字 是否等于 修改提交的名字
        $authManager = \Yii::$app->authManager;
        $permission = $authManager->getPermission($this->name);
        if ($permission) {
            $this->addError('name', '该权限已存在');
        }
    }
    //修改时也要验证  场景不一样
    public function updateName(){
        $authManager = \Yii::$app->authManager;
        //名称是否修改 得到 回显的name
        $oldName = \Yii::$app->request->get('name');
       //最初的name 不等于 现在的name
        if($oldName != $this->name){
            //查库看是否存在
            $name = $authManager->getPermission($this->name);
            if($name){
                $this->addError('name','该权限已存在');
            }
        }
    }

}