<?
	// ���� ������ ������ ������� ������ ������� �����������
	// ���� ���������� $w3s_results[0], ���������� ����������
	// ����������� � ��������� RESULTS_PER_PAGE, ������������
	// ���������� ����������� �� ��������

	$w3s_page++;
?>
<div class="pages">
 ��������: <?
	$w3s_pages_count=round($w3s_results[0]/RESULTS_PER_PAGE);
	if (!$w3s_pages_count) $w3s_pages_count=1;
	for ($w3s_i=1; $w3s_i<=$w3s_pages_count; $w3s_i++) {
		if ($w3s_i==$w3s_page) echo '<b>' . $w3s_i . '</b> ';
		else echo '<a href="search.php?q=' . urlencode($_GET['q']) . '&amp;page=' . $w3s_i . '">' . $w3s_i . '</a> ';
	}
?>

</div>