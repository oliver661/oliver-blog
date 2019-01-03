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

$page=new Page($data['pn'], $data['name'], $data['cate']);

getHTMLBeginer($page->title);
getContent($page);
getHTMLFinaller();

?>