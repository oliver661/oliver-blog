<?php

class Page{
	public $number=0;
	public $mdname="";
	public $category="none";
	public $dir="";

	private $path="";

	public $date="";
	public $size=0;
	public $title="";
	public $cp=-1;

	function __construct($pn, $n, $c, $d="pages"){
		$this->number=$pn;
		$this->mdname=$n;
		$this->category=$c;
		$this->dir="$p/"
		$this->path=$this->dir.$this->category.$this->mdname.".md";

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