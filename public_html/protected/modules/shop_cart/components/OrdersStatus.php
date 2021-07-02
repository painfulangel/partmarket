<?php

class OrdersStatus extends ShopingCartStatus
{

    protected $type = 'Orders';

    public function changeStatus($model, $new_status, $flag = false)
    {
        if ($this->get1CStatus($model)) {
            return $this->IC_ERROR;
        }
        $items_where = ' ';
        switch ($new_status) {
            case 9:
                //Отказ
                $model->cancelOrder();
            break;
            case 5:
                //Резерв
                $items_where = ' and `status`=\'1\'';
            break;
            case 6:
                //Готов к выдаче
                $items_where = ' and  status!=\'9\' and status!=\'8\' ';
            break;
            case 4:
                //Частичный резерв
                $items_where = ' and 0';
            break;
            case 7:
                //Частично выдан
                $items_where = ' and 0';
            break;
            case 8:
                //Выполнен
                if ($model->payed_status != 2) {
                    $items_where = ' and `status`!=\'9\'';
                    $model->finishOrder($flag);
                } else {
                    $items_where = ' and 0';
                }
            break;
            default:
                //1 - Принят к обработке
                //2 - Заказ оформлен
            break;
        }
        $db = Yii::app()->db;
        $sql = 'UPDATE  `'.Items::model()->tableName()."` SET `status`='$new_status'  WHERE `order_id`='$model->id' $items_where";
        $db->createCommand($sql)->query();
        $model->status = $new_status;
        $model->save();
        $model->sendEmailNotification();
        return $this->STATUS_SUCCESS;
    }
}