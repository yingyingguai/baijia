<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods_day`.
 */
class m171220_133707_create_goods_day_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('goods_day', [
            'id' => $this->primaryKey(),
            'day'=>$this->string()->comment('日期'),
            'count'=>$this->integer()->comment('商品数'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('goods_day');
    }
}
