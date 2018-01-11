<?php

namespace frontend\controllers;

use backend\models\Goods;
use frontend\models\Address;
use frontend\models\Cart;
use frontend\models\Member;
use frontend\models\Order;
use frontend\models\OrderGoods;
use yii\db\Exception;
use yii\web\Controller;

class OrderController extends Controller
{
    public $enableCsrfValidation = false;

    //订单展示
    public function actionIndex()
    {
        if (\Yii::$app->user->isGuest) {
            \Yii::$app->session->setFlash('success', '未登录请登录');
            return $this->redirect(['user/login']);
        } else {
            //找到用户更具
            $member_id = \Yii::$app->user->identity->getId();
            $address = Address::find()->where(['member_id' => $member_id])->all();

            $rows = Order::$deliveries;
            $data = Order::$payments;
            $cart = Cart::find()->where(['member_id' => $member_id])->all();
            $amount = [];
            $goods_id = [];
            foreach ($cart as $v) {
                $goods_id[] = $v->goods_id;
                $amount[$v->goods_id] = $v->amount;
            }
            $goods = Goods::find()->where(['in', 'id', $goods_id])->all();
            return $this->render('index', ['address' => $address, 'rows' => $rows
                , 'datas' => $data, 'goods' => $goods, 'amount' => $amount]);
        }
    }

    //订单提交
    public function actionAddOrder()
    {
        //接收post数据
        $member_id = \Yii::$app->user->identity->getId();
        $data = \Yii::$app->request->post();
        // var_dump($data);die;
        $zje = isset($data['zje']) ? $data['zje'] : 0;
        $address_id = isset($data['address_id']) ? $data['address_id'] : 0;
        $pay = isset($data['pay']) ? $data['pay'] : 0;
        //总金额
        //判断是否有值
        if ($zje && $address_id && $pay) {
            $delivery = $data['delivery'];
            //获取商品的小计
            $total = unserialize($data['total']);
            //收货地址表数据
            $path = Address::findOne(['id' => $address_id]);
            //送货方式表数据
            $deliverys = Order::$deliveries[$delivery];
            //获取支付方式
            $pays = Order::$payments[$pay];
            //获取购物车的数据
            $cart = Cart::find()->where(['member_id' => $member_id])->all();
            $goods_id = [];
            //数量
            foreach ($cart as $v) {
                $goods_id[$v->goods_id] = $v->amount;
            }
            $ret = [];
            foreach ($cart as $a) {
                $ret[] = $a->goods_id;
            }
            //商品
            $model = new Order();
            //先将订单保存到订单表 没有金额
            $model->member_id = $member_id;
            //---------------地址开始------------
            $model->name = $path->name;
            $model->province = $path->province;
            $model->city = $path->city;
            $model->area = $path->area;
            $model->address = $path->address;
            $model->tel = $path->tel;
            //------------地址结束---------
            $model->delivery_id = $delivery;
            $model->delivery_name = $deliverys['name'];
            $model->delivery_price = $deliverys['price'];
            $model->payment_id = $pay;
            $model->payment_name = $pays['name'];
            $model->status = 1;
            $model->create_time = time();
            $model->save(false);


            //开启事物
            $transaction = \Yii::$app->db->beginTransaction();
            //遍历循环商品得到每一条的库存
            try {
                //获取商品数据
                $goods = Goods::find()->where(['in', 'id', $ret])->all();
                foreach ($goods as $stock) {
                    $carts = Cart::findOne(['goods_id' => $stock->id]);

                    if (($stock->stock) > ($carts->amount)) {
                        //$model = new Order();
                        $model1 = new OrderGoods();
                        //订单详情表数据
                        $model1->order_id = $model->id;
                        $model1->goods_id = $stock->id;
                        $model1->goods_name = $stock->name;
                        $model1->logo = $stock->logo;
                        $model1->price = $stock->shop_price;
                        $model1->amount = $goods_id[$stock->id];
                        $model1->total = $total[$stock->id];
                        $model1->save(false);
                        $stock->stock = $stock->stock - $model1->amount;
                        $stock->save(false);

                    } else {
                        throw new Exception('商品库存不足，请修改购物车商品数量');
                    }
                    //保存总金额
                    $model->total = $total[$stock->id];
                    $model->save(false);
                    //=============发送邮件================
                    $member =    Member::findOne($member_id);
                    $email=$member->email;
                   // var_dump($email);die;
                    $mail = \Yii::$app->mailer->compose();
                    $res = $mail->setFrom('13688072750@163.com')
                       // ->setTo('13688072750@163.com')
                        ->setTo($email)
                        ->setSubject('hello')
                        ->setHtmlBody('你好订单')
                        ->send();
                    if ($res){
                        echo'ok';
                    }


                    //=============发送邮件================
                }
                //清除购物车数据
                $gouwuche = Cart::find()->where(['member_id' => $member_id])->all();
                foreach ($gouwuche as $delete) {
                    $ramove_cart = Cart::findOne(['id' => $delete->id]);
                    $ramove_cart->delete();
                }
                $transaction->commit();

            } catch (Exception $e) {
                $transaction->rollBack();
                echo '库存不足,';
                die;
                //return $this->redirect(['user/cart']);
                //  throw new Exception($e->getMessage());

            }
            return $this->redirect(['order/success']);

        } else {
            return $this->redirect(['order/index']);
        }

    }

    //查看订单
    public function actionOrder()
    {
        //>>已登录用户所有订单
        $orders = Order::find()->where(['member_id' => \Yii::$app->user->id])->all();
        foreach ($orders as $order) {
            //>>查出每条订单的所有商品信息
            $goods = OrderGoods::find()->where(['order_id' => $order->id])->all();
            foreach ($goods as $good) {
                $order->logo = $good->logo;
            }
        }
        return $this->render('order', ['orders' => $orders]);
    }

    //删除订单 未完善
    public function actionDelete($id)
    {
        $model = Order::findOne(['id' => $id]);
        $model1 = OrderGoods::findOne(['order_id' => $id]);
        $model->delete();
        $model1->delete();

        return $this->redirect(['order/order']);
    }

    //订单提交页面
    public function actionSuccess()
    {
        return $this->render('success');
    }


    //>>清理过期未支付订单
    public function actionCleanOrder()
    {
        $orders = Order::find()->where(['status' => 1])->andWhere(['<', 'create_time', time() - 10])->all();
        //>>每条订单
        if ($orders) {
            foreach ($orders as $order) {
                $order->status = 0;
                $order->save(false);
                $goods = OrderGoods::find()->where(['order_id' => $order->id])->all();
                //>>每个订单的商品 返还库存
                foreach ($goods as $good) {
                    $detail = Goods::find()->where(['id' => $good->goods_id])->one();
                    $detail->stock += $good->amount;
                    $detail->save(false);
                }
            }
        }
    }

    //>>解决超卖
    public function actionOverTop()
    {
        /**
         *  $redis = new \Redis();
         *   $redis->connect('127.0.0.1');
         *   事先将活动商品的库存和id存入redis
         *   在订单保存之后
         *   $order->save()
         *   遍历提交订单用户的购物车中的商品信息并根据商品id在redis中减去
         *    $res=$redis->decrBy('stock_'.$cart->goods_id,$cart->amount)
         *    在redis中用哈希类型保存id=>count 以便redis中商品数量不够回滚
         *      $redis->hSet('reduce_'.$order->id,$cart->goods_id,$cart->amount);
         *      if($res<0){
         *商品不足抛出异常
         * }else{
         *提交订单  保存订单数据
         * }
         * 异常处理
         *  catch{
         *      捕获到异常->redis中商品数量不足->回滚
         *      获取redis里该订单的商品id和数量
         *      $reduces = $redis->hGetAll('reduce_'.$order->id)
         *
         *      foreach($reduces as $id=>$num){
         *              $redis->incrBy('stock_'.$id,$num)
         *      }
         * }
         */
    }

    /**
     * 邮件发送
     */
/*    public function actionEmail($email, $title, $content)
    {

        $mail = \Yii::$app->mailer->compose();
        $res = $mail->setFrom('13688072750@163.com')
            ->setTo($email)
            ->setSubject($title)
            ->setHtmlBody($content)
            ->send();
    }*/
    public function actionEmail()
    {
        $mail = \Yii::$app->mailer->compose();
        $res = $mail->setFrom('13688072750@163.com')
            ->setTo('13688072750@163.com')
            ->setSubject('hello')
            ->setHtmlBody('你好')
            ->send();
        if ($res){
            echo'ok';
        }
    }

    /*
     *
     *
     *  a 将name= “张三”保存到redis，并设置30秒后过期。（写PHP代码）
     *  b 将age=  18 保存到redis（写PHP代码）
     *  c 检测name是否已过期，如果过期age减1，否则age加1（写PHP代码）
      */
    public function actionTest()
    {
        $redis = new \Redis();
        $redis->connect('127.0.0.1');
        $redis->set('name', '张三', 10);
        $redis->set('age', '18');
        echo $redis->get('name');
        echo $redis->get('age');

    }

    public function actionCheck()
    {
        $redis = new \Redis();
        $redis->connect('127.0.0.1');
        $name = $redis->get('name');
        $age = $redis->get('age');
        if ($name) {
            $age += 1;
        } else {
            $age -= 1;
        }
        echo $age;
    }


}