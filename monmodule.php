# monmoduletest
Mon test / Sylvain PAILLARES

// le fichier monmodul.php placé dans le dossier module

<?php 


//Ce module devra envoyer par e-mail automatiquement la quantité de produits restants en stock à chaque fois que le stock d'un produit est modifié. : 

/**ALGORYTHME==>        1/ on require le Script SPDO , qui permet de nous connecter à notre base de donnée 
						2/ on va chercher la quantité restante à partir de la base de donnée ,et plus précisement de la table ps_stock_available ; puis ont fait un foreach sur la ligne(fetch) renvoyé , pour avoir la quantité de ce produit (pomme rouge)
						
					    3/ on crée un Mail::send qui va m'envoyer un mail avec la quantité du produit "pomme rouge"
				PS: algorythme terminé mais fonctionnalité , qui s'en doute à du code manquant pour etre tout à fait operationnel.
*/

// premier condition indispensable  au tout debut e tout les modules pour ne pas etre hacker
require_once('SPDO.php');

if (!defined('_PS_VERSION_')) {
	exit;
}

/**
*  La classe de mon module
*/


class Monmodule extends Module
{
		
			
					

	
	public function __construct()
	{
		// le nom 
		$this->name = 'monmodule';
		//la tab => ou le module se montrera dans le back office
		$this->tab = 'front_office_features';
		// la version 
		$this->version = '0.1.0';
		// auteur
		$this->author = 'Sylvain Paillares';
		// un tableau qui dira à  prestaShop laquelle des versions  ce module peut etre installé 
		$this->ps_versions_compliancy =array('min' =>'1.5', 'max' => _PS_VERSION_); // de la 1.5 à la  plus recente
		// propriété qui indique ou  le module doit etre charger dans les models et instancié dans le back office ou non = 0
		$this->need_instance = 0;

		$this->bootstrap= true; // or false si 1.7 le prend pas 
		$this->displayName = $this->l('Mon Module');
		$this->description = $this->l("Ceci est un module pour un test qui consiste à envoyer par e-mail automatiquement la quantité de produits restants en stock à chaque fois que le stock d'un produit est modifié ");

		parent::__construct();
	}

	public function install()
	{
		// le parent install va appeler la classe du core module et de l'avoir installé et faire les choses dont on a besoin  pour laisser PrestaShop noter que ce module est installé
		
		if(
			!parent::install() OR 
			!$this->registerHook('displayLeftColumn') OR 

			)
			return false;
		return true;
	}

	public function getContent()
	{  // va chercher la quantité restante de mon produit créé 'pomme rouge' à ârtir de la fucntion ci dessus
			 $db = SPDO::getInstance();

		 $product = 'SELECT `quantity`

				FROM `ps_stock_available`
				 
				WHERE `id_product` = 8';

		 $qtt = $db->query($product);
	
			if (Tools::isSubmit('submitUpdate'))  // si on submit  le formulaire
			{
				if ($qtt !== false) 
				{
					if ($qtt->execute()) 
					{
						$row = $qtt->fetch();
						if (count($row) > 0) 
						{
								foreach ($row as $key => $value) 
								{
									
							 		return $value;
									Configuration::get($value, Tools::getValue('myquantity'));   // le 1er parametre c'est le nom du parametre de config qui va update ou creer  
																						// le 2eme	est la valeur actuel en utilisant le Tools
									//on rattahce un message de confirmation du submit 
								}
						}
					}
				}
	 		}

			$html .= $this->displayConfirmation($this->l('Parametres mis à jour'));

			$html .= '
			<form action="'.$_SERVER['REQUEST_URI'].'" method="post" class="defaultForm form-horizontal">
				<div class="panel">
						<div class="panel-heading">'.$this->l('Parametres').'</div>';
			$html .='
			<div class="form-group">
				<label class="control-label col-lg-3">'.$this->l('Mon Champ').'</label>
				<div class="col-lg-6">
					<input type="text" name="myquantity" value="'.Configuration::get($value).'" />
				</div>
			</div> 
			';			
					
			$html .= '
			<input type="submit" name="submitUpdate" value="'.$this->l('Afficher ').'" class="btn btn-default">
			';
			$html .= '		 
				</div>  
			</form>
			';
			// hookDisplayLeftColumn($params);
			// retourner notre formulaire avec un return 
			return $html;
	

			global $cookie;
			 
			$subject = 'Quantité de pommes restantes';
			$donnees = array('{nom}'  => 'Willis' ,  '{prenom}'  => 'Bruce' );
			$destinataire = 'zackstrife34000@gmail.com';
			 // en verifiant si le form exist alors on envoi la quantité de pomme par la fonction Mail::Send 
			 if(isset($html)) 
			 {
			 
					return Mail::Send(intval($cookie->id_lang), 'monmodule', $subject , $donnees, $destinataire, NULL, NULL, NULL, NULL, NULL, 'mails/');
			 }

			 	

						
	}

					
	 // pas fonctionelle ! 
   public function hookDisplayLeftColumn($params)   // on va afficher la quantité restante pour 'pomme rouge'
	{
	
		// cela retournera laffichage sur la colonne de gauche du front office

		
		// return Configuration::get($value);
	
		// var_dump($row);
		
	}
	
}
		
 ?>
