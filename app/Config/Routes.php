<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Connexion::login'); 
$routes->get('connexion', 'Connexion::login');
$routes->post('/connexion/valider', 'Connexion::valider');  
$routes->get('/connexion/deconnexion', 'Connexion::deconnexion');  

$routes->get('modificationMdp', 'ModificationMdp::modification_normale');  

$routes->get('/accueil', 'Accueil::index');  

$routes->get('gererfrais', 'GererFrais::index');
$routes->post('gererfrais/maj_fraisforfait', 'GererFrais::valider_maj_fraisforfait');
$routes->post('gererfrais/creation_fraishorsforfait', 'GererFrais::valider_creation_fraishorsforfait');
$routes->get('gererfrais/supp_fraishorsforfait/(:num)', 'GererFrais::supprimer_fraishorsforfait/$1');

$routes->get('modificationMdp_no', 'ModificationMdp::modification_normale/false');
$routes->get('modificationMdp_o', 'ModificationMdp::modification_normale/true');
$routes->post('modificationMdp/valider', 'ModificationMdp::valider');

$routes->get('etatfrais', 'EtatFrais::index');
$routes->post('etatfrais/mois', 'EtatFrais::selectionner_mois');

$routes->get('suivi', 'Suivi::index');
$routes->post('suivi/infos', 'Suivi::selectionner_informations');

$routes->get('suivi/rembourser', 'Suivi::rembourser');

$routes->get('validation', 'Validation::index');
$routes->post('validation/infos', 'Validation::selectionner_informations');