<?php

namespace app\models;

use Yii;
use \app\models\base\WilayahDesa as BaseWilayahDesa;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "wilayah_desa".
 */
class WilayahDesa extends BaseWilayahDesa
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
