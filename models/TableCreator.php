<?php

namespace app\models;

use app\entities\DomainsAr;
use app\entities\SettingsAr;
use Yii;
use yii\db\mysql\Schema;

class TableCreator
{
    public static function execute()
    {
        if (null === Yii::$app->db->getTableSchema(SettingsAr::tableName())) {
            Yii::$app->db->createCommand()->createTable(SettingsAr::tableName(), [
                'id'          => Yii::$app->db->getSchema()->createColumnSchemaBuilder(Schema::TYPE_PK),
                'companyName' => Yii::$app->db->getSchema()->createColumnSchemaBuilder(Schema::TYPE_STRING)->notNull(),
            ], 'DEFAULT CHARACTER SET=utf8 DEFAULT COLLATE=utf8_general_ci ENGINE=InnoDB')->execute();

            Yii::$app->db->createCommand()->createTable(DomainsAr::tableName(), [
                'id'     => Yii::$app->db->getSchema()->createColumnSchemaBuilder(Schema::TYPE_PK),
                'domain' => Yii::$app->db->getSchema()->createColumnSchemaBuilder(Schema::TYPE_STRING)->notNull(),
                'data'   => 'JSON NOT NULL',
            ], 'DEFAULT CHARACTER SET=utf8 DEFAULT COLLATE=utf8_general_ci ENGINE=InnoDB')->execute();
        }
    }
}
