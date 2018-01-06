<?php

use yii\db\Migration;

/**
 * Class m171203_085639_create_test
 */
class m171203_085639_create_test extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m171203_085639_create_test cannot be reverted.\n";

        return false;
    }

    
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable('{{%test}}', [
            'name' => $this->string(64)->notNull(),
            'data' => $this->text(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);
    }

    public function down()
    {
        echo "m171203_085639_create_test cannot be reverted.\n";

        return false;
    }
    
}
