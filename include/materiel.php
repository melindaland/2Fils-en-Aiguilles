<?php

require_once('fonctions.php');

class Materiel
{
	public $id;
	public $nom;
	public $description;
	public $image;
	public $categorie;

	// Le constructeur corrige les données récupérées de la BDD
	// Ici convertie l'identifiant en entier
	function __construct()
	{
		$this->id = intval($this->id);
	}

	static function readAll()
	{
		$sql = 'SELECT * FROM materiel';
		$pdo = connexion();
		$query = $pdo->prepare($sql);
		$query->execute();
		return $query->fetchAll(PDO::FETCH_CLASS, 'Materiel');
	}

	static function readOne($id)
	{
		$sql = 'SELECT * FROM materiel WHERE id = :id';
		$pdo = connexion();
		$query = $pdo->prepare($sql);
		$query->bindValue(':id', $id, PDO::PARAM_INT);
		$query->execute();
		return $query->fetchObject('Materiel');
	}

	function create()
    {
        $sql = "INSERT INTO materiel (nom, description, image, categorie) VALUES (:nom, :description, :image, :categorie)";
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':nom', $this->nom, PDO::PARAM_STR);
        $query->bindValue(':description', $this->description, PDO::PARAM_STR);
        $query->bindValue(':image', $this->image, PDO::PARAM_STR);
        $query->bindValue(':categorie', $this->categorie, PDO::PARAM_STR);
        $query->execute();
        $this->id = $pdo->lastInsertId();
    }

	function update()
    {
        $sql = "UPDATE materiel SET nom=:nom, description=:description, image=:image, categorie=:categorie WHERE id=:id";
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':id', $this->id, PDO::PARAM_INT);
        $query->bindValue(':nom', $this->nom, PDO::PARAM_STR);
        $query->bindValue(':description', $this->description, PDO::PARAM_STR);
        $query->bindValue(':image', $this->image, PDO::PARAM_STR);
        $query->bindValue(':categorie', $this->categorie, PDO::PARAM_STR);
        $query->execute();
    }

	function delete()
	{
		// Suppression du fichier lié
		if (!empty($this->image)) unlink('upload/' . $this->image);

		// Suppression du thème
		$sql = "DELETE FROM materiel WHERE id=:id";
		$pdo = connexion();
		$query = $pdo->prepare($sql);
		$query->bindValue(':id', $this->id, PDO::PARAM_INT);
		$query->execute();
	}

	// Pour le filtrage de categorie du matériel
	static function readByCategorie($categorie)
	{
		$sql = 'SELECT * FROM materiel WHERE categorie = :categorie';
		$pdo = connexion();
		$query = $pdo->prepare($sql);
		$query->bindValue(':categorie', $categorie, PDO::PARAM_STR);
		$query->execute();
		return $query->fetchAll(PDO::FETCH_CLASS, 'Materiel');
	}

	function chargePOST()
	{
		$this->id = postInt('id');
		$this->nom = postString('nom');
		$this->description = postString('description');
		$this->image = postString('old-image');
		$this->categorie = postString('categorie');

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
				$view = 'visit_materiel.twig';
				$data = ['liste_materiel' => Materiel::readAll()];
				break;
		}
	}

	// Pour l'administrateur
	static function controleurAdmin($action, $id, &$view, &$data)
	{
		switch ($action) {
			case 'read':
				if ($id > 0) {
					$view = 'materiel/detail_materiel.twig';
					$data = [
						'materiel' => Materiel::readOne($id),
						'liste_objets' => Objet::readAllByMateriel($id)
					];
				} else {
					$view = 'materiel/liste_materiel.twig';
					$data = ['liste_materiel' => Materiel::readAll()];
				}
				break;
			case 'new':
				$view = "materiel/form_materiel.twig";
				break;
			case 'create':
				$materiel = new Materiel();
				$materiel->chargePOST();
				$materiel->create();
				header('Location: admin.php?page=materiel');
				break;
			case 'edit':
				$view = "materiel/edit_materiel.twig";
				$data = ['materiel' => Materiel::readOne($id)];
				break;
			case 'update':
				$materiel = new Materiel();
				$materiel->chargePOST();
				$materiel->update();
				header('Location: admin.php?page=materiel');
				break;
			case 'delete':
				$materiel = Materiel::readOne($id);
				$materiel->delete();
				header('Location: admin.php?page=materiel');
				break;
			default:
				$view = 'materiel/liste_materiel.twig';
				$data = ['liste_materiel' => Materiel::readAll()];
				break;
		}
	}

	// Pour le professionnel
	static function controleurPro($action, $id, &$view, &$data)
	{
		switch ($action) {
			case 'read':
				if ($id > 0) {
					$view = 'materiel/detail_materiel_pro.twig';
					$data = [
						'materiel' => Materiel::readOne($id),
						'liste_objets' => Objet::readAllByMateriel($id)
					];
				} else {
					$view = 'materiel/liste_materiel_pro.twig';
					$data = ['liste_materiel' => Materiel::readAll()];
				}
				break;
			case 'new':
				$view = "materiel/form_materiel_pro.twig";
				break;
			case 'create':
				$materiel = new Materiel();
				$materiel->chargePOST();
				$materiel->create();
				header('Location: pro.php?page=materiel');
				break;
			case 'edit':
				$view = "materiel/edit_materiel_pro.twig";
				$data = ['materiel' => Materiel::readOne($id)];
				break;
			case 'update':
				$materiel = new Materiel();
				$materiel->chargePOST();
				$materiel->update();
				header('Location: pro.php?page=materiel');
				break;
			default:
				$view = 'materiel/liste_materiel_pro.twig';
				$data = ['liste_materiel' => Materiel::readAll()];
				break;
		}
	}

	// Pour le visiteur
	static function controleurVisit($action, $id, &$view, &$data)
    {
        switch ($action) {
            case 'read':
                if ($id > 0) {
                    $view = 'visit_detail_materiel.twig';
                    $data = [
                        'materiel' => Materiel::readOne($id),
                        'liste_objets' => Objet::readAllByMateriel($id)
                    ];
                } else {
					$view = 'visit_materiel.twig';
					// Filtrage par categorie si demandé
					if (isset($_GET['categorie'])) {
						$data = ['liste_materiel' => Materiel::readByCategorie($_GET['categorie'])];
					} else {
						$data = ['liste_materiel' => Materiel::readAll()];
					}
				}
                break;
            default:
                $view = 'visit_materiel.twig';
                $data = ['liste_materiel' => Materiel::readAll()];
                break;
        } 
    }
}
