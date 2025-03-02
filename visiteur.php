<?php
session_start();

require_once('include/twig.php');
require_once('include/fonctions.php');
require_once('include/connexion.php');
require_once('include/cours.php');
require_once('include/article.php');
require_once('include/materiel.php');
require_once('include/objet.php');

// Initialisation de Twig
$twig = init_twig();

// Route (page/action/id) pour le contrôleur
$page = isset($_GET['page']) ? $_GET['page'] : '';
$action = isset($_GET['action']) ? $_GET['action'] : 'read';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Vérification du rôle professionnel
$login = isset($_SESSION['login']) ? $_SESSION['login'] : '';

// Récupérer le niveau depuis l'URL (par défaut 'tous' si rien n'est sélectionné)
$niveau = isset($_GET['niveau']) ? $_GET['niveau'] : 'tous';
$categorie = isset($_GET['categorie']) ? $_GET['categorie'] : 'tous';

// Tableau de données par défaut
$view = '';
$data = [];

switch ($page) {
    // Pour accéder aux cours
    case 'cours':
        Cours::controleurVisit($action, $id, $view, $data);
        break;
    case 'article':
        Article::controleurVisit($action, $id, $view, $data);
        break;
    case 'visit_cours':
        // Récupérer la liste des cours (filtrée si un niveau est spécifié)
        if ($niveau == 'tous') {
            $liste_cours = Cours::readAll(); // Tous les cours
        } else {
            $liste_cours = Cours::readByNiveau($niveau); // Cours filtrés par niveau
        }
        $view = 'visit_cours.twig';
        $data = ['liste_cours' => $liste_cours, 'niveau' => $niveau];
        break;
    
    // accéder aux matériels
    case 'materiel':
        Materiel::controleurVisit($action, $id, $view, $data);
        break;
    case 'objet':
        Objet::controleurVisit($action, $id, $view, $data);
        break;
    case 'visit_materiel':
        // Récupérer la liste des matériels (filtrée si une catégorie est spécifié)
        if ($categorie == 'tous') {
            $liste_materiel = Materiel::readAll(); // Tous les materiels
        } else {
            $liste_materiel = Materiel::readByCategorie($categorie); // materiels filtrés par niveau
        }
        $view = 'visit_materiel.twig';
        $data = ['liste_materiel' => $liste_materiel, 'categorie' => $categorie];
        break;
    
    // Vue sur les autres pages du site
    case 'accueil':
        $view = 'accueil.twig';
        break;
    case 'apropos':
        $view = 'apropos.twig';
        break;

    default:
        $view = 'accueil.twig';
        $data = [];
}

// Ajoute les informations de login
echo $twig->render($view, $data);
