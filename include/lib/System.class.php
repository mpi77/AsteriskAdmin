<?php

/**
 * System class provides some "tool" functions.
 *
 * @version 1.17
 * @author MPI
 * */
class System{
	
	/* date */
	const DATE_ADD_DAYS = 1;
	const DATE_FORMAT_TO_TS = 2;
	const DATE_FORMAT_FROM_TS = 3;
	
	/* pagging, sorting defaults */
	const PAGE_SIZE_DEFAULT = 20;
	const DATA_COUNT_DEFAULT = 0;
	const PAGE_ACTUAL_DEFAULT = 1;
	const PAGE_MIN_PAGE = 1;
	const SORT_DEFAULT_DIRECTION = "A";
	const SORT_DEFAULT_COLUMN = 0;
	const SORT_ASC = "A";
	const SORT_DES = "D";
	const SORT_ASC_FULL = "ASC";
	const SORT_DES_FULL = "DESC";
	public static $page_size = array(
			20,
			40,
			60,
			80,
			100
	);

	private function __construct(){
	}
	
	/**
	 * sorting index - column index to sort; time_format - pattern if sort datetime
	 */
	public static $usort = array(
			"sorting_index" => 0, //
			"xm" => -1,
			"xv" => 1,
			"time_format" => "Y-m-d H:i:s"
	);

	/**
	 * Comparing function (as an argument to sort function) to compare strings.
	 *
	 * @param string $a        	
	 * @param string $b        	
	 * @return int
	 */
	public static function usortCallbackCmpCzechCi($a, $b){
		$a = $a[self::$usort["sorting_index"]];
		$b = $b[self::$usort["sorting_index"]];
		$alphabet = null;
		if($alphabet === null){
			$alphabet = array_flip(array(
					'0',
					'1',
					'2',
					'3',
					'4',
					'5',
					'6',
					'7',
					'8',
					'9',
					'a',
					'A',
					'á',
					'Á',
					'b',
					'B',
					'c',
					'C',
					'č',
					'Č',
					'd',
					'D',
					'ď',
					'Ď',
					'e',
					'E',
					'é',
					'É',
					'ě',
					'Ě',
					'f',
					'F',
					'g',
					'G',
					'h',
					'H',
					'ch',
					'Ch',
					'i',
					'I',
					'í',
					'Í',
					'j',
					'J',
					'k',
					'K',
					'l',
					'L',
					'm',
					'M',
					'n',
					'N',
					'o',
					'O',
					'ó',
					'Ó',
					'p',
					'P',
					'q',
					'Q',
					'r',
					'R',
					'ř',
					'Ř',
					's',
					'S',
					'š',
					'Š',
					't',
					'T',
					'ť',
					'Ť',
					'u',
					'U',
					'ú',
					'Ú',
					'ů',
					'Ů',
					'v',
					'V',
					'w',
					'W',
					'x',
					'X',
					'y',
					'Y',
					'ý',
					'Ý',
					'z',
					'Z',
					'ž',
					'Ž',
					''
			));
		}
		
		$len = min(mb_strlen($a), mb_strlen($b));
		for($i = 0; $i < $len; $i++){
			if(($a[$i] == "c" || $a[$i] == "C") && ($i + 1 < $len && ($a[$i + 1] == "h" || $a[$i + 1] == "H"))){
				if(($b[$i] == "c" || $b[$i] == "C") && ($b[$i + 1] == "h" || $b[$i + 1] == "H")){
					$i++;
					continue;
				}
				if($alphabet[$b[$i]] < $alphabet['ch'])
					return self::$usort["xv"];
				if($alphabet[$b[$i]] > $alphabet['ch'])
					return self::$usort["xm"];
			}
			if(($b[$i] == "c" || $b[$i] == "C") && ($i + 1 < $len && ($b[$i + 1] == "h" || $b[$i + 1] == "H"))){
				if($alphabet[$a[$i]] < $alphabet['ch'])
					return self::$usort["xm"];
				if($alphabet[$a[$i]] > $alphabet['ch'])
					return self::$usort["xv"];
			}
			
			if($a[$i] == $b[$i])
				continue;
			
			if(!isset($alphabet[$a[$i]]))
				return self::$usort["xv"];
			if(!isset($alphabet[$b[$i]]))
				return self::$usort["xm"];
			return ($alphabet[$a[$i]] > $alphabet[$b[$i]] ? self::$usort["xv"] : self::$usort["xm"]);
		}
		return mb_strlen($a) < mb_strlen($b) ? self::$usort["xm"] : self::$usort["xv"];
	}

	/**
	 * Comparing function (as an argument to sort function) to compare integers.
	 *
	 * @param int $a        	
	 * @param int $b        	
	 * @return int
	 */
	public static function usortCallbackCmpNumbers($a, $b){
		$a = $a[self::$usort["sorting_index"]];
		$b = $b[self::$usort["sorting_index"]];
		
		return ($a > $b) ? self::$usort["xv"] : self::$usort["xm"];
	}

	/**
	 * Comparing function (as an argument to sort function) to compare datetime.
	 *
	 * @param int $a        	
	 * @param int $b        	
	 * @return int
	 */
	public static function usortCallbackCmpTime($a, $b){
		$a = DateTime::createFromFormat(self::$usort["time_format"], $a[self::$usort["sorting_index"]]);
		$b = DateTime::createFromFormat(self::$usort["time_format"], $b[self::$usort["sorting_index"]]);
		
		return ($a->getTimestamp() > $b->getTimestamp()) ? self::$usort["xv"] : self::$usort["xm"];
	}

	/**
	 * Find all files in given directory.
	 *
	 * @param string $dir
	 *        	where start listing
	 * @param array $exclude
	 *        	with names to exlude from result
	 * @return 1D string array
	 */
	public static function findAllFiles($dir, $exclude){
		$root = scandir($dir);
		$result = array();
		foreach($root as $value){
			if(in_array($value, $exclude)){
				continue;
			}
			if(is_file("$dir/$value")){
				$result[] = "$dir/$value";
				continue;
			}
			foreach(self::findAllFiles("$dir/$value", $exclude) as $value){
				$result[] = $value;
			}
		}
		return $result;
	}

	/**
	 * Detect runtime exception and show it's message box.
	 */
	public static function makeExceptionCont(){
		if(isset($_SESSION["exception"]) && !empty($_SESSION["exception"])){
			echo sprintf("<div id=\"exception\">%s</div>", $_SESSION["exception"]->__toString());
		}
	}

	/**
	 * Detect runtime exception.
	 *
	 * @return boolean
	 */
	public static function isException(){
		if(isset($_SESSION["exception"]) && !empty($_SESSION["exception"])){
			return true;
		}else{
			return false;
		}
	}

	/**
	 * Compare given 1D arrays.
	 * If all values from array $a are on same index in array $b, return true.
	 *
	 * @param array $a        	
	 * @param array $b        	
	 * @return boolean
	 */
	public static function isArraySame($a, $b){
		if(count($a) != count($b))
			return false;
		for($i = 0; $i < count($a); $i++){
			if($a[$i] != $b[$i] || empty($a[$i]) != empty($b[$i]))
				return false;
		}
		return true;
	}

	/**
	 * Check if given array is multidimensional.
	 *
	 * @param array $a        	
	 * @return boolean
	 */
	public static function isArrayMultidimensional($a){
		if(!is_array($a)){
			return false;
		}
		foreach($a as $v){
			if(is_array($v))
				return true;
		}
		return false;
	}

	/**
	 * Check if is given date valid.
	 *
	 * @param string $date        	
	 * @param string $format        	
	 * @return boolean
	 */
	public static function isDateValid($date, $format = 'Y-m-d H:i:s'){
		$d = DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) == $date;
	}

	/**
	 * Get format of given date.
	 *
	 * @param string $date        	
	 * @return null or string
	 */
	public static function getDateFormat($date){
		$r = null;
		if(preg_match("/^([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2}):([0-9]{2}):([0-9]{2})$/i", $date) === 1){
			$r = "Y-m-d H:i:s";
		}else if(preg_match("/^([0-9]{2})\.([0-9]{2})\.([0-9]{4}) ([0-9]{2}):([0-9]{2}):([0-9]{2})$/i", $date) === 1){
			$r = "d.m.Y H:i:s";
		}
		return $r;
	}

	/**
	 * Trim&slash on 1D integer indexed array.
	 * If item value is null, this item is added at same place.
	 *
	 * @param boolean $trim
	 *        	(true = make trim)
	 * @param boolean $slash
	 *        	(true = make addslashes)
	 * @return 1D array
	 */
	public static function trimSlashArray1d($array, $trim = false, $slash = false){
		$a = array();
		for($i = 0; $i < count($array); $i++){
			$tmp = null;
			if(!empty($array[$i])){
				if($trim === true)
					$tmp = trim($array[$i]);
				if($slash === true)
					$tmp = addslashes($tmp);
			}
			array_push($a, $tmp);
		}
		return $a;
	}

	/**
	 * Trim&slash on 1D string associative indexed array.
	 * If item value is null, this item is added at same place.
	 *
	 * @param boolean $trim
	 *        	(true = make trim)
	 * @param boolean $slash
	 *        	(true = make addslashes)
	 * @return 1D array
	 */
	public static function trimSlashArray1dAssociative($array, $trim = false, $slash = false){
		$a = array();
		foreach($array as $k => $v){
			$tmp = null;
			if(!empty($v)){
				if($trim === true)
					$tmp = trim($v);
				if($slash === true)
					$tmp = addslashes($tmp);
			}
			$a[$k] = $tmp;
		}
		return $a;
	}

	/**
	 * Initialize session
	 */
	public static function initSession(){
		if(!isset($_SESSION["user"])){
			$_SESSION["user"]["uid"] = null;
			$_SESSION["user"]["email"] = null;
			$_SESSION["user"]["first_name"] = null;
			$_SESSION["user"]["last_name"] = null;
			$_SESSION["user"]["last_login"] = null;
			$_SESSION["user"]["type"] = null;
			$_SESSION["user"]["lang"] = Translator::LANG_CZ;
			$_SESSION["user"]["auth"] = false;
		}
		if(!isset($_SESSION["page_size"])){
			$_SESSION["page_size"] = self::PAGE_SIZE_DEFAULT;
		}
		$_SESSION["exception"] = null;
		$_SESSION["view"] = true;
	}

	/**
	 * Check if View is enabled.
	 *
	 * @return booleean
	 */
	public static function isViewEnabled(){
		return (isset($_SESSION["view"]) && $_SESSION["view"]);
	}

	/**
	 * Set View to enabled.
	 */
	public static function setViewEnabled(){
		$_SESSION["view"] = true;
	}

	/**
	 * Set View to disabled.
	 */
	public static function setViewDisabled(){
		$_SESSION["view"] = false;
	}

	/**
	 * Unset exception detector.
	 */
	public static function clearException(){
		$_SESSION["exception"] = null;
	}

	/**
	 * The is_callable php function only considers methods declared in the class itself, and ignores the parent's.
	 * This version considers all of the hierarchy.
	 *
	 * @param (string|Object) $class_name        	
	 * @param string $method_name        	
	 * @param bool $static
	 *        	the method being tested is static.
	 */
	public static function isCallable($class_name, $method_name, $static = false){
		if(!is_string($class_name)){
			$class_name = get_class($class_name);
		}
		
		if($static){
			$callable = "{$class_name}::{$method_name}";
		}else{
			$callable = array(
					$class_name,
					$method_name
			);
		}
		
		if(@is_callable($callable) === true){
			return true;
		}
		
		while($parent_class = get_parent_class($class_name)){
			if(@is_callable($callable) === true){
				return true;
			}
			$class_name = $parent_class;
		}
		
		return false;
	}

	/**
	 * Convert given date to timestamp and back.
	 * Also it is possible to add some days to timestamp object.
	 *
	 * @param array $args
	 *        	1D with these args ("act"=> action, "old"=> time, "add_days"=> int)
	 * @return string
	 */
	public static function prepareDate($args){
		if(!isset($args["act"]))
			return null;
		$new = null;
		$matches = null;
		
		switch($args["act"]){
			case self::DATE_ADD_DAYS:
				/*
				 * 	IN - timestamp
				 *  OUT - timestamp
				 */
				preg_match("/^(([0-9]{4})-([0-9]{2})-([0-9]{2})) (([0-9]{2}):([0-9]{2}):([0-9]{2}))$/i", $args["old"], $matches);
				if(!empty($matches)){
					$date = new DateTime($matches[1]);
					$date->modify(sprintf("+%d day", $args["add_days"]));
					$new = date_format($date, 'Y-m-d') . " " . $matches[5];
				}
				break;
			case self::DATE_FORMAT_FROM_TS:
				/*
				 * 	IN - timestamp
				 *  OUT - dd.mm.yyyy hh:mm:ss
				 */
				preg_match("/^(([0-9]{4})-([0-9]{2})-([0-9]{2})) (([0-9]{2}):([0-9]{2}):([0-9]{2}))$/i", $args["old"], $matches);
				if(!empty($matches)){
					$new = sprintf("%s.%s.%s %s", $matches[4], $matches[3], $matches[2], $matches[5]);
				}
				break;
			case self::DATE_FORMAT_TO_TS:
				/*
				 * 	IN - d(d).m(m).yyyy h(h):m(m):s(s)
				 *  OUT - timestamp
				 */
				preg_match("/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})[ ]?(([0-9]{1,2})\:([0-9]{1,2})(\:([0-9]{1,2})|)|)$/i", $args["old"], $matches);
				if(empty($matches[4])){
					$matches[5] = "0";
					$matches[6] = "0";
					$matches[8] = "0";
				}
				$matches[8] = empty($matches[8]) ? "0" : $matches[8];
				if(!empty($matches) && isset($matches[2]) && isset($matches[3]) && isset($matches[2]) && checkdate($matches[2], $matches[1], $matches[3]) && $matches[5] >= 0 && $matches[6] >= 0 && $matches[8] >= 0 && $matches[5] < 24 && $matches[6] < 60 && $matches[8] < 60){
					$new = sprintf("%04d-%02d-%02d %02d:%02d:%02d", $matches[3], $matches[2], $matches[1], $matches[5], $matches[6], $matches[8]);
				}else{
					$new = null;
				}
				break;
		}
		return $new;
	}

	/**
	 * Generates random code with specific length.
	 *
	 * @param integer $length
	 *        	of returned generated code
	 * @return 1D string
	 */
	public static function generateRandomCode($length){
		$v = array(
				"1",
				"2",
				"3",
				"4",
				"5",
				"6",
				"7",
				"8",
				"9",
				"a",
				"b",
				"c",
				"d",
				"e",
				"f",
				"g",
				"h",
				"i",
				"j",
				"k",
				"l",
				"m",
				"n",
				"p",
				"q",
				"r",
				"s",
				"t",
				"u",
				"v",
				"w",
				"x",
				"y",
				"z",
				"A",
				"B",
				"C",
				"D",
				"E",
				"F",
				"G",
				"H",
				"I",
				"J",
				"K",
				"L",
				"M",
				"N",
				"P",
				"Q",
				"R",
				"S",
				"T",
				"U",
				"V",
				"W",
				"X",
				"Y",
				"Z"
		);
		$str = "";
		for($i = 0; $i <= $length - 1; $i++){
			$nahoda = rand(0, count($v) - 1);
			$str .= $v[$nahoda];
		}
		return $str;
	}

	/**
	 * Remove given files.
	 *
	 * @param
	 *        	string array $files with full names of files to remove
	 */
	public static function removeFiles($files){
		foreach($files as $v){
			if(file_exists($v)){
				unlink($v);
			}
		}
	}

	/**
	 * Redirect to given url.
	 *
	 * @param string $url
	 *        	to jump
	 */
	public static function redirect($url){
		header(sprintf("Location: %s", $url));
		exit();
	}

	/**
	 * Get php version exploded in array.
	 *
	 * @return array
	 */
	public static function detectPhpVersion(){
		return explode('.', phpversion());
	}

	/**
	 * Trace variable.
	 *
	 * @param $var to
	 *        	print.
	 */
	public static function trace($var){
		echo "<pre>";
		print_r($var);
		echo "</pre>";
		exit();
	}
}
?>