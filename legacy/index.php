<?php $db = new SQLite3('data.db');?>[KSML:COAL][TITLE]Krist[/TITLE]
[BG:WHITE][HL:CYAN] [HL:WHITE][C:LIME]/[/C][/HL][HL:LIME][C:GREEN]\[/C][/HL]  KRIST  
[BR] [HL:LIME][C:GREEN]\[/C][/HL][HL:GREEN][C:LIME]/[/C][/HL] [C:GRAY]Node #X[/C] 

<?php 
function hextobase36($j) { //there is definitely a much better way to do this
  if ( $j <= 6 )  return "0";
  elseif ( $j <= 13 )  return "1";
  elseif ( $j <= 20 )  return "2";
  elseif ( $j <= 27 )  return "3";
  elseif ( $j <= 34 )  return "4";
  elseif ( $j <= 41 )  return "5";
  elseif ( $j <= 48 )  return "6";
  elseif ( $j <= 55 )  return "7";
  elseif ( $j <= 62 )  return "8";
  elseif ( $j <= 69 )  return "9";
  elseif ( $j <= 76 )  return "a";
  elseif ( $j <= 83 )  return "b";
  elseif ( $j <= 90 )  return "c";
  elseif ( $j <= 97 )  return "d";
  elseif ( $j <= 104 )  return "e";
  elseif ( $j <= 111 )  return "f";
  elseif ( $j <= 118 )  return "g";
  elseif ( $j <= 125 )  return "h";
  elseif ( $j <= 132 )  return "i";
  elseif ( $j <= 139 )  return "j";
  elseif ( $j <= 146 )  return "k";
  elseif ( $j <= 153 )  return "l";
  elseif ( $j <= 160 )  return "m";
  elseif ( $j <= 167 )  return "n";
  elseif ( $j <= 174 )  return "o";
  elseif ( $j <= 181 )  return "p";
  elseif ( $j <= 188 )  return "q";
  elseif ( $j <= 195 )  return "r";
  elseif ( $j <= 202 )  return "s";
  elseif ( $j <= 209 )  return "t";
  elseif ( $j <= 216 )  return "u";
  elseif ( $j <= 223 )  return "v";
  elseif ( $j <= 230 )  return "w";
  elseif ( $j <= 237 )  return "x";
  elseif ( $j <= 244 )  return "y";
  elseif ( $j <= 251 )  return "z";//>
  else return "e";
}
function makeV2($key) {
$stick = hash('sha256',hash('sha256',$key));
for ($p = 0; $p < 9; $p += 1) {
if ($p < 9) $prot[$p] = substr($stick,0,2);
$stick = hash('sha256',hash('sha256',$stick));
}
$v2 = "k";
for ($p = 0; $p < 9; $p += 1) {
$link = hexdec(substr($stick,2*$p,2)) % 9;
if ($prot[$link] == "zzz") {
$stick = hash('sha256',$stick);
$p -= 1;
} else {
$v2 .= hextobase36(hexdec($prot[$link]));
$prot[$link] = "zzz";
}
//$stick = hash('sha256',hash('sha256',$stick));
}
return $v2;
}
if (isset($_GET['chain'])) {die('This call has been deprecated 5/27/2015');
if (isset($_GET['web'])) {

} else {
echo '<float bgcolour="lightGray"><p width="6" align="right">Height</p><p width="13" align="right">Hash</p><p width="11" align="right">Miner</p><p width="20" align="right">Time</p></float>';
$blocks = $db->query('SELECT * FROM blocks ORDER BY id DESC LIMIT 20');
while ($block = $blocks->fetchArray()) {
echo '<float><p width="6" align="right">'.$block['id'].'</p><p width="13" align="right">'.substr($block['hash'],0,12).'</p><a href="/index.php?showaddress='.$block['address'].'" ulcolour="none" width="11" align="right">'.$block['address'].'</a><p width="20" align="right">'.date("Y-m-d H:i:s",$block['time']).'</p></float>';
}
}
} else if (isset($_GET['explorer'])) {
ob_end_clean();
echo '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css"><link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css"><script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script><style>body {padding-top: 50px;}.area {padding: 40px 150px;text-align: center;}</style><div class="area">';
echo '<h1><b>KRIST</b>EXPLORER</h1>';
echo '<ul class="nav nav-tabs" role="tablist">
    <li role="presentation"'; if (strlen($_GET['explorer']) == 0) echo ' class="active"'; echo '><a href="?explorer">Latest blocks</a></li>
    <li role="presentation"'; if ($_GET['explorer'] == "richlist") echo ' class="active"'; echo '><a href="?explorer=richlist">Richest addresses</a></li>
    <li role="presentation"'; if ($_GET['explorer'] == "recenttx") echo ' class="active"'; echo '><a href="?explorer=recenttx">Recent transactions</a></li>
  </ul>';
if (strlen($_GET['explorer']) == 0) {
  echo '<table class="table table-condensed"><tr><th>Height</th><th>Hash</th><th>Miner</th><th>Value</th><th>Time</th></tr>';
  $blocks = $db->query('SELECT * FROM blocks ORDER BY id DESC LIMIT 300');
  while ($block = $blocks->fetchArray()) {
    echo '<tr><td>'.$block['id'].'</td><td style="font-family: \'Lucida Console\';">'.substr($block['hash'],0,12).'<small style="color:gray;">'.substr($block['hash'],12,12).'</small></td><td style="font-family: \'Lucida Console\';"><a href="?explorer='.$block['address'].'" ulcolour="none" width="11" align="right">'.$block['address'].'</a></td><td>'.number_format($block['value'],0).' KST</td><td>'.date("Y-m-d H:i:s",$block['time']).'</td></tr>';
  }
  echo '</table>';
}
if ($_GET['explorer'] == "richlist") {
  echo '<table class="table table-condensed"><tr><th>Rank</th><th>Address</th><th>Balance</th><th>First transaction</th></tr>';
  $blocks = $db->query('SELECT * FROM addresses ORDER BY balance DESC LIMIT 2000');
  $cash = 0;
  while ($block = $blocks->fetchArray()) {
    $cash += 1;
    echo '<tr><td>'.$cash.'</td><td style="font-family: \'Lucida Console\';"><a href="?explorer='.$block['address'].'" ulcolour="none" width="11" align="right">'.$block['address'].'</a></td><td>'.number_format($block['balance'],0).' KST</td><td>'.date("Y-m-d H:i:s",$block['firstseen']).'</td></tr>';
  }
  echo '</table>';
}
if ($_GET['explorer'] == "recenttx") {
  echo '<table class="table table-condensed"><tr><th>From</th><th>To</th><th>Value</th><th>Time</th></tr>';
  $blocks = $db->query('SELECT * FROM transactions WHERE `from` != "" ORDER BY id DESC LIMIT 20');
  while ($block = $blocks->fetchArray()) {
    echo '<tr><td style="font-family: \'Lucida Console\';"><a href="?explorer='.$block['from'].'" ulcolour="none" width="11" align="right">'.$block['from'].'</a></td><td style="font-family: \'Lucida Console\';"><a href="?explorer='.$block['to'].'" ulcolour="none" width="11" align="right">'.$block['to'].'</a></td><td>'.number_format($block['value'],0).' KST</td><td>'.date("Y-m-d H:i:s",$block['time']).'</td></tr>';
  }
  echo '</table>';
}
if (strlen($_GET['explorer']) == 10) {
$address = $db->query('SELECT * FROM addresses WHERE address = "'.$_GET['explorer'].'"')->fetchArray();
$txs = $db->query('SELECT * FROM transactions WHERE (`to` = "'.$_GET['explorer'].'" OR `from` = "'.$_GET['explorer'].'") ORDER BY time DESC LIMIT 50');
$bal = $address['balance'];
$tag = $address['tag'];
echo '<h3>Address '.$_GET['explorer'].'<br/><small>'.$tag.'</small></h3>';
//echo '<float bgcolour="yellow"><p width="25" align="left">Address: '.$_GET['explorer'].'</p><p width="25" align="right">Balance: '.$bal.' KST</p></float>';
//echo '<float bgcolour="lightGray"><p width="18" align="right">Time</p><p width="11" align="right">Peer</p><p width="11" align="right">Value</p><p width="10" align="right">Balance</p></float>';
echo '<table class="table table-condensed"><tr><th>Peer</th><th>Value</th><th>Time</th></tr>';
while ($tx = $txs->fetchArray()) {
  if ($tx['from'] == $_GET['explorer']) $peer = $tx['to'];
  if ($tx['to'] == $_GET['explorer']) $peer = $tx['from'];
  if ($tx['from'] == $tx['to']) $peer = $tx['from'];
  if ($peer == "") $peer = 'Block '.$db->query('SELECT * FROM blocks WHERE `time` == '.$tx['time'].' ORDER BY id DESC LIMIT 1')->fetchArray()['id'];
  echo '<tr><td style="font-family: \'Lucida Console\';"><a href="?explorer='.$peer.'" ulcolour="none" width="11" align="right">'.$peer.'</a></td><td>'.number_format($tx['value'],0).' KST</td><td>'.date("Y-m-d H:i:s",$tx['time']).'</td></tr>';
  
}
echo '</table>';
}
} else if (isset($_GET['v2'])) {die('This call has been deprecated 5/27/2015');
ob_end_clean(); 
echo makeV2($_GET['v2']);
} else if (isset($_GET['blocks'])) {
ob_end_clean(); $blox = 0; $lim = "17"; $ord = "id DESC";
if (isset($_GET['low'])) {$lim = "18"; $ord = "hash ASC";}
if (isset($_GET['lownonce'])) {$ord = "nonce ASC";}
if (isset($_GET['highnonce'])) {$ord = "nonce DESC";}
//echo '<float bgcolour="lightGray"><p width="6" align="right">Height</p><p width="13" align="right">Hash</p><p width="11" align="right">Miner</p><p width="20" align="right">Time</p></float>';
$blocks = $db->query('SELECT * FROM blocks WHERE id > 4000 ORDER BY '.$ord.' LIMIT '.$lim);
while ($block = $blocks->fetchArray()) {
$blox += 1;
if ($blox == 1 and isset($_GET['low']) == 0) echo sprintf("%08d",$block['id']);
if ($blox == 1 and isset($_GET['low']) == 0) echo date("Y-m-d",$block['time']);
if ($block['address'] == '') $block['address'] = "N/A(Burnt)";
if ($block['address'] == 'N\A') $block['address'] = "N/A(Burnt)";
if ($block['address'] == 'C:\Users\Bryan\Downloads\miner.py') $block['address'] = "N/A(Burnt)";
if ($block['address'] == '2bb037a6f') $block['address'] = "N/A(Burnt)";
if (isset($_GET['low'])) {
$feat = substr($block['hash'],0,20);
if (isset($_GET['lownonce'])) {$feat = sprintf("%012d", $block['nonce']);}
if (isset($_GET['highnonce'])) {$feat = sprintf("%012d", $block['nonce']);}
echo date("M d",$block['time']).sprintf("%06d", $block['id']).$feat;
} else {echo date("H:i:s",$block['time']).substr($block['address'],0,10).substr($block['hash'],0,12);}
}
} else if (isset($_GET['richapi'])) {
ob_end_clean(); $blox = 0;
//echo '<float bgcolour="lightGray"><p width="6" align="right">Height</p><p width="13" align="right">Hash</p><p width="11" align="right">Miner</p><p width="20" align="right">Time</p></float>';
$blocks = $db->query('SELECT * FROM addresses ORDER BY balance DESC LIMIT 16');
while ($block = $blocks->fetchArray()) {
if ($block['address'] == '') $block['address'] = "N/A(Burnt)";
if ($block['address'] == 'N\A') $block['address'] = "N/A(Burnt)";
if ($block['address'] == 'C:\Users\Bryan\Downloads\miner.py') $block['address'] = "N/A(Burnt)";
if ($block['address'] == '2bb037a6f') $block['address'] = "N/A(Burnt)";
echo substr($block['address'],0,10).sprintf("%08d", $block['balance']).date("d M Y",$block['firstseen']);
}
} else if (isset($_GET['richlist'])) {die('This call has been deprecated 5/27/2015');
echo '<float bgcolour="lightGray"><p width="6" align="right">Rank</p><p width="11" align="right">Address</p><p width="15" align="right">Balance</p><p width="18" align="right">First seen</p></float>';
$addresses = $db->query('SELECT * FROM addresses ORDER BY balance DESC LIMIT 20');
$rank = 0;
while ($address = $addresses->fetchArray()) {
$rank += 1;
if ($address['address'] == '') {$addressname = 'NULL   ';} else {$addressname = $address['address'];}
echo '<float><p width="6" align="right">#'.$rank.'</p><a href="/index.php?showaddress='.$address['address'].'" ulcolour="none" width="11" align="right">'.$addressname.'</a><p width="15" align="right">'.$address['balance'].' KST</p><p width="18" align="right">'.date("Y-m-d H:i",$address['firstseen']).'</p></float>';
}
} else if (isset($_GET['recenttx'])) {
ob_end_clean();
if (isset($_GET['lots'])) {$lim = 20000;} else {$lim = 32;}
$txs = $db->query('SELECT * FROM transactions WHERE (`from` != "") ORDER BY time DESC LIMIT '.$lim);
while ($tx = $txs->fetchArray()) {
echo ''.date("M d H:i",$tx['time']).''.$tx['from'].$tx['to'].sprintf("%08d", abs($tx['value'])).'';
}} else if (isset($_GET['listtx'])) { ob_end_clean(); if (true) {

if (isset($_GET['overview'])) {$lim = 3;} else {$lim = 15984;}
if (strlen($_GET['listtx']) != 10) die('Error4');
if (substr($_GET['listtx'],0,1) == 'k') {if (!ctype_alnum($_GET['listtx'])) die('Error4');} else {if (!ctype_xdigit($_GET['listtx'])) die('Error4');}
$address = $db->query('SELECT * FROM addresses WHERE address = "'.$_GET['listtx'].'"')->fetchArray();
$txs = $db->query('SELECT * FROM transactions WHERE (`to` = "'.$_GET['listtx'].'" OR `from` = "'.$_GET['listtx'].'") ORDER BY time DESC LIMIT '.$lim);
$bal = 0;
//echo '<float bgcolour="yellow"><p width="25" align="left">Address: '.$_GET['listtx'].'</p><p width="25" align="right">Balance: '.$bal.' KST</p></float>';
//echo '<float bgcolour="lightGray"><p width="18" align="right">Time</p><p width="11" align="right">Peer</p><p width="11" align="right">Value</p><p width="10" align="right">Balance</p></float>';
while ($tx = $txs->fetchArray()) {
if ($tx['to'] == $_GET['listtx']) {$peer = $tx['from'];$sign = '+';$colr = 'green';} else {$peer = $tx['to'];$sign = '-';$colr = 'red';}
if (strlen($tx['from']) < 10) $peer = 'N/A(Mined)';
if (strlen($tx['to']) < 10) $peer = 'N/A(Names)';
echo ''.date("M d H:i",$tx['time']).''.$peer.$sign.sprintf("%08d", abs($tx['value'])).'';
if ($sign == '+') {$bal -= $tx['value'];} else {$bal += $tx['value'];}}

}die('end');} else if (isset($_GET['getdomainvalue'])) {
ob_end_clean();
$name = strtolower($_GET['getdomainvalue']);
if (!ctype_alnum($name)) die('Error6');
if (str_replace(" ","$",$name) != $name) die('Error6');
if (strlen($name) > 64) die('Error6');
if (strlen($name) < 1) die('Error6');
echo $db->query('SELECT * FROM names WHERE (`name` = '.$name.') ORDER BY id DESC')->fetchArray()['unpaid'];
die();
} else if (isset($_GET['getnewdomains'])) {
ob_end_clean();
$txs = $db->query('SELECT * FROM names WHERE (`unpaid` > 0) ORDER BY id DESC');
while ($tx = $txs->fetchArray()) {
echo $tx['name'];
echo ';';
}
die();
} else if (isset($_GET['listnames'])) {
ob_end_clean();
if (strlen($_GET['listnames']) != 10) die('Error4');
if (substr($_GET['listnames'],0,1) == 'k') {if (!ctype_alnum($_GET['listnames'])) die('Error4');} else {if (!ctype_xdigit($_GET['listnames'])) die('Error4');}
$txs = $db->query('SELECT * FROM names WHERE (`owner` = "'.$_GET['listnames'].'") ORDER BY name ASC');
while ($tx = $txs->fetchArray()) {
echo $tx['name'];
echo ';';
}
die();
} else if (isset($_GET['dumpnames'])) {
ob_end_clean();
$txs = $db->query('SELECT * FROM names ORDER BY name ASC');
while ($tx = $txs->fetchArray()) {
echo $tx['name'];
echo ';';
}
die();
} else if (isset($_GET['printcheckpoints'])) {die('This call has been deprecated 5/27/2015');
  ob_end_clean(); $blocks = $db->query('SELECT * FROM blocks WHERE id > 0 ORDER BY hash ASC LIMIT 2000');
  echo '<body style=\'font-family:"Lucida Console"\'>';
  while ($block = $blocks->fetchArray()) {
    echo $block['hash'].' - '.$block['id'].'<br/>';
  }
} else if (isset($_GET['sync'])) {die('This call has been deprecated 5/27/2015');
  ob_end_clean(); $blocks = $db->query('SELECT * FROM blocks WHERE id >= 40001 AND id <= 40100 ORDER BY id ASC');
  while ($block = $blocks->fetchArray()) {
  //       i'm a block!      nonce         end of nonce       miner        end of address    timestamp
    echo '[$$$$$$$$$$$]'.$block['nonce'].'[!!!!!!!!!!!]'.$block['address'].'[!!!!!!!!!!!]'.$block['time'];
  }
} else if (isset($_GET['pushtx'])) {
    error_reporting(0);
    ob_end_clean();
    $from = substr(hash('sha256',''.$_GET['pkey']),0,10);
    $address = $db->query('SELECT * FROM addresses WHERE address = "'.$from.'"')->fetchArray();
    if ($address['balance'] < $_GET['amt']) die('Error1');
    if (!is_numeric($_GET['amt'])) die('Error3');
    if (1 > $_GET['amt']) die('Error2');
    if (strlen($_GET['q']) != 10) die('Error4');
    if (substr($_GET['q'],0,1) == 'k') {if (!ctype_alnum($_GET['q'])) die('Error4');} else {if (!ctype_xdigit($_GET['q'])) die('Error4');}
    $db->query('INSERT INTO addresses (address, firstseen) VALUES ("'.$_GET['q'].'", "'.time().'")');
    $db->query('UPDATE addresses SET balance = balance - '.$_GET['amt'].' WHERE address = "'.$from.'"');
    $db->query('UPDATE addresses SET totalout = totalout + '.$_GET['amt'].' WHERE address = "'.$from.'"');
    $db->query('UPDATE addresses SET balance = balance + '.$_GET['amt'].' WHERE address = "'.$_GET['q'].'"');
    $db->query('UPDATE addresses SET totalin = totalin + '.$_GET['amt'].' WHERE address = "'.$_GET['q'].'"');
    $db->query('INSERT INTO transactions (`to`, `from`, value, time) VALUES ("'.$_GET['q'].'", "'.$from.'", "'.$_GET['amt'].'", "'.time().'")');
    echo('Success');
    die();
} else if (isset($_GET['pushtx2'])) {
    error_reporting(0);
    ob_end_clean();
		if ($_GET['q'] == "kyscekhdpy===") {
			echo('Unban me, Liam. ;)');
			die();
		}
    $from = makeV2($_GET['pkey']);
    $address = $db->query('SELECT * FROM addresses WHERE address = "'.$from.'"')->fetchArray();
    if ($address['balance'] < $_GET['amt']) die('Error1');
    if (!is_numeric($_GET['amt'])) die('Error3');
    if (1 > $_GET['amt']) die('Error2');
    if (strlen($_GET['q']) != 10) die('Error4');
    if (substr($_GET['q'],0,1) == 'k') {if (!ctype_alnum($_GET['q'])) die('Error4');} else {if (!ctype_xdigit($_GET['q'])) die('Error4');}
    $db->query('INSERT INTO addresses (address, firstseen) VALUES ("'.$_GET['q'].'", "'.time().'")');
    $db->query('UPDATE addresses SET balance = balance - '.$_GET['amt'].' WHERE address = "'.$from.'"');
    $db->query('UPDATE addresses SET totalout = totalout + '.$_GET['amt'].' WHERE address = "'.$from.'"');
    $db->query('UPDATE addresses SET balance = balance + '.$_GET['amt'].' WHERE address = "'.$_GET['q'].'"');
    $db->query('UPDATE addresses SET totalin = totalin + '.$_GET['amt'].' WHERE address = "'.$_GET['q'].'"');
    $db->query('INSERT INTO transactions (`to`, `from`, value, time) VALUES ("'.$_GET['q'].'", "'.$from.'", "'.$_GET['amt'].'", "'.time().'")');
    echo('Success');
    die();
} else if (isset($_GET['a'])) {ob_end_clean();
if (!ctype_alnum($_GET['a'])) die();
echo $db->query('SELECT a FROM names WHERE name = "'.$_GET['a'].'"')->fetchArray()['a'];
die();
} else if (isset($_GET['getnames'])) {ob_end_clean();
echo $db->query('SELECT COUNT(*) AS count FROM names WHERE owner = "'.$_GET['getnames'].'"')->fetchArray()['count']; die;
} else if (isset($_GET['name_cost'])) {ob_end_clean();
echo 500;
} else if (isset($_GET['name_transfer'])) {
    ob_end_clean();
    $from = makeV2($_GET['pkey']);
		$to = $_GET['q'];
    $name = strtolower($_GET['name']);
    $block = $db->query('SELECT id FROM blocks ORDER BY id DESC LIMIT 1')->fetchArray()['id'];
    if (!ctype_alnum($name)) die('Error6');
    if (strlen($name) > 64) die('Error6');
    if (strlen($_GET['q']) != 10) die('Error4');
    if (substr($_GET['q'],0,1) == 'k') {if (!ctype_alnum($_GET['q'])) die('Error4');} else {die('Error4');}
    if ($db->query('SELECT * FROM names WHERE `name` = "'.$name.'"')->fetchArray()['owner'] != $from) die($name);
    $db->query('INSERT INTO transactions (`to`, `from`, value, time, `name`) VALUES ("'.$to.'", "'.$from.'", "0", "'.time().'", "'.$name.'")');
    $db->query('UPDATE names SET `owner` = "'.$to.'" WHERE `name` = "'.$name.'"');
    $db->query('UPDATE names SET `updated` = "'.$block.'" WHERE `name` = "'.$name.'"');
    echo('Success');
} else if (isset($_GET['name_update'])) {
    //error_reporting(0);
    ob_end_clean();
    $from = makeV2($_GET['pkey']);
    $name = strtolower($_GET['name']);
    $a = $_GET['ar'];
    $block = $db->query('SELECT id FROM blocks ORDER BY id DESC LIMIT 1')->fetchArray()['id'];
    if (!ctype_alnum($name)) die('Error6');
    if (strlen($name) > 64) die('Error6');
    if (strlen($a) > 256) die('Error8');
    if (!preg_match("#^[a-zA-Z0-9\./\-\$]+$#", $a)) die('Error8');
    if ($db->query('SELECT * FROM names WHERE `name` = "'.$name.'"')->fetchArray()['owner'] != $from) die($name);
    $db->query('INSERT INTO transactions (`to`, `from`, value, time, `name`, `op`) VALUES ("a", "'.$from.'", "0", "'.time().'", "'.$name.'", "'.$a.'")');
    $db->query('UPDATE names SET `a` = "'.$a.'" WHERE `name` = "'.$name.'"');
    $db->query('UPDATE names SET `updated` = "'.$block.'" WHERE `name` = "'.$name.'"');
    echo('Success');
    die();
} else if (isset($_GET['name_new'])) {
    error_reporting(0);
    ob_end_clean();
    $from = makeV2($_GET['pkey']);
    $name = strtolower($_GET['name']);
    $cost = 500;
    $block = $db->query('SELECT id FROM blocks ORDER BY id DESC LIMIT 1')->fetchArray()['id'];
    $address = $db->query('SELECT * FROM addresses WHERE address = "'.$from.'"')->fetchArray();
    if (!ctype_alnum($name)) die('Error6');
    if (!preg_match("#^[a-zA-Z0-9]+$#", $name)) die('Error6');
    if (str_replace(" ","$",$name) != $name) die('Error6');
    if (strlen($name) > 64) die('Error6');
    if (strlen($name) < 1) die('Error6');
    if (strlen($db->query('SELECT * FROM names WHERE `name` = "'.$name.'"')->fetchArray()['owner']) > 9) die('Error5');
    if ($address['balance'] < $cost) die('Error1');
    $db->query('UPDATE addresses SET balance = balance - '.$cost.' WHERE address = "'.$from.'"');
    $db->query('UPDATE addresses SET totalout = totalout + '.$cost.' WHERE address = "'.$from.'"');
    $db->query('INSERT INTO transactions (`to`, `from`, value, time, `name`) VALUES ("name", "'.$from.'", "'.$cost.'", "'.time().'", "'.$name.'")');
    $db->query('INSERT INTO names (`name`, `owner`, `registered`, `updated`, `expires`, `unpaid`) VALUES ("'.$name.'", "'.$from.'", "'.$block.'", "'.$block.'", "'.($block+100000).'", "'.$cost.'")');
    echo('Success');
    die();
} else if (isset($_GET['name_check'])) {
    error_reporting(0);
    ob_end_clean();
    echo '0';
    $name = strtolower($_GET['name_check']);
    if (!ctype_alnum($name)) die();
    if (strlen($name) > 64) die();
    if (strlen($name) < 1) die();
    if (strlen($db->query('SELECT * FROM names WHERE `name` = "'.$name.'"')->fetchArray()['owner']) > 9) die();
    ob_end_clean();
    echo('1');
    die();
} else if (isset($_GET['transaction'])) {die('This call has been deprecated 5/30/2015');
echo '<br><p colour="red" align="center">';
$from = substr(hash('sha256',''.$_POST['pkey']),0,10);
$address = $db->query('SELECT * FROM addresses WHERE address = "'.$from.'"')->fetchArray();
if ($address['balance'] < $_POST['amt']) die('Insufficient funds from address '.$from.'!</p>');
if (1 > $_POST['amt']) die('Not enough to send!</p>');
if (!is_numeric($_POST['amt'])) die('Invalid amount!</p>');
if (strlen($_POST['q']) != 10) die('Invalid recipient!</p>');
if (!ctype_xdigit($_POST['q'])) die('Invalid recipient!</p>');
$db->query('INSERT INTO addresses (address, firstseen) VALUES ("'.$_POST['q'].'", "'.time().'")');
$db->query('UPDATE addresses SET balance = balance - '.$_POST['amt'].' WHERE address = "'.$from.'"');
$db->query('UPDATE addresses SET totalout = totalout + '.$_POST['amt'].' WHERE address = "'.$from.'"');
$db->query('UPDATE addresses SET balance = balance + '.$_POST['amt'].' WHERE address = "'.$_POST['q'].'"');
$db->query('UPDATE addresses SET totalin = totalin + '.$_POST['amt'].' WHERE address = "'.$_POST['q'].'"');
$db->query('INSERT INTO transactions (`to`, `from`, value, time) VALUES ("'.$_POST['q'].'", "'.$from.'", "'.$_POST['amt'].'", "'.time().'")');
echo '</p><p colour="green" align="center">Success!</p>';
} else if (isset($_GET['transfer'])) {die('This call has been deprecated 5/30/2015');
echo '<br><h>Transfer Krist</h>';
echo '<br><form method="post" action="http://krist.dia/index.php?transaction">';
echo '<float><p colour="white" width="8">--------</p><p width="13">Recipient</p> ';
echo '<input type="text" name="q" bgcolour="lightGray" placeholder="0000000000"/></float><br>';
echo '<float><p colour="white" width="8">--------</p><p width="13">Private key</p> ';
echo '<input type="text" name="pkey" bgcolour="lightGray" placeholder=""/></float><br>';
echo '<float><p colour="white" width="8">--------</p><p width="13">Amount (KST)</p> ';
echo '<input type="text" name="amt" bgcolour="lightGray" placeholder="10"/></float><br>';
echo '<float><p colour="white" width="20">--------------------</p>
<input type="submit" bgcolour="blue" name="submit" value="Transfer" /></float>';
echo '</form>';
} else if (isset($_GET['getwalletversion'])) {
ob_end_clean();
echo '11'; ///////////////////////////////////////////////////////////// WALLET VERSION NUMBER
die();
} else if (isset($_GET['gettag'])) {
ob_end_clean();
//$address = $db->query('SELECT * FROM addresses WHERE address = "'.$_GET['gettag'].'"')->fetchArray();
//echo $address['tag'];
//if (strlen($address['tag']) == 0) echo 'No tag';
die('This call has been deprecated 5/27/2015');
} else if (isset($_GET['getbalance'])) { //SQL INJECTION ALERT! FIX ASAP! discovered 4/29
ob_end_clean();
//if (strlen($_GET['getbalance']) != 10) die('nil');
//if (!ctype_xdigit($_GET['getbalance'])) die('nil'); //this is stupid
$thing  = $_GET['getbalance'];
$thing = str_replace('"',"bad",$thing);
$address = $db->query('SELECT * FROM addresses WHERE address = "'.$thing.'"')->fetchArray();
echo $address['balance'];
if (strlen($address['balance']) == 0) echo '0';
die();
} else if (isset($_GET['showaddress'])) {die('This call has been deprecated 5/27/2015');
$address = $db->query('SELECT * FROM addresses WHERE address = "'.$_GET['showaddress'].'"')->fetchArray();
$txs = $db->query('SELECT * FROM transactions WHERE (`to` = "'.$_GET['showaddress'].'" OR `from` = "'.$_GET['showaddress'].'") ORDER BY time DESC LIMIT 19');
$bal = $address['balance'];
echo '<float bgcolour="yellow"><p width="25" align="left">Address: '.$_GET['showaddress'].'</p><p width="25" align="right">Balance: '.$bal.' KST</p></float>';
echo '<float bgcolour="lightGray"><p width="18" align="right">Time</p><p width="11" align="right">Peer</p><p width="11" align="right">Value</p><p width="10" align="right">Balance</p></float>';
while ($tx = $txs->fetchArray()) {
if ($tx['to'] == $_GET['showaddress']) {$peer = $tx['from'];$sign = '+';$colr = 'green';} else {$peer = $tx['to'];$sign = '-';$colr = 'red';}
if (strlen($tx['from']) == 0) $peer = 'Mined KST';
$drawbal = $bal;
if ($drawbal >= 100000) {$drawbal = substr($drawbal,0,3).'K';}
if ($drawbal >= 1000000) {$drawbal = substr($drawbal,0,4).'K';}
$drawval = $tx['value'];
if ($drawval >= 100000) {$drawval = substr($drawval,0,3).'K';}
if ($drawval >= 1000000) {$drawval = substr($drawval,0,4).'K';}
echo '<float><p width="18" align="right">'.date("\'y-m-d H:i:s",$tx['time']).'</p><a href="/index.php?showaddress='.$peer.'" ulcolour="none" width="11" align="right">'.$peer.'</a><p width="11" colour="'.$colr.'" align="right">'.$sign.$drawval.' KST</p><p width="10" align="right">'.$drawbal.' KST</p></float>';
if ($sign == '+') {$bal -= $tx['value'];} else {$bal += $tx['value'];}}
} else if (isset($_GET['namebonus'])) {ob_end_clean();
//echo ($db->query('SELECT COUNT(*) AS count FROM blocks WHERE address = "a5dfb396d3" ORDER BY id ASC')->fetchArray()['count']);
echo $db->query('SELECT COUNT(*) AS count FROM names WHERE unpaid > 0')->fetchArray()['count'];
} else if (isset($_GET['getbaseblockvalue'])) {
ob_end_clean();
$blocks = $db->query('SELECT id FROM blocks ORDER BY id DESC LIMIT 1')->fetchArray()['id'];
$subsidy = 25; if ($blocks >= 100000) $subsidy = 10;
echo $subsidy;
die();
} else if (isset($_GET['getdomainaward'])) {
ob_end_clean();
$activenames = $db->query('SELECT COUNT(*) AS count FROM names WHERE unpaid > 0')->fetchArray()['count'];
echo $activenames;
die();
} else if (isset($_GET['getmoneysupply'])) {
ob_end_clean();
$blocks = $db->query('SELECT id FROM blocks ORDER BY id DESC LIMIT 1')->fetchArray()['id'];
echo 2500000 + 25 * ($blocks - 50000);
die();
} else if (isset($_GET['getblockvalue'])) {
ob_end_clean();
$id = $_GET['getblockvalue'] + 1;
$id = $id - 1;
echo $db->query('SELECT value FROM blocks WHERE id = '.$id.' LIMIT 1')->fetchArray()['value'];
die();
} else if (isset($_GET['submitblock'])) {
ob_end_clean();
$blocks = $db->query('SELECT id FROM blocks ORDER BY id DESC LIMIT 1')->fetchArray()['id'];
$subsidy = 25; if ($blocks >= 100000) $subsidy = 10;
$activenames = $db->query('SELECT COUNT(*) AS count FROM names WHERE unpaid > 0')->fetchArray()['count'];
$subsidy = $subsidy + $activenames;
$last = substr($db->query('SELECT hash FROM blocks ORDER BY id DESC LIMIT 1')->fetchArray()['hash'],0,12);
$difficulty = $db->query('SELECT val FROM config WHERE id = "1"')->fetchArray()['val'];
$hash = hash('sha256',$_GET['address'].$last.$_GET['nonce']);
if (strlen($_GET['address']) != 10) die('Invalid address');
if (strlen($_GET['nonce']) > 12) die('Nonce is too large');
if (substr($_GET['address'],0,1) == 'k') {if (!ctype_alnum($_GET['address'])) die('Invalid address');} else {if (!ctype_xdigit($_GET['address'])) die('Invalid address');}
if (hexdec(substr($hash,0,12)) <= $difficulty) {
$db->query('INSERT INTO blocks (hash, address, nonce, time, difficulty, value) VALUES ("'.$hash.'", "'.$_GET['address'].'", "'.$_GET['nonce'].'", "'.time().'", "'.$difficulty.'", "'.$subsidy.'")');
//$last_id = $db->sqlite_last_insert_rowid();
$db->query('INSERT INTO addresses (address, firstseen) VALUES ("'.$_GET['address'].'", "'.time().'")');
$db->query('INSERT INTO transactions (`to`, `from`, `value`, `time`) VALUES ("'.$_GET['address'].'", "'.'", "'.$subsidy.'", "'.time().'")');
$db->query('UPDATE addresses SET balance = balance + '.$subsidy.' WHERE address = "'.$_GET['address'].'"');
$db->query('UPDATE addresses SET totalin = totalin + '.$subsidy.' WHERE address = "'.$_GET['address'].'"');
$db->query('UPDATE names SET unpaid = unpaid - 1 WHERE unpaid > 0');
die('Block solved');
} else {
die($_GET['address'].$last.$_GET['nonce']);
}
} else if (isset($_GET['yomomma'])) {die('This call has been deprecated 5/27/2015');
ob_end_clean();
$joke = 'Fuck whoever made this yo momma shit! It is annoying as fuck!';
die('{"joke":"'.$joke.'"');
} else if (isset($_GET['lastblock'])) {
$hash = $db->query('SELECT hash FROM blocks ORDER BY id DESC LIMIT 1')->fetchArray()['hash'];
ob_end_clean();
die(substr($hash,0,12));
} else if (isset($_GET['getwork'])) {
$difficulty = $db->query('SELECT val FROM config WHERE id = "1"')->fetchArray()['val'];
ob_end_clean();
echo $difficulty;
die();
} else {die('[END]');
echo '<br><h>This site is being deprecated</h><!--<h>What is Krist?</h>
<p>Krist is a digital currency designed for use on Minecraft servers with modpacks running.'
.' It functions as currency, and can be used to buy and sell blocks, items, properties, or domain names in the Quest Network.'
.' Krist functions similarly to Bitcoin, but there is only one full node. This eliminates the need for transaction fees and confirmation time.'
.'</p><br><h>Where does Krist come from?</h><p>'
.'Krist has to be mined by ComputerCraft computers. Mining involves getting a bunch of computers and trying to solve a hard math problem.'
.' Whomever\'s computer can solve the problem will mine a "block" and receive 50 KST. Then the process continues until another block is solved.'
.' Blocks always produce 50 KST, for now. Eventually, they will only yeild 25 KST, then 12.5, then 6.25 and so on. This halving process takes a very long time.'
.' The difficulty of the math problem is automatically adjusted so that a solution is found once per minute when everyone is mining at once.'
.' Through mining, 21000000 KST will be produced. Blocks will still give KST until around 2030.'
.'</p><br><h>How can I mine Krist?</h><p>'
.' You need to install a mining program on your ComputerCraft computer or turtle.'
.'</p><a align="center" href="/kristminer">Download KristMiner</a>'
.'<br><h>How do I store my Krist?</h><p>'
.' Krist is stored in something called a Krist address. You can create Krist addresses on this website. Every Krist address has a secret key code used for spending the Krist.'
.' The first 10 characters of the SHA256 hash of your private key is your address.'
.' You will need your private key to make transfers from your address. Don\'t lose it, or your Krist will be gone forever!'
.'</p><br><h>WHY?</h><p>'
.' Because diamonds aren\'t always valuable! In modpack societies, barter economies are usually the best a server gets.'
.' With Krist, you can transfer value from one server to the next, or between players on the same server.'
.' Once exchange rates are established for your server, watch the economy stabilize and grow!'
.'</p><br><h>Other things to know</h><p>'
.' Krist is not currently divisible. This may be changed in the future if it becomes too valuable.'
.' The plural for Krist is Krist, and the symbol used is KST.'
.' There is no known exchange between KST and real money (like dollars or bitcoins). This may change if there is demand.'
.'</p>-->';
}?>
</body>
</html>
