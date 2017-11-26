<?php
#
function sortheight($a, $b)
{
 return $b['height'] - $a['height'];
}
#
function dopayments($data, $user)
{
 $btc = 'https://www.blocktrail.com/BTC/address/';
 $btcn = 'blocktrail';
 $addr1 = '1KzFJddTvK9TQWsmWFKYJ9fRx9QeSATyrT';
 $addr2 = '16dRhawxuR3BmdmkzdzUdgEfGAQszgmtbc';
 $addr3 = '1N6LrEDiHuFwSyJYj2GedZM2FGk7kkLjn';
 $addr4 = '1CVVn6sC46aZdokEnU1LThmi8WsMV4qzgh';
 $addr5 = '13pucx6gHP2vyBLc88QfcGivjkhK63PeVg';

 $pg = '<h1>Payments</h1>';
 $pg .= "The payment transactions on $btcn are here:";
 $pg .= " <a href='$btc$addr1' target=_blank>BTCa</a>,";
 $pg .= " <a href='$btc$addr2' target=_blank>BTCb</a>,";
 $pg .= " <a href='$btc$addr3' target=_blank>BTCc</a>,";
 $pg .= " <a href='$btc$addr4' target=_blank>BTCd</a> and";
 $pg .= " <a href='$btc$addr5' target=_blank>BTCe</a><br>";
 $pg .= "The payments below don't yet show when they have been sent.<br>";
 $pg .= "Dust payments below 0.00010000 BTC are not sent out yet.<br><br>";

 $ans = getPayments($user);

 $pg .= "<table cellpadding=0 cellspacing=0 border=0>\n";
 $pg .= '<thead><tr class=title>';
 $pg .= '<td class=dl>Block</td>';
 $pg .= '<td class=dl>Address</td>';
 $pg .= '<td class=dl>Status</td>';
 $pg .= '<td class=dr>BTC</td>';
 $pg .= '<td class=dl></td>';
 $pg .= "</tr></thead>\n";
 if ($ans['STATUS'] == 'ok')
 {
	$pg .= '<tbody>';
	$all = array();
	$count = $ans['rows'];
	for ($i = 0; $i < $count; $i++)
	{
		$all[] = array('payoutid' => $ans['payoutid:'.$i],
				'height' => $ans['height:'.$i],
				'payaddress' => $ans['payaddress:'.$i],
				'amount' => $ans['amount:'.$i],
				'paydate' => $ans['paydate:'.$i]);
	}
	usort($all, 'sortheight');
	$hasdust = false;
	for ($i = 0; $i < $count; $i++)
	{
		if (($i % 2) == 0)
			$row = 'even';
		else
			$row = 'odd';

		$pg .= "<tr class=$row>";
		$pg .= '<td class=dl>'.$all[$i]['height'].'</td>';
		$pg .= '<td class=dl>'.$all[$i]['payaddress'].'</td>';
		$pg .= '<td class=dl>&nbsp;</td>';
		$amount = $all[$i]['amount'];
		if ($amount < '10000')
		{
			$dust = '<span class=st1>*</span>';
			$hasdust = true;
		}
		else
			$dust = '&nbsp;';
		$pg .= '<td class=dr>'.btcfmt($amount).'</td>';
		$pg .= "<td class=dl>$dust</td>";
		$pg .= "</tr>\n";
	}
	$pg .= '</tbody>';
	if ($hasdust === true)
	{
		$pg .= '<tfoot><tr><td colspan=5 class=dc>';
		$pg .= '<font size=-1><span class=st1>*</span> ';
		$pg .= 'Dust payments are not automatically sent out';
		$pg .= '</font></td></tr></tfoot>';
	}
 }
 $pg .= "</table>\n";

 return $pg;
}
#
function show_payments($info, $page, $menu, $name, $user)
{
 gopage($info, NULL, 'dopayments', $page, $menu, $name, $user);
}
#
?>
