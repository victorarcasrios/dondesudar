<?php
$cssAdditionalStyles = array(
	"font-awesome"
);
$jsAdditionalScripts = array(
    "gyms/gymDetail"
);

define("MAX_STARS", 10);
define("MIN_SCORE", 1);
define("MAX_SCORE", 10);

$userStatus = $_SESSION["user"]->status;
$name = $gym["name"];
$header = $name;
?>

<div class="col-lg-6">
	<div class="col-lg-12 sliderContainer">
		<?= $images ?>
	</div>
	<div class="col-lg-12">
		<br>
	</div>
	<div class="col-lg-12">
	    <?php
	    if ($userStatus === "user" || $userStatus === "admin") {
	        ?>
	        <div class="col-lg-12">
	            <div class="col-lg-12 form-horizontal">
	                <div class="form-group">
	                    <textarea name="comment" rows="3" class="form-control" placeholder="<?= $t["commentHere"] ?>"></textarea>
	                </div>
	                <div class="form-group oculto">
	                    <button name="addComment" class="btn btn-primary pull-right"><span class="glyphicon glyphicon-ok"></span></button>			
	                </div>		
	            </div>
	        </div>
	        <?php
	    }
	    ?>

	    <div class="col-lg-12" id="comments">
	        <?php
	        if (isset($comments)) {
	            foreach ($comments as $key => $c) {
	                ?>
	                <div class="panel panel-default">
	                    <div class="panel-heading">
	                        <span><b><?= $c["nick"] ?></b>   </span>
                                <span><img src="<?=$c["avatar"]?>" class="img-circle avatarOnComment"></span>
	                        <span class='text-right'><?= $c["post_date"] ?></span>
	                    </div>
	                    <div class="panel-body">
	                        <?= $c["text"] ?>
	                    </div>
	                </div>
	                <?php
	            }
	        }
	        ?>
	    </div>
	</div>
</div>

<!-- RIGHT 1/2 -->
<div class="col-lg-6">
	<div class="col-lg-2"></div>
	<div class="col-lg-8">
		<div class="col-lg-1"></div>
		<div class="col-lg-10">
			<?php
			// PUNTUACION MEDIA DEL GIMNASIO
				if(! $gymScore )
					$gymScore = $t["notYetVoted"];
				else{
					$filledStars = floor($gymScore);
					$hasDecimal = strpos($gymScore, ".");
					$emptyStars = MAX_STARS - $filledStars;	
					if( $hasDecimal )
						$emptyStars--;
				}
			?>
			<h3 class='text-center'> <?= $t["score"] ?></h3>
			<input type='text' name='gymScore' class='form-control text-center' value="<?= $gymScore ?>" disabled>
			<div id="starsContainer" class='text-center center-block'>
			<?php
			if( isset($filledStars)){
				for( $i = 0; $i < $filledStars; $i++)
					echo '<i class="fa fa-star fa-lg"></i>';
				if( $hasDecimal )
					echo '<i class="fa fa-star-half-o fa-lg"></i>';
				for( $i = 0; $i < $emptyStars; $i++ )
					echo '<i class="fa fa-star-o fa-lg"></i>';
			}
			?>
			</div>
			<br>			
			<div class="col-lg-3"></div>
			<div class="col-lg-6">
			<?php
			// PUNTUACION DEL GIMNASIO DADA POR EL USUARIO ACTUAL
				if( $userStatus === "user" ){
					?>
				<select name='userVote' class='form-control'>
					<?php
						if(! $userVote )
							echo "<option value='false' class='vacio'>" .$t["voteIt"]. "</option>";
						for( $i = MIN_SCORE; $i <= MAX_SCORE; $i++){
							$s = ( $userVote && $userVote == $i ) ? "selected" : "";
							echo "<option value='$i' $s>$i</option>";
						}
					?>
				</select>
					<?php
				}
			?>
			</div>
		</div>
		<div class="col-lg-12">
			<br>

			<p><b><?= $t["name"] ?>: </b><?= $name ?></p>
		    <p><b><?= $t["address"] ?>: </b><?= $gym["domicilio"]; ?></p>
		    <p>
		        <span class="label label-warning"><?= $gym["provincia"] ?></span>
		        <span class="label label-warning"><?= $gym["ca"] ?></span>
		    </p>
		    <p><b>Email: </b><?= $gym["email"] ?></p>
		    <p><b><?= $t["phoneNumber"] ?>: </b><?= $gym["telefono"] ?></p>
		    <?php
		    if (isset($gym["otro_telefono"]))
		        echo "<p><b>" . $t["otherPhoneNumber"] . ": </b>" . $gym["otro_telefono"] . "</p>";
		    if (isset($features)) {
		        echo "<p>";
		        foreach ($features as $f) {
		            echo "<span class='label label-success'>" . $f["nombre"] . "</span> ";
		        }
		        echo "</p>";
		    }
		    ?>	
		</div>
	</div>
		    
</div>


<input type="hidden" name="id" value="<?= $gym["id"] ?>">
<input type="hidden" name="userId" value="<?= $_SESSION["user"]->id ?>">