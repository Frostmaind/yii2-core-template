<?php

/* @var $this yii\web\View */
/* @var $user \core\entities\User\User */

$resetLink = Yii::$app->frontendUrlManager->createAbsoluteUrl(['auth/signup/confirm', 'token' => $user->email_confirm_token]);
?>
Hello <?= $user->username ?>,

Follow the link below to confirm your email:

<?= $resetLink ?>
