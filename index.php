<?php
session_start();

require_once('include/twig.php');
require_once('include/fonctions.php');
require_once('include/connexion.php');
require_once('include/cours.php');
require_once('include/article.php');

// Initialisation de Twig
$twig = init_twig();

// Vérifie si une page est spécifiée, sinon redirige vers la page d'accueil
if (!isset($_GET['page'])) {
    header('Location: visiteur.php?page=accueil');
    exit();
}

// Route (page/action/id) pour le contrôleur
$page = $_GET['page'];
$action = isset($_GET['action']) ? $_GET['action'] : 'read';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
// Le tableau de données par défaut
$data = [];

switch ($page) {
	case 'cours':
		Cours::controleur($action, $id, $view, $data);
		break;
	case 'article':
		switch ($action) {
			case 'read':
				$view = 'details_article.twig';
				$article = Article::readOne($id);
				$data = [
					'article' => $article,
				];
				break;
			case 'create':
				$article = new Article();
				$messages = [];
				// Si le formulaire article a été rempli
				if (isset($_POST['form_article'])) {
					$ok = $article->lirePost($messages);
					if ($ok) {
						$article->create();
						header('Location: ?page=article&id=' . $article->id);
					}
				}
				// Sinon affiche le formulaire article
				$view = 'form_article.twig';
				$cours = Cours::readOne($id);
				$data = [
					'cours' => $cours,
					'article' => $article,
					'messages' => $messages
				];
				break;

			case 'update':
				$article = Article::readOne($id);
				$messages = [];
				if (isset($_POST['form_article'])) {
					$ok = $article->lirePost($messages);
					if ($ok) {
						$article->update();
						header('Location: ?page=article&id=' . $article->id);
					}
				}
				$view = 'form_article.twig';
				$data = [
					'article' => $article,
					'messages' => $messages
				];
				break;

			case 'confirm_delete':
				$article = Article::readOne($id);
				$view = 'delete_article.twig';
				$data = [
					'article' => $article,
				];
				break;

			case 'delete':
				$article = Article::readOne($id);
				$id_cours = $article->id_cours;
				$article->delete();
				header('Location: ?page=cours&id=' . $id_cours);
				break;

			case 'exchange':
				$article = Article::readOne($id);
				$id_cours = $article->id_cours;
				$article->exchangeOrder();
				header('Location: ?page=cours&id=' . $id_cours);
				break;
		}
		break;

	case 'login':
		$view = 'login.twig';
		break;

	case 'valid_login':
		if (isset($_POST['login'])) {
			$login = postString('login');
			// Vérification du login admin
			if ($login == "admin24!") {
				$_SESSION['login'] = $login;
				header('Location: admin.php');
			// Vérification du login pro
			} elseif ($login == "pro24!") {
				$_SESSION['login'] = $login;
				header('Location: pro.php');
			} else {
				$view = 'login.twig';
			}
		} else {
			header('Location: visiteur.php');
		}
		break;
	default:
		Cours::controleur($action, $id, $view, $data);
}

// Ajoute les informations de login
echo $twig->render($view, $data);
