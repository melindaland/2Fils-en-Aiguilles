<?php

use phpDocumentor\Reflection\DocBlock\Tags\Var_;

require_once('fonctions.php');

class Article
{
	public $id;
	public $ordre;
	public $titre;
	public $redacteur;
	public $accroche;
	public $image;
	public $id_cours;

	// Le constructeur corrige les données récupérées de la BDD
	// Ici convertie les clés et l'ordre (pour le tri) en entier
	function __construct()
	{
		$this->id = intval($this->id);
		$this->ordre = intval($this->ordre);
		$this->id_cours = intval($this->id_cours);
	}

	static function readAll()
	{
		$sql = 'SELECT * FROM article ORDER BY ordre';
		$pdo = connexion();
		$query = $pdo->prepare($sql);
		$query->execute();
		return $query->fetchAll(PDO::FETCH_CLASS, 'Article');
	}

	static function readOne($id)
	{
		$sql = 'SELECT * FROM article WHERE id = :id';
		$pdo = connexion();
		$query = $pdo->prepare($sql);
		$query->bindValue(':id', $id, PDO::PARAM_INT);
		$query->execute();
		return $query->fetchObject('Article');
	}

	// Récupère les articles d'un thème
	static function readAllByCours($id)
	{
		$sql = 'SELECT * FROM article WHERE id_cours = :id ORDER BY ordre';
		$pdo = connexion();
		$query = $pdo->prepare($sql);
		$query->bindValue(':id', $id, PDO::PARAM_INT);
		$query->execute();
		return $query->fetchAll(PDO::FETCH_CLASS, 'Article');
	}

	// Récupère l'ordre maximal des articles d'un thème
	static function readOrderMax($id)
	{
		$sql = 'SELECT max(ordre) AS maximum FROM article WHERE id_cours = :id';
		$pdo = connexion();
		$query = $pdo->prepare($sql);
		$query->bindValue(':id', $id, PDO::PARAM_INT);
		$query->execute();
		$objet = $query->fetchObject();
		return intval($objet->maximum);
	}

	// Echange l'ordre de deux articles
	function exchangeOrder()
	{
		// Recherche l'article précédent (dans le même thème)
		// C'est l'article le plus grand, parmi les articles d'ordre inférieur

		// étape 1 : cherche les articles du même thème ayant un ordre inférieur
		$sql = 'SELECT * FROM article
				WHERE id_cours = :id_cours AND ordre < :ordre ORDER BY ordre DESC';
		$pdo = connexion();
		$query = $pdo->prepare($sql);
		$query->bindValue(':id_cours', $this->id_cours, PDO::PARAM_INT);
		$query->bindValue(':ordre', $this->ordre, PDO::PARAM_INT);
		$query->execute();

		// étape 2 : les articles sont triés par ordre décroissant
		// donc le premier article est le plus grand des plus petits, donc le précédent
		$before = $query->fetchObject('Article');

		// Si le précédent existe (l'article courant n'est pas le premier)
		if ($before) {
			// Échange les valeurs d'ordre et enregistre dans la BDD
			$tmp = $this->ordre;
			$this->ordre = $before->ordre;
			$this->update();
			$before->ordre = $tmp;
			$before->update();
		}
	}

	function create()
	{
		// Récupère l'ordre maximum pour créer l'article en dernière position
		$maximum = self::readOrderMax($this->id_cours);
		$this->ordre = $maximum + 1;

		$sql = "INSERT INTO article (ordre, titre, accroche, redacteur, image, id_cours)
				VALUES (:ordre, :titre, :accroche, :redacteur, :image, :id_cours)";
		$pdo = connexion();
		$query = $pdo->prepare($sql);
		$query->bindValue(':ordre', $this->ordre, PDO::PARAM_INT);
		$query->bindValue(':titre', $this->titre, PDO::PARAM_STR);
		$query->bindValue(':accroche', $this->accroche, PDO::PARAM_STR);
		$query->bindValue(':redacteur', $this->redacteur, PDO::PARAM_STR);
		$query->bindValue(':image', $this->image, PDO::PARAM_STR);
		$query->bindValue(':id_cours', $this->id_cours, PDO::PARAM_INT);
		$query->execute();
		$this->id = $pdo->lastInsertId();
	}

	function update()
	{
		$sql = "UPDATE article
				SET ordre=:ordre, titre=:titre, accroche=:accroche, redacteur=:redacteur, image=:image
				WHERE id=:id";
		$pdo = connexion();
		$query = $pdo->prepare($sql);
		$query->bindValue(':id', $this->id, PDO::PARAM_INT);
		$query->bindValue(':ordre', $this->ordre, PDO::PARAM_INT);
		$query->bindValue(':titre', $this->titre, PDO::PARAM_STR);
		$query->bindValue(':accroche', $this->accroche, PDO::PARAM_STR);
		$query->bindValue(':redacteur', $this->redacteur, PDO::PARAM_STR);
		$query->bindValue(':image', $this->image, PDO::PARAM_STR);
		$query->execute();
	}

	function delete()
	{
		// Suppression du fichier lié
		if (!empty($this->image)) unlink('upload/' . $this->image);

		// Suppression de l'article
		$sql = "DELETE FROM article WHERE id=:id";
		$pdo = connexion();
		$query = $pdo->prepare($sql);
		$query->bindValue(':id', $this->id, PDO::PARAM_INT);
		$query->execute();
	}

	function chargePOST()
	{
		$this->id = postInt('id');
		$this->ordre = postInt('ordre');
		$this->titre = postString('titre');
		$this->redacteur = postString('redacteur');
		$this->accroche = postString('accroche');
		$this->image = postString('old-image');
		$this->id_cours = postInt('id_cours');

		// Récupère les informations sur le fichier uploadés si il existe
		$image = chargeFILE();
		if (!empty($image)) {
			// Supprime l'ancienne image si update
			unlink('upload/' . $this->image);
			$this->image = $image;
		}
	}


	static function controleur($action, $id, &$view, &$data)
	{
		switch ($action) {
			default:
				$view = 'visit_article.twig';
				$data = [
					'article' => Article::readOne($id)
				];
				break;
		}
	}

	// Ajout de la méthode controleur pour l'administrateur
	static function controleurAdmin($action, $id, &$view, &$data)
	{
		switch ($action) {
			case 'read':
				// Liste des articles d'un thème ($id)
				if ($id > 0) {
					$view = 'article/detail_article.twig';
					$data = [
						'article' => Article::readOne($id),
					];
				} else {
					header('Location: admin.php?page=cours');
				}
				break;
			case 'new':
				$view = "article/form_article.twig";
				$data = ['id_cours' => $id];
				break;
			case 'create':
				$article = new Article();
				$article->chargePOST();
				$article->create();
				header('Location: admin.php?page=cours&id=' . $article->id_cours);
				break;
			case 'edit':
				$view = "article/edit_article.twig";
				$data = ['article' => Article::readOne($id)];
				break;
			case 'update':
				$article = new Article();
				$article->chargePOST();
				$article->update();
				header('Location: admin.php?page=cours&id=' . $article->id_cours);
				break;
			case 'delete':
				$article = Article::readOne($id);
				$article->delete();
				header('Location: admin.php?page=cours&id=' . $article->id_cours);
				break;
			case 'exchange':
				$article = Article::readOne($id);
				$article->exchangeOrder();
				$view = 'cours/detail_cours.twig';
				header('Location: admin.php?page=cours&id=' . $article->id_cours);
				break;
			default:
				$view = 'cours/liste_cours.twig';
				$data = [
					'liste_cours' => Article::readAll()
				];
				break;
		}
	}



    // Ajout de la méthode controleur pour le professionnel
    static function controleurPro($action, $id, &$view, &$data)
	{
		switch ($action) {
			case 'read':
				if ($id > 0) {
					$view = 'article/detail_article_pro.twig';
					$data = [
						'article' => Article::readOne($id),
					];
				} else {
					header('Location: pro.php?page=cours');
				}
				break;
			case 'new':
				$view = "article/form_article_pro.twig";
				$data = ['id_cours' => $id];
				break;
			case 'create':
				$article = new Article();
				$article->chargePOST();
				$article->create();
				header('Location: pro.php?page=cours&id=' . $article->id_cours);
				break;
			case 'edit':
				$view = "article/edit_article_pro.twig";
				$data = ['article' => Article::readOne($id)];
				break;
			case 'update':
				$article = new Article();
				$article->chargePOST();
				$article->update();
				header('Location: pro.php?page=cours&id=' . $article->id_cours);
				break;
			case 'exchange':
				$article = Article::readOne($id);
				$article->exchangeOrder();
				$view = 'cours/detail_cours_pro.twig';
				header('Location: pro.php?page=cours&id=' . $article->id_cours);
				break;
			default:
				$view = 'cours/liste_cours_pro.twig';
				$data = [
					'liste_cours' => Article::readAll()
				];
				break;
		}
	}

	// Ajout de la méthode controleur pour le visiteur
	static function controleurVisit($action, $id, &$view, &$data)
    {
		switch ($action) {
			case 'read':
				if ($id > 0) {
					$view = 'article/detail_article_pro.twig';
					$data = [
						'article' => Article::readOne($id),
					];
				} else {
					header('Location: visiteur.php?page=cours');
				}
				break;
			default:
				$view = 'cours/liste_cours.twig';
				$data = [
					'liste_cours' => Article::readAll()
				];
				break;
		}
	}
}
