<?php
// Sometimes, htmlspecialchars seems to return empty strings for unknown reasons (for me), this supposedly does exactly the same as htmlspecialchars (with default args) but always returns something.
function my_htmlspecialchars($str) {
	return str_replace(
		array('&', '<', '>', '"'),
		array('&amp;', '&lt;', '&gt;', '&quot;'),
		$str
	);
}

if (isset($_GET['lang'])){$langue=strip_tags($_GET['lang']);}else{$langue=strip_tags(lang());}
clear_cache();// vire les thumbs de plus de trois minutes
define('LANGUAGE',$langue);
//define('RACINE','http://'.$_SERVER['SERVER_NAME'].'/');
define('RACINE','http://'.$_SERVER['HTTP_HOST'] );
define('USE_WEB_OF_TRUST',true);
define('WOT_URL','http://www.mywot.com/scorecard/');
define('REGEX_WEB','#(?<=<h3 class="r"><a href="/url\?q=)([^&]+).*?>(.*?)</a>.*?(?<=<span class="st">)(.*?)(?=</span>)#s');
define('REGEX_PAGES','#&start=([0-9]+)|&start=([0-9]+)#');
define('REGEX_IMGTHUMBS','#imgrefurl=([^&]+).*?imgurl=([^&]+).*?w=([0-9]+).*?h=([0-9]+).*?"th":([0-9]+).*?"tu":"(https://[^"]+).*?"tw":([0-9]+)#');
 // old regexes (just in case)
 /*define('REGEX_IMG','#(?<=imgurl=)(.*?)&imgrefurl=(.*?)&.*?h=([0-9]+)&w=([0-9]+)&sz=([0-9]+)|(?<=imgurl=)(.*?)&imgrefurl=(.*?)&.*?h=([0-9]+)&w=([0-9]+)&sz=([0-9]+)#s');
 define('REGEX_THMBS','#<img.*?height="([0-9]+)".*?src="([^"]+)".*?width="([0-9]+)"#s');*/

define('REGEX_VID','#(?:<img.*?src="([^"]+)".*?width="([0-9]+)".*?)?<h3 class="r">[^<]*<a href="/url\?q=(.*?)(?:&|&).*?">(.*?)</a>.*?<cite[^>]*>(.*?)</cite>.*?<span class="(?:st|f)">(.*?)(?:</span></td>|</span><br></?div>)#');
define('REGEX_VID_THMBS','#<img.*?src="([^"]+)".*?width="([0-9]+)"#');
define('TPL','<li class="result"><a target="_blank" rel="noreferrer" href="#link"><h3 class="title">#title</h3>#link</a>#wot<p class="description">#description</p></li>');
define('TPLIMG','<div class="image zoom-gallery" ><p><a rel="noreferrer" href="#link" title="" data-source="#link">#thumbs</a></p><p class="description">#W x #H <a class="source" href="#site" title="#site"> &#9658;</a></p></div>');
define('TPLVID','<div class="video"><h3><a class="popup-youtube" rel="noreferrer" href="#link" title="#link">#titre</a></h3><a class="thumb popup-youtube" rel="noreferrer" href="#link" title="#link">#thumbs</a><p class="site">#site</p><p class="description">#description</p></div>');
define('LOGO1','<a href="'.RACINE.'"><em class="g">G</em><em class="o1">o</em>');
define('LOGO2','<em class="o2">o</em><em class="g">g</em><em class="o1">o</em><em class="o3">l</em></a>');
define('CAPCHA_DETECT','<form action="Captcha" method="get"><input type="hidden" name="continue"');
define('SAFESEARCH_ON','&safe=on');
define('SAFESEARCH_IMAGESONLY','&safe=images');
define('SAFESEARCH_OFF','&safe=off');
define('SAFESEARCH_LEVEL',SAFESEARCH_ON);// SAFESEARCH_ON, SAFESEARCH_IMAGESONLY, SAFESEARCH_OFF

define('URL','https://www.google.com/search?hl='.LANGUAGE.SAFESEARCH_LEVEL.'&id=hp&q=');
define('URLIMG','&source=lnms&tbm=isch&biw=1920&bih=1075');
define('URLVID','&tbm=vid');
define('VERSION','v1.51a');
define('USE_GOOGLE_THUMBS',false);
//define('THEME','css/style_krisfr.css');
define('STARTTHEME','css/style_krisfr-start.css');
define('ENDTHEME','css/style_krisfr-end.css');

if (!USE_GOOGLE_THUMBS){ 
	session_start();
	if (!isset($_SESSION['ID'])){$_SESSION['ID']=uniqid();}
	define('UNIQUE_THUMBS_PATH','thumbs/'.$_SESSION['ID']);
	if (!is_dir('thumbs')){mkdir('thumbs');}// crée le dossier thumbs si nécessaire
}
$lang['fr']=array(
	'previous'=>strip_tags('Page précédente'),
	'next'=>'Page suivante',
	'Google has received too mutch requests from this IP, try again later or with another version af googol.'=>strip_tags('Google a reçu trop de requêtes de cette IP et la bloque: essaie plus tard !'),
	'The thumbnails are temporarly stored in this server to hide your ip from Google…'=>strip_tags('Les miniatures sont temporairement récupérées sur ce serveur, google n\'a pas votre IP…'),
	'Search anonymously on Google (direct links, fake referer)'=>strip_tags('Rechercher anonymement sur Google (liens directs et referrer caché)'),
	'Free and open source (please keep a link to warriordudimanche.net for the author ^^)'=>strip_tags('Libre et open source, merci de laisser un lien vers warriordudimanche.net pour citer l\'auteur ;)'),
	'Googol - google without lies'=>'Googol - Google sans mensonge',
	'GitHub'=>'GitHub',
	'no results for'=>strip_tags('pas de résultat pour '),
	'by'=>'par',
	'search '=>'recherche ',
	'Videos'=>strip_tags('Vidéos'),
	'Search'=>'Rechercher',
	'Otherwise, use a real Search engine !'=>'Sinon, utilisez un vrai moteur de recherche !',
	'Filter on'=>strip_tags('Filtre activé'),
	'Filter off'=>strip_tags('Filtre désactivé'),
	'Filter images only'=>strip_tags('Filtre activé sur les images'),
	'red'=>'rouge',
	'yellow'=>'jaune',
	'green'=>'vert',
	'white'=>'blanc',
	'gray'=>'gris',
	'teal'=>'sarcelle',
	'black'=>'noir',
	'pink'=>'rose',
	'blue'=>'bleu',
	'brown'=>'marron',
	'Black_and_white'=>'Noir_et_blanc',
	'Color'=>'couleur',
	'all colors'=>'toutes les couleurs',
	'all sizes'=>'toutes les tailles',
	'Select a color'=>'Filtrer par couleur',
	'Select a size'=>'Filtrer par taille',
	'Big'=>'Grande',
	'Medium'=>'Moyenne',
	'Icon'=>'Petite',
	'Project on Github'=>'Projet sur Github',
	'Give a beer to the author!'=>strip_tags('Offrez une bière à l\'auteur'),
	'By'=>'Par',
	'modified by'=>strip_tags('modifié par'),
	);

#######################################################################
## Fonctions
#######################################################################
function aff($a,$stop=true,$line){echo 'Arret a la ligne '.$line.' du fichier '.__FILE__.'<pre>';var_dump($a);echo '</pre>';if ($stop){exit();}}
function msg($m){global $lang;if(isset($lang[LANGUAGE][$m])){return $lang[LANGUAGE][$m];}else{return $m;}}
function lang($default='fr'){if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])){$l=explode(',',$_SERVER['HTTP_ACCEPT_LANGUAGE']);return substr($l[0],0,2);}else{return $default;}}
function return_safe_search_level(){
	if (SAFESEARCH_LEVEL==SAFESEARCH_ON){return '<b class="ss_on">'.msg('Filter on').'</b>';}
	if (SAFESEARCH_LEVEL==SAFESEARCH_OFF){return '<b class="ss_off">'.msg('Filter off').'</b>';}
	if (SAFESEARCH_LEVEL==SAFESEARCH_IMAGESONLY){return '<b class="ss_images">'.msg('Filter images only').'</b>';}
}
function Random_referer(){
	return array_rand(array(
		'http://oudanstoncul.com.free.fr/‎',
		'http://googlearretedenousfliquer.fr/‎',
		'http://stopspyingme.fr/‎',
		'http://spyyourassfuckinggoogle.fr/‎',
		'http://dontfuckinglookatme.fr/‎',
		'http://matemonculgoogle.fr/‎',
		'http://auxarmescitoyens.fr/‎',
		'http://jetlametsavecdugravier.con/‎',
		'http://lesdeuxpiedsdanstagueule.fr/‎',
		'http://moncoudedanstabouche.con/‎',
		'http://monpieddanston.uk/‎',
		'http://bienfaitpourvosgueul.es/‎',
		'http://pandanstesdents.fr/‎',
		'http://tupuessouslesbras.fr/‎',
		'http://mangetescrottesdenez.fr/‎',
		'http://jtepourristesstats.fr/‎',
		'http://ontecompissevigoureusement.com/‎',
		'http://lepoingleveetlemajeuraussi.com/‎',
	));
}
function file_curl_contents($url,$pretend=true){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept-Charset: UTF-8'));
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,  FALSE);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, $url);
	if (!ini_get("safe_mode") && !ini_get('open_basedir') ) {curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);}
	curl_setopt($ch, CURLOPT_MAXREDIRS, 10);if ($pretend){curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:19.0) Gecko/20100101 Firefox/19.0');}
	curl_setopt($ch, CURLOPT_REFERER, random_referer());// notez le referer "custom"

	$data = curl_exec($ch);
	$response_headers = curl_getinfo($ch);

	// Google seems to be sending ISO encoded page + htmlentities, why??
	if($response_headers['content_type'] == 'text/html; charset=ISO-8859-1') $data = html_entity_decode(iconv('ISO-8859-1', 'UTF-8//TRANSLIT', $data)); 
	
	# $data = curl_exec($ch);

	curl_close($ch);

	return $data;
}
function add_search_engine(){
	if(!is_file('googol.xml')){
		file_put_contents('googol.xml', '<OpenSearchDescription xmlns="http://a9.com/-/spec/opensearch/1.1/"
		  xmlns:moz="http://www.mozilla.org/2006/browser/search/">
		  <ShortName>Googol</ShortName>
		  <Description>'.msg('Googol - Google without lies').'</Description>
		  <InputEncoding>UTF-8</InputEncoding>
		  <Image width="32" height="32">data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAABCFJREFUeNrEV21MW2UUfm7v7QcwWqj9YBQyEAdqwBGGY3xkQRlLRjLnsmnUOOOSJYsmxvhDo/7VRH+YaPxhYqLRiAkuccbh5jbFRZgR3IbpwphG0wxaSgOUXgot7W177/V9L/DDrLv3bcK2kzy97e15zz3vOc85573c4bfOY112ruNuyATBKP0iQNVufETwKu6ufElwTFBVtf0ePJzKiwQniQNoLmRVYyUHT5kNT3f7YLbatHtkE4gvr+LsWATT82kEoszm2gVFVZk0K+0qnulwoM7nAG82gxfM2n2OM5HfAjzeYhw76EIuk8HV6zP4engBYoozMmuhKTB8eJ0LeHlvGc74VzHwexyz8f//73NweNBnRU9rJWq3VaBzVwNqKkrwdv9NpHP6Thg6QHfe12TDO6dFiKv5dWaWVIIUhiYDeK4rhid7m+D2utDkm8blKUXXvommQA9HO4rw1Wgai0l9vQ2cGotBSiaQTafwz5xsqC+oOg4+QEIfJrtbTLCTtLyIQyqxjIGLIaZ1gqLcPgVVTko2CxQlxexAx3YbRv4M46cJtjW6HOA4DntaqiApPC5eEzEj6uezs94Kj8OCjy8sMTusG4EbYYlEQMChfU040C1BzmZJzed3wmTiwfE8hq8GYeVFpLKsDuiQIBgDBkcCOLyvEcX2MvC8QMNyW31VUdDbaSNlCbzRH2JMgaJfhv3DIubFcRx5rAZljhItLXkjQJyzFhVrTcphL4aRXaYUbMg5f5Jg0lDv2T0ezdFvfw1CYXaAsRWziN2SwXdDf+FHf2JzSJhPdtaYsbvBjrb6LWuVQsgXislaCn6biOLMeLwge0yzYENe6fPi8UercWUygte+CCK6osBVasKJ/T50tdaiurIcgYifVE+W2aaJMpcFT7U7sL/7ISQlBR9+P4OFeE67T6/vfjONqdA8vF433jvRgupyDqx2TQr5MIKzBDja97A2/3+4NIVEWr5FZ+DnAGQ5h1KnG4c6PGCxS2Gi5WKEtu0lsG0p1UK2nJDy6oz9TQaQlNac7GmrAYtdCiYOVDhthGQ8NnTzrUlKZLqRCNA+QQl5H+FodEVlaUSKoVIimdUeSjshdUZvjSzLWIonCTdkRhISw0b4N5wgRy0JJjIXdjVuzatTZFnrhlkphV/+mAaLXQqmKrgeTCOysKql4f7areh5xH6LTm9zuTaQZkNhnBqZZa4C3U74wfNubKtyacSyWbNYic5rUXj9hRZymhnHkH9t7O7d4cDxJ+oRCUfw5qfXMLcsM/cBrvnIZy+R6yesCw7udqK1wY0ddXbkshJlJObEDM5dmcPg5aVCu/f7AgqcBadHFzVslhTUiu+AZOh54OY9dOAGLcPzBJ+zls0m4izBSXLG0lJwnL4oEnTRxneHdx1bfzUfpD/+E2AAqmeV253DYKAAAAAASUVORK5CYII=</Image>
		  <Url type="text/html" method="get" template="'.RACINE.'">
		  <Param name="q" value="{searchTerms}"/>
		  </Url>
		  <moz:SearchForm>'.RACINE.'</moz:SearchForm> 
		</OpenSearchDescription>');
	}
}

function parse_query($query,$start=0){
	global $mode,$filtre;
	if ($mode=='web'){ 
		$page=file_curl_contents(URL.str_replace(' ','+',urlencode($query)).'&start='.$start.'&num=100',false);
		if (stripos($page,CAPCHA_DETECT)!==false){
			exit(msg('Google has received too mutch requests from this IP, try again later or with another version af googol.'));
		}
		if (!$page){return false;}
		preg_match_all(REGEX_WEB, $page, $r);
		preg_match_all(REGEX_PAGES,$page,$p);
		$p=count($p[2]);

		$retour=array(
			'links'=>$r[1],
			'titles'=>array_map('strip_tags',$r[2]),
			'descriptions'=>array_map('strip_tags',$r[3]),
			'nb_pages'=>$p,
			'current_page'=>$start,
			'query'=>$query,
			'mode'=>$mode
			);
		return $retour;
	}elseif ($mode=='images'){ 
		if (!empty($filtre)){$f='&tbs='.$filtre;}else{$f='';}
		$page=file_curl_contents(URL.str_replace(' ','+',urlencode($query)).URLIMG.$f.'&start='.$start);                                
		if (!$page){return false;}                        
		preg_match_all(REGEX_PAGES,$page,$p);
		preg_match_all(REGEX_IMGTHUMBS,$page,$r);
		//preg_match_all(REGEX_THMBS,$page,$t);preg_match_all(REGEX_IMG,$page,$r);                
		$p=count($p[2]);
		$retour=array(
			'site'=>$r[1],
			'links'=>$r[2],
			'h'=>$r[4],
			'w'=>$r[3],
			'sz'=>0,//$r[5],
			'thumbs'=>$r[6],
			'thumbs_w'=>$r[7],
			'thumbs_h'=>$r[5],
			'nb_pages'=>$p,
			'current_page'=>$start,
			'query'=>$query,
			'mode'=>$mode
			);
		return $retour;	
	}elseif($mode=="videos"){
		$page=file_curl_contents(URL.str_replace(' ','+',urlencode($query)).URLVID.'&start='.$start,false);
		if (!$page){return false;}
		preg_match_all(REGEX_VID,$page,$r);
		preg_match_all(REGEX_PAGES,$page,$p);
		$p=count($p[2]);
		$retour=array(
			'site'=>$r[5],
			'titre'=>$r[4],
			'links'=>array_map('urldecode', $r[3]),
			'description'=>array_map('description_sanitise',$r[6]),
			'thumbs'=>$r[1],
			'thumbs_w'=>$r[2],
			'nb_pages'=>$p,
			'current_page'=>$start,
			'query'=>$query,
			'mode'=>$mode
			);
		return $retour;		
	}
}
function width($w,$h,$nh){return round(($nh*$w)/$h);}
function render_query($array){
	global $start,$langue,$mode,$couleur,$taille;
	if (!is_array($array)||count($array['links'])==0){echo '<div class="noresult"> '.msg('no results for').' <em>'.strip_tags($array['query']).'</em> </div>';return false;}
	
	if ($mode=='web'){
		echo '<ol start="'.$start.'">';
		$nbresultsperpage=100;
		$filtre='';
		foreach ($array['links'] as $nb => $link){
			$r=str_replace('#link',urldecode($link),TPL);
			$r=str_replace('#title',$array['titles'][$nb],$r);
			$d=str_replace('<br>','',$array['descriptions'][$nb]);
			$d=str_replace('<br/>','',$d);
			$r=str_replace('#description',$d,$r);
			if (preg_match('#http://(.*?)/#',$link,$domaine)){
				$domaine='<a target="_blank" class="wot-exclude wot" href="'.WOT_URL.$domaine[1].'" title="View scorecard"> </a>';
				$r=str_replace('#wot',$domaine,$r);
			}else{$r=str_replace('#wot','',$r);}

			echo $r;
		}
		echo '</ol>';
	}elseif ($mode=='images'){
		$nbresultsperpage=20;
                        $filtre='&couleur='.$couleur.'&taille='.$taille;
                        foreach ($array['links'] as $nb => $link){
                                $r=str_replace('#link',$link,TPLIMG);
                                $r=str_replace('#SZ',$array['sz'][$nb],$r);
                                $r=str_replace('#H',$array['h'][$nb],$r);
                                $r=str_replace('#W',$array['w'][$nb],$r);
                                $r=str_replace('#site',$array['site'][$nb],$r);
                                $common_height=min($array['thumbs_w']);
                                
                                if (!USE_GOOGLE_THUMBS){
                                        $repl='<img src="'.grab_google_thumb($array['thumbs'][$nb]).'" style="width:'.width($array['w'][$nb],$array['h'][$nb],$common_height).'px;height:'.$common_height.'px;"/>';
                                }else if (USE_GOOGLE_THUMBS){
                                        $repl='<img src="'.$array['thumbs'][$nb].'" style="width:auto;height:'.$common_height.'px;"/>';
                                }                                
                                $r=str_replace('#thumbs',$repl,$r);
                                echo $r;
                        } 
	}elseif($mode='videos'){ 
		$nbresultsperpage=10;
		$filtre='';
		foreach ($array['links'] as $nb => $link){
			$array['description'][$nb]=link2YoutubeUser($array['description'][$nb],$link);
			$r=str_replace('#link',$link,TPLVID);
			$r=str_replace('#titre',$array['titre'][$nb],$r);
			$r=str_replace('#description',$array['description'][$nb],$r);
			$r=str_replace('#site',$array['site'][$nb],$r);
			if (!USE_GOOGLE_THUMBS){
				$repl='<img src="'.grab_google_thumb($array['thumbs'][$nb]).'" width="'.$array['thumbs_w'][$nb].'" height="'.round($array['thumbs_w'][$nb]/1.33).'"/>';
			}else if (USE_GOOGLE_THUMBS){
				$repl='<img src="'.$array['thumbs'][$nb].'" width="'.$array['thumbs_w'][$nb].'" height="'.round($array['thumbs_w'][$nb]/1.33).'"/>';
			}				
			$r=str_replace('#thumbs',$repl,$r);
			echo $r;
		}

	}

	if($array['nb_pages'] != 0){
		echo '<hr/><p class="footerlogo">'.LOGO1.str_repeat('<em class="o2">o</em>', $array['nb_pages']-1).LOGO2.'</p><div class="pagination">';
		if ($start>0){echo '<a class="previous" title="'.msg('previous').'" href="?q='.urlencode($array['query']).'&mod='.$mode.'&start='.($start-$nbresultsperpage).'&lang='.$langue.$filtre.'">&#9668;</a>';}
		for ($i=0;$i<$array['nb_pages']-1;$i++){
			if ($i*$nbresultsperpage==$array['current_page']){echo '<em>'.($i+1).'</em>';}
			else{echo '<a href="?q='.urlencode($array['query']).'&mod='.$mode.'&start='.($i*$nbresultsperpage).'&lang='.$langue.$filtre.'">'.($i+1).'</a>';}
		}
		if ($start<($array['nb_pages']-2)*$nbresultsperpage){echo '<a class="next" title="'.msg('next').'" href="?q='.urlencode($array['query']).'&mod='.$mode.'&start='.($start+$nbresultsperpage).'&lang='.$langue.$filtre.'">&#9658;</a>';}
		
		echo  '</div>';
	}
}
function grab_google_thumb($link){
	$local = 'thumbs/'.md5($link).'.jpg';

	if(is_file($local)) return $local;

	if($thumb = file_curl_contents($link))
	{
		file_put_contents($local, $thumb);
		return $local;
	}

	return $link;
}
function link2YoutubeUser($desc,$link){
	if (stristr($link,'youtube.com')){
		$desc=preg_replace('#([Aa]jout[^ ]+ par )([^<]+)#','$1<a rel="noreferrer" href="http://www.youtube.com/user/$2?feature=watch">$2</a>',$desc);
	};
	return $desc;
}
function clear_cache($delay=180){
	$fs=glob('thumbs/*');
	if(!empty($fs)){
		foreach ($fs as $file){
			if (@date('U')-@date(filemtime($file))>$delay){
				unlink ($file);
			}
		}
	}
}
function is_active($first,$second){
	if ($first==$second){
		echo 'active';
	}else{
		echo '';
	}
}
	
#######################################################################
## Gestion GET
#######################################################################
if (isset($_GET['mod'])){$mode=strip_tags($_GET['mod']);}else{$mode='web';}
if (isset($_GET['start'])){$start=strip_tags($_GET['start']);}else{$start='';}
if (!empty($_GET['couleur'])&&empty($_GET['taille'])){$filtre=$couleur=strip_tags($_GET['couleur']);$taille='';}
elseif (!empty($_GET['taille'])&&empty($_GET['couleur'])){$filtre=$taille=strip_tags($_GET['taille']);$couleur='';}
elseif (!empty($_GET['taille'])&&!empty($_GET['couleur'])){$taille=strip_tags($_GET['taille']);$couleur=strip_tags($_GET['couleur']);$filtre=$couleur.','.$taille;}
else{$filtre=$taille=$couleur='';}
if (isset($_GET['q'])){
	$q_raw=$_GET['q'];
	$q_txt=strip_tags($_GET['q']);
	$title='Googol '.msg('search ').$q_txt;
}else{
	$q_txt=$q_raw='';$title=msg('Googol - google without lies');
}

?>
<!DOCTYPE html>
<html dir="ltr" lang="<?php echo $langue ?>">
<head>
	<title><?php echo $title;?> </title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="robots" content="all" />
	<link rel="apple-touch-icon-precomposed" sizes="144x144" href="img/apple-touch-icon-144x144-precomposed.png">
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="img/apple-touch-icon-114x114-precomposed.png">
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="img/apple-touch-icon-72x72-precomposed.png">
	<link rel="apple-touch-icon-precomposed" href="img/apple-touch-icon-precomposed.png">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="img/apple-touch-icon-144x144-precomposed.png">
	<?php if (is_file('img/favicon.png')){echo '<link rel="shortcut icon" href="img/favicon.png" /> ';}?>
	
	<!--link rel="stylesheet" href="<?php echo THEME;?>" /-->
	<link rel="stylesheet" href="<?php echo ($q_raw=='')? STARTTHEME:ENDTHEME;?>" />
	<link rel="stylesheet" href="css/fontawesome.css" />
	<!--[if IE 7]>
		<link rel="stylesheet" href="css/fontawesome-ie7.css">
	<![endif]-->
	<link rel="stylesheet" href="css/magnific-popup.css" />
	<link rel="search" type="application/opensearchdescription+xml" title="<?php echo msg('Googol - google without lies'); ?>" href="<?php echo RACINE;?>/googol.xml">
	<link rel="author" href="humans.txt" />
	<!--[if IE]><script> document.createElement("article");document.createElement("aside");document.createElement("section");document.createElement("footer");</script> <![endif]-->
</head>
<body class="<?php echo $mode;?>">
<a class="nomobile wot-exclude" href="https://github.com/xoofoo-project/googol" title="<?php echo msg('Project on Github');?>"><img style="position: absolute; top: 0; left: 0; border: 0;" src="img/forkme_left_orange_ff7600.png" alt="Fork me on GitHub"></a>
<header>
	<div class="top">
		<!--[if lt IE 9]>
			<div id="chromeframe"><p>Votre navigateur internet n'est plus à jour. <a class="wot-exclude" href="http://browsehappy.com/" title="Mettez à jour votre navigateur" rel="external">Mettez à jour votre navigateur aujourd'hui</a> ou <a class="wot-exclude" href="http://www.google.com/chromeframe/?redirect=true" title="install Google Chrome Frame" rel="external">installez le plugin Google Chrome Frame</a> pour une meilleur navigation sur ce site.</p></div>
		<![endif]-->
		<div id="menu">
			<nav id="selectsearch">
			<?php 
				if ($mode=='web'){echo '<li class="active">Web</li><li><a href="?q='.urlencode($q_raw).'&mod=images&lang='.$langue.'">Images</a></li><li><a href="?q='.urlencode($q_raw).'&mod=videos&lang='.$langue.'">'.msg('Videos').'</a></li>';}
				else if($mode=='images'){echo '<li><a href="?q='.urlencode($q_raw).'&lang='.$langue.'">Web</a></li><li class="active">Images</li><li><a href="?q='.urlencode($q_raw).'&mod=videos&lang='.$langue.'">'.msg('Videos').'</a></li>';}
				else { echo '<li><a href="?q='.urlencode($q_raw).'&lang='.$langue.'">Web</a></li><li><a href="?q='.urlencode($q_raw).'&mod=images&lang='.$langue.'">Images</a></li><li class="active">'.msg('Videos').'</li>';}
			?>
				<div id="lang">
					<a class="<?php is_active(LANGUAGE,'fr'); ?>" href="?lang=fr">FR</a> 
					<a class="<?php is_active(LANGUAGE,'en'); ?>" href="?lang=en">EN</a>  -  
					<a class="popup-with-zoom-anim" href="#small-dialog" ><i class="icon-info-sign"></i></a>
				</div>
			</nav>
		</div>
		<div id="small-dialog" class="zoom-anim-dialog mfp-hide">
			<h1 class="version"><?php echo LOGO1.LOGO2; ?> <?php echo strip_tags(VERSION); ?></h1>
			<p><a class="wot-exclude" href="#" title="<?php echo msg('Free and open source (please keep a link to warriordudimanche.net for the author ^^)');?>">Licence</a></p>
			<p><?php echo msg('By');?> <a class="wot-exclude" href="http://warriordudimanche.net" title="warriordudimanche.net" target="_blank">Bronco</a> & <?php echo msg('modified by');?> <a class="wot-exclude" href="http://www.xoofoo.org" title="XooFoo.org" target="_blank">Krisfr</a></p>
			<p><a href="https://github.com/xoofoo-project/googol" title="<?php echo msg('GitHub');?>" class="wot-exclude" target="_blank"><?php echo msg('Project on Github');?></a> - <a class="wot-exclude" href="http://flattr.com/thing/1319925/broncowddSnippetVamp-on-GitHub" target="_blank" title="Flattr.com"><?php echo msg('Give a beer to the author!');?></a></p>
			<p><a class=" wot-exclude" href="http://duckduckgo.com" title="DuckDuckGo"><?php echo msg('Otherwise, use a real Search engine !');?></a></p>
		</div>
	</div>
	<form id="formsearch" action="" method="get" >
		<input type="hidden" name="lang" value="<?php echo LANGUAGE;?>"/>
		<span class="logo"><?php echo LOGO1.LOGO2; ?></span>
		<span>
			<input type="text" name="q" autofocus value="<?php  echo $q_txt; ?>"/>
			<?php if ($mode=='web'){echo '<span class="add-on"><i class="icon-globe"></i></span><input type="hidden" name="web"/>';}?>
			<?php if ($mode=='images'){echo '<span class="add-on"><i class="icon-camera-retro"></i></span><input type="hidden" name="img"/>';}?>
			<?php if ($mode=='videos'){echo '<span class="add-on"><i class="icon-film"></i></span><input type="hidden" name="vid"/>';}?>
			<button><i class="icon-search" alt="OK"></i></button>
			<!--input type="submit" value="OK"/-->
		</span>
		<?php
			if ($mode!=''){echo '<br><input type="hidden" name="mod" value="'.$mode.'"/>';}
			if ($mode=='images'){
				// ajout des options de recherche d'images
				// couleur
				$colors=array(
					''=>msg('all colors'),
					'ic:trans'=>'Transparent',
					'ic:gray'=>msg('Black_and_white'),
					'ic:color'=>msg('Color'),
					'ic:specific,isc:red'=>'red',
					'ic:specific,isc:orange'=>'orange',
					'ic:specific,isc:yellow'=>'yellow',
					'ic:specific,isc:pink'=>'pink',
					'ic:specific,isc:white'=>'white',
					'ic:specific,isc:gray'=>'gray',
					'ic:specific,isc:black'=>'black',
					'ic:specific,isc:brown'=>'brown',
					'ic:specific,isc:green'=>'green',
					'ic:specific,isc:teal'=>'teal',
					'ic:specific,isc:blue'=>'blue',
				);
				echo '<select id="color_selection" name="couleur" class="'.$colors[$couleur].'" title="'.msg('Select a color').'" onChange="change_class(this.options[this.selectedIndex].innerHTML);" >';
				foreach ($colors as $get=>$color){
					if ($get==$couleur){$sel=' selected ';}else{$sel='';}
					echo '<option value="'.$get.'" class="'.$color.'"'.$sel.'>'.msg($color).'</option>';
				}
				echo '</select>';
				unset($colors);
				// tailles
				$sizes=array(
					''=>msg('all sizes'),
					'isz:l'=>msg('Big'),
					'isz:m'=>msg('Medium'),
					'isz:i'=>msg('Icon'),
					'isz:lt,islt:vga'=>'>  640x 480',
					'isz:lt,islt:svga'=>'>  800x 600',
					'isz:lt,islt:xga'=>'> 1024x 768',
					'isz:lt,islt:2mp'=>'> 1600x1200 2mpx',
					'isz:lt,islt:4mp'=>'> 2272x1704 4mpx',
					'isz:lt,islt:6mp'=>'> 2816x2112 6mpx',
					'isz:lt,islt:8mp'=>'> 3264x2448 8mpx',
					'isz:lt,islt:10mp'=>'> 3648x2736 10mpx',
					'isz:lt,islt:12mp'=>'> 4096x3072 12mpx',
					'isz:lt,islt:15mp'=>'> 4480x3360 15mpx',
					'isz:lt,islt:20mp'=>'> 5120x3840 20mpx',
					'isz:lt,islt:40mp'=>'> 7216x5412 40mpx',
					'isz:lt,islt:70mp'=>'> 9600x7200 70mpx',
				);
				echo '<select id="size_selection" name="taille" class="'.$sizes[$taille].'" title="'.msg('Select a size').'">';
				foreach ($sizes as $get=>$size){
					if ($get==$taille){$sel=' selected ';}else{$sel='';}
					echo '<option value="'.$get.'"'.$sel.'>'.$size.'</option>';
				}
				echo '</select>';
			}
		?>
	</form>
	<p class="msg nomobile">
		<?php 
			echo msg('Search anonymously on Google (direct links, fake referer)'); 
			if ($mode!='web'){	echo '<br>'.msg('The thumbnails are temporarly stored in this server to hide your ip from Google…');	} 
		?> 
	</p>
</header>
<section>
	<?php if ($q_raw!=''){render_query(parse_query($q_raw,$start,$mode));} ?>
</section>
<footer></footer>
<script>
	document.write('<script src=js/' +
	('__proto__' in {} ? 'zepto' : 'jquery') +
	'.min.js><\/script>')
</script>
<script src="js/jquery.magnific-popup.min.js"></script>
<script>
	$(document).ready(function() {
		$('.zoom-gallery').magnificPopup({
		  delegate: 'a',
		  type: 'image',
		  closeOnContentClick: false,
		  closeBtnInside: false,
		  mainClass: 'mfp-with-zoom mfp-img-mobile',
		  image: {
			verticalFit: true,
			titleSrc: function(item) {
			  return item.el.attr('title') + '<a class="image-source-link" href="'+item.el.attr('data-source')+'" target="_blank">image source</a>';
			}
		  },
		  gallery: {
			enabled: true
		  },
		  zoom: {
			enabled: true,
			duration: 300,
			opener: function(element) {
			  return element.find('img');
			}
		  }
		});
	  });
	$(document).ready(function() {
		$('.popup-youtube, .popup-vimeo, .popup-gmaps').magnificPopup({
		  disableOn: 700,
		  type: 'iframe',
		  mainClass: 'mfp-fade',
		  removalDelay: 160,
		  preloader: false,
		  fixedContentPos: false
		});
	  });
	$(document).ready(function() {
		$('.popup-with-zoom-anim').magnificPopup({
		  type: 'inline',
		  fixedContentPos: false,
		  fixedBgPos: true,
		  overflowY: 'auto',
		  closeBtnInside: true,
		  preloader: false,
		  midClick: true,
		  removalDelay: 300,
		  mainClass: 'my-mfp-zoom-in'
		});
	  });
	function change_class(classe) { 
		var btn = document.getElementById("color_selection"); 
		btn.className= classe; 
	} 
</script>
<?php if(USE_WEB_OF_TRUST){echo '<script src="http://api.mywot.com/widgets/ratings.js"></script>';}?>
</body>
</html>
<?php add_search_engine(); ?>