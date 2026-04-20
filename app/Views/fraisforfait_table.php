<h4>Eléments forfaitisés :</h4>
<table class="listeLegere">
    <tr>
        <?php foreach ($fraisforfait as $unff): ?>
            <th><?= esc($unff['libelle']) ?></th>
        <?php endforeach; ?>
    </tr>
    <tr>
        <?php foreach ($fraisforfait as $unff): ?>
            <td class="qteForfait"><?= esc($unff['quantite']) ?></td>
        <?php endforeach; ?>
    </tr>
</table>