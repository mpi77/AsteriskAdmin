<?php
/**
 * Line view.
 *
 * @version 1.12
 * @author MPI
 * */
class LineView extends View{

	public function __construct(Model $model, $args){
		parent::__construct($model, $args);
	}

	public function getName(){
		return get_class($this);
	}

	/**
	 * Show user line list.
	 */
	public function lst(){
		$args = $this->getArgs();
		$uid = $_SESSION["user"]["uid"];
		$base_url = substr($_SERVER["REQUEST_URI"], 0, strrpos($_SERVER["REQUEST_URI"], "/") + 1);
		$header = array(
				sprintf("%s", Translate::get(Translator::LINE_LIST_HEADER_ID)),
				sprintf("%s", Translate::get(Translator::LINE_LIST_HEADER_LINE)),
				sprintf("%s", Translate::get(Translator::LINE_LIST_HEADER_NAME)),
				sprintf("%s", Translate::get(Translator::LINE_LIST_HEADER_PSTN))
		);
		
		$config = array(
				"config" => array(
						"page" => (isset($args["GET"]["page"]) ? $args["GET"]["page"] : System::PAGE_ACTUAL_DEFAULT),
						"column" => (isset($args["GET"]["column"]) ? $args["GET"]["column"] : System::SORT_DEFAULT_COLUMN),
						"direction" => (isset($args["GET"]["direction"]) ? $args["GET"]["direction"] : System::SORT_DES),
						"actual_pagesize" => (isset($_SESSION["page_size"]) ? $_SESSION["page_size"] : System::PAGE_SIZE_DEFAULT),
						"data_count" => $this->getModel()->getCountUserLinesList($uid),
						"disable_menu" => false,
						"disable_select" => true,
						"disable_pagging" => false,
						"disable_set_pagesize" => false
				),
				"form_url" => array(
						"page" => $base_url . "-%d-%d-%s",
						"header_sort" => $base_url . "-%d-%d-%s",
						"form_action" => $base_url
				),
				"item_menu" => array(
						0 => array(
								"body" => "E",
								"title" => "editovat",
								"url" => "line/edit/%d/",
								"class" => "edit"
						),
						1 => array(
								"body" => "D",
								"title" => "smazat",
								"url" => "line/delete/%d/",
								"class" => "delete"
						)
				),
				"select_item_action" => array(
						0 => array(
								"value" => "D",
								"title" => "smazat"
						)
				),
				"style" => array(
						"marked_row_class" => "marked",
						"count_box_class" => "count_box",
						"pagging_box_class" => "pagging_box",
						"actual_page_class" => "actual_page",
						"table_header_class" => "head",
						"table_id" => "line_lst",
						"select_form_id" => "",
						"pagesize_form_id" => "pagesize_box",
						"list_footer_id" => ""
				)
		);
		$data = $this->getModel()->getUserLinesList($uid, $config["config"]["page"], $config["config"]["actual_pagesize"], $config["config"]["column"], $config["config"]["direction"], $config["config"]["disable_pagging"]);
		$out = Paginator::generatePage($header, $data, $config);
		include 'gui/template/LineTemplate.php';
	}

	/**
	 * Edit line form.
	 */
	public function edit(){
		$args = $this->getArgs();
		$out = $this->getModel()->getData($args["GET"]["id"]);
		$vm = $this->getModel()->getVoicemailData($out[2], $out[7]);
		$free_numbers = $this->getModel()->getPstnUnregistredList();
		$registered_pstn = $this->getModel()->getLineRegisteredPstn($out[2]);
		$has_pstn = true;
		if($registered_pstn == Database::EMPTY_RESULT || !is_array($registered_pstn)){
			$has_pstn = false;
			$registered_pstn = array(
					0,
					0
			);
		}
		$pstn = "";
		foreach($free_numbers as $v){
			$pstn .= sprintf("<option value=\"%d\"%s>%s</option>", $v[0], ($v[0] == $registered_pstn[0] ? " selected=\"selected\"" : ""), $v[1]);
		}
		include 'gui/template/LineTemplate.php';
	}

	/**
	 * Create new line.
	 */
	public function create(){
		$args = $this->getArgs();
		include 'gui/template/LineTemplate.php';
	}

	/**
	 * Remove line from dialplan.
	 */
	public function delete(){
		$args = $this->getArgs();
		$out = $this->getModel()->getData($args["GET"]["id"]);
		include 'gui/template/LineTemplate.php';
	}

	public function outputJson(){
	}

	public function outputHtml(){
		if(System::isCallable($this, $this->getActionName()) === true){
			$this->{$this->getActionName()}();
		}else{
			throw new WarningException(WarningException::WARNING_ACTION_IS_NOT_CALLABLE);
		}
	}
}
?>