<?php

namespace App\Controllers;

use App\Models\GsbModel;
use App\Libraries\Gsb_lib;

class Gererfrais extends BaseController
{
    private $id_annee;
    private $id_mois;
    private $id_fiche;
    private $id_utilisateur;
    protected $gsb_lib;
    protected $gsb_model;

    public function __construct()
    {
        helper(['url', 'form', 'html']);

        $this->gsb_lib = new Gsb_lib();
        $this->gsb_model = new GsbModel();

        $anneemois = $this->gsb_lib->get_annee_mois();
        $this->id_annee = $this->gsb_lib->get_annee_from_anneemois($anneemois);
        $this->id_mois = $this->gsb_lib->get_mois_from_anneemois($anneemois);
        $this->id_utilisateur = session()->get('idUtilisateur');

        $fiche = $this->gsb_model->get_id_ficheFrais($this->id_utilisateur, $this->id_annee, $this->id_mois);
        if ($fiche != null && !empty($fiche['idFiche'])) {
            $this->id_fiche = $fiche['idFiche'];
        }
    }

    /** Méthode par défaut */
    public function index()
    {
        // Vérifie si l’utilisateur est connecté
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/');
        }

        $data['infos'] = [];
        if ($this->gsb_model->est_premier_frais_mois($this->id_utilisateur, $this->id_annee, $this->id_mois)) {
            $resultatOk = $this->gsb_model->cree_nouvelles_lignes_frais($this->id_utilisateur, $this->id_annee, $this->id_mois);
            if ($resultatOk) {
                $fiche = $this->gsb_model->get_id_ficheFrais($this->id_utilisateur, $this->id_annee, $this->id_mois);
                if ($fiche != null && !empty($fiche['idFiche'])) {
                    $this->id_fiche = $fiche['idFiche'];
                }
                $data['infos'][] = "Une nouvelle fiche de frais vient d'être créée pour le mois en cours";
            } else {
                $data['erreurs'][] = "Impossible de créer une nouvelle fiche de frais";
            }
        }
        return $this->commun($data);
    }

    /** Validation des frais forfaitaires */
    public function valider_maj_fraisforfait()
    {
        $lesFrais = $this->request->getPost('lesFrais');
        $resultatOk = $this->gsb_model->maj_frais_forfait($this->id_fiche, $lesFrais);
        if ($resultatOk) {
            $data['infos'][] = "Les modifications des frais forfaitaires ont bien été effectuées";
        } else {
            $data['erreurs'][] = "Prolème de modifications des frais forfaitaires";
        }
        return $this->commun($data);
    }

    /** Création d'un nouveau frais hors forfait */
    public function valider_creation_fraishorsforfait()
    {
        $reglesSaisie = [
            'txtDateHF' => [
                'rules' => 'required',
                'label' => 'Date'
            ],
            'txtLibelleHF' => [
                'rules' => 'required|min_length[5]',
                'label' => 'Libellé'
            ],
            'txtMontantHF' => [
                'rules' => 'required|numeric',
                'label' => 'Montant'
            ]
        ];

        if (!$this->validate($reglesSaisie)) {
            // Redirection avec input et validation
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $dateFrais = $this->request->getPost("txtDateHF");
        $libelle = $this->request->getPost("txtLibelleHF");
        $montant = $this->request->getPost("txtMontantHF");

        $resultatOk = $this->gsb_model->creer_nouveau_frais_hors_forfait($this->id_fiche, $libelle, $dateFrais, $montant);
        if ($resultatOk) {
            $data['infos'][] = "La création d'un frais hors-forfait a bien été effectuée";
        } else {
            $data['erreurs'][] = "Problème lors de la création d'un frais hors-forfait";
        }

        return $this->commun($data);
    }

    /** Suppression d'un frais hors forfait */
    public function supprimer_fraishorsforfait($id_fraishorsforfait)
    {
        $suppOk = $this->gsb_model->supprimer_frais_hors_forfait($id_fraishorsforfait);
        if ($suppOk) {
            $data['infos'][] = "La suppression du frais hors-forfait a bien été effectuée";
        } else {
            $data['erreurs'][] = "Problème lors de la suppression du frais hors-forfait";
        }
        return $this->commun($data);
    }

    private function commun($data)
    {
        $data['listemenus'] = $this->gsb_lib->get_menus(session()->get('role'));
        echo view('structures/page_entete');
        echo view('structures/messages', $data);
        echo view('sommaire');

        $date_titre = $this->gsb_lib->get_nom_mois($this->id_mois) . " " . $this->id_annee;

        $data['titre'] = "Renseigner ma fiche de frais du mois de " . $date_titre;

        echo view('structures/contenu_entete', $data);

        // Frais forfaitisés
        $data['sc_titre'] = 'Eléments forfaitisés';
        echo view('structures/souscontenu_entete', $data);

        $data['fraisforfait'] = $this->gsb_model->get_les_frais_forfait($this->id_fiche);
        echo view('fraisforfait_edit', $data);
        echo view('structures/souscontenu_pied');

        // Frais hors forfait
        $data['sc_titre'] = 'Eléments hors forfait';
        echo view('structures/souscontenu_entete', $data);
        $listeFraisHorsForfait = $this->gsb_model->get_les_frais_hors_forfait($this->id_fiche);
        foreach ($listeFraisHorsForfait as &$fraisHF) {
            $fraisHF['dateFraisFR'] = $this->gsb_lib->date_vers_francais($fraisHF['dateFrais']);
            $fraisHF['montantFormate'] = $this->gsb_lib->format_montant($fraisHF['montant']);
        }
        unset($fraisHF);
        $data['fraishorsforfait'] = $listeFraisHorsForfait;

        echo view('fraishorsforfait_table_sup', $data);
        echo view('fraishorsforfait_edit');
        echo view('structures/souscontenu_pied');

        echo view('structures/page_pied');
    }
}
