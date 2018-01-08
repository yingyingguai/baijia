<?php

namespace frontend\models;

use yii\db\ActiveRecord;

class Address extends ActiveRecord
{
    public function rules()
    {
        return [
            [['name', 'tel', 'address', 'province', 'city', 'area'], 'required'],
            [['status','member_id'], 'default', 'value' => null],

        ];
    }


}