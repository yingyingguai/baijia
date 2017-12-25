<?php

namespace backend\controllers;

use backend\models\LoginForm;
use backend\models\User;

use yii\captcha\CaptchaAction;
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
    public function actionAdd(){
        //>>加载组件
        $request = new Request();
        //>>创建模型
        $model = new User();
        if ($request->isPost) {
            //>>加载表单数据
            $model->load($request->post());
            if ($model->validate()) {
                //>>对密码 进行加密
                $model->password_hash = \Yii::$app->security->generatePasswordHash($model->password_hash);
                //>>保存
                $model->save();
                //>>设置提示信息
                \Yii::$app->session->setFlash('success', '添加成功!');
                //>>跳转回首页
                return $this->redirect(['user/index']);
            } else {
                //>>验证失败后 打印错误信息
                var_dump($model->getErrors());
            }
        } else {
            return $this->render('add', ['model' => $model]);
        }
    }
    //>>删除
    public function actionDelete($id)
    {
            //>>查找id
            User::findOne(['id' => $id])->delete();
            //>>提示信息
            \Yii::$app->session->setFlash('success', '删除成功');
            //>>跳转
            return $this->redirect(['user/index']);
        }



//登录
    public function actionLogin()
    {
        $model = new LoginForm();
        $request = \Yii::$app->request;

        if ($request->isPost) {
            $model->load($request->post());
            if ($model->login()) {
                //>>最后登录时间和IP
                $session = \Yii::$app->user;
                User::updateAll(['last_login_time' => date('Y-m-d', time()), 'last_login_ip' => $_SERVER['REMOTE_ADDR']], ['id' => $session->id]);
                //>>提示信息
                \Yii::$app->session->setFlash('success', '登录成功');
                //>>跳转
                return $this->redirect(['user/index']);
            }
        }
        return $this->render('login', ['model' => $model]);
    }

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
}