<?php
    echo "<b>".$page["ShortTitle"]."</b><br />";
    echo $page["Content"];

    if ($_POST["getpass"] == 1)
    {
        $err = 0;
                if($_POST["login"]) {
                    $query = "SELECT Login, Email, FirstName, LastName FROM pm_users WHERE Login = '".$_POST["login"]."'";
                    $result = mysql_query($query);
                    if(mysql_num_rows($result)) {
                        $row = mysql_fetch_assoc($result);
                        $pass = setNewPass($row);      
                    } else   {
                        echo "<br>�� ���������� ����������� � ��������� ������� ��� e-mail �������<br><br>";
                        $err = 1;
                    }
                } elseif($_POST["email"]) {
                    $query = "SELECT Login, Email,  FROM pm_users WHERE Email = '".$_POST["email"]."'";
                    $result = mysql_query($query);
                    if(mysql_num_rows($result)) {
                        $row = mysql_fetch_assoc($result);
                        $pass = setNewPass($row);      
                    } else   {
                        echo "<br> ���������� ����������� � ��������� ������� ��� e-mail �������<br><br>";
                        $err = 1;
                    }
                }        
                if ($err == 0)
                {
                    

    $subj = "����������� � ��������-�������� ����-��������� ������� (www.carumba.ru)";
    $body = "������������, ".$row["FirstName"]." ".$row["LastName"].".

�� ����� ������������������ ������������� ������ ��������-�������� ������������� ������� (http://www.carumba.ru/).<br><br>

���� ��������������� ������:<br>
- ����� : ".$row["Login"]."<br>
- ������: ".$pass."<br><br>

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
                        $mail->AddAddress($row["Email"]);
                        $mail->Send();
        
                        $doRegister = 1;
                        unset($_POST);
                        echo "<br>�� ��������� ���� e-mail ��� ����������� ������� ���� ����� � ������<br><br>";

                    
                }
    }
        echo "
        <form method='post' action='".$root_path."/getpass/'><input type='hidden' name='getpass' value='1'>
        ������� ��� �����<br>
        <input type='text' class='fast' name='login' value='".$_POST["login"]."'><br>
        ��� e-mail �����, �� ������� ���� ����������� �����������<br>
        <input type='text' class='fast' name='email' value='".$_POST["email"]."'><br><br><input type='image' src='".$root_path."/img/reg.gif' alt=''></form>";
    
    
    
?>