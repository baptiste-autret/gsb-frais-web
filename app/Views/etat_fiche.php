<div id="etatMontant">
    <div id="etat" class="<?= esc($fiche['idEtat']) ?>">
        <?= esc($fiche['libelle']) ?> depuis le <?= esc($fiche['dateModifFr']) ?>
    </div>
    <div id="montantValide">
        Montant validé
        <div><?= esc($fiche['montantFormate']) ?> &#8364;</div>
    </div>
</div>
