<?php

include "./page-utils.php";
include "./page-list-utils.php";
include_once "./configure.php";

if(isset($_GET['c'])){
	$cate=$_GET["c"];
}else{
	$cate='all';
}

$config=new Config();

getHTMLBeginer("Directory", "directory.css");

if($cate=='all'){
	echo '—— 全部文章——';
}elseif(isset($config->nNameCate[$cate])){
	echo '—— '.$config->nNameCate[$cate].' ——';
}
echo '<hr class="hr0" />';

getDirectoryPage($cate);

getFooter(null, false);
getHTMLFinaller();

?>