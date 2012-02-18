<?php
include ("includes/config.php");
session_start();
	if(isset($_SESSION['user'])){
		header('location: index.php');
	} else {
		include ("includes/mysql_connector.php");
	}
?>
<?php
			if(isset($_GET['id'])) {
				$id = mysql_real_escape_string(stripslashes($_GET['id']));
				$result = mysql_query("SELECT * FROM df_news WHERE id = '".$id."'");
				$text = mysql_result($result,$i,"text");
				$title = mysql_result($result,$i,"title");
				$avatar = mysql_result($result,$i,"avatar");
				$caption = mysql_result($result,$i,"caption");
				print ("<tr><td align=\"center\" valign=\"top\">");
				if(isset($_SESSION['admin'])){
						$edit = "<a href=\"../admin/editnews.php?id=".$id."\">Edit This Post</a><a> - </a>";
						$delete = "<a href=\"../admin/deletenews.php?id=".$id."\">Delete This Post</a>";
					}
				print ("<img src=\"images/avatars/" . $avatar . "\" /></td><td align=\"left\" valign=\"top\"><strong class=\"style6\">" . $title . "</strong></a><p><strong class=\"style10\">" . $caption . "</strong><br />" . $text . "</p><br />".$edit."".$delete."</td></tr>");
			} else {
				$query = "SELECT * FROM df_news ORDER BY id DESC LIMIT 10";
				$result = mysql_query($query);
				$num = mysql_numrows($result);
				mysql_close();
				$i = 0;
				while ($i < $num) {
					$id = mysql_result($result,$i,"id");
					$text = mysql_result($result,$i,"text");
					$title = mysql_result($result,$i,"title");
					$avatar = mysql_result($result,$i,"avatar");
					$caption = mysql_result($result,$i,"caption");
				print ("<tr><td align=\"center\" valign=\"top\">");
				if(isset($_SESSION['admin'])){
						$edit = "<a href=\"../admin/editnews.php?id=".$id."\">Edit This Post</a><a> - </a>";
						$delete = "<a href=\"../admin/deletenews.php?id=".$id."\">Delete This Post</a>";
					}
				print ("<img src=\"images/avatars/" . $avatar . "\" /></td><td align=\"left\" valign=\"top\"><strong class=\"style6\">" . $title . "</strong></a><p><strong class=\"style10\">" . $caption . "</strong><br />" . $text . "</p><br />".$edit."".$delete."</td></tr>");
					$i++;
				} 
			}
?>