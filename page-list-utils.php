<?php

function updatePageList($list="page_list.csv", $dir="pages"){
	if(
		(file_exist($list)&&(time()-filemtime($list)>86400)
		||
		(!file_exists($list))
	){
		$cateList=array();
		$pn=0;
		try{
			$updateTime=time();
			if(!rename($list, "$list.$updateTime.bak")){
			}
			$dirList=scandir($dir);
			// Except . and ..
			if(count($dirList)<3){
				echo "Empty pages dir. Abort updating.";
				if(rename("$list.$updateTime.bak", $list)){
					throw new Exception("Success restore list file.");
				}else{
					throw new Exception("Failed to restore list file.");
				}
			}

			for($i=2;$i<count($dirList);$i++){
				// Scan dir in category
				$mds=scandir("$dir/{$dirList[$i]}");
				if(count($mds)<3){
					continue;
				}
				// Order Map
				$map=array();
				foreach($mds as $mdfile){
					if(($mdfile==".")||($mdfile=="..")){
						continue;
					}
					if(file_exists($mdfile)){
						$stat=stat("$dir/{$dirList[$i]}/$mdfile");
						if(!$stat){
							throw new Exception("Failed to stat markdown file: $dir/{$dirList[$i]}/$mdfile");
						}
						$map[$mdfile]=$stat['mtime'];
					}
				}
				arsort($map);
				// Push it into list and count
				$cateList[]=array('name'=>$dirList[$i], 'map'=>$map);
				$pn+=count($map);
			}
			// Store list into file
			$fp=fopen($list, 'w');
			foreach($cateList as $category){
				// category in first line
				fputs($fp, $category['name']."\n");
				// page file info 
				foreach($category['map'] as $name=>$mtime)
					if($pn<0){
						throw new Exception("Invalid page count");
					}
					// pn name mtime
					fputs($fp, "$pn,$name,$mtime\n");
					$pn--;
				}
			}
			fclose($fp);
		}catch(Exception $e){
			echo $e->getMessage();
		}
	}
}

function findPageData($pid, $list="page_list.csv"){

	if (($handle = fopen("test.csv", "r")) !== FALSE) {
	    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
	        $num = count($data);
	        echo "<p> $num fields in line $row: <br /></p>\n";
	        $row++;
	        for ($c=0; $c < $num; $c++) {
	            echo $data[$c] . "<br />\n";
	        }
	    }
	    fclose($handle);
	}

	if(($h=fopen($list, 'r'))!==false){
		$cate='';
		while(($data=fgetcsv($h))!==false){
			if(count($data)=1){
				$cate=$data[0];
			}elseif(is_numeric($pid)&&($pid==$data[0])){
				fclose($h)
				return array('cate'=>$cate)+toPageData($data);
			}elseif($pid==$data[1]){
				fclose($h)
				return array('cate'=>$cate)+toPageData($data);
			}
		}
		fclose($h)
	}
	return false;
}

function toPageData($raw){
	return array(
		'pn'=>$raw[0],
		'name'=>$raw[1],
		'mtime'=>$raw[2],
	)
}

?>