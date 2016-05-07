<?php

//funcao para procurar video usando uma ou várias keywords (uma string)
//para mais informacao sobre os parametros deste metodo: https://developers.google.com/youtube/v3/docs/search/list#parameters

function searchVideo($keyword){

    require "/home/aw008/variables/sensible_info.php";

	//variaveis de base
	$keyword = urlencode($keyword);
	$url = "https://www.googleapis.com/youtube/v3/search?";
	$key = $google_api_key;
	$part = 'id';

	//variaveis possiveis de alterar (filtros)
	$maxResults = 1;
	$type = 'video';
	$videoEmbeddable = 'true';
	$order = 'relevance';

	//construir o url correto com os dados definidos
	$url = $url . 'part=' . $part . '&maxResults='. $maxResults . '&q=' . $keyword . '&type=' . $type . '&videoEmbeddable=' . $videoEmbeddable .
	'&key=' . $key . '&order=' . $order;

	//fazer pedido a api
	$rsp = file_get_contents($url);
    $video = json_decode($rsp, true);

	//Devolve o id de um video caso a procura tenha sido valida, caso contrario avisa que o nome do artista é invalido
	if(isset($video['items'][0]['id']['videoId'])){
		return $video['items'][0]['id']['videoId'];
	}
	else{
		return 'invalid name for artist';
	}

}


# function connectToLastFM($params) {
#     # Makes a call to Lastfm API and returns results.
#
#     $encoded_params = array();
#
#     foreach ($params as $k => $v){
#       $encoded_params[] = urlencode($k).'='.urlencode($v);
#     }
#
#     $url = "http://ws.audioscrobbler.com/2.0/?".implode('&', $encoded_params);
#
#     $rsp = file_get_contents($url);
#     $rsp_obj = json_decode($rsp, true);
#
#     return $rsp_obj;
# }


function getArtistTopTrack($artist_name) {
    /* Requires: $artist_name is a string representing the name of a artist. */

    require "/home/aw008/variables/sensible_info.php";
    include_once "/home/aw008/database/lastfm_api/lastfm_functions.php";

    $params = array(
        'api_key' => $lastfm_api_key,
        'method' => "artist.getTopTracks",
        'artist' => $artist_name,
        'format' => 'json',
		'limit' => 1
        );

    $result = connectToLastFM($params);

	return $result->toptracks->track[0]->name;

}


function searchArtistsVideos(){

	include "/home/aw008/database/connect_to_database.php";

	$artists = [];
	$video_ids = [];

	//Sacar a lista de artistas na nossa bd
	$sql = "SELECT name FROM Artist";
    $artists = $conn->query($sql)->fetchAll(PDO::FETCH_COLUMN);

	//Procurar o video mais relevante do artista (o seu id)
	foreach($artists as $artist){
		echo nl2br("searching video for artist: " . $artist. PHP_EOL); //serve apenas para acompanhar o correr deste php na consola
		$topTrack = getArtistTopTrack($artist);
		$video_id = searchVideo($artist . ' ' . $topTrack);
		array_push($video_ids, $video_id);
	}

	//Juntar os dois arrays para que se tenha um array com a estrutura [artista]=>video_id
	$artists_and_videos = array_combine($artists, $video_ids);

	include "/home/aw008/database/disconnect_database.php";

	return $artists_and_videos;
}


function updateArtistsVideos($artists_and_videos){

	include "/home/aw008/database/connect_to_database.php";

	foreach($artists_and_videos as $artist=>$video){
		echo nl2br("updating artist: " . $artist. PHP_EOL); //serve apenas para acompanhar o correr deste php na consola
		$artist = $conn->quote($artist);
		$video = $conn->quote($video);

		$sql = "UPDATE Artist SET music_video = $video WHERE name = $artist;";

		try {
            $conn->exec($sql);
        }
		catch(PDOException $e) {
            echo $e->getMessage() . "\n";
        }
	}

	include "/home/aw008/database/disconnect_database.php";

}

function searchTopVideo($artist){

	//Descobrir qual a track mais popular do artista
	$topTrack = getArtistTopTrack($artist);

	//Procurar o video
	$video_id = searchVideo($artist . ' ' . $topTrack);

	return $video_id;

}

//Running the functions
//$artists_and_videos = searchArtistsVideos();
//updateArtistsVideos($artists_and_videos);
?>
