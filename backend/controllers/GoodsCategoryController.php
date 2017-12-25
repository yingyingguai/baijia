<?php

namespace backend\controllers;

use backend\models\GoodsCategory;
use yii\web\Controller;
use yii\web\Request;

class GoodsCategoryController extends Controller
{

    //1.添加
    public function actionAdd()
    {
        $model = new GoodsCategory();
        $request = new Request();
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
                //有父节点 就创建子节点
                if ($model->parent_id) {
                    //创建子节点
                    $parent = GoodsCategory::findOne(['id' => $model->parent_id]);
                    $model->appendTo($parent);
                } else {
                    //创建根节点
                    $model->makeRoot();
                }
                $model->save();
                //提示信息 跳转
                \Yii::$app->session->setFlash('success', '添加成功!');
                //>>跳转回首页
                return $this->redirect(['goods-category/index']);
            } else {
                var_dump($model->getErrors());
            }
        }
        return $this->render('add', ['model' => $model]);
    }
    //测试
    public function  actionZtree(){
        return $this->renderPartial('ztree');
    }
    //显示
    public function actionIndex(){
        $model= GoodsCategory::find()->all();
        return $this->render('index',['models'=>$model]);
    }
    //修改
    public function actionEdit($id){
        $model =GoodsCategory::findOne($id);
        $request = new Request();
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
                //有父节点 就创建子节点
                if ($model->parent_id) {
                    //创建子节点
                    $parent = GoodsCategory::findOne(['id' => $model->parent_id]);
                    $model->appendTo($parent);
                } else {
                    if ($model->getOldAttribute('parent_id')){
                        //创建根节点
                        $model->makeRoot();
                    }else{
                        //没有修改原来的
                        $model->save();
                    }
                }
                $model->save();
                //提示信息 跳转
                \Yii::$app->session->setFlash('success', '修改成功!');
                //>>跳转回首页
                return $this->redirect(['goods-category/index']);
            } else {
                var_dump($model->getErrors());
            }
        }
        return $this->render('add', ['model' => $model]);
    }
    //删除
    public function actionDelete($id){

    }
}