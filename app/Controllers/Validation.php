<?php

namespace App\Controllers;
use App\Libraries\Gsb_lib;
use App\Models\GsbModel;

class Validation extends BaseController
{    
    protected $gsbLib;
    protected $gsb_model;
    public function __construct()
    {
        // On charge le helper URL et HTML
        helper(['url', 'html']);
        helper(['url', 'form']);

        $this->gsbLib = new Gsb_lib();
        $this->gsb_model = new GsbModel();
    }

    public function index()
    {
        // Vérifie si l’utilisateur est connecté
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/');
        }
        session()->remove(['id_utilisateur', 'id_fiche']);
        return $this->commun();
    }

    public function selectionner_informations()
    {
        if ($this->request->getPost('lstUtilisateur') != null) {
            session()->set('id_utilisateur', $this->request->getPost('lstUtilisateur'));
        }
        if ($this->request->getPost('lstFicheFrais') != null) {
            session()->set('id_fiche', $this->request->getPost('lstFicheFrais'));
        }

        return $this->commun();
    }

    private function commun()
    {
        $data['listemenus'] = $this->gsbLib->get_menus(session()->get('role'));

        echo view('structures/page_entete');
        echo view('structures/messages');
        echo view('sommaire', $data);

        $data['titre'] = "Validation de fiches de frais";
        echo view('structures/contenu_entete', $data);

        $les_utilisateurs = $this->gsb_model->get_tous_les_utilisateurs();
        $options_utilisateur = ["Sélectionner un utilisateur"];
        foreach ($les_utilisateurs as $un_utilisateur) {
            $libelle = $un_utilisateur['idutilisateur'] . " - " . $un_utilisateur['prenom'] . " " . $un_utilisateur['nom'];
            $options_utilisateur[$un_utilisateur['idutilisateur']] = $libelle;
        }

        $data['lst_contenu'] = $options_utilisateur;
        $data['lst_select'] = session(val: 'id_utilisateur');
        $data['lst_action'] = 'validation/infos';
        $data['lst_id'] = 'lstUtilisateur';
        $data['lst_label'] = 'Visiteurs';
        $data['sc_titre'] = 'Visiteur à sélectionner :';
        echo view('structures/souscontenu_entete', $data);
        echo view('liste_deroulante', $data);
        echo view('structures/souscontenu_pied');

        $les_fiches_frais = $this->gsb_model->get_fiches_frais(session('id_utilisateur'), "CL");
        $options_fiche_frais = ["Sélectionner une fiche de frais à valider pour l'utilisateur choisi"];
        foreach ($les_fiches_frais as $une_fiche_frais) {
            $libelle = $une_fiche_frais['idFiche'] . " - Fiche de " . $this->gsbLib->get_nom_mois($une_fiche_frais['mois']) . " " . $une_fiche_frais['annee'];
            $options_fiche_frais[$une_fiche_frais['idFiche']] = $libelle;
        }

        $data['lst_contenu'] = $options_fiche_frais;
        $data['lst_select'] = session('id_fiche');
        $data['lst_action'] = 'validation/infos';
        $data['lst_id'] = 'lstFicheFrais';
        $data['lst_label'] = 'Fiches de frais';
        $data['sc_titre'] = 'Fiche frais à sélectionner :';
        echo view('structures/souscontenu_entete', $data);
        echo view('liste_deroulante', $data);
        echo view('structures/souscontenu_pied');


        $detailFiche = $this->gsb_model->get_les_infos_ficheFrais(session('id_fiche'));

        print_r($detailFiche);

        echo view('structures/page_pied');
    }
}