<?php

class Page{
	public $number=0;
	public $name="";
	public $category="none";
	public $dir="";

	private $path="pages";

	public $date="";
	public $size=0;
	public $title="";
	public $cp=-1;

	public $hasPrevPage=false;
	public $hasNextPage=false;
	public $nextPageData=array();

	function __construct($d){
		$this->number=$d['pn'];
		$this->name=$d['name'];
		$this->category=$d['category'];
		if(isset($d['path'])){
			$this->dir="{$d['path']}/";
		}
		$this->path=$this->dir.$this->category.$this->name.".md";
		$this->hasPrevPage=$this->number>0?true:false;
		if(!$d['next']){
			$this->hasNextPage=false;
		}else{
			$this->hasNextPage=true;
			$this->nextPageData=$d['next'];
		}

		// stat file
		$stat=stat($this->path);
		if(!$stat){
			throw new Exception("Failed to stat page file.", 500);
		}
		$this->date=date('Y-m-d H:i:s (e/O)', stat['mtime']);
		$this->size=stat['size'];

		// open and read
		if(($h=fopen($this->path, 'r'))!==false){
			while(($data=fgets($h))!==false){
				$this->title=$data;
				$this->cp=ftell($h);
				break;
			}
			fclose($h);
		}else{
			throw new Exception("Failed to open page file", 500);
		}

	}

	function getContent($hasTitle=false, $isFull=true){

		if(!file_exists($this->path)){
			throw new Exception("Page file not found.", 404);
		}

		// open and read
		if(($c=file_get_contents($this->path, false, NULL, $this->cp))!==false){
			return $c;
		}else{
			throw new Exception("Failed to read page file", 500);
		}
	}

}
?>