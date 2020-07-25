<?php

use yii\db\Migration;

/**
 * Class m200419_104201_init_db
 */
class m200419_104201_init_db extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        ini_set("memory_limit", "1G");
        $sql = file_get_contents(Yii::getAlias("@app/database/basic.sql"));
//        $command = Yii::$app->db->createCommand($sql);
//        $command->execute();

        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200419_104201_init_db cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200419_104201_init_db cannot be reverted.\n";

        return false;
    }
    */
}
