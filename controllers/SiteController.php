<?php
namespace app\controllers;

use app\models\ContactForm;
use app\models\LoginForm;
use app\models\SignupForm;
use app\models\User;
use app\models\Page;
use app\components\Controller as BaseContoller;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
//use yii\web\Controller;

/**
 * Site controller
 */
class SiteController extends BaseContoller
{
    /**
     * @inheritdoc
     */
    /*public function behaviors()
    {
        return [
          'access' => [
            'class' => AccessControl::className(),
            'only' => ['logout', 'signup'],
            'rules' => [
              [
                'actions' => ['signup'],
                'allow' => true,
                'roles' => ['?'],
              ],
              [
                'actions' => ['logout'],
                'allow' => true,
                'roles' => ['@'],
              ],
            ],
          ],
          'verbs' => [
            'class' => VerbFilter::className(),
            'actions' => [
              'logout' => ['get'],
            ],
          ],
        ];
    }*/

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
          'error' => [
            'class' => 'yii\web\ErrorAction',
          ],
          /*'captcha' => [
              'class' => 'yii\captcha\CaptchaAction',
              'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
          ],*/
        ];
    }

    public function actionIndex()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['/cabinet']);
        }

        $this->layout = 'landing';

        return $this->render('index');
    }

    public function actionRegistration()
    {
        $model = new User;

        // collect user input data
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            if ($model->save()) {
                Yii::$app->getSession()->setFlash('SUCCESS', 'Регистрация прошла успешно');
                $this->redirect(['/site/login']);
            }

        }

        // display the login form
        return $this->render('registration', ['model' => $model]);
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {

            if (Yii::$app->user->identity->role == 'USER') {
                $this->redirect(['/cabinet']);
            } elseif (Yii::$app->user->identity->role == 'ADDITIONAL_USER') {
                $this->redirect(['/cabinet']);
            } elseif (Yii::$app->user->identity->role == 'SUPERADMIN') {
                $this->redirect(['/admin']);
            }

        } else {
            return $this->render('login', [
              'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /*public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending email.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }*/

    /*public function actionAbout()
    {
        return $this->render('about');
    }*/

    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
          'model' => $model,
        ]);
    }

    /*public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->getSession()->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->getSession()->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }*/

    /*public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->getSession()->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }*/

}
