<?php

namespace app\models;

use Yii;
use \app\models\base\WilayahKabupaten as BaseWilayahKabupaten;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "wilayah_kabupaten".
 */
class WilayahKabupaten extends BaseWilayahKabupaten
{

    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                # custom behaviors
            ]
        );
    }

    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                # custom validation rules
            ]
        );
    }
}
