<?php
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

    /** Méthode par défaut */
    public function index()
    {
        // Vérifie si l’utilisateur est connecté
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/');
        }
    }

    /**
     * Affiche l’écran de connexion
     */
    public function modification_normale()
    {
        $data['listemenus'] = $this->gsbLib->get_menus(session()->get('role'));

        return view('structures/page_entete')
            . view('structures/messages')
            . view('sommaire', $data)
            . view('modifierMDP')
            . view('structures/page_pied');
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

            $this->gsb_model->update_mdp($login, $nvMdp);
            
            session()->set([
                'idUtilisateur' => $utilisateur['idutilisateur'],
                'login' => $utilisateur['login'],
                'nom' => $utilisateur['nom'],
                'prenom' => $utilisateur['prenom'],
                'role' => $utilisateur['idrole'],
                'libellerole' => $utilisateur['libellerole'],
                'isLoggedIn' => true
            ]);

            return redirect()->to('/accueil')->withInput()->with('validation', 'Le mot de passe a été changé avec succès');
        }

        return redirect()->back()->withInput()->with('erreurs', "Votre mot de passe n'est pas assez fort ou est le même que le précédent. Veuillez réessayer.");
    }
}