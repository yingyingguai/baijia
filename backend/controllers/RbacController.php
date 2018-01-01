<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\PermissionForm;
use backend\models\RoleForm;
use Codeception\Module\REST;
use yii\helpers\ArrayHelper;
use yii\rbac\Permission;
use yii\rbac\Role;
use yii\web\Controller;
use yii\web\Request;

class RbacController extends Controller
{
    //>>>一.权限
    //1.权限增加
    public function actionAddPermission()
    {
        $authManager = \Yii::$app->authManager;
        //1.1 新建一个权限
        $model = new PermissionForm();
        $permission = new Permission();
        $request = new Request();
        //场景
        $model->scenario = PermissionForm::SCENARIO_ADD_PERMISSION;
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
                $permission->name = $model->name;
                $permission->description = $model->description;
                $authManager->add($permission);
                //提示信息 跳转
                \Yii::$app->session->setFlash('success', '添加成功!');
                //跳转到首页
                return $this->redirect(['rbac/index-permission']);
            } else {
                //打印错误信息
                var_dump($model->getErrors());
            }
        }
        //1.2 新建表单模型
        return $this->render('permission-add', ['model' => $model]);
    }

    //2.权限展示
    public function actionIndexPermission()
    {
        $authManager = \Yii::$app->authManager;
        $models = $authManager->getPermissions();
        //调用视图
        return $this->render("permission-index", ['models' => $models]);

    }

    //3.权限修改
    public function actionEditPermission($name)
    {
        $authManager = \Yii::$app->authManager;
        //1.1 新建一个权限
        $model = new PermissionForm();
        //场景
        $model->scenario = PermissionForm::SCENARIO_EDIT_PERMISSION;
        //回显的--------------------------
        $permission = $authManager->getPermission($name);
        $model->name = $permission->name;
        $model->description = $permission->description;
        // 回显的 ------------------------------------------------
        $request = new Request();
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
                $permission->name = $model->name;
                $permission->description = $model->description;
                //更新权限
                $authManager->update($name, $permission);
                //提示信息 跳转
                \Yii::$app->session->setFlash('success', '修改成功!');
                //跳转到首页
                return $this->redirect(['rbac/index-permission']);
            } else {
                //打印错误信息
                var_dump($model->getErrors());
            }
        }
        //1.2 新建表单模型
        return $this->render('permission-add', ['model' => $model]);
    }

    //4.权限删除
    public function actionDeletePermission($id)
    {
        $permission = \Yii::$app->authManager->getPermission($id);
        $res = \Yii::$app->authManager->remove($permission);
        echo json_encode($res);
    }
    //测试data
    public function actionShow()
    {
        return $this->render('data');
    }

    //>>>二.角色
    //1.添加角色
    public function actionAddRole()
    {
        $role = new Role();
        $model = new RoleForm();
        $request = new Request();
   $model->scenario = RoleForm::SCENARIO_ADD_ROLE;
        $authManager = \Yii::$app->authManager;
        $options = ArrayHelper::map($authManager->getPermissions(), 'name', 'description');
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
                //------------------------------------------------------
                $role->name = $model->name;
                $role->description = $model->description;
                $a = $authManager->add($role);
                if ($a) {//保存到数据表
                    //关联该角色的权限
                    foreach ($model->permissions as $permissionName) {
                        $permission = $authManager->getPermission($permissionName);
                        if ($permission) {
                            $authManager->addChild($role, $permission);
                        }
                    }
                    //提示并跳转
                    \Yii::$app->session->setFlash('success', '添加成功!');
                    //跳转到首页
                    return $this->redirect(['rbac/index-role']);
                    //---------------------------------------------------
                }
            }
        }
        return $this->render('role-add', ['model' => $model, 'options' => $options]);
    }

    //2.显示角色
    public function actionIndexRole()
    {
        $authManager = \Yii::$app->authManager;
        $models = $authManager->getRoles();
        //调用视图
        return $this->render("role-index", ['models' => $models]);

    }

    //3修改角色
    public function actionEditRole($name)
    {
        $authManager = \Yii::$app->authManager;
        //1.1 新建一个权限
        $model = new RoleForm();
        $request = new  Request();
        //场景
        $model->scenario = RoleForm::SCENARIO_EDIT_ROLE;

        // var_dump($model);die;
        $role = $authManager->getRole($name);
        $options = ArrayHelper::map($authManager->getPermissions(), 'name', 'description');
        $model->name = $role->name;
        $model->description = $role->description;
        $arr = $authManager->getPermissionsByRole($name);
        //将权限便利出来 放入权限中
        $model->permissions=[];
        foreach ($arr as $v) {
            $model->permissions[] = $v->name;
        }
        //验证post
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
                //修改
                //1.1 将原来的角色权限全部删除
                $authManager->removeChildren($role);
                //1.2 重新赋值
                $role->name = $model->name;
                $role->description = $model->description;
                $a = $authManager->update($name,$role);
                if ($a) {//保存到数据表
                    //关联该角色的权限
                    if ($model->permissions){
                        foreach ($model->permissions as $permissionName) {
                            $permission = $authManager->getPermission($permissionName);
                            $authManager->addChild($role, $permission);
                        }
                    }
                    //提示并跳转
                    \Yii::$app->session->setFlash('success', '修改成功!');
                    //跳转到首页
                    return $this->redirect(['rbac/index-role']);
                }
            }
        }
        return $this->render('role-add', ['model' => $model, 'options' => $options]);
    }
    //删除角色
    public function actionDeleteRole($id)
    {
        $authManager = \Yii::$app->authManager;

       $res= $authManager->remove($authManager->getRole($id));
       echo json_encode($res);


    }


    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>RbacFilter::className(),
                //'only'=>[],
                'except'=>['login','logout','upload'],
            ]
        ];
    }
}