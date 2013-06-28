<?php

/**
 * Description of SensePeopleCommand
 *
 * @author candasm
 */
class SensePeopleCommand extends CConsoleCommand {
    
    public $migrationPath='ext.sensepeople.migrations';
    
    /**
     * sense people kurulumu için migration işlemini yapar.
     */
    public function actionIndex() {
        echo 'Do you want to install sensepeople apply[yes/no]:';
        exec(Yii::app()->getBasePath().'/yiic migrate up --migrationPath='.$this->migrationPath);
    }
}

