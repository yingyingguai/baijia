<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\LoginForm;
use backend\models\User;

use yii\captcha\CaptchaAction;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Request;

class UserController extends Controller
{   //用户表显示
    public function actionIndex()
    {
        $users = User::find()->All();
        return $this->render('index', ['users' => $users]);
    }

    //>>添加
    public function actionAdd()
    {
        //>>加载组件
        $request = new Request();
        //>>创建模型
        $model = new User();
        $authManager = \Yii::$app->authManager;
        //获取所有角色
        $roles = ArrayHelper::map($authManager->getRoles(), 'name', 'description');


        if ($request->isPost) {
            //>>加载表单数据
            $model->load($request->post());
            if ($model->validate()) {

                //>>对密码 进行加密
                $model->password_hash = \Yii::$app->security->generatePasswordHash($model->password_hash);
                //>>保存
                $model->save(false);

                //添加角色
                //1.获取角色
                $roles = $model->roles;


                //2.获取id'
                $id = \Yii::$app->db->lastInsertID;
                foreach ($roles as $role) {
                    $role = $authManager->getRole($role);
                    $authManager->assign($role, $id);
                }


                //>>设置提示信息
                \Yii::$app->session->setFlash('success', '添加成功!');
                //>>跳转回首页
                return $this->redirect(['user/index']);
            } else {
                //>>验证失败后 打印错误信息
                var_dump($model->getErrors());
            }
        } else {
            return $this->render('add', ['model' => $model, 'roles' => $roles]);
        }
    }

    //>>删除
    public function actionDelete($id)
    {
        $authManager = \Yii::$app->authManager;
        $authManager->revokeAll($id);
        //>>查找id
        User::findOne(['id' => $id])->delete();
        //>>提示信息
        \Yii::$app->session->setFlash('success', '删除成功');
        //>>跳转
        return $this->redirect(['user/index']);
    }
    //>>管理员修改
    public function actionEdit($id)
    {
        $request = \Yii::$app->request;
        $model = User::find()->where(['id' => $id])->one();

        $authManager = \Yii::$app->authManager;
        //获取所有角色
        $roles = ArrayHelper::map($authManager->getRoles(), 'name', 'description');
        $arr = $authManager->getRolesByUser($id);
        //将权限便利出来 放入权限中
        $model->roles=[];

        foreach ($arr as $v) {
            $model->roles[] = $v->name;
        }

        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {

//                $password_hash = \Yii::$app->security->generatePasswordHash($model->password_hash);
//                $model->password_hash = $password_hash;

                $model->save(false);

                //修改角色
                $authManager->revokeAll($id);
                //1.获取角色
                $roles = $model->roles;
                if ($roles){
                    foreach ($roles as $role) {
                        $role = $authManager->getRole($role);
                        $authManager->assign($role, $id);
                    }
                }
                \Yii::$app->session->setFlash('success', '修改成功');
                return $this->redirect(['user/index']);
            }
        }
        return $this->render('update', ['model' => $model,'roles'=>$roles]);
    }

    //登录
    public function actionLogin()
    {
        $model = new LoginForm();
        $request = \Yii::$app->request;
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->login()) {
                //保存当前登录时间和登录ip
                $user = User::findOne(['id' => \Yii::$app->user->identity->id]);
                $user->updateAttributes(['last_login_time' => time()]);
                $user->updateAttributes(['last_login_ip' => \Yii::$app->request->userIP]);
                $user->save(false);
                //>>跳转
                return $this->redirect(['user/index']);
            }
        }
        return $this->render('login', ['model' => $model]);
    }

    //验证码
    public function actions()
    {
        return [
            'captcha' => [
                'class' => CaptchaAction::className(),
                'height' => 34,
                'minLength' => 4,
                'maxLength' => 4
            ]
        ];
    }

    //>>注销登录
    public function actionLogout()
    {
        \Yii::$app->user->logout();

        return $this->redirect(['user/login']);
    }

    //>>>个人中心
    public function actionCenter()
    {
        return $this->render('center');
    }

    //>>>修改自己的密码
    public function actionUpdate()
    {
        /**
         * 1.根据session中是否有数据 来展示不同的个人中心
         * 2.点击修改 来到修改页面 旧密码  新密码 确认密码
         *
         * 3. 判断输入的旧密码与数据库是否相同  x 提示
         * 4.判断新密码与确认密码是否相等   x 提示
         * 5.上面验证都通过  将新密码保存到数据库
         */
        $id = \Yii::$app->user->identity->id;
        $model = User::findOne(['id' => $id]);
        $request = new Request();
        if ($request->isPost) {
            //>>加载表单数据
            $model->load($request->post());
            if ($model->validate()) {
                $model->updateAttributes(['password_hash' => \Yii::$app->security->generatePasswordHash($model->re_password)]);
                \Yii::$app->session->setFlash('success', '修改密码成功');
            }
            return $this->redirect(['user/center']);
        }


        return $this->render('edit', ['model' => $model]);


    }

//    public function behaviors()
//    {
//        return [
//            'rbac'=>[
//                'class'=>RbacFilter::className(),
//                //'only'=>[],
//                'except'=>['login','logout','upload'],
//            ]
//        ];
//    }

}