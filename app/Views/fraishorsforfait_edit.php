<?php helper('form');
$validation = session('validation');
?>

<h4>Nouvel élément hors forfait</h4>

<?= form_open('gererfrais/creation_fraishorsforfait') ?>

<div class="corpsForm">

    <!-- Date -->
    <p>
        <?= form_label('Date (jj/mm/aaaa) : ', 'txtDateHF') ?>
        <?= form_input([
            'type' => 'date',
            'name' => 'txtDateHF',
            'id' => 'txtDateHF',
            'maxlength' => '10',
            'size' => '10',
            'value' => old('txtDateHF')
        ]) ?>
        <?php if (isset($validation) && $validation->hasError('txtDateHF')): ?>
            <span class="erreurSaisie"><?= esc($validation->getError('txtDateHF')) ?></span>
        <?php endif; ?>
    </p>

    <!-- Libellé -->
    <p>
        <?= form_label('Libellé : ', 'txtLibelleHF') ?>
        <?= form_input([
            'name' => 'txtLibelleHF',
            'id' => 'txtLibelleHF',
            'maxlength' => '256',
            'size' => '50',
            'value' => old('txtLibelleHF')
        ]) ?>
        <?php if (isset($validation) && $validation->hasError('txtLibelleHF')): ?>
            <span class="erreurSaisie"><?= esc($validation->getError('txtLibelleHF')) ?></span>
        <?php endif; ?>
    </p>

    <!-- Montant -->
    <p>
        <?= form_label('Montant : ', 'txtMontantHF') ?>
        <?= form_input([
            'name' => 'txtMontantHF',
            'id' => 'txtMontantHF',
            'maxlength' => '12',
            'size' => '12',
            'value' => old('txtMontantHF')
        ]) ?>
        <?php if (isset($validation) && $validation->hasError('txtMontantHF')): ?>
            <span class="erreurSaisie"><?= esc($validation->getError('txtMontantHF')) ?></span>
        <?php endif; ?>
    </p>

</div>

<div class="piedForm">
    <p>
        <?= form_submit('btnAjouter', 'Ajouter', ['class' => 'bouton']) ?>
        <?= form_reset('btnEffacer', 'Effacer', ['class' => 'bouton']) ?>
    </p>
</div>

<?= form_close() ?>