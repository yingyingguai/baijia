<?php

namespace frontend\controllers;

use backend\models\Brand;
use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

class ListController extends Controller
{

    //商品列表
    public function actionIndex($id)
    {
        //1.三级分类
        $cate = GoodsCategory::findOne($id);
        if ($cate->depth == 2) {
            $ids = [$id];
        } //2.一 .二级分类
        else {
            $categorys = $cate->children()->select('id')->andWhere(['depth' => 2])->asArray()->all();
            $ids = ArrayHelper::map($categorys, 'id', 'id');
        }
        $query = Goods::find()->where(['in', 'goods_category_id', $ids]);

        $pager = new Pagination([
            'totalCount' => $query->count(),
            'defaultPageSize' => 3
        ]);
        $rows = $query->limit($pager->limit)->offset($pager->offset)->all();
        //var_dump($pager);die;
        return $this->render('index', ['rows' => $rows,'pager'=>$pager]);
    }

    //商品详情
    public function actionShow($id)
    {
        //获取商品
        $goods = Goods::findOne($id);
        //商品简介
        $intro = GoodsIntro::find()->where(['goods_id' => $id])->one();
        //商品图片

        $gallerys = GoodsGallery::find()->select(['path'])->where(['goods_id' => $id])->all();

        //   var_dump($gallerys);die;
        //品牌
        $row = Goods::find()->where(['id' => $id])->one();
        $brand = Brand::find()->where(['id' => $row->brand_id])->one();
        //找到第一张
        $one = $gallerys[0]->path;
//var_dump($one);die;
        //删除第一张
        array_shift($gallerys);

        $row->brand_id = $brand->name;
        return $this->render('list', ['one' => $one, 'goods' => $goods, 'row' => $row, 'intro' => $intro, 'gallerys' => $gallerys]);
    }



}