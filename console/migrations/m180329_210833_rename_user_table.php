<?php

use yii\db\Migration;

/**
 * Class m180329_210833_rename_user_table
 */
class m180329_210833_rename_user_table extends Migration
{
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->renameTable('{{%user%}}', '{{%users%}}');
    }

    public function down()
    {
        $this->renameTable('{{%users%}}', '{{%user%}}');
    }
}
