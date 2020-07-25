<?php

namespace app\controllers;

use app\models\Menu;
use app\models\RoleMenu;
use dmstr\bootstrap\Tabs;
use Yii;
use yii\helpers\Url;
use yii\web\HttpException;

//use app\models\search\MenuSearch;

/**
 * MenuController implements the CRUD actions for Menu model.
 */
class MenuController extends BaseController
{
    /**
     * @var boolean whether to enable CSRF validation for the actions in this controller.
     * CSRF validation is enabled only when both this property and [[Request::enableCsrfValidation]] are true.
     */
    public $enableCsrfValidation = false;

    /**
     * Lists all Menu models.
     * @return mixed
     */
    public function actionIndex()
    {

        return $this->render('index', [
        ]);
    }

    /**
     * Displays a single Menu model.
     * @param integer $id
     *
     * @return mixed
     */
    public function actionView($id)
    {
        \Yii::$app->session['__crudReturnUrl'] = Url::previous();
        Url::remember();
        Tabs::rememberActiveState();

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Menu model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Menu;

        try {
            if ($model->load($_POST)) {
                $model->icon = "fa " . $model->icon;
                $model->save();
                return $this->redirect(Url::previous());
            } elseif (!\Yii::$app->request->isPost) {
                $model->load($_GET);
            }
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            $model->addError('_exception', $msg);
        }
        return $this->render('create', ['model' => $model]);
    }

    /**
     * Updates an existing Menu model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load($_POST) && $model->save()) {
            return $this->redirect(Url::previous());
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Menu model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $menu = $this->findModel($id);

        RoleMenu::deleteAll(["menu_id" => $id]);

        $menu->delete();

        Yii::$app->session->addFlash("success", "Berhasil menghapus menu");
        return $this->redirect(["index"]);
    }

    public function actionSave()
    {
        $str = $_POST['str'];
        $trs = explode("||", $str);
        $no = 1;
        foreach ($trs as $tr) {
            $obj = explode("[=]", $tr);
            /** @var Menu $menu */
            $menu = Menu::find()->where(["id" => $obj[0]])->one();
            $menu->name = $obj[1];
            $menu->controller = $obj[2];
            $menu->parent_id = $obj[3];
            $menu->order = $no;
            $menu->icon = $obj[4];
            $menu->save();
            $no++;
        }
    }

    /**
     * Finds the Menu model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Menu the loaded model
     * @throws HttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Menu::findOne($id)) !== null) {
            return $model;
        } else {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }
}
