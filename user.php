<?php
require_once 'lib/GitHubApi.php';
require_once 'lib/LikeService.php';
require_once 'lib/DBConn.php';

DBConn::init();

$api = new GitHubApi();
$like_service = new LikeService($api->getCurrentUsername());
$user = array();
if(isset($_GET['user'])){
	$username = trim(strip_tags($_GET['user']));
	$user = $api->getUserDetails($username);
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
		<a href="/">GitHub Project Browser</a> &gt; User
		<div style="float:right;">
			<form action='/search.php' method="GET">
				<input placeholder="Search" name="q" />
				<input type="submit" value="Start" style="width:50px;"/>
			</form>
		</div>
	</header><!-- #header-->

	<div id="content">

<?if($user && !(isset($user['message']) && $user['message'] == 'Not Found')){?>
		<div style="float:left; text-align:center;">
			<img width="150px;" src="<?=$user['avatar_url']?>" class="bor" /><br/>
			<button style="width:70px;" onclick="modifyLikes(this, '<?=LikeService::TYPE_USER?>', '<?=$user['login']?>');"><?if(LikeService::checkIfLiked($likes, LikeService::TYPE_USER, $user['login']))echo "UnLike";
		else echo "Like";?></button>
		</div>
		
		<div style="margin-left:180px;">
			<?if(isset($user['name'])){?>
				<h2><?=$user['name']?></h2>
				<p>Nick:<?=$user['login']?></p>
			<?}else{?>
				<h2><?=$user['login']?></h2>
			<?}
			if(isset($user['company'])){?>
				<p>Company: <?=$user['company']?></p>
			<?}
			if(isset($user['blog'])){?>
				<p>Blog: <a href="<?=$user['blog']?>" class="dot"><?=$user['blog']?></a></p>
			<?}?>
			<p>Followers:<?=$user['followers']?></p>
		</div>

<?}else{?>
	<h2>User not found</h2>
<?}?>

	</div><!-- #content-->

</div><!-- #wrapper -->

<footer id="footer">
</footer><!-- #footer -->

</body>
</html>