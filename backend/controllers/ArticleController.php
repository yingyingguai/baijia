<?php

namespace backend\controllers;

use backend\models\Article;
use backend\models\Article_category;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Request;

class ArticleController extends Controller
{
    public $enableCsrfValidation = false;

    //1.显示首页
    public function actionIndex()
    {

        $articles = Article::find()->all();
        //调用视图
        return $this->render("index", ['articles' => $articles]);
    }

    //添加
    public function actionAdd()
    {
        //1.显示添加页面
        $request = new Request();
        $model = new Article();
        $category = Article_category::find()->all();
        $options = ArrayHelper::map($category, 'id', 'name');

        if ($request->isPost) {
            $model->load($request->post());
            //后台验证
            if ($model->validate()) {

                $model->create_time = time();
                $model->save();

                //提示信息 跳转
                \Yii::$app->session->setFlash('success', '添加成功!');
                //跳转到首页
                return $this->redirect(['article/index']);
            } else {
                //打印错误信息
                var_dump($model->getErrors());
            }
        }
            return $this->render('add', ['model' => $model, 'options' => $options]);

    }
    //添加
    public function actionEdit($id)
    {
        //1.显示添加页面
        $request = new Request();
        $model = Article::findOne($id);
        $category = Article_category::find()->all();
        $options = ArrayHelper::map($category, 'id', 'name');

        if ($request->isPost) {
            $model->load($request->post());
            //后台验证
            if ($model->validate()) {

                $model->create_time = time();
                $model->save();

                //提示信息 跳转
                \Yii::$app->session->setFlash('success', '修改成功!');
                //跳转到首页
                return $this->redirect(['article/index']);
            } else {
                //打印错误信息
                var_dump($model->getErrors());
            }
        }
            return $this->render('add', ['model' => $model, 'options' => $options]);

    }
    //删除
    public function actionDelete($id)
    {
        //ajax删除
        //点击删除 状态为-1 都不显示 数据库还有
        $row = Article::findOne(['id' => $id]);
        $row->status = -1;
        $res = $row->save(false);
        // var_dump($res);die;
        echo $res;
    }
}