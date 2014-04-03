<?php

/**
 * Paginator class makes list table string with support of paging.
 *
 * @version 1.9
 * @author MPI
 *
 */
class Paginator{

	private function __construct(){
	}

	/**
	 * Get list table string on sorted data.
	 *
	 * @param array $header
	 *        	1D with name of columns
	 * @param array $data
	 *        	2D with data to show
	 * @param array $config
	 *        	xD witch config values
	 *        	"config" => array(
	 *        	"page" => string,
	 *        	"column" => string,
	 *          "data_count" => int,
	 *        	"direction" => string,
	 *        	"actual_pagesize" => string,
	 *        	"disable_menu" => bool,
	 *        	"disable_select" => bool,
	 *        	"disable_pagging" => bool,
	 *        	"disable_set_pagesize" => bool
	 *        	),
	 *        	"form_url" => array(
	 *        	"page" => string,
	 *        	"header_sort" => string,
	 *        	"form_action" => string
	 *        	),
	 *        	"item_menu" => array(
	 *        	0 => array("body"=>string,"title"=>string,"url"=>string, "class"=>string)
	 *        	),
	 *        	"select_item_action" => array(
	 *        	0 => array("value"=>string,"title"=>string)
	 *        	),
	 *        	"style" => array(
	 *        	"marked_row_class" => string,
	 *        	"count_box_class" => string,
	 *        	"pagging_box_class" => string,
	 *        	"actual_page_class" => string,
	 *        	"table_header_class" => string,
	 *        	"table_id" => string,
	 *        	"select_form_id" => string,
	 *        	"pagesize_form_id" => string,
	 *        	"list_footer_id" => string
	 *        	)
	 * @throws NoticeException
	 * @return string
	 */
	public static function generatePage($header, $data, $config){
		$s = "";
		
		$page_size = (isset($config["config"]["actual_pagesize"]) && is_numeric($config["config"]["actual_pagesize"])) ? $config["config"]["actual_pagesize"] : System::PAGE_SIZE_DEFAULT;
		$data_count = (isset($config["config"]["data_count"]) && is_numeric($config["config"]["data_count"])) ? $config["config"]["data_count"] : System::DATA_COUNT_DEFAULT;
		$sum_pages = self::getCountPages($page_size, $data_count);
		// validation
		if((!empty($config["config"]["column"]) && !empty($config["config"]["page"])) && (!array_key_exists($config["config"]["column"], $header) || $config["config"]["page"] < System::PAGE_MIN_PAGE || $config["config"]["page"] > $sum_pages)){
			throw new NoticeException(NoticeException::NOTICE_INVALID_PARAMETERS);
		}
		
		if(!empty($header) && !empty($data) && $data != Database::EMPTY_RESULT && count($header) > 0 && count($data) > 0 && $data_count > 0 && count($header) == count($data[0])){
			// check and set config vars
			$config["config"]["sum_pages"] = $sum_pages;
			$config["config"]["data_count"] = $data_count;
			$config["config"]["actual_pagesize"] = $page_size;
			$config["config"]["page"] = (isset($config["config"]["page"]) && is_numeric($config["config"]["page"])) ? $config["config"]["page"] : System::PAGE_ACTUAL_DEFAULT;
			$config["config"]["column"] = (isset($config["config"]["column"])) ? $config["config"]["column"] : System::SORT_DEFAULT_COLUMN;
			$config["config"]["direction"] = (isset($config["config"]["direction"])) ? $config["config"]["direction"] : System::SORT_DEFAULT_DIRECTION;
			$config["config"]["disable_menu"] = (isset($config["config"]["disable_menu"])) ? $config["config"]["disable_menu"] : false;
			$config["config"]["disable_select"] = (isset($config["config"]["disable_select"])) ? $config["config"]["disable_select"] : false;
			$config["config"]["disable_pagging"] = (isset($config["config"]["disable_pagging"])) ? $config["config"]["disable_pagging"] : false;
			$config["config"]["disable_set_pagesize"] = (isset($config["config"]["disable_set_pagesize"])) ? $config["config"]["disable_set_pagesize"] : false;
			
			if($config["config"]["disable_pagging"] === true){
				$config["config"]["sum_pages"] = "1";
				$config["config"]["page"] = "1";
			}
			// System::trace($config);
			$s .= self::makeListTableString($header, $data, $config);
		}else{
			$s .= sprintf("<div class=\"%s\">%s</div>", "empty_result_box", Translate::get(Translator::NOTHING_TO_DISPLAY));
		}
		return $s;
	}

	/**
	 * Get start row of data.
	 *
	 * @param int $page_size
	 *        	with count rows per page
	 * @param int $page
	 *        	with actual index of page
	 * @return integer
	 */
	public static function getStartRow($page, $page_size){
		return ($page * $page_size) - $page_size;
	}

	/**
	 * Get count pages of data.
	 *
	 * @param int $page_size
	 *        	with count rows per page
	 * @param int $data_count
	 *        	with count of all data found
	 * @return integer
	 */
	private static function getCountPages($page_size, $data_count){
		return ceil($data_count / $page_size);
	}

	/**
	 * Get list table string on unsorted data.
	 * All data are given in $data and function sorts all of data. Then generates table string.
	 *
	 * @param array $header
	 *        	1D with name of columns
	 * @param array $data
	 *        	2D with data to show
	 * @param array $config
	 *        	xD witch config values
	 *        	"config" => array(
	 *        	"page" => string,
	 *        	"column" => string,
	 *        	"data_count" => int,
	 *        	"direction" => string,
	 *        	"actual_pagesize" => string,
	 *        	"disable_menu" => bool,
	 *        	"disable_select" => bool,
	 *        	"disable_pagging" => bool,
	 *        	"disable_set_pagesize" => bool
	 *        	),
	 *        	"form_url" => array(
	 *        	"page" => string,
	 *        	"header_sort" => string,
	 *        	"form_action" => string
	 *        	),
	 *        	"item_menu" => array(
	 *        	0 => array("body"=>string,"title"=>string,"url"=>string, "class"=>string)
	 *        	),
	 *        	"select_item_action" => array(
	 *        	0 => array("value"=>string,"title"=>string)
	 *        	),
	 *        	"style" => array(
	 *        	"marked_row_class" => string,
	 *        	"count_box_class" => string,
	 *        	"pagging_box_class" => string,
	 *        	"actual_page_class" => string,
	 *        	"table_header_class" => string,
	 *        	"table_id" => string,
	 *        	"select_form_id" => string,
	 *        	"pagesize_form_id" => string,
	 *        	"list_footer_id" => string
	 *        	)
	 * @throws NoticeException
	 * @return string
	 */
	public static function generatePageWithUnsortedData($header, $data, $config){
		$s = "";
		$page_size = (isset($config["config"]["actual_pagesize"]) && is_numeric($config["config"]["actual_pagesize"])) ? $config["config"]["actual_pagesize"] : System::PAGE_SIZE_DEFAULT;
		$sum_pages = self::getCountPagesData($page_size, $data);
		// validation
		if((!empty($config["config"]["column"]) && !empty($config["config"]["page"])) && (!array_key_exists($config["config"]["column"], $header) || $config["config"]["page"] < System::PAGE_MIN_PAGE || $config["config"]["page"] > $sum_pages)){
			throw new NoticeException(NoticeException::NOTICE_INVALID_PARAMETERS);
		}
		
		if(!empty($header) && !empty($data) && $data != Database::EMPTY_RESULT && count($header) > 0 && count($data) > 0 && count($header) == count($data[0])){
			// check and set config vars
			$config["config"]["sum_pages"] = $sum_pages;
			$config["config"]["data_count"] = count($data);
			$config["config"]["actual_pagesize"] = $page_size;
			$config["config"]["page"] = (isset($config["config"]["page"]) && is_numeric($config["config"]["page"])) ? $config["config"]["page"] : System::PAGE_ACTUAL_DEFAULT;
			$config["config"]["column"] = (isset($config["config"]["column"])) ? $config["config"]["column"] : System::SORT_DEFAULT_COLUMN;
			$config["config"]["direction"] = (isset($config["config"]["direction"])) ? $config["config"]["direction"] : System::SORT_DEFAULT_DIRECTION;
			$config["config"]["disable_menu"] = (isset($config["config"]["disable_menu"])) ? $config["config"]["disable_menu"] : false;
			$config["config"]["disable_select"] = (isset($config["config"]["disable_select"])) ? $config["config"]["disable_select"] : false;
			$config["config"]["disable_pagging"] = (isset($config["config"]["disable_pagging"])) ? $config["config"]["disable_pagging"] : false;
			$config["config"]["disable_set_pagesize"] = (isset($config["config"]["disable_set_pagesize"])) ? $config["config"]["disable_set_pagesize"] : false;
			
			/* set sorting */
			switch($config["config"]["direction"]){
				case System::SORT_ASC:
					System::$usort["xm"] = -1;
					System::$usort["xv"] = 1;
					break;
				case System::SORT_DES:
					System::$usort["xm"] = 1;
					System::$usort["xv"] = -1;
					break;
			}
			$time_format = null;
			$cmp_function = "System::usortCallbackCmpCzechCi";
			if(is_numeric($data[0][$config["config"]["column"]])){
				$cmp_function = "System::usortCallbackCmpNumbers";
			}else if(($time_format = System::getDateFormat($data[0][$config["config"]["column"]])) != null && System::isDateValid($data[0][$config["config"]["column"]], $time_format)){
				$cmp_function = "System::usortCallbackCmpTime";
				System::$usort["time_format"] = $time_format;
			}
			System::$usort["sorting_index"] = $config["config"]["column"];
			usort($data, $cmp_function);
			
			if($config["config"]["disable_pagging"] === true){
				$config["config"]["sum_pages"] = "1";
				$config["config"]["page"] = "1";
			}else{
				$data = self::getPage($page_size, $config["config"]["page"], $data);
			}
			$s .= self::makeListTableString($header, $data, $config);
		}else{
			$s .= sprintf("<div class=\"%s\">%s</div>", "empty_result_box", Translate::get(Translator::NOTHING_TO_DISPLAY));
		}
		return $s;
	}

	/**
	 * Get data on selected page.
	 *
	 * @param integer $page_size
	 *        	with count rows per page
	 * @param array $data
	 *        	2D with data
	 * @param integer $page_number
	 *        	with page index of selected page
	 * @return array 2D
	 */
	private static function getPage($page_size, $page_number, $data){
		$a = array();
		$start_pos = (count($data) > $page_size) ? (($page_size * $page_number) - $page_size) : 0;
		$final_pos = (($t = (count($data) - ($page_size * $page_number))) > 0) ? ($page_size * $page_number) : (($page_size * $page_number) - abs($t));
		for($i = $start_pos; $i < $final_pos; $i++){
			$a[] = $data[$i];
		}
		return $a;
	}

	/**
	 * Get count pages of data.
	 *
	 * @param int $page_size
	 *        	with count rows per page
	 * @param array $data
	 *        	2D with data
	 * @return integer
	 */
	private static function getCountPagesData($page_size, $data){
		return ceil(count($data) / $page_size);
	}

	/**
	 * Make string of list HTML TABLE if data and header are not empty
	 *
	 * @param $tbl_header array
	 *        	1D with name of columns
	 * @param $tbl_data array
	 *        	2D with data to show in table
	 * @param $config array
	 *        	xD witch config values
	 *        	"config" => array(
	 *        	"page" => string,
	 *        	"column" => string,
	 *        	"direction" => string,
	 *        	"actual_pagesize" => string,
	 *        	"disable_menu" => bool,
	 *        	"disable_select" => bool,
	 *        	"disable_pagging" => bool,
	 *        	"disable_set_pagesize" => bool
	 *        	),
	 *        	"form_url" => array(
	 *        	"page" => string,
	 *        	"header_sort" => string,
	 *        	"form_action" => string
	 *        	),
	 *        	"item_menu" => array(
	 *        	0 => array("body"=>string,"title"=>string,"url"=>string, "class"=>string)
	 *        	),
	 *        	"select_item_action" => array(
	 *        	0 => array("value"=>string,"title"=>string)
	 *        	),
	 *        	"style" => array(
	 *        	"marked_row_class" => string,
	 *        	"count_box_class" => string,
	 *        	"pagging_box_class" => string,
	 *        	"actual_page_class" => string,
	 *        	"table_header_class" => string,
	 *        	"table_id" => string,
	 *        	"select_form_id" => string,
	 *        	"pagesize_form_id" => string,
	 *        	"list_footer_id" => string
	 *        	)
	 * @return string
	 */
	private static function makeListTableString($header, $data, $config){
		$s = "";
		$config["form_url"]["page"] = (isset($config["form_url"]["page"])) ? $config["form_url"]["page"] : "";
		$config["form_url"]["header_sort"] = (isset($config["form_url"]["header_sort"])) ? $config["form_url"]["header_sort"] : "";
		$config["form_url"]["form_action"] = (isset($config["form_url"]["form_action"])) ? $config["form_url"]["form_action"] : "";
		$config["style"]["pagesize_form_id"] = (isset($config["style"]["pagesize_form_id"])) ? $config["style"]["pagesize_form_id"] : "";
		$config["style"]["select_form_id"] = (isset($config["style"]["select_form_id"])) ? $config["style"]["select_form_id"] : "";
		$config["style"]["table_id"] = (isset($config["style"]["table_id"])) ? $config["style"]["table_id"] : "";
		$config["style"]["table_header_class"] = (isset($config["style"]["table_header_class"])) ? $config["style"]["table_header_class"] : "";
		$config["style"]["actual_page_class"] = (isset($config["style"]["actual_page_class"])) ? $config["style"]["actual_page_class"] : "";
		$config["style"]["pagging_box_class"] = (isset($config["style"]["pagging_box_class"])) ? $config["style"]["pagging_box_class"] : "";
		$config["style"]["count_box_class"] = (isset($config["style"]["count_box_class"])) ? $config["style"]["count_box_class"] : "";
		$config["style"]["marked_row_class"] = (isset($config["style"]["marked_row_class"])) ? $config["style"]["marked_row_class"] : "";
		$config["style"]["list_footer_id"] = (isset($config["style"]["list_footer_id"])) ? $config["style"]["list_footer_id"] : "";
		$config["item_menu"] = (isset($config["item_menu"])) ? $config["item_menu"] : array();
		$config["select_item_action"] = (isset($config["select_item_action"])) ? $config["select_item_action"] : array();
		// validation $config["item_menu"] and $config["select_item_action"]
		if($config["config"]["disable_menu"] === false && is_array($config["item_menu"]) && !empty($config["item_menu"])){
			for($i = 0; $i < count($config["item_menu"]); $i++){
				$config["item_menu"][$i]["body"] = (isset($config["item_menu"][$i]["body"])) ? $config["item_menu"][$i]["body"] : "";
				$config["item_menu"][$i]["title"] = (isset($config["item_menu"][$i]["title"])) ? $config["item_menu"][$i]["title"] : "";
				$config["item_menu"][$i]["url"] = (isset($config["item_menu"][$i]["url"])) ? $config["item_menu"][$i]["url"] : "";
				$config["item_menu"][$i]["class"] = (isset($config["item_menu"][$i]["class"])) ? $config["item_menu"][$i]["class"] : "";
			}
		}
		if($config["config"]["disable_select"] === false && is_array($config["select_item_action"]) && !empty($config["select_item_action"])){
			for($i = 0; $i < count($config["select_item_action"]); $i++){
				$config["select_item_action"][$i]["value"] = (isset($config["select_item_action"][$i]["value"])) ? $config["select_item_action"][$i]["value"] : "";
				$config["select_item_action"][$i]["title"] = (isset($config["select_item_action"][$i]["title"])) ? $config["select_item_action"][$i]["title"] : "";
			}
		}
		
		if(count($header) > 0 && count($data) > 0 && count($header) == count($data[0])){
			// pagesize box
			if($config["config"]["disable_set_pagesize"] === false){
				$s .= sprintf("<form method=\"post\" enctype=\"application/x-www-form-urlencoded\" action=\"%s\" %s>", $config["form_url"]["form_action"], !empty($config["style"]["pagesize_form_id"]) ? " id=\"" . $config["style"]["pagesize_form_id"] . "\"" : "");
				$s .= sprintf("<div><span>%s</span><select name=\"action_pagesize\">", Translate::get(Translator::LIST_PAGE_SIZE));
				foreach(System::$page_size as $v){
					$s .= sprintf("<option value=\"%d\"%s>%d</option>", $v, ($v == $config["config"]["actual_pagesize"] ? " selected=\"selected\"" : ""), $v);
				}
				$s .= sprintf("</select><input type=\"submit\" value=\"%s\"/><div class=\"cleaner_micro\">&nbsp;</div></div></form>", Translate::get(Translator::BTN_SEND));
			}
			// select action box
			if($config["config"]["disable_select"] === false){
				$s .= sprintf("<form method=\"post\" enctype=\"application/x-www-form-urlencoded\" action=\"%s\" %s>", $config["form_url"]["form_action"], !empty($config["style"]["select_form_id"]) ? " id=\"" . $config["style"]["select_form_id"] . "\"" : "");
				$s .= sprintf("<div><select name=\"action_select\">");
				foreach($config["select_item_action"] as $k => $v){
					$s .= sprintf("<option value=\"%s\">%s</option>", $v["value"], $v["title"]);
				}
				$s .= sprintf("</select><input type=\"submit\" value=\"%s\"/><div class=\"cleaner_micro\">&nbsp;</div></div>", Translate::get(Translator::BTN_SEND));
			}
			// data table
			$s .= sprintf("<table%s>", !empty($config["style"]["table_id"]) ? " id=\"" . $config["style"]["table_id"] . "\"" : "");
			$s .= sprintf("<thead><tr%s>", !empty($config["style"]["table_header_class"]) ? " class=\"" . $config["style"]["table_header_class"] . "\"" : "");
			if($config["config"]["disable_select"] === false){
				// add select column
				$s .= sprintf("<th class=\"%s\">&nbsp;</th>", "col_select");
			}
			// table header
			$j = 0;
			foreach($header as $k => $v){
				$sort_next_direction = ($k == $config["config"]["column"] ? ($config["config"]["direction"] == System::SORT_ASC ? System::SORT_DES : ($config["config"]["direction"] == System::SORT_DES ? System::SORT_ASC : "")) : System::SORT_ASC);
				$sort_show = ($k == $config["config"]["column"] ? ($config["config"]["direction"] == System::SORT_ASC ? System::SORT_ASC : ($config["config"]["direction"] == System::SORT_DES ? System::SORT_DES : "")) : "");
				switch($sort_show){
					case System::SORT_ASC:
						$sort_show = "&#x2206;";
						break;
					case System::SORT_DES:
						$sort_show = "&#x2207;";
						break;
				}
				$s .= sprintf("<th class=\"%s\"><a href=\"%s\">%s</a></th>", "col_" . $j, sprintf($config["form_url"]["header_sort"], $config["config"]["page"], $k, $sort_next_direction), $v . "&nbsp;" . $sort_show);
				// add empty columns for item_menu
				if($j == (count($header) - 1) && $config["config"]["disable_menu"] === false){
					foreach($config["item_menu"] as $k => $v){
						$s .= sprintf("<th class=\"%s\">&nbsp;</th>", $v["class"]);
					}
				}
				++$j;
			}
			$s .= sprintf("</tr></thead>");
			// table data rows
			for($i = 0; $i < count($data); $i++){
				$s .= sprintf("<tr%s>", ($i % 2 == 0 ? sprintf(" class=\"%s\"", (!empty($config["style"]["marked_row_class"]) ? $config["style"]["marked_row_class"] : "")) : ""));
				for($j = 0; $j < count($data[$i]); $j++){
					if($config["config"]["disable_select"] === false && $j == 0){
						$s .= sprintf("<td class=\"%s\"><input type=\"checkbox\" name=\"%s\" /></th>", "col_select", "row_" . $i . "_" . $data[$i][$j]);
					}
					$s .= sprintf("<td class=\"%s\">%s</td>", "col_" . $j, $data[$i][$j]);
					// add item_menu columns
					if($j == count($data[$i]) - 1 && $config["config"]["disable_menu"] === false){
						foreach($config["item_menu"] as $k => $v){
							$s .= sprintf("<td class=\"%s\"><a%shref=\"%s\">%s</a></td>", $v["class"], (!empty($v["title"]) ? " title=\"" . $v["title"] . "\"" : " "), sprintf($v["url"], $data[$i][0]), "<span>" . $v["body"] . "</span>");
						}
					}
				}
				$s .= sprintf("</tr>");
			}
			$s .= sprintf("</table>");
			if($config["config"]["disable_select"] === false){
				$s .= sprintf("</form>");
			}
			// pagging box
			$s .= sprintf("<div%s>", (!empty($config["style"]["list_footer_id"]) ? " id=\"" . $config["style"]["list_footer_id"] . "\"" : ""));
			$s .= sprintf("<div%s><span>%s: %d | %s: %d | %s: %d/%d</span></div>", (!empty($config["style"]["count_box_class"]) ? sprintf(" class=\"%s\"", $config["style"]["count_box_class"]) : ""), Translate::get(Translator::LIST_DISPLAYED_ROWS), count($data), Translate::get(Translator::LIST_FOUND_ROWS), $config["config"]["data_count"], Translate::get(Translator::LIST_PAGE), $config["config"]["page"], $config["config"]["sum_pages"]);
			// box with page numbers
			$s .= sprintf("<div%s>", (isset($config["style"]["pagging_box_class"]) ? sprintf(" class=\"%s\"", $config["style"]["pagging_box_class"]) : ""));
			if($config["config"]["sum_pages"] > 5){
				$sp = isset($config["form_url"]["page"]);
				$ap = isset($config["style"]["actual_page_class"]);
				$url = (!empty($sp) ? sprintf($config["form_url"]["page"], System::PAGE_MIN_PAGE, $config["config"]["column"], $config["config"]["direction"]) : "");
				$s .= sprintf("<a href=\"%s\"%s>%d</a>,", $url, (($ap && System::PAGE_MIN_PAGE == $config["config"]["page"]) ? sprintf(" class=\"%s\"", $config["style"]["actual_page_class"]) : ""), System::PAGE_MIN_PAGE);
				if($config["config"]["page"] - 1 > 2){
					$url = (isset($sp) ? sprintf($config["form_url"]["page"], $config["config"]["page"] - 1, $config["config"]["column"], $config["config"]["direction"]) : "");
					$s .= sprintf("...,<a href=\"%s\"%s>%d</a>,", $url, (($ap && $config["config"]["page"] - 1 == $config["config"]["page"]) ? sprintf(" class=\"%s\"", $config["style"]["actual_page_class"]) : ""), $config["config"]["page"] - 1);
				}else if($config["config"]["page"] - 1 > 1 && $config["config"]["page"] - 1 < 2){
					$url = (isset($sp) ? sprintf($config["form_url"]["page"], $config["config"]["page"] - 1, $config["config"]["column"], $config["config"]["direction"]) : "");
					$s .= sprintf("<a href=\"%s\"%s>%d</a>,", $url, (($ap && $config["config"]["page"] - 1 == $config["config"]["page"]) ? sprintf(" class=\"%s\"", $config["style"]["actual_page_class"]) : ""), $config["config"]["page"] - 1);
				}else if($config["config"]["page"] > 2){
					$url = (isset($sp) ? sprintf($config["form_url"]["page"], $config["config"]["page"] - 1, $config["config"]["column"], $config["config"]["direction"]) : "");
					$s .= sprintf("<a href=\"%s\"%s>%d</a>,", $url, (($ap && $config["config"]["page"] - 1 == $config["config"]["page"]) ? sprintf(" class=\"%s\"", $config["style"]["actual_page_class"]) : ""), $config["config"]["page"] - 1);
				}
				if($config["config"]["page"] + 1 < $config["config"]["sum_pages"] && $config["config"]["page"] > System::PAGE_MIN_PAGE){
					$url = (isset($sp) ? sprintf($config["form_url"]["page"], $config["config"]["page"], $config["config"]["column"], $config["config"]["direction"]) : "");
					$s .= sprintf("<a href=\"%s\"%s>%d</a>,", $url, (($ap && $config["config"]["page"] == $config["config"]["page"]) ? sprintf(" class=\"%s\"", $config["style"]["actual_page_class"]) : ""), $config["config"]["page"]);
				}
				if($config["config"]["page"] + 2 < $config["config"]["sum_pages"]){
					$url = (isset($sp) ? sprintf($config["form_url"]["page"], $config["config"]["page"] + 1, $config["config"]["column"], $config["config"]["direction"]) : "");
					$s .= sprintf("<a href=\"%s\"%s>%d</a>,...,", $url, (($ap && $config["config"]["page"] + 1 == $config["config"]["page"]) ? sprintf(" class=\"%s\"", $config["style"]["actual_page_class"]) : ""), $config["config"]["page"] + 1);
				}else if($config["config"]["page"] + 1 < $config["config"]["sum_pages"]){
					$url = (isset($sp) ? sprintf($config["form_url"]["page"], $config["config"]["page"] + 1, $config["config"]["column"], $config["config"]["direction"]) : "");
					$s .= sprintf("<a href=\"%s\"%s>%d</a>,", $url, (($ap && $config["config"]["page"] + 1 == $config["config"]["page"]) ? sprintf(" class=\"%s\"", $config["style"]["actual_page_class"]) : ""), $config["config"]["page"] + 1);
				}else if($config["config"]["page"] < $config["config"]["sum_pages"]){
					$url = (isset($sp) ? sprintf($config["form_url"]["page"], $config["config"]["page"], $config["config"]["column"], $config["config"]["direction"]) : "");
					$s .= sprintf("<a href=\"%s\"%s>%d</a>,", $url, (($ap && $config["config"]["page"] == $config["config"]["page"]) ? sprintf(" class=\"%s\"", $config["style"]["actual_page_class"]) : ""), $config["config"]["page"]);
				}
				$url = (isset($sp) ? sprintf($config["form_url"]["page"], $config["config"]["sum_pages"], $config["config"]["column"], $config["config"]["direction"]) : "");
				$s .= sprintf("<a href=\"%s\"%s>%d</a>", $url, (($ap && $config["config"]["sum_pages"] == $config["config"]["page"]) ? sprintf(" class=\"%s\"", $config["style"]["actual_page_class"]) : ""), $config["config"]["sum_pages"]);
			}else{
				for($i = 1; $i <= $config["config"]["sum_pages"]; $i++){
					$url = (!empty($config["form_url"]["page"]) ? sprintf($config["form_url"]["page"], $i, $config["config"]["column"], $config["config"]["direction"]) : "");
					$s .= sprintf("<a href=\"%s\"%s>%d</a>%s", $url, ((!empty($config["style"]["actual_page_class"]) && $i == $config["config"]["page"]) ? sprintf(" class=\"%s\"", $config["style"]["actual_page_class"]) : ""), $i, ($i < $config["config"]["sum_pages"] ? "," : ""));
				}
			}
			$s .= sprintf("</div>");
			$s .= sprintf("</div>");
		}
		return $s;
	}
}
?>