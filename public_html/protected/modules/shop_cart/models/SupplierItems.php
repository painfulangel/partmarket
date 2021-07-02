<?php

/**
 * ContactForm class.
 * ContactForm is the data structure for keeping
 * contact form data. It is used by the 'contact' action of 'SiteController'.
 */
class SupplierItems extends Items {

    public function export() {
        $out = 'Производитель;Наименование;Артикул;Количество' . "\n";
        $ids = array(0);
        foreach ($this->getSelectedData()->getData() as $data) {
            $out.="$data->brand;$data->name;$data->article_order;$data->quantum\n";
            $ids[] = ' id=\'' . $data->id . '\' ';
        }
        Yii::app()->db->createCommand('UPDATE `items` SET`get_status`=\'1\' WHERE (' . implode(' OR ', $ids) . ')')->query();
        return iconv('UTF-8', 'cp1251', $out);
    }

    public function getSelectedData() {
        $list = array(0);
        if (isset($_POST['ids'])) {
            foreach ($_POST['ids'] as $key => $value) {
                $list[] = 'id=' . $key;
            }
        }

        $criteria = new CDbCriteria;
        $criteria->condition = implode(' or ', $list);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 9999,
            ),
        ));
    }

    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->with = array('User' => array('together' => true, 'alias' => 'u'));
        $criteria->together = true;

        //    $criteria->select = "*, u.first_name as user_search_fio ";

        if (!empty($this->user_search_fio))
            $criteria->compare('concat(u.second_name,\' \',u.first_name)', $this->user_search_fio, true);
        if (!empty($this->user_search_email))
            $criteria->compare('u.email', $this->user_search_email, true);
        if (!empty($this->user_search_organization_name))
            $criteria->compare('u.organization_name', $this->user_search_organization_name, true);
        if (!empty($this->user_search_inn))
            $criteria->compare('u.organization_inn', $this->user_search_inn, true);
        if (!empty($this->user_search_phone))
            $criteria->compare('concat(u.phone,\' \',u.extra_phone)', $this->user_search_phone, true);

        $criteria->compare('id', $this->id);
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('order_id', $this->order_id);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('price', $this->price);
        $criteria->compare('brand', $this->brand, true);
        $criteria->compare('price_echo', $this->price_echo);
        $criteria->compare('quantum', $this->quantum);
        $criteria->compare('delivery', $this->delivery);
        $criteria->compare('article', $this->article, true);
        $criteria->compare('article_order', $this->article_order);
        $criteria->compare('supplier_inn', $this->supplier_inn, true);
        $criteria->compare('supplier', $this->supplier, true);
        $criteria->compare('store', $this->store, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('payed_status', $this->payed_status);
        $criteria->compare('ic_status', $this->ic_status);
        $criteria->compare('status', $this->status);
        if ($this->get_status != -1 && $this->get_status != '') {
            $criteria->compare('get_status', $this->get_status);
        }

        if ($this->status != 0)
            $criteria->compare('status', $this->status);
        if ($this->date_from != '') {
            $this->date_from = strtotime($this->date_from);
        }
        if (!empty($this->date_to)) {
            $this->date_to = strtotime($this->date_to);
        }
        if (!empty($this->duration)) {
            $this->date_to = strtotime(date('Y-m-d 24:59:59'));
            $this->date_from = strtotime(date('Y-m-d')) - 3600 * 24 * $this->duration;
        }
        if (!empty($this->create_date)) {
            $this->date_from = @strtotime($this->create_date);
            $this->date_to = @strtotime($this->create_date) + 3600 * 24;
        }

        if ($this->date_from != '')
            $criteria->compare('create_date', '>=' . $this->date_from, true);
        if ($this->date_to != '')
            $criteria->compare('create_date', '<=' . $this->date_to, true);


        if (!empty($this->price_total)) {
            $criteria->compare('price*quantum', $this->price_total);
        }
        $criteria->compare('weight', $this->weight, true);
        $criteria->select.=', (t.price*t.quantum) AS price_total ';

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 100,
            ),
        ));
    }

}
