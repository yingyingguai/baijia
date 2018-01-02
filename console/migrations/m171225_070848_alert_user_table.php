<?php

use yii\db\Migration;

class m171225_070848_alert_user_table extends Migration
{
    public function up()
    {
        $this->addColumn('user','last_login_time','integer');
        $this->addColumn('user','last_login_ip','integer');
        $this->addColumn('user','status','integer');
    }

    public function down()
    {
        echo "m171225_070848_alert_user_table cannot be reverted.\n";

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
