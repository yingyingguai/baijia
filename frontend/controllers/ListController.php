<?php

namespace frontend\controllers;

use backend\models\Brand;
use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use common\models\SphinxClient;
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

      //  Goods::updateAllCounters(['view_time'=>1],['id'=>$id]);

        //使用redis，处理商品浏览数
        //前提 需要将商品浏览数保存到redis
        $redis = new \Redis();
        $redis->connect('127.0.0.1');
        $times = $redis->incr('times_'.$id);
        //同步  一般每天 （每周 每月）3-5点 将redis中的浏览次数写回商品表
        //编辑计划任务
        $row->view_time = $times;

        return $this->render('list',['row'=>$row,'intro'=>$intro,'gallerys'=>$gallerys]);  }

    //搜索功能  将搜索商品 展示在index页面
    public function actionSearch($name){
        //分词搜索

        $cl = new SphinxClient();
        $cl->SetServer('127.0.0.1', 9312);
        $cl->SetConnectTimeout(10);
        $cl->SetArrayResult(true);

        $cl->SetMatchMode(SPH_MATCH_EXTENDED2);
        $cl->SetLimits(0, 1000);
        $info = $name; //关键字
        $res = $cl->Query($info, 'mysql');//索引
        //找到搜索id
        $ids=[];
        if (isset($res['matches'])){
            foreach ( $res['matches'] as $val) {
                $ids[]=$val['id'];
            }
        }

       // var_dump($ids);
            //找到搜索条数
        $count = Goods::find()->where(['in','id',$ids])->count();
        //分页的
        $pager = new Pagination([
            'pageSize' => 2,
            'totalCount' => $count,
        ]);

        $rows = Goods::find()
            ->where(['in', 'id', $ids])
            ->limit($pager->limit)
            ->offset($pager->offset)
            ->all();
        //1.视图页面
    return  $this->render('index', ['rows' => $rows, 'pager' => $pager]);
    }



}