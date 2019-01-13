<?php

include "./libs/Parser.php";
include_once 'configure.php';

$config=new Config();

function getHTMLBeginer($title="Toppage", $css="page.css"){
	echo '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>'.$title.'</title>
<link href="'.$css.'" rel="stylesheet" type="text/css" />
</head>

<body bgcolor="#EEEEEE">
<div id="warpper">';
}

function getHTMLFinaller(){
	echo "
</div>
</body>
</html>";
}

function getDirectoryPage($cate='all'){
	
	// Check cate name
	if($cate!='all'){
		$cate=array_search($cate, $config->cNameCate);
		if(!$cate){
			throw new Exception("Category not found.", 404);
		}
	}
	$pageDatas=findCateData($cate);
	foreach($pageDatas as $page){
		echo '
		<div
			class="blog-cell" 
			onclick="window.open(\'page.php?p='.$page->pn.'\',\'mainFrame\')"
		>
			<div class="title-container">
				<div class="title">'.$page->title.'</div>
				<div class="info">'.$page->cate.' '.$page->mtime.'</div>
			</div>
			<div class="title-line">'.$page->preview.'<br /></div>
		</div>
		<hr class="hr0" />';
	}
}

function getContentPage($page){

	$mdParser=new Parser;

	getHeader($page);

	echo "<div id=\"content\"><hr class=\"hr0\"/>";
	echo $mdParser->makeHtml($page::getContent());
	echo "<hr class=\"hr0\" /></div>";

	getFooter($page);
}

function getHeader($page){

	$icon="imgs/".$page->category.".jpg";
	if(isset($config->cNameCate[$page->category])){
		$cate=$config->cNameCate[$page->category];
	}else{
		$cate=$page->category;
	}
	echo '
	<div id="header">
    	<div id="icon-container">
			<div><img id="icon" src="imgs/'.$icon.'.jpg" /></div>
        </div>
        <div id="title-container">
			<div id="title">'.$page->title.'</div>
  			<div id="header-info" class="info">
				'.$cate.' '.$page->mtime.'
                <a href="./page.php?p='.$page->name.'" target="_blank" > [本文地址] </a>
                <a href="./directory.php" target="_self" > [回到目录] </a>
            </div>
        </div>
    </div>';
}

function getFooter($page=null, $hasButton=true, $hasInfo=true){

	echo '
	<div id="footer">';

	if($hasButton){
		if($page!==null){
			echo '
			<div 
				id="button-next" ';
			if($page->hasNextPage){
				echo 'onclick="window.open(\'page.php?p='.$page->nextPageData['pn'].'\',\'_self\')
				class="header-button"
				>&lt;&lt;'.$page->nextPageData['title'];
			}else{
				echo 'class="header-button"
				>没有再新的文章了';
			}
			echo '
			</div>';
		}
		echo '
		<div class="header-button" onclick="window.open(\'./\',\'_top\')">
			—— 海龟的漂浮岛 ——
		</div>
		<div class="header-button" onclick="window.open(\'directory.php\',\'_self\')">
			—— 回到目录 ——
		</div>';
		
		if($page!==null){
			echo '
			<div 
				id="button-prev" ';
			if($page->hasPrevPage){
				echo 'onclick="window.open(\'page.php?p='.$page->prevPageData['pn'].'\',\'_self\')
				class="header-button"
				>'.$page->prevPageData['title'].'&gt;&gt;';
			}else{
				echo 'class="header-button"
				>没有再旧的文章了';
			}
			echo '
			</div>';
		};
	}
	if($hasInfo){

		$info=stat(__FILE__);
		$date=date('Y-m-d H:i:s (e/O)', $info['mtime']);

		echo '
		<div id="footer-info" class="info">
			Page Function Last Update: '.$date.'
		</div>';
	}

	echo '
	</div>';
}

function getDirePage($cate='all'){

}