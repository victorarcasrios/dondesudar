<?php
$header = $t["home"];
?>

<div class="row">
	<div class="jumbotron">
		<h2><b><?= $t["welcomeMessage"] ?></b></h2>
		<p><?= $t["aboutDondesudar"] ?></p>
	</div>
</div>
<div class="row">
	<div class="col-lg-6">
		<div class="jumbotron">
			<h3><b><?= $t["signinPeople"] ?></b></h3>
			<p><?= $t["signinPeopleMessage"] ?></p>
			<p><a href="?r=site/signinForm" class="btn btn-primary btn-lg" role="button"><?= $t["signin"] ?></a></p>
		</div>
	</div>
	<div class="col-lg-6">
		<?php require "views/site/doYouHaveAGym.php"; ?>
	</div>
</div>