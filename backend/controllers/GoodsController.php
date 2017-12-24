<?php

namespace backend\controllers;

use backend\models\Brand;
use backend\models\Goods;
use backend\models\GoodsIntro;
use kucha\ueditor\UEditorAction;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
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
    //处理图片
    public function actionUpload()
    {
        $img = UploadedFile::getInstanceByName('file');
        $fileName = '/upload/goods/' . uniqid() . '.' . $img->extension;
       // var_dump($fileName);die;
        if ($img->saveAs(\Yii::getAlias('@webroot') . $fileName, 0)) {
            //上传成功 回显
            //=========================七牛云==============================
            // 需要填写你的 Access Key 和 Secret Key
            $accessKey ="JRnaFMeKgmPI-CBmAvyLq9OcGkgKtHp3MUBjlSvj";
            $secretKey = "LOinqOoJWzEsmXxwDhauZo9aAD9udFqGydiguiV0";
            $bucket = "mine";//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
            //域名!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
            $domian = 'p1cemq4je.bkt.clouddn.com';
            // 构建鉴权对象
            $auth = new Auth($accessKey, $secretKey);
            // 生成上传 Token
            $token = $auth->uploadToken($bucket);
            // 要上传文件的本地路径
            $filePath = \Yii::getAlias('@webroot').$fileName;
            // 上传到七牛后保存的文件名
            $key = $fileName;
            // 初始化 UploadManager 对象并进行文件的上传。
            $uploadMgr = new UploadManager();
            // 调用 UploadManager 的 putFile 方法进行文件的上传。
            list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
            echo "\n====> putFile result: \n";
            if ($err !== null) {
                //错误
                //  var_dump($err);
                return Json::encode(['error' => 1]);
            } else {
                //上传成功
                //  var_dump($ret);
                $url = "http://{$domian}/{$key}";
            }
            //=========================七牛云==============================
            return Json::encode(['url' => $url]);
        }else{
            return Json::encode(['error' => 1]);
        }
    }
    //1.显示
    public function actionIndex()
    {

        //1.模型实例化 调用视图

        $goods = Goods::find()->All();
        return $this->render('index', ['goods' => $goods]);
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
        $intro = GoodsIntro::find()->where(['G_id'=>$id])->One();
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
        return $this->render('add', ['model' => $model,  'brands' => $brands]);
    }
    //删除
    public function  actionDelete($id){

    }

    //相册
    public function  actionGallery($id){

    }
}