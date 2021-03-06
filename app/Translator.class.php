<?php

/**
 * Root translator object.
 * 
 * @version 1.3
 * @author MPI
 * */
abstract class Translator{
	const DEFAULT_INVALID_KEY = ""; // default value for invalid key
	
	const LANG_EN = 1;
	const LANG_CZ = 2;
	
	private static $language = array(
			self::LANG_EN => "English",
			self::LANG_CZ => "Čeština"
	);
	
	public function __construct(){
	}
	
	public static function getLanguage($lang_id){
		return (empty($lang_id) ? self::$language : (array_key_exists($lang_id, self::$language) ? self::$language[$key] : self::$language[self::LANG_EN]));
	}

	/**
	 * Get string or string pattern from object.
	 *
	 * @all translators must contain a get method
	 */
	public abstract function get($key);

	/**
	 * Get name of this class.
	 *
	 * @all views must contain a getName method
	 */
	public abstract function getName();
	
	/*
	 * Common translation keys follow
	 * 
	 * */
	const SITE_TITLE = 1;
	const SITE_TITLE_HIDDEN = 2;
	
	const FAILURE_UNKNOWN = 100;
	const FAILURE_MISSING_CONFIG_DB = 101;
	const FAILURE_UNABLE_CONNECT_DB = 102;
	const FAILURE_UNABLE_SET_DB_CHARSET = 103;
	const FAILURE_UNABLE_SAVE_WARNING = 104;
	
	const WARNING_UNKNOWN = 200;
	const WARNING_CLASS_NOT_FOUND = 201;
	const WARNING_ACTION_IS_NOT_CALLABLE = 202;
	const WARNING_INVALID_ROUTE = 203;
	const WARNING_INVALID_SQL_SELECT = 204;
	const WARNING_INVALID_SQL_ACTION = 205;
	const WARNING_UNABLE_VERIFY_RESULT = 206;
	const WARNING_UNABLE_COMPLETE_TRANSACTION = 207;
	
	const NOTICE_UNKNOWN = 300;
	const NOTICE_LOGIN_FAILED = 301;
	const NOTICE_INVALID_PARAMETERS = 302;
	const NOTICE_PERMISSION_DENIED = 303;
	const NOTICE_PASSWORD_INVALID_FORMAT = 304;
	const NOTICE_INPUT_INVALID_FORMAT = 305;
	const NOTICE_SUCCESSFULLY_SAVED = 306;
	const NOTICE_LOGIN_REQUIRED = 307;
	const NOTICE_RENEW_EMAIL_ERROR = 308;
	const NOTICE_RENEW_EMAIL_SENDED = 309;
	const NOTICE_INVALID_TOKEN = 310;
	const NOTICE_PASSWORD_CHANGED = 311;
	const NOTICE_USER_NOT_FOUND = 312;
	const NOTICE_USER_CREATE_EMAIL_ERROR = 313;
	const NOTICE_EMAIL_USED_ENTER_ANOTHER = 314;
	const NOTICE_USER_CREATE_EMAIL_SENDED = 315;
	const NOTICE_USER_ACTIVATED = 316;
	const NOTICE_LINE_IS_USED = 317;
	const NOTICE_NOTHING_TO_DISPLAY = 318;
	const NOTICE_FILE_IS_NOT_DELETABLE = 319;
	const NOTICE_PSTN_LINE_IS_USED = 320;
	const NOTICE_PSTN_NOT_FOUND = 321;
	const NOTICE_PSTN_LINE_IS_ASSIGNED = 322;
	const NOTICE_PSTN_LINE_IS_FREE = 323;
	const NOTICE_PSTN_ONE_LINE_ONE_PSTN = 324;
	
	const LOG_USER_LOGIN = 500;
	const LOG_USER_LOGOUT = 501;
	const LOG_USER_ACTIVATION = 502;
	const LOG_USER_PASSWORD_CHANGED = 503;
	const LOG_USER_ACCOUNT_DATA_CHANGED = 504;
	const LOG_USER_REQUEST = 505;
	const LOG_EXTENSION_ITEM_CREATE = 506;
	const LOG_EXTENSION_ITEM_CHANGED = 507;
	const LOG_EXTENSION_ITEM_DELETED = 508;
	const LOG_EXTENSION_PSTN_CREATE = 509;
	const LOG_EXTENSION_PSTN_CANCEL = 510;
	const LOG_EXTENSION_PSTN_DELETE = 511;
	const LOG_LINE_SECRET_CHANGED = 512;
	const LOG_LINE_TITLE_CHANGED = 513;
	const LOG_LINE_PSTN_ASSIGNED = 514;
	const LOG_LINE_PSTN_CANCEL = 515;
	const LOG_LINE_PARAMETERS_CHANGED = 516;
	const LOG_LINE_CREATE = 517;
	const LOG_LINE_DELETE = 518;
	const LOG_VOICEMAIL_DELETE_MSG = 519;

	const PAGE_NAME_CDR_LIST = 1000;
	const PAGE_NAME_VOICEMAIL_LIST = 1001;
	const PAGE_NAME_USER_LOG_LIST = 1002;
	const PAGE_NAME_PHONEBOOK_LIST = 1003;
	const PAGE_NAME_LINE_LIST = 1004;
	const PAGE_NAME_USER_LIST = 1005;
	const PAGE_NAME_EXTENSION_LIST = 1006;
	const PAGE_NAME_PSTN_LIST = 1007;
	const PAGE_NAME_USER_CREATE = 1008;
	const PAGE_NAME_USER_RENEW = 1009;
	const PAGE_NAME_USER_SET_PASSWORD = 1010;
	const PAGE_NAME_USER_LOGIN = 1011;
	const PAGE_NAME_USER_EDIT = 1012;
	const PAGE_NAME_USER_REQUEST = 1013;
	const PAGE_NAME_EXTENSION_CREATE = 1014;
	const PAGE_NAME_EXTENSION_EDIT = 1015;
	const PAGE_NAME_EXTENSION_DELETE = 1016;
	const PAGE_NAME_PSTN_CREATE = 1017;
	const PAGE_NAME_PSTN_DELETE = 1018;
	const PAGE_NAME_LINE_ADD = 1019;
	const PAGE_NAME_LINE_EDIT = 1020;
	const PAGE_NAME_LINE_DELETE = 1021;
	const PAGE_NAME_INDEX = 1022;
	const PAGE_NAME_INDEX_UNLOG = 1023;
	
	const MENU_HOME = 1100;
	const MENU_LOGIN = 1101;
	const MENU_LOGOUT = 1102;
	const MENU_ADD = 1103;
	const MENU_LIST = 1104;
	const MENU_PBX = 1105;
	const MENU_USERS = 1106;
	const MENU_EXTENSIONS = 1107;
	const MENU_PSTN = 1108;
	const MENU_LINE = 1109;
	const MENU_PHONEBOOK = 1110;
	const MENU_VOICEMAIL = 1111;
	const MENU_CDR = 1112;
	const MENU_USER_MY_ACCOUNT = 1113;
	const MENU_USER_ACCESS_DATA = 1114;
	const MENU_USER_LOG = 1115;
	
	const CDR_LIST_HEADER_TIME = 1120;
	const CDR_LIST_HEADER_DIRECTION = 1121;
	const CDR_LIST_HEADER_FROM = 1122;
	const CDR_LIST_HEADER_TO = 1123;
	const CDR_LIST_HEADER_DURATION = 1124;
	const CDR_LIST_HEADER_BILLSEC = 1125;
	const CDR_LIST_HEADER_DISPOSITION = 1126;
	const CDR_LIST_INCOMING = 1127;
	const CDR_LIST_OUTGOING = 1128;
	const CDR_DISPOSITION_NO_ANSWER = 1129;
	const CDR_DISPOSITION_FAILED = 1130;
	const CDR_DISPOSITION_BUSY = 1131;
	const CDR_DISPOSITION_ANSWERED = 1132;
	const VOICEMAIL_LIST_HEADER_ID = 1133;
	const VOICEMAIL_LIST_HEADER_TIME = 1134;
	const VOICEMAIL_LIST_HEADER_FROM = 1135;
	const VOICEMAIL_LIST_HEADER_LENGTH = 1136;
	const USER_LOG_LIST_HEADER_TIME = 1137;
	const USER_LOG_LIST_HEADER_VALUE = 1138;
	const USER_LIST_HEADER_ID = 1139;
	const USER_LIST_HEADER_EMAIL = 1140;
	const USER_LIST_HEADER_FNAME = 1141;
	const USER_LIST_HEADER_LNAME = 1142;
	const USER_LIST_HEADER_PHONE = 1143;
	const EXT_LIST_HEADER_ID = 1144;
	const EXT_LIST_HEADER_CONTEXT = 1145;
	const EXT_LIST_HEADER_LINE = 1146;
	const EXT_LIST_HEADER_PRIORITY = 1147;
	const EXT_LIST_HEADER_APP = 1148;
	const EXT_LIST_HEADER_APPDATA = 1149;
	const PSTN_LIST_HEADER_ID = 1150;
	const PSTN_LIST_HEADER_LINE = 1151;
	const PSTN_LIST_HEADER_ASSIGNED = 1152;
	const PHONEBOOK_LIST_HEADER_LINE = 1153;
	const PHONEBOOK_LIST_HEADER_NAME = 1154;
	const PHONEBOOK_LIST_HEADER_PSTN = 1155;
	const PHONEBOOK_LIST_HEADER_OWNER = 1156;
	const LINE_LIST_HEADER_ID = 1157;
	const LINE_LIST_HEADER_LINE = 1158;
	const LINE_LIST_HEADER_NAME = 1159;
	const LINE_LIST_HEADER_PSTN = 1160;
	const LIST_PAGE_SIZE = 1161;
	const LIST_DISPLAYED_ROWS = 1162;
	const LIST_FOUND_ROWS = 1163;
	const LIST_PAGE = 1164;
	const BTN_SEND = 1165;
	const BTN_OK = 1166;
	const BTN_CONFIRM = 1167;
	const BTN_EDIT = 1168;
	const BTN_DELETE = 1169;
	const BTN_SAVE = 1170;
	const BTN_BACK = 1171;
	const BTN_XB = 1172;
	const USER_EMAIL = 1173;
	const USER_FIRST_NAME = 1174;
	const USER_LAST_NAME = 1175;
	const USER_PHONE = 1176;
	const USER_OLD_PASSWORD = 1177;
	const USER_NEW_PASSWORD = 1178;
	const USER_NEW_PASSWORD_AGAIN = 1179;
	const USER_LAST_LOGIN = 1180;
	const USER_ACCOUNT_TYPE = 1181;
	const USER_LANG = 1280;
	const USER_HELP_EMAIL = 1182;
	const USER_HELP_FIRST_NAME = 1183;
	const USER_HELP_LAST_NAME = 1184;
	const USER_HELP_PHONE = 1185;
	const USER_HELP_OLD_PASSWORD = 1186;
	const USER_HELP_NEW_PASSWORD = 1187;
	const USER_HELP_NEW_PASSWORD_AGAIN = 1188;
	const USER_TITLE_EMAIL = 1189;
	const USER_TITLE_FIRST_NAME = 1190;
	const USER_TITLE_LAST_NAME = 1191;
	const USER_TITLE_PHONE = 1192;
	const USER_TITLE_OLD_PASSWORD = 1193;
	const USER_TITLE_NEW_PASSWORD = 1194;
	const USER_TITLE_NEW_PASSWORD_AGAIN = 1195;
	const USER_PASSWORD = 1196;
	const USER_VALID_UNTIL = 1197;
	const USER_LOST_PASSWORD = 1198;
	const REQUIRED = 1199;
	const EXTENSION_ID = 1200;
	const EXTENSION_CONTEXT = 1201;
	const EXTENSION_LINE = 1202;
	const EXTENSION_PRIORITY = 1203;
	const EXTENSION_APP = 1204;
	const EXTENSION_APPDATA = 1205;
	const EXTENSION_HELP_CONTEXT = 1206;
	const EXTENSION_HELP_LINE = 1207;
	const EXTENSION_HELP_PRIORITY = 1208;
	const EXTENSION_HELP_APP = 1209;
	const EXTENSION_HELP_APPDATA = 1210;
	const EXTENSION_TITLE_CONTEXT = 1211;
	const EXTENSION_TITLE_LINE = 1212;
	const EXTENSION_TITLE_PRIORITY = 1213;
	const EXTENSION_TITLE_APP = 1214;
	const EXTENSION_TITLE_APPDATA = 1215;
	const EXTENSION_CONFIRM_DELETE = 1216;
	const PSTN_LINE = 1217;
	const PSTN_HELP_LINE = 1218;
	const PSTN_TITLE_LINE = 1219;
	const PSTN_CONFIRM_DELETE = 1220;
	const LINE_TAB_1 = 1221;
	const LINE_TAB_2 = 1222;
	const LINE_TAB_3 = 1223;
	const LINE_TAB_4 = 1224;
	const LINE_TAB_5 = 1225;
	const LINE_NUMBER = 1226;
	const LINE_SECRET = 1227;
	const LINE_SECRET_AGAIN = 1228;
	const LINE_TITLE = 1247;
	const LINE_VOICEMAIL = 1229;
	const LINE_VOICEMAIL_PASSWORD = 1230;
	const LINE_CALL_FWD = 1231;
	const LINE_NAT = 1232;
	const LINE_WATCH_IP = 1233;
	const LINE_IP = 1234;
	const LINE_SM = 1235;
	const LINE_PSTN_AVAILABLE = 1236;
	const LINE_PSTN_ASSIGNED = 1237;
	const BTN_ASSIGN = 1238;
	const BTN_CANCEL = 1239;
	const LINE_CONNECTED_DEVICE = 1240;
	const LINE_CONNECTED_DEVICE_IP = 1241;
	const LINE_CONNECTED_DEVICE_PORT = 1242;
	const LINE_CONNECTED_DEVICE_UA = 1243;
	const LINE_CONNECTED_DEVICE_LAT = 1244;
	const LINE_CONNECTED_DEVICE_REGSEC = 1245;
	const LINE_CONNECTED_DEVICE_FULLCONTACT = 1246;
	const LINE_HELP_NUMBER = 1248;
	const LINE_HELP_SECRET = 1249;
	const LINE_HELP_SECRET_AGAIN = 1250;
	const LINE_HELP_TITLE = 1251;
	const LINE_TITLE_NUMBER = 1252;
	const LINE_TITLE_SECRET = 1253;
	const LINE_TITLE_SECRET_AGAIN = 1254;
	const LINE_TITLE_TITLE = 1255;
	const LINE_TITLE_IP = 1256;
	const LINE_TITLE_SM = 1257;
	const LINE_TITLE_VOICEMAIL_PASSWORD = 1258;
	const LINE_CONFIRM_DELETE = 1259;
	const INDEX_LOGIN_FIRST = 1260;
	const INDEX_PBX_INFO = 1261;
	const INDEX_PBX_EXTERNAL_NAME = 1262;
	const INDEX_PBX_INTERNAL_NAME = 1263;
	const INDEX_PBX_PORT = 1264;
	const INDEX_PBX_PROTOCOLS = 1265;
	const INDEX_PBX_SPECIAL_LINE = 1266;
	const INDEX_PBX_ECHO = 1267;
	const INDEX_PBX_TIME = 1268;
	const INDEX_PBX_VOICEMAIL = 1269;
	const INDEX_PBX_WEATHER = 1270;
	const INDEX_PBX_HELP_EXTERNAL_NAME = 1271;
	const INDEX_PBX_HELP_INTERNAL_NAME = 1272;
	const INDEX_PBX_HELP_PORT = 1273;
	const INDEX_PBX_HELP_PROTOCOLS = 1274;
	const INDEX_PBX_HELP_ECHO = 1275;
	const INDEX_PBX_HELP_TIME = 1276;
	const INDEX_PBX_HELP_VOICEMAIL = 1277;
	const INDEX_PBX_HELP_WEATHER = 1278;
	const CHOOSE_LINE = 1279;
	const NOTHING_TO_DISPLAY = 1281;
	// next 1282
}
?>
