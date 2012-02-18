<?php
include ("includes/config.php");
$query = mysql_query("SELECT * FROM df_settings LIMIT 1");
$fetch = mysql_fetch_assoc($query);
$sitename = $fetch['DFSitename'];
$facebook = $fetch["FaceBookUsername"];
$myspace = $fetch["MySpaceUsername"];
?>
<div class="nav">
<?php
	if(isset($_SESSION['name'])){
            $name = $_SESSION['name'];
            print "<div class=\"menuHeader\">" . $sitename . "</div>";
            echo "<a href=\"../\">Home</a><br />";
            echo "<a href=\"game/\">Play</a><br />";
            echo "<a href=\"http://cris-is.stylin-on.me/viewtopic.php?f=147&t=9556\">Design Notes</a><br />";
            echo "<a href=\"topchars.php\">Top 100</a><br />";

            print "<div class=\"menuHeader\">Account</div>";
            echo "<a href=\"../ucp.php\">Account Manager</a><br />";
            echo "<a href=\"/df-lostpassword.php\">Lost Password</a><br />";
            echo "<a href=\"#Put Paypal Link Here\">Buy Dragon Amulet</a><br />";
            echo "<a href=\"#Put Paypal Link Here\">Buy Dragon Coins</a><br />";
            echo "<a href=\"/logout.php\">Logout [ " . $name . " ]</a><br />";

            print "<div class=\"menuHeader\">Support</div>";
            echo "<a href=\"#Put Forum Link Here or Something\">Help</a><br />";
            echo "<a href=\"#Put Forum Link Here or Something\" target=\"_blank\">Forums</a><br />";

            print "<div class=\"menuHeader\">AE Games</div>";
            echo "<a href=\"http://www.battleon.com\" target=\"_blank\">AdventureQuest</a><br />";
            echo "<a href=\"http://www.archknight.com\" target=\"_blank\">ArchKnight</a><br />";
            echo "<a href=\"http://www.dragonfable.com\" target=\"_blank\">DragonFable</a><br />";
            echo "<a href=\"http://www.mechquest.com\" target=\"_blank\">MechQuest</a><br />";
            echo "<a href=\"http://www.aq.com\" target=\"_blank\">AQWorlds</a><br />";
            echo "<a href=\"http://www.warpforce.com\" target=\"_blank\">WarpForce</a><br />";
            echo "<a href=\"http://www.herosmash.com\" target=\"_blank\">HeroSmash</a><br />";
            echo "<a href=\"http://www.ebilgames.com\" target=\"_blank\">EbilGames</a><br />";

            print "<div class=\"menuHeader\">Community</div>";
            echo "<a href=\"" . $facebook . "\" target=\"_blank\">Facebook</a><br />";
            echo "<a href=\"" . $myspace . "\" target=\"_blank\">MySpace</a>";
		if(isset($_SESSION['admin'])) {
		print "<div class=\"menuHeader\">Admin Panel</div>";
			echo "<a href='../admin/'>Admin Panel Home</a>";
			echo "<br /><a href='../admin/writenews.php'>Write news</a>";
			echo "<br /><a href='../admin/deletenews.php'>Delete news</a>";
			echo "<br /><a href='../admin/writemore-news.php'>Write More-News</a>";
			echo "<br /><a href='../admin/deletemore-news.php'>Delete More-News</a>";
		}
	} else {
            print "<div class=\"menuHeader\">" . $sitename . "</div>";
            echo "<a href=\"../\">Home</a><br />";
            echo "<a href=\"df-signup.php\">Signup</a><br />";
            echo "<a href=\"game/\">Play</a><br />";
            echo "<a href=\"http://cris-is.stylin-on.me/viewtopic.php?f=147&t=9556\">Design Notes</a><br />";
            echo "<a href=\"topchars.php\">Top 100</a><br />";

            print "<div class=\"menuHeader\">Account</div>";
            echo "<a href=\"df-signup.php\">New Account</a><br />";
            echo "<a href=\"/login.php\">Login</a><br />";

            print "<div class=\"menuHeader\">Support</div>";
            echo "<a href=\"#Put Forum Link Here or Something\">Help</a><br />";
            echo "<a href=\"#Put Forum Link Here or Something\" target=\"_blank\">Forums</a><br />";

            print "<div class=\"menuHeader\">AE Games</div>";
            echo "<a href=\"http://www.battleon.com\" target=\"_blank\">AdventureQuest</a><br />";
            echo "<a href=\"http://www.archknight.com\" target=\"_blank\">ArchKnight</a><br />";
            echo "<a href=\"http://www.dragonfable.com\" target=\"_blank\">DragonFable</a><br />";
            echo "<a href=\"http://www.mechquest.com\" target=\"_blank\">MechQuest</a><br />";
            echo "<a href=\"http://www.aq.com\" target=\"_blank\">AQWorlds</a><br />";
            echo "<a href=\"http://www.warpforce.com\" target=\"_blank\">WarpForce</a><br />";
            echo "<a href=\"http://www.herosmash.com\" target=\"_blank\">HeroSmash</a><br />";
            echo "<a href=\"http://www.ebilgames.com\" target=\"_blank\">EbilGames</a><br />";

            print "<div class=\"menuHeader\">Community</div>";
            echo "<a href=\"" . $facebook . "\" target=\"_blank\">Facebook</a><br />";
            echo "<a href=\"" . $myspace . "\" target=\"_blank\">MySpace</a>";
	}
?>
</div>