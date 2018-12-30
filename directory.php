<?php
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Directory</title>
<link href="directory.css" rel="stylesheet" type="text/css" /></head>

<body bgcolor="#EEEEEE">
<div id="warpper">—— 全 部 文 章 ——<hr class="hr0" />
<?php
if (file_exists('blog.xml')){
	$xml = simplexml_load_file('blog.xml');
	foreach($xml->children() as $child){
		$blog_path='pages/'.$child.'.xml';
		echo '<div class="blog-cell" ';
		if(file_exists($blog_path)){
			$blog_xml=simplexml_load_file($blog_path);
			echo 'onclick="window.open(\'page.php?p='.$child.'\',\'mainFrame\')">';
			//echo '<div class="icon-container">图标</div>';
			echo '<div class="title-container">';
				echo '<div class="title">'.$blog_xml->title.'</div>';
				echo '<div class="info">'.$blog_xml->category.' '.$blog_xml->date.'</div>';
			echo '</div><div class="title-line">'.$blog_xml->preview.'<br />'.'</div>';
			echo '</div>';
			echo '<hr class="hr0" />';
		}else{
			echo '>blog xml file not found.<br /><div class="title-line"></div></div>';
		}
	}
}else{
	echo "directory xml file not found.<br />";
}
?>
	<div id="footer">
    	<div id="footer-info" class="info">
        List Function Last Update: 2013-11-17 00:34
        </div>
    </div>
</div>
</body>
</html>
