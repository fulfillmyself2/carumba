	<form action="/" method="post">
	<div class="basket">
	<input type="hidden" name="module" value="Cart" />
	<input type="hidden" name="cmd" value="buycart" />

<table cellspacing="1" cellpadding="0" class="basinf">
  <tr>
      <td class="left">
<p><strong>����� ��������</strong>:<br />
 <?=$object['address']?><br />
<a href="/profile/"><img src="/images/arr_gray2.gif" width="7" height="9"  alt="" />�������� (��������) �����</a>
</p>
<p><strong>���������� �������</strong>:<br />
 <?=$object['phone']?><br />
<a href="/profile/"><img src="/images/arr_gray2.gif" width="7" height="9"  alt="" />�������� (��������) �������</a>
</p>
<input type="image" src="/images/buy_basket.gif" alt="��������� �����"/>
</td>
    <td class="right">
<p><strong>�����������:</strong></p>
<textarea name="comment_" class="input05" rows="5" cols="40"></textarea>
	</td>
  </tr>
</table>
</div>
</form>

