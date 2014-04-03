<?php
/**
 * Extension view.
 *
 * @version 1.13
 * @author MPI
 * */
class ExtensionView extends View{

	public function __construct(Model $model, $args){
		parent::__construct($model, $args);
	}

	public function getName(){
		return get_class($this);
	}

	/**
	 * Show extension list.
	 */
	public function lst(){
		$args = $this->getArgs();
		$uid = $_SESSION["user"]["uid"];
		$base_url = substr($_SERVER["REQUEST_URI"], 0, strrpos($_SERVER["REQUEST_URI"], "/") + 1);
		$header = array(
				sprintf("%s", Translate::get(Translator::EXT_LIST_HEADER_ID)),
				sprintf("%s", Translate::get(Translator::EXT_LIST_HEADER_CONTEXT)),
				sprintf("%s", Translate::get(Translator::EXT_LIST_HEADER_LINE)),
				sprintf("%s", Translate::get(Translator::EXT_LIST_HEADER_PRIORITY)),
				sprintf("%s", Translate::get(Translator::EXT_LIST_HEADER_APP)),
				sprintf("%s", Translate::get(Translator::EXT_LIST_HEADER_APPDATA))
		);
		
		$config = array(
				"config" => array(
						"page" => (isset($args["GET"]["page"]) ? $args["GET"]["page"] : System::PAGE_ACTUAL_DEFAULT),
						"column" => (isset($args["GET"]["column"]) ? $args["GET"]["column"] : System::SORT_DEFAULT_COLUMN),
						"direction" => (isset($args["GET"]["direction"]) ? $args["GET"]["direction"] : System::SORT_DES),
						"actual_pagesize" => (isset($_SESSION["page_size"]) ? $_SESSION["page_size"] : System::PAGE_SIZE_DEFAULT),
						"data_count" => $this->getModel()->getCountExtensionList(),
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
								"url" => "extension/edit/%d/",
								"class" => "edit"
						),
						1 => array(
								"body" => "D",
								"title" => "smazat",
								"url" => "extension/delete/%d/",
								"class" => "delete"
						)
				),
				"select_item_action" => array(),
				"style" => array(
						"marked_row_class" => "marked",
						"count_box_class" => "count_box",
						"pagging_box_class" => "pagging_box",
						"actual_page_class" => "actual_page",
						"table_header_class" => "head",
						"table_id" => "ext_lst",
						"select_form_id" => "",
						"pagesize_form_id" => "pagesize_box",
						"list_footer_id" => ""
				)
		);
		$data = $this->getModel()->getExtensionList($config["config"]["page"], $config["config"]["actual_pagesize"], $config["config"]["column"], $config["config"]["direction"], $config["config"]["disable_pagging"]);
		$out = Paginator::generatePage($header, $data, $config);
		include 'gui/template/ExtensionTemplate.php';
	}

	/**
	 * Edit extension form.
	 */
	public function edit(){
		$args = $this->getArgs();
		$out = $this->getModel()->getData($args["GET"]["id"]);
		include 'gui/template/ExtensionTemplate.php';
	}

	/**
	 * Create new extension.
	 */
	public function create(){
		$args = $this->getArgs();
		include 'gui/template/ExtensionTemplate.php';
	}

	/**
	 * Remove extension.
	 */
	public function delete(){
		$args = $this->getArgs();
		$out = $this->getModel()->getData($args["GET"]["id"]);
		include 'gui/template/ExtensionTemplate.php';
	}

	/**
	 * Show PSTN extension list.
	 */
	public function pstnlst(){
		$args = $this->getArgs();
		$uid = $_SESSION["user"]["uid"];
		$base_url = substr($_SERVER["REQUEST_URI"], 0, strrpos($_SERVER["REQUEST_URI"], "/") + 1);
		$header = array(
				sprintf("%s", Translate::get(Translator::PSTN_LIST_HEADER_ID)),
				sprintf("%s", Translate::get(Translator::PSTN_LIST_HEADER_LINE)),
				sprintf("%s", Translate::get(Translator::PSTN_LIST_HEADER_ASSIGNED))
		);
		
		$config = array(
				"config" => array(
						"page" => (isset($args["GET"]["page"]) ? $args["GET"]["page"] : System::PAGE_ACTUAL_DEFAULT),
						"column" => (isset($args["GET"]["column"]) ? $args["GET"]["column"] : System::SORT_DEFAULT_COLUMN),
						"direction" => (isset($args["GET"]["direction"]) ? $args["GET"]["direction"] : System::SORT_DES),
						"actual_pagesize" => (isset($_SESSION["page_size"]) ? $_SESSION["page_size"] : System::PAGE_SIZE_DEFAULT),
						"data_count" => $this->getModel()->getCountPstnNumList(),
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
								"body" => "C",
								"title" => "zrušit přidělení",
								"url" => "extension/pstncancel/%d/",
								"class" => "cancel"
						),
						1 => array(
								"body" => "D",
								"title" => "smazat",
								"url" => "extension/pstndelete/%d/",
								"class" => "delete"
						)
				),
				"select_item_action" => array(),
				"style" => array(
						"marked_row_class" => "marked",
						"count_box_class" => "count_box",
						"pagging_box_class" => "pagging_box",
						"actual_page_class" => "actual_page",
						"table_header_class" => "head",
						"table_id" => "pstn_lst",
						"select_form_id" => "",
						"pagesize_form_id" => "pagesize_box",
						"list_footer_id" => ""
				)
		);
		$data = $this->getModel()->getPstnNumList($config["config"]["page"], $config["config"]["actual_pagesize"], $config["config"]["column"], $config["config"]["direction"], $config["config"]["disable_pagging"]);
		$out = Paginator::generatePage($header, $data, $config);
		include 'gui/template/ExtensionTemplate.php';
	}

	/**
	 * Cancel PSTN.
	 */
	public function pstncancel(){
	}

	/**
	 * Create PSTN line.
	 */
	public function pstncreate(){
		$args = $this->getArgs();
		include 'gui/template/ExtensionTemplate.php';
	}

	/**
	 * Remove PSTN.
	 */
	public function pstndelete(){
		$args = $this->getArgs();
		$out = $this->getModel()->getData($args["GET"]["id"]);
		include 'gui/template/ExtensionTemplate.php';
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