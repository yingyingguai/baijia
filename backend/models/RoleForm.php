<?php
namespace backend\models;
use yii\base\Model;

class RoleForm extends Model{

    public $name;//角色名称
    public $description;//角色描述
    public $permissions=[];//角色的权限



    public function rules()
    {
        return [
            [['name','description'],'required'],
            [['permissions'],'safe'],

        ];
    }
    public function attributeLabels()
    {
        return [
            'name'=>'名称',
            'description'=>'描述',
            'permissions'=>'权限'
        ];
    }
}