<?php

include_once './configure.php';

function updatePageList($list="page_list.csv", $dir="./pages"){
	if(
		(file_exists($list)&&(time()-filemtime($list)>86400))
		||
		(!file_exists($list))
	){
		$cateList=array();
		$mdList=array();
		$pn=0;
		$config=new Config();
		try{
			$updateTime=time();
			if(file_exists($list)){
				if(!rename($list, "./$list.$updateTime.bak")){
					throw new Exception("Failed to rename to backup file.");
				}
			}
			$dirList=scandir($dir);
			// Except . and ..
			if(count($dirList)<3){
				echo "Empty pages dir. Abort updating.";
				if(file_exists("$list.$updateTime.bak")){
					if(rename("$list.$updateTime.bak", $list)){
						throw new Exception("Success restore list file.");
					}else{
						throw new Exception("Failed to restore list file.");
					}
				}
			}

			for($i=2;$i<count($dirList);$i++){

				// Except any file name by . beginning
				if($dirList[$i][0]=='.'){
					continue;
				}
				// Scan dir in category
				$mds=scandir("$dir/{$dirList[$i]}");
				if(count($mds)<3){
					continue;
				}
				
				// Read every markdown
				
				$map=array();	// order map of all markdown file
				foreach($mds as $mdfile){

					// Ignore any file begin with .
					if($mdfile[0]=="."){
						continue;
					}
					// Ignore no-markdown file
					$pi=pathinfo($mdfile);
					if(!isset($pi['extension'])||($pi['extension']!='md')){
						continue;
					}
					$fn="$dir/{$dirList[$i]}/$mdfile";
					if(file_exists($fn)){
						$stat=stat($fn);
						if(!$stat){
							throw new Exception("Failed to stat markdown file: $fn");
						}
						if(($fp=fopen($fn, 'r'))!==false){
							// title
							$mdList[$mdfile]['title']=trim(fgets($fp), "# \n\t\r\0");
							// preview
							$pmd='';
							for($pl=0;$pl<$config->nPreviewLine;$pl++){
								$pmd.=urlencode(fgets($fp));
							}
							$mdList[$mdfile]['preview']=$pmd;
							// mtime
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
					// pn,name,mtime,title,preview
					fputs($fp, "$pn,$name,$mtime,{$mdList[$name]['title']},{$mdList[$name]['preview']}\n");
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

	$rslt=false;
	if(($h=fopen($list, 'r'))!==false){
		$cate='';
		$nextData=array();
		while(($data=fgetcsv($h))!==false){

			if(count($data)==1){
				$cate=$data[0];
				continue;	// not record into last data
			}elseif(
				(is_numeric($pid)&&($pid>=$data[0]))
				||
				($pid==$data[1])
			){
				$rslt=array(
					'cate'=>$cate, 
					'next'=>toPageData($nextData)
				)+toPageData($data);
			}elseif($rslt!==false){
				$rslt+=array(
					'prev'=>toPageData($data)
				);
				break;	// only take one page for prev
			}
			$nextData=$data;
		}
		fclose($h);
	}
	return $rslt;
}

function findCateData($cate, $list="./page_list.csv"){

	$rslt=array();
	if(($h=fopen($list, 'r'))!==false){
		$isInCate=false;
		while(($data=fgetcsv($h))!==false){

			if(count($data)==1){
				if(
					($data[0]==$cate)
					||
					($cate=='all')
				){
					$isInCate=true;
				}else{
					$isInCate=false;
				}
				$ccate=$data[0];
			}elseif($isInCate){
				$rslt[]=array(
					'cate'=>$ccate
				)+toPageData($data);
			}
		}
		fclose($h);
	}
	return $rslt;
}

function toPageData($raw){
	if(count($raw)<3){
		return false;
	}else{
		return array(
			'pn'=>$raw[0],
			'name'=>$raw[1],
			'mtime'=>$raw[2],
			'title'=>$raw[3],
			'preview'=>$raw[4],
		);
	}
}

?>