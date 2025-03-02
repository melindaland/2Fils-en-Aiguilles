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
if (isset($_GET['page'])) $page = $_GET['page'];
else $page = '';
if (isset($_GET['action'])) $action = $_GET['action'];
else $action = 'read';
if (isset($_GET['id'])) $id = intval($_GET['id']);
else $id = 0;

// Vérification des droits administrateur
// Charge le login stocké dans la session
$login = '';
if (isset($_SESSION['login'])) $login = $_SESSION['login'];

// Si le login est incorrect : retour à la page d'accueil
if ($login != 'admin24!') {
	header('Location: index.php?login');
}

// Le tableau de données par défaut
$view = '';
$data = [];

switch ($page) {
	// Pour accéder aux cours
	case 'cours':
		Cours::controleurAdmin($action, $id, $view, $data);
		break;
	case 'article':
		Article::controleurAdmin($action, $id, $view, $data);
		break;

	// Pour accéder aux matériels
	case 'materiel':
		Materiel::controleurAdmin($action, $id, $view, $data);
		break;
	case 'objet':
		Objet::controleurAdmin($action, $id, $view, $data);
		break;

	case 'logout':
		unset($_SESSION['login']);
		header('Location: visiteur.php');
		break;
	default:
	$view = 'admin.twig';
    $data = [
        'materiels' => Materiel::readAll(),
        'cours' => Cours::readAll(),
        'login' => $login
    ];
    break;

break;

}

// Ajoute les informations de login
echo $twig->render($view, $data);
