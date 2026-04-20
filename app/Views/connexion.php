<?php helper('form');
$validation = session('validation');
?>

<div id="sousContenu">
    <?php
    echo form_open(site_url('connexion/valider'));
    ?>
    <div class="corpsForm">

        <p>
            <?= form_label('Login*', 'txtLogin') ?>
            <?= form_input([
                'name' => 'txtLogin',
                'id' => 'txtLogin',
                'maxlength' => 45,
                'size' => 15,
                'value' => old('txtLogin')
            ]) ?>
            <?php if (isset($validation) && $validation->hasError('txtLogin')): ?>
                <span class="erreurSaisie"><?= esc($validation->getError('txtLogin')) ?></span>
            <?php endif; ?>
        </p>

        <p>
            <?= form_label('Mot de passe*', 'pwdMdp') ?>
            <?= form_password([
                'name' => 'pwdMdp',
                'id' => 'pwdMdp',
                'maxlength' => 45,
                'size' => 15
            ]) ?>
            <?php if (isset($validation) && $validation->hasError('pwdMdp')): ?>
                <span class="erreurSaisie"><?= esc($validation->getError('pwdMdp')) ?></span>
            <?php endif; ?>
        </p>
    </div>

    <div class="piedForm">
        <p>
            <?= form_submit('btnValider', 'Valider', ['class' => 'bouton']) ?>
            <?= form_reset('btnEffacer', 'Effacer', ['class' => 'bouton']) ?>
        </p>
    </div>

    <?= form_close(); ?>

</div>