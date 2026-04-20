<?php

// Messages provenant de la session
$sessionInfo = session('infos');
$sessionErreurs = session('erreurs');

// Messages provenant des données envoyées par le contrôleur
$dataInfo = $infos ?? [];
$dataErreurs = $erreurs ?? [];

// Fusionner les messages
$allInfos = [];
$allErreurs = [];

if ($sessionInfo) {
    if (is_array($sessionInfo)) {
        $allInfos = array_merge($allInfos, $sessionInfo);
    } else {
        $allInfos[] = $sessionInfo;
    }
}

if ($sessionErreurs) {
    if (is_array($sessionErreurs)) {
        $allErreurs = array_merge($allErreurs, $sessionErreurs);
    } else {
        $allErreurs[] = $sessionErreurs;
    }
}

if (!empty($dataInfo)) {
    $allInfos = array_merge($allInfos, (array)$dataInfo);
}

if (!empty($dataErreurs)) {
    $allErreurs = array_merge($allErreurs, (array)$dataErreurs);
}
?>

<!-- Affichage des notifications -->
<?php if (!empty($allInfos)): ?>
    <div class="info">
        <ul>
            <?php foreach ($allInfos as $inf): ?>
                <li><?= esc($inf) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<!-- Affichage des erreurs -->
<?php if (!empty($allErreurs)): ?>
    <div class="erreur">
        <ul>
            <?php foreach ($allErreurs as $err): ?>
                <li><?= esc($err) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>