<?php /* Smarty version 2.6.14, created on 2007-05-05 08:56:23
         compiled from form.html */ ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>����������� � ������� ������� �������.��</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<link href="/css/orders.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="/comments/form.js"></script>
</head>
<body bgcolor="#f6f6f7">
<form method="POST">
<input type="hidden" name="id" value="<?php echo $this->_tpl_vars['id']; ?>
">
<table class="tbl" bgcolor="#c6c6c6" border="0" cellpadding="5" cellspacing="1" width="500">
  <tbody>
  <tr bgcolor="#ffffff"> 
    <td> ���:</td>
    <td> 
      <input name="name" value="<?php echo $this->_tpl_vars['name']; ?>
" id="input" type="text">
      </td>
  </tr>
  <tr bgcolor="#ffffff"> 
    <td> Email:</td>
    <td> 
      <input name="email" value="<?php echo $this->_tpl_vars['email']; ?>
" id="input" type="text">
      </td>
  </tr>
  <tr bgcolor="#f6f6f6"> 
    <td bgcolor="#ffffff"> �����:</td>
    <td><textarea name="comment" rows="10" type="text" id="input"><?php echo $this->_tpl_vars['comment']; ?>
</textarea></td>
  </tr>
  <tr bgcolor="#dbdbdb"> 
    <td><strong>&nbsp;</strong></td>
    <td><input type="submit" value="�������������" id="submit"></td>
  </tr>
</tbody></table>
</form>
<p align="center"><a href="javascript:back();">������� ����</a></p>

</body></html>