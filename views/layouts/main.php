<?php
// Pastikan namespace AppAsset sesuai dengan template kamu. 
use app\assets\AppAsset; 
use yii\helpers\Html;

// WAJIB: Ini yang memanggil Bootstrap & CSS bawaan
AppAsset::register($this);

// Load Lucide Icons secara global untuk seluruh halaman
$this->registerJsFile('https://unpkg.com/lucide@latest', ['position' => \yii\web\View::POS_HEAD]);
$this->registerJs('lucide.createIcons();', \yii\web\View::POS_READY);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100" data-bs-theme="light">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    
    <?php $this->head() ?> 
</head>
<body class="d-flex flex-column min-vh-100">
<?php $this->beginBody() ?>

<?= $this->render('_header') ?>

<main role="main" class="flex-shrink-0">
    <?= $content ?> 
</main>
<?= $this->render('_footer') ?>

<?php $this->endBody() ?> 
</body>
</html>
<?php $this->endPage() ?>