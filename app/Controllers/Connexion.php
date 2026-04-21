<?php

namespace App\Controllers;

use App\Models\GsbModel;

class Connexion extends BaseController
{
    protected $gsb_model;

    public function __construct()
    {
        helper(['url', 'form']); // helpers URL et form

        $this->gsb_model = new GsbModel();
    }

    /**
     * Affiche l’écran de connexion
     */
    public function login()
    {
        return view('structures/page_entete')
            . view('structures/messages')
            . view('connexion')
            . view('structures/page_pied');
    }

    /**
     * Valide la saisie du formulaire de connexion
     */
    public function valider()
    {
        $reglesSaisie = [
            'txtLogin' => [
                'rules' => 'required|min_length[3]',
                'label' => 'Login'
            ],
            'pwdMdp' => [
                'rules' => 'required|min_length[3]',
                'label' => 'Mot de passe'
            ]
        ];

        if (!$this->validate($reglesSaisie)) {
            // Redirection avec input et validation
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $login = $this->request->getPost(index: 'txtLogin');
        $mdp = $this->request->getPost('pwdMdp');

        $utilisateur = $this->gsb_model->get_infos_utilisateur($login, $mdp);



        if ($utilisateur) {

            session()->set([
                'idUtilisateur' => $utilisateur['idutilisateur'],
                'nom' => $utilisateur['nom'],
                'prenom' => $utilisateur['prenom'],
                'libellerole' => $utilisateur['libellerole'],
                'login' => $utilisateur['login'],
                'role' => $utilisateur['idrole']
            ]);

            $dernierChangementMdp = date_create($this->gsb_model->get_date_dernier_changement_mdp($utilisateur['login']));
            $currentDate = date_create(date('Y-m-d'));
            $difference_date = date_diff($dernierChangementMdp, $currentDate);

            if ($difference_date->format('%m') >= 6) {
                return redirect()->to('modificationMdp_o')->with('erreurs', 'Votre mot de passe a expiré. Veuillez le modifier.');
            }

            session()->set([
                'isLoggedIn' => true
            ]);
            return redirect()->to('/accueil');
        }

        return redirect()->back()->withInput()->with('erreurs', 'Login ou mot de passe incorrect');
    }

    /**
     * Déconnecte l’utilisateur
     */
    public function deconnexion()
    {
        session()->remove(['idutilisateur', 'nom', 'prenom', 'role', 'libellerole', 'isLoggedIn']);
        return redirect()->to('/')->with('infos', 'Vous avez bien été déconnecté.');
    }
}
