<?php
require_once 'lib/GitHubApi.php';
require_once 'lib/LikeService.php';
require_once 'lib/DBConn.php';

DBConn::init();

$number_of_contr_to_show = 7;
$api = new GitHubApi();
$like_service = new LikeService($api->getCurrentUsername());
$repo = array();
if(isset($_GET['owner']) && isset($_GET['repo'])){
	$owner = trim(strip_tags($_GET['owner']));
	$repo_name = trim(strip_tags($_GET['repo']));
	$repo = $api->getRepoDetails($owner, $repo_name);
}else{
	$repo = $api->getRepoDetails();
}
$likes = $like_service->getAllUserLikes(LikeService::TYPE_USER);
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta charset="utf-8" />
	<!--[if IE]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
	<title>GitHub Project Browser</title>
	<meta name="keywords" content="GitHub Project Browser" />
	<meta name="description" content="GitHub Project Browser" />
	<link rel="stylesheet" href="style.css" type="text/css" media="screen, projection" />
	<!--[if lte IE 6]><link rel="stylesheet" href="style_ie.css" type="text/css" media="screen, projection" /><![endif]-->
	<script type='text/javascript' src='js/jquery.js'></script>
	<script type='text/javascript' src='js/functions.js'></script>
</head>

<body>

<div id="wrapper">

	<header id="header">
		<a href="/">GitHub Project Browser</a> &gt; Main
		<div style="float:right;">
			<form action='/search.php' method="GET">
				<input placeholder="Search" name="q" />
				<input type="submit" value="Start" style="width:50px;"/>
			</form>
		</div>
	</header><!-- #header-->

	<div id="content">

<?if(!(isset($repo['message']) && $repo['message'] == 'Not Found')){?>
		<div style="float:left; width:50%;">
			<h2><a href="/user.php?user=<?=$repo['owner']['login']?>"><?=$repo['owner']['login']?></a> / <?=$repo['name']?></h2>
			<?if($repo['description']){?>
				<p>Description: <?=$repo['description']?></p>
			<?}?>
				<p>Watchers: <?=$repo['watchers']?></p>
				<p>Forks: <?=$repo['forks']?></p>
				<p>Open issues: <?=$repo['open_issues']?></p>
			<?if($repo['homepage']){?>
				<p>Homepage: <a href="<?=$repo['homepage']?>" class="dot"><?=$repo['homepage']?></a></p>
			<?}?>
				<p>On GitHub: <a href="<?=$repo['html_url']?>" class="dot"><?=$repo['html_url']?></a></p>
				<p>Created at: <?=$repo['created_at']?></p>
			
		</div>
		
		<div style="margin-left:50%">
			<h2>Contributors:</h2>
			<table>
			<?
				$num_contr = count($repo['contributors']);
				if($num_contr > $number_of_contr_to_show) $num_contr = $number_of_contr_to_show;
				for($i = 0; $i < $num_contr; $i++){
					$c = $repo['contributors'][$i];
			?>
				<tr>
					<td><a href="/user.php?user=<?=$c['login']?>"><?=$c['login']?></a></td>
					
					<td><button style="width:70px; margin-left:20px;" onclick="modifyLikes(this, '<?=LikeService::TYPE_USER?>', '<?=$c['login']?>');"><?if(LikeService::checkIfLiked($likes, LikeService::TYPE_USER, $c['login']))echo "UnLike";
					else echo "Like";?></button></td>
				</tr>
			<?
				}
			?>
			</table>
		</div>

<?}else{?>
	<h2>Repo not found</h2>
<?}?>

	</div><!-- #content-->

</div><!-- #wrapper -->

<footer id="footer">
</footer><!-- #footer -->

</body>
</html>