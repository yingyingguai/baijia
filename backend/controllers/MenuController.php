<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\Menu;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\Request;

/**
 * Class MenuController
 * @package backend\controllers
 *
 *   菜单增删改查
 *   完成路由 权限 的关联
 */
class MenuController extends Controller
{
    //1.添加菜单
    public function actionAdd()
    {
        $model = new Menu();
        $request = new Request();
        //获取路由
        $authManager = \Yii::$app->authManager;
        $options = ArrayHelper::map($authManager->getPermissions(), 'name', 'name');
        //获取上级菜单
        //parent_id = id ;
        $menu_options = ArrayHelper::map($model->getMenu(), 'id', 'label');
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
                $model->save();
                //提示加跳转
                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect(['menu/index']);
            }
        }
        return $this->render('add', ['model' => $model, 'options' => $options, 'menu_options' => $menu_options]);
    }

    //2.显示页面
    public function actionIndex()
    {
        $model = Menu::find()->All();
        return $this->render('index', ['model' => $model]);
    }

    //3.修改菜单
    public function actionEdit($id)
    {
        $model = Menu::findOne($id);
        $request = new Request();
        //获取路由
        $authManager = \Yii::$app->authManager;
        $options = ArrayHelper::map($authManager->getPermissions(), 'name', 'name');
        //获取上级菜单
        $menu_options = ArrayHelper::map($model->getMenu(), 'id', 'label');
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
                $model->save();
                //提示加跳转
                \Yii::$app->session->setFlash('success', '修改成功');
                return $this->redirect(['menu/index']);
            }
        }
        return $this->render('add', ['model' => $model, 'options' => $options, 'menu_options' => $menu_options]);
    }

    //4.删除菜单

    public function actionDelete($id)
    {
      $res =  Menu::findOne($id)->delete();
        echo json_encode($res);
        return $this->render('menu/index');

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