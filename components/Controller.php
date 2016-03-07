<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */

namespace app\components;

use app\models\User;
use app\models\Page;
use Yii;
use yii\helpers\Json;
use yii\web\Controller as BaseContoller;
use yii\web\View;

class Controller extends BaseContoller
{
    /**
     * @var string the default layout for the controller view. Defaults to '//layouts/column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout = '@app/views/layouts/column1';
    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu = [];
    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs = [];

    public $footer = '';

    public $user;

    private $isBackend;

    public $js_settings = ['/' => '/'];

    public function beforeAction($action)
    {
        //if (!Yii::$app->user->isGuest) {

            //Yii::$app->user->identity = User::findOne(['id' => Yii::$app->user->identity->userId]);

        //}

        return parent::beforeAction($action);
    }

    public function getIsBackend()
    {
        if ($this->isBackend === null) {
            $this->isBackend = strpos(Yii::$app->controllerNamespace, 'backend') === false ? false : true;
        }

        return $this->isBackend;
    }

    public function render($view, $params = [])
    {
        $_view = Yii::$app->view;
        $am = $_view->getAssetManager();
        list ($basePath, $baseUrl) = $am->publish('@app/assets/js');

        $urlManager = Yii::$app->urlManager;

        $managerVars = [];
        $managerVars['rules'] = [];
        $managerVars['enablePrettyUrl'] = $urlManager->enablePrettyUrl? true: false;
        $rules = get_object_vars($urlManager)['rules'];

        foreach($rules as $rule){
            $managerVars['rules'][$rule->name] = $rule->route;
        }

        $encodedVars = Json::encode($managerVars);

        if($urlManager->showScriptName) {
            $scriptUrl = Yii::$app->request->scriptUrl;
        } else {
            $scriptUrl = '';
        }
        $hostInfo = Yii::$app->request->hostInfo;

        $js = <<<JS
\n/** APP Settings */
var yii = yii || {};
yii.app = {scriptUrl: '{$scriptUrl}',baseUrl: '{$baseUrl}', hostInfo: '{$hostInfo}'};
yii.app.urlManager = new UrlManager({$encodedVars});
yii.app.createUrl = function(route, params, method)  {
    var method = method || "get";
    var url = this.urlManager.createUrl(route, params, method);
    console.info("Route: " + method + " " + route);
    console.info("Params: " + JSON.stringify(params));
    console.info("Url: " + url);
    return url;
};\n
JS;

        $_view->registerJsFile($baseUrl . '/PHPJS.dependencies.js', ['position' => View::POS_HEAD]);
        $_view->registerJsFile($baseUrl . '/Yii.UrlManager.js', ['position' => View::POS_HEAD]);
        $_view->registerJs($js, View::POS_HEAD, 'js-app-settings');

        //$this->module->layout = $this->module->theme . '/layouts/main';

        return parent::render($view, $params);
    }

    public function showMessages($model = null)
    {
        if ($model === null) {
            $errors = array();
        } else {
            $errors = $model->getErrors();
        }

        if (count($errors) > 0) {
            echo '<div class="row">';
            foreach ($errors as $error) {
                echo '<div class="alert alert-dismissable alert-danger">';
                echo '<button type="button" class="close" data-dismiss="alert">×</button>';
                echo '<strong>' . $error[0] . '</strong>';
                echo '</div>';
                break;
            }
            echo '</div>';
        } elseif (Yii::$app->session->hasFlash('SUCCESS')) {
            echo '<div class="alert alert-dismissable alert-success">';
            echo '<button type="button" class="close" data-dismiss="alert">×</button>';
            echo '<strong>' . Yii::$app->session->getFlash('SUCCESS') . '</strong>';
            echo '</div>';
        } elseif (Yii::$app->session->hasFlash('ERROR')) {
            echo '<div class="alert alert-dismissable alert-danger">';
            echo '<button type="button" class="close" data-dismiss="alert">×</button>';
            echo '<strong>' . Yii::$app->session->getFlash('ERROR') . '</strong>';
            echo '</div>';
        }
    }

    /*public function filterIsAdmin($filterChain)
    {
        if (isset(Yii::$app->user->identity->role) && !Yii::$app->user->identity->isGuest) {
            if (in_array(Yii::$app->user->identity->role, array('ADMIN', 'SUPERADMIN'))) {
                $filterChain->run();
            }
        } else {
            $this->redirect(array('/site/login'));
        }
    }*/

    /*public function filterIsUser($filterChain)
    {
        if (isset(Yii::$app->user->identity->role) && !Yii::$app->user->identity->isGuest) {
            if (in_array(Yii::$app->user->identity->role, array('USER'))) {
                $filterChain->run();
            }
        } else {
            $this->redirect(array('/site/login'));
        }
    }*/

    public function createUrl($params)
    {
        return Yii::$app->urlManager->createUrl($params);
    }

    /**
     * Static pages
     */
    public function actionPage()
    {
        $view = Yii::$app->request->get('view');

        if (empty($view)) {
            return $this->actionIndex();
        }

        $page = Page::findOne(['url' => $view]);

        if ($page === null) {
            return Yii::$app->runAction($view);
        } else {
            return $this->renderPartial('page', ['page' => $page]);
        }
    }

}