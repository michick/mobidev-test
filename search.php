<?php
require_once 'lib/GitHubApi.php';
require_once 'lib/DBConn.php';
require_once 'lib/LikeService.php';

DBConn::init();

$api = new GitHubApi();
$like_service = new LikeService($api->getCurrentUsername());
$repos = array();

if(isset($_GET['q'])){
	$query = trim(strip_tags($_GET['q']));
	$repos = $api->searchRepos($query);
}

$likes = $like_service->getAllUserLikes(LikeService::TYPE_REPO);

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
		<a href="/">GitHub Project Browser</a> &gt; Search Repos
		<div style="float:right;">
			<form action='/search.php' method="GET">
				<input placeholder="Search" name="q" <?if(isset($query) && $query) echo 'value="' . $query . '"';?>/>
				<input type="submit" value="Start" style="width:50px;"/>
			</form>
		</div>
	</header><!-- #header-->

	<div id="content">


<?if(!(isset($query) && $query)){?>
	<h2>Please enter query</h2>
<?}elseif(isset($repos) && $repos){?>
	<h2>Search results for "<?=$query?>"</h2>
	<div id="search_results">

<?
$i = 1;
$number_of_repos = count($repos);
foreach($repos as $repo){
$i++;
?>
<div  class="s_out <?if($i % 2) echo 'odd';?>">
	<div class="search_out"><h3><a href="/index.php?owner=<?=$repo['username']?>&repo=<?=$repo['name']?>"><?=$repo['name']?></a></h3></div>
	<div class="search_out">
	<?if($repo['homepage']){?>
		<a href="<?=$repo['homepage']?>" class="dot"><?=$repo['homepage']?></a>
	<?}else echo "&nbsp;"?>
	</div>
	<div class="search_out">
		<a href="/user.php?user=<?=$repo['owner']?>"><?=$repo['owner']?></a>
	</div>
	
	<div class="sep_small"></div>
	
	<?if($repo['description']) echo "<p>" . $repo['description'] . "</p>"; ?>
	
	<div class="search_out">
		Watchers: <?=$repo['watchers']?>
	</div>
	
	<div class="search_out">
		Forks: <?=$repo['forks']?>
	</div>
	
	<div class="search_out" style="text-align:right;">
		<button style="width:70px;" onclick="modifyLikes(this, '<?=LikeService::TYPE_REPO?>', '<?=$repo['owner']?>', '<?=$repo['name']?>');"><?if(LikeService::checkIfLiked($likes, "repo", $repo['owner'], $repo['name']))echo "UnLike";
		else echo "Like";?></button>
	</div>
	
	<div class="sep_small"></div>
</div>
<?}?>

	</div>
	
	<?if($number_of_repos == GitHubApi::NUMBER_GITHUB_SEARCH_OUTPUTS){?>
		<button style="width:130px;" onclick="getMoreResults(this, '<?=$query?>', '2');">Get more results</button>
	<?}?>

<?}else{?>
	<h2>Nothing found on "<?=$query?>"</h2>
<?}?>		
		
	</div><!-- #content-->

</div><!-- #wrapper -->

<footer id="footer">
</footer><!-- #footer -->

</body>
</html>