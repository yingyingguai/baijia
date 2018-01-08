<?php
namespace frontend\controllers;
use backend\models\Goods;
use frontend\models\Address;
use frontend\models\Cart;
use frontend\models\LoginForm;
use frontend\models\Member;
use yii\captcha\CaptchaAction;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\Cookie;
use yii\web\Request;


class UserController extends Controller
{
    //必须写这个 ,不然400错误
    public $enableCsrfValidation = false;

    //验证用户名是否存在
    public function actionCheckUsername($username)
    {
        $res = Member::find()->where(['username' => $username])->one();
        if ($res) {
            echo 'false'; //输出bool值
        } else {
            echo 'true';
        }
    }

    //用户注册
    public function actionRegist()
    {
        $request = \Yii::$app->request;
        $model = new Member();
        if($request->isPost){
            $model->load($request->post(),"");
           // var_dump($request->post());die;
      // var_dump($model->validate());die;
            if($model->validate()){
                $model->password_hash = \Yii::$app->security->generatePasswordHash($model->password_hash);

                $model->status = 1;
             //   $model->create_at =time();
                $model->save(false);
                echo "注册成功";
                sleep(1);
                return $this->redirect(['user/login']);
            }
        }
        return $this->render('regist');
    }

    //验证码
    public function actions()
{
    return [
        'captcha' => [
            'class' => CaptchaAction::className(),
            'height' => 34,
            'minLength' =>3,
            'maxLength' =>3,
        ]
    ];
}

    //登录
    public function actionLogin()
    {
        $request = new Request();
        $model = new LoginForm();
        if ($request->isPost) {
            $model->load($request->post(), '');
            if ($model->validate()) {
                if ($model->login()) {
                    //登录成功
                    $user = Member::find()->where(['username' => $model->username])->one();
                    $user->last_login_time = time();
                    $user->last_login_ip = $_SERVER['REMOTE_ADDR']; //记录ip
                    $user->save(false);


                    //>>已登陆,将cookie信息存入数据表
                    $cookies = \Yii::$app->request->cookies;
                    //>>如果cookie里有数据
                    if ($cookies->has('cart')) {
                        //echo 1;die;
                        $cart_info = unserialize($cookies->getValue('cart'));
                        $good_ids = array_keys($cart_info);
                        //>>获得cookie每个商品id
                        foreach ($good_ids as $good_id) {//1
                            $count=Cart::find()->where(['goods_id'=>$good_id])->andWhere(['member_id'=>\Yii::$app->user->identity->id])->one();
                            //>>数据表中该用户购物车有这个商品id就执行数量添加操作
                            if($count){
                                //>>有这个商品
                                $count->amount+=$cart_info[$good_id];
                                $count->save(false);
                            }else{
                                $cart = new Cart();
                                $cart->member_id = \Yii::$app->user->identity->id;
                                $cart->goods_id = $good_id;
                                $cart->amount = $cart_info[$good_id];
                                $cart->save(false);
                            }
                        }
                        $cookies = \Yii::$app->response->cookies;
                        $cookies->remove('cart');
                    }

                    echo '登录成功';
                    sleep(1);
                    return $this->redirect(['site/index']);
                }
            }
        }


        return $this->render('login');
    }


    //注销
    public function actionLogout()
    {
        \Yii::$app->user->logout();
        return $this->redirect(['user/login']);
    }

    //收货地址
    public function actionAddress()
    {
        $model = new Address();
        $request = new Request();
        if ($request->isPost) {
            //+++++++++++++++ '' '' '' ''   记住  这是一般表单  没有样式
            $model->load($request->post(), '');
            if ($model->validate()) {
                //得到member_id
                $model->member_id = \Yii::$app->user->id;
                if ($model->status) {
                    $model->status = 1;
                } else {
                    $model->status = 0;
                }
                $model->save(false);
                return $this->redirect('address');
            } else {
                //验证失败，提示错误信息
                return Json::encode(['status' => false, 'msg' => $model->getErrors()]);
            }
        }
        return $this->renderPartial('address');
    }

    //回显
    public function actionAddressEdit($id)
    {
        $row = Address::findOne(['id' => $id]);
        $request = new Request();
        if ($request->isPost) {
            $row->load($request->post());
            if ($row->validate()) {
                if ($row->status) {
                    $row->status = 1;
                } else {
                    $row->status = 0;
                }
                $row->save();
                return $this->redirect('address');
            } else {
                //验证失败，提示错误信息
                return Json::encode(['status' => false, 'msg' => $row->getErrors()]);
            }

        }
        return $this->render('edit_address', ['row' => $row]);
    }

    //删除
    public function actionAddressDel($id)
    {
        $model = Address::findOne(['id' => $id]);
        $model->delete();
        return $this->redirect(['user/address']);
    }

    //>>设置默认地址
    public function actionAddrDefault($id)
    {
        $addresses = Address::find()->where(['member_id' => \Yii::$app->user->identity->id])->all();
        //>>状态全部清0
        foreach ($addresses as $address) {
            $address->status = 0;
            $address->save(false);
        }
        $addr = Address::find()->where(['id' => $id])->one();
        $addr->status = 1;
        $addr->save(false);
        return $this->redirect(['user/address']);
    }

    //阿里大于
    public function actionSms($phone)
    {
        //1.验证电话号码
        //  $regex = "^((13[0-9])|(14[5|7])|(15([0-3]|[5-9]))|(18[0,5-9]))\\d{8}$";
        $code = rand(10000, 99999);
        var_dump($phone);
  //    $result = \Yii::$app->sms->send($phone, ['code' => $code]);

        //测试短信验证
        $result = new \stdClass();
        $result->Code='OK';

        //var_dump($result);die;
        if ($result->Code == 'OK') {
            //短信发送成功 保存到redis
            $redis = new \Redis();
            $redis->connect('127.0.0.1');
            $redis->set('code' . $phone, $code, 30 * 60);
            //这接收到表单传过来的 手机验证码 然后与redis的验证码对比
            return 'true';
        } else {
            //发送失败
            return '短信发送失败后台';
        }
        // var_dump($result);
        /*            $params = array ();

        // *** 需用户填写部分 ***

        // fixme 必填: 请参阅 https://ak-console.aliyun.com/ 取得您的AK信息
        $accessKeyId = "LTAIJVSZahaVBnly";
        $accessKeySecret = "TRGwMQAefHBjm26qQJuLhBckaVieM2";

        // fixme 必填: 短信接收号码
//            $params["PhoneNumbers"] = "13688072750";
        $params["PhoneNumbers"] = "18215580752";

        // fixme 必填: 短信签名，应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
        $params["SignName"] = "源氏特产";

        // fixme 必填: 短信模板Code，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
        $params["TemplateCode"] = "SMS_120125269";

        // fixme 可选: 设置模板参数, 假如模板中存在变量需要替换则为必填项
        $params['TemplateParam'] = Array (
            "code" => rand(1000,9999),
           // "product" => "阿里通信"
        );

        // fixme 可选: 设置发送短信流水号
       //$params['OutId'] = "12345";

        // fixme 可选: 上行短信扩展码, 扩展码字段控制在7位或以下，无特殊需求用户请忽略此字段
        //$params['SmsUpExtendCode'] = "1234567";


        // *** 需用户填写部分结束, 以下代码若无必要无需更改 ***
        if(!empty($params["TemplateParam"]) && is_array($params["TemplateParam"])) {
            $params["TemplateParam"] = json_encode($params["TemplateParam"], JSON_UNESCAPED_UNICODE);
        }

        // 初始化SignatureHelper实例用于设置参数，签名以及发送请求
        $helper = new SignatureHelper();

        // 此处可能会抛出异常，注意catch
        $content = $helper->request(
            $accessKeyId,
            $accessKeySecret,
            "dysmsapi.aliyuncs.com",
            array_merge($params, array(
                "RegionId" => "cn-hangzhou",
                "Action" => "SendSms",
                "Version" => "2017-05-25",
            ))
        );

        var_dump($content) ;*/
    }

    //验证手机验证码
    public function actionCheckCaptchaTel($captcha, $tel)
    {
        //1.找到redis数据
        $redis = new  \Redis();
        $redis->connect('127.0.0.1');
        //   $redis->del();
        $code = $redis->get('code'.$tel);
      //  var_dump($code);
        if ($code) {
            //没过期 验证
            if ($code == $captcha) {
                return true;
            } else {
                return "true";
            }
        } else {
            return "false";
        }
    }

    //>>添加商品到购物车
    public function actionAddToCart($goods_id,$amount){
        if(\Yii::$app->user->isGuest){
            //>>未登陆 将购物车信息保存至cookie
            $cookies = \Yii::$app->request->cookies;
            //>>先读cookie.看商品是否存在
            if($cookies->has('cart')){
                //var_dump($cookies->getValue('cart'));die;
                $cart = unserialize($cookies->getValue('cart'));
            }else{
                $cart=[];
            }
            //>>判断商品存不存在,不存在就新增,存在就累加
            if(array_key_exists($goods_id,$cart)){
                $cart[$goods_id]+=$amount;
            }else{
                $cart[$goods_id]=$amount;
            }
            //>>写cookie
            $cookies = \Yii::$app->response->cookies;
            $cookie = new Cookie();
            $cookie->name = 'cart';
            $cookie->value =serialize($cart);
            //var_dump($cookie);die;
            $cookies->add($cookie);
        }else{
            //>>如果用户已经将这个商品选入购物车
            $count=Cart::find()->where(['goods_id'=>$goods_id])->andWhere(['member_id'=>\Yii::$app->user->identity->id])->one();
            if($count){
                $count->amount += $amount;
                $count->save(false);
            }else{
                //>>登陆后直接将购物车信息存入数据表
                $cart = new Cart();
                $cart->member_id=\Yii::$app->user->identity->id;
                $cart->goods_id = $goods_id;
                $cart->amount = $amount;
                $cart->save(false);
            }
        }
        return $this->redirect(['user/cart']);
    }
    //>>在结算页面显示购物车的商品
    public function actionCart(){
        if(\Yii::$app->user->isGuest){
            //>>未登录->购物车信息从cookie获取
            $cookies = \Yii::$app->request->cookies;
            if($cookies->has('cart')){
                // var_dump($cookies->getValue('cart'));die;
                $count = unserialize($cookies->getValue('cart'));
                $ids =array_keys($count);
                $goods = Goods::find()->where(['in','id',$ids])->all();
            }else{
                return $this->render('cart-error');
            }
        }else{
            //>>登陆后根据登陆用户id从数据库查表获取购物车信息
            $carts = Cart::find()->where(['member_id'=>\Yii::$app->user->identity->id])->all();
            $good_ids = [];
            //>>获取所有商品id
            $count=[];
            foreach($carts as $cart){
                $good_ids[]=$cart->goods_id;
                $count[$cart->goods_id]=$cart->amount;
            }
            //>>获取所有商品信息
            $goods = Goods::find()->where(['in','id',$good_ids])->all();
        }
        return $this->render('cart',['goods'=>$goods,'count'=>$count]);
    }
    //>>购物车商品删除
    /**
     * @param $id 商品id
     */
    public function actionCartDelete($id){
        if(\Yii::$app->user->isGuest){
            $cookies = \Yii::$app->request->cookies;
            if($cookies->has('cart')){
                //>>获取cookie   cart的值
                $arr = $cookies->getValue('cart');
                $arr = unserialize($arr);
                foreach($arr as $g_id=>$count){
                    if($g_id==$id){
                        unset($arr[$id]);
                    }
                }
                $cookies = \Yii::$app->response->cookies;
                $cookie = new Cookie();
                $cookie->name='cart';
                $cookie->value =serialize($arr);
                $cookies->add($cookie);
                return json_encode(true);
            }else{
                return json_encode(false);
            }
        }else{
            $good = Cart::find()->where(['goods_id'=>$id])->andWhere(['member_id'=>\Yii::$app->user->identity->id])->one();
            $res = $good->delete();
        }
        if($res){
            return json_encode(true);
        }else{
            return json_encode(false);
        }
    }
    //>>购物车商品数量修改
    public function actionCartEdit(){
        $g_id = \Yii::$app->request->post('g_id');
        $count=\Yii::$app->request->post('count');
        if(\Yii::$app->user->isGuest){
            $cookies = \Yii::$app->request->cookies;
            if($cookies->has('cart')){
                $arr = unserialize($cookies->getValue('cart'));
                foreach($arr as $id=>$c){
                    if($id == $g_id){
                        $arr[$g_id]=$count;
                    }
                }
                $cookies = \Yii::$app->response->cookies;
                $cookie = new Cookie();
                $cookie->name = 'cart';
                $cookie->value = serialize($arr);
                $cookies->add($cookie);
                return json_encode(true);
            }
        }else{
            $cart = Cart::find()->where(['goods_id'=>$g_id])->andWhere(['member_id'=>\Yii::$app->user->identity->id])->one();
            $cart->amount = $count;
            $cart->save(false);
            return json_encode(true);
        }
    }




}