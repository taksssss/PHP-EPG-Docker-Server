<?php
//	
//	Project: phpLiteAdmin (https://www.phpliteadmin.org/)
//	Version: 1.9.8.2
//	Summary: PHP-based admin tool to manage SQLite2 and SQLite3 databases on the web
//	Last updated: 2019-09-05
//	Developers:
//	   Dane Iracleous (daneiracleous@gmail.com)
//	   Ian Aldrighetti (ian.aldrighetti@gmail.com)
//	   George Flanagin & Digital Gaslight, Inc (george@digitalgaslight.com)
//	   Christopher Kramer (crazy4chrissi@gmail.com, http://en.christosoft.de)
//	   Ayman Teryaki (http://havalite.com)
//	   Dreadnaut (dreadnaut@gmail.com, http://dreadnaut.altervista.org)
//	
//	
//	Copyright (C) 2019, phpLiteAdmin
//	
//	This program is free software: you can redistribute it and/or modify
//	it under the terms of the GNU General Public License as published by
//	the Free Software Foundation, either version 3 of the License, or
//	(at your option) any later version.
//	
//	This program is distributed in the hope that it will be useful,
//	but WITHOUT ANY WARRANTY; without even the implied warranty of
//	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//	GNU General Public License for more details.
//	
//	You should have received a copy of the GNU General Public License
//	along with this program.  If not, see <https://www.gnu.org/licenses/>.
//	
//	////////////////////////////////////////////////////////////////////////
//	
//	Please report any bugs you may encounter to our issue tracker here:
//		https://bitbucket.org/phpliteadmin/public/issues?status=new&status=open

//
// This is sample configuration file
//
// You can configure phpliteadmin in one of 2 ways:
// 1. Rename phpliteadmin.config.sample.php to phpliteadmin.config.php and change parameters in there.
//    You can set only your custom settings in phpliteadmin.config.php. All other settings will be set to defaults.
// 2. Change parameters directly in main phpliteadmin.php file
//
// Please see https://bitbucket.org/phpliteadmin/public/wiki/Configuration for more details

//password to gain access
$password = '';

//directory relative to this file to search for databases (if false, manually list databases in the $databases variable)
$directory = '.';

//whether or not to scan the subdirectories of the above directory infinitely deep
$subdirectories = false;

//if the above $directory variable is set to false, you must specify the databases manually in an array as the next variable
//if any of the databases do not exist as they are referenced by their path, they will be created automatically
$databases = array(
	 
	array(
		'path'=> 'adata.db',  
		'name'=> 'EPG数据库'
	),

	
);


/* ---- Interface settings ---- */

// Theme! If you want to change theme, save the CSS file in same folder of phpliteadmin or in folder "themes"
$theme = 'phpliteadmin.css';

// the default language! If you want to change it, save the language file in same folder of phpliteadmin or in folder "languages"
// More about localizations (downloads, how to translate etc.): https://bitbucket.org/phpliteadmin/public/wiki/Localization
$language = 'cn'; 

// set default number of rows. You need to relog after changing the number
$rowsNum = 30;

// reduce string characters by a number bigger than 10
$charsNum = 300;

// maximum number of SQL queries to save in the history
$maxSavedQueries = 10;

/* ---- Custom functions ---- */

//a list of custom functions that can be applied to columns in the databases
//make sure to define every function below if it is not a core PHP function
$custom_functions = array(
	'md5', 'sha1', 'time', 'strtotime',
	// add the names of your custom functions to this array
	/* 'leet_text', */
);

// define your custom functions here
/*
function leet_text($value)
{
  return strtr($value, 'eaAsSOl', '344zZ01');
}
*/


/* ---- Advanced options ---- */

//changing the following variable allows multiple phpLiteAdmin installs to work under the same domain.
$cookie_name = 'pla3412';

//whether or not to put the app in debug mode where errors are outputted
$debug = false;

// the user is allowed to create databases with only these extensions
$allowed_extensions = array('db','db3','sqlite','sqlite3');


// English language-texts.
// Read our wiki on how to translate: https://bitbucket.org/phpliteadmin/public/wiki/Localization
$lang = array(
	"direction" => "LTR",
	"date_format" =>'Y-m-d H:i:s', //'g:ia \o\n F j, Y (T)',  // see http://php.net/manual/en/function.date.php for what the letters stand for
	"ver" => "version",
	"for" => "给",
	"to" => "至",
	"go" => "确定", 
	"yes" => "是",
	"no" => "不", 
	"sql" => "SQL",
	"csv" => "CSV",
	"csv_tbl" => "Table that CSV pertains to",
	"srch" => "搜索",
	"srch_again" => "执行另一个搜索",
	"login" => "登录",
	"logout" => "退出",
	"view" => "视图",              // here, the noun SQL view is meant, not the verb "to view"
	"confirm" => "确认",
	"cancel" => "取消",
	"save_as" => "另存为",
	"options" => "选项",
	"no_opt" => "没有选项",
	"help" => "帮助",
	"installed" => "已安装",
	"not_installed" => "未安装",
	"done" => "完成",
	"insert" => "插入",
	"export" => "导出",
	"import" => "导入",
	"rename" => "重命名",
	"empty" => "清空",
	"drop" => "删除",
	"tbl" => "表格",
	"chart" => "图表",
	"err" => "错误",
	"act" => "操作",
	"rec" => "记录数",
	"col" => "列",
	"cols" => "Columns",
	"rows" => "行",
	"edit" => "编辑",
	"del" => "删除",
	"add" => "添加",
	"backup" => "备份数据文件", 
	"before" => "之前",
	"after" => "之后",
	"passwd" => "密码",
	"passwd_incorrect" => "密码错误.",
	"chk_ext" => "检查支持SQLite PHP 扩展",
	"autoincrement" => "自增长",
	"not_null" => "Not NULL",
	"attention" => "Attention",
	"none" => "None",
	"as_defined" => "As defined",
	"expression" => "Expression",
	"download" => "下载",
	"open_in_browser" => "用浏览器打开",
	
	"sqlite_ext" => "SQLite extension",
	"sqlite_ext_support" => "It appears that none of the supported SQLite library extensions are available in your installation of PHP. You may not use %s until you install at least one of them.",
	"sqlite_v" => "SQLite version",
	"sqlite_v_error" => "It appears that your database is of SQLite version %s but your installation of PHP does not contain the necessary extensions to handle this version. To fix the problem, either delete the database and allow %s to create it automatically or recreate it manually as SQLite version %s.",
	"report_issue" => "The problem cannot be diagnosed properly. Please file an issue report at",
	"sqlite_limit" => "Due to the limitations of SQLite, only the field name and data type can be modified.",
	
	"php_v" => "PHP version",
	"new_version" => "There is a new version!",
	
	"db_dump" => "database dump",
	"db_f" => "数据库文件",
	"db_ch" => "修改数据库",
	"db_event" => "Database Event",
	"db_name" => "数据库名",
	"db_rename" => "重命名数据库",
	"db_renamed" => "数据库 '%s' 已命名为",
	"db_del" => "删除数据库",
	"db_path" => "数据库路径",
	"db_size" => "数据库大小",
	"db_mod" => "最后更新时间",
	"db_create" => "创建新数据库",
	"db_vac" => "数据库, '%s', has been VACUUMed.",
	"db_not_writeable" => "数据库, '%s', does not exist and cannot be created because the containing directory, '%s', is not writable. The application is unusable until you make it writable.",
	"db_setup" => "There was a problem setting up your database, %s. An attempt will be made to find out what's going on so you can fix the problem more easily",
	"db_exists" => "A database, other file or directory of the name '%s' already exists.",
	"db_blank" => "The database name cannot be blank.",
		
	"exported" => "导出",
	"struct" => "结构",
	"struct_for" => "structure for",
	"on_tbl" => "on table",
	"data_dump" => "Data dump for",
	"backup_hint" => "Hint: To backup your database, the easiest way is to %s.",
	"backup_hint_linktext" => "download the database-file",
	"total_rows" => "共 %s 行",
	"total" => "共",
	"not_dir" => "The directory you specified to scan for databases does not exist or is not a directory.",
	"bad_php_directive" => "It appears that the PHP directive, 'register_globals' is enabled. This is bad. You need to disable it before continuing.",
	"page_gen" => "生成页面用时 %s 秒.",
	"powered" => "技术支持",
	"free_software" => "这是免费软件.",
	"please_donate" => "请捐赠.",
	"remember" => "记住",
	"no_db" => "Welcome to %s. It appears that you have selected to scan a directory for databases to manage. However, %s could not find any valid SQLite databases. You may use the form below to create your first database.",
	"no_db2" => "The directory you specified does not contain any existing databases to manage, and the directory is not writable. This means you can't create any new databases using %s. Either make the directory writable or manually upload databases to the directory.",
	"dir_not_executable" => "The directory you specified cannot be scanned for databases as %s has no execute permissions on it. On Linux, use 'chmod +x %s' to fix this.",
	
	"create" => "创建",
	"created" => "已创建",
	"create_tbl" => "创建新表",
	"create_tbl_db" => "在数据库创建新表", 
	"create_trigger" => "在表里面创建新触发器",
	"create_index" => "在表里面创建新索引",
	"create_index1" => "创建索引",
	"create_view" => "在数据库创建新视图",
	
	"trigger" => "触发器",
	"triggers" => "Triggers",
	"trigger_name" => "Trigger name",
	"trigger_act" => "Trigger Action",
	"trigger_step" => "Trigger Steps (semicolon terminated)",
	"when_exp" => "WHEN expression (type expression without 'WHEN')",
	"index" => "Index",
	"indexes" => "Indexes",
	"index_name" => "Index name",
	"name" => "表名",
	"unique" => "唯一",
	"seq_no" => "序列号",
	"emptied" => "已清空",
	"dropped" => "已删除",
	"renamed" => "已重命名为",
	"altered" => "已修改成功",
	"inserted" => "已插入",
	"deleted" => "已删除",
	"affected" => "影响",
	"blank_index" => "Index name must not be blank.",
	"one_index" => "You must specify at least one index column.",
	"docu" => "Documentation",
	"license" => "License",
	"proj_site" => "Project Site",
	"bug_report" => "This may be a bug that needs to be reported at",
	"return" => "返回",
	"browse" => "浏览",
	"fld" => "字段",
	"fld_num" => "字段数",
	"fields" => "字段列表",
	"type" => "类型",
	"operator" => "操作",
	"val" => "值",
	"update" => "更新",
	"comments" => "注释",
	
	"specify_fields" => "You must specify the number of table fields.",
	"specify_tbl" => "You must specify a table name.",
	"specify_col" => "You must specify a column.",
	
	"tbl_exists" => "Table of the same name already exists.",
	"show" => "显示",
	"show_rows" => "显示 %s row(s). ",
	"showing" => "Showing",
	"showing_rows" => "显示行数",
	"query_time" => "(执行消耗 %s 秒)",
	"syntax_err" => "There is a problem with the syntax of your query (Query was not executed)",
	"run_sql" => "运行 SQL query/queries on database '%s'",
	"recent_queries" => "最近的执行",
	"full_texts" => "显示完整文本",
	"no_full_texts" => "Shorten long texts",
	
	"ques_empty" => "你确定要清空表 '%s'?",
	"ques_drop" => "你确定要删除表 '%s'?",
	"ques_drop_view" => "你确定要删除视图 '%s'?",
	"ques_del_rows" => "Are you sure you want to delete row(s) %s from table '%s'?",
	"ques_del_db" => "你确定要删除数据库 '%s'?",
	"ques_column_delete" => "Are you sure you want to delete column(s) %s from table '%s'?",
	"ques_del_index" => "你确定要删除索引 '%s'?",
	"ques_del_trigger" => "你确定要删除触发器 '%s'?",
	"ques_primarykey_add" => "Are you sure you want to add a primary key for the column(s) %s in table '%s'?",
	
	"export_struct" => "导出包含结构",
	"export_data" => "导出包含数据",
	"add_drop" => "Add DROP TABLE",
	"add_transact" => "Add TRANSACTION",
	"fld_terminated" => "Fields terminated by",
	"fld_enclosed" => "Fields enclosed by",
	"fld_escaped" => "Fields escaped by",
	"fld_names" => "Field names in first row",
	"rep_null" => "Replace NULL by",
	"rem_crlf" => "Remove CRLF characters within fields",
	"put_fld" => "Put field names in first row",
	"null_represent" => "NULL represented by",
	"import_suc" => "Import was successful.",
	"import_into" => "Import into",
	"import_f" => "File to import",
	"max_file_size" => "最大限制",
	"rename_tbl" => "重命名表 '%s' 为",
	
	"rows_records" => "row(s) starting from record # ",
	"rows_aff" => "row(s) affected. ",
	
	"as_a" => "as a",
	"readonly_tbl" => "'%s' is a view, which means it is a SELECT statement treated as a read-only table. You may not edit or insert records.",
	"chk_all" => "全选",
	"unchk_all" => "全不选", 
	"with_sel" => "已选",
	
	"no_tbl" => "数据库中没有表.",
	"no_chart" => "If you can read this, it means the chart could not be generated. The data you are trying to view may not be appropriate for a chart.",
	"no_rows" => "There are no rows in the table for the range you selected.",
	"no_sel" => "You did not select anything.",
	
	"chart_type" => "Chart Type",
	"chart_bar" => "Bar Chart",
	"chart_pie" => "Pie Chart",
	"chart_line" => "Line Chart",
	"lbl" => "Labels",
	"empty_tbl" => "这个表是空的",
	"click" => "点击这里",
	"insert_rows" => "to insert rows.",
	"restart_insert" => "Restart insertion with ",
	"ignore" => "忽略",
	"func" => "函数",
	"new_insert" => "作为新行插入",
	"save_ch" => "保存修改",
	"def_val" => "默认值",
	"prim_key" => "主键",
	"tbl_end" => "字段位于表结尾",
	"query_used_table" => "Query used to create this table",
	"query_used_view" => "Query used to create this view",
	"create_index2" => "Create an index on",
	"create_trigger2" => "Create a new trigger",
	"new_fld" => "Adding new field(s) to table '%s'",
	"add_flds" => "添加字段",
	"edit_col" => "Editing column '%s'",
	"vac" => "整理",
	"vac_desc" => "Large databases sometimes need to be VACUUMed to reduce their footprint on the server. Click the button below to VACUUM the database '%s'.",
	"vac_on_empty"=>"Rebuild database file to recover unused space (Vacuum)",
	"event" => "事件",
	"each_row" => "For Each Row",
	"define_index" => "Define index properties",
	"dup_val" => "重复值",
	"allow" => "允许",
	"not_allow" => "不允许",
	"asc" => "升序",
	"desc" => "降序",
	"warn0" => "You have been warned.",
	"warn_passwd" => "You are using the default password, which can be dangerous. You can change it easily at the top of %s.",
	"counting_skipped" => "Counting of records has been skipped for some tables because your database is comparably big and some tables don't have primary keys assigned to them so counting might be slow. Add a primary key to these tables or %sforce counting%s.",
	"sel_state" => "查询语句",
	"delimit" => "分隔符",
	"back_top" => "返回顶部",
	"choose_f" => "选择文件",
	"instead" => "替换",
	"define_in_col" => "Define index column(s)",
	
	"delete_only_managed" => "You can only delete databases managed by this tool!",
	"rename_only_managed" => "You can only rename databases managed by this tool!",
	"db_moved_outside" => "You either tried to move the database into a directory where it cannot be managed anylonger, or the check if you did this failed because of missing rights.",
	"extension_not_allowed" => "The extension you provided is not within the list of allowed extensions. Please use one of the following extensions",
	"add_allowed_extension" => "You can add extensions to this list by adding your extension to \$allowed_extensions in the configuration.",
	"database_not_writable" => "The database-file is not writable, so its content cannot be changed in any way.",
	"directory_not_writable" => "The database-file itself is writable, but to write into it, the containing directory needs to be writable as well. This is because SQLite puts temporary files in there for locking.",
	"tbl_inexistent" => "Table %s does not exist",
	"col_inexistent" => "Column %s does not exist",

	// errors that can happen when ALTER TABLE fails. You don't necessarily have to translate these.
	"alter_failed" => "Altering of Table %s failed",
	"alter_tbl_name_not_replacable" => "could not replace the table name with the temporary one",
	"alter_no_def" => "no ALTER definition",
	"alter_parse_failed" =>"failed to parse ALTER definition",
	"alter_action_not_recognized" => "ALTER action could not be recognized",
	"alter_no_add_col" => "no column to add detected in ALTER statement",
	"alter_pattern_mismatch"=>"Pattern did not match on your original CREATE TABLE statement",
	"alter_col_not_recognized" => "could not recognize new or old column name",
	"alter_unknown_operation" => "Unknown ALTER operation!",
	
	/* Help documentation */
	"help_doc" => "帮助文档",
	"help1" => "SQLite Library Extensions",
	"help1_x" => "%s uses PHP library extensions that allow interaction with SQLite databases. Currently, %s supports PDO, SQLite3, and SQLiteDatabase. Both PDO and SQLite3 deal with version 3 of SQLite, while SQLiteDatabase deals with version 2. So, if your PHP installation includes more than one SQLite library extension, PDO and SQLite3 will take precedence to make use of the better technology. However, if you have existing databases that are of version 2 of SQLite, %s will be forced to use SQLiteDatabase for only those databases. Not all databases need to be of the same version. During the database creation, however, the most advanced extension will be used.",
	"help2" => "创建一个新数据库",
	"help2_x" => "When you create a new database, the name you entered will be appended with the appropriate file extension (.db, .db3, .sqlite, etc.) if you do not include it yourself. The database will be created in the directory you specified as the \$directory variable.",
	"help3" => "Tables vs. Views",
	"help3_x" => "On the main database page, there is a list of tables and views. Since views are read-only, certain operations will be disabled. These disabled operations will be apparent by their omission in the location where they should appear on the row for a view. If you want to change the data for a view, you need to drop that view and create a new view with the appropriate SELECT statement that queries other existing tables. For more information, see <a href='http://en.wikipedia.org/wiki/View_(database)' target='_blank'>http://en.wikipedia.org/wiki/View_(database)</a>",
	"help4" => "为新识图写一个查询语句", 
	"help4_x" => "When you create a new view, you must write an SQL SELECT statement that it will use as its data. A view is simply a read-only table that can be accessed and queried like a regular table, except it cannot be modified through insertion, column editing, or row editing. It is only used for conveniently fetching data.",
	"help5" => "Export Structure to SQL File",
	"help5_x" => "During the process for exporting to an SQL file, you may choose to include the queries that create the table and columns.",
	"help6" => "Export Data to SQL File",
	"help6_x" => "During the process for exporting to an SQL file, you may choose to include the queries that populate the table(s) with the current records of the table(s).",
	"help7" => "Add Drop Table to Exported SQL File",
	"help7_x" => "During the process for exporting to an SQL file, you may choose to include queries to DROP the existing tables before adding them so that problems do not occur when trying to create tables that already exist.",
	"help8" => "Add Transaction to Exported SQL File",
	"help8_x" => "During the process for exporting to an SQL file, you may choose to wrap the queries around a TRANSACTION so that if an error occurs at any time during the importation process using the exported file, the database can be reverted to its previous state, preventing partially updated data from populating the database.",
	"help9" => "Add Comments to Exported SQL File",
	"help9_x" => "During the process for exporting to an SQL file, you may choose to include comments that explain each step of the process so that a human can better understand what is happening.",
	"help10" => "Partial Indexes",
	"help10_x" => "Partial indexes are indexes over a subset of the rows of a table specified by a WHERE clause. Note this requires at least SQLite 3.8.0 and database files with partial indexes won't be readable or writable by older versions. See the <a href='https://www.sqlite.org/partialindex.html' target='_blank'>SQLite documentation.</a>",
	"help11" => "Maximum size of file uploads",
	"help11_x" => "The maximum size of file uploads is determined by three PHP settings: <em>upload_max_filesize</em>, <em>post_max_size</em> and <em>memory_limit</em>. The smallest of these three limits the maximum size for file uploads. To upload larger files, adjust these values in your <em>php.ini</em> file."

);
 
//constants 1
define("PROJECT", "phpLiteAdmin");
define("VERSION", "1.9.8.2");
define("FORCETYPE", false); //force the extension that will be used (set to false in almost all circumstances except debugging)
define("SYSTEMPASSWORD", $password); // Makes things easier.
define('PROJECT_URL','https://www.phpliteadmin.org/');
define('DONATE_URL','https://www.phpliteadmin.org/donate/');
define('VERSION_CHECK_URL','https://www.phpliteadmin.org/current_version.php');
define('PROJECT_BUGTRACKER_LINK','<a href="https://bitbucket.org/phpliteadmin/public/issues?status=new&status=open" target="_blank">https://bitbucket.org/phpliteadmin/public/issues?status=new&status=open</a>');
define('PROJECT_INSTALL_LINK','<a href="https://bitbucket.org/phpliteadmin/public/wiki/Installation" target="_blank">https://bitbucket.org/phpliteadmin/public/wiki/Installation</a>');

// up here, we don't output anything. debug output might appear here which is catched by ob and thrown later
ob_start();

// Resource output (css and javascript files)
// we get out of the main code as soon as possible, without inizializing the session
if (isset($_GET['resource']))
{
	Resources::output($_GET['resource']);
	exit();
}

// don't mess with this - required for the login session
ini_set('session.cookie_httponly', '1');
session_start();

// 检查是否有权限访问 phpliteadmin.php
if (!isset($_SESSION['can_access_phpliteadmin']) || $_SESSION['can_access_phpliteadmin'] !== true) {
    // 如果没有权限，可以重定向到其他页面或者显示错误信息
    echo "您没有访问权限";
    exit;
}

// version-number added so after updating, old session-data is not used anylonger
// cookies names cannot contain symbols, except underscores
define("COOKIENAME", preg_replace('/[^a-zA-Z0-9_]/', '_', $cookie_name . '_' . VERSION) );

$params = new GetParameters();

if($debug==true)
{
	ini_set("display_errors", 1);
	error_reporting(E_STRICT | E_ALL);
} else
{
	@ini_set("display_errors", 0);
}

// start the timer to record page load time
$pageTimer = new MicroTimer();
 
// stripslashes if MAGIC QUOTES is turned on
// This is only a workaround. Please better turn off magic quotes!
// This code is from http://php.net/manual/en/security.magicquotes.disabling.php
if (get_magic_quotes_gpc()) {
	$process = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
	while (list($key, $val) = each($process)) {
		foreach ($val as $k => $v) {
			unset($process[$key][$k]);
			if (is_array($v)) {
				$process[$key][stripslashes($k)] = $v;
				$process[] = &$process[$key][stripslashes($k)];
			} else {
				$process[$key][stripslashes($k)] = stripslashes($v);
			}
		}
	}
	unset($process);
}


//data types array
$sqlite_datatypes = array("INTEGER", "REAL", "TEXT", "BLOB","NUMERIC","BOOLEAN","DATETIME");

//available SQLite functions array (don't add anything here or there will be problems)
$sqlite_functions = array("abs", "hex", "length", "lower", "ltrim", "random", "round", "rtrim", "trim", "typeof", "upper");

//- Support functions

// for php < 5.6.0
if(!function_exists('hash_equals'))
{
	function hash_equals($str1, $str2)
	{
		if(strlen($str1) != strlen($str2))
			return false;
		else {
			$res = $str1 ^ $str2;
			$ret = 0;
			for($i = strlen($res) - 1; $i >= 0; $i--)
				$ret |= ord($res[$i]);
			return !$ret;
		}
	}
}

//function that allows SQL delimiter to be ignored inside comments or strings
function explode_sql($delimiter, $sql)
{
	$ign = array('"' => '"', "'" => "'", "/*" => "*/", "--" => "\n"); // Ignore sequences.
	$out = array();
	$last = 0;
	$slen = strlen($sql);
	$dlen = strlen($delimiter);
	$i = 0;
	while($i < $slen)
	{
		// Split on delimiter
		if($slen - $i >= $dlen && substr($sql, $i, $dlen) == $delimiter)
		{
			array_push($out, substr($sql, $last, $i - $last));
			$last = $i + $dlen;
			$i += $dlen;
			continue;
		}
		// Eat comments and string literals
		foreach($ign as $start => $end)
		{
			$ilen = strlen($start);
			if($slen - $i >= $ilen && substr($sql, $i, $ilen) == $start)
			{
				$i+=strlen($start);
				$elen = strlen($end);
				while($i < $slen)
				{
					if($slen - $i >= $elen && substr($sql, $i, $elen) == $end)
					{
						// SQL comment characters can be escaped by doubling the character. This recognizes and skips those.
						if($start == $end && $slen - $i >= $elen*2 && substr($sql, $i, $elen*2) == $end.$end)
						{
							$i += $elen * 2;
							continue;
						}
						else
						{
							$i += $elen;
							continue 3;
						}
					}
					$i++;
				}
				continue 2;
			}
		}
		$i++;
	}
	if($last < $slen)
		array_push($out, substr($sql, $last, $slen - $last));
	return $out;
}

//function to scan entire directory tree and subdirectories
function dir_tree($dir)
{
	$path = array();
	$stack = array($dir);
	while($stack)
	{
		$thisdir = array_pop($stack);
		if($dircont = scandir($thisdir))
		{
			$i=0;
			while(isset($dircont[$i]))
			{
				if($dircont[$i] !== '.' && $dircont[$i] !== '..')
				{
					$current_file = $thisdir.DIRECTORY_SEPARATOR.$dircont[$i];
					if(is_file($current_file))
					{
						$path[] = $thisdir.DIRECTORY_SEPARATOR.$dircont[$i];
					}
					elseif (is_dir($current_file))
					{
						$path[] = $thisdir.DIRECTORY_SEPARATOR.$dircont[$i];
						$stack[] = $current_file;
					}
				}
				$i++;
			}
		}
	}
	return $path;
}

//the function echo the help [?] links to the documentation
function helpLink($name)
{
	global $lang;
	return "<a href='?help=1' onclick='openHelp(\"".$name."\"); return false;' class='helpq' title='".$lang['help'].": ".$name."' target='_blank'><span>[?]</span></a>";
}

// function to encode value into HTML just like htmlentities, but with adjusted default settings
function htmlencode($value, $flags=ENT_QUOTES, $encoding ="UTF-8")
{
	return htmlentities($value, $flags, $encoding);
}

// reduce string chars
function subString($str)
{
	global $charsNum, $params;
	if($charsNum > 10 && (!isset($params->fulltexts) || !$params->fulltexts) && mb_strlen($str)>$charsNum)
	{
		$str = mb_substr($str, 0, $charsNum).'...';
	}
	return $str;
}

// marks searchwords and htmlencodes correctly
function markSearchWords($input, $field, $search)
{
	$output = htmlencode($input);
	if(isset($search['values'][$field]) && is_array($search['values'][$field]))
	{
		// build one regex that matches (all) search words
		$regex = '/';
		$vali=0;
		foreach($search['values'][$field] as $searchValue)
		{
			if($search['operators'][$field] =='LIKE' || $search['operators'][$field] == 'LIKE%')
				$regex .= '(?:'.($searchValue[0]=='%'?'':'^'); // does the searchvalue have to occur at the start?
			$regex .= preg_quote(trim($searchValue,'%'),'/');  // the search value
			if($search['operators'][$field] =='LIKE' || $search['operators'][$field] == 'LIKE%')
				$regex .= (substr($searchValue,-1)=='%'?'':'$').')';  // does the searchvalue have to occur at the end?
			if($vali++<count($search['values'][$field]))
				$regex .= '|';    // there is another search value, so we add a |
		}
		$regex .= '/u';
		// LIKE operator is not case sensitive, others are
		if($search['operators'][$field] =='LIKE' || $search['operators'][$field] == 'LIKE%')
			$regex.= 'i';

		// split the string into parts that match and should be highlighted and parts in between
		// $fldBetweenParts: the parts that don't match (might contain empty strings)
		$fldBetweenParts = preg_split($regex, $input);
		// $fldFoundParts[0]: the parts that match
		preg_match_all($regex, $input, $fldFoundParts);

		// stick the parts together
		$output = '';
		foreach($fldBetweenParts as $index => $betweenPart)
		{
			$output .= htmlencode($betweenPart); // part that does not match (might be empty)
			if(isset($fldFoundParts[0][$index]))
				$output .= '<u class="found">'.htmlencode($fldFoundParts[0][$index]).'</u>'; // the part that matched
		}
	}
	return $output;
}

// checks the (new) name of a database file
function checkDbName($name)
{
	global $allowed_extensions;
	$info = pathinfo($name);
	if(isset($info['extension']) && !in_array($info['extension'], $allowed_extensions))
	{
		return false;
	} else
	{
		return (!is_file($name) && !is_dir($name));
	}

}

// check whether a path is a db managed by this tool
// requires that $databases is already filled!
// returns the key of the db if managed, false otherwise.
function isManagedDB($path)
{
	global $databases;
	foreach($databases as $db_key => $database)
	{
		if($path === $database['path'])
		{
			// a db we manage. Thats okay.
			// return the key.
			return $db_key;
		}
	}
	// not a db we manage!
	return false;
}

// from a typename of a colun, get the type of the column's affinty
// see https://www.sqlite.org/datatype3.html section 2.1 for rules
function get_type_affinity($type)
{
	if (preg_match("/INT/i", $type))
		return "INTEGER";
	else if (preg_match("/(?:CHAR|CLOB|TEXT)/i", $type))
		return "TEXT";
	else if (preg_match("/BLOB/i", $type) || $type=="")
		return "NONE";
	else if (preg_match("/(?:REAL|FLOA|DOUB)/i", $type))
		return "REAL";
	else
		return "NUMERIC";
}


// Returns a file size limit in bytes based on the PHP upload_max_filesize
// post_max_size and memory_limit. Returns -1 in case of no limit.
function fileUploadMaxSize()
{
	$max1 = parseSize(ini_get('post_max_size'));
	$max2 = parseSize(ini_get('upload_max_filesize'));
	$max3 = parseSize(ini_get('memory_limit'));
	if($max1>0 && ($max1<=$max2 || $max2==0) && ($max1<=$max3 || $max3==-1))
		return $max1;
	elseif($max2>0 && ($max2<=$max1 || $max1==0) && ($max2<=$max3 || $max3==-1))
		return $max2;
	elseif($max3>-1 && ($max3<=$max1 || $max1==0) && ($max3<=$max2 || $max2==0))
		return $max3;
	else
		return -1; // no limit
}

// Parses given size string like "12M" into number of bytes
// based on https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Component%21Utility%21Bytes.php/function/Bytes%3A%3AtoInt/8.2.x
function parseSize($size)
{
	// Remove the non-unit characters from the size.
	$unit = preg_replace('/[^bkmgtpezy]/i', '', $size);
	// Remove the non-numeric characters from the size.
	$size = preg_replace('/[^0-9\.]/', '', $size);
	if ($unit)
	{
		// Find the position of the unit in the ordered string which is the power
		// of magnitude to multiply a kilobyte by.
		return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
	}
	else {
		return round($size);
	}
}


//- Check user authentication, login and logout
$auth = new Authorization(); //create authorization object

// check if user has attempted to log out
if (isset($_GET['logout']))
	$auth->revoke();
// check if user has attempted to log in
else if (isset($_POST['login']) && isset($_POST['password']))
{
	$attempt = $auth->attemptGrant($_POST['password'], isset($_POST['remember']));
	$params->redirect( $attempt ? array():array('failed'=>'1') );
}

//- Actions on database files and bulk data
if ($auth->isAuthorized())
{

	//- Create a new database
	if(isset($_POST['new_dbname']))
	{
		if($_POST['new_dbname']=='')
			$params->redirect(array('table'=>null), $lang['err'].': '.$lang['db_blank']);
		else
		{
			$str = preg_replace('@[^\w\-.]@u','', $_POST['new_dbname']);
			$dbname = $str;
			$dbpath = $str;
			if(checkDbName($dbname))
			{
				$tdata = array();
				$tdata['name'] = $dbname;
				$tdata['path'] = $directory.DIRECTORY_SEPARATOR.$dbpath;
				if(isset($_POST['new_dbtype']))
					$tdata['type'] = $_POST['new_dbtype'];
				else
					$tdata['type'] = 3;
				$td = new Database($tdata);
				$td->query("VACUUM");
			} else
			{
				if(is_file($dbname) || is_dir($dbname))
					$params->redirect(array('view'=>'structure'),$lang['err'].': '.sprintf($lang['db_exists'], htmlencode($dbname)));
				else
					$params->redirect(array('view'=>'structure'),$lang['extension_not_allowed'].': '.implode(', ', array_map('htmlencode', $allowed_extensions)).'<br />'.$lang['add_allowed_extension']);
			}
		}
	}

	//- Scan a directory for databases
	if($directory!==false)
	{
		if($directory[strlen($directory)-1]==DIRECTORY_SEPARATOR) //if user has a trailing slash in the directory, remove it
			$directory = substr($directory, 0, strlen($directory)-1);

		if(is_dir($directory)) //make sure the directory is valid
		{
			if($subdirectories===true)
				$arr = dir_tree($directory);
			else
				$arr = scandir($directory);
			$databases = array();
			$j = 0;
			for($i=0; $i<sizeof($arr); $i++) //iterate through all the files in the databases
			{
				if($subdirectories===false)
					$arr[$i] = $directory.DIRECTORY_SEPARATOR.$arr[$i];

				if(@!is_file($arr[$i])) continue;
				$con = file_get_contents($arr[$i], NULL, NULL, 0, 60);
				if(strpos($con, "** This file contains an SQLite 2.1 database **", 0)!==false || strpos($con, "SQLite format 3", 0)!==false)
				{
					$databases[$j]['path'] = $arr[$i];
					if($subdirectories===false)
						$databases[$j]['name'] = basename($arr[$i]);
					else
						$databases[$j]['name'] = $arr[$i];
					$databases[$j]['writable'] = is_writable($databases[$j]['path']);
					$databases[$j]['writable_dir'] = is_writable(dirname($databases[$j]['path']));
					$databases[$j]['readable'] = is_readable($databases[$j]['path']);
					$j++;
				}
			}
			// 22 August 2011: gkf fixed bug #50.
			sort($databases);
			if(isset($tdata))
			{
				foreach($databases as $db_id => $database)
				{
					if($database['path'] === $tdata['path'])
					{
						$currentDB = $database;
						$params->database = $database['path'];
						break;
					}
				}
			}
		}
		else //the directory is not valid - display error and exit
		{
			echo "<div class='confirm' style='margin:20px;'>".$lang['not_dir']."</div>";
			exit();
		}
	}
	else
	{
		for($i=0; $i<sizeof($databases); $i++)
		{
			if(!file_exists($databases[$i]['path']))
			{
				// the file does not exist and will be created when clicked, if permissions allow to
				$databases[$i]['writable'] = is_writable(dirname($databases[$i]['path']));
				$databases[$i]['writable_dir'] = is_writable(dirname($databases[$i]['path']));
				$databases[$i]['readable'] = is_writable(dirname($databases[$i]['path']));
			}
			else
			{
				$databases[$i]['writable'] = is_writable($databases[$i]['path']);
				$databases[$i]['writable_dir'] = is_writable(dirname($databases[$i]['path']));
				$databases[$i]['readable'] = is_readable($databases[$i]['path']);
			}
		}
		sort($databases);
	}
	// we now have the $databases array set. Check whether selected DB is a managed Db (is in this array)
	if(!isset($currentDB) && (isset($_GET['database']) || isset($_POST['database']) ) )
	{
		$selected_db = ( isset($_POST['database']) ? $_POST['database'] : $_GET['database'] );
		$db_key = isManagedDB($selected_db);
		if($db_key!==false) {
			$currentDB = $databases[$db_key];
			$params->database = $databases[$db_key]['path'];
		}
	}

	//- Delete an existing database
	if(isset($_GET['database_delete']))
	{
		$dbpath = $_POST['database_delete'];
		// check whether $dbpath really is a db we manage
		$checkDB = isManagedDB($dbpath);
		if($checkDB !== false)
		{
			unlink($dbpath);
			unset($params->database);
			unset($currentDB);
			unset($databases[$checkDB]);
		} else die($lang['err'].': '.$lang['delete_only_managed']);
	}

	//- Rename an existing database
	if(isset($_GET['database_rename']))
	{
		$oldpath = $_POST['oldname'];
		$newpath = $_POST['newname'];
		$oldpath_parts = pathinfo($oldpath);
		$newpath_parts = pathinfo($newpath);
		// only rename?
		$newpath = $oldpath_parts['dirname'].DIRECTORY_SEPARATOR.basename($_POST['newname']);
		if($newpath != $_POST['newname'] && $subdirectories)
		{
			// it seems that the file should not only be renamed but additionally moved.
			// we need to make sure it stays within $directory...
			$new_realpath = realpath($newpath_parts['dirname']).DIRECTORY_SEPARATOR;
			$directory_realpath = realpath($directory).DIRECTORY_SEPARATOR;
			if(strpos($new_realpath, $directory_realpath)===0)
			{
				// its okay, the new directory is within $directory
				$newpath =  $_POST['newname'];
			}
			else $params->redirect(array('view'=>'rename'), $lang['err'].': '.$lang['db_moved_outside']);
		}

		if(checkDbName($newpath))
		{
			$checkDB = isManagedDB($oldpath);
			if($checkDB !==false )
			{
				rename($oldpath, $newpath);
				$databases[$checkDB]['path'] = $newpath;
				$databases[$checkDB]['name'] = basename($newpath);
				$currentDB = $databases[$checkDB];
				$params->database = $databases[$checkDB]['path'];
				$params->redirect(array('view'=>'rename'), sprintf($lang['db_renamed'], htmlencode($oldpath))." '".htmlencode($newpath)."'.");
			}
			else $params->redirect(array('view'=>'rename'), $lang['err'].': '.$lang['rename_only_managed']);
		}
		else
		{
			if(is_file($newpath) || is_dir($newpath))
				$params->redirect(array('view'=>'rename'), $lang['err'].": " . sprintf($lang['db_exists'], htmlencode($newpath)));
			else
				$params->redirect(array('view'=>'rename'), $lang['err'].": " . $lang['extension_not_allowed'].': '.implode(', ', array_map('htmlencode', $allowed_extensions)).'<br />'.$lang['add_allowed_extension']);
		}
	}


	//- Export (download a dump) an existing database
	if(isset($_POST['export']))
	{
		ob_end_clean();
		$export_filename = str_replace(array("\r", "\n"), '',$_POST['filename']); // against http header injection (php < 5.1.2 only)
		if($_POST['export_type']=="sql")
		{
			header('Content-Type: text/sql');
			header('Content-Disposition: attachment; filename="'.$export_filename.'.'.$_POST['export_type'].'";');
			if(isset($_POST['tables']))
				$tables = $_POST['tables'];
			else
			{
				$tables = array();
				$tables[0] = $_POST['single_table'];
			}
			$drop = isset($_POST['drop']);
			$structure = isset($_POST['structure']);
			$data = isset($_POST['data']);
			$transaction = isset($_POST['transaction']);
			$comments = isset($_POST['comments']);
			$db = new Database($currentDB);
			echo $db->export_sql($tables, $drop, $structure, $data, $transaction, $comments);
		}
		else if($_POST['export_type']=="csv")
		{
			header("Content-type: application/csv");
			header('Content-Disposition: attachment; filename="'.$export_filename.'.'.$_POST['export_type'].'";');
			header("Pragma: no-cache");
			header("Expires: 0");
			if(isset($_POST['tables']))
				$tables = $_POST['tables'];
			else
			{
				$tables = array();
				$tables[0] = $_POST['single_table'];
			}
			$field_terminate = $_POST['export_csv_fieldsterminated'];
			$field_enclosed = $_POST['export_csv_fieldsenclosed'];
			$field_escaped = $_POST['export_csv_fieldsescaped'];
			$null = $_POST['export_csv_replacenull'];
			$crlf = isset($_POST['export_csv_crlf']);
			$fields_in_first_row = isset($_POST['export_csv_fieldnames']);
			$db = new Database($currentDB);
			echo $db->export_csv($tables, $field_terminate, $field_enclosed, $field_escaped, $null, $crlf, $fields_in_first_row);
		}
		exit();
	}

	//- Import a file into an existing database
	if(isset($_POST['import']))
	{
		$db = new Database($currentDB);
		$db->registerUserFunction($custom_functions);
		if($_POST['import_type']=="sql")
		{
			$data = file_get_contents($_FILES["file"]["tmp_name"]);
			$importSuccess = $db->import_sql($data);
		}
		else
		{
			$field_terminate = $_POST['import_csv_fieldsterminated'];
			$field_enclosed = $_POST['import_csv_fieldsenclosed'];
			$field_escaped = $_POST['import_csv_fieldsescaped'];
			$null = $_POST['import_csv_replacenull'];
			$fields_in_first_row = isset($_POST['import_csv_fieldnames']);
			if(isset($_POST['single_table']) && $_POST['single_table']!='')
				$table = $_POST['single_table'];
			else
			{
				$table = basename($_FILES["file"]["name"],".csv");
				$i="";
				while($db->getTypeOfTable($table.$i)!="")
				{
					if($i=="")
						$i=2;
					else
						$i++;
				}
				$table = $table.$i;
			}
			$importSuccess = $db->import_csv($_FILES["file"]["tmp_name"], $table, $field_terminate, $field_enclosed, $field_escaped, $null, $fields_in_first_row);
		}
	}
	//- Download (backup) a database file (as SQLite file, not as dump)
	if(isset($_GET['download']) && isManagedDB($_GET['download'])!==false)
	{
		ob_end_clean();
		header("Content-type: application/octet-stream");
		header('Content-Disposition: attachment; filename="'.basename($_GET['download']).'";');
		header("Pragma: no-cache");
		header("Expires: 0");
		readfile($_GET['download']);
		exit;
	}

	//- Select database (from session or first available)
	if(!isset($currentDB) && count($databases)>0)
	{
		//set the current database to the first existing one in the array (default)
		$currentDB = reset($databases);
		$params->database = $currentDB['path'];
	}

	if(isset($currentDB))
	{
		//- Open database (creates a Database object)
		$db = new Database($currentDB); //create the Database object
		$db->registerUserFunction($custom_functions);
	}

	// collect parameters early, just once
	$target_table = isset($_GET['table']) ? $_GET['table'] : null;
	// are we working on a view? let's check once here
	$target_table_type = !is_null($target_table) ? $db->getTypeOfTable($target_table) : null;
	if(is_null($target_table_type) && !is_null($target_table))
		$params->redirect(array('table'=>null), $lang['err'].': '.sprintf($lang['tbl_inexistent'], htmlencode($target_table)));
	$params->table = $target_table;

	// initialize / change fulltexts and numrows parameter
	if(isset($_GET['fulltexts']))
		$params->fulltexts = ($_GET['fulltexts'] ? 1 : 0);
	else
		$params->fulltexts = 0;

	if(isset($_GET['numRows']) && intval($_GET['numRows'])>0)
		$params->numRows = intval($_GET['numRows']);
	else
		$params->numRows = $rowsNum;

	//- Switch on $_GET['action'] for operations without output
	if(isset($_GET['action']) && isset($_GET['confirm']))
	{
		switch($_GET['action'])
		{
		//- Table actions

			//- Create table (=table_create)
			case "table_create":
				$num = intval($_POST['rows']);
				$name = $_POST['tablename'];
				$primary_keys = array();
				for($i=0; $i<$num; $i++)
				{
					if($_POST[$i.'_field']!="" && isset($_POST[$i.'_primarykey']))
					{
						$primary_keys[] = $_POST[$i.'_field'];
					}
				}
				$query = "CREATE TABLE ".$db->quote($name)." (";
				for($i=0; $i<$num; $i++)
				{
					if($_POST[$i.'_field']!="")
					{
						$query .= $db->quote($_POST[$i.'_field'])." ";
						$query .= $_POST[$i.'_type']." ";
						if(isset($_POST[$i.'_primarykey']))
						{
							if(count($primary_keys)==1)
							{
								$query .= "PRIMARY KEY ";
								if(isset($_POST[$i.'_autoincrement']) && $db->getType() != "SQLiteDatabase")
									$query .=  "AUTOINCREMENT ";
							}
							$query .= "NOT NULL ";
						}
						if(!isset($_POST[$i.'_primarykey']) && isset($_POST[$i.'_notnull']))
							$query .= "NOT NULL ";
						if($_POST[$i.'_defaultoption']!='defined' && $_POST[$i.'_defaultoption']!='none' && $_POST[$i.'_defaultoption']!='expr')
							$query .= "DEFAULT ".$_POST[$i.'_defaultoption']." ";
						elseif($_POST[$i.'_defaultoption']=='expr')
							$query .= "DEFAULT (".$_POST[$i.'_defaultvalue'].") ";
						elseif(isset($_POST[$i.'_defaultvalue']) && $_POST[$i.'_defaultoption']=='defined')
						{
							$typeAffinity = get_type_affinity($_POST[$i.'_type']);
							if(($typeAffinity=="INTEGER" || $typeAffinity=="REAL" || $typeAffinity=="NUMERIC") && is_numeric($_POST[$i.'_defaultvalue']))
								$query .= "DEFAULT ".$_POST[$i.'_defaultvalue']."  ";
							else
								$query .= "DEFAULT ".$db->quote($_POST[$i.'_defaultvalue'])." ";
						}
						$query = substr($query, 0, -1);
						$query .= ", ";
					}
				}
				if (count($primary_keys)>1)
				{
					$compound_key = "";
					foreach ($primary_keys as $primary_key)
					{
						$compound_key .= ($compound_key=="" ? "" : ", ") . $db->quote($primary_key);
					}
					$query .= "PRIMARY KEY (".$compound_key."), ";
				}
				$query = substr($query, 0, -2);
				$query .= ")";
				$result = $db->query($query);
				if($result === false)
					$completed = $db->getError(true);
				else
					$completed = $lang['tbl']." '".htmlencode($_POST['tablename'])."' ".$lang['created'].".<br/><span style='font-size:11px;'>".htmlencode($query)."</span>";
				$params->redirect(($result===false ? array() : array('action'=>'column_view', 'table'=>$name) ), $completed);
				break;

			//- Empty table (=table_empty)
			case "table_empty":
				$query1 = "DELETE FROM ".$db->quote_id($_GET['table']).";";
				$result1 = $db->query($query1);
				if($result1 === false)
					$completed = $db->getError(true);
				if(isset($_POST['vacuum']) && $_POST['vacuum'])
				{
					$query2 = "VACUUM;";
					$result2 = $db->query($query2);
				}
				else
					$query2 = "";
				if($result1 !== false)
					$completed = $lang['tbl']." '".htmlencode($_GET['table'])."' ".$lang['emptied'].".<br/><span style='font-size:11px;'>".htmlencode($query1)."<br />".htmlencode($query2)."</span>";
				$params->redirect(($result1===false ? array() : array('action'=>'row_view') ), $completed);
				break;

			//- Create view (=view_create)
			case "view_create":
				$query = "CREATE VIEW ".$db->quote($_POST['viewname'])." AS ".$_POST['select'];
				$result = $db->query($query);
				if($result === false)
					$completed = $db->getError(true);
				else
					$completed = $lang['view']." '".htmlencode($_POST['viewname'])."' ".$lang['created'].".<br/><span style='font-size:11px;'>".htmlencode($query)."</span>";
				$params->redirect(($result===false ? array() : array('action'=>'column_view', 'table'=>$_POST['viewname']) ), $completed);
				break;

			//- Drop table (=table_drop)
			case "table_drop":
				$query1 = "DROP TABLE ".$db->quote_id($_GET['table']).";";
				$result1=$db->query($query1);
				if($result1 === false)
					$completed = $db->getError(true);
				if(isset($_POST['vacuum']) && $_POST['vacuum'])
				{
					$query2 = "VACUUM;";
					$result2 = $db->query($query2);
				}
				else
					$query2 = "";
				if($result1 !== false)
				{
					$target_table = null;
					$completed = $lang['tbl']." '".htmlencode($_GET['table'])."' ".$lang['dropped'].".<br/><span style='font-size:11px;'>".htmlencode($query1)."<br />".htmlencode($query2)."</span>";;
				}
				$params->redirect(array('table'=>null), $completed);
				break;

			//- Drop view (=view_drop)
			case "view_drop":
				$query = "DROP VIEW ".$db->quote_id($_POST['viewname']);
				$result=$db->query($query);
				if($result === false)
					$completed = $db->getError(true);
				else
					$completed = $lang['view']." '".htmlencode($_POST['viewname'])."' ".$lang['dropped'].".<br/><span style='font-size:11px;'>".htmlencode($query)."</span>";
				$params->redirect(array('table'=>null), $completed);
				break;

			//- Rename table (=table_rename)
			case "table_rename":
				$query = "ALTER TABLE ".$db->quote_id($_GET['table'])." RENAME TO ".$db->quote($_POST['newname']);
				$type = $db->getTypeOfTable($_GET['table']);
				if($db->getVersion()==3 && $type=='table' // SQLite 3 can rename tables, not views 
					// In SQL(ite) table names are case-insensitve, so changing is not supported by SQLite.
					// But table names are stored and displayed case sensitive, so we use the workaround for case sensitive renaming.
					&& !($_GET['table'] !== $_POST['newname'] && strtolower($_GET['table']) === strtolower($_POST['newname']))
					)
					$result = $db->query($query, true);
				else
					// Workaround can rename tables of sqlite2 and views of both sqlite versions. Can also do case sensitive renames. 
					$result = $db->query($query, false); 
				if($result === false)
					$completed = $db->getError(true);
				else
				{
					$completed = $lang['tbl']." '".htmlencode($_GET['table'])."' ".$lang['renamed']." '".htmlencode($_POST['newname'])."'.<br/><span style='font-size:11px;'>".htmlencode($query)."</span>";
					$target_table = $_POST['newname'];
				}
				$params->redirect(array('action'=>'row_view', 'table'=>$_POST['newname']), $completed);
				break;

			//- Search table (=table_search)
			case "table_search":
				$searchValues = array();
				$searchOperators = array();

				$tableInfo = $db->getTableInfo($target_table);
				$j = 0;
				$whereExpr = array();
				for($i=0; $i<sizeof($tableInfo); $i++)
				{
					$field = $tableInfo[$i][1];
					$operator = $_POST['field_'.$i.'_operator'];
					$searchOperators[$field] = $operator;
					$value = $_POST['field_'.$i.'_value'];
					if($value!="" || $operator=="!= ''" || $operator=="= ''" || $operator == 'IS NULL' || $operator == 'IS NOT NULL')
					{
						if($operator=="= ''" || $operator=="!= ''" || $operator == 'IS NULL' || $operator == 'IS NOT NULL')
							$whereExpr[$j] = $db->quote_id($field)." ".$operator;
						else{
							if($operator == "LIKE%"){
								$operator = "LIKE";
								if(!preg_match('/(^%)|(%$)/', $value)) $value = '%'.$value.'%';
								$searchValues[$field] = array($value);
								$valueQuoted = $db->quote($value);
							}
							elseif($operator == 'IN' || $operator == 'NOT IN')
							{
								$value = trim($value, '() ');
								$values = explode(',',$value);
								$values = array_map('trim', $values, array_fill(0,count($values),' \'"'));
								if($operator == 'IN')
									$searchValues[$field] = $values;
								$values = array_map(array($db, 'quote'), $values);
								$valueQuoted = '(' .implode(', ', $values) . ')';
							}
							else
							{
								$searchValues[$field] = array($value);
								$valueQuoted = $db->quote($value);
							}
							$whereExpr[$j] = $db->quote_id($field)." ".$operator." ".$valueQuoted;
						}
						$j++;
					}
				}
				$searchWhere = '';
				if(sizeof($whereExpr)>0)
				{
					$searchWhere .= " WHERE ".$whereExpr[0];
					for($i=1; $i<sizeof($whereExpr); $i++)
					{
						$searchWhere .= " AND ".$whereExpr[$i];
					}
				}
				$searchID = md5($searchWhere);
				$_SESSION[COOKIENAME.'search'][$searchID] = array(
					'where' => $searchWhere,
					'values' => $searchValues,
					'operators' => $searchOperators
					);
				$params->redirect(array('action'=>'table_search','search'=>$searchID));
			break;

		//- Row actions

			//- Create row (=row_create)
			case "row_create":
				$completed = "";
				$num = $_POST['newRows'];
				$z = 0;
				$error = false;

				$tableInfo = $db->getTableInfo($target_table);

				for($i=0; $i<$num; $i++)
				{
					if(!isset($_POST[$i.":ignore"]))
					{
						$query_cols = "";
						$query_vals = "";
						$all_default = true;
						for($j=0; $j<sizeof($tableInfo); $j++)
						{
							$null = isset($_POST[$j."_null"][$i]);
							$type = strtoupper($tableInfo[$j]['type']);
							$typeAffinity = get_type_affinity($type);
							if(!$null && isset($_POST[$i.":".$j]))
								$value = $_POST[$i.":".$j];
							else
								$value = "";
							if(preg_match('/^BLOB/', $type))
							{
								if($_FILES[$i.":".$j]["error"] == UPLOAD_ERR_OK && is_file($_FILES[$i.":".$j]["tmp_name"]))
									$blobFiles[$j] = $_FILES[$i.":".$j]["tmp_name"];
								else
									$blobFiles[$j] = null;
							}
							elseif($value===$tableInfo[$j]['dflt_value'])
							{
								// if the value is the default value, skip it
								continue;
							}
							$all_default = false;
							$query_cols .= $db->quote_id($tableInfo[$j]['name']).",";

							$function = $_POST["function_".$j][$i];
							if($function!="")
								$query_vals .= $function."(";
							if(preg_match('/^BLOB/', $type))
								$query_vals .= ':blobval'.$j;
							elseif(($typeAffinity=="TEXT" || $typeAffinity=="NONE") && !$null)
								$query_vals .= $db->quote($value);
							elseif(($typeAffinity=="INTEGER" || $typeAffinity=="REAL"|| $typeAffinity=="NUMERIC") && $value=="")
								$query_vals .= "NULL";
							elseif($null)
								$query_vals .= "NULL";
							else
								$query_vals .= $db->quote($value);
							if($function!="")
								$query_vals .= ")";
							$query_vals .= ",";
						}
						$query = "INSERT INTO ".$db->quote_id($target_table);
						if(!$all_default)
						{
							$query_cols = substr($query_cols, 0, strlen($query_cols)-1);
							$query_vals = substr($query_vals, 0, strlen($query_vals)-1);

							$query.=" (". $query_cols . ") VALUES (". $query_vals. ")";
						} else {
							$query .= " DEFAULT VALUES";
						}
						if(isset($blobFiles))
						{
							// blob files need to be done using a prepared statement because the query size would be too large
							$handle = $db->prepareQuery($query);
							foreach($blobFiles as $j=>$filename)
								$db->bindValue($handle, ':blobval'.$j, file_get_contents($filename), 'blob');

							$result1 = $db->executePrepared($handle, false);
						}
						else
							$result1 = $db->query($query);
						if($result1===false)
							$error = true;
						$completed .= "<span style='font-size:11px;'>".htmlencode($query)."</span><br/>";
						$z++;
					}
				}
				if($error)
					$completed = $db->getError(true);
				else
					$completed = $z." ".$lang['rows']." ".$lang['inserted'].".<br/><br/>".$completed;
				$params->redirect(array('action'=>'row_view'), $completed);
				break;

			//- Delete row (=row_delete)
			case "row_delete":
				$pks = json_decode($_GET['pk']);

				$query = "DELETE FROM ".$db->quote_id($target_table)." WHERE (".$db->wherePK($target_table,json_decode($pks[0])).")";
				for($i=1; $i<sizeof($pks); $i++)
				{
					$query .= " OR (".$db->wherePK($target_table,json_decode($pks[$i])).")";
				}
				$result = $db->query($query);
				if($result === false)
					$completed = $db->getError(true);
				else
					$completed = sizeof($pks)." ".$lang['rows']." ".$lang['deleted'].".<br/><span style='font-size:11px;'>".htmlencode($query)."</span>";
				$params->redirect(array('action'=>'row_view'), $completed);
				break;

			//- Edit row (=row_edit)
			case "row_edit":
				$pks = json_decode($_GET['pk']);
				$z = 0;

				$tableInfo = $db->getTableInfo($target_table);

				if(isset($_POST['new_row']))
					$completed = "";
				else
					$completed = sizeof($pks)." ".$lang['rows']." ".$lang['affected'].".<br/><br/>";

				for($i=0; $i<sizeof($pks); $i++)
				{
					if(isset($_POST['new_row']))
					{
						$query_cols = "";
						$query_vals = "";
						$all_default = true;
						for($j=0; $j<sizeof($tableInfo); $j++)
						{
							$null = isset($_POST[$j."_null"][$i]);
							$type = strtoupper($tableInfo[$j]['type']);
							$typeAffinity = get_type_affinity($type);
							if(!$null)
							{
								if(preg_match('/^BLOB/', $type))
								{
									if(isset($_POST["row_".$i."_field_".$j."_blob_use"]) && $_POST["row_".$i."_field_".$j."_blob_use"]=='old')
									{
										$select = 'SELECT '.$db->quote_id($tableInfo[$j]['name']).' AS \'blob\' FROM '.$db->quote_id($target_table).' WHERE '.$db->wherePK($target_table, json_decode($pks[$i]));
										$bl = $db->select($select);
										$blobFiles[$j] = $bl['blob'];
										unset($bl);
									}
									else
									{
										if($_FILES[$i.":".$j]["error"] == UPLOAD_ERR_OK && is_file($_FILES[$i.":".$j]["tmp_name"]))
											$blobFiles[$j] = file_get_contents($_FILES[$i.":".$j]["tmp_name"]);
										else
											$blobFiles[$j] = null;
									}
								}
								else
									$value = $_POST[$j][$i];
							}
							else
								$value = "";
							if(!preg_match('/^BLOB/', $type) && $value===$tableInfo[$j]['dflt_value'])
							{
								// if the value is the default value, skip it
								continue;
							}
							$all_default = false;
							$query_cols .= $db->quote_id($tableInfo[$j]['name']).",";

							$function = $_POST["function_".$j][$i];
							if($function!="")
								$query_vals .= $function."(";

							if(preg_match('/^BLOB/', $type))
								$query_vals .= ':blobval'.$j;
							elseif(($typeAffinity=="TEXT" || $typeAffinity=="NONE") && !$null)
								$query_vals .= $db->quote($value);
							elseif(($typeAffinity=="INTEGER" || $typeAffinity=="REAL"|| $typeAffinity=="NUMERIC") && $value=="")
								$query_vals .= "NULL";
							elseif($null)
								$query_vals .= "NULL";
							else
								$query_vals .= $db->quote($value);
							if($function!="")
								$query_vals .= ")";
							$query_vals .= ",";
						}
						$query = "INSERT INTO ".$db->quote_id($target_table);
						if(!$all_default)
						{
							$query_cols = substr($query_cols, 0, strlen($query_cols)-1);
							$query_vals = substr($query_vals, 0, strlen($query_vals)-1);

							$query.=" (". $query_cols . ") VALUES (". $query_vals. ")";
						} else {
							$query .= " DEFAULT VALUES";
						}

						if(isset($blobFiles))
						{
							// blob files need to be done using a prepared statement because the query size would be too large
							$handle = $db->prepareQuery($query);
							foreach($blobFiles as $j=>$blobval)
								$db->bindValue($handle, ':blobval'.$j, $blobval, 'blob');

							$result1 = $db->executePrepared($handle, false);
						}
						else
							$result1 = $db->query($query);
						if($result1===false)
							$error = true;
						$z++;
					}
					else
					{
						$query = "UPDATE ".$db->quote_id($target_table)." SET ";
						for($j=0; $j<sizeof($tableInfo); $j++)
						{
							$type = strtoupper($tableInfo[$j]['type']);
							$function = $_POST["function_".$j][$i];
							$null = isset($_POST[$j."_null"][$i]);
							// if the old BLOB value is chosen to be kept, just skip this column
							if(!$null && preg_match('/^BLOB/', $type) && isset($_POST["row_".$i."_field_".$j."_blob_use"]) && $_POST["row_".$i."_field_".$j."_blob_use"]=='old')
								continue;
							if(!$null && preg_match('/^BLOB/', $type))
							{
								if($_FILES[$i.":".$j]["error"] == UPLOAD_ERR_OK && is_file($_FILES[$i.":".$j]["tmp_name"]))
									$blobFiles[$j] = $_FILES[$i.":".$j]["tmp_name"];
								else
									$blobFiles[$j] = null;
							}

							$query .= $db->quote_id($tableInfo[$j]['name'])."=";
							if($function!="")
								$query .= $function."(";
							if($null)
								$query .= "NULL";
							else
							{
								if(preg_match('/^BLOB/', $type))
									$query .= ':blobval'.$j;
								else
									$query .= $db->quote($_POST[$j][$i]);
							}
							if($function!="")
								$query .= ")";
							$query .= ", ";
						}
						$query = substr($query, 0, -2);
						$query .= " WHERE ".$db->wherePK($target_table, json_decode($pks[$i]));
						if(isset($blobFiles))
						{
							// blob files need to be done using a prepared statement because the query size would be too large
							$handle = $db->prepareQuery($query);
							foreach($blobFiles as $j=>$filename)
								$db->bindValue($handle, ':blobval'.$j, file_get_contents($filename), 'blob');

							$result1 = $db->executePrepared($handle, false);
						}
						else
							$result1 = $db->query($query);
						if($result1===false)
						{
							$error = true;
						}
					}
					$completed .= "<span style='font-size:11px;'>".htmlencode($query)."</span><br/>";
				}
				if($error)
					$completed = $db->getError(true);
				elseif(isset($_POST['new_row']))
					$completed = $z." ".$lang['rows']." ".$lang['inserted'].".<br/><br/>".$completed;
				$params->redirect(array('action'=>'row_view'), $completed);
				break;


			case "row_get_blob":
				$blobVal = $db->select("SELECT ".$db->quote_id($_GET['column'])." AS 'blob' FROM ".$db->quote_id($target_table)." WHERE ".$db->wherePK($target_table, json_decode($_GET['pk'])));
				$filename = 'download';
				if(function_exists('getimagesizefromstring'))     // introduced in PHP 5.4.0
					$imagesize = getimagesizefromstring($blobVal['blob']);
				if(isset($imagesize) && $imagesize!==false && isset($imagesize['mime']))
					$mimetype = $imagesize['mime'];
				elseif(class_exists('finfo'))  // included since php 5.3.0, but might be disabled on Windows
				{
					$finfo    = new finfo(FILEINFO_MIME);
					$mimetype = $finfo->buffer($blobVal['blob']);
				}
				else
					$mimetype = "application/octet-stream";

				if(isset($imagesize) && $imagesize!==false && isset($imagesize[2]))
					$extension = image_type_to_extension($imagesize[2]);
				else
					$extension = '.blob';
				ob_end_clean();
				header('Content-Length: '.strlen($blobVal['blob']));
				header("Content-type: ".$mimetype);
				if(isset($_GET['download_blob']) && $_GET['download_blob'])
					header('Content-Disposition: attachment; filename="'.$filename.$extension.'";');
				header("Pragma: no-cache");
				header("Expires: 0");
				echo $blobVal['blob'];
				exit;
				break;


		//- Column actions

			//- Create column (=column_create)
			case "column_create":
				$num = intval($_POST['rows']);
				for($i=0; $i<$num; $i++)
				{
					if($_POST[$i.'_field']!="")
					{
						$query = "ALTER TABLE ".$db->quote_id($target_table)." ADD ".$db->quote($_POST[$i.'_field'])." ";
						$query .= $_POST[$i.'_type']." ";
						if(isset($_POST[$i.'_primarykey']))
							$query .= "PRIMARY KEY ";
						if(isset($_POST[$i.'_notnull']))
							$query .= "NOT NULL ";
						if($_POST[$i.'_defaultoption']!='defined' && $_POST[$i.'_defaultoption']!='none' && $_POST[$i.'_defaultoption']!='expr')
							$query .= "DEFAULT ".$_POST[$i.'_defaultoption']." ";
						elseif($_POST[$i.'_defaultoption']=='expr')
							$query .= "DEFAULT (".$_POST[$i.'_defaultvalue'].") ";
						elseif(isset($_POST[$i.'_defaultvalue']) && $_POST[$i.'_defaultoption']=='defined')
						{
							$typeAffinity = get_type_affinity($_POST[$i.'_type']);
							if(($typeAffinity=="INTEGER" || $typeAffinity=="REAL" || $typeAffinity=="NUMERIC") && is_numeric($_POST[$i.'_defaultvalue']))
								$query .= "DEFAULT ".$_POST[$i.'_defaultvalue']."  ";
							else
								$query .= "DEFAULT ".$db->quote($_POST[$i.'_defaultvalue'])." ";
						}
						if($db->getVersion()==3 &&
							($_POST[$i.'_defaultoption']=='defined' || $_POST[$i.'_defaultoption']=='none' || $_POST[$i.'_defaultoption']=='NULL')
							// Sqlite3 cannot add columns with default values that are not constant
							&& !isset($_POST[$i.'_primarykey'])
							// sqlite3 cannot add primary key columns
							&& (!isset($_POST[$i.'_notnull']) || $_POST[$i.'_defaultoption']!='none')
							// SQLite3 cannot add NOT NULL columns without DEFAULT even if the table is empty
							)
							// use SQLITE3 ALTER TABLE ADD COLUMN
							$result = $db->query($query, true);
						else
							// use ALTER TABLE workaround
							$result = $db->query($query, false);
						if($result===false)
							$error = true;
					}
				}
				if($error)
					$completed = $db->getError(true);
				else
					$completed = $lang['tbl']." '".htmlencode($target_table)."' ".$lang['altered'].".";
				$params->redirect(array('action'=>'column_view'), $completed);
				break;

			//- Delete column (=column_delete)
			case "column_delete":
				$pks = explode(":", $_GET['pk']);
				$query = "ALTER TABLE ".$db->quote_id($target_table).' DROP '.$db->quote_id($pks[0]);
				for($i=1; $i<sizeof($pks); $i++)
				{
					$query .= ", DROP ".$db->quote_id($pks[$i]);
				}
				$result = $db->query($query);
				if($result === false)
					$completed = $db->getError(true);
				else
					$completed = $lang['tbl']." '".htmlencode($target_table)."' ".$lang['altered'].".";
				$params->redirect(array('action'=>'column_view'), $completed);
				break;

			//- Add a primary key (=primarykey_add)
			case "primarykey_add":
				$pks = explode(":", $_GET['pk']);
				$query = "ALTER TABLE ".$db->quote_id($target_table).' ADD PRIMARY KEY ('.$db->quote_id($pks[0]);
				for($i=1; $i<sizeof($pks); $i++)
				{
					$query .= ", ".$db->quote_id($pks[$i]);
				}
				$query .= ")";
				$result = $db->query($query);
				if($result === false)
					$completed = $db->getError(true);
				else
					$completed = $lang['tbl']." '".htmlencode($target_table)."' ".$lang['altered'].".";
				$params->redirect(array('action'=>'column_view'), $completed);
				break;

			//- Edit column (=column_edit)
			case "column_edit":
				$query = "ALTER TABLE ".$db->quote_id($target_table).' CHANGE '.$db->quote_id($_POST['oldvalue'])." ".$db->quote($_POST['0_field'])." ".$_POST['0_type'];
				$result = $db->query($query);
				if($result === false)
					$completed = $db->getError(true);
				else
					$completed = $lang['tbl']." '".htmlencode($target_table)."' ".$lang['altered'].".";
				$params->redirect(array('action'=>'column_view'), $completed);
				break;

			//- Delete trigger (=trigger_delete)
			case "trigger_delete":
				$query = "DROP TRIGGER ".$db->quote_id($_GET['pk']);
				$result = $db->query($query);
				if($result === false)
					$completed = $db->getError(true);
				else
					$completed = $lang['trigger']." '".htmlencode($_GET['pk'])."' ".$lang['deleted'].".<br/><span style='font-size:11px;'>".htmlencode($query)."</span>";
				$params->redirect(array('action'=>'column_view'), $completed);
				break;

			//- Delete index (=index_delete)
			case "index_delete":
				$query = "DROP INDEX ".$db->quote_id($_GET['pk']);
				$result = $db->query($query);
				if($result === false)
					$completed = $db->getError(true);
				else
					$completed = $lang['index']." '".htmlencode($_GET['pk'])."' ".$lang['deleted'].".<br/><span style='font-size:11px;'>".htmlencode($query)."</span>";
				$params->redirect(array('action'=>'column_view'), $completed);
				break;

			//- Create trigger (=trigger_create)
			case "trigger_create":
				$str = "CREATE TRIGGER ".$db->quote($_POST['trigger_name']);
				if($_POST['beforeafter']!="")
					$str .= " ".$_POST['beforeafter'];
				$str .= " ".$_POST['event']." ON ".$db->quote_id($target_table);
				if(isset($_POST['foreachrow']))
					$str .= " FOR EACH ROW";
				if($_POST['whenexpression']!="")
					$str .= " WHEN ".$_POST['whenexpression'];
				$str .= " BEGIN";
				$str .= " ".$_POST['triggersteps'];
				$str .= " END";
				$query = $str;
				$result = $db->query($query);
				if($result === false)
					$completed = $db->getError(true);
				else
					$completed = $lang['trigger']." ".$lang['created'].".<br/><span style='font-size:11px;'>".htmlencode($query)."</span>";
				$params->redirect(array('action'=>'column_view'), $completed);
				break;

			//- Create index (=index_create)
			case "index_create":
				$num = $_POST['num'];
				if($_POST['name']=="")
				{
					$completed = $lang['blank_index'];
				}
				else if($_POST['0_field']=="")
				{
					$completed = $lang['one_index'];
				}
				else
				{
					$str = "CREATE ";
					if($_POST['duplicate']=="no")
						$str .= "UNIQUE ";
					$str .= "INDEX ".$db->quote($_POST['name'])." ON ".$db->quote_id($target_table)." (";
					$str .= $db->quote_id($_POST['0_field']).$_POST['0_order'];
					for($i=1; $i<$num; $i++)
					{
						if($_POST[$i.'_field']!="")
							$str .= ", ".$db->quote_id($_POST[$i.'_field']).$_POST[$i.'_order'];
					}
					$str .= ")";
					if(isset($_POST['where']) && $_POST['where']!='')
						$str.=" WHERE ".$_POST['where'];
					$query = $str;
					$result = $db->query($query);
					if($result === false)
						$completed = $db->getError(true);
					else
						$completed = $lang['index']." ".$lang['created'].".<br/><span style='font-size:11px;'>".htmlencode($query)."</span>";
				}
				$params->redirect(array('action'=>'column_view'), $completed);
				break;
		}
	}
}

// if not in debug mode, destroy all output until here
if($debug)
	$bufferedOutput = ob_get_contents();
ob_end_clean();

//- HTML: output starts here
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<!-- Copyright <?php echo date("Y").' '.PROJECT.' ('.PROJECT_URL.')'; ?> -->
<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
<link rel="shortcut icon" href="?resource=favicon" />
<title><?php echo PROJECT ?></title>

<?php
 
// HTML: output help text, then exit
if(isset($_GET['help']))
{
	//help section array
	$help = array($lang['help1'] => sprintf($lang['help1_x'], PROJECT, PROJECT, PROJECT));
	for($i=2; isset($lang['help'.$i]); $i++)
		$help[$lang['help'.$i]]=$lang['help'.$i.'_x'];
	?>
	</head>
	<body style="direction:<?php echo $lang['direction']; ?>;">
	<div id='help_container'>
	<?php
	echo "<div class='help_list'>";
	echo "<span style='font-size:18px;'>".PROJECT." v".VERSION." ".$lang['help_doc']."</span><br/><br/>";
	foreach((array)$help as $key => $val)
	{
		echo "<a href='#".$key."'>".$key."</a><br/>";
	}
	echo "</div>";
	echo "<br/><br/>";
	foreach((array)$help as $key => $val)
	{
		echo "<div class='help_outer'>";
		echo "<a class='headd' name='".$key."'>".$key."</a>";
		echo "<div class='help_inner'>";
		echo $val;
		echo "</div>";
		echo "<a class='help_top' href='#top'>".$lang['back_top']."</a>";
		echo "</div>";
	}
	?>
	</div>
	</body>
	</html>
	<?php
	exit();
}

if($auth->isAuthorized())
{
	//- Javascript include
	?>
 
	<script type="text/javascript">
	var fileUploadMaxSize = <?php echo fileUploadMaxSize(); ?>;
	var fileUploadMaxSizeErrorMsg = '<?php echo $lang['err'].': \n'.$lang['max_file_size']; ?>';
	</script>
<?php
}
?>
 <script type="text/javascript">

function initAutoincrement()
{var i=0;while(document.getElementById('i'+i+'_autoincrement')!=undefined)
{document.getElementById('i'+i+'_autoincrement').disabled=true;i++;}}
function toggleAutoincrement(i)
{var type=document.getElementById('i'+i+'_type');var primarykey=document.getElementById('i'+i+'_primarykey');var autoincrement=document.getElementById('i'+i+'_autoincrement');if(!autoincrement)return false;if(type.value=='INTEGER'&&primarykey.checked)
autoincrement.disabled=false;else
{autoincrement.disabled=true;autoincrement.checked=false;}}
function toggleNull(i)
{var pk=document.getElementById('i'+i+'_primarykey');var notnull=document.getElementById('i'+i+'_notnull');if(pk.checked)
{notnull.disabled=true;notnull.checked=true;}
else
{notnull.disabled=false;}}
function checkAll(field)
{var i=0;while(document.getElementById('check_'+i)!=undefined)
{document.getElementById('check_'+i).checked=true;i++;}}
function uncheckAll(field)
{var i=0;while(document.getElementById('check_'+i)!=undefined)
{document.getElementById('check_'+i).checked=false;i++;}}
function changeIgnore(area,e,u)
{if(area.value!="")
{if(document.getElementById(e)!=undefined)
document.getElementById(e).checked=false;if(document.getElementById(u)!=undefined)
document.getElementById(u).checked=false;}}
function moveFields()
{var fields=document.getElementById("fieldcontainer");var selected=[];for(var i=0;i<fields.options.length;i++)
if(fields.options[i].selected)
selected.push(fields.options[i].value);for(var i=0;i<selected.length;i++)
{var val='"'+selected[i].replace(/"/g,'""')+'"';if(i<selected.length-1)
val+=', ';sqleditorInsertValue(val);}}
function notNull(checker)
{document.getElementById(checker).checked=false;}
function disableText(checker,textie)
{if(checker.checked)
{document.getElementById(textie).value="";document.getElementById(textie).disabled=true;}
else
{document.getElementById(textie).disabled=false;}}
function toggleExports(val)
{document.getElementById("exportoptions_sql").style.display="none";document.getElementById("exportoptions_csv").style.display="none";document.getElementById("exportoptions_"+val).style.display="block";}
function toggleImports(val)
{document.getElementById("importoptions_sql").style.display="none";document.getElementById("importoptions_csv").style.display="none";document.getElementById("importoptions_"+val).style.display="block";}
function openHelp(section)
{PopupCenter('?help=1#'+section,"Help Section");}
var helpsec=false;function PopupCenter(pageURL,title)
{helpsec=window.open(pageURL,title,"toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=400,height=300");}
function checkLike(srchField,selOpt)
{if(selOpt=="LIKE%"){var textArea=document.getElementById(srchField);textArea.value="%"+textArea.value+"%";}}
function createCORSRequest(method,url)
{var xhr=new XMLHttpRequest();if("withCredentials"in xhr)
{xhr.open(method,url,true);}
else if(typeof XDomainRequest!="undefined")
{xhr=new XDomainRequest();xhr.open(method,url);}
else
{xhr=null;}
return xhr;}
function checkVersion(installed,url)
{var xhr=createCORSRequest('GET',url);if(!xhr)
return false;xhr.onload=function()
{if(xhr.responseText.split("\n").indexOf(installed)==-1)
{document.getElementById('oldVersion').style.display='inline';}};xhr.send();}
var codeEditor;
function sqleditor(textarea,tableDefinitions,tableDefault)
{
 codeEditor=document.getElementById("queryval"); 
}


function sqleditorSetValue(text)
{
	codeEditor.value=text;
}
function sqleditorInsertValue(text)
{
  
  myField = codeEditor;
  myValue = text;

        //IE support
        if (document.selection)
        {
            myField.focus();
            sel            = document.selection.createRange();
            sel.text    = myValue;
            sel.select();
        }
        //MOZILLA/NETSCAPE support
        else if (myField.selectionStart || myField.selectionStart == '0')
        {
            var startPos    = myField.selectionStart;
            var endPos        = myField.selectionEnd;
            // save scrollTop before insert
            var restoreTop    = myField.scrollTop;
            myField.value    = myField.value.substring(0, startPos) + myValue + myField.value.substring(endPos, myField.value.length);
            if (restoreTop > 0)
            {
                // restore previous scrollTop
                myField.scrollTop = restoreTop;
            }
            myField.focus();
            myField.selectionStart    = startPos + myValue.length;
            myField.selectionEnd    = startPos + myValue.length;
        } else {
            myField.value += myValue;
            myField.focus();
        }

}
 
function checkFileSize(input)
{if(input.files&&input.files.length==1)
{if(input.files[0].size>fileUploadMaxSize)
{alert(fileUploadMaxSizeErrorMsg+": "+(fileUploadMaxSize/1024/1024)+" MiB");return false;}}
return true;}
	
</script>


</head> 
<body style="direction:<?php echo $lang['direction']; ?>;">
<?php
// if in debug mode, ouput all output that has been generated above now
if($debug)
	echo $bufferedOutput;

if(ini_get("register_globals") == "on" || ini_get("register_globals")=="1") //check whether register_globals is turned on - if it is, we need to not continue
{
	echo "<div class='confirm' style='margin:20px;'>".$lang['bad_php_directive']."</div>";
	echo "</body></html>";
	exit();
}

//- HTML: login screen if not authorized, exit
if(!$auth->isAuthorized())
{
	echo "<div id='loginBox'>";
	echo "<h1><span id='logo'>".PROJECT."</span> <span id='version'>v".VERSION."</span></h1>";
	echo "<div style='padding:15px; text-align:center;'>";
	if (isset($_GET['failed']))
		echo "<span class='warning'>".$lang['passwd_incorrect']."</span><br/><br/>";
	echo $params->getForm();
	echo $lang['passwd'].": <input type='password' name='password' autofocus='autofocus'/><br/>";
	echo "<label><input type='checkbox' name='remember' value='yes' checked='checked'/> ".$lang['remember']."</label><br/><br/>";
	echo "<input type='submit' value='".$lang['login']."' class='btn'/>";
	echo "<input type='hidden' name='login' value='true' />";
	echo "</form>";
	echo "</div>";
	echo "</div>";
	echo "<br/>";
	echo "<div style='text-align:center;'>";
	echo "<span style='font-size:11px;'>".$lang['powered']." <a href='".PROJECT_URL."' target='_blank' style='font-size:11px;'>".PROJECT."</a> | ";
	printf($lang['page_gen'], $pageTimer);
	echo "</span></div>";
	echo "</body></html>";
	exit();
}

//- User is authorized, display the main application

if(count($databases)==0) // the database array is empty, offer to create a new database
{
	//- HTML: form to create a new database, exit
	if($directory!==false && is_writable($directory))
	{
		echo "<div class='confirm' style='margin:20px;'>";
		printf($lang['no_db'], PROJECT, PROJECT);
		echo "</div>";
		//if the user has performed some action, show the resulting message
		if(isset($_GET['message']) && isset($_SESSION[COOKIENAME.'messages'][$_GET['message']]))
		{
			echo "<div class='confirm' style='margin:10px 20px;'>";
			echo $_SESSION[COOKIENAME.'messages'][$_GET['message']];
			echo "</div><br />";
			unset($_SESSION[COOKIENAME.'messages'][$_GET['message']]);
		}
		echo "<fieldset style='margin:15px;'><legend><b>".$lang['db_create']."</b></legend>";
		echo $params->getForm(array('table'=>null), 'post', false, 'create_database');
		echo "<input type='text' name='new_dbname' style='width:150px;'/> ";
		if(class_exists('SQLiteDatabase') && (class_exists('SQLite3') || class_exists('PDO')))
		{
			echo "<select name='new_dbtype' class='newDbType'>";
			echo "<option value='3'>SQLite 3</option>";
			echo "<option value='2'>SQLite 2</option>";
			echo "</select>";
		}
		echo "<input type='submit' value='".$lang['create']."' class='btn'/>";
		echo "</form>";
		echo "</fieldset>";
	}
	elseif(($directory!==false && !is_executable($directory)))
	{
		echo "<div class='confirm' style='margin:20px;'>";
		echo $lang['err'].": ".sprintf($lang['dir_not_executable'], PROJECT, $directory);
		echo "</div><br/>";
	}
	else
	{
		echo "<div class='confirm' style='margin:20px;'>";
		echo $lang['err'].": ".sprintf($lang['no_db2'], PROJECT);
		echo "</div><br/>";
	}
	exit();
}

//- HTML: sidebar
echo '<table class="body_tbl" width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td valign="top" class="left_td" style="width:100px; padding:9px 2px 9px 9px;">';
echo "<div id='leftNav'>";
// echo "<h1><a href='".$params->getURL()."'>";
// echo "<span id='logo'>".PROJECT."</span> <span id='version'>v".VERSION."</span>";
// echo "</a></h1>";
// echo "<div id='headerlinks'>";
// echo "<a href='javascript:void' onclick='openHelp(\"top\");'>".$lang['docu']."</a> | ";
// echo "<a href='https://www.gnu.org/licenses/gpl.html' target='_blank'>".$lang['license']."</a> | ";
// echo "<a href='".PROJECT_URL."' target='_blank'>".$lang['proj_site']."</a>";
// echo "</div>";

//- HTML: database list
$db->print_db_list();
echo "<fieldset style='margin:15px;'><legend>";
echo "<a href='".$params->getURL(array('table'=>null))."'";
if (!$target_table)
	echo " class='active_table'";
$name = $currentDB['name'];
if(strlen($name)>25)
	$name = "...".substr($name, strlen($name)-22, 22);
echo ">".htmlencode($name)."</a>";
echo "</legend>";

//- HTML: table list
$tables = $db->getTables(true, false);
foreach($tables as $tableName => $tableType)
{
	echo "<span class='sidebar_table'>";
	echo $params->getLink(array('action'=>'column_view', 'table'=>$tableName), "[".$lang[$tableType=='table'?'tbl':'view']."]");
	echo "</span> ";
	echo $params->getLink(array('action'=>'row_view', 'table'=>$tableName), htmlencode($tableName),
		($target_table == $tableName ? 'active_table' : '') );
	echo "<br/>";
}
if(count($tables)==0)
	echo $lang['no_tbl'];
echo "</fieldset>";

//- HTML: form to create a new database
if($directory!==false && is_writable($directory))
{
	echo "<fieldset style='margin:15px;'><legend><b>".$lang['db_create']."</b> ".helpLink($lang['help2'])."</legend>";
	echo $params->getForm(array('table'=>null), 'post', false, 'create_database');
	echo "<input type='text' name='new_dbname' style='width:150px;'/>";
	if(class_exists('SQLiteDatabase') && (class_exists('SQLite3') || class_exists('PDO')))
	{
		echo "<select name='new_dbtype' class='newDbType'>";
		echo "<option value='3'>SQLite 3</option>";
		echo "<option value='2'>SQLite 2</option>";
		echo "</select>";
	}
	echo "<input type='submit' value='".$lang['create']."' class='btn'/>";
	echo "</form>";
	echo "</fieldset>";
}

echo "<div style='text-align:center;'>";
echo $params->getForm(array(),'get');
echo "<input type='submit' value='".$lang['logout']."' name='logout' class='btn'/>";
echo "</form>";
echo "</div>";
echo "</div>";
echo '</td><td valign="top" id="main_column" class="right_td" style="padding:9px 2px 9px 9px;">';

//- HTML: breadcrumb navigation
echo $params->getLink(array('table'=>null), htmlencode($currentDB['name']));
if ($target_table)
	echo " &rarr; ".$params->getLink(array('action'=>'row_view'), htmlencode($target_table));
echo "<br/><br/>";

//- Show the various tab views for a table
if($target_table)
{
	//- HTML: tabs
	echo $params->getLink(array('action'=>'row_view'), $lang['browse'],
		(in_array($_GET['action'], array('row_view', 'row_editordelete') ) ? 'tab_pressed' : 'tab'));

	echo $params->getLink(array('action'=>'column_view'), $lang['struct'],
		(in_array($_GET['action'], array('column_view', 'column_edit', 'column_confirm', 'primarykey_add', 'column_create', 'index_create', 'index_delete', 'trigger_create', 'trigger_delete') ) ? 'tab_pressed' : 'tab'));

	echo $params->getLink(array('action'=>'table_sql'), $lang['sql'],
		($_GET['action']=="table_sql" ? 'tab_pressed' : 'tab'));

	echo $params->getLink(array(
		'action' => 'table_search',
		'oldSearch' => (isset($_GET['search'])?$_GET['search']:null)
		), $lang['srch'], ($_GET['action']=="table_search" ? 'tab_pressed' : 'tab'));

	if($target_table_type == 'table' && $db->isWritable() && $db->isDirWritable())
		echo $params->getLink(array('action'=>'row_create'), $lang['insert'],
			($_GET['action']=="row_create" ? 'tab_pressed' : 'tab'));

	echo $params->getLink(array('action'=>'table_export'), $lang['export'],
		($_GET['action']=="table_export" ? 'tab_pressed' : 'tab'));

	if($target_table_type == 'table' && $db->isWritable() && $db->isDirWritable())
		echo $params->getLink(array('action'=>'table_import'), $lang['import'],
			($_GET['action']=="table_import" ? 'tab_pressed' : 'tab'));

	if($db->isWritable() && $db->isDirWritable())
		echo $params->getLink(array('action'=>'table_rename'), $lang['rename'],
			($_GET['action']=="table_rename" ? 'tab_pressed' : 'tab'));

	if($target_table_type == 'table' && $db->isWritable() && $db->isDirWritable())
	{
		echo $params->getLink(array('action'=>'table_empty'), $lang['empty'],
			($_GET['action']=="table_empty" ? 'tab_pressed empty' : 'tab empty'));

		echo $params->getLink(array('action'=>'table_drop'), $lang['drop'],
			($_GET['action']=="table_drop" ? 'tab_pressed drop' : 'tab drop'));
	} elseif($db->isWritable() && $db->isDirWritable()) {
		echo $params->getLink(array('action'=>'view_drop'), $lang['drop'],
			($_GET['action']=="view_drop" ? 'tab_pressed drop' : 'tab drop'));
	}
}
else
//- Show the various tab views for a database
{
	$view = isset($_GET['view']) ? $_GET['view'] : 'structure';

	echo $params->getLink(array('view'=>'structure'), $lang['struct'], ($view=="structure" ? 'tab_pressed': 'tab')  );

	echo $params->getLink(array('view'=>'sql'), $lang['sql'], ($view=="sql" ? 'tab_pressed': 'tab')  );

	echo $params->getLink(array('view'=>'export'), $lang['export'], ($view=="export" ? 'tab_pressed': 'tab')  );

	if($db->isWritable() && $db->isDirWritable())
		echo $params->getLink(array('view'=>'import'), $lang['import'], ($view=="import" ? 'tab_pressed': 'tab')  );

	if($db->isWritable() && $db->isDirWritable())
		echo $params->getLink(array('view'=>'vacuum'), $lang['vac'], ($view=="vacuum" ? 'tab_pressed': 'tab')  );

	if($directory!==false && is_writable($directory))
	{

		echo $params->getLink(array('view'=>'rename'), $lang['db_rename'], ($view=="rename" ? 'tab_pressed': 'tab')  );

		echo $params->getLink(array('view'=>'delete'), "<span>".$lang['db_del']."</span>", ($view=="delete" ? 'tab_pressed delete_db': 'tab delete_db')  );
	}
}

echo "<div style='clear:both;'></div>";
echo "<div id='main'>";

//- HTML: confirmation panel
//if the user has performed some action, show the resulting message
if(isset($_GET['message']) && isset($_SESSION[COOKIENAME.'messages'][$_GET['message']]))
{
	echo "<div class='confirm'>";
	echo $_SESSION[COOKIENAME.'messages'][$_GET['message']];
	echo "</div><br />";
	unset($_SESSION[COOKIENAME.'messages'][$_GET['message']]);
}


//- Switch on $_GET['action'] for operations with output
if(isset($_GET['action']) && !isset($_GET['confirm']))
{
	switch($_GET['action'])
	{
	//- Table actions

		//- Create table (=table_create)
		case "table_create":
			$query = "SELECT name FROM sqlite_master WHERE type='table' AND name=".$db->quote($_GET['tablename']);
			$results = $db->selectArray($query);
			if(sizeof($results)>0)
				$exists = true;
			else
				$exists = false;
			echo "<h2>".$lang['create_tbl'].": '".htmlencode($_GET['tablename'])."'</h2>";
			if($_GET['tablefields']=="" || intval($_GET['tablefields'])<=0)
				echo $lang['specify_fields'];
			else if($_GET['tablename']=="")
				echo $lang['specify_tbl'];
			else if($exists)
				echo $lang['tbl_exists'];
			else
			{
				$num = intval($_GET['tablefields']);
				$name = $_GET['tablename'];
				echo $params->getForm(array('action'=>'table_create', 'confirm'=>'1'));
				echo "<input type='hidden' name='tablename' value='".htmlencode($name)."'/>";
				echo "<input type='hidden' name='rows' value='".$num."'/>";
				echo "<table border='0' cellpadding='2' cellspacing='1' class='viewTable'>";
				echo "<tr>";
				$headings = array($lang['fld'], $lang['type'], $lang['prim_key']);
				if($db->getType() != "SQLiteDatabase") $headings[] = $lang['autoincrement'];
				$headings[] = $lang['not_null'];
				$headings[] = $lang['def_val'];
				for($k=0; $k<count($headings); $k++)
					echo "<td class='tdheader'>" . $headings[$k] . "</td>";
				echo "</tr>";

				for($i=0; $i<$num; $i++)
				{
					$tdWithClass = "<td class='td" . ($i%2 ? "1" : "2") . "'>";
					echo "<tr>";
					echo $tdWithClass;
					echo "<input type='text' name='".$i."_field' style='width:200px;'/>";
					echo "</td>";
					echo $tdWithClass;
					echo "<select name='".$i."_type' id='i".$i."_type' onchange='toggleAutoincrement(".$i.");'>";
					foreach ($sqlite_datatypes as $t) {
						echo "<option value='".htmlencode($t)."'>".htmlencode($t)."</option>";
					}
					echo "</select>";
					echo "</td>";
					echo $tdWithClass;
					echo "<label><input type='checkbox' name='".$i."_primarykey' id='i".$i."_primarykey' onclick='toggleNull(".$i."); toggleAutoincrement(".$i.");'/> ".$lang['yes']."</label>";
					echo "</td>";
					if($db->getType() != "SQLiteDatabase")
					{
						echo $tdWithClass;
						echo "<label><input type='checkbox' name='".$i."_autoincrement' id='i".$i."_autoincrement'/> ".$lang['yes']."</label>";
						echo "</td>";
					}
					echo $tdWithClass;
					echo "<label><input type='checkbox' name='".$i."_notnull' id='i".$i."_notnull'/> ".$lang['yes']."</label>";
					echo "</td>";
					echo $tdWithClass;
					echo "<select name='".$i."_defaultoption' id='i".$i."_defaultoption' onchange=\"if(this.value!='defined' && this.value!='expr') document.getElementById('i".$i."_defaultvalue').value='';\">";
					echo "<option value='none'>".$lang['none']."</option><option value='defined'>".$lang['as_defined'].":</option><option>NULL</option><option>CURRENT_TIME</option><option>CURRENT_DATE</option><option>CURRENT_TIMESTAMP</option><option value='expr'>".$lang['expression'].":</option>";
					echo "</select>";
					echo "<input type='text' name='".$i."_defaultvalue' id='i".$i."_defaultvalue' style='width:100px;' onchange=\"if(document.getElementById('i".$i."_defaultoption').value!='expr') document.getElementById('i".$i."_defaultoption').value='defined';\"/>";
					echo "</td>";
					echo "</tr>";
				}
				echo "<tr>";
				echo "<td class='tdheader' style='text-align:right;' colspan='6'>";
				echo "<input type='submit' value='".$lang['create']."' class='btn'/> ";
				echo $params->getLink(array(), $lang['cancel']);
				echo "</td>";
				echo "</tr>";
				echo "</table>";
				echo "</form>";
				if($db->getType() != "SQLiteDatabase") echo "<script type='text/javascript'>window.onload=initAutoincrement;</script>";
			}
			break;

		//- Perform SQL query on table (=table_sql)
		case "table_sql":
			if(isset($_POST['query']) && $_POST['query']!="")
			{
				$delimiter = $_POST['delimiter'];
				$queryStr = $_POST['queryval'];
				//save the queries in history if necessary
				if($maxSavedQueries!=0 && $maxSavedQueries!=false)
				{
					if(!isset($_SESSION[COOKIENAME.'query_history']))
						$_SESSION[COOKIENAME.'query_history'] = array();
					$_SESSION[COOKIENAME.'query_history'][md5(strtolower($queryStr))] = $queryStr;
					if(sizeof($_SESSION[COOKIENAME.'query_history']) > $maxSavedQueries)
						array_shift($_SESSION[COOKIENAME.'query_history']);
				}
				$query = explode_sql($delimiter, $queryStr); //explode the query string into individual queries based on the delimiter

				for($i=0; $i<sizeof($query); $i++) //iterate through the queries exploded by the delimiter
				{
					if(str_replace(" ", "", str_replace("\n", "", str_replace("\r", "", $query[$i])))!="") //make sure this query is not an empty string
					{
						$queryTimer = new MicroTimer();
						$table_result = $db->query($query[$i]);

						echo "<div class='confirm'>";
						echo "<b>".htmlencode($query[$i])."</b>";
						if($table_result === NULL || $table_result === false)
						{
							echo "<br /><b>".$lang['err'].": ".htmlencode($db->getError())."</b></div>";
						}
						echo "</div><br/>";
						if($row = $db->fetch($table_result, 'num'))
						{
							for($j=0; $j<sizeof($row);$j++)
								$headers[$j] = $db->getColumnName($table_result,$j);
							echo "<table border='0' cellpadding='2' cellspacing='1' class='viewTable'>";
							echo "<tr>";
							for($j=0; $j<sizeof($headers); $j++)
							{
								echo "<td class='tdheader'>";
								echo htmlencode($headers[$j]);
								echo "</td>";
							}
							echo "</tr>";
							$rowCount = 0;
							for(; $rowCount==0 || $row = $db->fetch($table_result, 'num'); $rowCount++)
							{
								$tdWithClass = "<td class='td".($rowCount%2 ? "1" : "2")."'>";
								echo "<tr>";
								for($z=0; $z<sizeof($headers); $z++)
								{
									echo $tdWithClass;
									if($row[$z]==="")
										echo "&nbsp;";
									elseif($row[$z]===NULL)
										echo "<i class='null'>NULL</i>";
									else
										echo htmlencode(subString($row[$z]));
									echo "</td>";
								}
								echo "</tr>";
							}
							$queryTimer->stop();
							echo "</table><br/><br/>";


							if($table_result !== NULL && $table_result !== false)
							{
								echo "<div class='confirm' style='margin-bottom: 2em'>";
								if($rowCount>0 || $db->getAffectedRows()==0)
								{
									printf($lang['show_rows'], $rowCount);
								}
								if($db->getAffectedRows()>0 || $rowCount==0)
								{
									echo $db->getAffectedRows()." ".$lang['rows_aff']." ";
								}
								printf($lang['query_time'], $queryTimer);
								echo "</div>";
							}


						}
					}
				}
			}
			else
			{
				$delimiter = ";";
				$queryStr = "SELECT * FROM ".$db->quote_id($target_table)." WHERE 1";
			}

			echo "<fieldset>";
			echo "<legend><b>".sprintf($lang['run_sql'],htmlencode($db->getName()))."</b></legend>";
			echo $params->getForm(array('action'=>'table_sql'));
			if(isset($_SESSION[COOKIENAME.'query_history']) && sizeof($_SESSION[COOKIENAME.'query_history'])>0)
			{
				echo "<b>".$lang['recent_queries']."</b><ul>";
				foreach($_SESSION[COOKIENAME.'query_history'] as $key => $value)
					echo "<li><a onclick='sqleditorSetValue(this.textContent); return false;' href='#'>".htmlencode($value)."</a></li>";
				echo "</ul><br/><br/>";
			}
			echo "<div style='float:left; width:70%;'>";
			echo "<textarea style='width:97%; height:300px;' name='queryval' id='queryval' cols='50' rows='8'>".htmlencode($queryStr)."</textarea>";
			echo "<script>sqleditor(document.getElementById('queryval'),".json_encode($db->getTableDefinitions()).",'".htmlencode($target_table)."');</script>";
			echo "</div>";
			echo "<div style='float:left; width:28%; padding-left:10px;'>";
			echo $lang['fields']."<br/>";
			echo "<select multiple='multiple' style='width:100%; height:250px;' id='fieldcontainer'>";
			$tableInfo = $db->getTableInfo($target_table);
			for($i=0; $i<sizeof($tableInfo); $i++)
			{
				echo "<option value='".htmlencode($tableInfo[$i][1])."'>".htmlencode($tableInfo[$i][1])."</option>";
			}
			echo "</select>";
			echo "<input type='button' value='&lt;&lt;' onclick='moveFields();' class='btn'/>";
			echo "</div>";
			echo "<div style='clear:both;'></div>";
			echo $lang['delimit']." <input type='text' name='delimiter' value='".htmlencode($delimiter)."' style='width:50px;'/> ";
			echo "<input type='submit' name='query' value='".$lang['go']."' class='btn'/>";
			echo "</form>";
			echo "</fieldset>";
			break;

		//- Empty table (=table_empty)
		case "table_empty":
			echo $params->getForm(array('action'=>'table_empty','confirm'=>'1'));
			echo "<div class='confirm'>";
			echo sprintf($lang['ques_empty'], htmlencode($target_table))."<br/><br/>";
			echo "<input type='checkbox' name='vacuum' checked='checked'/> ".$lang['vac_on_empty']."<br/><br/>";
			echo "<input type='submit' value='".$lang['confirm']."' class='btn'/> ";
			echo $params->getLink(array('table'=>null), $lang['cancel']);
			echo "</div>";
			break;

		//- Drop table (=table_drop)
		case "table_drop":
			echo $params->getForm(array('action'=>'table_drop','confirm'=>'1'));
			echo "<div class='confirm'>";
			echo sprintf($lang['ques_drop'], htmlencode($target_table))."<br/><br/>";
			echo "<input type='checkbox' name='vacuum' checked='checked'/> ".$lang['vac_on_empty']."<br/><br/>";
			echo "<input type='submit' value='".$lang['confirm']."' class='btn'/> ";
			echo $params->getLink(array('table'=>null), $lang['cancel']);
			echo "</div>";
			break;

		//- Drop view (=view_drop)
		case "view_drop":
			echo $params->getForm(array('action'=>'view_drop','confirm'=>'1'));
			echo "<input type='hidden' name='viewname' value='".htmlencode($target_table)."'/>";
			echo "<div class='confirm'>";
			echo sprintf($lang['ques_drop_view'], htmlencode($target_table))."<br/><br/>";
			echo "<input type='submit' value='".$lang['confirm']."' class='btn'/> ";
			echo $params->getLink(array('table'=>null), $lang['cancel']);
			echo "</div>";
			break;

		//- Export table (=table_export)
		case "table_export":
			echo $params->getForm();
			echo "<fieldset style='float:left; width:260px; margin-right:20px;'><legend><b>".$lang['export']."</b></legend>";
			echo "<input type='hidden' value='".htmlencode($target_table)."' name='single_table'/>";
			echo "<label><input type='radio' name='export_type' checked='checked' value='sql' onclick='toggleExports(\"sql\");'/> ".$lang['sql']."</label>";
			echo "<br/><label><input type='radio' name='export_type' value='csv' onclick='toggleExports(\"csv\");'/> ".$lang['csv']."</label>";
			echo "</fieldset>";

			echo "<fieldset style='float:left; max-width:350px;' id='exportoptions_sql'><legend><b>".$lang['options']."</b></legend>";
			echo "<label><input type='checkbox' checked='checked' name='structure'/> ".$lang['export_struct']."</label> ".helpLink($lang['help5'])."<br/>";
			echo "<label><input type='checkbox' checked='checked' name='data'/> ".$lang['export_data']."</label> ".helpLink($lang['help6'])."<br/>";
			echo "<label><input type='checkbox' name='drop'/> ".$lang['add_drop']."</label> ".helpLink($lang['help7'])."<br/>";
			echo "<label><input type='checkbox' checked='checked' name='transaction'/> ".$lang['add_transact']."</label> ".helpLink($lang['help8'])."<br/>";
			echo "<label><input type='checkbox' checked='checked' name='comments'/> ".$lang['comments']."</label> ".helpLink($lang['help9'])."<br/>";
			echo "</fieldset>";

			echo "<fieldset style='float:left; max-width:350px; display:none;' id='exportoptions_csv'><legend><b>".$lang['options']."</b></legend>";
			echo "<div style='float:left;'>".$lang['fld_terminated']."</div>";
			echo "<input type='text' value=';' name='export_csv_fieldsterminated' style='float:right;'/>";
			echo "<div style='clear:both;'></div>";
			echo "<div style='float:left;'>".$lang['fld_enclosed']."</div>";
			echo "<input type='text' value='\"' name='export_csv_fieldsenclosed' style='float:right;'/>";
			echo "<div style='clear:both;'></div>";
			echo "<div style='float:left;'>".$lang['fld_escaped']."</div>";
			echo "<input type='text' value='\' name='export_csv_fieldsescaped' style='float:right;'/>";
			echo "<div style='clear:both;'></div>";
			echo "<div style='float:left;'>".$lang['rep_null']."</div>";
			echo "<input type='text' value='NULL' name='export_csv_replacenull' style='float:right;'/>";
			echo "<div style='clear:both;'></div>";
			echo "<label><input type='checkbox' name='export_csv_crlf'/> ".$lang['rem_crlf']."</label><br/>";
			echo "<label><input type='checkbox' checked='checked' name='export_csv_fieldnames'/> ".$lang['put_fld']."</label>";
			echo "</fieldset>";

			echo "<div style='clear:both;'></div>";
			echo "<br/><br/>";
			echo "<fieldset><legend><b>".$lang['save_as']."</b></legend>";
			$file = pathinfo($db->getPath());
			$name = $file['filename'];
			echo "<input type='text' name='filename' value='".htmlencode($name)."_".htmlencode($target_table)."_".date("Y-m-d").".dump' style='width:400px;'/> <input type='submit' name='export' value='".$lang['export']."' class='btn'/>";
			echo "</fieldset>";
			echo "</form>";
			echo "<div class='confirm' style='margin-top: 2em'>".sprintf($lang['backup_hint'],
				$params->getLink(array('download' => $currentDB['path'], 'token' => $_SESSION[COOKIENAME.'token']), $lang["backup_hint_linktext"], '', $lang['backup']))."</div>";
			break;

		//- Import table (=table_import)
		case "table_import":
			if(isset($_POST['import']))
			{
				echo "<div class='confirm'>";
				if($importSuccess===true)
					echo $lang['import_suc'];
				else
					echo $lang['err'].': '.htmlencode($importSuccess);
				echo "</div><br/>";
			}
			echo $params->getForm(array('action' => 'table_import'), 'post', true);
			echo "<fieldset style='float:left; width:260px; margin-right:20px;'><legend><b>".$lang['import_into']." ".htmlencode($target_table)."</b></legend>";
			echo "<label><input type='radio' name='import_type' checked='checked' value='sql' onclick='toggleImports(\"sql\");'/> ".$lang['sql']."</label>";
			echo "<br/><label><input type='radio' name='import_type' value='csv' onclick='toggleImports(\"csv\");'/> ".$lang['csv']."</label>";
			echo "</fieldset>";

			echo "<fieldset style='float:left; max-width:350px;' id='importoptions_sql'><legend><b>".$lang['options']."</b></legend>";
			echo $lang['no_opt'];
			echo "</fieldset>";

			echo "<fieldset style='float:left; max-width:350px; display:none;' id='importoptions_csv'><legend><b>".$lang['options']."</b></legend>";
			echo "<input type='hidden' value='".htmlencode($target_table)."' name='single_table'/>";
			echo "<div style='float:left;'>".$lang['fld_terminated']."</div>";
			echo "<input type='text' value=';' name='import_csv_fieldsterminated' style='float:right;'/>";
			echo "<div style='clear:both;'>";
			echo "<div style='float:left;'>".$lang['fld_enclosed']."</div>";
			echo "<input type='text' value='\"' name='import_csv_fieldsenclosed' style='float:right;'/>";
			echo "<div style='clear:both;'>";
			echo "<div style='float:left;'>".$lang['fld_escaped']."</div>";
			echo "<input type='text' value='\' name='import_csv_fieldsescaped' style='float:right;'/>";
			echo "<div style='clear:both;'>";
			echo "<div style='float:left;'>".$lang['rep_null']."</div>";
			echo "<input type='text' value='NULL' name='import_csv_replacenull' style='float:right;'/>";
			echo "<div style='clear:both;'>";
			echo "<label><input type='checkbox' checked='checked' name='import_csv_fieldnames'/> ".$lang['fld_names']."</label>";
			echo "</fieldset>";

			echo "<div style='clear:both;'></div>";
			echo "<br/><br/>";

			echo "<fieldset><legend><b>".$lang['import_f']."</b></legend>";
			echo "<em>".$lang['max_file_size'].": ".number_format(fileUploadMaxSize()/1024/1024)." MiB</em> ".helpLink($lang['help11'])."<br />";
			echo "<input type='file' value='".$lang['choose_f']."' name='file' style='background-color:transparent; border-style:none; margin:0; padding:0' onchange='checkFileSize(this)'/>";
			echo "<input type='submit' value='".$lang['import']."' name='import' class='btn'/>";
			echo "</fieldset>";
			break;

		//- Rename table (=table_rename)
		case "table_rename":
			echo $params->getForm(array('action'=>'table_rename', 'confirm'=>'1'));
			printf($lang['rename_tbl'], htmlencode($target_table));
			echo " <input type='text' name='newname' value='".htmlencode($target_table)."' style='width:200px;'/> <input type='submit' value='".$lang['rename']."' name='rename' class='btn'/>";
			echo "</form>";
			break;

		//- Search table (=table_search)
		case "table_search":
			if(!isset($_GET['search']))
			{
				$tableInfo = $db->getTableInfo($target_table);

				echo $params->getForm(array('action'=>'table_search', 'confirm'=>'1'));

				echo "<table border='0' cellpadding='2' cellspacing='1' class='viewTable'>";
				echo "<tr>";
				echo "<td class='tdheader'>".$lang['fld']."</td>";
				echo "<td class='tdheader'>".$lang['type']."</td>";
				echo "<td class='tdheader'>".$lang['operator']."</td>";
				echo "<td class='tdheader'>".$lang['val']."</td>";
				echo "</tr>";

				for($i=0; $i<sizeof($tableInfo); $i++)
				{
					$field = $tableInfo[$i][1];
					$type = $tableInfo[$i]['type'];
					$typeAffinity = get_type_affinity($type);
					$tdWithClass = "<td class='td".($i%2 ? "1" : "2")."'>";
					$tdWithClassLeft = "<td class='td".($i%2 ? "1" : "2")."' style='text-align:left;'>";
					if(isset($_GET['oldSearch']) && isset($_SESSION[COOKIENAME.'search'][$_GET['oldSearch']]['values'][$field]))
						$value = implode($_SESSION[COOKIENAME.'search'][$_GET['oldSearch']]['values'][$field], ",");
					else
						$value = '';
					if(isset($_GET['oldSearch']) && isset($_SESSION[COOKIENAME.'search'][$_GET['oldSearch']]['operators'][$field]))
						$operator = $_SESSION[COOKIENAME.'search'][$_GET['oldSearch']]['operators'][$field];
					elseif($typeAffinity=="TEXT" || $typeAffinity=="NONE")
						$operator = 'LIKE';
					else
						$operator = '=';

					echo "<tr>";
					echo $tdWithClassLeft;
					echo htmlencode($field);
					echo "</td>";
					echo $tdWithClassLeft;
					echo htmlencode($type);
					echo "</td>";
					echo $tdWithClassLeft;
					echo "<select name='field_".$i."_operator' onchange='checkLike(\"field_".$i."_value\", this.options[this.selectedIndex].value); '>";

					$operators = array('=', '>', '>=', '<', '<=', "= ''", "!= ''", '!=', 'LIKE', 'LIKE%','NOT LIKE', 'IN', 'NOT IN', 'IS NULL', 'IS NOT NULL');
					$operatorsDisplay = array('LIKE%' => 'LIKE %...%', 'IN'=>'IN (..., ...)', 'NOT IN'=>'NOT IN (..., ...)');
					$operatorsNumbersOnly = array('>', '>=', '<', '<=');
					$operatorsTextOnly = array("= ''", "!= ''");
					foreach($operators as $op)
					{
						if($typeAffinity!="INTEGER" && $typeAffinity!="REAL" && $typeAffinity!="NUMERIC" && in_array($op, $operatorsNumbersOnly))
							continue;
						if($typeAffinity!="TEXT" && $typeAffinity!="NONE" && in_array($op, $operatorsTextOnly))
							continue;
						$display = (isset($operatorsDisplay[$op]) ? $operatorsDisplay[$op] : $op);
						echo "<option value='".htmlencode($op)."'".($operator==$op?" selected='selected'":'').">".htmlencode($display)."</option>";
					}
					echo "</select>";
					echo "</td>";
					echo $tdWithClassLeft;
					if($typeAffinity=="INTEGER" || $typeAffinity=="REAL" || $typeAffinity=="NUMERIC")
						echo "<input type='text' id='field_".$i."_value' name='field_".$i."_value' value='".htmlencode($value)."'/>";
					else
						echo "<textarea id='field_".$i."_value' name='field_".$i."_value' rows='1' cols='60'>".htmlencode($value)."</textarea>";
					echo "</td>";
					echo "</tr>";
				}
				echo "<tr>";
				echo "<td class='tdheader' style='text-align:right;' colspan='4'>";
				echo "<input type='submit' value='".$lang['srch']."' class='btn'/>";
				echo "</td>";
				echo "</tr>";
				echo "</table>";
				echo "</form>";

				break;
			}
			elseif(isset($_SESSION[COOKIENAME.'search'][$_GET['search']]))
			{
				$params->search = $_GET['search'];
				$search = $_SESSION[COOKIENAME.'search'][$_GET['search']];
				// NOTICE: we do not break here!! we just do the same now like row_view-action does
			}

	//- Row actions

		//- View row (=row_view)
		case "row_view":
			if(!isset($_GET['startRow']))
				$_GET['startRow'] = 0;

			if(isset($_SESSION[COOKIENAME.'currentTable']) && $_SESSION[COOKIENAME.'currentTable']!=$target_table)
			{
				unset($_SESSION[COOKIENAME.'sortRows']);
				unset($_SESSION[COOKIENAME.'orderRows']);
			}
			if(isset($_GET['viewtype']))
			{
				$_SESSION[COOKIENAME.'viewtype'] = $_GET['viewtype'];
			}

			//- Query execution
			if(!isset($_GET['sort']))
				$_GET['sort'] = NULL;
			if(!isset($_GET['order']))
				$_GET['order'] = NULL;

			$numRows = $params->numRows;
			$startRow = $_GET['startRow'];
			if(isset($_GET['sort']))
			{
				$_SESSION[COOKIENAME.'sortRows'] = $_GET['sort'];
				$_SESSION[COOKIENAME.'currentTable'] = $target_table;
			}
			if(isset($_GET['order']))
			{
				$_SESSION[COOKIENAME.'orderRows'] = $_GET['order'];
				$_SESSION[COOKIENAME.'currentTable'] = $target_table;
			}
			$query = "SELECT * ";
			// select the primary key column(s) last (ROWID if there is no PK).
			// this will be used to identify rows, e.g. when editing/deleting rows
			$primary_key = $db->getPrimaryKey($target_table);
			foreach($primary_key as $pk)
			{
				$query.= ', '.$db->quote_id($pk);
				$query.= ', typeof('.$db->quote_id($pk).')';
			}
			$query .= " FROM ".$db->quote_id($target_table);
			$queryDisp = "SELECT * FROM ".$db->quote_id($target_table);
			$queryCount = "SELECT COUNT(*) AS count FROM ".$db->quote_id($target_table);
			$queryAdd = "";
			if(isset($search) && isset($search['where']))
			{
				$queryAdd = $search['where'];
				$queryCount .= $search['where'];
			}
			if(isset($_SESSION[COOKIENAME.'sortRows']))
				$queryAdd .= " ORDER BY ".$db->quote_id($_SESSION[COOKIENAME.'sortRows']);
			if(isset($_SESSION[COOKIENAME.'orderRows']))
				$queryAdd .= " ".$_SESSION[COOKIENAME.'orderRows'];
			$queryAdd .= " LIMIT ".$startRow.", ".$numRows;
			$query .= $queryAdd;
			$queryDisp .= $queryAdd;

			$resultRows = $db->select($queryCount);
			$totalRows = $resultRows['count'];
			$shownRows = min($resultRows['count']-$startRow, $numRows);

			//- HTML: pagination buttons
			$lastPage = intval($totalRows / $params->numRows);
			$remainder = intval($totalRows % $params->numRows);
			if($remainder==0)
				$remainder = $params->numRows;

			echo "<div style=''>";
			//previous button
			if($_GET['startRow']>0)
			{
				echo "<div style='float:left;'>";
				echo $params->getForm(array('action'=>$_GET['action']),'get');
				echo "<input type='hidden' name='startRow' value='0'/>";
				echo "<input type='submit' value='&larr;&larr;' class='btn'/> ";
				echo "</form>";
				echo "</div>";
				echo "<div style='float:left; overflow:hidden; margin-right:20px;'>";
				echo $params->getForm(array('action'=>$_GET['action']),'get');
				echo "<input type='hidden' name='startRow' value='".max(0,intval($_GET['startRow']-$params->numRows))."'/>";
				echo "<input type='submit' value='&larr;' class='btn'/> ";
				echo "</form>";
				echo "</div>";
			}

			//show certain number buttons
			echo "<div style='float:left;'>";
			echo $params->getForm(array('action'=>$_GET['action'], 'numRows'=>null),'get');
			echo "<input type='submit' value='".$lang['show']." : ' name='show' class='btn'/> ";
			echo "<input type='text' name='numRows' style='width:50px;' value='".$params->numRows."'/> ";
			echo $lang['rows_records'];

			if(intval($_GET['startRow']+$params->numRows) < $totalRows)
				echo "<input type='text' name='startRow' style='width:90px;' value='".intval($_GET['startRow']+$params->numRows)."'/>";
			else
				echo "<input type='text' name='startRow' style='width:90px;' value='0'/> ";
			echo $lang['as_a'];
			echo " <select name='viewtype'>";
			if(!isset($_SESSION[COOKIENAME.'viewtype']) || $_SESSION[COOKIENAME.'viewtype']=="table")
			{
				echo "<option value='table' selected='selected'>".$lang['tbl']."</option>";
				echo "<option value='chart'>".$lang['chart']."</option>";
			}
			else
			{
				echo "<option value='table'>".$lang['tbl']."</option>";
				echo "<option value='chart' selected='selected'>".$lang['chart']."</option>";
			}
			echo "</select>";
			echo "</form>";
			echo "</div>";

			//next button
			if(intval($_GET['startRow']+$params->numRows)<$totalRows)
			{
				echo "<div style='float:left; margin-left:20px; '>";
				echo $params->getForm(array('action'=>$_GET['action']),'get');
				echo "<input type='hidden' name='startRow' value='".intval($_GET['startRow']+$params->numRows)."'/>";
				echo "<input type='submit' value='&rarr;' class='btn'/> ";
				echo "</form>";
				echo "</div>";
				echo "<div style='float:left; '>";
				echo $params->getForm(array('action'=>$_GET['action']),'get');
				echo "<input type='hidden' name='startRow' value='".intval($totalRows-$remainder)."'/>";
				echo "<input type='submit' value='&rarr;&rarr;' class='btn'/> ";
				echo "</form>";
				echo "</div>";
			}
			echo "<div style='clear:both;'></div>";
			echo "</div>";


			//- Show results
			if($shownRows>0)
			{
				$queryTimer = new MicroTimer();
				$table_result = $db->query($query);
				$queryTimer->stop();


				echo "<br/><div class='confirm'>";
				echo "<b>".$lang['showing_rows']." ".$startRow." - ".($startRow + $shownRows-1).", ".$lang['total'].": ".$totalRows." ";
				printf($lang['query_time'], $queryTimer);
				echo "</b><br/>";
				echo "<span style='font-size:11px;'>".htmlencode($queryDisp)."</span>";
				echo "</div><br/>";

				if($target_table_type == 'view')
				{
					echo sprintf($lang['readonly_tbl'], htmlencode($target_table))." <a href='https://en.wikipedia.org/wiki/View_(SQL)' target='_blank'>https://en.wikipedia.org/wiki/View_(SQL)</a>";
					echo "<br/><br/>";
				}

				$tableInfo = $db->getTableInfo($target_table);
				$pkFirstCol = sizeof($tableInfo)+1;
				//- Table view
				if(!isset($_SESSION[COOKIENAME.'viewtype']) || $_SESSION[COOKIENAME.'viewtype']=="table")
				{
					echo $params->getForm(array('action'=>'row_editordelete'), 'post', false, 'checkForm');
					echo "<table border='0' cellpadding='2' cellspacing='1' class='viewTable'>";
					echo "<tr>";
					echo "<td colspan='3' class='tdheader' style='text-align:center'>";
					echo "<a href='".$params->getURL(array('action'=>$_GET['action'], 'fulltexts'=>($params->fulltexts?0:1) ))."' title='".$lang[($params->fulltexts?'no_full_texts':'full_texts')]."'>";
					echo "<b>&".($params->fulltexts?'r':'l')."arr;</b>&nbsp;T&nbsp;<b>&".($params->fulltexts?'l':'r')."arr;</b></a>";
					echo "</td>";

					for($i=0; $i<sizeof($tableInfo); $i++)
					{
						echo "<td class='tdheader'>";
						if(isset($_SESSION[COOKIENAME.'sortRows']))
							$orderTag = ($_SESSION[COOKIENAME.'sortRows']==$tableInfo[$i]['name'] && $_SESSION[COOKIENAME.'orderRows']=="ASC") ? "DESC" : "ASC";
						else
							$orderTag = "ASC";
						echo $params->getLink(array('action'=>$_GET['action'], 'sort'=>$tableInfo[$i]['name'], 'order'=>$orderTag ), htmlencode($tableInfo[$i]['name']));
						if(isset($_SESSION[COOKIENAME.'sortRows']) && $_SESSION[COOKIENAME.'sortRows']==$tableInfo[$i]['name'])
							echo (($_SESSION[COOKIENAME.'orderRows']=="ASC") ? " <b>&uarr;</b>" : " <b>&darr;</b>");
						echo "</td>";
					}
					echo "</tr>";

					for($i=0; $row = $db->fetch($table_result, 'num'); $i++)
					{
						// -g-> $pk will always be the last columns in each row of the array because we are doing "SELECT *, PK_1, typeof(PK_1), PK2, typeof(PK_2), ... FROM ..."
						$pk_arr = array();
						for($col = $pkFirstCol; array_key_exists($col, $row); $col=$col+2)
						{
							// in $col we have the type and in $col-1 the value
							if($row[$col]=='integer' || $row[$col]=='real')
								// json encode as int or float, not string
								$pk_arr[] = $row[$col-1]+0;
							else
								// encode as json string
								$pk_arr[] = $row[$col-1];
						}
						$pk = json_encode($pk_arr);
						$tdWithClass = "<td class='td".($i%2 ? "1" : "2")."'>";
						$tdWithClassLeft = "<td class='td".($i%2 ? "1" : "2")."' style='text-align:left;'>";
						echo "<tr>";
						if($target_table_type == 'table' && $db->isWritable() && $db->isDirWritable())
						{
							echo $tdWithClass;
							echo "<input type='checkbox' name='check[]' value='".htmlencode($pk)."' id='check_".htmlencode($i)."'/>";
							echo "</td>";
							echo $tdWithClass;
							// -g-> Here, we need to put the PK in as the link for both the edit and delete.
							echo $params->getLink(array('action'=>'row_editordelete', 'pk'=>$pk, 'type'=>'edit'),"<span>".$lang['edit']."</span>",'edit', $lang['edit']);
							echo "</td>";
							echo $tdWithClass;
							echo $params->getLink(array('action'=>'row_editordelete', 'pk'=>$pk, 'type'=>'delete'),"<span>".$lang['del']."</span>",'delete', $lang['del']);
							echo "</td>";
						} else {
							echo "<td class='td".($i%2 ? "1" : "2")."' colspan='3'></td>";
						}
						for($j=0; $j<sizeof($tableInfo); $j++)
						{
							$typeAffinity = get_type_affinity($tableInfo[$j]['type']);
							if($typeAffinity=="INTEGER" || $typeAffinity=="REAL" || $typeAffinity=="NUMERIC")
								echo $tdWithClass;
							else
								echo $tdWithClassLeft;
							if($row[$j]==="")
								echo "&nbsp;";
							elseif($row[$j]===NULL)
								echo "<i class='null'>NULL</i>";
							elseif(preg_match('/^BLOB/i', $tableInfo[$j]['type']))
							{
								echo "<div style='float:left; text-align: left; padding-right:2em'>";
								echo $params->getLink(array('action'=>'row_get_blob', 'confirm'=>1, 'pk'=>$pk, 'column'=>$tableInfo[$j]['name'], 'download_blob'=>1),$lang["download"]).' | ';
								echo $params->getLink(array('action'=>'row_get_blob', 'confirm'=>1, 'pk'=>$pk, 'column'=>$tableInfo[$j]['name'], 'download_blob'=>0),$lang["open_in_browser"],'','','_blank');
								echo "</div><div style='float:right; text-align: right'>";
								echo 'Size: '.number_format(strlen($row[$j])).' Bytes';
								echo "</div>";
							}
							elseif(isset($search))
								echo markSearchWords(subString($row[$j]),$tableInfo[$j]['name'], $search);
							else
								echo htmlencode(subString($row[$j]));
							echo "</td>";
						}
						echo "</tr>";
					}
					echo "</table>";
					if($target_table_type == 'table' && $db->isWritable() && $db->isDirWritable())
					{
						echo "<a onclick='checkAll()'>".$lang['chk_all']."</a> / <a onclick='uncheckAll()'>".$lang['unchk_all']."</a> <i>".$lang['with_sel'].":</i> ";
						echo "<select name='type'>";
						echo "<option value='edit'>".$lang['edit']."</option>";
						echo "<option value='delete'>".$lang['del']."</option>";
						echo "</select> ";
						echo "<input type='submit' value='".$lang['go']."' name='massGo' class='btn'/>";
					}
					echo "</form>";
				}
				else
				//- Chart view
				{
					if(!isset($_SESSION[COOKIENAME.$target_table.'chartlabels']))
					{
						// No label-column set. Try to pick a text-column as label-column.
						for($i=0; $i<sizeof($tableInfo); $i++)
						{
							if(get_type_affinity($tableInfo[$i]['type'])=='TEXT')
							{
								$_SESSION[COOKIENAME.$target_table.'chartlabels'] = $i;
								break;
							}
						}
					}
					if(!isset($_SESSION[COOKIENAME.$target_table.'chartlabels']))
						// no text column found, use the first column
						$_SESSION[COOKIENAME.$target_table.'chartlabels'] = 0;

					if(!isset($_SESSION[COOKIENAME.$target_table.'chartvalues']))
					{
						// No value-column set. Pick the first numeric column if possible.
						// If not possible, pick the first column that is not the label-column.

						$potential_value_column = null;
						for($i=0; $i<sizeof($tableInfo); $i++)
						{
							if($potential_value_column===null && $i != $_SESSION[COOKIENAME.$target_table.'chartlabels'])
								// the first column (of any type) that is not the label-column
								$potential_value_column = $i;
							// check if the col is numeric
							$typeAffinity = get_type_affinity($tableInfo[$i]['type']);
							if($typeAffinity=='INTEGER' || $typeAffinity=='REAL' || $typeAffinity=='NUMERIC')
							{
								// this is defined as a numeric column, so prefer this as a value column over $potential_value_column
								$_SESSION[COOKIENAME.$target_table.'chartvalues'] = $i;
								break;
							}
						}
						if(!isset($_SESSION[COOKIENAME.$target_table.'chartvalues']))
						{
							// we did not find a numeric column
							if($potential_value_column!==null)
								// use the $potential_value_column, i.e. the second column which is not the label-column
								$_SESSION[COOKIENAME.$target_table.'chartvalues'] = $potential_value_column;
							else
								// it's hopeless, there is only 1 column
								$_SESSION[COOKIENAME.$target_table.'chartvalues'] = 0;
						}
					}

					if(!isset($_SESSION[COOKIENAME.'charttype']))
						$_SESSION[COOKIENAME.'charttype'] = 'bar';

					if(isset($_POST['chartsettings']))
					{
						$_SESSION[COOKIENAME.'charttype'] = $_POST['charttype'];
						$_SESSION[COOKIENAME.$target_table.'chartlabels'] = $_POST['chartlabels'];
						$_SESSION[COOKIENAME.$target_table.'chartvalues'] = $_POST['chartvalues'];
					}
					//- Chart javascript code
					?>
					<script type='text/javascript' src='https://www.jq22.com/demo/jQuery-bzt20161213/js/jsapi.js'></script>
                    <script type='text/javascript' src='https://www.jq22.com/demo/jQuery-bzt20161213/js/corechart.js'></script> 
					
					<script type='text/javascript'>
					google.load('visualization', '1.0', {'packages':['corechart']});
					google.setOnLoadCallback(drawChart);
					function drawChart()
					{
						var data = new google.visualization.DataTable();
						data.addColumn('string', '<?php echo $tableInfo[$_SESSION[COOKIENAME.$target_table.'chartlabels']]['name']; ?>');
						data.addColumn('number', '<?php echo $tableInfo[$_SESSION[COOKIENAME.$target_table.'chartvalues']]['name']; ?>');
						data.addRows([
						<?php
						for($i=0; $row = $db->fetch($table_result); $i++)
						{
							$label = str_replace("'", "", htmlencode($row[$_SESSION[COOKIENAME.$target_table.'chartlabels']]));
							$value = htmlencode($row[$_SESSION[COOKIENAME.$target_table.'chartvalues']]);

							if($value==NULL || $value=="")
								$value = 0;

							echo "['".$label."', ".$value."]";
							if($i<$totalRows-1)
								echo ",";
						}
						$height = ($totalRows+1) * 30;
						if($height>1000)
							$height = 1000;
						else if($height<300)
							$height = 300;
						if($_SESSION[COOKIENAME.'charttype']=="pie")
							$height = 800;
						?>
						]);
						var chartWidth = document.getElementById("main_column").offsetWidth - document.getElementById("chartsettingsbox").offsetWidth - 100;
						if(chartWidth>1000)
							chartWidth = 1000;

						var options =
						{
							'width':chartWidth,
							'height':<?php echo $height; ?>,
							'title':'<?php echo $tableInfo[$_SESSION[COOKIENAME.$target_table.'chartlabels']]['name']." vs ".$tableInfo[$_SESSION[COOKIENAME.$target_table.'chartvalues']]['name']; ?>'
						};
						<?php
						if($_SESSION[COOKIENAME.'charttype']=="bar")
							echo "var chart = new google.visualization.BarChart(document.getElementById('chart_div'));";
						else if($_SESSION[COOKIENAME.'charttype']=="pie")
							echo "var chart = new google.visualization.PieChart(document.getElementById('chart_div'));";
						else
							echo "var chart = new google.visualization.LineChart(document.getElementById('chart_div'));";
						?>
						chart.draw(data, options);
					}
					</script>
					<div id="chart_div" style="float:left;"><?php echo $lang['no_chart']; ?></div>
					<?php
					echo "<fieldset style='float:right; text-align:center;' id='chartsettingsbox'><legend><b>Chart Settings</b></legend>";
					echo $params->getForm(array('action'=>$_GET['action']));
					echo $lang['chart_type'].": <select name='charttype'>";
					echo "<option value='bar'";
					if($_SESSION[COOKIENAME.'charttype']=="bar")
						echo " selected='selected'";
					echo ">".$lang['chart_bar']."</option>";
					echo "<option value='pie'";
					if($_SESSION[COOKIENAME.'charttype']=="pie")
						echo " selected='selected'";
					echo ">".$lang['chart_pie']."</option>";
					echo "<option value='line'";
					if($_SESSION[COOKIENAME.'charttype']=="line")
						echo " selected='selected'";
					echo ">".$lang['chart_line']."</option>";
					echo "</select>";
					echo "<br/><br/>";
					echo $lang['lbl'].": <select name='chartlabels'>";
					for($i=0; $i<sizeof($tableInfo); $i++)
					{
						if(isset($_SESSION[COOKIENAME.$target_table.'chartlabels']) && $_SESSION[COOKIENAME.$target_table.'chartlabels']==$i)
							echo "<option value='".$i."' selected='selected'>".htmlencode($tableInfo[$i]['name'])."</option>";
						else
							echo "<option value='".$i."'>".htmlencode($tableInfo[$i]['name'])."</option>";
					}
					echo "</select>";
					echo "<br/><br/>";
					echo $lang['val'].": <select name='chartvalues'>";
					for($i=0; $i<sizeof($tableInfo); $i++)
					{
						if(isset($_SESSION[COOKIENAME.$target_table.'chartvalues']) && $_SESSION[COOKIENAME.$target_table.'chartvalues']==$i)
							echo "<option value='".$i."' selected='selected'>".htmlencode($tableInfo[$i]['name'])."</option>";
						else
							echo "<option value='".$i."'>".htmlencode($tableInfo[$i]['name'])."</option>";
					}
					echo "</select>";
					echo "<br/><br/>";
					echo "<input type='submit' name='chartsettings' value='".$lang['update']."' class='btn'/>";
					echo "</form>";
					echo "</fieldset>";
					echo "<div style='clear:both;'></div>";
					//end chart view
				}
			}
			else //no rows - do nothing
			{
				echo "<br/><div class='confirm'>";
				if(isset($search) || $totalRows>0)
					echo $lang['no_rows']."<br/><br/>";
				elseif($target_table_type == 'table')
					echo $lang['empty_tbl']." ".$params->getLink(array('action'=>'row_create'), $lang['click']) ." ".$lang['insert_rows'].'<br/><br/>';
				echo "<span style='font-size:11px;'>".htmlencode($queryDisp)."</span>";
				echo "</div><br/>";
			}

			if(isset($search))
				echo "<br/><br/>".$params->getLink(array('action'=>'table_search','search'=>null,'oldSearch' => (isset($_GET['search'])?$_GET['search']:null)), $lang['srch_again']);

			break;

		//- Create new row (=row_create)
		case "row_create":
			echo $params->getForm(array('action'=>'row_create'), 'get');
			echo $lang['restart_insert'];
			echo " <select name='newRows'>";
			for($i=1; $i<=40; $i++)
			{
				if(isset($_GET['newRows']) && $_GET['newRows']==$i)
					echo "<option value='".$i."' selected='selected'>".$i."</option>";
				else
					echo "<option value='".$i."'>".$i."</option>";
			}
			echo "</select> ";
			echo $lang['rows'];
			echo " <input type='submit' value='".$lang['go']."' class='btn'/>";
			echo "</form>";
			echo "<br/>";
			echo $params->getForm(array('action'=>'row_create','confirm'=>'1'), 'post', true);
			$tableInfo = $db->getTableInfo($target_table);
			if(isset($_GET['newRows']))
				$num = $_GET['newRows'];
			else
				$num = 1;
			echo "<input type='hidden' name='newRows' value='".$num."'/>";
			for($j=0; $j<$num; $j++)
			{
				if($j>0)
					echo "<label><input type='checkbox' value='ignore' name='".$j.":ignore' id='row_".$j."_ignore' checked='checked'/> ".$lang['ignore']."</label><br/>";
				echo "<table border='0' cellpadding='2' cellspacing='1' class='viewTable'>";
				echo "<tr>";
				echo "<td class='tdheader'>".$lang['fld']."</td>";
				echo "<td class='tdheader'>".$lang['type']."</td>";
				echo "<td class='tdheader'>".$lang['func']."</td>";
				echo "<td class='tdheader'>Null</td>";
				echo "<td class='tdheader'>".$lang['val']."</td>";
				echo "</tr>";

				for($i=0; $i<sizeof($tableInfo); $i++)
				{
					$field = $tableInfo[$i]['name'];
					$type = strtoupper($tableInfo[$i]['type']);
					$typeAffinity = get_type_affinity($type);
					if($tableInfo[$i]['dflt_value'] === "NULL")
						$value = NULL;
					else
						$value = htmlencode(trim(trim($tableInfo[$i]['dflt_value']), "'"));
					$tdWithClassLeft = "<td class='td".($i%2 ? "1" : "2")."' style='text-align:left;'>";
					echo "<tr>";
					echo $tdWithClassLeft;
					echo htmlencode($field);
					echo "</td>";
					echo $tdWithClassLeft;
					echo htmlencode($type);
					echo "</td>";
					echo $tdWithClassLeft;
					echo "<select name='function_".$i."[]' onchange='notNull(\"row_".$j."_field_".$i."_null\");'>";
					echo "<option value=''>&nbsp;</option>";
					foreach (array_merge($sqlite_functions, $custom_functions) as $f) {
						echo "<option value='".htmlencode($f)."'>".htmlencode($f)."</option>";
					}
					echo "</select>";
					echo "</td>";
					echo $tdWithClassLeft;
					if($tableInfo[$i]['notnull']==0)
					{
						if($value===NULL)
							echo "<input type='checkbox' name='".$i."_null[]' id='row_".$j."_field_".$i."_null' checked='checked' onclick='disableText(this, \"row_".$j."_field_".$i."_value\");'/>";
						else
							echo "<input type='checkbox' name='".$i."_null[]' id='row_".$j."_field_".$i."_null' onclick='disableText(this, \"row_".$j."_field_".$i."_value\");'/>";
					}
					echo "</td>";
					echo $tdWithClassLeft;

					if($typeAffinity=="INTEGER" || $typeAffinity=="REAL" || $typeAffinity=="NUMERIC")
						echo "<input type='text' id='row_".$j."_field_".$i."_value' name='".$j.":".$i."' value='".$value."' onblur='changeIgnore(this, \"row_".$j."_ignore\");' onclick='notNull(\"row_".$j."_field_".$i."_null\");'/>";
					elseif(preg_match('/^BLOB/', $type))
						echo "<input type='file' id='row_".$j."_field_".$i."_value' name='".$j.":".$i."' onblur='changeIgnore(this, \"row_".$j."_ignore\");' onclick='notNull(\"row_".$j."_field_".$i."_null\");'/>";
					else
						echo "<textarea id='row_".$j."_field_".$i."_value' name='".$j.":".$i."' rows='5' cols='60' onclick='notNull(\"row_".$j."_field_".$i."_null\");' onblur='changeIgnore(this, \"row_".$j."_ignore\");'>".$value."</textarea>";
					echo "</td>";
					echo "</tr>";
				}
				echo "<tr>";
				echo "<td class='tdheader' style='text-align:right;' colspan='5'>";
				echo "<input type='submit' value='".$lang['insert']."' class='btn'/>";
				echo "</td>";
				echo "</tr>";
				echo "</table><br/>";
			}
			echo "</form>";
			break;

		//- Edit or delete row (=row_editordelete)
		case "row_editordelete":
			if(isset($_POST['check']))
				$pks = $_POST['check'];
			else if(isset($_GET['pk']))
				$pks = array($_GET['pk']);
			else $pks[0] = "";
			$str = implode(', ', $pks);
			if($str=="") //nothing was selected so show an error
			{
				echo "<div class='confirm'>";
				echo $lang['err'].": ".$lang['no_sel'];
				echo "</div>";
				echo "<br/><br/>".$params->getLink(array('action'=>'row_view'),$lang['return']);
			}
			else
			{
				if((isset($_POST['type']) && $_POST['type']=="edit") || (isset($_GET['type']) && $_GET['type']=="edit")) //edit
				{
					echo $params->getForm(array('action'=>'row_edit', 'confirm'=>'1', 'pk'=>json_encode($pks)),'post',true);
					$tableInfo = $db->getTableInfo($target_table);
					$primary_key = $db->getPrimaryKey($target_table);

					for($j=0; $j<sizeof($pks); $j++)
					{
						$query = "SELECT * FROM ".$db->quote_id($target_table)." WHERE " . $db->wherePK($target_table, json_decode($pks[$j]));
						$result1 = $db->select($query, 'num');

						echo "<table border='0' cellpadding='2' cellspacing='1' class='viewTable'>";
						echo "<tr>";
						echo "<td class='tdheader'>".$lang['fld']."</td>";
						echo "<td class='tdheader'>".$lang['type']."</td>";
						echo "<td class='tdheader'>".$lang['func']."</td>";
						echo "<td class='tdheader'>Null</td>";
						echo "<td class='tdheader'>".$lang['val']."</td>";
						echo "</tr>";

						for($i=0; $i<sizeof($tableInfo); $i++)
						{
							$field = $tableInfo[$i]['name'];
							$type = strtoupper($tableInfo[$i]['type']);
							$typeAffinity = get_type_affinity($type);
							$value = $result1[$i];
							$tdWithClassLeft = "<td class='td".($i%2 ? "1" : "2")."' style='text-align:left;'>";
							echo "<tr>";
							echo $tdWithClassLeft;
							echo htmlencode($field);
							echo "</td>";
							echo $tdWithClassLeft;
							echo htmlencode($type);
							echo "</td>";
							echo $tdWithClassLeft;
							echo "<select name='function_".$i."[]' onchange='notNull(\"row_".$j."_field_".$i."_null\");'>";
							echo "<option value=''></option>";
							foreach (array_merge($sqlite_functions, $custom_functions) as $f) {
								echo "<option value='".htmlencode($f)."'>".htmlencode($f)."</option>";
							}
							echo "</select>";
							echo "</td>";
							echo $tdWithClassLeft;
							if($tableInfo[$i]['notnull']==0)
							{
								if($value===NULL)
									echo "<input type='checkbox' name='".$i."_null[]' id='row_".$j."_field_".$i."_null' checked='checked' onclick='disableText(this, \"row_".$j."_field_".$i."_value\");'/>";
								else
									echo "<input type='checkbox' name='".$i."_null[]' id='row_".$j."_field_".$i."_null' onclick='disableText(this, \"row_".$j."_field_".$i."_value\");'/>";
							}
							echo "</td>";
							echo $tdWithClassLeft;
							if($typeAffinity=="INTEGER" || $typeAffinity=="REAL" || $typeAffinity=="NUMERIC")
								echo "<input type='text' id='row_".$j."_field_".$i."_value' name='".$i."[]' value='".htmlencode($value)."' onblur='changeIgnore(this, \"".$j."\", \"row_".$j."_field_".$i."_null\")' />";
							elseif(preg_match('/^BLOB/', $type))
							{
								if($value!==NULL)
								{
									echo "<input type='radio' name='row_".$j."_field_".$i."_blob_use' value='old' checked='checked'>";
									echo $params->getLink(array('action'=>'row_get_blob', 'confirm'=>1, 'pk'=>$pks[$j], 'column'=>$field, 'download_blob'=>1),$lang["download"]).' | ';
									echo $params->getLink(array('action'=>'row_get_blob', 'confirm'=>1, 'pk'=>$pks[$j], 'column'=>$field, 'download_blob'=>0),$lang["open_in_browser"],'','','_blank').'<br/>';
									echo "<input type='radio' name='row_".$j."_field_".$i."_blob_use' value='new' id='row_".$j."_field_".$i."_blob_new'>";
								}
								echo "<input type='file' id='row_".$j."_field_".$i."_value' name='".$j.":".$i."'
									onblur='changeIgnore(this, \"row_".$j."_ignore\");'
									onchange='document.getElementById(\"row_".$j."_field_".$i."_blob_new\").checked=true;'
									onclick='notNull(\"row_".$j."_field_".$i."_null\");'
									".($value===NULL?" disabled='disabled'":"")."/>";
							}
							else
								echo "<textarea id='row_".$j."_field_".$i."_value' name='".$i."[]' rows='1' cols='60' class='".htmlencode($field)."_textarea' onblur='changeIgnore(this, \"".$j."\", \"row_".$j."_field_".$i."_null\")'>".htmlencode($value)."</textarea>";
							echo "</td>";
							echo "</tr>";
						}
						echo "<tr>";
						echo "<td class='tdheader' style='text-align:right;' colspan='5'>";
						// Note: the 'Save changes' button must be first in the code so it is the one used when submitting the form with the Enter key (issue #215)
						echo "<input type='submit' value='".$lang['save_ch']."' class='btn'/> ";
						echo "<input type='submit' name='new_row' value='".$lang['new_insert']."' class='btn'/> ";
						echo $params->getLink(array('action'=>'row_view'), $lang['cancel']);
						echo "</td>";
						echo "</tr>";
						echo "</table>";
						echo "<br/>";
					}
					echo "</form>";
				}
				else //delete
				{
					echo $params->getForm(array('action'=>'row_delete', 'confirm'=>'1', 'pk'=>json_encode($pks)));
					echo "<div class='confirm'>";
					printf($lang['ques_del_rows'], htmlencode($str), htmlencode($target_table));
					echo "<br/><br/>";
					echo "<input type='submit' value='".$lang['confirm']."' class='btn'/> ";
					echo $params->getLink(array('action'=>'row_view'), $lang['cancel']);
					echo "</div>";
				}
			}
			break;

	//- Column actions

		//- View table structure (=column_view)
		case "column_view":
			$tableInfo = $db->getTableInfo($target_table);

			echo $params->getForm(array('action'=>'column_confirm'), 'get', false, 'checkForm');
			echo "<table border='0' cellpadding='2' cellspacing='1' class='viewTable'>";
			echo "<tr>";
			if($target_table_type == 'table' && $db->isWritable() && $db->isDirWritable())
				echo "<td colspan='3'></td>";
			echo "<td class='tdheader'>".$lang['col']." #</td>";
			echo "<td class='tdheader'>".$lang['fld']."</td>";
			echo "<td class='tdheader'>".$lang['type']."</td>";
			echo "<td class='tdheader'>".$lang['not_null']."</td>";
			echo "<td class='tdheader'>".$lang['def_val']."</td>";
			echo "<td class='tdheader'>".$lang['prim_key']."</td>";
			echo "</tr>";

			$noPrimaryKey = true;

			for($i=0; $i<sizeof($tableInfo); $i++)
			{
				$colVal = $tableInfo[$i][0];
				$fieldVal = $tableInfo[$i][1];
				$typeVal = $tableInfo[$i]['type'];
				$notnullVal = $tableInfo[$i][3];
				$defaultVal = $tableInfo[$i][4];
				$primarykeyVal = $tableInfo[$i][5];

				if(intval($notnullVal)!=0)
					$notnullVal = $lang['yes'];
				else
					$notnullVal = $lang['no'];
				if(intval($primarykeyVal)!=0)
				{
					$primarykeyVal = $lang['yes'];
					$noPrimaryKey = false;
				}
				else
					$primarykeyVal = $lang['no'];

				$tdWithClass = "<td class='td".($i%2 ? "1" : "2")."'>";
				$tdWithClassLeft = "<td class='td".($i%2 ? "1" : "2")."' style='text-align:left;'>";
				echo "<tr>";
				if($target_table_type == 'table' && $db->isWritable() && $db->isDirWritable())
				{
					echo $tdWithClass;
					echo "<input type='checkbox' name='check[]' value='".htmlencode($fieldVal)."' id='check_".$i."'/>";
					echo "</td>";
					echo $tdWithClass;
					echo $params->getLink(array('action'=>'column_edit', 'pk'=>$fieldVal),"<span>".$lang['edit']."</span>",'edit', $lang['edit']);
					echo "</td>";
					echo $tdWithClass;
					echo $params->getLink(array('action'=>'column_confirm', 'action2'=>'column_delete', 'pk'=>$fieldVal),"<span>".$lang['del']."</span>",'delete', $lang['del']);
					echo "</td>";
				}
				echo $tdWithClass;
				echo htmlencode($colVal);
				echo "</td>";
				echo $tdWithClassLeft;
				echo htmlencode($fieldVal);
				echo "</td>";
				echo $tdWithClassLeft;
				echo htmlencode($typeVal);
				echo "</td>";
				echo $tdWithClassLeft;
				echo htmlencode($notnullVal);
				echo "</td>";
				echo $tdWithClassLeft;
				if($defaultVal===NULL)
					echo "<i class='null'>".$lang['none']."</i>";
				elseif($defaultVal==="NULL")
					echo "<i class='null'>NULL</i>";
				else
					echo htmlencode($defaultVal);
				echo "</td>";
				echo $tdWithClassLeft;
				echo htmlencode($primarykeyVal);
				echo "</td>";
				echo "</tr>";
			}

			echo "</table>";
			if($target_table_type == 'table' && $db->isWritable() && $db->isDirWritable())
			{
				echo "<a onclick='checkAll()'>".$lang['chk_all']."</a> / <a onclick='uncheckAll()'>".$lang['unchk_all']."</a> <i>".$lang['with_sel'].":</i> ";
				echo "<select name='action2'>";
				//echo "<option value='edit'>".$lang['edit']."</option>";
				echo "<option value='column_delete'>".$lang['del']."</option>";
				if($noPrimaryKey)
					echo "<option value='primarykey_add'>".$lang['prim_key']."</option>";
				echo "</select> ";
				echo "<input type='submit' value='".$lang['go']."' name='massGo' class='btn'/>";
			}
			echo "</form>";
			if($target_table_type == 'table' && $db->isWritable() && $db->isDirWritable())
			{
				echo "<br/>";
				echo $params->getForm(array('action'=>'column_create'), 'get');
				echo $lang['add']." <input type='text' name='tablefields' style='width:30px;' value='1'/> ".$lang['tbl_end']." <input type='submit' value='".$lang['go']."' name='addfields' class='btn'/>";
				echo "</form>";
			}

			$query = "SELECT sql FROM sqlite_master WHERE name=".$db->quote($target_table);
			$master = $db->selectArray($query);

			echo "<br/>";
			echo "<br/>";
			echo "<div class='confirm'>";
			echo "<b>".$lang['query_used_'.$target_table_type]."</b><br/>";
			echo "<span style='font-size:11px;'>".htmlencode($master[0]['sql'])."</span>";
			echo "</div>";
			echo "<br/>";
			if($target_table_type != 'view')
			{
				echo "<br/><hr/><br/>";
				$query = "PRAGMA index_list(".$db->quote_id($target_table).")";
				$result = $db->selectArray($query);
				if(sizeof($result)>0)
				{
					echo "<h2>".$lang['indexes'].":</h2>";
					echo "<table border='0' cellpadding='2' cellspacing='1' class='viewTable'>";
					echo "<tr>";
					echo "<td colspan='1'>";
					echo "</td>";
					echo "<td class='tdheader'>".$lang['name']."</td>";
					echo "<td class='tdheader'>".$lang['unique']."</td>";
					echo "<td class='tdheader'>".$lang['seq_no']."</td>";
					echo "<td class='tdheader'>".$lang['col']." #</td>";
					echo "<td class='tdheader'>".$lang['fld']."</td>";
					echo "</tr>";
					for($i=0; $i<sizeof($result); $i++)
					{
						if($result[$i]['unique']==0)
							$unique = $lang['no'];
						else
							$unique = $lang['yes'];

						$query = "PRAGMA index_info(".$db->quote_id($result[$i]['name']).")";
						$info = $db->selectArray($query);
						$span = sizeof($info);

						$tdWithClass = "<td class='td".($i%2 ? "1" : "2")."'>";
						$tdWithClassLeft = "<td class='td".($i%2 ? "1" : "2")."' style='text-align:left;'>";
						$tdWithClassSpan = "<td class='td".($i%2 ? "1" : "2")."' rowspan='".$span."'>";
						$tdWithClassLeftSpan = "<td class='td".($i%2 ? "1" : "2")."' style='text-align:left;' rowspan='".$span."'>";
						echo "<tr>";
						echo $tdWithClassSpan;
						echo $params->getLink(array('action'=>'index_delete', 'pk'=>$result[$i]['name']), "<span>".$lang['del']."</span>", 'delete', $lang['del']);
						echo "</td>";
						echo $tdWithClassLeftSpan;
						echo $result[$i]['name'];
						echo "</td>";
						echo $tdWithClassLeftSpan;
						echo $unique;
						echo "</td>";
						for($j=0; $j<$span; $j++)
						{
							if($j!=0)
								echo "<tr>";
							echo $tdWithClassLeft;
							echo htmlencode($info[$j]['seqno']);
							echo "</td>";
							echo $tdWithClassLeft;
							echo htmlencode($info[$j]['cid']);
							echo "</td>";
							echo $tdWithClassLeft;
							echo htmlencode($info[$j]['name']);
							echo "</td>";
							echo "</tr>";
						}
					}
					echo "</table><br/><br/>";
				}

				$query = "SELECT * FROM sqlite_master WHERE type='trigger' AND tbl_name=".$db->quote($target_table)." ORDER BY name";
				$result = $db->selectArray($query);
				//print_r($result);
				if(sizeof($result)>0)
				{
					echo "<h2>".$lang['triggers'].":</h2>";
					echo "<table border='0' cellpadding='2' cellspacing='1' class='viewTable'>";
					echo "<tr>";
					echo "<td colspan='1'>";
					echo "</td>";
					echo "<td class='tdheader'>".$lang['name']."</td>";
					echo "<td class='tdheader'>".$lang['sql']."</td>";
					echo "</tr>";
					for($i=0; $i<sizeof($result); $i++)
					{
						$tdWithClass = "<td class='td".($i%2 ? "1" : "2")."'>";
						echo "<tr>";
						echo $tdWithClass;
						echo $params->getLink(array('action'=>'trigger_delete', 'pk'=>$result[$i]['name']), "<span>".$lang['del']."</span>", 'delete', $lang['del']);
						echo "</td>";
						echo $tdWithClass;
						echo htmlencode($result[$i]['name']);
						echo "</td>";
						echo $tdWithClass;
						echo htmlencode($result[$i]['sql']);
						echo "</td>";
					}
					echo "</table><br/><br/>";
				}

				if($db->isWritable() && $db->isDirWritable())
				{
					echo $params->getForm(array('action'=>'index_create'),'get');
					echo "<br/><div class='tdheader'>";
					echo $lang['create_index2']." <input type='text' name='numcolumns' style='width:30px;' value='1'/> ".$lang['cols']." <input type='submit' value='".$lang['go']."' name='addindex' class='btn'/>";
					echo "</div>";
					echo "</form>";
	
					echo $params->getForm(array('action'=>'trigger_create'),'get');
					echo "<br/><div class='tdheader'>";
					echo $lang['create_trigger2']." <input type='submit' value='".$lang['go']."' name='addindex' class='btn'/>";
					echo "</div>";
					echo "</form>";
				}
			}
			break;

		//- Create column (=column_create)
		case "column_create":
			echo "<h2>".sprintf($lang['new_fld'],htmlencode($_GET['table']))."</h2>";
			if($_GET['tablefields']=="" || intval($_GET['tablefields'])<=0)
				echo $lang['specify_fields'];
			else if($_GET['table']=="")
				echo $lang['specify_tbl'];
			else
			{
				$num = intval($_GET['tablefields']);
				$name = $_GET['table'];
				echo $params->getForm(array('action'=>'column_create', 'confirm'=>'1'));
				echo "<input type='hidden' name='rows' value='".$num."'/>";
				echo "<table border='0' cellpadding='2' cellspacing='1' class='viewTable'>";
				echo "<tr>";
				$headings = array($lang["fld"], $lang["type"], $lang["prim_key"]);
				if($db->getType() != "SQLiteDatabase") $headings[] = $lang["autoincrement"];
				$headings[] = $lang["not_null"];
				$headings[] = $lang["def_val"];

				for($k=0; $k<count($headings); $k++)
					echo "<td class='tdheader'>" . $headings[$k] . "</td>";
				echo "</tr>";

				for($i=0; $i<$num; $i++)
				{
					$tdWithClass = "<td class='td" . ($i%2 ? "1" : "2") . "'>";
					echo "<tr>";
					echo $tdWithClass;
					echo "<input type='text' name='".$i."_field' style='width:200px;'/>";
					echo "</td>";
					echo $tdWithClass;
					echo "<select name='".$i."_type' id='i".$i."_type' onchange='toggleAutoincrement(".$i.");'>";
					foreach ($sqlite_datatypes as $t) {
						echo "<option value='".htmlencode($t)."'>".htmlencode($t)."</option>";
					}
					echo "</select>";
					echo "</td>";
					echo $tdWithClass;
					echo "<label><input type='checkbox' name='".$i."_primarykey'/> ".$lang['yes']."</label>";
					echo "</td>";
					if($db->getType() != "SQLiteDatabase")
					{
						echo $tdWithClass;
						echo "<label><input type='checkbox' name='".$i."_autoincrement' id='i".$i."_autoincrement'/> ".$lang['yes']."</label>";
						echo "</td>";
					}
					echo $tdWithClass;
					echo "<label><input type='checkbox' name='".$i."_notnull'/> ".$lang['yes']."</label>";
					echo "</td>";
					echo $tdWithClass;
					echo "<select name='".$i."_defaultoption' id='i".$i."_defaultoption' onchange=\"if(this.value!='defined' && this.value!='expr') document.getElementById('i".$i."_defaultvalue').value='';\">";
					echo "<option value='none'>".$lang['none']."</option><option value='defined'>".$lang['as_defined'].":</option><option>NULL</option><option>CURRENT_TIME</option><option>CURRENT_DATE</option><option>CURRENT_TIMESTAMP</option><option value='expr'>".$lang['expression'].":</option>";
					echo "</select>";
					echo "<input type='text' name='".$i."_defaultvalue' id='i".$i."_defaultvalue' style='width:100px;' onchange=\"if(document.getElementById('i".$i."_defaultoption').value!='expr') document.getElementById('i".$i."_defaultoption').value='defined';\"/>";
					echo "</td>";
					echo "</tr>";
				}
				echo "<tr>";
				echo "<td class='tdheader' style='text-align:right;' colspan='6'>";
				echo "<input type='submit' value='".$lang['add_flds']."' class='btn'/> ";
				echo $params->getLink(array('action'=>'column_view'), $lang['cancel']);
				echo "</td>";
				echo "</tr>";
				echo "</table>";
				echo "</form>";
			}
			break;

		//- Delete column (=column_confirm)
		case "column_confirm":
			if(isset($_GET['check']))
				$pks = $_GET['check'];
			elseif(isset($_GET['pk']))
				$pks = array($_GET['pk']);
			else $pks = array();

			if(sizeof($pks)==0) //nothing was selected so show an error
			{
				echo "<div class='confirm'>";
				echo $lang['err'].": ".$lang['no_sel'];
				echo "</div>";
				echo "<br/><br/>";
				echo $params->getLink(array('action'=>'column_view'), $lang['return']);
			}
			else
			{
				$str = $pks[0];
				$pkVal = $pks[0];
				for($i=1; $i<sizeof($pks); $i++)
				{
					$str .= ", ".$pks[$i];
					$pkVal .= ":".$pks[$i];
				}
				echo $params->getForm(array('action'=>$_GET['action2'], 'confirm'=>'1', 'pk'=>$pkVal));
				echo "<div class='confirm'>";
				printf($lang['ques_'.$_GET['action2']], htmlencode($str), htmlencode($target_table));
				echo "<br/><br/>";
				echo "<input type='submit' value='".$lang['confirm']."' class='btn'/> ";
				echo $params->getLink(array('action'=>'column_view'), $lang['cancel']);
				echo "</div>";
			}
			break;

		//- Edit column (=column_edit)
		case "column_edit":
			echo "<h2>".sprintf($lang['edit_col'], htmlencode($_GET['pk']))." ".$lang['on_tbl']." '".htmlencode($target_table)."'</h2>";
			echo $lang['sqlite_limit']."<br/><br/>";
			if(!isset($_GET['pk']))
				echo $lang['specify_col'];
			else if (!$target_table)
				echo $lang['specify_tbl'];
			else
			{
				$tableInfo = $db->getTableInfo($target_table);

				for($i=0; $i<sizeof($tableInfo); $i++)
				{
					if($tableInfo[$i][1]==$_GET['pk'])
					{
						$colVal = $tableInfo[$i][0];
						$fieldVal = $tableInfo[$i][1];
						$typeVal = $tableInfo[$i]['type'];
						$notnullVal = $tableInfo[$i][3];
						$defaultVal = $tableInfo[$i][4];
						$primarykeyVal = $tableInfo[$i][5];
						break;
					}
				}

				if(!isset($fieldVal))
				{
					echo "<div class='confirm'>".$lang['err'].": ".sprintf($lang['col_inexistent'], htmlencode($_GET['pk']))."</div>";
				}
				else
				{
					$name = $target_table;
					echo $params->getForm(array('action'=>'column_edit', 'confirm'=>'1'));
					echo "<input type='hidden' name='oldvalue' value='".htmlencode($_GET['pk'])."'/>";
					echo "<table border='0' cellpadding='2' cellspacing='1' class='viewTable'>";
					echo "<tr>";
					//$headings = array("Field", "Type", "Primary Key", "Autoincrement", "Not NULL", "Default Value");
					$headings = array($lang["fld"], $lang["type"]);
					for($k=0; $k<count($headings); $k++)
						echo "<td class='tdheader'>".$headings[$k]."</td>";
					echo "</tr>";

					$i = 0;
					$tdWithClass = "<td class='td" . ($i%2 ? "1" : "2") . "'>";
					echo "<tr>";
					echo $tdWithClass;
					echo "<input type='text' name='".$i."_field' style='width:200px;' value='".htmlencode($fieldVal)."'/>";
					echo "</td>";
					echo $tdWithClass;
					echo "<select name='".$i."_type' id='i".$i."_type' onchange='toggleAutoincrement(".$i.");'>";
					if(!in_array($typeVal, $sqlite_datatypes))
						echo "<option value='".htmlencode($typeVal)."' selected='selected'>".htmlencode($typeVal)."</option>";
					foreach ($sqlite_datatypes as $t) {
						if($t==$typeVal)
							echo "<option value='".htmlencode($t)."' selected='selected'>".htmlencode($t)."</option>";
						else
							echo "<option value='".htmlencode($t)."'>".htmlencode($t)."</option>";
					}
					echo "</select>";
					echo "</td>";
					/*
					echo $tdWithClass;
					if($primarykeyVal)
						echo "<input type='checkbox' name='".$i."_primarykey' checked='checked'/> Yes";
					else
						echo "<input type='checkbox' name='".$i."_primarykey'/> Yes";
					echo "</td>";
					echo $tdWithClass;
					if(1==2)
						echo "<input type='checkbox' name='".$i."_autoincrement' id='".$i."_autoincrement' checked='checked'/> Yes";
					else
						echo "<input type='checkbox' name='".$i."_autoincrement' id='".$i."_autoincrement'/> Yes";
					echo "</td>";
					echo $tdWithClass;
					if($notnullVal)
						echo "<input type='checkbox' name='".$i."_notnull' checked='checked'/> Yes";
					else
						echo "<input type='checkbox' name='".$i."_notnull'/> Yes";
					echo "</td>";
					echo $tdWithClass;
					echo "<input type='text' name='".$i."_defaultvalue' value='".$defaultVal."' style='width:100px;'/>";
					echo "</td>";
					*/
					echo "</tr>";

					echo "<tr>";
					echo "<td class='tdheader' style='text-align:right;' colspan='6'>";
					echo "<input type='submit' value='".$lang['save_ch']."' class='btn'/> ";
					echo $params->getLink(array('action'=>'column_view'), $lang['cancel']);
					echo "</td>";
					echo "</tr>";
					echo "</table>";
					echo "</form>";
				}
			}
			break;

		//- Delete index (=index_delete)
		case "index_delete":
			echo $params->getForm(array('action'=>'index_delete', 'pk'=>$_GET['pk'], 'confirm'=>'1'));
			echo "<div class='confirm'>";
			echo sprintf($lang['ques_del_index'], htmlencode($_GET['pk']))."<br/><br/>";
			echo "<input type='submit' value='".$lang['confirm']."' class='btn'/> ";
			echo $params->getLink(array('action'=>'column_view'), $lang['cancel']);
			echo "</div>";
			echo "</form>";
			break;

		//- Delete trigger (=trigger_delete)
		case "trigger_delete":
			echo $params->getForm(array('action'=>'trigger_delete', 'pk'=>$_GET['pk'], 'confirm'=>'1'));
			echo "<div class='confirm'>";
			echo sprintf($lang['ques_del_trigger'], htmlencode($_GET['pk']))."<br/><br/>";
			echo "<input type='submit' value='".$lang['confirm']."' class='btn'/> ";
			echo $params->getLink(array('action'=>'column_view'), $lang['cancel']);
			echo "</div>";
			echo "</form>";
			break;

		//- Create trigger (=trigger_create)
		case "trigger_create":
			echo "<h2>".$lang['create_trigger']." '".htmlencode($_GET['table'])."'</h2>";
			if($_GET['table']=="")
				echo $lang['specify_tbl'];
			else
			{
				echo $params->getForm(array('action'=>'trigger_create', 'confirm'=>'1'));
				echo $lang['trigger_name'].": <input type='text' name='trigger_name'/><br/><br/>";
				echo "<fieldset><legend>".$lang['db_event']."</legend>";
				echo $lang['before']."/".$lang['after'].": ";
				echo "<select name='beforeafter'>";
				echo "<option value=''></option>";
				echo "<option value='BEFORE'>".$lang['before']."</option>";
				echo "<option value='AFTER'>".$lang['after']."</option>";
				echo "<option value='INSTEAD OF'>".$lang['instead']."</option>";
				echo "</select>";
				echo "<br/><br/>";
				echo $lang['event'].": ";
				echo "<select name='event'>";
				echo "<option value='DELETE'>".$lang['del']."</option>";
				echo "<option value='INSERT'>".$lang['insert']."</option>";
				echo "<option value='UPDATE'>".$lang['update']."</option>";
				echo "</select>";
				echo "</fieldset><br/><br/>";
				echo "<fieldset><legend>".$lang['trigger_act']."</legend>";
				echo "<label><input type='checkbox' name='foreachrow'/> ".$lang['each_row']."</label><br/><br/>";
				echo $lang['when_exp'].":<br/>";
				echo "<textarea name='whenexpression' style='width:500px; height:100px;' rows='8' cols='50'></textarea>";
				echo "<br/><br/>";
				echo $lang['trigger_step'].":<br/>";
				echo "<textarea name='triggersteps' style='width:500px; height:100px;' rows='8' cols='50'></textarea>";
				echo "</fieldset><br/><br/>";
				echo "<input type='submit' value='".$lang['create_trigger2']."' class='btn'/> ";
				echo $params->getLink(array('action'=>'column_view'), $lang['cancel']);
				echo "</form>";
			}
			break;

		//- Create index (=index_create)
		case "index_create":
			echo "<h2>".$lang['create_index']." '".htmlencode($_GET['table'])."'</h2>";
			if($_GET['numcolumns']=="" || intval($_GET['numcolumns'])<=0)
				echo $lang['specify_fields'];
			else if($_GET['table']=="")
				echo $lang['specify_tbl'];
			else
			{
				echo $params->getForm(array('action'=>'index_create', 'confirm'=>'1'));
				$num = intval($_GET['numcolumns']);
				$tableInfo = $db->getTableInfo($_GET['table']);
				echo "<fieldset><legend>".$lang['define_index']."</legend>";
				echo "<label for='index_name'>".$lang['index_name'].":</label> <input type='text' name='name' id='index_name'/><br/>";
				echo "<label for='index_duplicate'>".$lang['dup_val'].":</label>";
				echo "<select name='duplicate' id='index_duplicate'>";
				echo "<option value='yes'>".$lang['allow']."</option>";
				echo "<option value='no'>".$lang['not_allow']."</option>";
				echo "</select><br/>";
				if(version_compare($db->getSQLiteVersion(),'3.8.0')>=0)
					echo "<label for='index_where'>WHERE:</label> <input type='text' name='where' id='index_where'/> ".helpLink($lang['help10']);
				echo "</fieldset>";
				echo "<br/>";
				echo "<fieldset><legend>".$lang['define_in_col']."</legend>";
				for($i=0; $i<$num; $i++)
				{
					echo "<select name='".$i."_field'>";
					echo "<option value=''>--".$lang['ignore']."--</option>";
					for($j=0; $j<sizeof($tableInfo); $j++)
						echo "<option value='".htmlencode($tableInfo[$j][1])."'>".htmlencode($tableInfo[$j][1])."</option>";
					echo "</select> ";
					echo "<select name='".$i."_order'>";
					echo "<option value=''></option>";
					echo "<option value=' ASC'>".$lang['asc']."</option>";
					echo "<option value=' DESC'>".$lang['desc']."</option>";
					echo "</select><br/>";
				}
				echo "</fieldset>";
				echo "<br/><br/>";
				echo "<input type='hidden' name='num' value='".$num."'/>";
				echo "<input type='submit' value='".$lang['create_index1']."' class='btn'/> ";
				echo $params->getLink(array('action'=>'column_view'), $lang['cancel']);
				echo "</form>";
			}
			break;
	}
	echo "</div>";
}

//- HMTL: views for databases
if(!$target_table && !isset($_GET['confirm']) && (!isset($_GET['action']) || (isset($_GET['action']) && $_GET['action']!="table_create"))) //the absence of these fields means we are viewing the database homepage
{
  //- Switch on $view (actually a series of if-else)

	if($view=="structure")
	{
		//- Database structure, shows all the tables (=structure)

		if($db->isWritable() && !$db->isDirWritable())
		{
			echo "<div class='confirm' style='margin:10px 0'>";
			echo $lang['attention'].': '.$lang['directory_not_writable'];
			echo "</div><br/>";
		}
		elseif(!$db->isWritable())
		{
			echo "<div class='confirm' style='margin:10px 0;'>";
			echo $lang['attention'].': '.$lang['database_not_writable'];
			echo "</div><br/>";
		}

		if ($auth->isPasswordDefault())
		{
			echo "<div class='confirm' style='margin:20px 0px;'>";
			echo sprintf($lang['warn_passwd'],(is_readable('phpliteadmin.config.php')?'phpliteadmin.config.php':basename(__FILE__)))."<br />".$lang['warn0'];
			echo "</div>";
		}

		echo "<b>".$lang['db_name']."</b>: ".htmlencode($db->getName())."<br/>";
		echo "<b>".$lang['db_path']."</b>: ".htmlencode($db->getPath())."<br/>";
		echo "<b>".$lang['db_size']."</b>: ".number_format($db->getSize())." KiB<br/>";
		echo "<b>".$lang['db_mod']."</b>: ".$db->getDate()."<br/>";
		echo "<b>".$lang['sqlite_v']."</b>: ".$db->getSQLiteVersion()."<br/>";
		echo "<b>".$lang['sqlite_ext']."</b> ".helpLink($lang['help1']).": ".$db->getType()."<br/>";
		echo "<b>".$lang['php_v']."</b>: ".phpversion()."<br/>";
		echo "<b>".PROJECT." ".$lang["ver"]."</b>: ".VERSION;
		echo " <a href='".PROJECT_URL."' target='_blank' id='oldVersion' style='display: none;' class='warning'>".$lang['new_version']."</a><br/><br/>";
		echo "<script type='text/javascript'>checkVersion('".VERSION."','".VERSION_CHECK_URL."');</script>";

		if(isset($_GET['sort']) && ($_GET['sort']=='type' || $_GET['sort']=='name'))
			$_SESSION[COOKIENAME.'sortTables'] = $_GET['sort'];
		if(isset($_GET['order']) && ($_GET['order']=='ASC' || $_GET['order']=='DESC'))
			$_SESSION[COOKIENAME.'orderTables'] = $_GET['order'];

		if(!isset($_SESSION[COOKIENAME.'sortTables']))
			$_SESSION[COOKIENAME.'sortTables'] = 'name';

		if(!isset($_SESSION[COOKIENAME.'orderTables']))
			$_SESSION[COOKIENAME.'orderTables'] = 'ASC';

		$tables = $db->getTables(true, false, $_SESSION[COOKIENAME.'sortTables'], $_SESSION[COOKIENAME.'orderTables']);

		if(sizeof($tables)==0)
			echo $lang['no_tbl']."<br/><br/>";
		else
		{
			echo "<table border='0' cellpadding='2' cellspacing='1' class='viewTable'>";
			echo "<tr>";

			echo "<td class='tdheader'>";
			if(isset($_SESSION[COOKIENAME.'sortTables']))
				$orderTag = ($_SESSION[COOKIENAME.'sortTables']=="type" && $_SESSION[COOKIENAME.'orderTables']=="ASC") ? "DESC" : "ASC";
			else
				$orderTag = "ASC";
			echo $params->getLink(array('sort'=>'type', 'order'=>$orderTag), $lang['type']);
			echo helpLink($lang['help3']);
			if(isset($_SESSION[COOKIENAME.'sortTables']) && $_SESSION[COOKIENAME.'sortTables']=="type")
				echo (($_SESSION[COOKIENAME.'orderTables']=="ASC") ? " <b>&uarr;</b>" : " <b>&darr;</b>");
			echo "</td>";

			echo "<td class='tdheader'>";
			if(isset($_SESSION[COOKIENAME.'sortTables']))
				$orderTag = ($_SESSION[COOKIENAME.'sortTables']=="name" && $_SESSION[COOKIENAME.'orderTables']=="ASC") ? "DESC" : "ASC";
			else
				$orderTag = "ASC";
			echo $params->getLink(array('sort'=>'name', 'order'=>$orderTag), $lang['name']);
			if(isset($_SESSION[COOKIENAME.'sortTables']) && $_SESSION[COOKIENAME.'sortTables']=="name")
				echo (($_SESSION[COOKIENAME.'orderTables']=="ASC") ? " <b>&uarr;</b>" : " <b>&darr;</b>");
			echo "</td>";

			echo "<td class='tdheader' colspan='10'>".$lang['act']."</td>";
			echo "<td class='tdheader'>".$lang['rec']."</td>";
			echo "</tr>";

			$totalRecords = 0;
			$skippedTables = false;
			foreach($tables as $tableName => $tableType)
			{
				$records = $db->numRows($tableName, (!isset($_GET['forceCount'])));
				if($records == '?')
				{
					$skippedTables = true;
					$records = $params->getLink(array('forceCount'=>'1'), '?');
				}
				else
					$totalRecords += $records;
				$tdWithClass = "<td class='td".($i%2 ? "1" : "2")."'>";
				$tdWithClassLeft = "<td class='td".($i%2 ? "1" : "2")."' style='text-align:left;'>";

				echo "<tr>";
				echo $tdWithClassLeft;
				echo ($tableType=="table"? $lang['tbl'] : $lang['view']);
				echo "</td>";
				echo $tdWithClassLeft;
				echo $params->getLink(array('table'=>$tableName, 'action'=>'row_view'), htmlencode($tableName));
				echo "</td>";
				echo $tdWithClass;
				echo $params->getLink(array('table'=>$tableName, 'action'=>'row_view'), $lang['browse']);
				echo "</td>";
				echo $tdWithClass;
				echo $params->getLink(array('table'=>$tableName, 'action'=>'column_view'), $lang['struct']);
				echo "</td>";
				echo $tdWithClass;
				echo $params->getLink(array('table'=>$tableName, 'action'=>'table_sql'), $lang['sql']);
				echo "</td>";
				echo $tdWithClass;
				echo $params->getLink(array('table'=>$tableName, 'action'=>'table_search'), $lang['srch']);
				echo "</td>";
				echo $tdWithClass;
				if($tableType=="table" && $db->isWritable() && $db->isDirWritable())
					echo $params->getLink(array('table'=>$tableName, 'action'=>'row_create'), $lang['insert']);
				else
					echo $lang['insert'];
				echo "</td>";
				echo $tdWithClass;
				echo $params->getLink(array('table'=>$tableName, 'action'=>'table_export'), $lang['export']);
				echo "</td>";
				echo $tdWithClass;
				if($tableType=="table" && $db->isWritable() && $db->isDirWritable())
					echo $params->getLink(array('table'=>$tableName, 'action'=>'table_import'), $lang['import']);
				else
					echo $lang['import'];
				echo "</td>";
				echo $tdWithClass;
				if($db->isWritable() && $db->isDirWritable())
					echo $params->getLink(array('table'=>$tableName, 'action'=>'table_rename'), $lang['rename']);
				else
					echo $lang['rename'];
				echo "</td>";
				echo $tdWithClass;
				if($tableType=="table" && $db->isWritable() && $db->isDirWritable())
					echo $params->getLink(array('table'=>$tableName, 'action'=>'table_empty'), $lang['empty'], 'empty');
				else
					echo $lang['empty'];
				echo "</td>";
				echo $tdWithClass;
				if($db->isWritable() && $db->isDirWritable())
					echo $params->getLink(array('table'=>$tableName, 'action'=>'table_drop'), $lang['drop'], 'drop');
				else
					echo $lang['drop'];
				echo "</td>";
				echo $tdWithClass;
				echo $records;
				echo "</td>";
				echo "</tr>";
			}
			echo "<tr>";
			echo "<td class='tdheader' colspan='12'>".sizeof($tables)." ".$lang['total']."</td>";
			echo "<td class='tdheader' colspan='1' style='text-align:right;'>".$totalRecords.($skippedTables?" ".$params->getLink(array('forceCount'=>'1'),'+ ?'):"")."</td>";
			echo "</tr>";
			echo "</table>";
			echo "<br/>";
			if($skippedTables)
				echo "<div class='confirm' style='margin-bottom:20px;'>".sprintf($lang["counting_skipped"],"<a href='".$params->getURL(array('forceCount'=>'1'))."'>","</a>")."</div>";
		}
		if($db->isWritable() && $db->isDirWritable())
		{
			echo "<fieldset>";
			echo "<legend><b>".$lang['create_tbl_db']." '".htmlencode($db->getName())."'</b></legend>";
			echo $params->getForm(array('action'=>'table_create'), 'get');
			echo $lang['name'].": <input type='text' name='tablename' style='width:200px;'/> ";
			echo $lang['fld_num'].": <input type='text' name='tablefields' style='width:90px;'/> ";
			echo "<input type='submit' name='createtable' value='".$lang['go']."' class='btn'/>";
			echo "</form>";
			echo "</fieldset>";
			echo "<br/>";
			echo "<fieldset>";
			echo "<legend><b>".$lang['create_view']." '".htmlencode($db->getName())."'</b></legend>";
			echo $params->getForm(array('action'=>'view_create', 'confirm'=>'1'));
			echo $lang['name'].": <input type='text' name='viewname' style='width:200px;'/> ";
			echo $lang['sel_state']." ".helpLink($lang['help4']).": <input type='text' name='select' style='width:400px;'/> ";
			echo "<input type='submit' name='createtable' value='".$lang['go']."' class='btn'/>";
			echo "</form>";
			echo "</fieldset>";
		}
	}
	else if($view=="sql")
	{
		//- Database SQL editor (=sql)
		if(isset($_POST['query']) && $_POST['query']!="")
		{
			$delimiter = $_POST['delimiter'];
			$queryStr = $_POST['queryval'];
			//save the queries in history if necessary
			if($maxSavedQueries!=0 && $maxSavedQueries!=false)
			{
				if(!isset($_SESSION[COOKIENAME.'query_history']))
					$_SESSION[COOKIENAME.'query_history'] = array();
				$_SESSION[COOKIENAME.'query_history'][md5(strtolower($queryStr))] = $queryStr;
				if(sizeof($_SESSION[COOKIENAME.'query_history']) > $maxSavedQueries)
					array_shift($_SESSION[COOKIENAME.'query_history']);
			}
			$query = explode_sql($delimiter, $queryStr); //explode the query string into individual queries based on the delimiter

			for($i=0; $i<sizeof($query); $i++) //iterate through the queries exploded by the delimiter
			{
				if(str_replace(" ", "", str_replace("\n", "", str_replace("\r", "", $query[$i])))!="") //make sure this query is not an empty string
				{
					$queryTimer = new MicroTimer();
					$table_result = $db->query($query[$i]);

					echo "<div class='confirm'>";
					echo "<b>".htmlencode($query[$i])."</b>";
					if($table_result === NULL || $table_result === false)
					{
						echo "<br /><b>".$lang['err'].": ".htmlencode($db->getError())."</b></div>";
					}
					echo "</div><br/>";
					if($row = $db->fetch($table_result, 'num'))
					{
						for($j=0; $j<sizeof($row);$j++)
							$headers[$j] = $db->getColumnName($table_result,$j);
						echo "<table border='0' cellpadding='2' cellspacing='1' class='viewTable'>";
						echo "<tr>";
						for($j=0; $j<sizeof($headers); $j++)
						{
							echo "<td class='tdheader'>";
							echo htmlencode($headers[$j]);
							echo "</td>";
						}
						echo "</tr>";
						$rowCount = 0;
						for(; $rowCount==0 || $row = $db->fetch($table_result, 'num'); $rowCount++)
						{
							$tdWithClass = "<td class='td".($rowCount%2 ? "1" : "2")."'>";
							echo "<tr>";
							for($z=0; $z<sizeof($headers); $z++)
							{
								echo $tdWithClass;
								if($row[$z]==="")
									echo "&nbsp;";
								elseif($row[$z]===NULL)
									echo "<i class='null'>NULL</i>";
								else
									echo htmlencode(subString($row[$z]));
								echo "</td>";
							}
							echo "</tr>";
						}
						$queryTimer->stop();
						echo "</table><br/><br/>";


						if($table_result !== NULL && $table_result !== false)
						{
							echo "<div class='confirm' style='margin-bottom: 2em'>";
							if($rowCount>0 || $db->getAffectedRows()==0)
							{
								printf($lang['show_rows'], $rowCount);
							}
							if($db->getAffectedRows()>0 || $rowCount==0)
							{
								echo $db->getAffectedRows()." ".$lang['rows_aff']." ";
							}
							printf($lang['query_time'], $queryTimer);
							echo "</div>";
						}


					}
				}
			}
		}
		else
		{
			$delimiter = ";";
			$queryStr = "";
		}

		echo "<fieldset>";
		echo "<legend><b>".sprintf($lang['run_sql'],htmlencode($db->getName()))."</b></legend>";
		echo $params->getForm(array('view'=>'sql'));
		if(isset($_SESSION[COOKIENAME.'query_history']) && sizeof($_SESSION[COOKIENAME.'query_history'])>0)
		{
			echo "<b>".$lang['recent_queries']."</b><ul>";
			foreach($_SESSION[COOKIENAME.'query_history'] as $key => $value)
			{
				echo "<li><a onclick='sqleditorSetValue(this.textContent); return false;' href='#'>".htmlencode($value)."</a></li>";
			}
			echo "</ul><br/><br/>";
		}
		echo "<textarea style='width:100%; height:300px;' name='queryval' id='queryval' cols='50' rows='8'>".htmlencode($queryStr)."</textarea>";
		echo "<script>sqleditor(document.getElementById('queryval'),".json_encode($db->getTableDefinitions()).", null);</script>";
		echo $lang['delimit']." <input type='text' name='delimiter' value='".htmlencode($delimiter)."' style='width:50px;'/> ";
		echo "<input type='submit' name='query' value='".$lang['go']."' class='btn'/>";
		echo "</form>";
		echo "</fieldset>";
	}
	else if($view=="vacuum")
	{
		//- Vacuum database confirmation (=vacuum)
		if(isset($_POST['vacuum']))
		{
			$query = "VACUUM";
			$db->query($query);
			echo "<div class='confirm'>";
			printf($lang['db_vac'], htmlencode($db->getName()));
			echo "</div><br/>";
		}
		echo $params->getForm(array('view'=>'vacuum'));
		printf($lang['vac_desc'],htmlencode($db->getName()));
		echo "<br/><br/>";
		echo "<input type='submit' value='".$lang['vac']."' name='vacuum' class='btn'/>";
		echo "</form>";
	}
	else if($view=="export")
	{
		//- Export view (=export)
		echo $params->getForm(array('view'=>'export'));
		echo "<fieldset style='float:left; width:260px; margin-right:20px;'><legend><b>".$lang['export']."</b></legend>";
		echo "<select multiple='multiple' size='10' style='width:240px;' name='tables[]'>";
		$tables = $db->getTables(true, false);
		foreach($tables as $tableName => $tableType)
		{
			echo "<option value='".htmlencode($tableName)."' selected='selected'>".htmlencode($tableName)."</option>";
		}
		echo "</select>";
		echo "<br/><br/>";
		echo "<label><input type='radio' name='export_type' checked='checked' value='sql' onclick='toggleExports(\"sql\");'/> ".$lang['sql']."</label>";
		echo "<br/><label><input type='radio' name='export_type' value='csv' onclick='toggleExports(\"csv\");'/> ".$lang['csv']."</label>";
		echo "</fieldset>";

		echo "<fieldset style='float:left; max-width:350px;' id='exportoptions_sql'><legend><b>".$lang['options']."</b></legend>";
		echo "<label><input type='checkbox' checked='checked' name='structure'/> ".$lang['export_struct']."</label> ".helpLink($lang['help5'])."<br/>";
		echo "<label><input type='checkbox' checked='checked' name='data'/> ".$lang['export_data']."</label> ".helpLink($lang['help6'])."<br/>";
		echo "<label><input type='checkbox' name='drop'/> ".$lang['add_drop']."</label> ".helpLink($lang['help7'])."<br/>";
		echo "<label><input type='checkbox' checked='checked' name='transaction'/> ".$lang['add_transact']."</label> ".helpLink($lang['help8'])."<br/>";
		echo "<label><input type='checkbox' checked='checked' name='comments'/> ".$lang['comments']."</label> ".helpLink($lang['help9'])."<br/>";
		echo "</fieldset>";

		echo "<fieldset style='float:left; max-width:350px; display:none;' id='exportoptions_csv'><legend><b>".$lang['options']."</b></legend>";
		echo "<div style='float:left;'>".$lang['fld_terminated']."</div>";
		echo "<input type='text' value=';' name='export_csv_fieldsterminated' style='float:right;'/>";
		echo "<div style='clear:both;'>";
		echo "<div style='float:left;'>".$lang['fld_enclosed']."</div>";
		echo "<input type='text' value='\"' name='export_csv_fieldsenclosed' style='float:right;'/>";
		echo "<div style='clear:both;'>";
		echo "<div style='float:left;'>".$lang['fld_escaped']."</div>";
		echo "<input type='text' value='\' name='export_csv_fieldsescaped' style='float:right;'/>";
		echo "<div style='clear:both;'>";
		echo "<div style='float:left;'>".$lang['rep_null']."</div>";
		echo "<input type='text' value='NULL' name='export_csv_replacenull' style='float:right;'/>";
		echo "<div style='clear:both;'>";
		echo "<label><input type='checkbox' name='export_csv_crlf'/> ".$lang['rem_crlf']."</label><br/>";
		echo "<label><input type='checkbox' checked='checked' name='export_csv_fieldnames'/> ".$lang['put_fld']."</label>";
		echo "</fieldset>";

		echo "<div style='clear:both;'></div>";
		echo "<br/><br/>";
		echo "<fieldset><legend><b>".$lang['save_as']."</b></legend>";
		$file = pathinfo($db->getPath());
		$name = $file['filename'];
		echo "<input type='text' name='filename' value='".htmlencode($name)."_".date("Y-m-d").".dump' style='width:400px;'/> <input type='submit' name='export' value='".$lang['export']."' class='btn'/>";
		echo "</fieldset>";
		echo "</form>";
		echo "<div class='confirm' style='margin-top: 2em'>".sprintf($lang['backup_hint'],
			$params->getLink(array('download'=>$currentDB['path'], 'token'=>$_SESSION[COOKIENAME.'token']), $lang["backup_hint_linktext"], '', $lang['backup'])
			)."</div>";
	}
	else if($view=="import")
	{
		//- Import view (=import)
		if(isset($_POST['import']))
		{
			echo "<div class='confirm'>";
			if($importSuccess===true)
				echo $lang['import_suc'];
			else
				echo $importSuccess;
			echo "</div><br/>";
		}

		echo $params->getForm(array('view'=>'import'), 'post', true);
		echo "<fieldset style='float:left; width:260px; margin-right:20px;'><legend><b>".$lang['import']."</b></legend>";
		echo "<label><input type='radio' name='import_type' checked='checked' value='sql' onclick='toggleImports(\"sql\");'/> ".$lang['sql']."</label>";
		echo "<br/><label><input type='radio' name='import_type' value='csv' onclick='toggleImports(\"csv\");'/> ".$lang['csv']."</label>";
		echo "</fieldset>";

		echo "<fieldset style='float:left; max-width:350px;' id='importoptions_sql'><legend><b>".$lang['options']."</b></legend>";
		echo $lang['no_opt'];
		echo "</fieldset>";

		echo "<fieldset style='float:left; max-width:350px; display:none;' id='importoptions_csv'><legend><b>".$lang['options']."</b></legend>";
		echo "<div style='float:left;'>".$lang['csv_tbl']."</div>";
		echo "<select name='single_table' style='float:right;'>";
		$tables = $db->getTables(true, false);
		echo "<option value=''>(".$lang['create_tbl'].")</option>";
		foreach($tables as  $tableName => $tableType)
		{
			echo "<option value='".htmlencode($tableName)."'>".htmlencode($tableName)."</option>";
		}
		echo "</select>";
		echo "<div style='clear:both;'>";
		echo "<div style='float:left;'>".$lang['fld_terminated']."</div>";
		echo "<input type='text' value=';' name='import_csv_fieldsterminated' style='float:right;'/>";
		echo "<div style='clear:both;'>";
		echo "<div style='float:left;'>".$lang['fld_enclosed']."</div>";
		echo "<input type='text' value='\"' name='import_csv_fieldsenclosed' style='float:right;'/>";
		echo "<div style='clear:both;'>";
		echo "<div style='float:left;'>".$lang['fld_escaped']."</div>";
		echo "<input type='text' value='\' name='import_csv_fieldsescaped' style='float:right;'/>";
		echo "<div style='clear:both;'>";
		echo "<div style='float:left;'>".$lang['null_represent']."</div>";
		echo "<input type='text' value='NULL' name='import_csv_replacenull' style='float:right;'/>";
		echo "<div style='clear:both;'>";
		echo "<label><input type='checkbox' checked='checked' name='import_csv_fieldnames'/> ".$lang['fld_names']."</label>";
		echo "</fieldset>";

		echo "<div style='clear:both;'></div>";
		echo "<br/><br/>";

		echo "<fieldset><legend><b>".$lang['import_f']."</b></legend>";
		echo "<em>".$lang['max_file_size'].": ".number_format(fileUploadMaxSize()/1024/1024)." MiB</em> ".helpLink($lang['help11'])."<br />";
		echo "<input type='file' value='".$lang['choose_f']."' name='file' style='background-color:transparent; border-style:none; margin:0; padding:0' onchange='checkFileSize(this)'/>";
		echo "<input type='submit' value='".$lang['import']."' name='import' class='btn'/>";
		echo "</fieldset>";
	}
	else if($view=="rename")
	{
		//- Rename database confirmation (=rename)
		echo $params->getForm(array('view'=>'rename', 'database_rename'=>'1'));
		echo "<input type='hidden' name='oldname' value='".htmlencode($db->getPath())."'/>";
		echo $lang['db_rename']." '".htmlencode($db->getPath())."' ".$lang['to']." <input type='text' name='newname' style='width:200px;' value='".htmlencode($db->getPath())."'/> <input type='submit' value='".$lang['rename']."' name='rename' class='btn'/>";
		echo "</form>";
	}
	else if($view=="delete")
	{
		//- Delete database confirmation (=delete)
		echo $params->getForm(array('database_delete'=>'1'));
		echo "<div class='confirm'>";
		echo sprintf($lang['ques_del_db'],htmlencode($db->getPath()))."<br/><br/>";
		echo "<input name='database_delete' value='".htmlencode($db->getPath())."' type='hidden'/>";
		echo "<input type='submit' value='".$lang['confirm']."' class='btn'/> ";
		echo $params->getLink(array(), $lang['cancel']);
		echo "</div>";
		echo "</form>";
	}

	echo "</div>";
}
echo "</div>";

//- HTML: page footer
echo "<br/>";
echo "<span style='font-size:11px;'>".$lang['powered']." <a href='".PROJECT_URL."' target='_blank' style='font-size:11px;'>".PROJECT."</a> | ";
echo $lang['free_software']." <a href='".DONATE_URL."' target='_blank' style='font-size:11px;'>".$lang['please_donate']."</a> | ";
printf($lang['page_gen'], $pageTimer);
echo "</span>";
echo "</td></tr></table>";
$db->close(); //close the database
echo "</body>";
echo "</html>";

//- End of main code

// Authorization class
// Maintains user's logged-in state and security of application
//

class Authorization
{
	private $authorized;
	private $login_failed;
	private $system_password_encrypted;

	public function __construct()
	{
		// first, make sure a CSRF token is generated
		$this->generateToken();
		// second, check for possible CSRF attacks. to protect logins, this is done before checking login
		$this->checkToken();
		
		// the salt and password encrypting is probably unnecessary protection but is done just
		// for the sake of being very secure
		if(!isset($_SESSION[COOKIENAME.'_salt']) && !isset($_COOKIE[COOKIENAME.'_salt']))
		{
			// create a random salt for this session if a cookie doesn't already exist for it
			$_SESSION[COOKIENAME.'_salt'] = self::generateSalt(22);
		}
		else if(!isset($_SESSION[COOKIENAME.'_salt']) && isset($_COOKIE[COOKIENAME.'_salt']))
		{
			// session doesn't exist, but cookie does so grab it
			$_SESSION[COOKIENAME.'_salt'] = $_COOKIE[COOKIENAME.'_salt'];
		}

		// salted and encrypted password used for checking
		$this->system_password_encrypted = md5(SYSTEMPASSWORD."_".$_SESSION[COOKIENAME.'_salt']);

		$this->authorized =
			// no password
			SYSTEMPASSWORD == ''
			// correct password stored in session
			|| isset($_SESSION[COOKIENAME.'password']) && hash_equals($_SESSION[COOKIENAME.'password'], $this->system_password_encrypted) 
			// correct password stored in cookie
			|| isset($_COOKIE[COOKIENAME]) && isset($_COOKIE[COOKIENAME.'_salt']) && hash_equals(md5(SYSTEMPASSWORD."_".$_COOKIE[COOKIENAME.'_salt']), $_COOKIE[COOKIENAME]);
	}

	public function attemptGrant($password, $remember)
	{
		$hashed_password = crypt(SYSTEMPASSWORD, '$2a$07$'.self::generateSalt(22).'$');
		if (hash_equals($hashed_password, crypt($password, $hashed_password))) {
			if ($remember) {
				// user wants to be remembered, so set a cookie
				$expire = time()+60*60*24*30; //set expiration to 1 month from now
				setcookie(COOKIENAME, $this->system_password_encrypted, $expire, null, null, null, true);
				setcookie(COOKIENAME."_salt", $_SESSION[COOKIENAME.'_salt'], $expire, null, null, null, true);
			} else {
				// user does not want to be remembered, so destroy any potential cookies
				setcookie(COOKIENAME, "", time()-86400, null, null, null, true);
				setcookie(COOKIENAME."_salt", "", time()-86400, null, null, null, true);
				unset($_COOKIE[COOKIENAME]);
				unset($_COOKIE[COOKIENAME.'_salt']);
			}

			$_SESSION[COOKIENAME.'password'] = $this->system_password_encrypted;
			$this->authorized = true;
			return true;
		}

		$this->login_failed = true;
		return false;
	}

	public function revoke()
	{
		//destroy everything - cookies and session vars
		setcookie(COOKIENAME, "", time()-86400, null, null, null, true);
		setcookie(COOKIENAME."_salt", "", time()-86400, null, null, null, true);
		unset($_COOKIE[COOKIENAME]);
		unset($_COOKIE[COOKIENAME.'_salt']);
		session_unset();
		session_destroy();
		$this->authorized = false;
		// start a new session and generate a new CSRF token for the login form
		session_start();
		$this->generateToken();
	}

	public function isAuthorized()
	{
		return $this->authorized;      
	}

	public function isFailedLogin()
	{
		return $this->login_failed;
	}

	public function isPasswordDefault()
	{
		return SYSTEMPASSWORD == 'admin';
	}

	private static function generateSalt($saltSize)
	{
		$set = 'ABCDEFGHiJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
		$setLast = strlen($set) - 1;
		$salt = '';
		while ($saltSize-- > 0) {
			$salt .= $set[mt_rand(0, $setLast)];
		}
		return $salt;
	}
	
	private function generateToken()
	{
		// generate CSRF token 
		if (empty($_SESSION[COOKIENAME.'token']))
		{
			if (function_exists('random_bytes')) // introduced in PHP 7.0
			{
				$_SESSION[COOKIENAME.'token'] = bin2hex(random_bytes(32));
			}
			elseif (function_exists('openssl_random_pseudo_bytes')) // introduced in PHP 5.3.0
			{
				$_SESSION[COOKIENAME.'token'] = bin2hex(openssl_random_pseudo_bytes(32));
			}
			else
			{
				// For PHP 5.2.x - This case can be removed once we drop support for 5.2.x
				$_SESSION[COOKIENAME.'token'] = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
			}
		}
	}
	
	private function checkToken()
	{
		// checking CSRF token
		if($_SERVER['REQUEST_METHOD'] === 'POST' || isset($_GET['download'])) // all POST forms need tokens! downloads are protected as well
		{
			if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['token']))
				$check_token=$_POST['token'];
			elseif($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['token']))
				$check_token=$_GET['token'];
			
			if (!isset($check_token))
			{
				die("CSRF token missing");
			}
			elseif(!hash_equals($_SESSION[COOKIENAME.'token'], $check_token))
			{
				die("CSRF token is wrong - please try to login again");
			}
		}
	}

}
// Database class
// Generic database abstraction class to manage interaction with database without worrying about SQLite vs. PHP versions
//
class Database
{
	protected $db; //reference to the DB object
	protected $type; //the extension for PHP that handles SQLite
	protected $data;
	protected $lastResult;
	protected $alterError;
	protected $debugOutput ='';

	public function __construct($data)
	{
		global $lang, $params;
		$this->data = $data;
		try
		{
			if(!file_exists($this->data["path"]) && !is_writable(dirname($this->data["path"]))) //make sure the containing directory is writable if the database does not exist
			{
				echo "<div class='confirm' style='margin:20px;'>";
				printf($lang['db_not_writeable'], htmlencode($this->data["path"]), htmlencode(dirname($this->data["path"])));
				echo $params->getForm();
				echo "<input type='submit' value='Log Out' name='".$lang['logout']."' class='btn'/>";
				echo "</form>";
				echo "</div><br/>";
				exit();
			}

			$ver = $this->getVersion();

			switch(true)
			{
				case ((!isset($data['type']) || $data['type']!=2) && (FORCETYPE=="PDO" || (FORCETYPE==false && class_exists("PDO") && in_array("sqlite", PDO::getAvailableDrivers()) && ($ver==-1 || $ver==3)))):
					$this->db = new PDO("sqlite:".$this->data['path']);
					if($this->db!=NULL)
					{
						$this->type = "PDO";
						break;
					}
				case ((!isset($data['type']) || $data['type']!=2) && (FORCETYPE=="SQLite3" || (FORCETYPE==false && class_exists("SQLite3") && ($ver==-1 || $ver==3)))):
					$this->db = new SQLite3($this->data['path']);
					if($this->db!=NULL)
					{
						$this->type = "SQLite3";
						break;
					}
				case (FORCETYPE=="SQLiteDatabase" || (FORCETYPE==false && class_exists("SQLiteDatabase") && ($ver==-1 || $ver==2))):
					$this->db = new SQLiteDatabase($this->data['path']);
					if($this->db!=NULL)
					{
						$this->type = "SQLiteDatabase";
						break;
					}
				default:
					$this->showError();
					exit();
			}
			$this->query("PRAGMA foreign_keys = ON");
		}
		catch(Exception $e)
		{
			$this->showError();
			exit();
		}
	}

	public function registerUserFunction($ids)
	{
		// in case a single function id was passed
		if (is_string($ids))
			$ids = array($ids);

		if ($this->type == 'PDO') {
			foreach ($ids as $id) {
				$this->db->sqliteCreateFunction($id, $id, -1);
			}
		} else { // type is Sqlite3 or SQLiteDatabase
			foreach ($ids as $id) {
				$this->db->createFunction($id, $id, -1);
			}
		}
	}
	
	public function getError($complete_msg = false)
	{
		global $lang, $debug;
		$error = "unknown";
		
		if($this->alterError!='')
		{
			$error = $this->alterError;
			$this->alterError = "";
		}
		else if($this->type=="PDO")
		{
			$e = $this->db->errorInfo();
			$error = $e[2];
		}
		else if($this->type=="SQLite3")
		{
			$error = $this->db->lastErrorMsg();
		}
		else
		{
			$error = sqlite_error_string($this->db->lastError());
		}
		
		if($complete_msg)
		{
			$error = $lang['err'].": ".htmlencode($error);
			// do not suggest to report a bug when constraints fail
			if(strpos($error, 'constraint failed')===false)
				$error.="<br/>".$lang['bug_report'].' '.PROJECT_BUGTRACKER_LINK;
		}

		if($debug)
			$error .= $this->getDebugOutput();
		
		return $error;
	}

	function getDebugOutput()
	{
		return ($this->debugOutput != "" ? "<hr /><strong>DEBUG:</strong><br />".$this->debugOutput : $this->debugOutput);
	}
	
	public function showError()
	{
		global $lang;
		$classPDO = class_exists("PDO");
		$classSQLite3 = class_exists("SQLite3");
		$classSQLiteDatabase = class_exists("SQLiteDatabase");
		if($classPDO)	// PDO is there, check if the SQLite driver for PDO is missing
			$PDOSqliteDriver = (in_array("sqlite", PDO::getAvailableDrivers() ));
		else
			$PDOSqliteDriver = false;
		echo "<div class='confirm' style='margin:20px;'>";
		printf($lang['db_setup'], $this->getPath());
		echo ".<br/><br/><i>".$lang['chk_ext']."...<br/><br/>";
		echo "<b>PDO</b>: ".($classPDO ? $lang['installed'] : $lang['not_installed'])."<br/>";
		echo "<b>PDO SQLite Driver</b>: ".($PDOSqliteDriver ? $lang['installed'] : $lang['not_installed'])."<br/>";
		echo "<b>SQLite3</b>: ".($classSQLite3 ? $lang['installed'] : $lang['not_installed'])."<br/>";
		echo "<b>SQLiteDatabase</b>: ".($classSQLiteDatabase ? $lang['installed'] : $lang['not_installed'])."<br/>";
		echo "<br/>...".$lang['done'].".</i><br/><br/>";
		if(!$classPDO && !$classSQLite3 && !$classSQLiteDatabase)
			printf($lang['sqlite_ext_support'], PROJECT);
		else
		{
			if(!$PDOSqliteDriver && !$classSQLite3 && $this->getVersion()==3)
				printf($lang['sqlite_v_error'], 3, PROJECT, 2);
			else if(!$classSQLiteDatabase && $this->getVersion()==2)
				printf($lang['sqlite_v_error'], 2, PROJECT, 3);
			else
				echo $lang['report_issue'].' '.PROJECT_BUGTRACKER_LINK.'.';
		}
		echo "<p>See ".PROJECT_INSTALL_LINK." for help.</p>";
		
		$this->print_db_list();

		echo "</div>";
	}
	
	// print the list of databases
	public function print_db_list()
	{
		global $databases, $lang, $params, $currentDB;
		echo "<fieldset style='margin:15px;' class='databaseList'><legend><b>".$lang['db_ch']."</b></legend>";
		if(sizeof($databases)<10) //if there aren't a lot of databases, just show them as a list of links instead of drop down menu
		{
			$i=0;
			foreach($databases as $database)
			{
				$i++;
				$name = $database['name'];
				if(mb_strlen($name)>25)
					$name = "...".mb_substr($name, mb_strlen($name)-22, 22); 
				echo '[' . ($database['readable'] ? 'r':' ' ) . ($database['writable'] && $database['writable_dir'] ? 'w':' ' ) . ']&nbsp;';
				
				echo $params->getLink(array('database'=>$database['path'], 'table'=>null), htmlencode($name), ($database == $currentDB? 'active_db': '') );
				echo "&nbsp;&nbsp;";
				echo $params->getLink(array('download'=>$database['path'], 'table'=>null, 'token'=>$_SESSION[COOKIENAME.'token']), '[&darr;]', '', $lang['backup']);
				
				if($i<sizeof($databases))
					echo "<br/>";
			}
		}
		else //there are a lot of databases - show a drop down menu
		{
			echo $params->getForm(array('table'=>null), 'get');
			echo "<select name='database' onchange='this.form.submit()'>";
			foreach($databases as $database)
			{
				$perms_string = htmlencode('[' . ($database['readable'] ? 'r':' ' ) . ($database['writable'] && $database['writable_dir'] ? 'w':' ' ) . '] ');
				if($database == $currentDB)
					echo "<option value='".htmlencode($database['path'])."' selected='selected'>".$perms_string.htmlencode($database['name'])."</option>";
				else
					echo "<option value='".htmlencode($database['path'])."'>".$perms_string.htmlencode($database['name'])."</option>";
			}
			echo "</select>";
			echo "<noscript><input type='submit' value='".$lang['go']."' class='btn'></noscript>";
			echo "</form>";
		}
		echo "</fieldset>";	
	}

	public function __destruct()
	{
		if($this->db)
			$this->close();
	}

	//get the exact PHP extension being used for SQLite
	public function getType()
	{
		return $this->type;
	}
	
	// get the version of the SQLite library
	public function getSQLiteVersion()
	{
		$queryVersion = $this->select("SELECT sqlite_version() AS sqlite_version");
		return $queryVersion['sqlite_version'];
	}

	//get the name of the database
	public function getName()
	{
		return $this->data["name"];
	}

	//get the filename of the database
	public function getPath()
	{
		return $this->data["path"];
	}

	//is the db-file writable?
	public function isWritable()
	{
		return $this->data["writable"];
	}

	//is the db-folder writable?
	public function isDirWritable()
	{
		return $this->data["writable_dir"];
	}

	//get the version of the database
	public function getVersion()
	{
		if(file_exists($this->data['path'])) //make sure file exists before getting its contents
		{
			$content = strtolower(file_get_contents($this->data['path'], NULL, NULL, 0, 40)); //get the first 40 characters of the database file
			$p = strpos($content, "** this file contains an sqlite 2"); //this text is at the beginning of every SQLite2 database
			if($p!==false) //the text is found - this is version 2
				return 2;
			else
				return 3;
		}
		else //return -1 to indicate that it does not exist and needs to be created
		{
			return -1;
		}
	}

	//get the size of the database (in KiB)
	public function getSize()
	{
		return round(filesize($this->data["path"])*0.0009765625, 1);
	}

	//get the last modified time of database
	public function getDate()
	{
		global $lang;
		return date($lang['date_format'], filemtime($this->data['path']));
	}

	//get number of affected rows from last query
	public function getAffectedRows()
	{
		if($this->type=="PDO")
			if(!is_object($this->lastResult))
				// in case it was an alter table statement, there is no lastResult object
				return 0;
			else
				return $this->lastResult->rowCount();
		else if($this->type=="SQLite3")
			return $this->db->changes();
		else if($this->type=="SQLiteDatabase")
			return $this->db->changes();
	}

	public function getTypeOfTable($table)
	{
		$result = $this->select("SELECT `type` FROM `sqlite_master` WHERE `name`=" . $this->quote($table), 'assoc');
		return $result['type'];
	}
	
	public function getTableInfo($table)
	{
		return $this->selectArray("PRAGMA table_info(".$this->quote_id($table).")");
	}
	
	// returns the list of tables (opt. incl. views) as
	// array( Tablename => tableType ) with tableType being 'view' or 'table'
	public function getTables($alsoViews=true, $alsoInternal=false, $orderBy='name', $orderDirection='ASC')
	{
		$query = "SELECT name, type FROM sqlite_master " 
			. "WHERE (type='table'".($alsoViews?" OR type='view'":"").") "
			. "AND name!='' ".($alsoInternal? "":" AND name NOT LIKE 'sqlite_%' ")
			. "ORDER BY ".$this->quote_id($orderBy)." ".$orderDirection;
		$result = $this->selectArray($query);
		$list = array();
		for($i=0; $i<sizeof($result); $i++)
		{
			$list[$result[$i]['name']] = $result[$i]['type'];
		}
		return $list;
	}
	
	// returns an array of all tables and their columns as
	// array( tablename => array(columName) )
	public function getTableDefinitions()
	{
		$tables = $this->getTables(true, true);
		$result = array();
		foreach ($tables as $tableName => $tableType)
		{
			$tableInfo = $this->getTableInfo($tableName);
			$columns = array();
			foreach($tableInfo as $column)
				$columns[] = $column['name'];
			$result[$tableName] = $columns;
		}
		return $result;
	}
	
	public function close()
	{
		if($this->type=="PDO")
			$this->db = NULL;
		else if($this->type=="SQLite3")
			$this->db->close();
		else if($this->type=="SQLiteDatabase")
			$this->db = NULL;
	}

	public function beginTransaction()
	{
		$this->query("BEGIN");
	}

	public function commitTransaction()
	{
		$this->query("COMMIT");
	}

	public function rollbackTransaction()
	{
		$this->query("ROLLBACK");
	}

	//generic query wrapper
	//returns false on error and the query result on success
	public function query($query, $ignoreAlterCase=false)
	{
		global $debug;
		if(strtolower(substr(ltrim($query),0,5))=='alter' && $ignoreAlterCase==false) //this query is an ALTER query - call the necessary function
		{
			preg_match("/^\s*ALTER\s+TABLE\s+\"((?:[^\"]|\"\")+)\"\s+(.*)$/i",$query,$matches);
			if(!isset($matches[1]) || !isset($matches[2]))
			{
				if($debug) echo "<span title='".htmlencode($query)."' onclick='this.innerHTML=\"".htmlencode(str_replace('"','\"',$query))."\"' style='cursor:pointer'>SQL?</span><br />";
				return false;
			}
			$tablename = str_replace('""','"',$matches[1]);
			$alterdefs = $matches[2];
			if($debug) echo "ALTER TABLE QUERY=(".htmlencode($query)."), tablename=($tablename), alterdefs=($alterdefs)<br />";
			$result = $this->alterTable($tablename, $alterdefs);
		}
		else //this query is normal - proceed as normal
		{
			$result = $this->db->query($query);
			if($debug) echo "<span title='".htmlencode($query)."' onclick='this.innerHTML=\"".htmlencode(str_replace('"','\"',$query))."\"' style='cursor:pointer'>SQL?</span><br />";
		}
		if($result===false)
			return false;
		$this->lastResult = $result;
		return $result;
	}

	//wrapper for an INSERT and returns the ID of the inserted row
	public function insert($query)
	{
		$result = $this->query($query);
		if($this->type=="PDO")
			return $this->db->lastInsertId();
		else if($this->type=="SQLite3")
			return $this->db->lastInsertRowID();
		else if($this->type=="SQLiteDatabase")
			return $this->db->lastInsertRowid();
	}

	//returns an array for SELECT
	public function select($query, $mode="both")
	{
		$result = $this->query($query);
		if(!$result) //make sure the result is valid
			return NULL;
		if($this->type=="PDO")
		{
			if($mode=="assoc")
				$mode = PDO::FETCH_ASSOC;
			else if($mode=="num")
				$mode = PDO::FETCH_NUM;
			else
				$mode = PDO::FETCH_BOTH;
			$ret = $result->fetch($mode);
			$result->closeCursor();
			return $ret;
		}
		else if($this->type=="SQLite3")
		{
			if($mode=="assoc")
				$mode = SQLITE3_ASSOC;
			else if($mode=="num")
				$mode = SQLITE3_NUM;
			else
				$mode = SQLITE3_BOTH;
			$ret = $result->fetchArray($mode);
			$result->finalize();
			return $ret;
		}
		else if($this->type=="SQLiteDatabase")
		{
			if($mode=="assoc")
				$mode = SQLITE_ASSOC;
			else if($mode=="num")
				$mode = SQLITE_NUM;
			else
				$mode = SQLITE_BOTH;
			return $result->fetch($mode);
		}
	}

	//returns an array of arrays after doing a SELECT
	public function selectArray($query, $mode="both")
	{
		$result = $this->query($query);
		//make sure the result is valid
		if($result=== false || $result===NULL) 
			return NULL;		// error
		if(!is_object($result)) // no rows returned
			return array();
		if($this->type=="PDO")
		{
			if($mode=="assoc")
				$mode = PDO::FETCH_ASSOC;
			else if($mode=="num")
				$mode = PDO::FETCH_NUM;
			else
				$mode = PDO::FETCH_BOTH;
			$ret = $result->fetchAll($mode);
			$result->closeCursor();
			return $ret;
		}
		else if($this->type=="SQLite3")
		{
			if($mode=="assoc")
				$mode = SQLITE3_ASSOC;
			else if($mode=="num")
				$mode = SQLITE3_NUM;
			else
				$mode = SQLITE3_BOTH;
			$arr = array();
			$i = 0;
			while($res = $result->fetchArray($mode))
			{
				$arr[$i] = $res;
				$i++;
			}
			$result->finalize();
			return $arr;
		}
		else if($this->type=="SQLiteDatabase")
		{
			if($mode=="assoc")
				$mode = SQLITE_ASSOC;
			else if($mode=="num")
				$mode = SQLITE_NUM;
			else
				$mode = SQLITE_BOTH;
			return $result->fetchAll($mode);
		}
	}
	
	//returns an array of the next row in $result
	public function fetch($result, $mode="both")
	{
		//make sure the result is valid
		if($result=== false || $result===NULL) 
			return NULL;		// error
		if(!is_object($result)) // no rows returned
			return array();
		if($this->type=="PDO")
		{
			if($mode=="assoc")
				$mode = PDO::FETCH_ASSOC;
			else if($mode=="num")
				$mode = PDO::FETCH_NUM;
			else
				$mode = PDO::FETCH_BOTH;
			return $result->fetch($mode);
		}
		else if($this->type=="SQLite3")
		{
			if($mode=="assoc")
				$mode = SQLITE3_ASSOC;
			else if($mode=="num")
				$mode = SQLITE3_NUM;
			else
				$mode = SQLITE3_BOTH;
			return $result->fetchArray($mode);
		}
		else if($this->type=="SQLiteDatabase")
		{
			if($mode=="assoc")
				$mode = SQLITE_ASSOC;
			else if($mode=="num")
				$mode = SQLITE_NUM;
			else
				$mode = SQLITE_BOTH;
			return $result->fetch($mode);
		}
	}
	
	public function getColumnName($result, $colNum)
	{
		//make sure the result is valid
		if($result=== false || $result===NULL || !is_object($result)) 
			return "";		// error or no rows returned
		if($this->type=="PDO")
		{
			$meta = $result->getColumnMeta($colNum);
			return $meta['name'];
		}
		else if($this->type=="SQLite3")
		{
			return $result->columnName($colNum);
		}
		else if($this->type=="SQLiteDatabase")
		{
			return $result->fieldName($colNum);
		}
	}

	
	// SQlite supports multiple ways of surrounding names in quotes:
	// single-quotes, double-quotes, backticks, square brackets.
	// As sqlite does not keep this strict, we also need to be flexible here.
	// This function generates a regex that matches any of the possibilities.
	private function sqlite_surroundings_preg($name,$preg_quote=true,$notAllowedCharsIfNone="'\"",$notAllowedName=false)
	{
		if($name=="*" || $name=="+")
		{
			if($notAllowedName!==false && $preg_quote)
				$notAllowedName = preg_quote($notAllowedName,"/");
			// use possesive quantifiers to save memory
			// (There is a bug in PCRE starting in 8.13 and fixed in PCRE 8.36
			// why we can't use posesive quantifiers - See issue #310).
			if(version_compare(strstr(constant('PCRE_VERSION'), ' ', true), '8.36', '>=') ||
				version_compare(strstr(constant('PCRE_VERSION'), ' ', true), '8.12', '<='))
				$posessive='+';
			else
				$posessive='';
			
			$nameSingle   = ($notAllowedName!==false?"(?!".$notAllowedName."')":"")."(?:[^']$name+|'')$name".$posessive;
			$nameDouble   = ($notAllowedName!==false?"(?!".$notAllowedName."\")":"")."(?:[^\"]$name+|\"\")$name".$posessive;
			$nameBacktick = ($notAllowedName!==false?"(?!".$notAllowedName."`)":"")."(?:[^`]$name+|``)$name".$posessive;
			$nameSquare   = ($notAllowedName!==false?"(?!".$notAllowedName."\])":"")."(?:[^\]]$name+|\]\])$name".$posessive;
			$nameNo = ($notAllowedName!==false?"(?!".$notAllowedName."\s)":"")."[^".$notAllowedCharsIfNone."]$name";
		}
		else
		{
			if($preg_quote) $name = preg_quote($name,"/");
			
			$nameSingle = str_replace("'","''",$name);
			$nameDouble = str_replace('"','""',$name);
			$nameBacktick = str_replace('`','``',$name);
			$nameSquare = str_replace(']',']]',$name);
			$nameNo = $name;
		}
		
		$preg =	"(?:'".$nameSingle."'|".   // single-quote surrounded or not in quotes (correct SQL for values/new names)
				$nameNo."|".               // not surrounded (correct SQL if not containing reserved words, spaces or some special chars)
				"\"".$nameDouble."\"|".    // double-quote surrounded (correct SQL for identifiers)
				"`".$nameBacktick."`|".    // backtick surrounded (MySQL-Style)
				"\[".$nameSquare."\])";    // square-bracket surrounded (MS Access/SQL server-Style)
		return $preg;
	}
	
	// Returns the last PREG error as a string, '' if no error occured
	private function getPregError()
	{
		$error = preg_last_error();
		switch ($error)
		{
			case PREG_NO_ERROR: return 'No error';
			case PREG_INTERNAL_ERROR: return 'There is an internal error!';
			case PREG_BACKTRACK_LIMIT_ERROR: return 'Backtrack limit was exhausted!';
			case PREG_RECURSION_LIMIT_ERROR: return 'Recursion limit was exhausted!';
			case PREG_BAD_UTF8_ERROR: return 'Bad UTF8 error!';
			// PREG_BAD_UTF8_OFFSET_ERROR is introduced in PHP 5.3.0, which is not yet required by PLA, so we use its value 5 instead so long
			case 5: return 'Bad UTF8 offset error!'; 
			default: return 'Unknown Error';
		} 
	}
	
	// function that is called for an alter table statement in a query
	// code borrowed with permission from http://code.jenseng.com/db/
	// this has been completely debugged / rewritten by Christopher Kramer
	public function alterTable($table, $alterdefs)
	{
		global $debug, $lang;
		$this->alterError="";
		$errormsg = sprintf($lang['alter_failed'],htmlencode($table)).' - ';
		if($debug) $this->debugOutput .= "ALTER TABLE: table=($table), alterdefs=($alterdefs), PCRE version=(".PCRE_VERSION.")<hr /><br />";
		if($alterdefs != '')
		{
			$recreateQueries = array();
			$resultArr = $this->selectArray("SELECT sql,name,type FROM sqlite_master WHERE tbl_name = ".$this->quote($table));
			if(sizeof($resultArr)<1)
			{
				$this->alterError = $errormsg . sprintf($lang['tbl_inexistent'], htmlencode($table));
				if($debug) $this->debugOutput .= "ERROR: unknown table<hr /><br />";
				return false;
			}
			for($i=0; $i<sizeof($resultArr); $i++)
			{
				$row = $resultArr[$i];
				if($row['type'] != 'table' && $row['type'] != 'view')
				{
					if($row['sql']!='')
					{
						// store the CREATE statements of triggers and indexes to recreate them later
						$recreateQueries[] = $row;
						if($debug) $this->debugOutput .= "recreate=(".$row['sql'].";)<br />";
					}
				}
				elseif($row['type']=='view')  // workaround to rename views
				{
					$origsql = $row['sql'];
					$preg_remove_create_view = "/^\s*+CREATE\s++VIEW\s++".$this->sqlite_surroundings_preg($table)."\s*+(AS\s++SELECT\s++.*+)$/is";
					$origsql_no_create = preg_replace($preg_remove_create_view, '$1', $origsql, 1);
					if($debug) $this->debugOutput .= "origsql=($origsql)<br />preg_remove_create_table=($preg_remove_create_view)<br />";
					preg_match("/RENAME\s++TO\s++(?:\"((?:[^\"]|\"\")+)\"|'((?:[^']|'')+)')/is", $alterdefs, $matches);
					if(isset($matches[1]) && $matches[1]!='')
						$newname = $matches[1];
					elseif(isset($matches[2]) && $matches[2]!='')
						$newname = $matches[2];
					else
					{
						$this->alterError = $errormsg . ' could not detect new view name. It needs to be in single or double quotes.';
						if($debug) $this->debugOutput .= "ERROR: could not detect new view name<hr />";
						return false;	
					}
					$dropoldSQL = 'DROP VIEW '.$this->quote_id($table);
					$createnewSQL = 'CREATE VIEW '.$this->quote_id($newname).' '.$origsql_no_create;
					$alter_transaction = 'BEGIN; ' . $dropoldSQL .'; '. $createnewSQL . '; ' . 'COMMIT;';
					if($debug) $this->debugOutput .= $alter_transaction;
					return $this->multiQuery($alter_transaction); 
				}
				else
				{
					// ALTER the table
					$tmpname = 't'.time();
					$origsql = $row['sql'];
					$preg_remove_create_table = "/^\s*+CREATE\s++TABLE\s++".$this->sqlite_surroundings_preg($table)."\s*+(\(.*+)$/is";
					$origsql_no_create = preg_replace($preg_remove_create_table, '$1', $origsql, 1);
					if($debug) $this->debugOutput .= "origsql=($origsql)<br />preg_remove_create_table=($preg_remove_create_table)<br />";
					if($origsql_no_create == $origsql)
					{
						$this->alterError = $errormsg . $lang['alter_tbl_name_not_replacable'];
						if($debug) $this->debugOutput .= "ERROR: could not get rid of CREATE TABLE<hr />";
						return false;
					}
					$createtemptableSQL = "CREATE TABLE ".$this->quote($tmpname)." ".$origsql_no_create;
					if($debug) $this->debugOutput .= "createtemptableSQL=($createtemptableSQL)<br />";
					$createindexsql = array();
					$preg_alter_part = "/(?:DROP(?! PRIMARY KEY)|ADD(?! PRIMARY KEY)|CHANGE|RENAME TO|ADD PRIMARY KEY|DROP PRIMARY KEY)" // the ALTER command
						."(?:"
							."\s+\(".$this->sqlite_surroundings_preg("+",false,"\"'\[`)")."+\)"	// stuff in brackets (in case of ADD PRIMARY KEY)
						."|"																	// or
							."\s+".$this->sqlite_surroundings_preg("+",false,",'\"\[`")			// column names and stuff like this
						.")*/i";
					if($debug)
						$this->debugOutput .= "preg_alter_part=(".$preg_alter_part.")<br />";
					preg_match_all($preg_alter_part,$alterdefs,$matches);
					$defs = $matches[0];
					
					$result_oldcols = $this->getTableInfo($table);
					$newcols = array();
					$coltypes = array();
					$primarykey = array();
					foreach($result_oldcols as $column_info)
					{
						$newcols[$column_info['name']] = $column_info['name'];
						$coltypes[$column_info['name']] = $column_info['type'];
						if($column_info['pk'])
							$primarykey[] = $column_info['name'];
					}
					$newcolumns = '';
					$oldcolumns = '';
					reset($newcols);
					while(list($key, $val) = each($newcols))
					{
						$newcolumns .= ($newcolumns?', ':'').$this->quote_id($val);
						$oldcolumns .= ($oldcolumns?', ':'').$this->quote_id($key);
					}
					$copytotempsql = 'INSERT INTO '.$this->quote_id($tmpname).'('.$newcolumns.') SELECT '.$oldcolumns.' FROM '.$this->quote_id($table);
					$dropoldsql = 'DROP TABLE '.$this->quote_id($table);
					$createtesttableSQL = $createtemptableSQL;
					if(count($defs)<1)
					{
						$this->alterError = $errormsg . $lang['alter_no_def'];
						if($debug) $this->debugOutput .= "ERROR: defs&lt;1<hr /><br />";
						return false;
					}
					foreach($defs as $def)
					{
						if($debug) $this->debugOutput .= "<hr />def=$def<br />";
						$preg_parse_def =
							"/^(DROP(?! PRIMARY KEY)|ADD(?! PRIMARY KEY)|CHANGE|RENAME TO|ADD PRIMARY KEY|DROP PRIMARY KEY)" // $matches[1]: command
							."(?:"												// this is either
								."(?:\s+\((.+)\)\s*$)"							// anything in brackets (for ADD PRIMARY KEY)
																				// then $matches[2] is what there is in brackets
							."|"												// OR: 
								."(?:\s+\"((?:[^\"]|\"\")+)\"|\s+'((?:[^']|'')+)')"// (first) column name, either in single or double quotes
																				// in case of RENAME TO, it is the new a table name
																				// $matches[3] will be the column/table name without the quotes if double quoted
																				// $matches[4] will be the column/table name without the quotes if single quoted
								."("											// $matches[5]: anything after the column name
									."(?:\s+'((?:[^']|'')+)')?"					// $matches[6] (optional): a second column name surrounded with single quotes
																				//		(the match does not contain the quotes) 
									."\s*"
									."((?:[A-Z]+\s*)+(?:\(\s*[+-]?\s*[0-9]+(?:\s*,\s*[+-]?\s*[0-9]+)?\s*\))?)?\s*"	// $matches[7] (optional): a type name
									.".*".
								")"
								."?\s*$"
							.")?\s*$/i"; // in case of DROP PRIMARY KEY, there is nothing after the command
						if($debug) $this->debugOutput .= "preg_parse_def=$preg_parse_def<br />";
						$parse_def = preg_match($preg_parse_def,$def,$matches);
						if($parse_def===false)
						{
							$this->alterError = $errormsg . $lang['alter_parse_failed'];
							if($debug) $this->debugOutput .= "ERROR: !parse_def<hr /><br />";
							return false;
						}
						if(!isset($matches[1]))
						{
							$this->alterError = $errormsg . $lang['alter_action_not_recognized'];
							if($debug) $this->debugOutput .= "ERROR: !isset(matches[1])<hr /><br />";
							return false;
						}
						$action = strtolower($matches[1]);
						if(($action == 'add' || $action == 'rename to') && isset($matches[4]) && $matches[4]!='')	
							$column = str_replace("''","'",$matches[4]);		// enclosed in ''
						elseif($action == 'add primary key' && isset($matches[2]) && $matches[2]!='')
							$column = $matches[2];	
						elseif($action == 'drop primary key')
							$column = '';	// DROP PRIMARY KEY has no column definition
						elseif(isset($matches[3]) && $matches[3]!='')
							$column = str_replace('""','"',$matches[3]);		// enclosed in ""
						else
							$column = '';

						$column_escaped = str_replace("'","''",$column);

						if($debug) $this->debugOutput .= "action=($action), column=($column), column_escaped=($column_escaped)<br />";

						/* we build a regex that devides the CREATE TABLE statement parts:
						  Part example                            Group  Explanation
						  1. CREATE TABLE t... (                  $1
						  2. 'col1' ..., 'col2' ..., 'colN' ...,  $3     (with col1-colN being columns that are not changed and listed before the col to change)
						  3. 'colX' ...,                                 (with colX being the column to change/drop)
						  4. 'colX+1' ..., ..., 'colK')           $5     (with colX+1-colK being columns after the column to change/drop)
						*/
						$preg_create_table = "\s*+(CREATE\s++TABLE\s++".preg_quote($this->quote($tmpname),"/")."\s*+\()";   // This is group $1 (keep unchanged)
						$preg_column_definiton = "\s*+".$this->sqlite_surroundings_preg("+",true," '\"\[`,",$column)."(?:\s*+".$this->sqlite_surroundings_preg("*",false,"'\",`\[ ").")++";		// catches a complete column definition, even if it is
														// 'column' TEXT NOT NULL DEFAULT 'we have a comma, here and a double ''quote!'
														// this definition does NOT match columns with the column name $column
						if($debug) $this->debugOutput .= "preg_column_definition=(".$preg_column_definiton.")<br />";
						$preg_columns_before =  // columns before the one changed/dropped (keep)
							"(?:".
								"(".			// group $2. Keep this one unchanged!
									"(?:".
										"$preg_column_definiton,\s*+".		// column definition + comma
									")*".								// there might be any number of such columns here
									$preg_column_definiton.				// last column definition 
								")".			// end of group $2
								",\s*+"			// the last comma of the last column before the column to change. Do not keep it!
							.")?";    // there might be no columns before
						if($debug) $this->debugOutput .= "preg_columns_before=(".$preg_columns_before.")<br />";
						$preg_columns_after = "(,\s*(.+))?"; // the columns after the column to drop. This is group $3 (drop) or $4(change) (keep!)
												// we could remove the comma using $6 instead of $5, but then we might have no comma at all.
												// Keeping it leaves a problem if we drop the first column, so we fix that case in another regex.
						$table_new = $table;
	
						switch($action)
						{
							case 'add':
								if($column=='')
								{
									$this->alterError = $errormsg . ' (add) - '. $lang['alter_no_add_col'];
									return false;
								}
								$new_col_definition = "'$column_escaped' ".(isset($matches[5])?$matches[5]:'');
								$preg_pattern_add = "/^".$preg_create_table.   // the CREATE TABLE statement ($1)
									"((?:(?!,\s*(?:PRIMARY\s+KEY\s*\(|CONSTRAINT\s|UNIQUE\s*\(|CHECK\s*\(|FOREIGN\s+KEY\s*\()).)*)". // column definitions ($2)
									"(.*)\\)\s*$/si"; // table-constraints like PRIMARY KEY(a,b) ($3) and the closing bracket
								// append the column definiton in the CREATE TABLE statement
								$newSQL = preg_replace($preg_pattern_add, '$1$2, '.strtr($new_col_definition, array('\\' => '\\\\', '$' => '\$')).' $3', $createtesttableSQL).')';
								$preg_error = $this->getPregError();
								if($debug)
								{
									$this->debugOutput .= $createtesttableSQL."<hr /><br />";
									$this->debugOutput .= $newSQL."<hr /><br />";
									$this->debugOutput .= $preg_pattern_add."<hr /><br />";
								}
								if($newSQL==$createtesttableSQL) // pattern did not match, so column adding did not succed
									{
									$this->alterError = $errormsg . ' (add) - '.$lang['alter_pattern_mismatch'].'. PREG ERROR: '.$preg_error;
									return false;
									}
								$createtesttableSQL = $newSQL;
								break;
							case 'change':
								var_dump($matches);
								if(!isset($matches[6]))
								{
									$this->alterError = $errormsg . ' (change) - '.$lang['alter_col_not_recognized'];
									return false;
								}
								$new_col_name = $matches[6];
								if(!isset($matches[7]))
									$new_col_type = '';
								else
									$new_col_type = $matches[7];
								$new_col_definition = "'$new_col_name' $new_col_type";
								$preg_column_to_change = "\s*".$this->sqlite_surroundings_preg($column)."(?:\s+".preg_quote($coltypes[$column]).")?(\s+(?:".$this->sqlite_surroundings_preg("*",false,",'\"`\[").")+)?";
												// replace this part (we want to change this column)
												// group $3 contains the column constraints (keep!). the name & data type is replaced.
								$preg_pattern_change = "/^".$preg_create_table.$preg_columns_before.$preg_column_to_change.$preg_columns_after."\s*\\)\s*$/s";

								// replace the column definiton in the CREATE TABLE statement
								$newSQL = preg_replace($preg_pattern_change, '$1$2,'.strtr($new_col_definition, array('\\' => '\\\\', '$' => '\$')).'$3$4)', $createtesttableSQL);
								$preg_error = $this->getPregError();
								// remove comma at the beginning if the first column is changed
								// probably somebody is able to put this into the first regex (using lookahead probably).
								$newSQL = preg_replace("/^\s*(CREATE\s+TABLE\s+".preg_quote($this->quote($tmpname),"/")."\s+\(),\s*/",'$1',$newSQL);
								if($debug)
								{
									$this->debugOutput .= "preg_column_to_change=(".$preg_column_to_change.")<hr /><br />";
									$this->debugOutput .= $createtesttableSQL."<hr /><br />";
									$this->debugOutput .= $newSQL."<hr /><br />";

									$this->debugOutput .= $preg_pattern_change."<hr /><br />";
								}
								if($newSQL==$createtesttableSQL || $newSQL=="") // pattern did not match, so column removal did not succed
								{
									$this->alterError = $errormsg . ' (change) - '.$lang['alter_pattern_mismatch'].'. PREG ERROR: '.$preg_error;
									return false;
								}
								$createtesttableSQL = $newSQL;
								$newcols[$column] = str_replace("''","'",$new_col_name);
								break;
							case 'drop':
								$preg_column_to_drop = "\s*".$this->sqlite_surroundings_preg($column)."\s+(?:".$this->sqlite_surroundings_preg("*",false,",'\"\[`").")+";      // delete this part (we want to drop this column)
								$preg_pattern_drop = "/^".$preg_create_table.$preg_columns_before.$preg_column_to_drop.$preg_columns_after."\s*\\)\s*$/s";

								// remove the column out of the CREATE TABLE statement
								$newSQL = preg_replace($preg_pattern_drop, '$1$2$3)', $createtesttableSQL);
								$preg_error = $this->getPregError();
								// remove comma at the beginning if the first column is removed
								// probably somebody is able to put this into the first regex (using lookahead probably).
								$newSQL = preg_replace("/^\s*(CREATE\s+TABLE\s+".preg_quote($this->quote($tmpname),"/")."\s+\(),\s*/",'$1',$newSQL);
								if($debug)
								{
									$this->debugOutput .= $createtesttableSQL."<hr /><br />";
									$this->debugOutput .= $newSQL."<hr /><br />";
									$this->debugOutput .= $preg_pattern_drop."<hr /><br />";
								}
								if($newSQL==$createtesttableSQL || $newSQL=="") // pattern did not match, so column removal did not succed
								{
									$this->alterError = $errormsg . ' (drop) - '.$lang['alter_pattern_mismatch'].'. PREG ERROR: '.$preg_error;
									return false;
								}
								$createtesttableSQL = $newSQL;
								unset($newcols[$column]);
								break;
							case 'rename to':
								// don't change column definition at all
								$newSQL = $createtesttableSQL;
								// only change the name of the table
								$table_new = $column;
								break;
							case 'add primary key':
								// we want to add a primary key for the column(s) stored in $column
								$newSQL = preg_replace("/\)\s*$/", ", PRIMARY KEY (".$column.") )", $createtesttableSQL);
								$createtesttableSQL = $newSQL;
								break;
							case 'drop primary key':
								// we want to drop the primary key
								if($debug) $this->debugOutput .= "DROP";
								if(sizeof($primarykey)==1)
								{
									// if not compound primary key, might be a column constraint -> try removal
									$column = $primarykey[0];
									if($debug) $this->debugOutput .= "<br>Trying to drop column constraint for column $column <br>";
									/*
									TODO: This does not work yet:
									CREATE TABLE 't12' ('t1' INTEGER CONSTRAINT "bla" NOT NULL CONSTRAINT 'pk' PRIMARY KEY ); ALTER TABLE "t12" DROP PRIMARY KEY
									This does:                                  !   !
									CREATE TABLE 't12' ('t1' INTEGER CONSTRAINT  bla  NOT NULL CONSTRAINT 'pk' PRIMARY KEY ); ALTER TABLE "t12" DROP PRIMARY KEY
									*/
									$preg_column_to_change = "(\s*".$this->sqlite_surroundings_preg($column).")". // column ($3)
										"(?:".		// opt. type and column constraints
											"(\s+(?:".$this->sqlite_surroundings_preg("(?:[^PC,'\"`\[]|P(?!RIMARY\s+KEY)|".
											"C(?!ONSTRAINT\s+".$this->sqlite_surroundings_preg("+",false," ,'\"\[`")."\s+PRIMARY\s+KEY))",false,",'\"`\[").")*)". // column constraints before PRIMARY KEY ($3)
												// primary key constraint (remove this!):
												"(?:CONSTRAINT\s+".$this->sqlite_surroundings_preg("+",false," ,'\"\[`")."\s+)?".
												"PRIMARY\s+KEY".
												"(?:\s+(?:ASC|DESC))?".
												"(?:\s+ON\s+CONFLICT\s+(?:ROLLBACK|ABORT|FAIL|IGNORE|REPLACE))?".
												"(?:\s+AUTOINCREMENT)?".
											"((?:".$this->sqlite_surroundings_preg("*",false,",'\"`\[").")*)". // column constraints after PRIMARY KEY ($4)
										")";
													// replace this part (we want to change this column)
													// group $3 (column) $4  (constraints before) and $5 (constraints after) contain the part to keep
									$preg_pattern_change = "/^".$preg_create_table.$preg_columns_before.$preg_column_to_change.$preg_columns_after."\s*\\)\s*$/si";
		
									// replace the column definiton in the CREATE TABLE statement
									$newSQL = preg_replace($preg_pattern_change, '$1$2,$3$4$5$6)', $createtesttableSQL);
									// remove comma at the beginning if the first column is changed
									// probably somebody is able to put this into the first regex (using lookahead probably).
									$newSQL = preg_replace("/^\s*(CREATE\s+TABLE\s+".preg_quote($this->quote($tmpname),"/")."\s+\(),\s*/",'$1',$newSQL);
									if($debug)
									{
										$this->debugOutput .= "preg_column_to_change=(".$preg_column_to_change.")<hr /><br />";
										$this->debugOutput .= $createtesttableSQL."<hr /><br />";
										$this->debugOutput .= $newSQL."<hr /><br />";
	
										$this->debugOutput .= $preg_pattern_change."<hr /><br />";
									}
									if($newSQL!=$createtesttableSQL && $newSQL!="") // pattern did match, so PRIMARY KEY constraint removed :)
									{
										$createtesttableSQL = $newSQL;
										if($debug) $this->debugOutput .= "<br>SUCCEEDED<br>";
									}
									else
									{
										if($debug) $this->debugOutput .= "NO LUCK";
										// TODO: try removing table constraint
										return false;
									}
									$createtesttableSQL = $newSQL;
								} else
									// TODO: Try removing table constraint
									return false;
								
								break;
							default:
								if($debug) $this->debugOutput .= 'ERROR: unknown alter operation!<hr /><br />';
								$this->alterError = $errormsg . $lang['alter_unknown_operation'];
								return false;
						}
					}
					$droptempsql = 'DROP TABLE '.$this->quote_id($tmpname);

					$createnewtableSQL = "CREATE TABLE ".$this->quote($table_new)." ".preg_replace("/^\s*CREATE\s+TABLE\s+'?".str_replace("'","''",preg_quote($tmpname,"/"))."'?\s+(.*)$/is", '$1', $createtesttableSQL, 1);

					$newcolumns = '';
					$oldcolumns = '';
					reset($newcols);
					while(list($key,$val) = each($newcols))
					{
						$newcolumns .= ($newcolumns?', ':'').$this->quote_id($val);
						$oldcolumns .= ($oldcolumns?', ':'').$this->quote_id($key);
					}
					$copytonewsql = 'INSERT INTO '.$this->quote_id($table_new).'('.$newcolumns.') SELECT '.$oldcolumns.' FROM '.$this->quote_id($tmpname);
				}
			}
			$alter_transaction  = 'BEGIN; ';
			$alter_transaction .= $createtemptableSQL.'; ';  //create temp table
			$alter_transaction .= $copytotempsql.'; ';       //copy to table
			$alter_transaction .= $dropoldsql.'; ';          //drop old table
			$alter_transaction .= $createnewtableSQL.'; ';   //recreate original table
			$alter_transaction .= $copytonewsql.'; ';        //copy back to original table
			$alter_transaction .= $droptempsql.'; ';         //drop temp table

			$preg_index="/^\s*(CREATE\s+(?:UNIQUE\s+)?INDEX\s+(?:".$this->sqlite_surroundings_preg("+",false," '\"\[`")."\s*)*ON\s+)(".$this->sqlite_surroundings_preg($table).")(\s*\((?:".$this->sqlite_surroundings_preg("+",false," '\"\[`")."\s*)*\)\s*)\s*$/i";				
			foreach($recreateQueries as $recreate_query)
			{
				if($recreate_query['type']=='index')
				{
					// this is an index. We need to make sure the index is not on a column that we drop. If it is, we drop the index as well.
					$indexInfos = $this->selectArray('PRAGMA index_info('.$this->quote_id($recreate_query['name']).')');
					foreach($indexInfos as $indexInfo)
					{
						if(!isset($newcols[$indexInfo['name']]))
						{
							if($debug) $this->debugOutput .= 'Not recreating the following index: <hr /><br />'.htmlencode($recreate_query['sql']).'<hr /><br />'; 
							// Index on a column that was dropped. Skip recreation.
							continue 2;
						}
					}
				}
				// TODO: In case we renamed a column on which there is an index, we need to recreate the index with the column name adjusted.
				
				// recreate triggers / indexes
				if($table == $table_new)
				{
					// we had no RENAME TO, so we can recreate indexes/triggers just like the original ones
					$alter_transaction .= $recreate_query['sql'].';';
				} else
				{
					// we had a RENAME TO, so we need to exchange the table-name in the CREATE-SQL of triggers & indexes
					switch ($recreate_query['type'])
					{
						case 'index':
							$recreate_queryIndex = preg_replace($preg_index, '$1'.$this->quote_id(strtr($table_new, array('\\' => '\\\\', '$' => '\$'))).'$3 ', $recreate_query['sql']);
							if($recreate_queryIndex!=$recreate_query['sql'] && $recreate_queryIndex != NULL)
								$alter_transaction .= $recreate_queryIndex.';';
							else
							{
								// the CREATE INDEX regex did not match. this normally should not happen
								if($debug) $this->debugOutput .= 'ERROR: CREATE INDEX regex did not match!?<hr /><br />';
								// just try to recreate the index originally (will fail most likely)
								$alter_transaction .= $recreate_query['sql'].';';
							}
							break;
							
						case 'trigger':
							// TODO: IMPLEMENT
							$alter_transaction .= $recreate_query['sql'].';';
							break;
						default:
							if($debug) $this->debugOutput .= 'ERROR: Unknown type '.htmlencode($recreate_query['type']).'<hr /><br />';
							$alter_transaction .= $recreate_query['sql'].';';
					}
				}
			}
			$alter_transaction .= 'COMMIT;';
			if($debug) $this->debugOutput .= $alter_transaction;
			return $this->multiQuery($alter_transaction);
		}
	}

	//multiple query execution
	//returns true on success, false otherwise. Use getError() to fetch the error.
	public function multiQuery($query)
	{
		if($this->type=="PDO")
			$success = $this->db->exec($query);
		else if($this->type=="SQLite3")
			$success = $this->db->exec($query);
		else
			$success = $this->db->queryExec($query, $error);
		return $success;
	}

	
	// checks whether a table has a primary key
	public function hasPrimaryKey($table)
	{
		$table_info = $this->getTableInfo($table);
		foreach($table_info as $row_id => $row_data)
		{
			if($row_data['pk'])
			{
				return true;
			}
		 
		}
		return false;
	}
	
	// Returns an array of columns by which rows can be uniquely adressed.
	// For tables with a rowid column, this is always array('rowid')
	// for tables without rowid, this is an array of the primary key columns. 
	public function getPrimaryKey($table)
	{
		$primary_key = array();
		// check if this table has a rowid
		$getRowID = $this->select('SELECT ROWID FROM '.$this->quote_id($table).' LIMIT 0,1');
		if(isset($getRowID[0]))
			// it has, so we prefer addressing rows by rowid			
			return array('rowid');
		else
		{
			// the table is without rowid, so use the primary key
			$table_info = $this->getTableInfo($table);
			if(is_array($table_info))
			{
				foreach($table_info as $row_id => $row_data)
				{
					if($row_data['pk'])
						$primary_key[] = $row_data['name'];
				}
			}
		}
		return $primary_key;
	}
	
	// selects a row by a given key $pk, which is an array of values
	// for the columns by which a row can be adressed (rowid or primary key)
	public function wherePK($table, $pk)
	{
		$where = "";
		$primary_key = $this->getPrimaryKey($table);
		foreach($primary_key as $pk_index => $column)
		{
			if($where!="")
				$where .= " AND ";
			$where .= $this->quote_id($column) . ' = ';
			if(is_int($pk[$pk_index]) || is_float($pk[$pk_index]))
				$where .= $pk[$pk_index];
			else
				$where .= $this->quote($pk[$pk_index]);
		}
		return $where;
	}

	//get number of rows in table
	public function numRows($table, $dontTakeLong = false)
	{
		// as Count(*) can be slow on huge tables without PK,
		// if $dontTakeLong is set and the size is > 2MB only count() if there is a PK
		if(!$dontTakeLong || $this->getSize() <= 2000 || $this->hasPrimaryKey($table))
		{
			$result = $this->select("SELECT Count(*) FROM ".$this->quote_id($table));
			return $result[0];
		} else
		{
			return '?';
		}
	}

	//correctly escape a string to be injected into an SQL query
	public function quote($value)
	{
		if($this->type=="PDO")
		{
			// PDO quote() escapes and adds quotes
			return $this->db->quote($value);
		}
		else if($this->type=="SQLite3")
		{
			return "'".$this->db->escapeString($value)."'";
		}
		else
		{
			return "'".sqlite_escape_string($value)."'";
		}
	}

	//correctly escape an identifier (column / table / trigger / index name) to be injected into an SQL query
	public function quote_id($value)
	{
		// double-quotes need to be escaped by doubling them
		$value = str_replace('"','""',$value);
		return '"'.$value.'"';
	}


	//import sql
	//returns true on success, error message otherwise
	public function import_sql($query)
	{
		$import = $this->multiQuery($query);
		if(!$import)
			return $this->getError();
		else
			return true;
	}
	
	public function prepareQuery($query)
	{
		if($this->type=='PDO' || $this->type=='SQLite3')
			return $this->db->prepare($query);
		else
		{
			// here we are in trouble, SQLiteDatabase cannot prepare statements.
			// we need to emulate prepare as best as we can
			# todo: implement this
			return null;
		}
	}
	
	public function bindValue($handle, $parameter, $value, $type)
	{
		if($this->type=='SQLite3')
		{
			$types = array(
				'bool'=>SQLITE3_INTEGER,
				'int'=>SQLITE3_INTEGER,
				'float'=>SQLITE3_FLOAT,
				'text'=>SQLITE3_TEXT,
				'blob'=>SQLITE3_BLOB,
				'null'=>SQLITE3_NULL);
			if(!isset($types[$type]))
				$type = 'text';
			// there is no SQLITE_BOOL, so check value and make sure it is 0/1
			if($type=='bool')
			{
				if($value===1 || $value===true)
					$value=1;
				elseif($value===0 || $value===false)
					$value=0;
				else
					return false;
			}
			return $handle->bindValue($parameter, $value, $types[$type]);
		}
		if($this->type=='PDO')
		{
			$types = array(
				'bool'=>PDO::PARAM_BOOL,
				'int'=>PDO::PARAM_INT,
				'float'=>PDO::PARAM_STR,
				'text'=>PDO::PARAM_STR,
				'blob'=>PDO::PARAM_LOB,
				'null'=>PDO::PARAM_NULL);
			if(!isset($types[$type]))
				$type = 'text';
			// there is no PDO::PARAM_FLOAT, so we check it ourself
			if($type=='float')
			{
				if(is_numeric($value))
					$value = (float) $value;
				else
					return false;
			}
			return $handle->bindValue($parameter, $value, $types[$type]);
		}
		else
			# todo: workaround
			return false;

	}
	
	public function executePrepared($handle, $fetchResult=false)
	{
		if($this->type=='PDO')
		{
			$ok=$handle->execute();
			if($fetchResult && $ok)
			{
				$res = $handle->fetchAll();
				$handle->closeCursor();
				return $res;
			}
			else
			{
				if($ok)
					$handle->closeCursor();
				return $ok;
			}
		}
		elseif($this->type=='SQLite3')
		{
			$resultset=$handle->execute();
			if($fetchResult && $resultset!==false)
			{
				$res = $resultset->fetchArray();
				$resultset->finalize();
				return $res;
			}
			else
			{
				if($resultset!==false)
					$resultset->finalize();
				if($resultset===false)
					return false;
				else
					return true;
			}
		}
		else
		{
			#todo.
			return false;
		}
	}
	
	//import csv
	//returns true on success, error message otherwise
	public function import_csv($filename, $table, $field_terminate, $field_enclosed, $field_escaped, $null, $fields_in_first_row)
	{
		@set_time_limit(-1);
		$csv_handle = fopen($filename,'r');
		$csv_insert = "BEGIN;\n";
		$csv_number_of_rows = 0;
		// PHP requires enclosure defined, but has no problem if it was not used
		if($field_enclosed=="") $field_enclosed='"';
		// PHP requires escaper defined
		if($field_escaped=="") $field_escaped='\\';
		// support tab delimiters
		if($field_terminate=='\t') $field_terminate = "\t";
		while($csv_handle!==false && !feof($csv_handle))
		{
			$csv_data = fgetcsv($csv_handle, 0, $field_terminate, $field_enclosed, $field_escaped); 
			if(is_array($csv_data) && ($csv_data[0] != NULL || count($csv_data)>1))
			{
				$csv_number_of_rows++;
				if($csv_number_of_rows==1)
				{
					if($this->getTypeOfTable($table)!="table")
					{
						// First,Create a new table
						$csv_insert .="CREATE TABLE ".$this->quote($table)." (";
						$number_of_cols = count($csv_data);
						foreach($csv_data as $csv_col => $csv_cell)
						{
							if($fields_in_first_row)
								$csv_insert .= $this->quote($csv_cell);
							else
								$csv_insert.= $this->quote("col{$csv_col}");
							if($csv_col < $number_of_cols-1)
								$csv_insert .= ", ";
						}
						$csv_insert .=");";
						
					} else {
						$number_of_cols = count($this->getTableInfo($table));
					}
					if($fields_in_first_row)
						continue;
				} 
				$csv_insert .= "INSERT INTO ".$this->quote_id($table)." VALUES (";
				for($csv_col = 0; $csv_col < $number_of_cols; $csv_col++)
				{
					if(isset($csv_data[$csv_col]))
						$csv_cell = $csv_data[$csv_col];
					else
						$csv_cell = $null;
					if($csv_cell == $null)
						$csv_insert .= "NULL";
					else
						$csv_insert.= $this->quote($csv_cell);
					if($csv_col < $number_of_cols-1)
						$csv_insert .= ",";
				}
				$csv_insert .= ");\n";
				
				if($csv_number_of_rows % 5000 == 0)
				{
					$csv_insert .= "COMMIT;\nBEGIN;\n";
				}
			}
		}
		if($csv_handle === false)
			return "Error reading CSV file";
		else
		{
			$csv_insert .= "COMMIT;";
			fclose($csv_handle);
			$import = $this->multiQuery($csv_insert);
			if(!$import)
				return $this->getError();
			else
				return true;
		}
	}
	
	//export csv
	public function export_csv($tables, $field_terminate, $field_enclosed, $field_escaped, $null, $crlf, $fields_in_first_row)
	{
		@set_time_limit(-1);
		// we use \r\n if the _client_ OS is windows (as the exported file is downloaded to the client), \n otherwise
		$crlf = (isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'Win')!==false ? "\r\n" : "\n");

		$query = "SELECT * FROM sqlite_master WHERE type='table' or type='view' ORDER BY type DESC";
		$result = $this->selectArray($query);
		for($i=0; $i<sizeof($result); $i++)
		{
			$valid = false;
			for($j=0; $j<sizeof($tables); $j++)
			{
				if($result[$i]['tbl_name']==$tables[$j])
					$valid = true;
			}
			if($valid)
			{
				$temp = $this->getTableInfo($result[$i]['tbl_name']);
				$cols = array();
				for($z=0; $z<sizeof($temp); $z++)
					$cols[$z] = $temp[$z][1];
				if($fields_in_first_row)
				{
					for($z=0; $z<sizeof($cols); $z++)
					{
						echo $field_enclosed.$cols[$z].$field_enclosed;
						// do not terminate the last column!
						if($z < sizeof($cols)-1)
							echo $field_terminate;
					}
					echo $crlf;
				}
				$query = "SELECT * FROM ".$this->quote_id($result[$i]['tbl_name']);
				$table_result = $this->query($query);
				$firstRow=true;
				while($row = $this->fetch($table_result, "assoc"))
				{
					if(!$firstRow)
						echo $crlf;
					else
						$firstRow=false;

					for($y=0; $y<sizeof($cols); $y++)
					{
						$cell = $row[$cols[$y]];
						if($crlf)
						{
							$cell = str_replace("\n","", $cell);
							$cell = str_replace("\r","", $cell);
						}
						$cell = str_replace($field_terminate,$field_escaped.$field_terminate,$cell);
						$cell = str_replace($field_enclosed,$field_escaped.$field_enclosed,$cell);
						// do not enclose NULLs
						if($cell == NULL)
							echo $null;  
						else
							echo $field_enclosed.$cell.$field_enclosed;
						// do not terminate the last column!
						if($y < sizeof($cols)-1)
							echo $field_terminate;
					}
				}
				if($i<sizeof($result)-1)
					echo $crlf;
			}
		}
	}
	
	//export sql
	public function export_sql($tables, $drop, $structure, $data, $transaction, $comments)
	{
		global $lang;
		@set_time_limit(-1);
		// we use \r\n if the _client_ OS is windows (as the exported file is downloaded to the client), \n otherwise
		$crlf = (isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'Win')!==false ? "\r\n" : "\n");
		
		if($comments)
		{
			echo "----".$crlf;
			echo "-- ".PROJECT." ".$lang['db_dump']." (".PROJECT_URL.")".$crlf;
			echo "-- ".PROJECT." ".$lang['ver'].": ".VERSION.$crlf;
			echo "-- ".$lang['exported'].": ".date($lang['date_format']).$crlf;
			echo "-- ".$lang['db_f'].": ".$this->getPath().$crlf;
			echo "----".$crlf;
		}
		$query = "SELECT * FROM sqlite_master WHERE type='table' OR type='index' OR type='view' OR type='trigger' ORDER BY type='trigger', type='index', type='view', type='table'";
		$result = $this->selectArray($query);

		if($transaction)
			echo "BEGIN TRANSACTION;".$crlf;

		//iterate through each table
		for($i=0; $i<sizeof($result); $i++)
		{
			$valid = false;
			for($j=0; $j<sizeof($tables); $j++)
			{
				if($result[$i]['tbl_name']==$tables[$j])
					$valid = true;
			}
			if($valid)
			{
				if($drop)
				{
					if($comments)
					{
						echo "\r\n----".$crlf;
						echo "-- ".$lang['drop']." ".$result[$i]['type']." ".$lang['for']." ".$result[$i]['name'].$crlf;
						echo "----".$crlf;
					}
					echo "DROP ".strtoupper($result[$i]['type'])." IF EXISTS ".$this->quote_id($result[$i]['name']).";".$crlf;
				}
				if($structure)
				{
					if($comments)
					{
						echo "\r\n----".$crlf;
						if($result[$i]['type']=="table" || $result[$i]['type']=="view")
							echo "-- ".ucfirst($result[$i]['type'])." ".$lang['struct_for']." ".$result[$i]['tbl_name'].$crlf;
						else // index or trigger
							echo "-- ".$lang['struct_for']." ".$result[$i]['type']." ".$result[$i]['name']." ".$lang['on_tbl']." ".$result[$i]['tbl_name'].$crlf;
						echo "----".$crlf;
					}
					echo $result[$i]['sql'].";".$crlf;
				}
				if($data && $result[$i]['type']=="table")
				{
					$query = "SELECT * FROM ".$this->quote_id($result[$i]['tbl_name']);
					$table_result = $this->query($query, "assoc");

					if($comments)
					{
						$numRows = $this->numRows($result[$i]['tbl_name']);
						echo "\r\n----".$crlf;
						echo "-- ".$lang['data_dump']." ".$result[$i]['tbl_name'].", ".sprintf($lang['total_rows'], $numRows).$crlf;
						echo "----".$crlf;
					}
					$temp = $this->getTableInfo($result[$i]['tbl_name']);
					$cols = array();
					$cols_quoted = array();
					for($z=0; $z<sizeof($temp); $z++)
					{
						$cols[$z] = $temp[$z][1];
						$cols_quoted[$z] = $this->quote_id($temp[$z][1]);
					}
					while($row = $this->fetch($table_result))
					{
						$vals = array();
						for($y=0; $y<sizeof($cols); $y++)
						{
							if($row[$cols[$y]] === NULL)
								$vals[$cols[$y]] = 'NULL';
							else
								$vals[$cols[$y]] = $this->quote($row[$cols[$y]]);
						}
						echo "INSERT INTO ".$this->quote_id($result[$i]['tbl_name'])." (".implode(",", $cols_quoted).") VALUES (".implode(",", $vals).");".$crlf;
					}
				}
			}
		}
		if($transaction)
			echo "COMMIT;".$crlf;
	}
}
class GetParameters
{
	private $_fields;

	public function __construct(array $defaults = array())
	{
		$this->_fields = $defaults;
	}

	public function __set($key, $value)
	{
		$this->_fields[$key] = $value;
	}

	public function __isset($key)
	{
		return isset($this->_fields[$key]);
	}

	public function __unset($key)
	{
		unset($this->_fields[$key]);
	}

	public function __get($key)
	{
		return $this->_fields[$key];
	}

	public function getURL(array $assoc = array(), $html = true, $prefix='?')
	{
		$arg_sep = ($html?'&amp;':'&');
		return $prefix . http_build_query(array_merge($this->_fields, $assoc), '', $arg_sep);
	}

	public function getLink(array $assoc = array(), $content = '[ link ]', $class = '', $title = '', $target='')
	{
		return '<a href="' . $this->getURL($assoc) . '"'
			. ($class  != '' ? ' class="'  . $class .  '"' : '')
			. ($title  != '' ? ' title="'  . $title .  '"' : '')
			. ($target != '' ? ' target="' . $target . '"' : '')
			. '>' . $content . '</a>';
	}

	public function getForm(array $assoc = array(), $method = 'post', $upload = false, $name = '', $csrf = true)
	{
		$hidden = '';
		if($method == 'get')
		{
			$url = '';
			foreach(array_merge($this->_fields, $assoc) as $key => $value)
			{
				if(!is_null($value))
					$hidden .= '<input type="hidden" name="'.htmlencode($key).'" value="'.htmlencode($value).'" /> ';
			}
		}
		else 
			$url = $this->getURL($assoc);
		
		if($csrf && $method == 'post')
			$hidden .= '<input type="hidden" name="token" value="'.$_SESSION[COOKIENAME.'token'].'" />';
		
		return "<form action='". $url ."' method='" . $method . "'" .
			($name!=''? " name='". $name ."'" : '') .
			($upload? " enctype='multipart/form-data'" : '') . ">" .
			$hidden;
	}
	
	public function redirect(array $assoc = array(), $message="")
	{
		if($message!="")
		{
			$_SESSION[COOKIENAME.'messages'][md5($message)] = $message;
			$url = $this->getURL(array_merge($assoc, array('message'=>md5($message))), false);
		}
		else
			$url = $this->getURL($assoc, false);
		
		$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http'); 
		
		header("Location: ".$protocol."://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].$url, true, 302);
		exit;
	}
}//	class MicroTimer (issue #146)
//	wraps calls to microtime(), calculating the elapsed time and rounding output
//
class MicroTimer {

	private $startTime, $stopTime;

	// creates and starts a timer
	function __construct()
	{
		$this->startTime = microtime(true);
	}

	// stops a timer
	public function stop()
	{
		$this->stopTime = microtime(true);
	}

	// returns the number of seconds from the timer's creation, or elapsed
	// between creation and call to ->stop()
	public function elapsed()
	{
		if ($this->stopTime)
			return round($this->stopTime - $this->startTime, 4);

		return round(microtime(true) - $this->startTime, 4);
	}

	// called when using a MicroTimer object as a string
	public function __toString()
	{
		return (string) $this->elapsed();
	}

}
//	class Resources (issue #157)
//	outputs secondary files, such as css and javascript
//	data is stored gzipped (gzencode) and encoded (base64_encode)
//
class Resources {

	// set this to the file containing getInternalResource;
	// currently unused in split mode; set to __FILE__ for built PLA.
	public static $embedding_file = __FILE__;

	private static $_resources = array(
		'css' => array(
			'mime' => 'text/css',
			'data' => 'resources/phpliteadmin.css',
		),
		'javascript' => array(
			'mime' => 'text/javascript',
			'data' => 'resources/phpliteadmin.js',
		),
		'favicon' => array(
			'mime' => 'image/x-icon',
			'data' => 'resources/favicon.ico',
			'base64' => 'true',
		),
	);

	// outputs the specified resource, if defined in this class.
	// the main script should do no further output after calling this function.
	public static function output($resource)
	{
		if (isset(self::$_resources[$resource])) {
			$res =& self::$_resources[$resource];

			if (function_exists('getInternalResource') && $data = getInternalResource($res['data'])) {
				$filename = self::$embedding_file;
			} else {
				$filename = $res['data'];
			}

			//echo $filename; exit;

			// use last-modified time as etag; etag must be quoted
			$etag = '"' . filemtime($filename) . '"';

			// check headers for matching etag; if etag hasn't changed, use the cached version
			if (isset($_SERVER['HTTP_IF_NONE_MATCH']) && $_SERVER['HTTP_IF_NONE_MATCH'] == $etag) {
				header('HTTP/1.0 304 Not Modified');
				return;
			}

			header('Etag: ' . $etag);

			// cache file for at most 30 days
			header('Cache-control: max-age=2592000');

			// output resource
			header('Content-type: ' . $res['mime']);

			if (isset($data)) {
				if (isset($res['base64'])) {
					echo base64_decode($data);
				} else {
					echo $data;
				}
			} else {
				readfile($filename);
			}
		}
	}

}


// returns data from internal resources, available in single-file mode
function getInternalResource($res) {
	$resources = array(
		'resources/phpliteadmin.css'=>' ',
		'resources/phpliteadmin.js'=>' ', 
		'resources/favicon.ico'=>'AAABAAEAEBAAAAEAIAAoBAAAFgAAACgAAAAQAAAAIAAAAAEAIAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAADwoKZQAAABMAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABEMDJMAAAAdAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAASDg7BAAAASAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAETDw9CCQkJ1QUFBb4AAABjAAAAAQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAADgsLVgAAAO8YExP/AAAA7QAAALEAAAARAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABQNDUsAAADJGBMT/xgTE/8AAAD/AAAAuAAAAA8AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAZERE8DQoKwhgTE/8QDQ2sGBMT/xgTE/8AAAA/AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAwICHkAAAD/EQwMzQAAAMIAAAD/AAAA7gAAAGEAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAOCwtWAAAA8RgTE/8IBQW1AAAA/wAAAP8AAADlAAAAHQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA0KCsEAAAD/EQ8PzAAAAMkAAAD/AAAA/wAAAHoAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABDAAAA/xgTE/8TDw/FAAAA8gAAAP8AAADqAAAAJgAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAgAAALUAAAD/GBMT/wAAALkAAAD/AAAA/wAAAIkAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAfAAAA4wAAAP8GBgbFAAAA2QAAAP8AAADGAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAF4AAAD5AAAA/RgTE/8AAAD/AAAA0AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAQQAAAOIAAAD/AAAA/wAAAHYAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAjAAAArgAAAG4AAAAAAAAAAAAAAAAAAAAA',
	);

 
	return $resources[$res];  
}

// resources embedded below, do not edit!
 ?>

<style type="text/css">
body{margin:0px;padding:0px;font-family:Arial,Helvetica,sans-serif;font-size:14px;color:#000;background-color:#e0ebf6;overflow:auto}.body_tbl td{padding:9px 2px 9px 9px}.left_td{width:100px}a{color:#03F;text-decoration:none;cursor:pointer}a:hover{color:#06F}hr{height:1px;border:0;color:#bbb;background-color:#bbb;width:100%}h1{margin:0px;padding:5px;font-size:24px;background-color:#f3cece;text-align:center;color:#000;}#headerlinks{text-align:center;margin-bottom:10px;padding:5px 15px;border-color:#03F;border-width:1px;border-style:solid;border-left-style:none;border-right-style:none;font-size:12px;background-color:#e0ebf6;font-weight:bold}h1 #version{color:#000;font-size:16px}h1 #logo{color:#000}h2{margin:0px;padding:0px;font-size:14px;margin-bottom:20px}input,select,textarea,.CodeMirror{font-family:Arial,Helvetica,sans-serif;background-color:#eaeaea;color:#03F;border-color:#03F;border-style:solid;border-width:1px;margin:5px; padding:3px}input.btn{cursor:pointer}input.btn:hover{background-color:#ccc}fieldset label{min-width:200px;display:block;float:left}fieldset{padding:15px;border-color:#03F;border-width:1px;border-style:solid;background-color:#f9f9f9}#container{padding:10px}#leftNav{min-width:250px;padding:0px;border-color:#03F;border-width:1px;border-style:solid;background-color:#FFF;padding-bottom:15px;}.databaseList select{max-width:200px}.viewTable tr td{padding:1px}#loginBox{width:500px;margin-left:auto;margin-right:auto;margin-top:50px;border-color:#03F;border-width:1px;border-style:solid;background-color:#FFF;}#main{border-color:#03F;border-width:1px;border-style:solid;padding:15px;background-color:#FFF;}.td1{background-color:#f9e3e3;text-align:center;font-size:12px;padding-left:10px;padding-right:10px;min-width:36px;}
.td2{background-color:#f3cece;text-align:center;font-size:12px;padding-left:10px;padding-right:10px;min-width:36px;}.tdheader{border-color:#03F;border-width:1px;border-style:solid;font-weight:bold;font-size:12px;padding-left:10px;padding-right:10px;background-color:#e0ebf6;}.confirm{border-color:#03F;border-width:1px;border-style:dashed;padding:15px;background-color:#e0ebf6}.tab{display:block;height:18px;padding:5px;padding-right:8px;padding-left:8px;border-color:#03F;border-width:1px;border-style:solid;margin-right:5px;float:left;border-bottom-style:none;position:relative;top:1px;padding-bottom:4px;background-color:#eaeaea;}.tab_pressed{display:block;height:18px;padding:5px;padding-right:8px;padding-left:8px;border-color:#03F;border-width:1px;border-style:solid;margin-right:5px;float:left;border-bottom-style:none;position:relative;top:1px;background-color:#FFF;cursor:default;}.helpq{font-size:11px;font-weight:normal}#help_container{padding:0px;font-size:12px;margin-left:auto;margin-right:auto;background-color:#fff}.help_outer{background-color:#FFF;padding:0px;height:300px;position:relative}.help_list{padding:10px;height:auto}.headd{font-size:14px;font-weight:bold;display:block;padding:10px;background-color:#e0ebf6;border-color:#03F;border-width:1px;border-style:solid;border-left-style:none;border-right-style:none}.help_inner{padding:10px}.help_top{display:block;position:absolute;right:10px;bottom:10px}.warning,.delete,.empty,.drop,.delete_db{color:red}.sidebar_table{font-size:11px}.active_table,.active_db{text-decoration:underline;font-weight: bold;}.null{color:#888}.found{background:#FF0;text-decoration:none}

</style>
