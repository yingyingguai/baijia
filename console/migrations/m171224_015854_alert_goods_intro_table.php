<?php

use yii\db\Migration;

class m171224_015854_alert_goods_intro_table extends Migration
{
    public function up()
    {
        $this->addColumn('goods_intro','G_id','integer');
    }

    public function down()
    {
        echo "m171224_015854_alert_goods_intro_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
