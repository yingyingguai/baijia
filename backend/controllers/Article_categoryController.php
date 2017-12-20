<?php

namespace backend\controllers;

use backend\models\Article_category;
use yii\web\Controller;
use yii\web\Request;

class Article_categoryController extends Controller
{

    //1.首页
    public function actionIndex()
    {
        $categorys = Article_category::find()->All();
        return $this->render('index', ['categorys' => $categorys]);
    }

    //添加
    public function actionAdd()
    {
        $model = new Article_category();
        $request = new Request();
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
                $model->save();
                //提示信息 跳转

                \Yii::$app->session->setFlash('success', '添加成功!');
                //>>跳转回首页
                return $this->redirect(['article_category/index']);
            } else {
                var_dump($model->getErrors());
            }
        }
        return $this->render('add', ['model' => $model]);
    }

    //修改
    public function actionEdit($id)
    {
        $model = Article_category::findOne($id);
        $request = new Request();
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
                $model->save();
                //提示信息 跳转

                \Yii::$app->session->setFlash('success', '修改成功!');
                //>>跳转回首页
                return $this->redirect(['article_category/index']);
            } else {
                var_dump($model->getErrors());
            }
        }
        return $this->render('add', ['model' => $model]);
    }
    //删除

    public function actionDelete($id)
    {

        //ajax删除
        //点击删除 状态为-1 都不显示 数据库还有

        $row = Article_category::findOne(['id' => $id]);
        $row->status = -1;
        $res = $row->save(false);
        // var_dump($res);die;
        echo $res;

    }
}