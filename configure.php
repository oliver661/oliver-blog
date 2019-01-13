<?php

class Config{
	public $nPreviewLine=0;
	public $cNameCate=array();

	private $configPath="config.csv";

	function __construct(){
		if(!file_exists($configPath)){

			// Uncomment following if prefer to take no configuration file
			// throw new Exception("Configuration file not found.", 500);
			return;
		}
		if(($fp=fopen($configPath))!==false){
			$cmode='';
			while(($cls=fgetcsv($fn))!==false){
				if((count($cls)<0)||($cls[0]=='#')){
					$cmode='';
					continue;
				}
				switch($cls[0]){
					case 'n_preview_line':
						$this->nPreviewLine=int($cls[1]);
						break;
					case 'custom_name_category':
						$cmode='cnc';
						break;
					default:
						if($cmode=='cnc'){
							$this->cNameCate[$cls[0]]=$cls[1];
						}
						break;
				}
			}
		}else{
			throw new Exception("Failed to open configuration file.", 500);
		}
	}
}

?>