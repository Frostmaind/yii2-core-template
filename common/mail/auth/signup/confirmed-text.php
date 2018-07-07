<?php

/* @var $this yii\web\View */
/* @var $user \core\entities\User\User */

$homeLink = Yii::$app->frontendUrlManager->createAbsoluteUrl(['site/index']);
$cabinetLink = Yii::$app->frontendUrlManager->createAbsoluteUrl(['cabinet']);
?>

You have successfully registered at <?= $homeLink ?>.

Here is a link to your profile: <?= $cabinetLink ?>.

Good luck, and let your figures find the way to the opponent's king!

This is a services letter related to your use of <?= $homeLink ?>.



