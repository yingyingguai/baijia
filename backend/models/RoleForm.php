<?php
namespace backend\models;
use yii\base\Model;

class RoleForm extends Model{

    public $name;//角色名称
    public $description;//角色描述
    public $permissions=[];//角色的权限
    //场景
    const SCENARIO_ADD_ROLE= 'add-role'; //添加角色场景
    const SCENARIO_EDIT_ROLE = 'edit-role'; //修改角色场景


    public function rules()
    {
        return [
            [['name','description'],'required'],
            [['permissions'],'safe'],
            ['name','theone','on'=>[self::SCENARIO_ADD_ROLE]],
            //修改时验证角色名称
            ['name','updateName','on'=>self::SCENARIO_EDIT_ROLE]

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
    //判断添加的name是否唯一
    public function theone()
    {
        //添加的名字 是否等于 修改提交的名字
        $authManager = \Yii::$app->authManager;
        $role = $authManager->getRole($this->name);
        if ($role) {
            $this->addError('name', '该角色已存在');
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
            $name = $authManager->getRole($this->name);
            if($name){
                $this->addError('name','该角色已存在');
            }
        }
    }
}