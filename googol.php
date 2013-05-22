<?php
	define('REGEX_WEB','#(?<=<h3 class="r"><a href="/url\?q=)([^&]+).*?>(.*?)</a>.*?(?<=<span class="st">)(.*?)(?=</span>)#');
	define('REGEX_PAGES','#&start=([0-9]+)|&amp;start=([0-9]+)#');
	define('REGEX_IMG','#(?<=imgurl=)(.*?)&amp;.*?h=([0-9]+)&amp;w=([0-9]+)&amp;sz=([0-9]+)|(?<=imgurl=)(.*?)&.*?h=([0-9]+)&w=([0-9]+)&sz=([0-9]+)#');
	define('REGEX_THMBS','#<img.*?height="([0-9]+)".*?width="([0-9]+)".*?src="(.*?)"#');
	define('TPL','<div class="result"><a href="#link"><h3 class="title">#title</h3>#link</a><p class="description">#description</p></div>');
	define('TPLIMG','<div class="image" ><p><a href="#link" title="#link">#thumbs</a></p><p class="description">#W x #H (#SZ ko)</p></div>');
	define('LOGO1','<em class="g">G</em><em class="o1">o</em>');
	define('LOGO2','<em class="o2">o</em><em class="g">g</em><em class="o1">o</em><em class="l">l</em>');
	define('URL','https://www.google.fr/search?q=');
	define('URLIMG','&tbm=isch&biw=1920&bih=1075&sei=v5ecUb6OG-2l0wW554GYBQ');

	define('RACINE','http://'.$_SERVER['SERVER_NAME']);
	function aff($a,$stop=true){echo 'Arret a la ligne '.__LINE__.' du fichier '.__FILE__.'<pre>';var_dump($a);echo '</pre>';if ($stop){exit();}}

	// imgrefurl => site sources
		
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
			  <ShortName>Googole</ShortName>
			  <Description>G sans mensonge !</Description>
			  <InputEncoding>UTF-8</InputEncoding>
			  <Image width="16" height="16">data:image/png,%89PNG%0D%0A%1A%0A%00%00%00%0DIHDR%00%00%00%10%00%00%00%10%08%06%00%00%00%1F%F3%FFa%00%00%00%06bKGD%00%FF%00%FF%00%FF%A0%BD%A7%93%00%00%00%09pHYs%00%00%0B%13%00%00%0B%13%01%00%9A%9C%18%00%00%00%07tIME%07%DD%05%15%15%0F%23%F2A%81%AA%00%00%00%1DiTXtComment%00%00%00%00%00Created%20with%20GIMPd.e%07%00%00%01%D2IDAT8%CB%A5%92KHTQ%1C%C6%7F%E7%DC%7B%C7%19%87%11%22%94ta%3Ez%90%08M%8F%C1%DA%B5%A8%85%11%88%D8B%82VA.l%95D%90m%82h%D5%A2h%15%F4X%94%24%05%D1%A2%16%D9%B6%06)(%1F%1B%0D%7B)%A3P%D4038w%1C%E7%9C%E3b%F06%E3%BDI%D4%B7%3A%87%8F%FF%C7%F7%FF%BE%BF0%C6%18%FE%03%F6%FA%E3%EC%9De%16%D3%86g%E7%A3%3Cx%5D%E4%C5%C4*3)%8Dm%C1%AEF%C9%89%7D%0E%FD%87C%84lQ%25%20%D6%1D%1C%BD%96c%FE%A7%26%D1f3%3E%A7%88o%97%EC%DC%26YJ%1B%A6%16%14Y%17%F64I%EE%0D%D4%D2P\'%FD%0E%00%94%16L%2F(F%06k9%B4%E37%95s5%97%9F%14x%FE%A1%C4%E0%FD%3C%A3%E7%A2XV%D9%89%DC%B8%D3pO%B8j%18%20%16%91%DC8%1Dao%B3%E4%FDW%CD%CB%E9U%8F%F3%09%1C%E9%B0%03%C3%12B0t%BC%06%80%B1%A9R%F0%0A%00%8BiM%AE%60%10%40%F3V%89S%11Z%BC%C5%02%E0%F3%0F%ED%17%E8%8E%3B%3CJ%169y3%EF%91%D7O%85%E9M%84%BC%7F%B4FR%1F%83%2F%DF%B5%BF%85%BF%C5%FEKY%2C%09%EF%AE%D6%05g%B0%192yM%C6%85%D6%06%F9%E7%107C%F2c9%BC%F6%20%81%E1%C7.%1D%172%BC%AA%A8%A8%12Z%1Bn%8D%15%01%E89%E0%F8%05%9A%B6%08VJ%82%8B%A3.%93%DFJU%C3YW3p7%CF%EC%92%E6X%A7EW%C5%9Dx!*e%B8%F2%B4%C0H%B2%EC%20%D1%26i%A9%97%A4~%19%26%E7%15%CB%2B%D0%D5nq%FBL%84XD%06%B7%60%8C%E1%ED\'%C5%C37EfR%8ATZ%13%0E%09v7Z%F4%25%1Cz%0F%3A%DE%09%FFs%8D%1B%B1%06WR%AC%DE%DB%CE%97L%00%00%00%00IEND%AEB%60%82</Image>
			  <Url type="text/html" method="get" template="'.RACINE.'">
			  <Param name="q" value="{searchTerms}"/>
			  </Url>
			  <moz:SearchForm>'.RACINE.'</moz:SearchForm> 
			</OpenSearchDescription>');
		}
	}
	function parse_query($query,$start=0,$img=false){

		
		if (!$img){ // web
			$page=file_curl_contents(URL.str_replace(' ','+',$query).'&start='.$start);
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
			$page=file_curl_contents(URL.str_replace(' ','+',$query).URLIMG.'&start='.$start);			
			if (!$page){return false;}
			preg_match_all(REGEX_IMG,$page,$r);
			preg_match_all(REGEX_PAGES,$page,$p);
			preg_match_all(REGEX_THMBS,$page,$t);
			$p=count($p[2]);
			$retour=array(
				'links'=>$r[1],
				'h'=>$r[2],
				'w'=>$r[3],
				'sz'=>$r[4],
				'thumbs'=>$t[0],
				'nb_pages'=>$p,
				'current_page'=>$start,
				'query'=>$query
				);
			return $retour;		
		}
	}

	function render_query($array){
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
				$r=str_replace('#thumbs',$array['thumbs'][$nb].'/>',$r);
				echo $r;
			}	
			$img='&img';		
		}
		
		echo '<hr/><p class="footerlogo">'.LOGO1.str_repeat('<em class="o2">o</em>', $array['nb_pages']-1).LOGO2.'</p><div class="pagination">';
		for ($i=0;$i<$array['nb_pages']-1;$i++){
			if ($i*10==$array['current_page']){echo '<em>'.($i+1).'</em>';}
			else{echo '<a href="?q='.htmlentities($array['query']).$img.'&start='.$i.'0">'.($i+1).'</a>';}
		}
		echo  '</div>';
	}

	if (isset($_GET['img'])){$img=true;}else{$img=false;}
	if (isset($_GET['start'])){$start=$_GET['start'];}else{$start='';}
	if (isset($_GET['q'])){$q=$_GET['q'];$title='Googol recherche '.htmlentities($q);}else{$q='';$title='Googol - google sans mensonge';}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html dir="ltr" lang="fr">
<head>
	<title><?php echo $title; ?> </title>
	<style>
		*{-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;}
		body{padding:0;margin:0;}
		aside{padding:0 25px 100px;}
		a {text-decoration: none; }
		hr{border:none;border-top:1px solid #aaa;}
		form{margin-bottom:20px;}
		header{font-size:85px;text-align:center;width:auto;background-color:#eee;padding-bottom:20px;border-bottom:1px solid #ddd;}
		header em{font-style: normal;text-shadow:0 1px 2px #555;-webkit-touch-callout: none; -webkit-user-select: none; -khtml-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none;}
		 em.g{color:blue;}
		 em.o1{color:red;}
		 em.o2{color:orange;}
		 em.l{color:green;}
		header .mini{font-size:14px;padding:0 0 0 220px;margin:0;margin-top:-15px;text-shadow:0 0 3px red;}
		nav{padding-left:35px;background-color:white;border-bottom:1px solid #ccc;}
		nav li{border-bottom:4px solid transparent;background-color:white; display:inline-block;list-style:none;width:100px;height:30px;font-size:18px;font-family: arial, sans-serif;text-align: center;}
		nav li.active{color:red;font-weight: bold;border-bottom:4px solid red;}
		nav li a{color:#666;}
		nav li a:hover{color:#111;}
		input[type=text]{height:30px;width:30%;min-width:230px;border-radius: 3px; padding:3px;border:1px solid #ccc;}
		input[type=text]:hover{border-color:#aaa;}
		input[type=submit]{height:30px;width:40px;font-size:14px;background-color:#4a8cf7;border:1px solid #397be6;border-radius: 3px;color:#eee; }
		input[type=submit]:hover{background-color:#397be6;border-color:#286ad5 }
		.result{padding:0 10px ;margin:0;font-family:arial, sans-serif;border-radius:3px;}
		.result:hover{background-color:#EEE;}
		.result a {color:#0B0;}
		.result h3 {text-decoration: underline; color:#00B!important;}
		.result .title{margin-bottom:0;}
		.result .description{margin-top:3px;}
		.image{display:inline-block;margin:10px;text-align: center;}
		.image p{margin:0;padding: 0;}
		.image img{border:1px solid transparent;border-radius: 4px; box-shadow: 0 1px 2px #555}
		.image img:hover{border:1px solid #333;}
		.pagination{font-size:18px!important;text-align:center;width:auto;padding-top:5px;}
		.pagination a{text-decoration: none;padding:5px;border-radius: 4px;display:inline-block;}
		.pagination a:hover{background-color:#DDD;}
		.pagination em{padding:5px;background-color:#CCC;border-radius: 4px;display:inline-block;}
		.footerlogo{text-align:center;padding:0;margin:0;font-size:22px;font-weight:bold;user-select: none;-webkit-user-select: none;}
		.footerlogo em{font-style: normal;display:inline-block;}
		footer{position:fixed;bottom:0;left:0;right:0;height:auto;min-height:40px;border-top:solid 1px #ddd;margin-top:30px;background-color:#EEE;text-align: right;color:#555;line-height: 30px;padding-right:10px;padding-bottom:5px;}
		footer a{color:#444;font-weight: bold;}
	</style>
	<link rel="shortcut icon" href="favicon.png" /> 
	<link rel="search" type="application/opensearchdescription+xml" title="Googol G sans mensonge" href="'<?php echo RACINE;?>googol.xml">
	<!--[if IE]><script> document.createElement("article");document.createElement("aside");document.createElement

("section");document.createElement("footer");</script> <![endif]-->
</head>
<body>
<header>
	<?php echo LOGO1.LOGO2; ?>
	<p class="mini">DTC</p>
	<form action="" method="get" >
	<input type="text" name="q" placeholder="Rechercher" value="<?php echo htmlentities($q); ?>"/><input type="submit" value="OK"/>
	</form>
</header>
<nav>
<?php 
	if (!$img){echo '<li class="active">Web</li><li><a href="?q='.htmlentities($q).'&img">Images</a></li>';}
	else{echo '<li><a href="?q='.htmlentities($q).'">Web</a></li><li class="active">Images</li>';}
?>
</nav>
<aside>
	<?php if ($q!=''){render_query(parse_query($q,$start,$img));} ?>
</aside>
<footer>Googol est une niaiserie de <a href="http://warriordudimanche.net">Bronco - warriordudimanche.net</a>  (voir sur <a 

href="https://github.com/broncowdd/googol">GitHub</a>)</footer>
</body>

</html>
<?php add_search_engine(); ?>
