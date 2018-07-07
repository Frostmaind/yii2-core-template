<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user \core\entities\User\User */

$homeLink = Yii::$app->frontendUrlManager->createAbsoluteUrl(['site/index']);
$cabinetLink = Yii::$app->frontendUrlManager->createAbsoluteUrl(['cabinet']);
?>
<div>
    <p>You have successfully registered at <?= $homeLink ?>.</p>

    <p>Here is a link to your profile: <?= $cabinetLink ?>.</p>

    <p>Good luck, and let your figures find the way to the opponent's king!</p>

    <p>This is a services letter related to your use of <?= $homeLink ?>.</p>
</div>
