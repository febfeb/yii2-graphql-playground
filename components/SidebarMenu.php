<?php

namespace app\components;

use app\models\Menu;
use yii\bootstrap\Widget;

class SidebarMenu extends Widget
{
    public static function buildMenu()
    {
        return "tes";
    }

    public static function getMenu($roleId, $parentId = NULL)
    {
        $output = [];
        /** @var Menu $menu */
        foreach (Menu::find()->where(["parent_id" => $parentId])->orderBy("`order` ASC")->all() as $menu) {
            //$icon = substr($menu->icon, 6);
            if (SidebarMenu::roleHasAccess($roleId, $menu->id)) {
                $obj = [
                    "id" => $menu->id,
                    "label" => $menu->name,
                    "icon" => $menu->icon,
                    "url" => SidebarMenu::getUrl($menu),
                    "controller" => $menu->controller,

                ];

                if (count($menu->menus) != 0) {
                    $obj["items"] = SidebarMenu::getMenu($roleId, $menu->id);
                }

                $output[] = $obj;
            }
        }
        return $output;
    }

    private static function roleHasAccess($roleId, $menuId)
    {
        $roleMenu = \app\models\RoleMenu::find()->where(["menu_id" => $menuId, "role_id" => $roleId])->one();
        if ($roleMenu) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    private static function getUrl($menu)
    {
        if ($menu->controller == NULL) {
            return "#";
        } else {
            return [$menu->controller . "/" . $menu->action];
        }
    }
}