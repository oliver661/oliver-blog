<?php

class Page{
	public $number=0;
	public $mdname="";
	public $category="none";
	public $dir="";
	private $path="";

	public $date="";
	public $title="";

	function __construct($pn, $n, $c, $d="pages"){
		$this->number=$pn;
		$this->mdname=$n;
		$this->category=$c;
		$this->dir="$p/"
		$this->path=$this->dir.$this->category.$this->mdname.".md";

		// stat file
		$stat=stat($this->path);
		if(!$stat){
			throw new Exception("Failed to stat markdown file.");
		}

		// open and read
		if(($h=fopen($this->path, 'r'))!==false){
			while(($data=fgets($h))!==false){
				$this->title=$data;
			}
			fclose($h);
		}

	}

	function getContent($hasTitle=false, $isFull=true){}

}
?>