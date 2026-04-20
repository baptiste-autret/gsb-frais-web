<h4>Descriptif des éléments hors forfait - <?= esc($fiche['nbJustificatifs']) ?> justificatifs reçus -</h4>

<table class="listeLegere">
    <tr>
        <th class="date">Date</th>
        <th class="libelle">Libellé</th>
        <th class="montant">Montant</th>                
    </tr>

    <?php foreach ($fraishorsforfait as $unfhf): ?>  
        <tr>
            <td><?= esc($unfhf['dateFraisFR']) ?></td>
            <td class="libelle"><?= esc($unfhf['libelle']) ?></td>
            <td class="montant"><?= esc($unfhf['montantFormate']) ?></td>
        </tr>
    <?php endforeach; ?>
</table>
<br>
