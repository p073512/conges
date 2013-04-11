<?php
// PTRI - Test commit sur le fichier index
//définition de la constante 'APPLICATION_PATH'
defined('APPLICATION_PATH')
	|| define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

//définition de la constante 'APPLICATION_ENV'
defined('APPLICATION_ENV')
	|| define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));

require_once 'Zend/Application.php';

//création d'un objet Zend_Application qui est la classe de base et le point
//d'entrée de l'application
//le constructeur contient deux arguments:
//   - argument 1: représente l'environnement actuel dans lequel tourne
//                 l'application
//   - argument 2: chemin vers un fichier Zend_Config à charger pour la
//                 configuration de l'application, l'argument 1 détermine la
//                 section de configuration à charger depuis le fichier
$application = new Zend_Application(
		APPLICATION_ENV,
		APPLICATION_PATH . '/configs/application.ini');

//appel de la méthode bootstrap() du bootstrap pour lancer l'application
//toutes les méthodes du bootstrap préfixées par '_init' sont appelées
//(cf. application/Bootstrap.php)
$application->bootstrap();

//appel de la méthode run() du bootstrap pour lancer le dispatch de
//l'application
$application->run();

