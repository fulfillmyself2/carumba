<?
	// ������� �������� ����������-������ � ������ $item
	// ������ �������:
	// $item=array (
	//	'url'		=> ��� �������� (��� ������)
	//	'title'		=> ��������� ��������
	//	'lastupdate'	=> ����� ��������� ����������
	// );
?>

<div class="result">
 <a href="<?=STARTURL ?><?=$w3s_item['url'] ?>" target="_blank"><?=$w3s_item['title'] ?></a><br />
 <span class="date">���� ����������: <?=w3s_mdate($w3s_item['lastupdate']) ?></span>
</div>