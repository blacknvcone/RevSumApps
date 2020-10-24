<?php
namespace App\Libraries;

class CSV_Data{

	protected $cache = array();
	protected $lang;
	protected $file;

	public function __construct($file){
		$this -> setFile($file);
	}

    public function setFile($file) {
		$this -> file = preg_replace("#[\////]+#",DIRECTORY_SEPARATOR, $file);
		$path = $this -> file;
		if(!array_key_exists($path, $this -> cache)){
			if(file_exists($path)){
				$found = true;
			} else {
				$found = false;
			}
			if($found){
				$file = fopen($path, 'r');
				$flex = true;
				$array = array();
				$names = array();
				while (($line = fgetcsv($file,0,',','"',"\\")) !== FALSE) {
					if($line){
						$name = array_shift($line);
						if($flex){
							if(!$line){
								break;
							}
							$indexes = array();
							foreach($line as $index1){
								if(!array_key_exists($index1, $array)){
									$array[$index1] = array();
									$indexes[] = $index1;
								}
							}
							$flex = false;
						} else {
							if(!array_key_exists($name, $names)){
								foreach($indexes as $i => $index1){
									if(array_key_exists($i, $line)){
										$array[$index1][$name] = stripslashes($line[$i]);
									} else {
										$array[$index1][$name] = null;
									}
								}
								$names[$name] = true;
							}
						}
					}
				}
			} else {
				$array = array();
			}
			$this -> cache[$path] = $array;
		}
		return $this;
	}

	public function get($x, $y){
		$path = $this -> file;
		if(!empty($this -> cache[$path][$x]) && array_key_exists($y, $this -> cache[$path][$x])){
			return $this -> cache[$path][$x][$y];
		} else {
			return "";
		}
	}
	
	public function edit($x, $y, $new_value){
		$key = key($this -> cache[$this -> file]);
		if(!array_key_exists($y,$this -> cache[$this -> file][$key])){
			$keys_z = array();
			foreach($this -> cache[$this -> file][$key] as $keyz=>$val){
				if(empty($this -> cache[$this -> file][$x])||
					!array_key_exists($keyz,$this -> cache[$this -> file][$x])){
					$this -> cache[$this -> file][$x][$keyz] = "";
					$this -> cache[$this -> file][$key][$y] = "";
				}
			}
		}
		$this -> cache[$this -> file][$x][$y] = $new_value;
		return $this;
	}
	
	public function save()
	{
		$lines = array(array("index"));
		$xindex = 1;
		$yindex = 0;
		foreach($this -> cache[$this -> file] as $x => $value1)
		{
			$no = 0;
			$lines[0][$xindex] = $x;
			$xindex++;
			$yindex++;
			foreach($value1 as $y => $value){
				$no++;
				$lines[$no][0] = $y;
				$lines[$no][$yindex] = $value;
			}
		}
		$fp = fopen($this -> file, 'w');
		foreach($lines as $i1=>$line){
			fwrite($fp, ($i1?"\n":""));
			end($line);
			$key = key($line);
			reset($line);
			for($i2=0;$i2<=$key;$i2++){
				fwrite($fp, (($i2)?', ':'').'"'.(array_key_exists($i2,$line)?addcslashes($line[$i2],'\"'):"").'"');
			}
		}
		fclose($fp);
	}
}