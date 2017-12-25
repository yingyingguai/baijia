<?php

namespace backend\controllers;

use backend\models\Brand;
use backend\models\Gallery;
use backend\models\Goods;
use backend\models\GoodsDay;
use backend\models\GoodsIntro;
use kucha\ueditor\UEditorAction;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\Request;
use yii\web\UploadedFile;

class  GoodsController extends Controller
{

    public $enableCsrfValidation = false;

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

    //处理logo图片
    public function actionUploads()
    {
        $img = UploadedFile::getInstanceByName('file');
        $fileName = '/upload/goods/' . uniqid() . '.' . $img->extension;
        if ($img->saveAs(\Yii::getAlias('@webroot') . $fileName, 0)) {
            //上传成功 回显
            //=========================七牛云==============================
            // 需要填写你的 Access Key 和 Secret Key
            $accessKey = "JRnaFMeKgmPI-CBmAvyLq9OcGkgKtHp3MUBjlSvj";
            $secretKey = "LOinqOoJWzEsmXxwDhauZo9aAD9udFqGydiguiV0";
            $bucket = "mine";//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
            //域名!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
            $domian = 'p1cemq4je.bkt.clouddn.com';
            // 构建鉴权对象
            $auth = new Auth($accessKey, $secretKey);
            // 生成上传 Token
            $token = $auth->uploadToken($bucket);
            // 要上传文件的本地路径
            $filePath = \Yii::getAlias('@webroot') . $fileName;
            // 上传到七牛后保存的文件名
            $key = $fileName;
            //上传过后地址
            $url = "http://{$domian}/{$key}";
            // 初始化 UploadManager 对象并进行文件的上传。
            $uploadMgr = new UploadManager();
            // 调用 UploadManager 的 putFile 方法进行文件的上传。
            list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
            if ($err !== null) {
                return Json::encode(['error' => 1]);
            } else {
                //上传成功
                $url = "http://{$domian}/{$key}";
                return Json::encode(['url' => $url]);
            }
            //=========================七牛云==============================
        }
    }
    //处理相册图片
    public function actionUpload1()
    {
        $id=$_GET['id'];
        $img = UploadedFile::getInstanceByName('file');
        $fileName = '/upload/goods/gallery/' . uniqid() . '.' . $img->extension;
        if ($img->saveAs(\Yii::getAlias('@webroot') . $fileName, 0)) {
            //上传成功 回显
            //=========================七牛云==============================
            // 需要填写你的 Access Key 和 Secret Key
            $accessKey = "JRnaFMeKgmPI-CBmAvyLq9OcGkgKtHp3MUBjlSvj";
            $secretKey = "LOinqOoJWzEsmXxwDhauZo9aAD9udFqGydiguiV0";
            $bucket = "mine";//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
            //域名!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
            $domian = 'p1cemq4je.bkt.clouddn.com';
            // 构建鉴权对象
            $auth = new Auth($accessKey, $secretKey);
            // 生成上传 Token
            $token = $auth->uploadToken($bucket);
            // 要上传文件的本地路径
            $filePath = \Yii::getAlias('@webroot') . $fileName;
            // 上传到七牛后保存的文件名
            $key = $fileName;
            //上传过后地址
            $url = "http://{$domian}/{$key}";
            // 初始化 UploadManager 对象并进行文件的上传。
            $uploadMgr = new UploadManager();
            // 调用 UploadManager 的 putFile 方法进行文件的上传。
            list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
            if ($err !== null) {
                return Json::encode(['error' => 1]);
            } else {
                //上传成功
                //上传成功 保存到数据库
                $gallery = new Gallery();
                $gallery->goods_id=$id;
                $gallery->path = $url;

                $gallery->save();
                $gid = \Yii::$app->db->getLastInsertID();
                return Json::encode(['url' => $url,'gid'=>$gid]);

            }
            //=========================七牛云==============================
        }
    }





    //1.显示
    public function actionIndex()
    {
        $sn = \Yii::$app->request->get('sn')?\Yii::$app->request->get('sn'):'';
        $shop_price = \Yii::$app->request->get('shop_price')?\Yii::$app->request->get('shop_price'):0;
        $keyword = \Yii::$app->request->get('keyword')?\Yii::$app->request->get('keyword'):0;


        $query = Goods::find();
        if ($sn) {
            $query->andwhere(['like', 'sn', $sn]);
        }  if ($shop_price) {
            $query->andwhere(['like', 'shop_price', $shop_price]);
        }  if ($keyword) {
            $query->andwhere(['like', 'name', $keyword]);
        }
        $pager = new Pagination([
            'totalCount' => $query->count(),
            'defaultPageSize' => 2
        ]);
        $goods = $query->limit($pager->limit)->offset($pager->offset)->all();
        //调用视图
        return $this->render("index", ['goods' => $goods, 'pager' => $pager]);
    }

    //添加
    public function actionAdd()
    {
        //1.显示添加页面
        $intro = new GoodsIntro();
        $request = new Request();
        $model = new Goods();

        $goods_brand = Brand::find()->all();
        $brands = ArrayHelper::map($goods_brand, 'id', 'name');
        // var_dump($brands);die;
        if ($request->isPost) {
            $model->load($request->post());
            // var_dump($request->post());die;
            //后台验证
            if ($model->validate()) {
                //------------------自动生成货号------------------
                //2017122500001 补0五位 !!!!!!!!!!!!!!!!!!!!
                $day = date('Y-m-d');
                $goodsCount = GoodsDay::findOne(['day' => $day]);
                if ($goodsCount == null) {
                    $goodsCount = new GoodsDay();
                    $goodsCount->day = $day;
                    $goodsCount->count = 1;
                    $goodsCount->save();
                } else {
                    $goodsCount->count += 1;
                    $goodsCount->save();
                }
                $model->sn = date('Ymd') . sprintf("%04d", $goodsCount->count + 1);
                //------------------自动生成货号------------------
                $model->create_time = time();
                $model->save();

                //详情
                $intro->content = $model->content;
                $intro->G_id = $model->id;
                $intro->save();
                //提示信息 跳转
                \Yii::$app->session->setFlash('success', '添加成功!');
                //跳转到首页
                return $this->redirect(['goods/index']);
            } else {
                //打印错误信息
                var_dump($model->getErrors());
            }
        }
        return $this->render('add', ['model' => $model, 'brands' => $brands]);
    }

    //修改
    public function actionEdit($id)
    {
        $request = new Request();
        $model = Goods::findOne($id);
        $goods_brand = Brand::find()->all();
        $brands = ArrayHelper::map($goods_brand, 'id', 'name');
        //>>>详情页
        $intro = GoodsIntro::find()->where(['G_id' => $id])->One();
        $model->content = $intro->content;

        if ($request->isPost) {
            $model->load($request->post());
            // var_dump($request->post());die;
            //后台验证
            if ($model->validate()) {
                $model->create_time = time();
                $model->save();
                //详情
                $intro->content = $model->content;
                $intro->G_id = $model->id;
                $intro->save();
                //提示信息 跳转
                \Yii::$app->session->setFlash('success', '修改成功!');
                //跳转到首页
                return $this->redirect(['goods/index']);
            } else {
                //打印错误信息
                var_dump($model->getErrors());
            }
        }
        return $this->render('add', ['model' => $model, 'brands' => $brands]);
    }

    //删除
    public function actionDelete($id)
    {

    }

    //商品相册
    public function actionGallery($id)
    {
        //1.显示页面
        $goods = Goods::findOne(['id' => $id]);
        return $this->render('gallery', ['goods' => $goods]);
    }

    //AJAX删除图片
    public function actionDelGallery()
    {
        $id = \Yii::$app->request->post('id');
        $model = Gallery::findOne(['id' => $id]);

    }
}