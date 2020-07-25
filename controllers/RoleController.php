<?php

namespace app\controllers;

use app\models\Role;
use app\models\RoleMenu;
use app\models\search\RoleSearch;
use dmstr\bootstrap\Tabs;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\HttpException;

/**
 * RoleController implements the CRUD actions for Role model.
 */
class RoleController extends BaseController
{
    /**
     * @var boolean whether to enable CSRF validation for the actions in this controller.
     * CSRF validation is enabled only when both this property and [[Request::enableCsrfValidation]] are true.
     */
    public $enableCsrfValidation = false;

    /**
     * Lists all Role models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RoleSearch;
        $dataProvider = $searchModel->search($_GET);

        Tabs::clearLocalStorage();

        Url::remember();
        \Yii::$app->session['__crudReturnUrl'] = null;

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionDuplicate($from, $to)
    {
        RoleMenu::deleteAll(["role_id" => $to]);
        $roleMenus = RoleMenu::find()->where(["role_id" => $from])->all();
        /** @var RoleMenu $roleMenu */
        foreach ($roleMenus as $roleMenu) {
            $newMenu = new RoleMenu();
            $newMenu->role_id = $to;
            $newMenu->menu_id = $roleMenu->menu_id;
            $newMenu->save();
        }

        /** @var Role $role */
        $role = Role::find()->where(["id"=>$from])->one();

        Yii::$app->session->addFlash("success", "Berhasil menduplikat dari role ".$role->name);
        return $this->redirect(["detail", "id" => $to]);
    }

    /**
     * Displays a single Role model.
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
     * Creates a new Role model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Role;

        try {
            if ($model->load($_POST) && $model->save()) {
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
     * Updates an existing Role model.
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
     * Deletes an existing Role model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        try {
            $this->findModel($id)->delete();
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            \Yii::$app->getSession()->setFlash('error', $msg);
            return $this->redirect(Url::previous());
        }

        // TODO: improve detection
        $isPivot = strstr('$id', ',');
        if ($isPivot == true) {
            return $this->redirect(Url::previous());
        } elseif (isset(\Yii::$app->session['__crudReturnUrl']) && \Yii::$app->session['__crudReturnUrl'] != '/') {
            Url::remember(null);
            $url = \Yii::$app->session['__crudReturnUrl'];
            \Yii::$app->session['__crudReturnUrl'] = null;

            return $this->redirect($url);
        } else {
            return $this->redirect(['index']);
        }
    }

    public function actionDetail($id)
    {
        $model = $this->findModel($id);

        if (isset($_POST['menu'])) {
            RoleMenu::deleteAll(["role_id" => $id]);
            $menus = $_POST['menu'];
            foreach ($menus as $menu) {
                $roleMenu = new RoleMenu();
                $roleMenu->role_id = $id;
                $roleMenu->menu_id = $menu;
                $roleMenu->save();
            }
            \Yii::$app->session->addFlash("success", "Role " . $model->name . " successfully updated.");
            return $this->redirect(["index"]);
        }

        return $this->render('detail', [
            'model' => $model,
        ]);
    }

    /**
     * Finds the Role model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Role the loaded model
     * @throws HttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Role::findOne($id)) !== null) {
            return $model;
        } else {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }
}
