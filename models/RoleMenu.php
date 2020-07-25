<?php

namespace app\models;

use Yii;
use \app\models\base\RoleMenu as BaseRoleMenu;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "role_menu".
 */
class RoleMenu extends BaseRoleMenu
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
