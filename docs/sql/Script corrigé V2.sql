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

CREATE TABLE IF NOT EXISTS personne
	(
	id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
	nom VARCHAR(20) NOT NULL,
	prenom VARCHAR(20) NOT NULL,
	date_entree DATE NOT NULL,
	date_debut DATE NOT NULL,
	date_fin DATE NOT NULL,
	id_entite SMALLINT UNSIGNED NOT NULL,
	id_pole SMALLINT UNSIGNED NOT NULL,
	id_modalite SMALLINT UNSIGNED NOT NULL,
	id_fonction SMALLINT UNSIGNED NOT NULL,
	pourcent SMALLINT UNSIGNED NOT NULL,
	centre_service BOOLEAN NOT NULL,
	stage BOOLEAN NOT NULL,
	total_q1 FLOAT(4,1) NOT NULL,
	total_q2 FLOAT(4,1) NOT NULL,
	total_cp FLOAT(4,1) NOT NULL,
	PRIMARY KEY (id),
	FOREIGN KEY (id_entite) REFERENCES entite(id),
	FOREIGN KEY (id_pole) REFERENCES pole(id),
	FOREIGN KEY (id_modalite) REFERENCES modalite(id),
	FOREIGN KEY (id_fonction) REFERENCES fonction(id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
	
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

CREATE TABLE IF NOT EXISTS conge
	(
	id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
	id_proposition SMALLINT UNSIGNED NOT NULL, 	
	id_personne SMALLINT UNSIGNED NOT NULL,
	date_debut DATE NOT NULL,
	mi_debut_journee BOOLEAN NOT NULL, 
	date_fin DATE NOT NULL,
	mi_fin_journee BOOLEAN NOT NULL,
	nombre_jours FLOAT(4,1) NOT NULL,
	id_type_conge SMALLINT UNSIGNED NOT NULL,
	annee_reference SMALLINT NOT NULL,
	ferme BOOLEAN NOT NULL,
	PRIMARY KEY (id),
	index (id_proposition), 
	index (id_personne), 
	index (id_type_conge),
	FOREIGN KEY (id_proposition) REFERENCES proposition(id),
	FOREIGN KEY (id_personne) REFERENCES personne(id),
	FOREIGN KEY (id_type_conge) REFERENCES type_conge(id)
  ) ENGINE=InnoDB;
