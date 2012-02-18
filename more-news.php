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
				$result = mysql_query("SELECT * FROM df_morenews WHERE id = '".$id."'");
				$text = mysql_result($result,$i,"text");
				$title = mysql_result($result,$i,"title");
				if(isset($_SESSION['admin'])){
						$edit = "<a href=\"../admin/editmore-news.php?id=".$id."\">Edit this Post</a><a> - </a>";
						$delete = "<a href=\"../admin/deletemore-news.php?id=".$id."\">Delete This Post";
					}
				print ("<br><strong><strong><span class=\"subheader\">".$title."</span></strong></strong><br />".$text."</p><br />".$edit."".$delete."");
			} else {
				$query = "SELECT * FROM df_morenews ORDER BY id DESC LIMIT 10";
				$result = mysql_query($query);
				$num = mysql_numrows($result);
				mysql_close();
				$i = 0;
				while ($i < $num) {
					$id = mysql_result($result,$i,"id");
					$text = mysql_result($result,$i,"text");
					$title = mysql_result($result,$i,"title");
				if(isset($_SESSION['admin'])){
						$edit = "<a href=\"../admin/editmore-news.php?id=".$id."\">Edit this Post</a><a> - ";
						$delete = "<a href=\"../admin/deletemore-news.php?id=".$id."\">Delete This Post";
					}
				print ("<br><strong><strong><span class=\"subheader\">".$title."</span></strong></strong><br />".$text."</p><br />".$edit."".$delete."");
					$i++;
				} 
			}
?>