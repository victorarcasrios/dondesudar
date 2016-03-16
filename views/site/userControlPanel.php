<?php
$header = $t["controlPanel"];
?>

<div class="row">

<?php
if( $gyms ){
if( $numberOfGyms = count( $gyms ) ){			

	foreach( $gyms as $g ){
		$gymStatus = $g["status"];
		$gymClass = ( $gymStatus ) ? "enabledGym" : "disabledGym";
		?>
		<div class="col-lg-6">
			<div class="thumbnail <?= $gymClass ?>">
		      <img data-src="#" alt="">
		      <div class="caption">
		        <h3><?= $g["name"] ?>
		        <div class="pull-right">
		        	<small>
		        		<i><?php if( !$gymStatus ) echo $t["disabled"]; ?></i>
		        	</small>		        	
		        </div>
		        </h3>
		        <p><?= $g["domicilio"] ?> 
		        	<span class="label label-warning "><?= $g["provincia"] ?></span>
			        <a href="?r=gyms/editGym&gym=<?= $g["id"] ?>" class="btn btn-default pull-right" role="button"
			        data-toggle="tooltip" data-placement="bottom" title="<?= $t["edit"] ?>">
			        <span class="glyphicon glyphicon-edit"></span>	
			        </a>
			        <a href="?r=gyms/viewGym&gym=<?= $g["id"] ?>" class="btn btn-primary pull-right" role="button"
			        data-toggle="tooltip" data-placement="bottom" title="<?= $t["howItLooks"] ?>">
			        <span class="glyphicon glyphicon-eye-open"></span>
			        </a>
		        </p>
		      </div>
		    </div>
		</div>
		<?php
			}
		}
	?>
	</div>
	<div class='row'>
		<div class="jumbotron">
			<h3><b><?= $t["youCanSignAnotherGymIn"] ?></b></h3>
			<p><?= $t["signinAnotherGymMessage"] ?></p>
			<p><a href="?r=gyms/signNewGymIn" class="btn btn-primary btn-lg" role="button"><?= $t["signinAnotherGym"] ?></a></p>
		</div>
	</div>
	<?php
} else{
	echo "<div class='row'><div class='col-lg-12'><span class='alert alert-info col-lg-12'>" 
	. $t["youDontHaveAnyGym"] . "</span></div></div>";
	require "views/site/doYouHaveAGym.php";
}
?>