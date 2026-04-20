<?php helper('form');?>

<div id="sousContenu">
    <?php
    echo form_open(site_url('modificationmdp/valider'));
    ?>
    <div class="corpsForm">

        <p>
            <?= form_label('Mot de passe actuel*', 'mdpActuel') ?>
            <?= form_password([
                'name' => 'txtMdpActuel',
                'id' => 'txtMdpActuel',
                'maxlength' => 45,
                'size' => 15,
                'value' => old('txtMdpActuel')
            ]) ?>
            <?php if (isset($validation) && $validation->hasError('txtMdpActuel')): ?>
                <span class="erreurSaisie"><?= esc($validation->getError('txtMdpActuel')) ?></span>
            <?php endif; ?>
        </p>

        <p>
            <?= form_label('Nouveau mot de passe*', 'nvMdp') ?>
            <?= form_password([
                'name' => 'txtNvMdp',
                'id' => 'txtNvMdp',
                'maxlength' => 45,
                'size' => 15
            ]) ?>
            <?php if (isset($validation) && $validation->hasError('txtNvMdp')): ?>
                <span class="erreurSaisie"><?= esc($validation->getError('txtNvMdp')) ?></span>
            <?php endif; ?>
        </p>

        <p>
            <?= form_label('Confirmer mot de passe*', 'confirmerNvMdp') ?>
            <?= form_password([
                'name' => 'txtConfirmerNvMdp',
                'id' => 'txtConfirmerNvMdp',
                'maxlength' => 45,
                'size' => 15
            ]) ?>
            <?php if (isset($validation) && $validation->hasError('txtConfirmerNvMdp')): ?>
                <span class="erreurSaisie"><?= esc($validation->getError('txtConfirmerNvMdp')) ?></span>
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