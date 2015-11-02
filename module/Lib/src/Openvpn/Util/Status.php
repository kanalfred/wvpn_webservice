
// method one
cat /etc/openvpn/openvpn-status.log

//http://yumax1012.blogspot.ca/2007/08/openvpn-web-status-use-php.html
步驟一：將這一行加入 /etc/openvpn/server.conf

management localhost 7505

步驟二：新增 vpnstat.php，並置於網站可存取目錄內，內容如下：

=============================================
<?php

// OpenVPN (php-based) web status script
//
// This script has been released to the public domain by Pablo Hoffman
// on February 28, 2007.
//
// Original location:
// http://pablohoffman.com/software/vpnstatus/vpnstatus.txt

// Configuration values --------
$vpn_name = "My VPN";
$vpn_host = "localhost";
$vpn_port = 7505;
// -----------------------------

$fp = fsockopen($vpn_host, $vpn_port, $errno, $errstr, 30);

if (!$fp) {
echo "$errstr ($errno)<br />";
exit;
}

fwrite($fp, "status");
sleep(1);
fwrite($fp, "quit");
sleep(1);

$clients = array();

$inclients = $inrouting = false;

while (!feof($fp)) {

$line = fgets($fp, 128);

if (substr($line, 0, 13) == "ROUTING TABLE") {
$inclients = false;
}

if ($inclients) {
$cdata = split(',', $line);
$clines[$cdata[1]] = array($cdata[2], $cdata[3], $cdata[4]);
}

if (substr($line, 0, 11) == "Common Name") {$inclients = true;}

if (substr($line, 0, 12) == "GLOBAL STATS") {$inrouting = false;}

if ($inrouting) {
$routedata = split(',', $line);
array_push($clients, array_merge($routedata, $clines[$routedata[2]]));
}

if (substr($line, 0, 15) == "Virtual Address") {$inrouting = true;}

}

$headers = array('VPN Address', 'Name', 'Real Address', 'Last Act', 'Recv', 'Sent', 'Connected Since');

$tdalign = array('left', 'left', 'left', 'left', 'right', 'right', 'left');

/* DEBUGprint "<pre>";
print_r($headers);
print_r($clients);
print_r($clines);
print_r($routedata);
print "</pre>";
*/

fclose($fp);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN""http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $vpn_name ?> status</title>
<meta equiv="'refresh'" content="'300'">
<style type="text/css">

body {
font-family: Verdana, Arial, Helvetica, sans-serif;
font-size: 14px;
background-color: #E5EAF0;
}

h1 {
color: green;
font-size: 24px;
text-align: center;
padding-bottom: 0;margin-bottom: 0;
}

p.info {
text-align: center;
font-size: 12px;
}

.status0 {
background: #ebb;
}

.status1 {
background: lime;
}

table {
#border: medium solid maroon;
margin: 0 auto;border-collapse: collapse;
}

th {
background: maroon;
color: white;
}

tr {
border-bottom: 1px solid silver;
}

td {
padding: 0px 10px 0px 10px;
}

</style>
</head>
<body>
<table>
<tr>
<?php
foreach ($headers as $th) {
?>
<th><?php echo $th?></th>
<?php
}
?>
</tr>
<?php
foreach ($clients as $client) {
$client[3] = date ('Y-m-d H:i', strtotime($client[3]));
$client[6] = date ('Y-m-d H:i', strtotime($client[6]));
$client[4] = number_format($client[4], 0, '', '.');
$client[5] = number_format($client[5], 0, '', '.');
$client[2] = preg_replace('/(.*):.*/', '$1', $client[2]);
$i = 0;
?>
<tr>
<?php
foreach ($client as $td) {
?>
<td align="'<?php">'><?php echo $td?></td>
<?php
}
?>
</tr>
<?php
}
?>
</table>
<p class="'info'">This page gets reloaded every 5 min.<br />Last update:
<b><?php echo date ("Y-m-d H:i:s") ?></b>
</p>
</body>
</html>
