<?

include_once('mod.Cart.php');

class ClubHandler{

	function ClubHandler(){
	
	}

	function getContent(){
		global $authenticationMgr;
		$userID = $authenticationMgr->getUserID();

		$ret = '';
		$isCardInCart = Cart::isItemInCart(GetCfg('Club.GoodID'));

		if(isset($_GET['act'])){
			$handler = new ClubGetCardHandler();
			$ret.=$handler->getContent();
		}else{
			$userData = array();
			if($userID!=1){
				$userData = $authenticationMgr->getUserData($userID,'');
				$userName = $userData['FirstName']." ".$userData['LastName'];

				$ret.='������������, '.$userName.'<br/><br/>';

				if($isCardInCart || (isset($userData['cardID']) && $userData['cardID']!='0')){
					$ret.= $isCardInCart ? '���� ������� ����� ����� �������� ����� ������<br/>'
						: '����� ����� �����: '.$userData['cardID'];
				}else{
					$ret.='

<table style="width:100%;margin:0 0 20px;" cellspacing="0" cellpadding="0">
	<tr>
		<td style="width:100px;">
		<div style="padding:20px 0 0 20px;"><img src="http://www.carumba.ru/images/minime.gif" border="0" />
<!-- <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="65" height="90">
 <param name="movie" value="/images/carumbych.swf"/>
 <param name="quality" value="high"/>
 <embed src="/images/carumbych.swf" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="65" height="90"></embed>
</object> -->
</div>
		</td>
		<td style="vertical-align:middle;width:195px;">
		<div style="padding:10px;">
<a href="/main/club"><img src="/images/club.gif" alt="�������� ������� (������, ������)" width="175" height="98" border="0" align="absmiddle"/></a>
		</div>

		</td>
		<td><br/><br/>
			<p>��� ���������� � ���� ��� ���������� �������� ����� � �������.<br/>��� ����� �������� � ������� ���� ���� ������ "���� �� �����".<br/>����: 500 ���.<br/>
				<form action="/" method="post" style="margin: 0;">
                  <input type="image" src="/images/buy.gif" alt="������" />
                  <input type=hidden name=module value=Cart>
                  <input type=hidden name=function value=tocart>
                  <input type=hidden name=goodID value="'.GetCfg('Club.GoodID').'">
                  </form>
            </p>
		</td>
	</tr>
</table>
					';
				}
			
			}else{
				$ret.='����������, <a href="/registration">�����������������</a>';
			}

			if(!(sizeof($userData) && $userData['cardID']!='0')){
				$ret.='<div style="margin:10px 0;">
<p>���� ����� �� 1 ������ 2008 ���� <strong>����� 500 ������</strong>! </p>
<strong>5 ��! ��� ���������� � �������� &quot;�������&quot;:</strong>
<ol>
  <li>������ �� ���� ����������� on-line ��������</li>
  <li> ���������� ������ ���� �� ������� ����������� ������� ���������������</li>
  <li> �������������� ������ � ������� ����� �����</li>
  <li> �������������� �������� ��� ������ �������</li>
  <li> ������ ���� ��������� � ��������� ������</li>
</ol>
<p><em>���������� ����� ��������� �� ��������� ����� ��� ������ ������!</em></p>
<p><strong>��� ����� �����������:</strong></p>
<ul>
  <li> ��� ���������� � �������� ��� ������� ����� ������ �����������</li>
</ul>
<p><strong>��� ������������������ �������������:</strong></p>
<ul>
  <li> ���� �� ��� �� �������������� �� ��� ����� ������ �������� ����� � �������</li>
  <li> ����� ����� ����� �������� ��� ����� ����������, ����� �������� ������</li>
</ul>
<em>����� ������� ����������� ������ ����� � ���� ������ ����!</em>
				</div>';
			}
		}

		return $ret;
	}

}

class ClubGetCardHandler{
	function getContent(){
		global $authenticationMgr;
		$userID = $authenticationMgr->getUserID();
		$ret='';
		if($userID==1){
			$ret.='����������, <a href="/registration">�����������������</a>';
		}else{
			$userData = $authenticationMgr->getUserData($userID,'');
			$userName = $userData['FirstName']." ".$userData['LastName'];
			if($userData['cardID']!='0'){
				$ret.= '� ��� ��� ���� �����';
				$ret.='����� ����� �����: '.$card->itemData['cardID'];
			}else{
				$cards = new Cards(array('userID'=>'0'));
				$card = current($cards->items);
				if($card->assignUserID($userID) && $authenticationMgr->setUserData($userID,'cardID',$cardID)){
					$ret.='����� ����� �����: '.$card->itemData['cardID'];
				}else{
					$ret.='������ ���������� �����';
				}
			}
		
		}
		return $ret;
	}
}

?>