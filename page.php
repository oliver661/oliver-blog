<?php

include "./page-class.php";
include "./page-utils.php";
include "./page-list-utils.php";    

$pid=$_GET["p"];
$data=findPageData($pid);
if(!$data){
    echo "Failed to find page file.";
    exit();
}

$page=new Page($data);

getHTMLBeginer($page->title);
getContent($page);
getHTMLFinaller();

?>