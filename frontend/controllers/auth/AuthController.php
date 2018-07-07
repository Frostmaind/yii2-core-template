<?php

namespace frontend\controllers\auth;

use Yii;
use yii\base\Module;
use yii\web\Controller;
use core\forms\auth\LoginForm;
use core\services\auth\AuthService;

class AuthController extends Controller
{
    private $service;

    public function __construct(string $id, Module $module, AuthService $service, array $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->service = $service;
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $form = new LoginForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $user = $this->service->auth($form);
                Yii::$app->user->login($user, $form->rememberMe ? Yii::$app->params['user.rememberMeDuration'] : 0);
                return $this->goBack();
            } catch (\DomainException $e) {
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('login', [
            'model' => $form,
        ]);
    }


    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}