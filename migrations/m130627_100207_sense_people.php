<?php

class m130627_100207_sense_people extends CDbMigration {

    private $tableName = 'sense_people_data_send';

    public function safeUp() {
        $this->createTable($this->tableName, array(
            'id' => 'pk',
            'request_url' => 'text NOT NULL',
            'post_data' => 'mediumtext NOT NULL',
            'response_data' => 'text NOT NULL',
            'create_time' => 'datetime DEFAULT "0000-00-00 00:00:00"'
                ), 'ENGINE=InnoDB DEFAULT CHARSET=utf8');
    }

    public function safeDown() {
        $this->dropTable($this->tableName);
    }

}