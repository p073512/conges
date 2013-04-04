/*--------------------------------------------------------------------------------*/
/*  Création de la base conges                                                    */
CREATE  DATABASE IF NOT EXISTS conges CHARACTER SET 'utf8';
 USE conges;
/*--------------------------------------------------------------------------------*/


/*--------------------------------------------------------------------------------*/

/* Table type_conge */
	
CREATE TABLE IF NOT EXISTS type_conge
	(
	id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
	code CHAR(2) NOT NULL,
	libelle VARCHAR(30) NOT NULL,
	PRIMARY KEY (id)
  ) ENGINE=InnoDB;

/*------------------------------------------------------------------------------------------------*/

/* Table pole */
	
CREATE TABLE IF NOT EXISTS pole
	(
	id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
	libelle VARCHAR(30) NOT NULL,
	PRIMARY KEY (id)
  ) ENGINE=InnoDB;	

/*--------------------------------------------------------------------------------*/

/* Table entite */
	
CREATE TABLE IF NOT EXISTS entite
	(
	id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
	libelle CHAR(4) NOT NULL,
	PRIMARY KEY (id)
  ) ENGINE=InnoDB;


/*--------------------------------------------------------------------------------*/

/* Table modalite */	

CREATE TABLE IF NOT EXISTS modalite (
  id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
  code CHAR(2) NOT NULL,
  libelle VARCHAR(20) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


/*--------------------------------------------------------------------------------*/

/* Table fonction */	

CREATE TABLE IF NOT EXISTS fonction
	(
	id SMALLINT UNSIGNED NOT NULL,
	libelle VARCHAR(20) NOT NULL,
	PRIMARY KEY (id)
  ) ENGINE=InnoDB;

/*--------------------------------------------------------------------------------*/

/* Table personne */

CREATE TABLE IF NOT EXISTS `personne` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(20) NOT NULL,
  `prenom` varchar(20) NOT NULL,
  `date_entree` date NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `id_entite` smallint(5) unsigned NOT NULL,
  `id_pole` smallint(5) unsigned NOT NULL,
  `id_modalite` smallint(5) unsigned NOT NULL,
  `id_fonction` smallint(5) unsigned NOT NULL,
  `pourcent` smallint(5) unsigned NOT NULL,
  `centre_service` tinyint(1) NOT NULL,
  `stage` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_entite` (`id_entite`),
  KEY `id_pole` (`id_pole`),
  KEY `id_modalite` (`id_modalite`),
  KEY `id_fonction` (`id_fonction`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=85 ;
	
/*--------------------------------------------------------------------------------*/

/* Table vacances */
	
CREATE TABLE IF NOT EXISTS vacances
	(
	zone CHAR(1),
	date_debut DATE NOT NULL,
	date_fin DATE NOT NULL)
	ENGINE=InnoDB;	
	
/*--------------------------------------------------------------------------------*/

/* Table Profil */	

CREATE TABLE IF NOT EXISTS profil
	(
	id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
	login VARCHAR(10) NOT NULL,
	mot_passe CHAR(64) NOT NULL,
	PRIMARY KEY (id)
  )	ENGINE=InnoDB;


/*--------------------------------------------------------------------------------*/

/* Table propostion */

CREATE TABLE IF NOT EXISTS proposition
	(
	id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,	
	id_personne SMALLINT UNSIGNED NOT NULL, 
	date_debut DATE NOT NULL,
	mi_debut_journee BOOLEAN NOT NULL, 
	date_fin DATE NOT NULL,
	mi_fin_journee BOOLEAN NOT NULL,
	nombre_jours FLOAT(4,1) NOT NULL,
	etat CHAR(2),
	PRIMARY KEY (id),
	INDEX (id_personne),
	FOREIGN KEY (id_personne) REFERENCES personne(id)
  ) ENGINE=InnoDB;


/*--------------------------------------------------------------------------------*/

/* Table conge */

CREATE TABLE IF NOT EXISTS `conge` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `id_proposition` smallint(5) unsigned DEFAULT NULL,
  `id_personne` smallint(5) unsigned NOT NULL,
  `date_debut` date NOT NULL,
  `mi_debut_journee` tinyint(1) NOT NULL,
  `date_fin` date NOT NULL,
  `mi_fin_journee` tinyint(1) NOT NULL,
  `annnee_reference` smallint(4) NOT NULL,
  `nombre_jours` float NOT NULL,
  `id_type_conge` smallint(5) unsigned NOT NULL,
  `ferme` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_proposition` (`id_proposition`),
  KEY `id_personne` (`id_personne`),
  KEY `id_type_conge` (`id_type_conge`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

/*--------------------------------------------------------------------------------*/

/* Table solde */

CREATE TABLE IF NOT EXISTS `solde` (
  `id_personne` smallint(5) unsigned NOT NULL,
  `total_cp` float NOT NULL,
  `total_q1` float NOT NULL,
  `total_q2` float NOT NULL,
  `annee_reference` int(4) NOT NULL,
   INDEX (id_personne),
  PRIMARY KEY (`id_personne`,`annee_reference`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- Contraintes pour la table `personne`
--
ALTER TABLE `personne`
  ADD CONSTRAINT `personne_ibfk_1` FOREIGN KEY (`id_entite`) REFERENCES `entite` (`id`),
  ADD CONSTRAINT `personne_ibfk_2` FOREIGN KEY (`id_pole`) REFERENCES `pole` (`id`),
  ADD CONSTRAINT `personne_ibfk_3` FOREIGN KEY (`id_modalite`) REFERENCES `modalite` (`id`),
  ADD CONSTRAINT `personne_ibfk_4` FOREIGN KEY (`id_fonction`) REFERENCES `fonction` (`id`);



--
-- Contraintes pour la table `solde`
--
ALTER TABLE `solde`
  ADD CONSTRAINT `solde_ibfk_2` FOREIGN KEY (`id_personne`) REFERENCES `personne` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;