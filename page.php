<?php
	//$next_pn=0;
	$curr_pn=$_GET["p"];
	//$prev_pn=2;
	$page_path='pages/'.$curr_pn.'.xml';
	if(file_exists($page_path)){
		$page_xml=simplexml_load_file($page_path);
		$page_icon=$page_xml->icon;
		$page_title=$page_xml->title;
		$page_category=$page_xml->category;
		$page_date=$page_xml->date;
		$page_time=$page_date.' '.$page_xml->time;
	}else{
		echo 'blog xml file not found.<br />';
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Toppage</title>
<link href="page.css" rel="stylesheet" type="text/css" />
</head>

<body bgcolor="#EEEEEE">
<div id="warpper">
	<div id="header">
    	<div id="icon-container">
			<div><img id="icon" src="imgs/<?php echo $page_icon;?>.jpg" /></div>
        </div>
        <div id="title-container">
			<div id="title"><?php echo $page_title;?></div>
  			<div id="header-info" class="info">
				<?php echo $page_category.' '.$page_time;?>
                <a href="./page.php?p=<?php echo $curr_pn;?>" target="_blank" > [本文地址] </a>
                <a href="./directory.php" target="_self" > [回到目录] </a>
            </div>
        </div>
        <div id="header-line">
        	<!--
            <div id="button-prev" class="header-button"
            	onclick="window.open('pages/<?php //echo $prev_pn; ?>.html','mainFrame')">
            	&lt;&lt; 上一篇
        	</div>
        	<div class="header-button"
            	onclick="window.open('directory.php','mainFrame')">
            	—— 回目录 ——
        	</div>
        	<div id="button-next" class="header-button"
            	onclick="window.open('pages/<?php //echo $next_pn; ?>.html','mainFrame')">
            	下一篇 &gt;&gt;
        	</div>-->
        </div>
    </div>
    <div id="content"><hr class="hr0"/>
    <?php
		$file_path='pages/'.$curr_pn.'.html';
		if(file_exists($file_path)){
			$file_xml=simplexml_load_file($file_path);
			echo $file_xml->body->asXML();
		}else{
			echo 'blog file not found.<br />';
		}
	?>
    <hr class="hr0" />
    </div>
    <div id="footer">
    	<!--
    	<div id="button-prev" class="header-button">
        	&lt;&lt; 上一篇
        </div>
    	-->
        <div class="header-button"
        onclick="window.open('./','_top')">—— 海龟的漂浮岛 ——</div>
        <div class="header-button"
        onclick="window.open('directory.php','_self')">—— 回到目录 ——</div>
        <!--
        <div id="button-next" class="header-button">
        	下一篇 &gt;&gt;
        </div>
        -->
        <div id="footer-info" class="info">
        Page Function Last Update: 2013-11-17 00:33
        </div>
    </div>
    
</div>
</body>
</html>
