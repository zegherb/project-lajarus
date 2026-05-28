<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\ContactForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\captcha\Captcha;

$this->title = 'Hubungi Admin LAJARUS';
$this->params['breadcrumbs'][] = $this->title;
$this->params['meta_description'] = 'Hubungi tim admin LAJARUS untuk pertanyaan, bantuan, atau saran terkait sistem pelaporan jalan rusak.';
$this->params['meta_keywords'] = 'lajarus, kontak admin, bantuan, lapor jalan rusak';

$htmlIcon = <<<HTML
{label}<div class="input-group"><span class="input-group-text" aria-hidden="true">%s</span>{input}</div>{error}{hint}
HTML;
$labelOptions = ['class' => 'form-label fw-semibold small'];
?>
<?php if (Yii::$app->session->hasFlash('success')): ?>

<div class="site-contact-success d-flex align-items-center justify-content-center text-center py-5">
    <div class="site-contact-success-content mx-auto p-5 border rounded shadow-sm bg-body">
        <div class="mb-4 text-success">
            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
              <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
            </svg>
        </div>
        <h1 class="display-6 fw-semibold mb-3">Pesan Terkirim!</h1>
        <p class="text-body-tertiary mb-4">
            Terima kasih telah menghubungi Admin LAJARUS.<br>Kami akan segera menindaklanjuti dan membalas pesan Anda melalui email.
        </p>

        <?php if (YII_DEBUG && Yii::$app->mailer->useFileTransport): ?>
            <p class="text-danger small mb-4">
                <em>Development mode: email saved under</em><br>
                <code><?= Yii::getAlias(Yii::$app->mailer->fileTransportPath) ?></code>
            </p>
        <?php endif; ?>

        <?= Html::a(
            'Kirim Pesan Lain',
            ['contact'],
            ['class' => 'btn btn-outline-primary px-4 rounded-pill'],
        ) ?>
    </div>
</div>

<?php else: ?>

<div class="site-contact d-flex align-items-center justify-content-center py-5">
    <!-- Tambahkan class shadow dan bg-body agar popup card-nya lebih elegan -->
    <div class="card border-0 overflow-hidden shadow-lg login-split-card login-split-card-wide rounded-4" style="max-width: 900px; width: 100%;">
        <div class="row g-0">

            <!-- Brand panel (Kiri) - Diubah menjadi bg-primary agar nyambung dengan tema biru LAJARUS -->
            <div class="col-md-4 d-none d-md-flex bg-primary text-white p-4 p-lg-5">
                <div class="d-flex flex-column justify-content-between w-100">
                    <!-- Logo LAJARUS -->
                    <div class="d-flex align-items-center gap-2 mb-4">
                        <div class="bg-white rounded d-flex align-items-center justify-content-center shadow-sm" style="width: 40px; height: 40px">
                            <span class="text-primary fs-5 fw-bold">L</span>
                        </div>
                        <span class="fs-4 fw-bold tracking-wide">LAJARUS</span>
                    </div>
                    
                    <div class="mt-auto">
                        <h2 class="fw-bold mb-3 display-6">
                            Pusat<br>Bantuan
                        </h2>
                        <p class="opacity-75 mb-0" style="line-height: 1.6;">
                            Punya kendala saat melapor jalan rusak? Atau ada saran untuk pengembangan sistem LAJARUS? Jangan ragu untuk menghubungi Admin kami.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Form panel (Kanan) -->
            <div class="col-md-8 bg-body">
                <div class="p-4 p-lg-5">
                    <div class="text-center text-md-start mb-4">
                        <!-- Logo untuk versi Mobile -->
                        <div class="d-md-none mb-3 d-flex align-items-center justify-content-center gap-2">
                            <div class="bg-primary rounded d-flex align-items-center justify-content-center" style="width: 35px; height: 35px">
                                <span class="text-white fs-6 fw-bold">L</span>
                            </div>
                            <span class="fs-5 fw-bold text-primary">LAJARUS</span>
                        </div>
                        
                        <h1 class="h3 fw-bold mb-2"><?= Html::encode($this->title) ?></h1>
                        <p class="text-body-secondary small">Silakan isi formulir di bawah ini dan tim kami akan segera merespons Anda.</p>
                    </div>

                    <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

                    <div class="row">
                        <div class="col-sm-6 mb-3">
                            <?= $form->field($model, 'name', [
                                'options' => ['class' => 'mb-0'],
                                'template' => sprintf($htmlIcon, '&#128100;'), // Icon User
                                'inputOptions' => [
                                    'class' => 'form-control',
                                    'placeholder' => 'Budi Santoso',
                                    'autofocus' => true,
                                ],
                            ])->label('Nama Lengkap', $labelOptions) ?>
                        </div>

                        <div class="col-sm-6 mb-3">
                            <?= $form->field($model, 'email', [
                                'options' => ['class' => 'mb-0'],
                                'template' => sprintf($htmlIcon, '&#9993;'), // Icon Amplop
                                'inputOptions' => [
                                    'class' => 'form-control',
                                    'placeholder' => 'budi@example.com',
                                ],
                            ])->label('Alamat Email', $labelOptions) ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <?= $form->field($model, 'subject', [
                            'options' => ['class' => 'mb-0'],
                            'template' => sprintf($htmlIcon, '&#128172;'), // Icon Chat
                            'inputOptions' => [
                                'class' => 'form-control',
                                'placeholder' => 'Cth: Kendala Unggah Foto Laporan',
                            ],
                        ])->label('Subjek Pesan', $labelOptions) ?>
                    </div>

                    <div class="mb-4">
                        <?= $form->field($model, 'body', [
                            'options' => ['class' => 'mb-0'],
                            'template' => '{label}{input}{error}{hint}',
                            'inputOptions' => [
                                'class' => 'form-control',
                                'placeholder' => 'Jelaskan pesan atau kendala Anda secara detail di sini...',
                                'rows' => 5,
                            ],
                        ])->textarea()->label('Isi Pesan', $labelOptions) ?>
                    </div>

                    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                        <!-- Captcha -->
                        <div class="captcha-wrapper">
                            <?= $form->field($model, 'verifyCode', [
                                'enableLabel' => false,
                                'options' => ['class' => 'mb-0'],
                                'inputOptions' => [
                                    'aria-label' => 'Kode Verifikasi',
                                    'class' => 'form-control',
                                    'placeholder' => 'Ketik huruf di samping'
                                ],
                            ])->widget(Captcha::class, [
                                'template' => '<div class="d-flex align-items-center gap-2">{image}{input}</div>',
                            ]) ?>
                        </div>

                        <!-- Tombol Submit -->
                        <?= Html::submitButton(
                            'Kirim Pesan &nbsp; &#10148;',
                            [
                                'class' => 'btn btn-primary px-4 py-2 fw-semibold rounded-pill',
                                'name' => 'contact-button',
                            ],
                        ) ?>
                    </div>

                    <?php ActiveForm::end(); ?>

                </div>
            </div>

        </div>
    </div>
</div>

<?php endif; ?>