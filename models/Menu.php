<?php

namespace app\models;

use Yii;
use \app\models\base\Menu as BaseMenu;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "menu".
 */
class Menu extends BaseMenu
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
