<?php

function updatePageList($list="page_list.csv", $dir="pages"){
	if(
		(file_exist($list)&&(time()-filemtime($list)>86400))
		||
		(!file_exists($list))
	){
		$cateList=array();
		$mdList=array();
		$pn=0;
		try{
			$updateTime=time();
			if(!rename($list, "$list.$updateTime.bak")){
				throw new Exception("Failed to rename to backup file.");
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
					$fn="$dir/{$dirList[$i]}/$mdfile";
					if(file_exists($mdfile)){
						$stat=stat($fn);
						if(!$stat){
							throw new Exception("Failed to stat markdown file: $fn");
						}
						if(($fp=fopen($fn, 'r'))!==false){
							$mdList[$mdfile]=array(
								'title'=>rtrim(fgets($fp)),
							);
							$map[$mdfile]=$stat['mtime'];
						}else{
							throw new Exception("Failed to open markdown file: $fn");
						}
					}
				}
				arsort($map);
				// Push it into list and count
				$cateList[]=array('name'=>$dirList[$i], 'map'=>$map);
				$pn+=count($map);
			}
			// Take last dir into first(greater is latter one)
			$dirList=array_reverse($dirList);
			// Store list into file
			$fp=fopen($list, 'w');
			foreach($cateList as $category){
				// category in first line
				fputs($fp, $category['name']."\n");
				// page file info 
				foreach($category['map'] as $name=>$mtime){
					if($pn<0){
						throw new Exception("Invalid page count");
					}
					// pn,name,mtime,title,
					fputs($fp, "$pn,$name,$mtime,{$mdList[$name]['title']}\n");
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

	if(($h=fopen($list, 'r'))!==false){
		$cate='';
		$nextData=array();
		while(($data=fgetcsv($h))!==false){
			if(count($data)==1){
				$cate=$data[0];
				continue;	// not record into last data
			}elseif(is_numeric($pid)&&($pid>=$data[0])){
				fclose($h);
				return array('cate'=>$cate, 'next'=>toPageData($nextData))+toPageData($data);
			}elseif($pid==$data[1]){
				fclose($h);
				return array('cate'=>$cate, 'next'=>toPageData($nextData))+toPageData($data);
			}
			$nextData=$data;
		}
		fclose($h);
	}
	return false;
}

function toPageData($raw){
	if(count($raw)<3){
		return false;
	}else{
		return array(
			'pn'=>$raw[0],
			'name'=>$raw[1],
			'mtime'=>$raw[2],
		);
	}
}

?>