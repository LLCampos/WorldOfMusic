<?php

//// Funcoes ////


function getListOfAllCountries() {

	//Recolher lista com todos os paises do url com formato json
    $url = "http://restcountries.eu/rest/v1/all";

    $rsp = file_get_contents($url);
    $countries_info = json_decode($rsp);

    $country_list = array();

    foreach ($countries_info as $country) {
		$country_array = array();

		//Guardar cada elemento num array de pais
        $country_name = $country->name;
		$country_name_alpha2 = $country-> alpha2Code;
		$country_capital= $country->capital;
		$country_population= $country->population;
		$country_region= $country->region;
		$country_subregion= $country->subregion;
		array_push($country_array, $country_name, strtolower($country_name_alpha2), $country_capital, $country_population, $country_region, $country_subregion);

		//Guardar o pais na lista de paises
		array_push($country_list,$country_array);
    }

    return $country_list;
}


function getFlags($country_list){

	$links_list = [];

	//Recolher nomes em alpha2 dos paises
	foreach($country_list as $country){
		$country_name_alpha2 = $country[1];
		$link = "http://www.geonames.org/flags/x/". $country_name_alpha2 . ".gif";
		array_push($links_list, $link);
	}

	return $links_list;
}


//////Funcoes para remover acentos//////
function remove_accent($str)
{
  $a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ');
  $b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o');

  return str_replace($a, $b, $str);
}

function post_slug($str)
{
  return preg_replace(array('/[^a-zA-Z0-9 -]/', '/[ -]+/', '/^-|-$/'),
  array('', '-', ''), remove_accent($str));
}
//////


function populateCountryTableWithCountries($country_list) {

    require "/home/aw008/database/connect_to_database.php";;

	foreach ($country_list as $country) {

		//Recolher informacao sobre os paises e de seguida converter para string com o quote
		$country_name = $country[0];
		$country_name_alpha2 = $country[1];
		$country_capital = $country[2];
		$country_population = $country[3];
		$country_region = $country[4];
		$country_subregion = $country[5];

		$country_name = $conn->quote($country_name);
		$country_name_alpha2 = $conn->quote($country_name_alpha2);
		$country_capital = $conn->quote(post_slug($country_capital));
		$country_region = $conn->quote($country_region);
		$country_subregion = $conn->quote($country_subregion);

		//Inserir o pais na BD
        $sql = "INSERT INTO Country (name, name_alpha2 ,capital,population,region,subregion)
		VALUES ($country_name, $country_name_alpha2, $country_capital, $country_population, $country_region, $country_subregion);";


        try {
            $conn->exec($sql);
        } catch(PDOException $e) {
            echo $e->getMessage() . "\n";
        }
    }

    require "/home/aw008/database/disconnect_database.php";
}

//Fazer update as tabelas para adicionar as bandeiras
function addFlagsToTable($flags_list){

	require "/home/aw008/database/connect_to_database.php";;

	foreach($flags_list as $flag){
		$flag= $conn->quote($flag);
		$name_flag = substr($flag,33,2);
		$name_flag = $conn->quote($name_flag);

		$sql = "UPDATE Country SET flag_img_url = $flag WHERE name_alpha2 = $name_flag;";

		try {
            $conn->exec($sql);
        } catch(PDOException $e) {
            echo $e->getMessage() . "\n";
        }
	}

	require "/home/aw008/database/disconnect_database.php";
}

//Recolher descricao da musica do pais
function getMusicDescription($country_list, $startSlice, $numberofCountries){

	$country_music_list = array();
	$country_list = array_slice($country_list,$startSlice,$numberofCountries);
	//Recolher nomes dos paises
	foreach ($country_list as $country){
		$country_name = $country[0];

		//Corrigir nomes de paises com sufixo "The" e nomes com caracteres errados
		if((substr($country_name,0,3)) === "The"){
			$country_name = str_replace('The', 'the', $country_name);
		}
		$country_name = post_slug($country_name);

		//Contruir o URL
		$url = "https://en.wikipedia.org/w/api.php?format=json&action=query&prop=extracts&exintro=&explaintext=&titles=Music_of_" . $country_name ;
		$url = str_replace(' ', '_', $url);
		$url = str_replace('-', '_', $url);
		$rsp = file_get_contents($url);
		$countries_music = json_decode($rsp,true);

		//Retirar o conteudo pertinente (Texto introdutorio de cada pagina)
			foreach($countries_music ['query']['pages'] as $country){

				//Verificar se o pais tem uma pagina de musica e adicionar o texto correto em cada caso
				if(array_key_exists('pageid',$country)){
					$description_text = $country['extract'];
					if(strlen($description_text) > 0){
						$description = $country['extract'];
					}
					else{
						$description = "There isn't available information about music in this country.";
					}
				}
				else{
					$description = "There isn't available information about music in this country.";
				}

				array_push($country_music_list, $description);
			}
	}
	return $country_music_list;
}

function addMusicDescription($music_description_list, $counter){

	require "/home/aw008/database/connect_to_database.php";;

	foreach($music_description_list as $music){
		$music = $conn->quote($music);
		$country_id = $conn->quote($counter);

		$sql = "UPDATE Country SET description_of_music = $music WHERE id = $counter;";

		//Incrementar o contador
		$counter = ++$counter;

		try {
            $conn->exec($sql);
        } catch(PDOException $e) {
            echo $e->getMessage() . "\n";
        }

	}
	require "/home/aw008/database/disconnect_database.php";
}

//// Correr as funções ////

//Sacar lista de paises
$country_list = getListOfAllCountries();

//Sacar lista dos links para as bandeiras
$flags_list = getFlags($country_list);

//Popular tabela com dados (exceto bandeiras e descricao da musica)
//populateCountryTableWithCountries($country_list);

//Popular tabela com links das bandeiras (atualizacao)
addFlagsToTable($flags_list);

//Obter descricao da musica de cada pais
//A api do wikipedia apenas aguenta um numero limitado de pedidos pelo que cada um dos pedidos em baixo deve ser executado individualmente
// $music_description_list1 = getMusicDescription($country_list,0,25);
// $music_description_list2 = getMusicDescription($country_list,25,25);
// $music_description_list3 = getMusicDescription($country_list,50,25);
// $music_description_list4 = getMusicDescription($country_list,75,25);
// $music_description_list5 = getMusicDescription($country_list,100,25);
// $music_description_list6 = getMusicDescription($country_list,125,25);
// $music_description_list7= getMusicDescription($country_list,150,25);
// $music_description_list8 = getMusicDescription($country_list,175,25);
// $music_description_list9 = getMusicDescription($country_list,200,25);
// $music_description_list10 = getMusicDescription($country_list,225,25);

//Popular tabela com descricao da sua musica, apenas um grupo de paises de cada vez (em conjunto com um dos anteriores)
// addMusicDescription($music_description_list1,1);
// addMusicDescription($music_description_list2,26);
// addMusicDescription($music_description_list3,51);
// addMusicDescription($music_description_list4,76);
// addMusicDescription($music_description_list5,101);
// addMusicDescription($music_description_list6,126);
// addMusicDescription($music_description_list7,151);
// addMusicDescription($music_description_list8,176);
// addMusicDescription($music_description_list9,201);
// addMusicDescription($music_description_list10,226);

?>
