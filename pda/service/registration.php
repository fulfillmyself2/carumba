<?php
    echo "<b>".$page["ShortTitle"]."</b><br />";
    echo $page["Content"];
        if ($userID > 1)
        {
           echo "<br />�� ��� ����������������!";             
        } else
        {
          $doRegister = 0; 
          $err  = 0;
          if ($_POST["send_reg"] == 1)
          {
              $usersWithSameEmail = $auth->getUserData(-1, 0,$_POST["email"]);
              $ud = $auth->getUserData(-1, $_POST["login"]);
              if (count($ud))
              {
                  $err = 1;
                  echo "��� ���������� ������������ � ������� `" .  $_POST["login"] . "`.<br />";
              }
              if (count($usersWithSameEmail))
              {
                  $err = 1;
                  echo "��� ���������� ������������ � email `" .  $_POST["email"] . "`.<br />";
              }          
              
              if ($_POST["psw"] != $_POST["psw1"])
              {
                  $err = 1;
                  echo "������ �� ���������.<br><br>";
              }              
              
              if (($_POST["login"] == '')||($_POST["psw"] == '')||($_POST["fname"] == '')||($_POST["lname"] == '')||($_POST["sname"] == '')||($_POST["email"] == '')||($_POST["city"] == '')||($_POST["address"] == '')||($_POST["phone"] == ''))
              {
                  $err = 1;
                  echo "�� ��������� �� ��� ����!<br><br>";
              }
              if ($err == 0)
              {
                    $q = "INSERT INTO pm_users (login,`password`,FirstName,LastName,SurName,Email,sex,phone,city,address) 
                    VALUES (
                    '" . mysql_escape_string($_POST["login"]) . "',
                    MD5('" . mysql_escape_string($_POST["psw"]) . "'),
                    '" . mysql_escape_string($_POST["fname"]) . "',
                    '" . mysql_escape_string($_POST["lname"]) . "',
                    '" . mysql_escape_string($_POST["sname"]) . "',
                    '" . mysql_escape_string($_POST["email"]) . "',
                    '" . mysql_escape_string($_POST["sex"]) . "',
                    '" . mysql_escape_string($_POST["phone"]) . "',
                    '" . mysql_escape_string($_POST["city"]) . "',
                    '" . mysql_escape_string($_POST["address"]) . "'
                    )";

                   
                    $qr = mysql_query($q);

                    if ($qr)
                    {
                        
                        $subj = "����������� � ��������-�������� ����-��������� ������� (www.carumba.ru)";
                        $body = "������������, ".$_POST["fname"]." ".$_POST["lname"].".

�� ����� ������������������ ������������� ������ ��������-�������� ������������� ������� (http://www.carumba.ru/).<br><br>

���� ��������������� ������:<br>
- ����� : ".$_POST["login"]."<br>
- ������: ".$_POST["psw"]."<br><br>

����������� ��� ��������� ��� ���������, ����� �� ������ ���� ��������������� ������.<br><br>

---<br>
���������� ��� �������� � �������� \"�������\", ��� ���� ��� �����������:<br>
�������� �������������� ������, ���������� ��������, <br>
� ��� �� ������ ���� ����� ����������� � ��������� ������!<br>
��������� � ������� \"���� (������)\" http://www.carumba.ru/main/club<br>
---<br><br>

���� ����� �������. ������ ����� ���� ������ ��� � ����� ��������-��������.<br> ";
                                
                        
                        
                        $mail = new PHPMailer();

                        $mail->IsSMTP(); // set mailer to use SMTP
                        $mail->Host = "localhost";  // specify main and backup server
                        $mail->SMTPAuth = true;     // turn on SMTP authentication
                        $mail->Username = "robot@carumba.ru";  // SMTP username
                        $mail->Password = "Vifi2Ht6b"; // SMTP password

                        $mail->From = 'info@carumba.ru';
                        $mail->FromName = 'Carumba.ru';

                        $mail->WordWrap = 50;                                 // set word wrap to 50 characters
                        $mail->IsHTML(true);                                  // set email format to HTML
                        $mail->Subject = $subj;
                        $mail->Body = $body;
                        $mail->AddAddress($_POST["email"]);
                        $mail->Send();
        
                        $doRegister = 1;
                        unset($_POST);
                        echo "<br>������� �� �����������! �� ��� email ���������� ������ � ������ ����������� �������.<br>";
                     }              
              }
          } else
          {
             unset($_POST); 
             $_POST["address"] = '����� ��������';
          }
           if ($doRegister == 0)
           {
              if ($_POST['sex'] == 'female') $f2 = ' checked';  else $f1 = ' checked';
              echo "
              ����� �������� ������, �� ��� e-mail ����� �������� ������.<br><br>
              !!!��� ���� ������ ������ ����������� ��� ����������.
              <br />
              <form method='post' action='".$root_path."/registration/'><input type='hidden' name='send_reg' value='1'>
              <table cellpadding=0 cellspacing=0>
              <tr><td height='25'><b>�����:</b></td><td><input type='text' class='fast' name='login' value='".$_POST["login"]."'></td></tr>
              <tr><td height='25'><b>������:</b></td><td><input type='password' class='fast' name='psw'></td></tr>
              <tr><td height='25'><b>������:</b></td><td><input type='password' class='fast' name='psw1'></td></tr>
              <tr><td height='25'><b>E-mail: &nbsp;</b></td><td><input type='text' class='fast' name='email' value='".$_POST["email"]."'></td></tr>
              <tr><td height='25'><b>���.:</b></td><td><input type='text' class='fast' name='lname' value='".$_POST["lname"]."'></td></tr>
              <tr><td height='25'><b>���:</b></td><td><input type='text' class='fast' name='fname' value='".$_POST["fname"]."'></td></tr>
              <tr><td height='25'><b>���.:</b></td><td><input type='text' class='fast' name='sname' value='".$_POST["sname"]."'></td></tr>
              <tr><td height='25'><b>���.:</b></td><td><input type='text' class='fast' name='phone' value='".$_POST["phone"]."'></td></tr>
              <tr><td height='25'><b>���:</b></td><td>���. <input type='radio' name='sex'".$f1." value='male'> &nbsp;&nbsp;���. <input type='radio' name='sex' value='female'".$f2."> </td></tr>
              <tr><td height='25'><b>�����:</b></td><td><input type='text' class='fast' name='city' value='".$_POST["city"]."'></td></tr>              
              </table>
              <textarea class='tfast' rows='6' name='address'>".$_POST["address"]."</textarea><br />
              <input type='image' src='".$root_path."/img/reg.gif' alt=''></form>
              "; 
           }
        }
    
?>