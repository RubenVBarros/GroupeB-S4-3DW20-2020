<?php

	//On inclut le fichier twig.php qui est 
	include 'inc.twig.php';
	//ajout du 'e' dans include

	//On chage la page index.tpl pour qu'elle s'affiche
	$template_index = $twig->loadTemplate('index.tpl');
	//ajout du point virgule

	//On veut la météo des 3 prochains jours
	$n_jours_previsions = 3;

	//On veut savoir la météo de Limoges
	$ville = "Limoges"; 

	//~ Clé API
	//~ Si besoin, vous pouvez générer votre propre clé d'API gratuitement, en créant 
	//~ votre propre compte ici : https://home.openweathermap.org/users/sign_up
	$apikey = "10eb2d60d4f267c79acb4814e95bc7dc";

	$data_url = 'http://api.openweathermap.org/data/2.5/forecast/daily?appid='.$apikey.'&q='.$ville.',fr&lang=fr&units=metric&cnt='.$n_jours_previsions;

	//On récupère les données de l'API en JSON et on les décode
	$data_contenu = file_get_contents($data_url);
	$_data_array = json_decode($data_contenu, true);

	//On récupère le nom de la ville et on récup les prévisions de cette ville
	$_ville = $_data_array['city']['name'];
	//$_cp = $_data_array['city']; ajout du code postal - (amélioration du code)
	$_journees_meteo = $_data_array['list'];
	//Suppression du 'e' dans list

	//Cette boucle permet de récupérer l'image et la météo des trois jours
	for ($i = 0; $i < count($_journees_meteo); $i++) {
		$_meteo = getMeteoImage($_journees_meteo[$i]['weather'][0]['icon']);
		
		$_journees_meteo[$i]['meteo'] = $_meteo;
	}

	//On affiche les données, la météo, les prévisions et la ville
	echo $template_index->render(array(
		'_journees_meteo'	=> $_journees_meteo,

		//ajout de l'underscore dans la variable de droite => $_ville
		'_ville'			=> $_ville,

		//'_cp' => $_cp,  ajout du code postal  - (amélioration du code)

		'n_jours_previsions'=> $n_jours_previsions
	));

	//On obtient l'image des météos si il fait beau ce sera un soleil etc...
	function getMeteoImage($code){
		if(strpos($code, 'n'))
		{//manque l'accolade
			return 'entypo-moon';
		}
		
	//c'est la liste de toutes les icones pour le type de temps
	$_icones_meteo =array(
		'01d' => 'entypo-light-up',
		'02d' => 'entypo-light-up',
		'03d' => 'entypo-cloud',
		//une virgule était manquante
		'04d' => 'entypo-cloud',
		'09d' => 'entypo-water', 
		'10d' => 'entypo-water',
		'11d' => 'entypo-flash',
		'13d' => 'entypo-star', 
		'50d' => 'entypo-air');

		//Si l'icone existe on l'affiche sinon on l'affiche pas
		if(array_key_exists($code, $_icones_meteo)){
			//un { était manquant pour le if
			return $_icones_meteo[$code];
		}else{
			return 'entypo-help';
		}
	}	
//on rajoute la fermeture du php
?>