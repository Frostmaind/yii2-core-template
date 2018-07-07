<?php

namespace frontend\controllers;

use Yii;
use yii\base\Module;
use yii\web\Controller;
use core\forms\ContactForm;
use core\services\ContactSerivce;

class ContactController extends Controller
{
    private $service;

    public function __construct(string $id, Module $module, ContactSerivce $service, array $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->service = $service;
    }

    public function actionIndex()
    {
        $form = new ContactForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->send($form);
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
                return $this->goHome();
            } catch (\Exception $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
            return $this->refresh();
        }

        return $this->render('index', [
            'model' => $form,
        ]);
    }
}