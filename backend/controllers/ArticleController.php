<?php

namespace backend\controllers;

use backend\models\Article;
use backend\models\ArticleCategory;
use backend\models\ArticleDetail;
use kucha\ueditor\UEditorAction;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Request;

class ArticleController extends Controller
{
   // public $enableCsrfValidation = false;
    //上传图片
    public function actions()
    {
        return [
            'upload' => [
                'class' => UEditorAction::className(),
                'config' => [
                    //上传图片配置
                    'imageUrlPrefix' => "", /* 图片访问路径前缀 */
                    'imagePathFormat' => "/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}", /* 上传保存路径,可以自定义保存路径和文件名格式 */
                    "imageRoot" => \Yii::getAlias("@webroot"),
                ]
            ]
        ];
    }
    //1.显示首页
    public function actionIndex()
    {

        $articles = Article::find()->where(['>=', 'status', 0])->all();
        //调用视图
        return $this->render("index", ['articles' => $articles]);
    }
    //添加
    public function actionAdd()
    {
        //1.显示添加页面
        $detail = new ArticleDetail();
        $request = new Request();
        $model = new Article();
        $category = ArticleCategory::find()->all();
        $options = ArrayHelper::map($category, 'id', 'name');
        if ($request->isPost) {
            $model->load($request->post());
            //后台验证
            if ($model->validate()) {
                $model->create_time = time();
                $model->save();
                $detail->content = $model->content;
                $detail->A_id=$model->id;
                $detail->save();
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
    //修改
    public function actionEdit($id)
    {
        $request = new Request();
        $model = Article::findOne($id);
        //>>>详情页
        //根据article的id = 文章详情的A_id
        $detail = ArticleDetail::find()->where(['A_id'=>$id])->One();
        $model->content = $detail->content;
        $category = ArticleCategory::find()->all();
        $options = ArrayHelper::map($category, 'id', 'name');
        if ($request->isPost) {
            $model->load($request->post());
            //后台验证
            if ($model->validate()) {
                $detail->content = $model->content;
                $model->save();
                $detail->save();
                //提示信息 跳转
                \Yii::$app->session->setFlash('success', '修改成功!');
                //跳转到首页
                return $this->redirect(['article/index']);
            } else {
                //打印错误信息
                var_dump($model->getErrors());
            }
        }
        return $this->render('add', ['model' => $model, 'options' => $options, 'detail' => $detail]);
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