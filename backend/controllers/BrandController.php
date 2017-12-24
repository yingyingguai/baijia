<?php
namespace backend\controllers;
use backend\models\Brand;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\Request;
use yii\web\UploadedFile;

// 引入鉴权类
use Qiniu\Auth;
// 引入上传类
use Qiniu\Storage\UploadManager;
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
            //  var_dump($request->post());die;
            $model->load($request->post());
            if (empty($model->logo)) {
                $model->logo = \Yii::getAlias('@web') . '/upload/brand/1.jpg';
            }
            if ($model->validate()) {
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
    //处理图片
    public function actionUpload()
    {
        $img = UploadedFile::getInstanceByName('file');
        $fileName = '/upload/brand/' . uniqid() . '.' . $img->extension;
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
    //修改
    public function actionEdit($id)
    {
        $model = Brand::findOne($id);

        $request = new Request();
        if ($request->isPost) {
            //>>>加载表单数据
            $model->load($request->post());

            if ($model->validate()) {
                //>>>保存
                $model->save();
                //>>设置提示信息
                \Yii::$app->session->setFlash('success', '修改成功!');
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