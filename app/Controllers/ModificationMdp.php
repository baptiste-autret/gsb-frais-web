<?php

// to-do: seulement pour les visiteurs médicale ou comptable 

namespace App\Controllers;

use App\Libraries\Gsb_lib;
use App\Models\GsbModel;

class ModificationMdp extends BaseController
{
    protected $gsb_model;
    protected $gsbLib;

    public function __construct()
    {
        helper(['url', 'form']); // helpers URL et form

        $this->gsb_model = new GsbModel();
        $this->gsbLib = new Gsb_lib();
    }

    /**
     * Affiche l’écran de connexion
     */
    public function modification_normale($obligation)
    {
        if ($obligation == "true") {
            return view('structures/page_entete')
                . view('structures/messages')
                . view('modifierMDP')
                . view('structures/page_pied');
        } else {
            $data['listemenus'] = $this->gsbLib->get_menus(session()->get('role'));

            return view('structures/page_entete')
                . view('structures/messages')
                . view('sommaire', $data)
                . view('modifierMDP')
                . view('structures/page_pied');
        }
    }

    private function verification_mdp($mdp, $nvMdp, $confirmerNvMdp)
    {
        if ($mdp === $nvMdp) {
            return "mdp_identique";
        }

        if ($nvMdp !== $confirmerNvMdp) {
            return "nv_mdp_différent";
        }

        if (strlen($nvMdp) < 12) {
            return "mdp_trop_court";
        }

        return true;
    }

    /**
     * Valide la saisie du formulaire de connexion
     */
    public function valider()
    {
        $reglesSaisie = [
            'txtMdpActuel' => [
                'rules' => 'required|min_length[3]',
                'label' => 'Mot de passe actuel'
            ],
            'txtNvMdp' => [
                'rules' => 'required|min_length[3]',
                'label' => 'Nouveau mot de passe'
            ],
            'txtConfirmerNvMdp' => [
                'rules' => 'required|min_length[3]',
                'label' => 'Confirmer mot de passe'
            ]
        ];

        if (!$this->validate($reglesSaisie)) {
            // Redirection avec input et validation
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $login = session()->get('login');
        $mdp = $this->request->getPost('txtMdpActuel');

        $nvMdp = $this->request->getPost('txtNvMdp');


        $utilisateur = $this->gsb_model->get_infos_utilisateur($login, $mdp);

        if ($utilisateur) {

            $resultat = $this->verification_mdp($mdp, $nvMdp, $this->request->getPost('txtConfirmerNvMdp'));

            if ($resultat === true) {
                $this->gsb_model->update_mdp($login, $nvMdp);

                return redirect()->to('/accueil')->with('infos', 'Le mot de passe a été changé avec succès');
            } elseif ($resultat === "mdp_identique") {
                return redirect()->back()
                    ->withInput()
                    ->with('erreurs', "Votre nouveau mot de passe ne peut pas être le même que l'ancien.");
            } elseif ($resultat === "nv_mdp_différent") {
                return redirect()->back()
                    ->withInput()
                    ->with('erreurs', "Le nouveau mot de passe et la confirmation ne correspondent pas.");
            } else {
                return redirect()->back()
                    ->withInput()
                    ->with('erreurs', "Le mot de passe doit contenir au moins 12 caractères.");
            }
        } else {

            return redirect()->back()
                ->withInput()
                ->with('erreurs', "Mot de passe actuel incorrect.");
        }
    }
}
