<?php

use app\models\Menu;
use app\models\Role;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var app\models\Role $model
 */

$this->title = 'Hak Akses - ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Hak Akses', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => "Set Menu untuk " . $model->name, 'url' => ['view', 'id' => $model->id]];
?>
<?php $form = ActiveForm::begin(['id' => 'my-form']); ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Pilih Menu untuk Hak Akses <?= $model->name; ?></h3>
    </div>
    <div class="card-body">

        <?php
        function isChecked($role_id, $menu_id)
        {

            $role_menu = \app\models\RoleMenu::find()->where(["menu_id" => $menu_id, "role_id" => $role_id])->one();
            if ($role_menu) {
                return TRUE;
            }
            return FALSE;
        }


        function showCheckbox($name, $value, $label, $checked = FALSE)
        {
            ?>
            <label>
                <input type="checkbox" name="<?= $name ?>" value="<?= $value ?>"
                       class="minimal actions" <?= $checked ? "checked" : "" ?>>
            </label>
            <label style="padding: 0px 20px 0px 5px"> <?= $label; ?></label>
            <?php
        }

        function getAllChild($role_id, $parent_id = NULL, $level = 0)
        {
            /** @var Menu $menu */
            foreach (\app\models\Menu::find()->where(["parent_id" => $parent_id])->orderBy("`order` ASC")->all() as $menu) {
                ?>
                <div style="padding-left: <?= $level * 20 ?>px">
                    <label style="font-weight: normal">
                        <input type="checkbox" name="menu[]" value="<?= $menu->id ?>"
                               class="minimal" <?= isChecked($role_id, $menu->id) ? "checked" : "" ?>>
						<?php if(Yii::$app->user->identity->role_id!=Role::SUPER_ADMINISTRATOR && $menu->controller=='slip-gaji'){?>
							<?php echo 'Slip Gaji (Individu)'; ?>
						<?php }else{?>
							<?= $menu->name; ?>
						<?php }?>
                    </label>
                </div>
                <?php

                /*
                //Show All Actions
                $camelName = Inflector::id2camel($menu->controller);
                $fullControllerName = "app\\controllers\\".$camelName."Controller";
                if(class_exists($fullControllerName)){
                    $reflection = new ReflectionClass($fullControllerName);
                    $methods = $reflection->getMethods();

                    echo "<div class=\"form-group\" style=\"padding-left: ".($level * 20 + 10)."px;\">";
                    echo "<label><input type=\"checkbox\" class=\"minimal select-all\" ></label><label style=\"padding: 0px 20px 0px 5px\"> Select All</label>";
                    foreach($methods as $method){
                        if(substr($method->name, 0, 6) == "action" && $method->name != "actions"){
                            $camelAction = substr($method->name, 6);
                            $id = Inflector::camel2id($camelAction);
                            $name = Inflector::camel2words($camelAction);
                            $action = \app\models\Action::find()->where(["action_id"=>$id, "controller_id"=>$menu->controller])->one();
                            if($action == NULL){
                                //If the action not in database, save it !
                                $action = new \app\models\Action();
                                $action->action_id = $id;
                                $action->controller_id = $menu->controller;
                                $action->name = $name;
                                $action->save();
                            }
                            showCheckbox("action[]", $action->id, $name, hasAccessToAction($role_id, $action->id));
                        }
                    }
                    echo "</div>";
                }
                */

                getAllChild($role_id, $menu->id, $level + 1);
            }
        }

        getAllChild($model->id, NULL);
        ?>

        <button class="btn btn-info" type="button" id="select_all_btn">
            <i class="fa fa-check"></i> Select/Deselect All
        </button>
        <button class="btn btn-success" type="submit" id="btn-save">
            <i class="fa fa-save"></i> Simpan
        </button>
        <?= Html::a("<i class=\"fa fa-chevron-left\"></i> Kembali", ["index"], ["class" => "btn btn-default"]) ?>

        <hr>

        <div class="alert alert-info">
            Klik tombol di bawah ini untuk melakukan duplikat hak akses.
        </div>

        <?= Html::dropDownList("roles", null, ArrayHelper::map(Role::find()->where("id != " . $model->id)->orderBy("name")->all(), "id", "name"), ["class" => "form-control", "id" => "roles", "style" => "width:auto;display:inline"]) ?>
        <?= Html::button("Duplikat Hak Akses", ["id" => "btn-dupe", "class" => "btn btn-info"]) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>

<?php $this->registerJs('

$("#btn-save").click(function(){
    
    return true;
});

$("#select_all_btn").click(function(){
    $(".minimal").iCheck("toggle");
});

$(".select-all").on("ifClicked", function(){

    if($(this).prop("checked")){
        $(this).closest(".form-group").find(".actions").iCheck("uncheck");
    }else{
        $(this).closest(".form-group").find(".actions").iCheck("check");
    }
});

$("#btn-dupe").click(function(){
    window.location = "' . Url::to(["duplicate"]) . '?from="+$("#roles").val()+"&to=' . $model->id . '";
    return;
})

'); ?>
