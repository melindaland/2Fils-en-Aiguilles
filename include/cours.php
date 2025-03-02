<?php

require_once('fonctions.php');

class Cours
{
	public $id;
	public $nom;
	public $description;
	public $image;
	public $niveau;

	// Le constructeur corrige les données récupérées de la BDD
	// Ici convertie l'identifiant en entier
	function __construct()
	{
		$this->id = intval($this->id);
	}

	static function readAll()
	{
		$sql = 'SELECT * FROM cours';
		$pdo = connexion();
		$query = $pdo->prepare($sql);
		$query->execute();
		return $query->fetchAll(PDO::FETCH_CLASS, 'Cours');
	}

	static function readOne($id)
	{
		$sql = 'SELECT * FROM cours WHERE id = :id';
		$pdo = connexion();
		$query = $pdo->prepare($sql);
		$query->bindValue(':id', $id, PDO::PARAM_INT);
		$query->execute();
		return $query->fetchObject('Cours');
	}

	function create()
    {
        $sql = "INSERT INTO cours (nom, description, image, niveau) VALUES (:nom, :description, :image, :niveau)";
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':nom', $this->nom, PDO::PARAM_STR);
        $query->bindValue(':description', $this->description, PDO::PARAM_STR);
        $query->bindValue(':image', $this->image, PDO::PARAM_STR);
        $query->bindValue(':niveau', $this->niveau, PDO::PARAM_STR);
        $query->execute();
        $this->id = $pdo->lastInsertId();
    }

	function update()
    {
        $sql = "UPDATE cours SET nom=:nom, description=:description, image=:image, niveau=:niveau WHERE id=:id";
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':id', $this->id, PDO::PARAM_INT);
        $query->bindValue(':nom', $this->nom, PDO::PARAM_STR);
        $query->bindValue(':description', $this->description, PDO::PARAM_STR);
        $query->bindValue(':image', $this->image, PDO::PARAM_STR);
        $query->bindValue(':niveau', $this->niveau, PDO::PARAM_STR);
        $query->execute();
    }

	function delete()
	{
		// Suppression du fichier lié
		if (!empty($this->image)) unlink('upload/' . $this->image);

		// Suppression du thème
		$sql = "DELETE FROM cours WHERE id=:id";
		$pdo = connexion();
		$query = $pdo->prepare($sql);
		$query->bindValue(':id', $this->id, PDO::PARAM_INT);
		$query->execute();
	}

	// Pour le filtrage de niveau (débutant, intermédiare ou avancé)
	static function readByNiveau($niveau)
	{
		$sql = 'SELECT * FROM cours WHERE niveau = :niveau';
		$pdo = connexion();
		$query = $pdo->prepare($sql);
		$query->bindValue(':niveau', $niveau, PDO::PARAM_STR);
		$query->execute();
		return $query->fetchAll(PDO::FETCH_CLASS, 'Cours');
	}

	function chargePOST()
	{
		$this->id = postInt('id');
		$this->nom = postString('nom');
		$this->description = postString('description');
		$this->image = postString('old-image');
		$this->niveau = postString('niveau');

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
				$view = 'visit_cours.twig';
				$data = ['liste_cours' => Cours::readAll()];
				break;
		}
	}

	// Pour l'administrateur
	static function controleurAdmin($action, $id, &$view, &$data)
	{
		switch ($action) {
			case 'read':
				if ($id > 0) {
					$view = 'cours/detail_cours.twig';
					$data = [
						'cours' => Cours::readOne($id),
						'liste_articles' => Article::readAllByCours($id)
					];
				} else {
					$view = 'cours/liste_cours.twig';
					$data = ['liste_cours' => Cours::readAll()];
				}
				break;
			case 'new':
				$view = "cours/form_cours.twig";
				break;
			case 'create':
				$cours = new Cours();
				$cours->chargePOST();
				$cours->create();
				header('Location: admin.php?page=cours');
				break;
			case 'edit':
				$view = "cours/edit_cours.twig";
				$data = ['cours' => Cours::readOne($id)];
				break;
			case 'update':
				$cours = new Cours();
				$cours->chargePOST();
				$cours->update();
				header('Location: admin.php?page=cours');
				break;
			case 'delete':
				$cours = Cours::readOne($id);
				$cours->delete();
				header('Location: admin.php?page=cours');
				break;
			default:
				$view = 'cours/liste_cours.twig';
				$data = ['liste_cours' => Cours::readAll()];
				break;
		}
	}

	// Pour le professionnel
	static function controleurPro($action, $id, &$view, &$data)
	{
		switch ($action) {
			case 'read':
				if ($id > 0) {
					$view = 'cours/detail_cours_pro.twig';
					$data = [
						'cours' => Cours::readOne($id),
						'liste_articles' => Article::readAllByCours($id)
					];
				} else {
					$view = 'cours/liste_cours_pro.twig';
					$data = ['liste_cours' => Cours::readAll()];
				}
				break;
			case 'new':
				$view = "cours/form_cours_pro.twig";
				break;
			case 'create':
				$cours = new Cours();
				$cours->chargePOST();
				$cours->create();
				header('Location: pro.php?page=cours');
				break;
			case 'edit':
				$view = "cours/edit_cours_pro.twig";
				$data = ['cours' => Cours::readOne($id)];
				break;
			case 'update':
				$cours = new Cours();
				$cours->chargePOST();
				$cours->update();
				header('Location: pro.php?page=cours');
				break;
			default:
				$view = 'cours/liste_cours_pro.twig';
				$data = ['liste_cours' => Cours::readAll()];
				break;
		}
	}

	// Pour le visiteur
	static function controleurVisit($action, $id, &$view, &$data)
    {
        switch ($action) {
            case 'read':
                if ($id > 0) {
                    $view = 'visit_detail_cours.twig';
                    $data = [
                        'cours' => Cours::readOne($id),
                        'liste_articles' => Article::readAllByCours($id)
                    ];
                } else {
					$view = 'visit_cours.twig';
					// Filtrage par niveau si demandé
					if (isset($_GET['niveau'])) {
						$data = ['liste_cours' => Cours::readByNiveau($_GET['niveau'])];
					} else {
						$data = ['liste_cours' => Cours::readAll()];
					}
				}
                break;
            default:
                $view = 'visit_cours.twig';
                $data = ['liste_cours' => Cours::readAll()];
                break;
        }
    }
}
