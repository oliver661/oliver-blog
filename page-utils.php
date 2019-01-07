<?php

include "./libs/Parser.php";

function getHTMLBeginer($title="Toppage", $css="page.css"){
	echo "
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
<title>$title</title>
<link href=\"$css\" rel=\"stylesheet\" type=\"text/css\" />
</head>

<body bgcolor=\"#EEEEEE\">
<div id=\"warpper\">";
}

function getHTMLFinaller(){
	echo "
</div>
</body>
</html>";
}

function getContentPage($page){

	$mdParser=new Parser;

	getHeader($page);

	echo "<div id=\"content\"><hr class=\"hr0\"/>";
	echo $mdParser->makeHtml($page::getContent());
	echo "<hr class=\"hr0\" /></div>";

	getFooter();
}

function getHeader($page){

	$icon="imgs/".$page->category.".jpg";

	echo "
	<div id=\"header\">
    	<div id=\"icon-container\">
			<div><img id=\"icon\" src=\"imgs/$icon.jpg\" /></div>
        </div>
        <div id=\"title-container\">
			<div id=\"title\">{$page->title}</div>
  			<div id=\"header-info\" class=\"info\">
				{$page->category} {$page->mtime}
                <a href=\"./page.php?p={$page->name}\" target=\"_blank\" > [本文地址] </a>
                <a href=\"./directory.php\" target=\"_self\" > [回到目录] </a>
            </div>
        </div>
    </div>";
}

function getFooter(){
	$info=stat(__FILE__);
	$date=date('Y-m-d H:i:s (e/O)', $info['mtime']);
	echo "
    <div id=\"footer\">
    	<!--
    	<div id=\"button-prev\" class=\"header-button\">
        	&lt;&lt; 上一篇
        </div>
    	-->
        <div class=\"header-button\"
        onclick=\"window.open('./','_top')\">—— 海龟的漂浮岛 ——</div>
        <div class=\"header-button\"
        onclick=\"window.open('directory.php','_self')\">—— 回到目录 ——</div>
        <!--
        <div id=\"button-next\" class=\"header-button\">
        	下一篇 &gt;&gt;
        </div>
        -->
        <div id=\"footer-info\" class=\"info\">
        Page Function Last Update: $date
        </div>
    </div>";
}