<?php

include "./page-utils.php";

getHTMLBeginer("Directory", "directory.css");
echo '—— 全 部 文 章 ——<hr class="hr0" />';

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

getFooter(false);
getHTMLFinaller();

?>