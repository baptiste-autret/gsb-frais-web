<?php

namespace App\Controllers;

use SimpleXMLElement;
use App\Libraries\Gsb_lib;

class Accueil extends BaseController
{
    protected $gsbLib;
    public function __construct()
    {
        // On charge le helper URL et HTML
        helper(['url', 'html']);
        $this->gsbLib = new Gsb_lib();
    }

    /** Méthode par défaut */
    public function index()
    {
        // Vérifie si l’utilisateur est connecté
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/');
        }

        $data['titre'] = "Bienvenue sur l'intranet GSB";
        $data['actualite'] = $this->getFluxRss();
        $data['listemenus'] = $this->gsbLib->get_menus(session()->get('role'));

        return view('structures/page_entete')
            . view('structures/messages')
            . view('sommaire', $data)
            . view('structures/contenu_entete', $data)
            . view('actualites', $data)
            . view('structures/page_pied');
    }

    public function getFluxRss()
    {
        $url = 'https://www.santemagazine.fr/feeds/rss/sante';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // temporaire si pas openssl
        $execute = curl_exec($ch);
        curl_close($ch);

        $rss = simplexml_load_string($execute);

        // Vérifie si le chargement du flux a réussi
        if ($rss === false) {
            return array("error" => "Impossible de charger le flux RSS.");
        }

        $actualites = [];

        foreach ($rss->channel->item as $item) {
            $actualites[] = [
                'titreArticle' => (string) $item->title,
                'description' => (string) $item->description,
                'lien' => (string) $item->link,
                'date' => (string) $item->pubDate,
                'image' => (string) $item->enclosure['url']
            ];
        }
        return $actualites;
    }
}
