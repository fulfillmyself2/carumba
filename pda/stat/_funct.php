<?
$LANG=Array();
$CONFIG["debug"]=1;

$CNSTATS_DR=dirname(dirname(__FILE__));
if($CNSTATS_DR[strlen($CNSTATS_DR)-1]!="/") $CNSTATS_DR.="/";
if(!isset($STATS_CONF["dbname"]))include$CNSTATS_DR."stat/config.php";

if ($COUNTER["resolution"]=="1024") {
        $IMGW=465+230;
        $IMGH=270;
        $TW=695;
        $LW=605;
        $TDW=601;$TDS="style='overflow:hidden;word-wrap:yesbreak-word;word-break: break-all;width: ".$TDW."px;'";
        $TABLE="<table width='".$TW."' cellspacing='1' border='0' cellpadding='3' bgcolor='#D4F3D7' style='table-layout:fixed;' ".$TA.">\n";
        $BBG="bbg1024.gif";
        }
else {
        $IMGW=465;
        $IMGH=270;
        $TW=465;
        $LW=375;
        $TDW=371;$TDS="style='overflow:hidden;word-wrap:yesbreak-word;word-break: break-all;width: ".$TDW."px;'";
        $TABLE="<table width='".$TW."' cellspacing='1' border='0' cellpadding='3' bgcolor='#D4F3D7' style='table-layout:fixed;' ".$TA.">\n";
        $BBG="bbg800.gif";
        }

function cnstats_sql_query($query) {
        GLOBAL $LANG,$STATS_CONF,$COUNTER,$_SERVER;

        if ($STATS_CONF["sqlserver"]="MySql") {
                $r=@mysql_query($query);
                if (mysql_errno()!=0) {
                        if ($COUNTER["senderrorsbymail"]=="yes" && !empty($STATS_CONF["cnsoftwarelogin"])) {
                                mail($STATS_CONF["cnsoftwarelogin"],"CNStats MySql Error",">A fatal MySQL error occured\n\n".mysql_error()."\nQuery:\n------------\n".$query."\n-----------\nURL: http://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]."\nDate: ".date($LANG["datetime_format"]));
                                }
                        die("<font color=red><B>A fatal MySQL error occured:</B></font><br><br>\n\n".mysql_error()."<br><br>\n\n $query");
                        }
                }
        return($r);
        }

function ShowTable($num,$needtd=1) {
        GLOBAL $TABLED,$TABLEC,$TABLEU,$TDW,$TDS,$TW,$LW,$LANG,$TABLE,$CONFIG;

        $CONFIG["showlines"]=1;
        if ($CONFIG["showlines"]==1) {
                $max=$sum=0;
                for ($i=0;$i<count($TABLEC);$i++) {
                        if ($TABLED[$i]!=$LANG["noreferer"] &&
                $TABLED[$i]!=$LANG["direct jump"] &&
                $TABLED[$i]!=$LANG["other queries"] &&
                $TABLED[$i]!=$LANG["other countries"]
               )
                                if ($max<$TABLEC[$i]) $max=$TABLEC[$i];
                        $sum+=$TABLEC[$i];
                        }
                if ($max<5) $CONFIG["showlines"]=0;
                }

        print $TABLE;
        for ($i=0;$i<count($TABLEC);$i++) {
                if ($class!="tbl1") $class="tbl1"; else $class="tbl2";
                print "<tr class=".$class."><td valign='middle' width='25'><font style='font-size:9px' color='#333333'><b>".($num+$i+1)."</b></font></td>";
                if ($needtd==1) print "<td width='".$TDW."' ".$TDS.">";

                if (empty($TABLEU[$i])) print $TABLED[$i];
                else print "<a target=_blank href='".$TABLEU[$i]."'>".$TABLED[$i]."</a>";

                if ($CONFIG["gauge"]==1) {
                        if ($TABLED[$i]!=$LANG["noreferer"] &&
                $TABLED[$i]!=$LANG["direct jump"] &&
                $TABLED[$i]!=$LANG["other queries"] &&
                $TABLED[$i]!=$LANG["other countries"]
               )
                                if ($CONFIG["showlines"]==1 && $needtd==1) print "<br><img src='img/gauge.gif' alt='".$TABLEC[$i]."' width=".intval($TABLEC[$i]*$LW/$max)." height=7>";
                        }

                if ($needtd==1) print "</td>";
                print "<td align=right width=45>&nbsp;".$TABLEC[$i]."&nbsp;";

                /* ���������� �������� */
                if ($CONFIG["percents"]==1) {
						if($sum==0) $sum=1;
                        print "<br><font style='font-size:9px' color=#A0A0A0>&nbsp;".sprintf("%2.2f",$TABLEC[$i]*100/$sum)."%&nbsp;</font>";
                        }

                print "</td></tr>\n";
                }
        print "</table>\n";
        }

function LeftRight($start,$inpage,$num,$count,$marginu=5,$margind=5,$addurl="") {
        GLOBAL $LANG,$DATELINK,$stm,$ftm,$st,$_GET;

        if ($count==0) return;

        $prev=$start-$inpage;
        print "<center><img src='img/none.gif' alt='' width=1 height=".$marginu."><br>";
        if ($prev>=0)
                print "<a href='index.php?st=".$st."&amp;start=".$prev."&amp;stm=".$stm."&amp;ftm=".$ftm.$addurl.$DATELINK."'>&lt;&lt; ".$LANG["prev"]."</a> ";
        print "[ ".$LANG["page"]." ".(1+my_round(0.9999+$start/$inpage))."/".my_round(0.9999+$count/$inpage)." ] ";
        $next=$start+$inpage;
        if ($next<$count)
                print " <a href='index.php?st=".$st."&amp;start=".$next."&amp;stm=".$stm."&amp;ftm=".$ftm.$addurl.$DATELINK."'>".$LANG["next"]." &gt;&gt;</a>";
        print "</center><img src='img/none.gif' alt='' width=1 height=".$margind."><br>";

        }

function strrtolower($str) {
    $trans=array(
     "�" => "�", "�" => "�", "�" => "�", "�" => "�", "�" => "�", "�" => "�",
     "�" => "�", "�" => "�", "�" => "�", "�" => "�", "�" => "�", "�" => "�",
     "�" => "�", "�" => "�", "�" => "�", "�" => "�", "�" => "�", "�" => "�",
     "�" => "�", "�" => "�", "�" => "�", "�" => "�", "�" => "�", "�" => "�",
     "�" => "�", "�" => "�", "�" => "�", "�" => "�", "�" => "�", "�" => "�",
     "�" => "�", "�" => "�", "�" => "�",
    );
    $str=strtr($str, $trans);
    return($str);
    }

function DecodePhrase($data,$url) {
        $data=urldecode($data);

        $utf8=0;
        if (strstr($url,"ie=utf-8")) $utf8=1;
        if ($utf8==0) if (strstr($url,"oe=utf-8")) $utf8=1;
        if ($utf8==0) if (strstr($url,"oe=UTF-8")) $utf8=1;
        if ($utf8==0) if (strstr($url,"ie=UTF-8")) $utf8=1;

        if ($utf8==1) $data=cnstats_unUTF($data);

        $p=strpos(" ".$data,"text=");
        if ($p) {
                $data=substr($data,$p+4);
                $data=urldecode($data);
                $data=convert_cyr_string($data,"k","w");
                }
        else $data=urldecode($data);


        $data=str_replace("+"," ",$data);
        if (strpos($data,"&")) $data=substr($data,0,strpos($data,"&"));

        if ($utf8==1) cnstats_unUTF($data);

        if ($class!="tbl1") $class="tbl1"; else $class="tbl2";
        return($data);
        }

function GetRegexpPhrase($regexp,$url,$rule) {
        GLOBAL $PRE,$PRU,$PPP,$CONFIG;

        if (@preg_match($regexp,$url,$rx)) {
                $data=urldecode($rx[1]);
                if (strlen($PRU[$rule])>1) {
                        $data=GetRegexpPhrase($PRE[$PRU[$rule]],"&".$data,"");
                        }
                $d=explode(",",$PPP[$rule]);
                for ($i=0;$i<count($d);$i++) {
                        $d[$i]=trim($d[$i]);
                        switch ($d[$i]) {
                                case "koi2win": $data=convert_cyr_string($data,"k","w"); break;
                                case "utf-8": $data=cnstats_unUTF($data);break;
                                case "urldecode": $data=urldecode($data);break;
                                case "gutf-8":
                                        if (!strstr($url,"ie=windows-1251")) $data=cnstats_unUTF($data);
                                        break;
                                }
                        }
                }
        else if ($CONFIG["debug"]==1) $data="<font color=red>".$url."</font><br><font color=gray>".$regexp."</font>";
                else $data="";

        return($data);
        }

function mysqlconnect() {
        GLOBAL $STATS_CONF;
        @mysql_connect($STATS_CONF["sqlhost"],$STATS_CONF["sqluser"],$STATS_CONF["sqlpassword"]) or die("Error connectiong to database.\n<hr size=1><b>Host:</b> ".$STATS_CONF["sqlhost"]."\n<br><b>Login:</b> ".$STATS_CONF["sqluser"]."\n<br><b>Using password</b>: ".(empty($STATS_CONF["sqlpassword"])?"no":"yes"));
        @mysql_select_db($STATS_CONF["dbname"]) or die("Connecting to MySql...Ok<hr size=1>\nError selecting database<br>\n<B>Database name:</B> ".$STATS_CONF["dbname"]);
        }

function getmicrotime(){
    list($usec, $sec) = explode(" ",microtime());
    return ((float)$usec + (float)$sec);
    }

function title($title,$md=false,$u=0) {
	if($md){print"<table cellspacing=0 cellpadding=3 border=0 class='ttl'><tr><td>";
		if($_COOKIE["cnstats_mn".$u]=="hidden")$ks="expand";else $ks="colapse";
			print"<a onclick=\"ptable_ex('mn".$u."')\" href=\"javascript:;\"><img src='img/".$ks.".gif' width=17 height=17 id='imn".$u."' border=0></a>";
			print"</td><td width=100%><B>".$title."</B></td></tr></table>\n";
		}else print"<table cellspacing=0 cellpadding=5 border=0 class='ttl'><tr><td><B>".$title."</B></td></tr></table>\n";
	}

function my_round($num) {
        if (strpos($num,".")!=0) return(substr($num,0,strpos($num,".")));
        else return($num);
        }

function cnstats_error($err) {
        print "<table bgcolor=red cellspacing=1 cellpadding=5 border=0><tr><td bgcolor=#FFF0F0>";
        print $err;
        print "</td></tr></table>";
        die();
        }

function cnstats_mhtml($s) {
        $s=StripSlashes($s);
        $s=str_replace("'","&#39;",$s);
        $s=str_replace("\"","&quot;",$s);
        return($s);
        }

function cnstats_themesidebox($title,$content) {
?>
<table width='100%' cellspacing=1 cellpadding=6 bgcolor='#E0E0E0'><tr><td bgcolor='#F0F0F0' class='title'>
<?=$title;?>
</td></tr><tr><td bgcolor='#F8F8F8'>
<?=$content;?>
</td></tr></table>
<?
        }

function cnstats_unUnicode($str) {
$unicode = array(
        "%u0410"=>"�","%u0411"=>"�","%u0412"=>"�","%u0413"=>"�","%u0414"=>"�","%u0415"=>"�",
        "%u0401"=>"�","%u0416"=>"�","%u0417"=>"�","%u0418"=>"�","%u0419"=>"�","%u041A"=>"�",
        "%u041B"=>"�","%u041C"=>"�","%u041D"=>"�","%u041E"=>"�","%u041F"=>"�","%u0420"=>"�",
        "%u0421"=>"�","%u0422"=>"�","%u0423"=>"�","%u0424"=>"�","%u0425"=>"�","%u0426"=>"�",
        "%u0427"=>"�","%u0428"=>"�","%u0429"=>"�","%u042A"=>"�","%u042B"=>"�","%u042C"=>"�",
        "%u042D"=>"�","%u042E"=>"�","%u042F"=>"�","%u0430"=>"�","%u0431"=>"�","%u0432"=>"�",
        "%u0433"=>"�","%u0434"=>"�","%u0435"=>"�","%u0451"=>"�","%u0436"=>"�","%u0437"=>"�",
        "%u0438"=>"�","%u0439"=>"�","%u043A"=>"�","%u043B"=>"�","%u043C"=>"�","%u043D"=>"�",
        "%u043E"=>"�","%u043F"=>"�","%u0440"=>"�","%u0441"=>"�","%u0442"=>"�","%u0443"=>"�",
        "%u0444"=>"�","%u0445"=>"�","%u0446"=>"�","%u0447"=>"�","%u0448"=>"�","%u0449"=>"�",
        "%u044A"=>"�","%u044B"=>"�","%u044C"=>"�","%u044D"=>"�","%u044E"=>"�","%u044F"=>"�");

        return(strtr($str, $unicode));
        }

function cnstats_unUnicodeLc($str) {
$unicode = array(
        "%u0410"=>"�","%u0411"=>"�","%u0412"=>"�","%u0413"=>"�","%u0414"=>"�","%u0415"=>"�",
        "%u0401"=>"�","%u0416"=>"�","%u0417"=>"�","%u0418"=>"�","%u0419"=>"�","%u041a"=>"�",
        "%u041b"=>"�","%u041c"=>"�","%u041d"=>"�","%u041e"=>"�","%u041f"=>"�","%u0420"=>"�",
        "%u0421"=>"�","%u0422"=>"�","%u0423"=>"�","%u0424"=>"�","%u0425"=>"�","%u0426"=>"�",
        "%u0427"=>"�","%u0428"=>"�","%u0429"=>"�","%u042a"=>"�","%u042b"=>"�","%u042C"=>"�",
        "%u042d"=>"�","%u042e"=>"�","%u042f"=>"�","%u0430"=>"�","%u0431"=>"�","%u0432"=>"�",
        "%u0433"=>"�","%u0434"=>"�","%u0435"=>"�","%u0451"=>"�","%u0436"=>"�","%u0437"=>"�",
        "%u0438"=>"�","%u0439"=>"�","%u043a"=>"�","%u043b"=>"�","%u043c"=>"�","%u043d"=>"�",
        "%u043e"=>"�","%u043f"=>"�","%u0440"=>"�","%u0441"=>"�","%u0442"=>"�","%u0443"=>"�",
        "%u0444"=>"�","%u0445"=>"�","%u0446"=>"�","%u0447"=>"�","%u0448"=>"�","%u0449"=>"�",
        "%u044a"=>"�","%u044b"=>"�","%u044c"=>"�","%u044d"=>"�","%u044e"=>"�","%u044f"=>"�");

        return(strtr($str, $unicode));
        }

function cnstats_unUTF($str) {
        $newstr="";
        $l=strlen($str);
        $i=0;
        while ($i<$l) {
                $code=ord($str[$i]);
                if ($code<0x80) $newstr.=$str[$i];
                else {
                        $i++;
                        $w=$code*256+ord($str[$i]);

                        if ($w>=0xd090) $b=192+$w-0xd090; else $b=95;
                        if ($w>=0xd180 && $w<=0xd18f) $b=240+$w-0xd180;
                        $newstr.=chr($b);
                        }
                $i++;
                }
        return($newstr);
        }

function phrase_uncode($str) {
		$str=cnstats_unUnicode($str);
		$str=cnstats_unUnicodeLc($str);
        return($str);
        }

function cNumber($num) {
        $num=strval(intval($num));
        $res="";
        $l=strlen($num);
        $c=-1;
        for ($i=$l;$i>=0;$i--) {
                $c++;
                $res=$num[$i].$res;
                if ($c>2) {$res=" ".$res;$c=0;}
                }
        return($res);
        }

function gdVersion() {
        if (!function_exists("imagepng")) return(0);

        ob_start();
        phpinfo(8);
        $phpinfo=ob_get_contents();
        ob_end_clean();
        $phpinfo=stristr($phpinfo,"gd version");
        $phpinfo=stristr($phpinfo,"version");

        $end=strpos($phpinfo,"</tr>");
        if ($end) $phpinfo=substr($phpinfo,0,$end);
        $phpinfo=strip_tags($phpinfo);

        if (ereg(".*([0-9]+)\.([0-9]+)\.([0-9]+).*", $phpinfo, $r)) {
                $phpinfo=$r[1].".".$r[2].".".$r[3];
                }

        if (version_compare("2.0", $phpinfo)>=1) return(1);
        else return(2);
        }

function GetLanguage($code) {
        $lr=mysql_query("SELECT eng FROM cns_languages WHERE code='".mysql_escape_string($code)."';");
        if (mysql_num_rows($lr)==1) return(mysql_result($lr,0,0));
        else return("");
        }

function RemoveVar($var,$qs) {
        $vars=explode("&amp;",$qs);
        while (list ($key, $val) = each ($vars)) {
                if (substr($val,0,strlen($var))==$var) unset($vars[$key]);
            }

        $qs="";
        reset($vars);
        while (list ($key, $val) = each ($vars)) $qs.=$val."&amp;";
        $qs=substr($qs,0,-5);
        return($qs);
        }

function GenerateFilter($title) {
        $excl=cnstats_sql_query("select txt,id FROM cns_filters WHERE title='".$title."' ORDER BY id");
        while ($a=mysql_fetch_array($excl,MYSQL_ASSOC)) {
                $e=explode("|||",$a["txt"]);
                if ($excl_count==0) $L=""; else $L=$e[1]==0?"AND":"OR";

                switch ($e[0]) {
                        case 1 : {$F="page";$e[3]=urlencode($e[3]);break;}
                        case 3 : {$F="language";break;}
                        case 4 : {$F="agent";break;}
                        default: {$F="referer";break;}
                        }

                $e[3]=str_replace("%","\%",$e[3]);
                $e[3]=str_replace("*","%",$e[3]);
                $e[3]=str_replace("!","_",$e[3]);

                switch ($e[2]) {
                        case 1: {$quer=$quer." ".$L." ".$F." LIKE '%".$e[3]."%'";$excl_count++;break;}
                        case 2: {$quer=$quer." ".$L." ".$F." NOT LIKE '%".$e[3]."%'";$excl_count++;break;}
                        case 3: {$quer=$quer." ".$L." ".$F." LIKE '".$e[3]."%'";$excl_count++;break;}
                        case 4: {$quer=$quer." ".$L." ".$F." NOT LIKE '".$e[3]."%'";$excl_count++;break;}
                        case 5: {$quer=$quer." ".$L." ".$F." LIKE '%".$e[3]."'";$excl_count++;break;}
                        case 6: {$quer=$quer." ".$L." ".$F." NOT LIKE '%".$e[3]."'";$excl_count++;break;}
                        case 7: {$quer=$quer." ".$L." ".$F." LIKE '".$e[3]."'";$excl_count++;break;}
                        }
                }
        if (!empty($quer)) $quer=" AND (".$quer.")";
        return($quer);
        }

function FormFilter($title) {
        GLOBAL $st,$stm,$ftm,$DATELINK,$LANG;

        print "<form action='index.php'>\n";
        print "<input type='hidden' name='st' value='".$st."'>\n";
        print "<input type='hidden' name='stm' value='".$stm."'>\n";
        print "<input type='hidden' name='ftm' value='".$ftm."'>\n";

        $fields=explode("&amp;",RemoveVar("filter",$DATELINK));
        while (list ($key, $val) = each ($fields)) {
                list ($vkey,$vval)=explode("=",$val);
                if (!empty($vkey)) print "<input type=\"hidden\" name=\"".$vkey."\" value='".cnstats_mhtml(urldecode($vval))."'>\n";
            }

        print "<table align=left><tr><td colspan=2>".$LANG["filter"].":</td></tr><tr><td>";
        $excl=cnstats_sql_query("select title FROM cns_filters GROUP BY title ORDER BY id");
        print "<SELECT name='filter'>";
        print "<OPTION value=''>".$LANG["no filter"]."\n";
        while ($a=mysql_fetch_array($excl,MYSQL_ASSOC)) {
                if ($a["title"]==$title) $sel=" selected"; else $sel="";
                print "<OPTION value='".$a["title"]."'".$sel.">".$a["title"]."\n";
                }
        print "</SELECT>\n";
        print "</td><td><input type='image' src='img/go.gif' width='19' height='18' alt='go' border=''0'></td></tr></table>\n";
        print "</form>\n";
        }


$LANG["softname"]="CNStats 2.5";
?>