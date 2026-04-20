<?php

namespace App\Controllers;

use App\Models\GsbModel;
use App\Libraries\Gsb_lib;

class Etatfrais extends BaseController
{
    private $annee_mois;
    private $id_utilisateur;
    private $id_fiche;
    protected $gsb_lib;
    protected $gsb_model;

    public function __construct()
    {
        helper(['url', 'form']);

        $this->gsb_lib = new Gsb_lib();
        $this->gsb_model = new GsbModel();

        $this->id_utilisateur = session()->get('idUtilisateur');
    }

    /** Méthode par défaut */
    public function index()
    {
        // Vérifie si l’utilisateur est connecté
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/');
        }

        $this->annee_mois = null;
        $this->id_fiche = null;
        return $this->commun();
    }

    /** Sélection d’un mois depuis la liste déroulante */
    public function selectionner_mois()
    {
        $this->annee_mois = $this->request->getPost('lstMois');
        return $this->commun();
    }

    /** Traitement commun pour affichage de la page */
    private function commun()
    {
        $data['listemenus'] = $this->gsb_lib->get_menus(session()->get('role'));

        echo view('structures/page_entete');
        echo view('structures/messages');
        echo view('sommaire', $data);

        $data['titre'] = "Mes fiches de frais";
        echo view('structures/contenu_entete', $data);

        // Récupération des couples annee/mois disponibles
        $les_anneemois = $this->gsb_model->get_les_mois_disponibles($this->id_utilisateur);
        if (count($les_anneemois) == 0) {
            return redirect()->back()->withInput()->with('erreurs', "Aucune fiche de frais n'a été saisie pour ce visiteur");
        } else {
            if (!isset($this->annee_mois)) {
                $this->annee_mois = $les_anneemois[0]['anneemois'];
            }

            // Liste déroulante
            $options = [];
            foreach ($les_anneemois as $une_anneemois) {
                $libelle = $this->gsb_lib->get_nom_mois($une_anneemois['mois']) . " " . $une_anneemois['annee'];
                $options[$une_anneemois['anneemois']] = $libelle;
            }
            $data['lst_contenu'] = $options;
            $data['lst_select'] = $this->annee_mois;
            $data['lst_action'] = 'etatfrais/mois';
            $data['lst_id'] = 'lstMois';
            $data['lst_label'] = 'Mois';
            $data['sc_titre'] = 'Mois à sélectionner :';
            echo view('structures/souscontenu_entete', $data);
            echo view('liste_deroulante', $data);
            echo view('structures/souscontenu_pied');

            // Fiche sélectionnée
            $num_annee = $this->gsb_lib->get_annee_from_anneemois($this->annee_mois);
            $num_mois = $this->gsb_lib->get_mois_from_anneemois($this->annee_mois);
            $date_titre = $this->gsb_lib->get_nom_mois($num_mois) . " " . $num_annee;

            $data['sc_titre'] = 'Fiche de frais du mois de ' . $date_titre . ' :';

            echo view('structures/souscontenu_entete', $data);

            // Zone état
            $fiche = $this->gsb_model->get_id_ficheFrais($this->id_utilisateur, $num_annee, $num_mois);
            $this->id_fiche = $fiche['idFiche'];
            $detailFiche = $this->gsb_model->get_les_infos_ficheFrais($this->id_fiche);
            $detailFiche['dateModifFr'] = $this->gsb_lib->date_vers_francais($detailFiche['dateModif']);
            $detailFiche['montantFormate'] = $this->gsb_lib->format_montant($detailFiche['montantValide']);
            $data['fiche'] = $detailFiche;
            $data['idFiche'] = $this->id_fiche;
            echo view('etat_fiche', $data);

            // Frais forfait
            $data['fraisforfait'] = $this->gsb_model->get_les_frais_forfait($this->id_fiche);
            echo view('fraisforfait_table', $data);

            // Frais hors forfait
            $listeFraisHorsForfait = $this->gsb_model->get_les_frais_hors_forfait($this->id_fiche);
            foreach ($listeFraisHorsForfait as &$fraisHF) {
                $fraisHF['dateFraisFR'] = $this->gsb_lib->date_vers_francais($fraisHF['dateFrais']);
                $fraisHF['montantFormate'] = $this->gsb_lib->format_montant($fraisHF['montant']);
            }
            unset($fraisHF);
            $data['fraishorsforfait'] = $listeFraisHorsForfait;
            echo view('fraishorsforfait_table', $data);

            echo view('structures/souscontenu_pied');

            // Pied de page
            echo view('structures/page_pied');
        }
    }
}
