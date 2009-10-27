<?php
error_reporting(E_ALL);
set_error_handler('userError', E_ERROR | E_WARNING | E_USER_ERROR | E_USER_NOTICE | E_USER_WARNING | E_COMPILE_ERROR);
function userError($errno, $errstr, $errfile, $errline) {
    print '<div style="padding:5px; margin:5px; border:1px solid black; background:white;">';
    print '<b>ERROR</b> '.$errstr.'<br />';
    print $errfile.':'.$errline.'</div>';
}

require_once('_pm/_kernel.config.php');
mysql_connect($CFG["mysql.host"],$CFG["mysql.username"],$CFG["mysql.password"]);
mysql_select_db($CFG["mysql.dbname"]);
mysql_query("SET NAMES 'cp1251'");
mysql_query("SET CHARACTER SET cp1251");

require_once('_pm/class.authenticationmgr.php');
$autMgr = new AuthenticationManager();

$userID = $autMgr->getUserID();
$userGroup = $autMgr->getUserGroup();

if ( ($userID == 1) OR ($userGroup != 5)) {
    $autMgr->endSession();
    header('location: /login');
    exit();
}

$sql = "SELECT propID, propValue FROM pm_as_parts_properties";
$result = mysql_query($sql);
while ($arr = mysql_fetch_array($result)){
	$tr = ruslat($arr[1]);
	$sql = "UPDATE pm_as_parts_properties SET propValueTranslit = '$tr' WHERE propID = '{$arr[0]}'";
	mysql_query($sql);
}

function ruslat($text)
{
	$subs = array ("�","zh","�","yo","�","j","�","yu","�","ch","�","sch","�","tc","�","u","�","k","�","e","�","n","�","g","�","sh","�","z","�","h","�","f","�","y","�","v","�","a","�","p","�","r","�","o","�","l","�","d","�","e","�","ja","�","s","�","m","�","i","�","t","�","b","�","","�","�","�","Yo","�","J","�","Yu","�","Cc","�","Sch","�","Tc","�","U","�","K","�","E","�","N","�","G","�","Sh","�","Z","�","H","�","F","�","Y","�","V","�","A","�","P","�","R","�","O","�","L","�","D","�","Zh","�","E","�","Ja","�","S","�","M","�","I","�","T","�","B","�","","�","");
	$len = count ($subs);
	$len = ($len % 2 == 0) ? $len : $len - 1;
	for ($i = 0 ; $i < $len; $i+=2){
		$text = str_replace($subs[$i], $subs[$i+1], $text);
	}
	$text = preg_replace("/[^a-zA-Z0-9]+/","-", $text);
	$text = trim(trim($text),"-");
	return $text;
}
?>