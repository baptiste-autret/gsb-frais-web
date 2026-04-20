<h1 class="text-center my-5">Actualités Santé Magazine</h1>

<div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">

        <?php $isFirst = true; ?>
        <?php foreach ($actualite as $actu_actuelle) { ?>
            <div class="carousel-item <?= $isFirst ? 'active' : '' ?>">
                <div class="d-flex justify-content-center">
                    <div class="card shadow-sm" style="max-width: 700px;">
                        <img src="<?= $actu_actuelle['image'] ?>" class="card-img-top" alt="Image de l'article">
                        <div class="card-body text-center">
                            <h5 class="card-title text-primary"><?= $actu_actuelle['titreArticle'] ?></h5>
                            <p class="card-text"><?= $actu_actuelle['description'] ?></p>
                            <p class="card-text"><small class="text-muted">Publié le : <?= $actu_actuelle['date'] ?></small></p>
                            <a href="<?= $actu_actuelle['lien'] ?>" class="btn btn-info text-white" target="_blank">Lire l'article</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php $isFirst = false; ?>
        <?php } ?>
    </div>

    <!-- Bouton précédent -->
    <button class="carousel-control-prev me-5" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
        <span class="carousel-control-prev-icon bg-dark rounded-circle p-3" aria-hidden="true"></span>
        <span class="visually-hidden">Précédent</span>
    </button>

    <!-- Bouton suivant -->
    <button class="carousel-control-next ms-5" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
        <span class="carousel-control-next-icon bg-dark rounded-circle p-3" aria-hidden="true"></span>
        <span class="visually-hidden">Suivant</span>
    </button>

</div>