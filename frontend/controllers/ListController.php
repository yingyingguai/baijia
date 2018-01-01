<?php
namespace frontend\controllers;
use backend\models\Brand;
use backend\models\Goods;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use yii\web\Controller;

class ListController extends Controller{

    //三级商品
    public function actionIndex($id){

        $rows =  Goods::find()->where(['goods_category_id'=>$id])->all();
     //   $categorys = GoodsGallery::find()->where(['parent_id'=>0])->all();
//        $children=[];
//        //顶级分类id
//        foreach($categorys as $category){
//            $child = GoodsGallery::find()->where(['parent_id'=>$category->id])->all();
//            //二级分类
//            $children[$category->id]= $child;
//            foreach ($child as $kid){
//                //  $three=[];
//                $kids =GoodsGallery::find()->where(['parent_id'=>$kid->id])->all();
//                //3级分类
//                $threes[$kid->id]=$kids;
//            }
//        }

        return $this->render('index',['rows'=>$rows/*,'categorys'=>$categorys,'children'=>$children,'threes'=>$threes*/]);
    }

    //商品详情
    public function actionShow($id){
        //获取商品
        $goods=Goods::findOne($id);
        //商品简介
        $intro = GoodsIntro::find()->where(['goods_id'=>$id])->one();
            //商品图片
        $gallerys = GoodsGallery::find()->where(['goods_id'=>$id])->all();
        //品牌
        $row = Goods::find()->where(['id'=>$id])->one();
        $brand = Brand::find()->where(['id'=>$row->brand_id])->one();

        $row->brand_id = $brand->name;
        return $this->render('list',['goods'=>$goods,'row'=>$row,'intro'=>$intro,'gallerys'=>$gallerys]);
    }

}