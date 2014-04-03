<?php
/**
 * Translate is Translator singleton wrapper.
 *
 * @version 1.3
 * @author MPI
 * */
class Translate{
	private static $translator = null;

	private function Translate(){
	}

	/**
	 * Get string or string pattern from actual Translator.
	 *
	 * @param string $key
	 *        	string index (constant defined in Translator root object) to index item
	 * @return string
	 */
	public static function get($key){
		if(empty(self::$translator)){
			self::initTranslator($_SESSION["user"]["lang"]);
		}
		return self::$translator->get($key);
	}

	/**
	 * Print string from actual Translator.
	 *
	 * @param string $key
	 *        	string index (constant defined in Translator root object) to index item
	 * @return nothing
	 */
	public static function display($key){
		if(empty(self::$translator)){
			self::initTranslator($_SESSION["user"]["lang"]);
		}
		echo htmlspecialchars(self::$translator->get($key));
	}
	
	/**
	 * Reinitialize translator to new language.
	 * Called only in updateUser function!
	 * 
	 * @param integer $lang_id
	 */
	public static function changeLang($lang_id){
		self::initTranslator($lang_id);
	}

	/**
	 * Create new translator.
	 * 
	 * @param integer $lang_id
	 */
	private static function initTranslator($lang_id){
		switch($lang_id){
			case Translator::LANG_EN:
				self::$translator = new EnglishTranslator();
				break;
			case Translator::LANG_CZ:
				self::$translator = new CzechTranslator();
				break;
			default:
				self::$translator = new EnglishTranslator();
		}
	}
}
?>