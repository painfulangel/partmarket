<?php

/**
 * Description of OrderAction
 *
 * @author Nr Aziz
 */
class NestedSetOrderAction extends CAction {

    public $modelClass;
    public $pkName = 'id';
    public $parentIdName = 'parent_id';
    public $rootName = 'root';
    public $levelName = 'level';
    public $lftName = 'lft';
    public $subUpdateFunction = 'moveItems';
    public $setInitFunction = 'InitOrderFunc';

    public function run($pk, $name, $value, $move) {
        $model = CActiveRecord::model($this->modelClass)->findByPk($pk);
        $table = $model->tableName();

        if ($move === 'up') {
            $lftOp = '<=';
            $lftOrder = 'DESC';
        } else if ($move === 'down') {
            $lftOp = '>=';
            $lftOrder = 'ASC';
        }

        $db = Yii::app()->db;
        if ($model->{$this->levelName} == 1) {
            $temp_model = new $this->modelClass;
            $temp_model->{$this->setInitFunction}();
            $temp_model->saveNode();
            $temp_id = $temp_model->id;
            $temp_model->deleteNode();
            $sql = "SELECT $this->pkName FROM $table WHERE $this->pkName!=" . $model->{$this->pkName} . " and $this->levelName=" . $model->{$this->levelName} . " and $this->rootName $lftOp " . $model->{$this->rootName} . "  ORDER BY $this->rootName LIMIT 1";
            $compare_pk = $db->createCommand($sql)->queryScalar();
            if ($compare_pk != NULL)
                $compare_model = CActiveRecord::model($this->modelClass)->findByPk($compare_pk);
            else
                $compare_model = NULL;
            if ($compare_model != NULL) {

                $db->createCommand("UPDATE $table SET $this->rootName=$temp_id WHERE $this->rootName=" . $model->{$this->pkName} . "")->query();
                $db->createCommand("UPDATE $table SET  $this->parentIdName=$temp_id WHERE $this->parentIdName=" . $model->{$this->pkName})->query();

                $db->createCommand("UPDATE $table SET $this->rootName=" . $model->{$this->pkName} . " WHERE $this->rootName=" . $compare_model->{$this->pkName})->query();
                $db->createCommand("UPDATE $table SET  $this->parentIdName=" . $model->{$this->pkName} . "  WHERE $this->parentIdName=" . $compare_model->{$this->pkName})->query();

                $db->createCommand("UPDATE $table SET $this->rootName=" . $compare_model->{$this->pkName} . " WHERE $this->rootName=$temp_id")->query();
                $db->createCommand("UPDATE $table SET  $this->parentIdName=" . $compare_model->{$this->pkName} . "  WHERE $this->parentIdName=$temp_id")->query();

//                $db->createCommand('SET FOREIGN_KEY_CHECKS = 0;')->query();
                $db->createCommand("UPDATE $table SET  $this->pkName=$temp_id  WHERE $this->pkName=$pk LIMIT 1")->query();
                $db->createCommand("UPDATE $table SET  $this->pkName=$pk  WHERE $this->pkName=$compare_pk LIMIT 1")->query();
                $db->createCommand("UPDATE $table SET  $this->pkName=$compare_pk  WHERE $this->pkName=$temp_id LIMIT 1")->query();
//                $db->createCommand('SET FOREIGN_KEY_CHECKS = 1;')->query();
//                if ($this->subUpdateFunction != '') {
//                    $model->{$this->subUpdateFunction}($temp_id);
//                    $compare_model->{$this->subUpdateFunction}($model->{$this->pkName});
//                    $temp_model->{$this->subUpdateFunction}($compare_model->{$this->pkName});
//                }
            }
        } else {
            $sql = "SELECT $this->pkName FROM $table WHERE $this->pkName!=" . $model->{$this->pkName} . " and $this->levelName=" . $model->{$this->levelName} . " and $this->rootName=" . $model->{$this->rootName} . " and $this->lftName $lftOp " . $model->{$this->lftName} . "  ORDER BY $this->lftName $lftOrder LIMIT 1";
            $compare_pk = $db->createCommand($sql)->queryScalar();
            if ($compare_pk != NULL)
                $compare_model = CActiveRecord::model($this->modelClass)->findByPk($compare_pk);
            else
                $compare_model = NULL;
            if ($compare_model != NULL) {
                if ($move === 'up') {
                    $model->moveBefore($compare_model);
                } else if ($move === 'down') {
                    $model->moveAfter($compare_model);
                }
            }
        }
        $model = CActiveRecord::model($this->modelClass)->findByPk($pk);
        $model->afterUpdateOrder();
    }

}

?>
