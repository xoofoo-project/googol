<?php
	if (isset($_GET['lang'])){$langue=$_GET['lang'];}else{$langue=lang();}
	clear_cache();// vire les thumbs de plus de trois minutes (THUMB_EXPIRE_DELAY)
	define('REGEX_WEB','#(?<=<h3 class="r"><a href="/url\?q=)([^&]+).*?>(.*?)</a>.*?(?<=<span class="st">)(.*?)(?=</span>)#');
	define('REGEX_PAGES','#&start=([0-9]+)|&amp;start=([0-9]+)#');
	define('REGEX_IMG','#(?<=imgurl=)(.*?)&amp;imgrefurl=(.*?)&amp;.*?h=([0-9]+)&amp;w=([0-9]+)&amp;sz=([0-9]+)|(?<=imgurl=)(.*?)&imgrefurl=(.*?)&.*?h=([0-9]+)&w=([0-9]+)&sz=([0-9]+)#');
	define('REGEX_THMBS','#<img.*?height="([0-9]+)".*?width="([0-9]+)".*?src="([^"]+)"#');
	define('TPL','<div class="result"><a href="#link"><h3 class="title">#title</h3>#link</a><p class="description">#description</p></div>');
	define('TPLIMG','<div class="image" ><p><a href="#link" title="#link">#thumbs</a></p><p class="description">#W x #H (#SZ ko)<a class="source" href="#site" title="#site"> &#9658;</a></p></div>');
	define('LOGO1','<em class="g">G</em><em class="o1">o</em>');
	define('LOGO2','<em class="o2">o</em><em class="g">g</em><em class="o1">o</em><em class="l">l</em>');
	define('URL','https://www.google.fr/search?q=');
	define('URLIMG','&tbm=isch&biw=1920&bih=1075&sei=v5ecUb6OG-2l0wW554GYBQ');
	define('VERSION','v1.2');
	define('LANGUAGE',$langue);
	define('RACINE','http://'.$_SERVER['SERVER_NAME']);
	define('USE_WEB_OF_TRUST',true);
	define('USE_GOOGLE_THUMBS',false);
	define('THEME','style_google.css');
	// true = googol utilise les miniatures de google (c'est l'ip du visiteur que google verra mais c'est rapide et sans charge pour le servuer hébergeant googol)
	// false = c'est le serveur googol qui télécharge les miniatures (ip user cachée à google, il ne verra que l'ip du serveur, mais c'est sensiblement plus lent)
	
	if (!USE_GOOGLE_THUMBS){ // on va télécharger temporairement les miniatures pour cacher l'ip du visiteur à google
		session_start();
		if (!isset($_SESSION['ID'])){$_SESSION['ID']=uniqid();}
		define('UNIQUE_THUMBS_PATH','thumbs/'.$_SESSION['ID']);
		if (!is_dir('thumbs')){mkdir('thumbs');}// crée le dossier thumbs si nécessaire
	}
	$lang['fr']=array(
		'previous'=>htmlentities('Page précédente', ENT_QUOTES, 'UTF-8'),
		'next'=>'Page suivante',
		'The thumbnails are temporarly stored in this server to hide your ip from Google...'=>htmlentities('les miniatures sont temporairement récupérées sur ce serveur, google n\'a pas votre IP...', ENT_QUOTES, 'UTF-8'),
		'Search anonymously on Google (direct links, fake referer)'=>htmlentities('Rechercher anonymement sur Google (liens directs et referrer caché)', ENT_QUOTES, 'UTF-8'),
		'Free and open source (please keep a link to warriordudimanche.net for the author ^^)'=>htmlentities('Libre et open source, merci de laisser un lien vers warriordudimanche.net pour citer l\'auteur ;)', ENT_QUOTES, 'UTF-8'),
		'Googol - google without lies'=>'Googol - google sans mensonge',
		'on GitHub'=>'sur GitHub',
		'no results'=>htmlentities('pas de résultat', ENT_QUOTES, 'UTF-8'),
		'by'=>'par',
		'search '=>'recherche ',
		'Search'=>'Rechercher',
		'Otherwise, use a real Search engine !'=>'Sinon, utilisez un vrai moteur de recherche !',
		);



	function aff($a,$stop=true){echo 'Arret a la ligne '.__LINE__.' du fichier '.__FILE__.'<pre>';var_dump($a);echo '</pre>';if ($stop){exit();}}
	function msg($m){global $lang;if(isset($lang[LANGUAGE][$m])){return $lang[LANGUAGE][$m];}else{return $m;}}
	function lang($default='fr'){if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])){$l=explode(',',$_SERVER['HTTP_ACCEPT_LANGUAGE']);return substr($l[0],0,2);}else{return $default;}}
	function Random_referer(){
		$rr=array(
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
		);
		shuffle($rr);
		return $rr[0];
	}
	function file_curl_contents($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,  FALSE);     
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);     
		curl_setopt($ch, CURLOPT_URL, $url);     
		if (!ini_get("safe_mode") && !ini_get('open_basedir') ) {curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);}    
		curl_setopt($ch, CURLOPT_MAXREDIRS, 10); 
		curl_setopt($ch, CURLOPT_REFERER, random_referer());// notez le referer "custom"
		$data = curl_exec($ch);     
		curl_close($ch);     
		return $data; 
	}  
	function add_search_engine(){
		if(!is_file('googol.xml')){
			file_put_contents('googol.xml', '<OpenSearchDescription xmlns="http://a9.com/-/spec/opensearch/1.1/"
              xmlns:moz="http://www.mozilla.org/2006/browser/search/">
			  <ShortName>Googol</ShortName>
			  <Description>'.msg('Googol - google without lies').'</Description>
			  <InputEncoding>UTF-8</InputEncoding>
			  <Image width="32" height="32">data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAABCFJREFUeNrEV21MW2UUfm7v7QcwWqj9YBQyEAdqwBGGY3xkQRlLRjLnsmnUOOOSJYsmxvhDo/7VRH+YaPxhYqLRiAkuccbh5jbFRZgR3IbpwphG0wxaSgOUXgot7W177/V9L/DDrLv3bcK2kzy97e15zz3vOc85573c4bfOY112ruNuyATBKP0iQNVufETwKu6ufElwTFBVtf0ePJzKiwQniQNoLmRVYyUHT5kNT3f7YLbatHtkE4gvr+LsWATT82kEoszm2gVFVZk0K+0qnulwoM7nAG82gxfM2n2OM5HfAjzeYhw76EIuk8HV6zP4engBYoozMmuhKTB8eJ0LeHlvGc74VzHwexyz8f//73NweNBnRU9rJWq3VaBzVwNqKkrwdv9NpHP6Thg6QHfe12TDO6dFiKv5dWaWVIIUhiYDeK4rhid7m+D2utDkm8blKUXXvommQA9HO4rw1Wgai0l9vQ2cGotBSiaQTafwz5xsqC+oOg4+QEIfJrtbTLCTtLyIQyqxjIGLIaZ1gqLcPgVVTko2CxQlxexAx3YbRv4M46cJtjW6HOA4DntaqiApPC5eEzEj6uezs94Kj8OCjy8sMTusG4EbYYlEQMChfU040C1BzmZJzed3wmTiwfE8hq8GYeVFpLKsDuiQIBgDBkcCOLyvEcX2MvC8QMNyW31VUdDbaSNlCbzRH2JMgaJfhv3DIubFcRx5rAZljhItLXkjQJyzFhVrTcphL4aRXaYUbMg5f5Jg0lDv2T0ezdFvfw1CYXaAsRWziN2SwXdDf+FHf2JzSJhPdtaYsbvBjrb6LWuVQsgXislaCn6biOLMeLwge0yzYENe6fPi8UercWUygte+CCK6osBVasKJ/T50tdaiurIcgYifVE+W2aaJMpcFT7U7sL/7ISQlBR9+P4OFeE67T6/vfjONqdA8vF433jvRgupyDqx2TQr5MIKzBDja97A2/3+4NIVEWr5FZ+DnAGQ5h1KnG4c6PGCxS2Gi5WKEtu0lsG0p1UK2nJDy6oz9TQaQlNac7GmrAYtdCiYOVDhthGQ8NnTzrUlKZLqRCNA+QQl5H+FodEVlaUSKoVIimdUeSjshdUZvjSzLWIonCTdkRhISw0b4N5wgRy0JJjIXdjVuzatTZFnrhlkphV/+mAaLXQqmKrgeTCOysKql4f7areh5xH6LTm9zuTaQZkNhnBqZZa4C3U74wfNubKtyacSyWbNYic5rUXj9hRZymhnHkH9t7O7d4cDxJ+oRCUfw5qfXMLcsM/cBrvnIZy+R6yesCw7udqK1wY0ddXbkshJlJObEDM5dmcPg5aVCu/f7AgqcBadHFzVslhTUiu+AZOh54OY9dOAGLcPzBJ+zls0m4izBSXLG0lJwnL4oEnTRxneHdx1bfzUfpD/+E2AAqmeV253DYKAAAAAASUVORK5CYII=</Image>
			  <Url type="text/html" method="get" template="'.RACINE.'">
			  <Param name="q" value="{searchTerms}"/>
			  </Url>
			  <moz:SearchForm>'.RACINE.'</moz:SearchForm> 
			</OpenSearchDescription>');
		}
	}
	function parse_query($query,$start=0,$img=false){
		if (!$img){ // web
			$page=file_curl_contents(URL.str_replace(' ','+',urlencode($query)).'&start='.$start);
			if (!$page){return false;}
			preg_match_all(REGEX_WEB, $page, $r);
			preg_match_all(REGEX_PAGES,$page,$p);
			$p=count($p[2]);
			$retour=array(
				'links'=>$r[1],
				'titles'=>$r[2],
				'descriptions'=>$r[3],
				'nb_pages'=>$p,
				'current_page'=>$start,
				'query'=>$query
				);
			return $retour;
		}else{ //images
			$page=file_curl_contents(URL.str_replace(' ','+',urlencode($query)).URLIMG.'&start='.$start);			
			if (!$page){return false;}
			preg_match_all(REGEX_IMG,$page,$r);
			preg_match_all(REGEX_PAGES,$page,$p);
			preg_match_all(REGEX_THMBS,$page,$t);
			$p=count($p[2]);
			$retour=array(
				'site'=>$r[2],
				'links'=>$r[1],
				'h'=>$r[3],
				'w'=>$r[4],
				'sz'=>$r[5],
				'thumbs'=>$t[3],
				'thumbs_w'=>$t[2],
				'thumbs_h'=>$t[1],
				'nb_pages'=>$p,
				'current_page'=>$start,
				'query'=>$query
				);			
			return $retour;		
		}
	}

	function render_query($array){
		global $start,$langue;
		if (!is_array($array)||count($array)==0){return false;}
		if (!isset($array['sz'][0])){
			foreach ($array['links'] as $nb => $link){
				$r=str_replace('#link',$link,TPL);
				$r=str_replace('#title',$array['titles'][$nb],$r);
				$d=str_replace('<br>','',$array['descriptions'][$nb]);
				$d=str_replace('<br/>','',$d);
				$r=str_replace('#description',$d,$r);
				echo $r;
			}
			$img='';
		}else{
			foreach ($array['links'] as $nb => $link){
				$r=str_replace('#link',$link,TPLIMG);
				$r=str_replace('#SZ',$array['sz'][$nb],$r);
				$r=str_replace('#H',$array['h'][$nb],$r);
				$r=str_replace('#W',$array['w'][$nb],$r);
				$r=str_replace('#site',$array['site'][$nb],$r);
				if (!USE_GOOGLE_THUMBS){
					$repl='<img src="'.grab_google_thumb($array['thumbs'][$nb]).'" width="'.$array['thumbs_w'][$nb].'" height="'.$array['thumbs_h'][$nb].'"/>';
				}else if (USE_GOOGLE_THUMBS){
					$repl='<img src="'.$array['thumbs'][$nb].'" width="'.$array['thumbs_w'][$nb].'" height="'.$array['thumbs_h'][$nb].'"/>';
				}				
				$r=str_replace('#thumbs',$repl,$r);
				$r=str_replace('#thumbs_h',$array['thumbs_h'][$nb],$r);
				$r=str_replace('#thumbs_w',$array['thumbs_w'][$nb],$r);
				echo $r;
			}	
			$img='&img';

		}

		if($array['nb_pages'] != 0){
			echo '<hr/><p class="footerlogo">'.LOGO1.str_repeat('<em class="o2">o</em>', $array['nb_pages']-1).LOGO2.'</p><div class="pagination">';
		}
		else{
			echo '<div class="noresult"> '.msg('no results').' </div>';
		}

		if ($start>0){echo '<a class="previous" title="'.msg('previous').'" href="?q='.urlencode($array['query']).$img.'&start='.($start-10).'&lang='.$langue.'">&#9668;</a>';}
		for ($i=0;$i<$array['nb_pages']-1;$i++){
			if ($i*10==$array['current_page']){echo '<em>'.($i+1).'</em>';}
			else{echo '<a href="?q='.urlencode($array['query']).$img.'&start='.$i.'0&lang='.$langue.'">'.($i+1).'</a>';}
		}
		if ($start<($array['nb_pages']-2)*10){echo '<a class="next" title="'.msg('next').'" href="?q='.urlencode($array['query']).$img.'&start='.($start+10).'&lang='.$langue.'">&#9658;</a>';}
		
		echo  '</div>';
	}
	function grab_google_thumb($link){
		if ($thumb=file_curl_contents($link)){
			$local='thumbs/'.str_replace(array('?','/',':'),'',$link).'.jpg';
			if (!is_file($local)){file_put_contents($local,$thumb);}
			return $local;
		}else{
			return $link;
		}
	}
	function clear_cache($delay=180){$fs=glob('thumbs/*'); if(!empty($fs)){foreach ($fs as $file){if (@date('U')-@date(filemtime($file))>$delay){unlink ($file);}}}}
	function is_active($first,$second){if ($first==$second){echo 'active';}else{echo '';}}


	// Gestion GET
	if (isset($_GET['img'])){$img=true;}else{$img=false;}
	if (isset($_GET['start'])){$start=$_GET['start'];}else{$start='';}
	if (isset($_GET['q'])){$q=urldecode($_GET['q']);$title='Googol '.msg('search ').$q;}else{$q='';$title=msg('Googol - google without lies');}
?>

<!DOCTYPE html>
<html dir="ltr" lang="fr">
<head>
	<title><?php echo htmlentities($title, ENT_QUOTES, 'UTF-8'); ?> </title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php if (is_file('favicon.png')){echo '<link rel="shortcut icon" href="favicon.png" /> ';}?>
	<link rel="stylesheet" type="text/css" href="<?php echo THEME;?>"  media="screen" />
	<link rel="search" type="application/opensearchdescription+xml" title="<?php echo msg('Googol - google without lies'); ?>" href="<?php echo RACINE;?>/googol.xml">
	<!--[if IE]><script> document.createElement("article");document.createElement("aside");document.createElement("section");document.createElement("footer");</script> <![endif]-->
</head>
<body>
<header>
	<p class="top"><span class="version"> <?php echo htmlentities(VERSION, ENT_QUOTES, 'UTF-8'); ?></span><a class="<?php is_active(LANGUAGE,'fr'); ?>" href="?lang=fr">FR</a> <a class="<?php is_active(LANGUAGE,'en'); ?>" href="?lang=en">EN</a></p>
	
	<form action="" method="get" >
		<input type="hidden" name="lang" value="<?php echo LANGUAGE;?>"/>
	<span class="logo"><?php echo LOGO1.LOGO2; ?></span><span><input type="text" name="q" placeholder="<?php echo msg('Search'); ?>" value="<?php echo htmlentities($q, ENT_QUOTES, 'UTF-8'); ?>"/><input type="submit" value="OK"/></span>
	<?php if ($img){echo '<input type="hidden" name="img"/>';}?>
	</form>
<p class="msg"><?php echo msg('Search anonymously on Google (direct links, fake referer)'); if ($img){echo '<br/>'.msg('The thumbnails are temporarly stored in this server to hide your ip from Google...');}  ?> </p>
	
</header>
<nav>
<?php 
	if (!$img){echo '<li class="active">Web</li><li><a href="?q='.htmlentities($q, ENT_QUOTES, 'UTF-8').'&img&lang='.$langue.'">Images</a></li>';}
	else{echo '<li><a href="?q='.htmlentities($q, ENT_QUOTES, 'UTF-8').'&lang='.$langue.'">Web</a></li><li class="active">Images</li>';}
?>
</nav>
<aside>
	<?php if ($q!=''){render_query(parse_query($q,$start,$img));} ?>
</aside>
<footer><a href="<?php echo RACINE;?>">Googol</a> <?php echo msg('by');?> <a href="http://warriordudimanche.net">Bronco - warriordudimanche.net</a> <a href="#" title="<?php echo msg('Free and open source (please keep a link to warriordudimanche.net for the author ^^)');?>"><em>Licence</em></a>  <a href="https://github.com/broncowdd/googol" title="<?php echo msg('on GitHub');?>"><img width="32" src="github.png" alt="logoGH"/></a> <a href="http://flattr.com/thing/1319925/broncowddSnippetVamp-on-GitHub" target="_blank"><img src="http://images.warriordudimanche.net/flattr.png" alt="Flattr this" title="Flattr this" border="0" /></a><a href="http://duckduckgo.com" title="<?php echo msg('Otherwise, use a real Search engine !');?>"><img src="ddg.png" alt="ddg icon"/></a></footer>
<?php if(USE_WEB_OF_TRUST){echo '<script type="text/javascript" src="http://api.mywot.com/widgets/ratings.js"></script>';}?> 
</body>
</html>
<?php add_search_engine(); ?>
