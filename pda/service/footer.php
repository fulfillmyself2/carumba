</div><div class="bot"><div class="content"><br /><?php
    if ($userID > 1) 
    {
        ?><div class="login">������������,<br /> <?php echo $userData["FirstName"] . " " . $userData["LastName"]; ?></div><a href="<?php echo $root_path; ?>/index.php?logout=1" class="reg">�����</a><?php
    } else
    {
        ?><form class="logform" action="<?php echo $_SERVER["REQUEST_URI"]; ?>" method="post"><input type="text" name="login" value="�����" class="loginp"> <input type="password" name="psw" value="������" class="loginp"> <input type="submit" value="��" class="but"></form><a href="<?php echo $root_path; ?>/registration/" class="reg">�����������</a> <a href="<?php echo $root_path; ?>/getpass/" class="reg">������ ������?</a><?php
    }
    ?> </div></div><div align="center" class="copy">2006-2007 &copy; �������.��</div>
  </div>
</body>
</html>