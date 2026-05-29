<?php

use app\assets\AppAsset;
use yii\helpers\Html;

AppAsset::register($this);

// Load Lucide Icons
$this->registerJsFile(
    'https://unpkg.com/lucide@latest',
    ['position' => \yii\web\View::POS_HEAD]
);

$this->registerJs(
    'lucide.createIcons();',
    \yii\web\View::POS_READY
);

$currentRoute = Yii::$app->controller->route;

$footerRoutes = [
    'site/login',
    'site/register',
    'site/signup',
    'site/index' // Gw tambahin index (landing page) ngikutin rencana lu sebelumnya
];

$showFooter = in_array($currentRoute, $footerRoutes);
?>

<?php $this->beginPage() ?>

<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    
    <meta name="viewport"
          content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <?php $this->registerCsrfMetaTags() ?>

    <title><?= Html::encode($this->title) ?></title>

    <?php $this->head() ?>

    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            overflow-x: hidden;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background: #f8f9fa;
        }

        main {
            flex: 1;
            width: 100%;
            padding-top: 80px;
        }

        @media (max-width: 768px) {
            main {
                padding-top: 70px;
            }
        }
    </style>
</head>

<body>

<?php $this->beginBody() ?>

<?= $this->render('_header') ?>

<main role="main">
    <?= $content ?>
</main>

<?php if ($showFooter): ?>
    <?= $this->render('_footer') ?>
<?php endif; ?>

<?php $this->endBody() ?>

</body>
</html>

<?php $this->endPage() ?>