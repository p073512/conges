<?php
//"Default" correspond au namespace que nous avons d�fini dans le bootstrap
class Default_Model_Conge
{
	//variables correspondant � chacun des champs de notre table users
	protected  $_id;
	protected  $_id_personne;
	protected  $_id_proposition;
	protected  $_date_debut;
	protected  $_mi_debut_journee;
	protected  $_date_fin;
	protected  $_mi_fin_journee;
	protected  $_nombre_jours;
	protected  $_id_type_conge;
	protected  $_annee_reference;
	protected  $_ferme;
	protected $_nombreJoursT;

	//le mapper va nous fournir les m�thodes pour interagir avec notre table (objet de type Default_Model_CongeMapper)
	protected $_mapper;

	//constructeur
	//le tableau d'options peut contenir les valeurs des champs � utiliser
	//pour l'initialisation de l'objet
	public function __construct(array $options = null)
	{
		if (is_array($options)) 
		{
			$this->setOptions($options);
		}
	}

	//cette m�thode permet d'appeler n'importe quel settor en fonction
	//des arguments
	public function __set($name, $value)
	{
		$method = 'set' . $name;
		if (('mapper' == $name) || !method_exists($this, $method)) 
		{
			throw new Exception('Invalid guestbook property');
		}
		$this->$method($value);
	}

	//cette m�thode permet d'appeler n'importe quel gettor en fonction
	//du nom pass� en argument
	public function __get($name)
	{
		$method = 'get' . $name;
		if (('mapper' == $name) || !method_exists($this, $method)) 
		{
			throw new Exception('Invalid guestbook property');
		}
		return $this->$method();
	}

	//permet de g�rer un tableau d'options pass� en argument au constructeur
	//ce tabelau d'options peut contenir la valeur des champs � utiliser
	//pour l'initialisation de l'objet
	public function setOptions(array $options)
	{
		$methods = get_class_methods($this);
		foreach ($options as $key => $value) 
		{
			$method = 'set' . ucfirst($key);
			if (in_array($method, $methods)) 
			{
				$this->$method($value);
			}
		}
		return $this;
	}

	//gettors and settors d'acc�s aux variables
	public function setId($id)
	{
		$this->_id = (int)$id;
		return $this;
	}

	public function getId()
	{
		return $this->_id;
	}

	public function setId_personne($id_personne)
	{
		$this->_id_personne = (int)$id_personne;
		return $this;
	}
	public function getId_personne()
	{
		return $this->_id_personne;
	}
	
	public function setId_proposition($id_proposition)
	{
		$this->_id_proposition = $id_proposition;
		return $this;
	}
	public function getId_proposition()
	{
		return $this->_id_proposition;
	}

	public function setDate_debut($date_debut)
	{
		$this->_date_debut = $date_debut;
		return $this;
	}
	public function getDate_debut()
	{
		return $this->_date_debut;
	}

	public function setMi_debut_journee($mi_debut_journee)
	{
		$this->_mi_debut_journee = $mi_debut_journee;
		return $this;
	}
	public function getMi_debut_journee()
	{
		return $this->_mi_debut_journee;
	}

	public function setDate_fin($date_fin)
	{
		$this->_date_fin = $date_fin;
		return $this;
	}

	public function getDate_fin()
	{
		return $this->_date_fin;
	}

	public function setMi_fin_journee($mi_fin_journee)
	{
		$this->_mi_fin_journee = $mi_fin_journee;
		return $this;
	}
	public function getMi_fin_journee()
	{
		return $this->_mi_fin_journee;
	}
	
	/* Mohamed khalil TAKAFI */
	public function setNombre_jours()  // MTA : calcul nombre de jours Cong�
	{
		$date_debut = new DateTime($this->getDate_debut());    // date_debut 
    	$date_fin = new DateTime($this->getDate_fin());        // date_fin
    	
    	$date_depart =  date_timestamp_get($date_debut);
    	$annee = (string)date('Y', $date_depart);              // ann�e 
	    
        $debut_midi = $this->getMi_debut_journee();            // debut midi 
        $fin_midi = $this->getMi_fin_journee();                // fin midi 

        $maroc = false;                                        // France 
        
        $utils = new Default_Controller_Helpers_Validation();

        // mettre les jours f�ri�s maroc dans session TEST 
		$jours_feries_maroc = new Zend_Session_Namespace('TEST',false);
        $jours_feries_maroc->jfm = $utils->jours_feries_maroc($annee);
        
        //MTA :  verifi� si c'est un CSM ou France     
        $personne = new Default_Model_Personne();
        $per = $personne->find($this->getId_personne());
       
        
        if ($per->getEntite()->getCs() == 1) 
        {
		 $maroc = true;     // maroc 
        }
    
        $this->_nombre_jours = $utils->calculer_jours_conges($date_debut, $date_fin,$debut_midi,$fin_midi,$maroc);
		return $this;  
	}
	
	/*
	 * Fonction qui retourne le calcul de nombre de jour de congé
	 * en affichant (pour test) le tableau de détails période congé
	 * et le table des compteurs jours de congé
	 * 
	 */
	
public function CalculNombreJoursConge($csm = false,$alsaceMoselle = false)
	{
	//récupération des propriétés de l'objet congé	
	$dateDebut = $this->getDate_debut();   
    $dateFin = $this->getDate_fin(); 
    $annneeReference = $this->getAnnee_reference();

    // instance de la classe helper Outils
     $outils = new Default_Controller_Helpers_outils();
	 //ici changer les dates de congé:
     $conge = $outils->getPeriodeDetails($annneeReference,$dateDebut , $dateFin,$this->getMi_debut_journee(),$this->getMi_fin_journee(),$csm,$alsaceMoselle );

     // affichage tableau de calcul jours de congé
     echo'Nombre jours congé : </br>';
     var_dump($outils->calculNombreJourConge($conge));
     
     //affichage du tableau du détails de la période de congé
     echo'Le détails du congé ci-dessous :</br>';
     var_dump($conge);
     
     $this->_nombreJoursT = $outils->CalculNombreJourConge($conge);

     return $this;
		
	}
	
	
	public function  getNombre_jours()
	{
		return $this->_nombre_jours;
	}

	public function setId_type_conge($id_type_conge)
	{
		$this->_id_type_conge = (int)$id_type_conge;
		return $this;
	}
	public function  getId_type_conge()
	{
		return $this->_id_type_conge;
	}

	public function setAnnee_reference($annee_reference)
	{
			return $this->_annee_reference = (int)$annee_reference;
	}
	public function  getAnnee_reference()
	{
		return $this->_annee_reference;
	}

	public function setFerme($ferme)
	{
		$this->_ferme = $ferme;
		return $this;
	}

	public function  getFerme()
	{
		return $this->_ferme;
	}



	public function setMapper($mapper)
	{
		$this->_mapper = $mapper;
		return $this;
	}
	public function getMapper()
	{
		//si la valeur $_mapper n'est pas initialis�e, on l'initialise (
		if(null == $this->_mapper)
		{
			$this->setMapper(new Default_Model_CongeMapper());
		}

		return $this->_mapper;
	}

	//m�thodes de classe utilisant les m�thodes du mapper
	//cr�e ou met � jour une entr�e dans la table
	public function save()
	{
		$this->getMapper()->save($this);
	}

	//r�cup�re une entr�e particuli�re
	public function find($id)
	{
		$this->getMapper()->find($id, $this);
		return $this;
	}

	//r�cup�re toutes les entr�es de la table
	public function fetchAll($str)
	{
		return $this->getMapper()->fetchAll($str);
	}
	// récupére les congés sur une période donnée.
   public function conges_existant($id_personne,$date_debut,$date_fin,$flag) 
	{
		return $this->getMapper()->conges_existant($id_personne,$date_debut,$date_fin,$flag);
	}

	//permet la suppression
	public function delete($id)
	{
		$this->getMapper()->delete($id);
	}
	public function somme($id,$annee_reference) 
    {
    	return $this->getMapper()->somme($id,$annee_reference);
    }
    public function selctid() 
    {
    	return $this->getMapper()->selctid();
    }
    public function somme_solde_annuel_confe($id_personnes, $debut_annee, $fin_annee)
    {
    	return $this->getMapper()->somme_solde_annuel_confe($id_personnes, $debut_annee, $fin_annee);
    }
    
    public function doublont( $debut_annee, $fin_annee)
    {
    	return $this->getMapper()->doublont(  $debut_annee, $fin_annee);
    }
   
    public function  DateDebutMin($id,$debut_mois,$fin_mois)
    {
    	return $this->getMapper()-> DateDebutMin($id,$debut_mois,$fin_mois);
    }
    
    public function DateFinMax($id,$debut_mois,$fin_mois)
    {
    	return $this->getMapper()-> DateFinMax($id,$debut_mois,$fin_mois);
    }
    public function CongesNondoublont( $debut_mois,$fin_mois) 
 	{
    	return $this->getMapper()-> CongesNondoublont( $debut_mois,$fin_mois) ;
    }
     public function RecupererLeNombreConge( $id_personne,$date_debut)
 	{
    	return $this->getMapper()-> RecupererLeNombreConge( $id_personne,$date_debut) ;
    }
   
  	 public function  DoublontAuNiveauPole( $tableau_id, $debut_mois,  $fin_mois)
 	{
    	return $this->getMapper()->DoublontAuNiveauPole( $tableau_id, $debut_mois,  $fin_mois) ;
    }
    
 	public function  CongesNondoublontPole( $tableau_id,$debut_mois,$fin_mois) 
 	{
    	return $this->getMapper()->CongesNondoublontPole( $tableau_id,$debut_mois,$fin_mois)  ;
    }
  
	
/*
	 * calcule de nombre de jours ouvres dans un mois  
	 */

	public function joursOuvresDuMois($debut_mois,$fin_mois)
	{
		// tu peut utiliser cette fonction pour afficher les nombre totale ouvere pour un mois donn�

		$date_debut = strtotime($debut_mois );
    	$date_fin = strtotime($fin_mois );
		
    	$tableau_jours_feries = array(); // Tableau des jours feri�s
    // On boucle dans le cas o� l'ann�e de d�part serait diff�rente de l'ann�e d'arriv�e
    	$difference_annees = date('Y', $date_fin) - date('Y', $date_debut);
    for ($i = 0; $i <= $difference_annees; $i++) 
    {
	    $annee = (int)date('Y', $date_debut) + $i;
	    // Liste des jours feri�s
	    $tableau_jours_feries[] = '1_1_'.$annee; // Jour de l'an
	    $tableau_jours_feries[] = '1_5_'.$annee; // Fete du travail
	    $tableau_jours_feries[] = '8_5_'.$annee; // Victoire 1945
	    $tableau_jours_feries[] = '14_7_'.$annee; // Fete nationale
	    $tableau_jours_feries[] = '15_8_'.$annee; // Assomption
	    $tableau_jours_feries[] = '1_11_'.$annee; // Toussaint
	    $tableau_jours_feries[] = '11_11_'.$annee; // Armistice 1918
	    $tableau_jours_feries[] = '25_12_'.$annee; // Noel
	    // R�cup�ration de paques. Permet ensuite d'obtenir le jour de l'ascension et celui de la pentecote
	    $easter = easter_date($annee);
	    $tableau_jours_feries[] = date('j_n_'.$annee, $easter + 86400); // Paques
	    $tableau_jours_feries[] = date('j_n_'.$annee, $easter + (86400*39)); // Ascension
	    $tableau_jours_feries[] = date('j_n_'.$annee, $easter + (86400*50)); // Pentecote
    }
    //print_r($tableau_jours_feries);
    $nb_jours_ouvres = 0;
    // Mettre <= si on souhaite prendre en compte le dernier jour dans le d�compte
    while ($date_debut <= $date_fin) 
    {
    // Si le jour suivant n'est ni un dimanche (0) ou un samedi (6), ni un jour f�ri�, on incr�mente les jours ouvr�s
	    if (!in_array(date('w', $date_debut), array(0, 6)) && !in_array(date('j_n_'.date('Y', $date_debut), $date_debut), $tableau_jours_feries)) 
	    {
	    	$nb_jours_ouvres++;
	    }
	    	$date_debut = mktime(date('H', $date_debut), date('i', $date_debut), date('s', $date_debut), date('m', $date_debut), date('d', $date_debut) + 1, date('Y', $date_debut));
	}

		return $nb_jours_ouvres; 
	}

	
	
	/*
	 * cette fonction a pour role de chercher les jours feries d'un mois 
	 * elle est utilis�e afin de valoriser les jours feries au niveau du 
	 * calendrier mensuel
	 */
	
	
	public function chercher_jours_feriers($debut_mois,$fin_mois)
	{
	$date_debut = strtotime($debut_mois );
    $date_fin = strtotime($fin_mois );
		
    	$tableau_jours_feries = array(); // Tableau des jours feri�s
    // On boucle dans le cas o� l'ann�e de d�part serait diff�rente de l'ann�e d'arriv�e
    	$difference_annees = date('Y', $date_fin) - date('Y', $date_debut);
 for ($i = 0; $i <= $difference_annees; $i++) 
    {
	    $annee = (int)date('Y', $date_debut) + $i;
	    // Liste des jours feri�s
	    $tableau_jours_feries[] = '1_1_'.$annee; // Jour de l'an
	    $tableau_jours_feries[] = '1_5_'.$annee; // Fete du travail
	    $tableau_jours_feries[] = '8_5_'.$annee; // Victoire 1945
	    $tableau_jours_feries[] = '14_7_'.$annee; // Fete nationale
	    $tableau_jours_feries[] = '15_8_'.$annee; // Assomption
	    $tableau_jours_feries[] = '1_11_'.$annee; // Toussaint
	    $tableau_jours_feries[] = '11_11_'.$annee; // Armistice 1918
	    $tableau_jours_feries[] = '25_12_'.$annee; // Noel
	    // R�cup�ration de paques. Permet ensuite d'obtenir le jour de l'ascension et celui de la pentecote
	    $easter = easter_date($annee);
	    $tableau_jours_feries[] = date('j_n_'.$annee, $easter + 86400); // Paques
	    $tableau_jours_feries[] = date('j_n_'.$annee, $easter + (86400*39)); // Ascension
	    $tableau_jours_feries[] = date('j_n_'.$annee, $easter + (86400*50)); // Pentecote
    }
    	return $tableau_jours_feries;
}

/////////////////////////////// DEBUT Fonction Normaliser date_debut et date_fin ///////////////////////////////////
public function normaliser_dates($date_debut,$date_fin)
{
	 $outil = new Default_Controller_Helpers_Validation();
	 
	 if($date_debut <> $date_fin )
	 {
			  $d_d = new DateTime($date_debut);
			  $d_f = new DateTime($date_fin);
                 
			  // si date_debut = f�ri� ou week      et       date_fin = f�ri� ou week   on les d�cale tt les deux en avant 
			  if(($outil->est_ferie(date_format($d_f,'Y-m-d H:i:s'),false,false) || in_array(date_format($d_f, 'l'),array('Saturday','Sunday')) && ($outil->est_ferie(date_format($d_d, 'Y-m-d H:i:s'),false,false)) || in_array(date_format($d_d, 'l'),array('Saturday','Sunday'))))
			  {
			     $dd = $outil->normaliser_date_debut_conge($d_d,false);
			     $df = $outil->normaliser_date_debut_conge($d_f,false);
			     $tab[0] = $dd->format('Y-m-d H:i:s');
				 $tab[1] = $df->format('Y-m-d H:i:s');
			  }
			  else 
			  {
				  $dd = $outil->normaliser_date_debut_conge($d_d,false);
				  $df = $outil->normaliser_date_fin_conge($d_f,false);
				  $tab[0] = $dd->format('Y-m-d H:i:s');
				  $tab[1] = $df->format('Y-m-d H:i:s');
			  }
	}
	else 
    {
		      $d_d = new DateTime($date_debut);
			  $dd = $outil->normaliser_date_debut_conge($d_d,false);
		   
		      $tab[0] = $dd->format('Y-m-d H:i:s');
			  $tab[1] = $tab[0];
	}
		                  	    
	 return $tab; 
	
}
///////////////////////////////FIN Fonction Normaliser date_debut et date_fin ///////////////////////////////////



}
////////////////////////////// reglage des dates en fonction des demis journ�es ////////////////////////////////
public function makeDatetime($date_debut,$date_fin,$debut_midi,$fin_midi) 
{
	$date_deb = new DateTime($date_debut);
    $date_fi = new DateTime($date_fin);

	// gerer les datetimes 			
	if($debut_midi == 1)
	{   
		// ajouter 12h00m00s � la date 
		$date_deb =  $date_deb->add(new DateInterval('PT12H00M00S'));				    
	} 
							    
	if($fin_midi == 1)
	{
		 // ajouter 11h59m59s � la date 
		$date_fi =   $date_fi->add(new DateInterval('PT11H59M59S'));
			     			    
	}
	else //  $fin_midi == 0
	{
		// ajouter 23h59m59s � la date 
		$date_fi =  $date_fi->add(new DateInterval('PT23H59M59S'));
	}
	
	 $date[0] = $date_deb->format('Y-m-d H:i:s');
	 $date[1] = $date_fi->format('Y-m-d H:i:s');
		
	 return $date;

}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////



}

