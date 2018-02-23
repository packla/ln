<?php

use app\entities\SettingsAr;
use yii\db\Migration;

/**
 * Class m171208_155535_create_settings
 */
class m171208_155535_create_settings extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable(SettingsAr::tableName(), [
            'id'              => $this->primaryKey(),
            'companyName'     => $this->string()->notNull(),
        ], 'DEFAULT CHARACTER SET=utf8 DEFAULT COLLATE=utf8_general_ci ENGINE=InnoDB');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        return $this->dropTable(SettingsAr::tableName());
    }
}
