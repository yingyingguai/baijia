<?php

use yii\db\Migration;

class m171221_074233_alter_article_detal_table extends Migration
{
    public function up()
    {
        $this->addColumn('article_detail','A_id','integer');
    }

    public function down()
    {
        echo "m171221_074233_alter_article_detal_table cannot be reverted.\n";

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
