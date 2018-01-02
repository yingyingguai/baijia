<?php
namespace frontend\controllers;

    use frontend\models\LoginForm;
    use frontend\models\Member;
    use yii\web\Controller;
    use yii\web\Request;

    class UserController extends Controller{
        //必须写这个 ,不然400错误
        public $enableCsrfValidation = false;

        //验证用户名是否存在
        public function actionCheckUsername($username){
            $res=Member::find()->where(['username'=>$username])->one();
            if($res){
                echo 'false'; //输出bool值
            }else{
                echo 'true';
            }
        }
        //用户注册
        public function actionRegist(){
            $request =new Request();
            $model = new Member();
            if($request->isPost){
                //load 第二个参数设置为 空
                $model->load($request->post(),"");
                if($model->validate()){
                    //密码加密
                    $model->password_hash = \Yii::$app->security->generatePasswordHash($model->password_hash);
                    //创建时间
                    $model->create_at=time();
                    //默认能使用
                    $model->status = 1;
                    $model->save(false);
                    echo "注册成功";
                    sleep(1);
                    //跳转到登录界面
                    return $this->redirect(['user/login']);
                }
            }
            return $this->render('regist');
        }
        //登录
        public function actionLogin(){
            $request = new Request();
            $model = new LoginForm();
            if($request->isPost){
                $model->load($request->post(),'');
                if($model->validate()){
                    if($model->login()){
                        //登录成功
                        $user = Member::find()->where(['username'=>$model->username])->one();
                        $user->last_login_time = time();
                        $user->last_login_ip=$_SERVER['REMOTE_ADDR']; //记录ip
                        $user->save(false);
                        echo '登录成功';
                        sleep(1);
                        return $this->redirect(['site/index']);
                    }
                }
            }
            return $this->render('login');
        }
        //>>注销
        public function actionLogout(){
            \Yii::$app->user->logout();
            return $this->redirect(['member/login']);
        }
    }