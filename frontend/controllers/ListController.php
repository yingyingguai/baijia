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
    //必须写这个 ,不然400错误
    public $enableCsrfValidation = false;

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

        $intro = GoodsIntro::find()->where(['goods_id'=>$id])->one();
        $gallerys = GoodsGallery::find()->where(['goods_id'=>$id])->all();
        $row = Goods::find()->where(['id'=>$id])->one();
    //  var_dump($row);
        $brand = Brand::find()->where(['id'=>$row->brand_id])->asArray()->one();
     //  var_dump($brand);
        $row->brand_id = $brand['name'];
        Goods::updateAllCounters(['view_time'=>1],['id'=>$id]);

        return $this->render('list',['row'=>$row,'intro'=>$intro,'gallerys'=>$gallerys]);  }



}