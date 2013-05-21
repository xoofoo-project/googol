<?php
	define('REGEX2','#(?<=<h3 class="r"><a href="/url\?q=)([^&]+).*?>(.*?)</a>.*?(?<=<span class="st">)(.*?)(?=</span>)#');
	define('REGEX3','#&start=([0-9]+)|&amp;start=([0-9]+)#');
	define('TPL','<div class="result"><a href="#link"><h3 class="title">#title</h3>#link</a><p class="description">#description</p></div>');
	define('LOGO1','<em class="g">G</em><em class="o1">o</em>');
	define('LOGO2','<em class="o2">o</em><em class="g">g</em><em class="o1">o</em><em class="l">l</em>');
	define('URL','https://www.google.fr/search?q=');
	
	function aff($a,$stop=true){echo 'Arret a la ligne '.__LINE__.' du fichier '.__FILE__.'<pre>';var_dump($a);echo '</pre>';if ($stop){exit();}}
	function file_curl_contents($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,  FALSE);     
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);     
		curl_setopt($ch, CURLOPT_URL, $url);     
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);     
		curl_setopt($ch, CURLOPT_MAXREDIRS, 10); 
		curl_setopt($ch, CURLOPT_REFERER, 'http://oudanstoncul.com.free.fr/‎');// notez le referer "custom"
		$data = curl_exec($ch);     
		curl_close($ch);     
		return $data; 
	}  
	function parse_query($query,$start=0){
		$page=file_curl_contents(URL.str_replace(' ','+',$query).'&start='.$start);
		
		if (!$page){return false;}
		preg_match_all(REGEX2, $page, $r);
		preg_match_all(REGEX3,$page,$p);
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
	}
	function render_query($array){
		if (!is_array($array)||count($array)==0){return false;}
		foreach ($array['links'] as $nb => $link){
			$r=str_replace('#link',$link,TPL);
			$r=str_replace('#title',$array['titles'][$nb],$r);
			$d=str_replace('<br>','',$array['descriptions'][$nb]);
			$d=str_replace('<br/>','',$d);
			$r=str_replace('#description',$d,$r);
			echo $r;
		}
		echo '<hr/><p class="footerlogo">'.LOGO1.str_repeat('<em class="o2">o</em>', $array['nb_pages']-1).LOGO2.'</p><div class="pagination">';
		for ($i=0;$i<$array['nb_pages']-1;$i++){
			if ($i*10==$array['current_page']){echo '<em>'.($i+1).'</em>';}
			else{echo '<a href="?q='.$array['query'].'&start='.$i.'0">'.($i+1).'</a>';}
		}
		echo  '</div>';
	}

	if (isset($_GET['start'])){$start=$_GET['start'];}else{$start='';}
	if (isset($_GET['q'])){$q=$_GET['q'];$title='Googol recherche '.$q;}else{$q='';$title='Googol - google sans mensonges';}
?>
<html>
<head>
	<title><?php echo $title; ?> </title>
	<style>
		*{-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;}
		body{padding:0;margin:0;}
		aside{padding:25px;padding-top:0;}
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
		input[type=text]{height:30px;width:30%;min-width:250px;border-radius: 3px; padding:3px;border:1px solid #ccc;}
		input[type=text]:hover{border-color:#aaa;}
		input[type=submit]{height:30px;width:40px;font-size:14px;background-color:#4a8cf7;border:1px solid #397be6;border-radius: 3px;color:#eee; }
		input[type=submit]:hover{background-color:#397be6;border-color:#286ad5 }
		.result{padding:0 10px ;margin:0;font-family:arial, sans-serif;border-radius:3px;}
		.result:hover{background-color:#EEE;}
		.result a {color:#0B0;}
		.result h3 {text-decoration: underline; color:#00B!important;}
		.result .title{margin-bottom:0;}
		.result .description{margin-top:3px;}
		.pagination{font-size:18px!important;text-align:center;width:100%;padding-top:5px;}
		.pagination a{text-decoration: none;padding:5px;border-radius: 4px;}
		.pagination a:hover{background-color:#DDD;}
		.pagination em{padding:5px;background-color:#CCC;border-radius: 4px;}
		.footerlogo{text-align:center;padding:0;margin:0;font-size:22px;font-weight:bold;user-select: none;-webkit-user-select: none;}
		.footerlogo em{font-style: normal;}
		footer{position:fixed;bottom:0;left:0;right:0;height:auto;min-height:40px;border-top:solid 1px #ddd;margin-top:30px;background-color:#EEE;text-align: right;color:#555;line-height: 30px;padding-right:10px;padding-bottom:5px;}
		footer a{color:#444;font-weight: bold;}
	</style>
	<!--[if IE]><script> document.createElement("article");document.createElement("aside");document.createElement("section");document.createElement("footer");</script> <![endif]-->
</head>
<body>
<header>
	<?php echo LOGO1.LOGO2; ?>
	<p class="mini">DTC</p>
	<form action="" method="get" >
	<input type="text" name="q" placeholder="Rechercher" value="<?php echo $q; ?>"/><input type="submit" value="OK"/>
	</form>
</header>
<aside>
	<?php if ($q!=''){render_query(parse_query($q,$start));} ?>
</aside>
<footer>Googol est une niaiserie de <a href="http://warriordudimanche.net">Bonco - warriordudimanche.net</a>  (voir sur <a href="https://github.com/broncowdd/googol">GitHub</a>)</footer>
</body>

</html>