<?php

namespace backend\controllers;


use backend\models\Brand;
use yii\web\Controller;
use yii\web\Request;
use yii\web\UploadedFile;

class  BrandController extends Controller
{
    public $enableCsrfValidation = false;

    //1.显示页面
    public function actionIndex()
    {
        $brands = Brand::find()->where(['>=', 'status', 0])->all();
        return $this->render('index', ['brands' => $brands]);
    }

    //添加
    public function actionAdd()
    {
        $model = new Brand();
        $request = new Request();
        if ($request->isPost) {
            //>>>加载表单数据
            $model->load($request->post());
            //处理图片
            $model->imgFile = UploadedFile::getInstance($model, 'imgFile');
            //如果为空 给一张默认的
//            if (empty($model->imgFile)){
//
//            }
            if ($model->validate()) {
                //>>>处理图片
                $file = '/upload/brand/' . uniqid() . '.' . $model->imgFile->extension;
//                var_dump($file);die;
                if ($model->imgFile->saveAs(\Yii::getAlias('@webroot') . $file)) {
                    //上传成功 保存到数据库
                    $model->logo = $file;
                }
                //>>>保存
                $model->save(false);
                //>>设置提示信息
                \Yii::$app->session->setFlash('success', '添加成功!');
                //>>跳转回首页
                return $this->redirect(['brand/index']);
            } else {
                //>>验证失败后 打印错误信息
                var_dump($model->getErrors());
            }
        }
        //1.显示添加表单
        return $this->render('add', ['model' => $model]);
    }

    //修改
    public function actionEdit($id)
    {
        $model = Brand::findOne($id);
        $request = new Request();
        if ($request->isPost) {
            //>>>加载表单数据
            $model->load($request->post());
            //处理图片
            $model->imgFile = UploadedFile::getInstance($model, 'imgFile');
            //  var_dump($model->imgFile);die;

            if ($model->validate()) {
                //>>>处理图片
                if ($model->imgFile) {
                    $file = '/upload/brand/' . uniqid() . '.' . $model->imgFile->extension;

//                var_dump($file);die;
                    if ($model->imgFile->saveAs(\Yii::getAlias('@webroot') . $file)) {
                        //上传成功 保存到数据库
                        $model->logo = $file;
                    }
                }
                //>>>保存
                $model->save(false);
                //>>设置提示信息
                \Yii::$app->session->setFlash('success', '添加成功!');
                //>>跳转回首页
                return $this->redirect(['brand/index']);
            } else {
                //>>验证失败后 打印错误信息
                var_dump($model->getErrors());
            }
        }
        //1.显示添加表单
        return $this->render('add', ['model' => $model]);
    }

    //删除
    public function actionDelete($id)
    {

        //ajax删除
        //点击删除 状态为-1 都不显示 数据库还有

        $row = Brand::findOne(['id' => $id]);
        $row->status = -1;
        $res = $row->save(false);
       // var_dump($res);die;
        echo $res;

    }
}