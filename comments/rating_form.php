<?php
require_once('../task/config.inc.php');
mysql_connect($dbs, $dbu, $dbp);
mysql_select_db($dbn);
@mysql_query("SET NAMES cp1251"); 

$html = 1;

if (isset($_POST['rating']) && is_array($_POST['rating'])) {
    $rating = $_POST['rating'];
    $id = (isset($_POST['id']) && is_numeric($_POST['id'])) ? $_POST['id'] : 0;
    $r = 1;
    foreach ($rating as $v) {
        $r = (is_numeric($v)) ? 1 : 0;
        if (!$r) break;
    }
    if ($r && $id) {
        for ($i=1; $i<=3; $i++) {
            addRating($id, $i, $rating[$i]);
        }
        $html = 2;
    } else {
        die('Bad params');
    }
}

function addRating($id, $type, $rating)
{
    $oResult = mysql_query("SELECT * FROM pm_rating WHERE sID = '$id' AND type = '$type' LIMIT 1");
    if ($oResult && mysql_num_rows($oResult)) {
        mysql_query("UPDATE pm_rating SET grade=grade+'$rating', count=count+1 WHERE sID = '$id' AND type = '$type' LIMIT 1");
    } else {
        mysql_query("INSERT INTO pm_rating (rID, sID, type, grade, count) VALUES ('', '$id', '$type', '$rating', '1')");
    }
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>������� �������.��</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<style>
<!--
body {
    font-family: Tahoma, Verdana, Arial, sans-serif; 
    font-size: 11px; 
    font-weight: normal;
    color: #343434;
    background-color:#f6f6f7;
    padding:7px;
    margin:0px;
}
.cont {
    border-top:1px solid #DCDDE0;
    color:#62646B;
    padding:3px 0px;
}
.cont select {
    border:1px solid #D1D3D9;
    color:#343434;
    font-size:10px;
    width:120px;
    padding:0px;
    margin:0px;
}
.cont span {
    float:left;
    display:block;
    width:83px;
}
form {
    position: absolute;
    top:7px;
    left:50%;
    width:205px;
    padding:0px;
    margin: 0px 0px 0px -102px;
}
* HTML form {
    width:207px;
    margin: 0px 0px 0px -104px;
}
#send {
    cursor: pointer;
    border-width:0px;
}
#system {
    display:none;
    text-align:center;
}
-->
</style>
<?php
if ($html==1) {
?>
<script>
<!--
window.onload = function()
{
    document.getElementById("send").onclick = function()
    {
        var rating1 = document.getElementById("rating1").value;
        var rating2 = document.getElementById("rating2").value;
        var rating3 = document.getElementById("rating3").value;
        if (rating1!=0 && rating2!=0 && rating3!=0) {
            document.forms[0].submit();
        } else {
           var system = document.getElementById("system");
           system.innerHTML = "������� ��� ��������";
           window.status = "������� ��� ��������";
           system.style.display="block";
        }
    }
}
-->
</script>
</head>
<body>
<form method="POST">
<input type="hidden" name="id" value="<?=$_GET['sID']?>">
<div class="cont">
<span>�������:</span>
            <select name="rating[1]" id="rating1">
                      <option value="0" selected="selected">������� 1 - 5</option>
                      <option value="5">5 - �������</option>
                      <option value="4">4 - ������</option>
                      <option value="3">3 - ������</option>
                      <option value="2">2 - �����</option>
                      <option value="1">1 - ����� �����</option>
                    </select>
</div>

<div class="cont">
<span>����:</span>
            <select name="rating[2]" id="rating2">
                      <option value="0" selected="selected">������� 1 - 5</option>
                      <option value="5">5 - �������</option>
                      <option value="4">4 - ������</option>
                      <option value="3">3 - ������</option>
                      <option value="2">2 - �����</option>
                      <option value="1">1 - ����� �����</option>
                    </select>
</div>

<div class="cont">
<span>��������:</span>
            <select name="rating[3]" id="rating3">
                      <option value="0" selected="selected">������� 1 - 5</option>
                      <option value="5">5 - �������</option>
                      <option value="4">4 - ������</option>
                      <option value="3">3 - ������</option>
                      <option value="2">2 - �����</option>
                      <option value="1">1 - ����� �����</option>
                    </select>
</div>

<div class="cont">
<img src="/images/podrobnee_ocenit.gif" alt="�������" id="send">
</div>
<div id="system"></div>
<?php
} elseif ($html==2) {
?>
<script>
window.onload = function()
{
    window.setTimeout("wclose()", 3000);
}
function wclose()
{
    window.close();
}
</script>
</head>
<body>

<p align="center">��� ����� �����</p>
<p align="center"><a href="javascript:wclose();">������� ����</a></p>
<?php
}
?>
</body></html>