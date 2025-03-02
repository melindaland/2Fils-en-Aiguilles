<?php

use phpDocumentor\Reflection\DocBlock\Tags\Var_;

require_once('fonctions.php');

class Objet
{
	public $id;
	public $ordre;
	public $titre;
	public $redacteur;
	public $accroche;
	public $image;
	public $id_materiel;

	// Le constructeur corrige les données récupérées de la BDD
	// Ici convertie les clés et l'ordre (pour le tri) en entier
	function __construct()
	{
		$this->id = intval($this->id);
		$this->ordre = intval($this->ordre);
		$this->id_materiel = intval($this->id_materiel);
	}

	static function readAll()
	{
		$sql = 'SELECT * FROM objet ORDER BY ordre';
		$pdo = connexion();
		$query = $pdo->prepare($sql);
		$query->execute();
		return $query->fetchAll(PDO::FETCH_CLASS, 'Objet');
	}

	static function readOne($id)
	{
		$sql = 'SELECT * FROM objet WHERE id = :id';
		$pdo = connexion();
		$query = $pdo->prepare($sql);
		$query->bindValue(':id', $id, PDO::PARAM_INT);
		$query->execute();
		return $query->fetchObject('Objet');
	}

	// Récupère les objets d'un thème
	static function readAllByMateriel($id)
	{
		$sql = 'SELECT * FROM objet WHERE id_materiel = :id ORDER BY ordre';
		$pdo = connexion();
		$query = $pdo->prepare($sql);
		$query->bindValue(':id', $id, PDO::PARAM_INT);
		$query->execute();
		return $query->fetchAll(PDO::FETCH_CLASS, 'Objet');
	}

	// Récupère l'ordre maximal des objets d'un thème
	static function readOrderMax($id)
	{
		$sql = 'SELECT max(ordre) AS maximum FROM objet WHERE id_materiel = :id';
		$pdo = connexion();
		$query = $pdo->prepare($sql);
		$query->bindValue(':id', $id, PDO::PARAM_INT);
		$query->execute();
		$objet = $query->fetchObject();
		return intval($objet->maximum);
	}

	// Echange l'ordre de deux objets
	function exchangeOrder()
	{
		// Recherche l'objet précédent (dans le même thème)
		// C'est l'objet le plus grand, parmi les objets d'ordre inférieur

		// étape 1 : cherche les objets du même thème ayant un ordre inférieur
		$sql = 'SELECT * FROM objet
				WHERE id_materiel = :id_materiel AND ordre < :ordre ORDER BY ordre DESC';
		$pdo = connexion();
		$query = $pdo->prepare($sql);
		$query->bindValue(':id_materiel', $this->id_materiel, PDO::PARAM_INT);
		$query->bindValue(':ordre', $this->ordre, PDO::PARAM_INT);
		$query->execute();

		// étape 2 : les objets sont triés par ordre décroissant
		// donc le premier objets est le plus grand des plus petits, donc le précédent
		$before = $query->fetchObject('Objet');

		// Si le précédent existe (l'objet courant n'est pas le premier)
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
		// Récupère l'ordre maximum pour créer l'objet en dernière position
		$maximum = self::readOrderMax($this->id_materiel);
		$this->ordre = $maximum + 1;

		$sql = "INSERT INTO objet (ordre, titre, accroche, redacteur, image, id_materiel)
				VALUES (:ordre, :titre, :accroche, :redacteur, :image, :id_materiel)";
		$pdo = connexion();
		$query = $pdo->prepare($sql);
		$query->bindValue(':ordre', $this->ordre, PDO::PARAM_INT);
		$query->bindValue(':titre', $this->titre, PDO::PARAM_STR);
		$query->bindValue(':accroche', $this->accroche, PDO::PARAM_STR);
		$query->bindValue(':redacteur', $this->redacteur, PDO::PARAM_STR);
		$query->bindValue(':image', $this->image, PDO::PARAM_STR);
		$query->bindValue(':id_materiel', $this->id_materiel, PDO::PARAM_INT);
		$query->execute();
		$this->id = $pdo->lastInsertId();
	}

	function update()
	{
		$sql = "UPDATE objet
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

		// Suppression de l'objet
		$sql = "DELETE FROM objet WHERE id=:id";
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
		$this->id_materiel = postInt('id_materiel');

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
				$view = 'visit_objet.twig';
				$data = [
					'objet' => Objet::readOne($id)
				];
				break;
		}
	}

	// Ajout de la méthode controleur pour l'administrateur
	static function controleurAdmin($action, $id, &$view, &$data)
	{
		switch ($action) {
			case 'read':
				// Liste des objets d'un thème ($id)
				if ($id > 0) {
					$view = 'objet/detail_objet.twig';
					$data = [
						'objet' => Objet::readOne($id),
					];
				} else {
					header('Location: admin.php?page=materiel');
				}
				break;
			case 'new':
				$view = "objet/form_objet.twig";
				$data = ['id_materiel' => $id];
				break;
			case 'create':
				$objet = new Objet();
				$objet->chargePOST();
				$objet->create();
				header('Location: admin.php?page=materiel&id=' . $objet->id_materiel);
				break;
			case 'edit':
				$view = "objet/edit_objet.twig";
				$data = ['objet' => Objet::readOne($id)];
				break;
			case 'update':
				$objet = new Objet();
				$objet->chargePOST();
				$objet->update();
				header('Location: admin.php?page=materiel&id=' . $objet->id_materiel);
				break;
			case 'delete':
				$objet = Objet::readOne($id);
				$objet->delete();
				header('Location: admin.php?page=materiel&id=' . $objet->id_materiel);
				break;
			case 'exchange':
				$objet = Objet::readOne($id);
				$objet->exchangeOrder();
				$view = 'materiel/detail_materiel.twig';
				header('Location: admin.php?page=materiel&id=' . $objet->id_materiel);
				break;
			default:
				$view = 'materiel/liste_materiel.twig';
				$data = [
					'liste_materiel' => Objet::readAll()
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
					$view = 'objet/detail_objet_pro.twig';
					$data = [
						'objet' => Objet::readOne($id),
					];
				} else {
					header('Location: pro.php?page=materiel');
				}
				break;
			case 'new':
				$view = "objet/form_objet_pro.twig";
				$data = ['id_materiel' => $id];
				break;
			case 'create':
				$objet = new Objet();
				$objet->chargePOST();
				$objet->create();
				header('Location: pro.php?page=materiel&id=' . $objet->id_materiel);
				break;
			case 'edit':
				$view = "objet/edit_objet_pro.twig";
				$data = ['objet' => Objet::readOne($id)];
				break;
			case 'update':
				$objet = new Objet();
				$objet->chargePOST();
				$objet->update();
				header('Location: pro.php?page=materiel&id=' . $objet->id_materiel);
				break;
			case 'exchange':
				$objet = Objet::readOne($id);
				$objet->exchangeOrder();
				$view = 'materiel/detail_materiel_pro.twig';
				header('Location: pro.php?page=materiel&id=' . $objet->id_materiel);
				break;
			default:
				$view = 'materiel/liste_materiel_pro.twig';
				$data = [
					'liste_materiel' => Objet::readAll()
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
					$view = 'objet/detail_objet_pro.twig';
					$data = [
						'objet' => Objet::readOne($id),
					];
				} else {
					header('Location: visiteur.php?page=materiel');
				}
				break;
			default:
				$view = 'materiel/liste_materiel.twig';
				$data = [
					'liste_materiel' => Objet::readAll()
				];
				break;
		}
	}
}
