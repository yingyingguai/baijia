<?php
namespace frontend\controllers;
use backend\models\Goods;
use frontend\models\Address;
use frontend\models\Cart;
use frontend\models\Order;
use frontend\models\OrderGoods;
use yii\db\Exception;
use yii\web\Controller;

class OrderController extends Controller{

    public $enableCsrfValidation=false;

    public function actionIndex()
    {
        if(\Yii::$app->user->isGuest)
        {
            \Yii::$app->session->setFlash('success','未登录请登录');
            return $this->redirect(['user/login']);
        }else{
            //找到用户更具
            $member_id=\Yii::$app->user->identity->getId();
            $address=Address::find()->where(['member_id'=>$member_id])->all();

            $rows=Order::$deliveries;
            $data=Order::$payments;
            $cart=Cart::find()->where(['member_id'=>$member_id])->all();
            $amount=[];
            $goods_id=[];
            foreach ($cart as $v){
                $goods_id[]=$v->goods_id;
                $amount[$v->goods_id]=$v->amount;
            }
            $goods= Goods::find()->where(['in', 'id',$goods_id])->all();
            return $this->render('index',['address'=>$address,'rows'=>$rows
                ,'datas'=>$data,'goods'=>$goods,'amount'=>$amount]);
        }
    }
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
            foreach ($cart as $v) {
                $goods_id[$v->goods_id] = $v->amount;
            }
            $ret = [];
            foreach ($cart as $a) {
                $ret[] = $a->goods_id;
            }
            //获取商品数据
            $goods = Goods::find()->where(['in', 'id', $ret])->all();
            //实列表单模型


            //开启事物
            $transaction = \Yii::$app->db->beginTransaction();
            //遍历循环商品得到每一条的库存
            try {
                $model = new Order();
                $model->save(false);
                foreach ($goods as $stock) {
                    $carts = Cart::findOne(['goods_id' => $stock->id]);
                  //  var_dump($carts);die;
                    if (($stock->stock) > ($carts->amount)) {
                        $model = new Order();
                        $model1 = new OrderGoods();

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
                        $model->total = $total[$stock->id];
                        $model->status = 1;
                        $model->create_time = time();
//                        $model->save(false);
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
                }
                $gouwuche=Cart::find()->where(['member_id'=>$member_id])->all();
                foreach ($gouwuche as $delete){
                    $ramove_cart=Cart::findOne(['id'=>$delete->id]);
                    $ramove_cart->delete();
                }
                $transaction->commit();

            } catch (Exception $e) {
                $transaction->rollBack();
                echo '库存不足,';
                return $this->redirect(['user/cart']);
              //  throw new Exception($e->getMessage());

            }
            return $this->redirect(['order/success']);

        } else {
            return $this->redirect(['order/index']);
        }

    }







































    public function actionOrder(){
        $models=Order::find()->all();
        return $this->render('order',['models'=>$models]);
    }
    public function actionDelete($id){
        $model=Order::findOne(['id'=>$id]);
        $model1=OrderGoods::findOne(['order_id'=>$id]);
        $model->delete();
        $model1->delete();
        return $this->redirect(['order/order']);
    }
    public function actionSuccess(){
        return $this->render('success');
    }


}