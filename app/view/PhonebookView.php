<?php
/**
 * Phonebook view.
 *
 * @version 1.2
 * @author MPI
 * */
class PhonebookView extends View{

	public function __construct(Model $model, $args){
		parent::__construct($model, $args);
	}

	public function getName(){
		return get_class($this);
	}

	/**
	 * Show list of available lines.
	 */
	public function lst(){
		$args = $this->getArgs();
		$base_url = substr($_SERVER["REQUEST_URI"], 0, strrpos($_SERVER["REQUEST_URI"], "/") + 1);
		$header = array(
				sprintf("%s", Translate::get(Translator::PHONEBOOK_LIST_HEADER_LINE)),
				sprintf("%s", Translate::get(Translator::PHONEBOOK_LIST_HEADER_NAME)),
				sprintf("%s", Translate::get(Translator::PHONEBOOK_LIST_HEADER_PSTN)),
				sprintf("%s", Translate::get(Translator::PHONEBOOK_LIST_HEADER_OWNER))
		);
		
		$config = array(
				"config" => array(
						"page" => (isset($args["GET"]["page"]) ? $args["GET"]["page"] : System::PAGE_ACTUAL_DEFAULT),
						"column" => (isset($args["GET"]["column"]) ? $args["GET"]["column"] : System::SORT_DEFAULT_COLUMN),
						"direction" => (isset($args["GET"]["direction"]) ? $args["GET"]["direction"] : System::SORT_DES),
						"actual_pagesize" => (isset($_SESSION["page_size"]) ? $_SESSION["page_size"] : System::PAGE_SIZE_DEFAULT),
						"data_count" => $this->getModel()->getCountPhonebookList(),
						"disable_menu" => true,
						"disable_select" => true,
						"disable_pagging" => false,
						"disable_set_pagesize" => false
				),
				"form_url" => array(
						"page" => $base_url . "-%d-%d-%s" . "?line=" . $line_id,
						"header_sort" => $base_url . "-%d-%d-%s" . "?line=" . $line_id,
						"form_action" => $base_url . "?line=" . $line_id
				),
				"item_menu" => array(),
				"select_item_action" => array(),
				"style" => array(
						"marked_row_class" => "marked",
						"count_box_class" => "count_box",
						"pagging_box_class" => "pagging_box",
						"actual_page_class" => "actual_page",
						"table_header_class" => "head",
						"table_id" => "phonebook_lst",
						"select_form_id" => "",
						"pagesize_form_id" => "pagesize_box",
						"list_footer_id" => ""
				)
		);
		$data = $this->getModel()->getPhonebookList($config["config"]["page"], $config["config"]["actual_pagesize"], $config["config"]["column"], $config["config"]["direction"], $config["config"]["disable_pagging"]);
		$out = Paginator::generatePage($header, $data, $config);
		include 'gui/template/PhonebookTemplate.php';
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