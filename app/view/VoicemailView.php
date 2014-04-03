<?php
/**
 * Voicemail view.
 *
 * @version 1.2
 * @author MPI
 * */
class VoicemailView extends View{

	public function __construct(Model $model, $args){
		parent::__construct($model, $args);
	}

	public function getName(){
		return get_class($this);
	}

	/**
	 * Show voicemail list.
	 */
	public function lst(){
		$uid = $_SESSION["user"]["uid"];
		$args = $this->getArgs();
		$base_url = substr($_SERVER["REQUEST_URI"], 0, strrpos($_SERVER["REQUEST_URI"], "/") + 1);
		$header = array(
				sprintf("%s", Translate::get(Translator::VOICEMAIL_LIST_HEADER_ID)),
				sprintf("%s", Translate::get(Translator::VOICEMAIL_LIST_HEADER_TIME)),
				sprintf("%s", Translate::get(Translator::VOICEMAIL_LIST_HEADER_FROM)),
				sprintf("%s", Translate::get(Translator::VOICEMAIL_LIST_HEADER_LENGTH))
		);
		$line_id = "0";
		$out2 = "";
		$data = null;
		$config = null;
		
		$user_lines = $this->getModel()->getUserLines($_SESSION["user"]["uid"]);
		if($user_lines != Database::EMPTY_RESULT && is_array($user_lines)){
			if($_SERVER["REQUEST_METHOD"] == "GET" && !isset($args["GET"]["line"])){
				$line_id = $user_lines[0][0];
			}
			$line_id = (!empty($args["POST"]["select_voicemail_line"])) ? $args["POST"]["select_voicemail_line"] : (!empty($args["GET"]["line"]) ? $args["GET"]["line"] : ($line_id > 0 ? $line_id : "0"));
			$line = $this->getModel()->getLineData($line_id);
			$data = $this->getModel()->getVoicemailMessages($line[7], $line[2]);
			$cnt_data = count($data);
			$config = array(
					"config" => array(
							"page" => (isset($args["GET"]["page"]) ? $args["GET"]["page"] : System::PAGE_ACTUAL_DEFAULT),
							"column" => (isset($args["GET"]["column"]) ? $args["GET"]["column"] : System::SORT_DEFAULT_COLUMN),
							"direction" => (isset($args["GET"]["direction"]) ? $args["GET"]["direction"] : System::SORT_DES),
							"actual_pagesize" => (isset($_SESSION["page_size"]) ? $_SESSION["page_size"] : System::PAGE_SIZE_DEFAULT),
							"data_count" => $cnt_data,
							"disable_menu" => false,
							"disable_select" => true,
							"disable_pagging" => false,
							"disable_set_pagesize" => false
					),
					"form_url" => array(
							"page" => $base_url . "-%d-%d-%s" . "?line=" . $line_id,
							"header_sort" => $base_url . "-%d-%d-%s" . "?line=" . $line_id,
							"form_action" => $base_url . "?line=" . $line_id
					),
					"item_menu" => array(
							0 => array(
									"body" => "G",
									"title" => "stáhnout",
									"url" => "voicemail/download/%d/?m=" . $line[0],
									"class" => "download"
							),
							1 => array(
									"body" => "D",
									"title" => "smazat",
									"url" => "voicemail/delete/%d/?m=" . $line[0],
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
							"table_id" => "voicemail_lst",
							"select_form_id" => "",
							"pagesize_form_id" => "pagesize_box",
							"list_footer_id" => ""
					)
			);
			foreach($user_lines as $v){
				$out2 .= sprintf("<option value=\"%d\"%s>%s</option>", $v[0], ($v[0] == $line_id ? " selected=\"selected\"" : ""), $v[1]);
			}
		}else{
			$data = null;
		}
		$out = Paginator::generatePageWithUnsortedData($header, $data, $config);
		include 'gui/template/VoicemailTemplate.php';
	}

	/**
	 * Download voicemail message.
	 */
	public function download(){
	}

	/**
	 * Remove voicemail message.
	 */
	public function delete(){
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