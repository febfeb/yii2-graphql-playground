<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Menu;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\MenuSearch $searchModel
 */

$this->title = 'Manajemen Menu';
$this->params['breadcrumbs'][] = $this->title;
?>

    <style>
        .sorterer {
            text-align: center;
            background: #0000aa;
            color: #ffffff;
            cursor: move;
        }

        table tr.sorting-row td {
            background-color: #8b8;
        }
    </style>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <?= Html::a("<i class=\"fa fa-plus\"></i> Tambah Menu Baru", ["create"], ["class" => "btn btn-info"]) ?>
                    <button id="simpanBtn" class="btn btn-success"><i class="fa fa-save"></i> Simpan</button>
                </div>
                <div class="card-body">
                    <table class="table table-responsive" id="tableSorter">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Menu</th>
                            <th>Controller</th>
                            <th>Ikon</th>
                            <th style="width: 100px">Jenis</th>
                            <th>Induk</th>
                            <th style="width: 50px">#</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php


                        $parents = \yii\helpers\ArrayHelper::map(\app\models\Menu::find()->where(["parent_id" => null])->all(), "id", "name");

                        $no = 1;
                        /* @var $menu Menu */
                        foreach (Menu::find()->where(["parent_id" => null])->orderBy("`order` ASC")->all() as $menu) {
                            $delete = Html::a("<span class='fas fa-trash'></span>", ["delete", "id"=>$menu->id], [
                                "class" => "btn btn-danger",
                                "data-confirm" => "Anda yakin ingin menghapus menu ini ?",
                            ]);

                            $name = Html::textInput("name", $menu->name, ["class" => "form-control name"]);
                            $controller = Html::textInput("controller", $menu->controller, ["class" => "form-control controller"]);
                            $parent = Html::dropDownList("parent_id", $menu->parent_id, $parents, ["class" => "form-control parent_id", "prompt" => "-"]);
                            $button = "<i class='fas fa-arrows-alt'></i>";
                            $icp = Html::textInput("icon", $menu->icon, ["class" => "form-control icon icp-auto"]);
                            echo "<tr style='background-color: #FFFCE7;' data='{$menu->id}'>
                            <td>{$no}</td>
                            <td>{$name}</td>
                            <td>{$controller}</td>
                            <td>{$icp}</td>
                            <td></td>
                            <td>{$parent}</td>
                            <td>
                                {$delete}
                            </td>
                            <td class='sorterer'>{$button}</td>
                            </tr>";
                            $no++;
                            /** @var Menu $menu2 */
                            foreach (Menu::find()->where(["parent_id" => $menu->id])->orderBy("`order` ASC")->all() as $menu2) {
                                $delete = Html::a("<span class='fas fa-trash'></span>", ["delete", "id"=>$menu2->id], [
                                    "class" => "btn btn-danger",
                                    "data-confirm" => "Anda yakin ingin menghapus menu ini ?",
                                ]);

                                $name = Html::textInput("name", $menu2->name, ["class" => "form-control name"]);
                                $controller = Html::textInput("controller", $menu2->controller, ["class" => "form-control controller"]);
                                $parent = Html::dropDownList("parent_id", $menu2->parent_id, $parents, ["class" => "form-control parent_id", "prompt" => "-"]);
                                $button = "<i class='fas fa-arrows-alt'></i>";
                                $icp = Html::textInput("icon", $menu2->icon, ["class" => "form-control icon icp-auto"]);
                                echo "<tr data='{$menu2->id}'>
                            <td>{$no}</td>
                            <td>{$name}</td>
                            <td>{$controller}</td>
                            <td>{$icp}</td>
                            <td></td>
                            <td>{$parent}</td>
                            <td>
                                {$delete}
                            </td>
                            <td class='sorterer'>{$button}</td>
                            </tr>";
                                $no++;
                            }
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

<?php
$this->registerJs('

new RowSorter("#tableSorter", {
    handler: "td.sorterer",
});

$("#simpanBtn").click(function(){
    var arr = [];
    $("tbody tr").each(function(){
        var obj = [];
        obj.push($(this).attr("data"));
        obj.push($(this).find(".name").val());
        obj.push($(this).find(".controller").val());
        obj.push($(this).find(".parent_id").val());
        obj.push($(this).find(".icon").val());
        arr.push(obj.join("[=]"));
    });
    console.log(arr.join("||"));
    $.ajax({
        url : "' . Url::to(["save"]) . '",
        data : {
            str : arr.join("||"),
        },
        type : "post",
        success : function(){
            alert("Menu berhasil disimpan");
        }
    });
    return false;
});

$(".icp-auto").iconpicker();

');
?>