<?php $session = session(); ?>

<div id="menuGauche">
    <div id="infosUtil">
        <div id="user">
            <img src="<?= base_url('assets/images/UserIcon.png') ?>" alt="Utilisateur" />
        </div>
        <div id="infos">
            <h2><?= esc($session->get('prenom') . ' ' . $session->get('nom')) ?></h2>
            <h3><?= esc($session->get('libellerole')) ?></h3>  
        </div>
        <ul class="menuList">
            <li>
                <?= anchor('modificationMdp_no', 'Modifier le mot de passe', ['title' => 'Modifier le mot de passe']) ?>
                <?= anchor('connexion/deconnexion', 'Déconnexion', ['title' => 'Se déconnecter']) ?>
            </li>
        </ul>
    </div>  

    <ul id="menuPrincipal" class="menuList">
			<?php foreach ($listemenus as $menu) { ?>
            	<li>
            		<?= anchor($menu["route"], $menu["texte"]) ?>
        		</li>
            <?php } ?>
    </ul>
</div>