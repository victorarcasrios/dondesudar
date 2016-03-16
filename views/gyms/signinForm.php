<?php
$header = $t["signinGym"];
$jsAdditionalScripts = array(
	"gyms/signinForm",
	"gyms/locationSelects",
	"gyms/featuresAccordion",
	"bootbox"
	);
/*
$jsExternalAdditionalScripts = array(
	"http://maps.googleapis.com/maps/api/js?key=AIzaSyDBq8zCPljgcMpEPF9hGhEk73YpTfSRpx4&sensor=false"
	);
*/

if( !isset( $actualName ) || !$actualName ) $actualName = "";

?>

<input type="hidden" name="cifPopoverAlertMessage" value="<?= $t['cifPopoverAlertMessage'] ?>">

<form class="form-horizontal" id="gymDataForm">
	<div class="form-group">
		<div class="col-lg-1"></div>
		<div class="col-lg-6">
			<input type="text" name="name" class="form-control" placeholder="<?= $t['name'] ?>" autofocus required>
		</div>
		<div class="col-lg-2"></div>
		<div class="col-lg-2">
			<input type="text" name="cif" placeholder="CIF" class="form-control" 
			pattern="^(X(-|\.)?0?\d{7}(-|\.)?[A-Z]|[A-Z](-|\.)?\d{7}(-|\.)?[0-9A-Z]|\d{8}(-|\.)?[A-Z])$" required>
		</div>
	</div>
	<div class="form-group">
		<div class="col-lg-1"></div>
		<div class="col-lg-6">
			<input type="text" name="address" class="form-control" placeholder="<?= $t['address'] ?>" required>
		</div>	
		<div class="col-lg-2"></div>
		<div class="col-lg-2">
			<input type="text" name="postcode" class="form-control" placeholder="<?= $t["postcode"] ?>" required
			pattern="^([1-9]{2}|[0-9][1-9]|[1-9][0-9])[0-9]{3}$">
		</div>
	</div>
	<div class="form-group">
		<div class="col-lg-6">
			<label for="comunidadAutonoma"><?= $t["comunidadAutonoma"] ?></label>
			<select name="comunidadAutonoma" class="form-control" required
			data-container="body" data-toggle="popover" data-placement="right" data-content="<?= $t['mustSelectCa']?>">
				<option class="vacio" value="false"></option>
				<?php
					foreach($comunidadesAutonomas as $ca){
						?>
						<option value="<?= $ca['id'] ?>"><?= $ca["nombre"] ?></option>
						<?php
					}
				?>
			</select>
		</div>
		<div class="col-lg-6">
			<label for="provincia"><?= $t["provincia"] ?></label>
			<select name="provincia" class="form-control" required disabled>
				<option value="false"><?= $t["selectCAFirst"] ?></option>		
			</select>
		</div>
	</div>
	<div class="form-group">
		<div class="col-lg-4">
			<input type="email" name="email" class="form-control" placeholder="Email" required>
		</div>
		<div class="col-lg-4">
			<input type="tel" name="telefono" class="form-control" placeholder="<?= $t['phoneNumber'] ?>" required
			pattern="[0-9]{9}">
		</div>
		<div class="col-lg-4">
			<input type="tel" name="otroTelefono" class="form-control" placeholder="<?= $t['otherPhoneNumber'] ?>"
			pattern="[0-9]{9}">
		</div>
	</div>
	<div class="form-group">
		<fieldset class="scheduler-border">
			<legend class="scheduler-border"><?= $t["features"] ?></legend>
			<div class="control-group">
				<div class="panel-group" id="accordion">

				<?php

					foreach ($caracteristicas as $c) {
				?>
					  <div class="col-lg-12">
						  <div class="panel panel-default">
						    <div class="panel-heading">
						      <h4 class="panel-title">
						        <a data-toggle="collapse" data-parent="#accordion" id="ff_<?= $c['id'] ?>" href="#">
									<?= $c['nombre'] ?>
						        </a>
						      </h4>
						    </div>
						    <div id="collapse<?= $c['id'] ?>" class="panel-collapse collapse in">
						      <div class="panel-body">
						      </div>
						    </div>
						  </div>
					  </div>

				<?php
					 } 
				?>

				</div>
			</div>
		</fieldset>
	</div>

	<div class="form-group">
		<div class="col-lg-5"></div>
		<div class="col-lg-2">
			<input type="submit" value="<?= $t["signin"] ?>" class="btn btn-primary col-lg-12">
		</div>
		<div class="col-lg-5"></div>
	</div>
</form>

<div class="modal fade" id="signerDataFormModal">
  <div class="modal-dialog">
    <div class="modal-content">
	    <form id="signerDataForm">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	        <h4 class="modal-title"><?= $t["contactData"] ?></h4>
	      </div>
	      <div class="modal-body form-horizontal">
	        <div class="form-group">
	        	<div class="col-lg-6">
	        		<label for="contactName"><?= $t['whatIsYourNameNowYouMustGiveUsIt'] ?></label>        		
	        		<input type="text" name="contactName" class="form-control" value='<?= $actualName ?>' required>
	        	</div>
	        	<div class="col-lg-6">
	        		<label for="occupation"><?= $t['whatIsYourOccupationInTheGym'] ?></label>
	        		<input type="text" name="occupation" class="form-control" value="" required>
	        	</div>
	        </div>	      	
	      </div>
	      <div class="modal-footer">
	        <button type="submit" class="btn btn-success"><?= $t["signin"] ?></button>
	        <button type="button" class="btn btn-danger" data-dismiss="modal"><?= $t["cancel"] ?></button>
	      </div>
		</form>
    </div>
  </div>
</div>

<form action="?r=gyms/profile" id="continue"></form>

<input type="hidden" name="userId" value="<?= $_SESSION['user']->id ?>">
<input type="hidden" name="gymSigned" value="<?= $t["gymSigned"] ?>">
<input type="hidden" name="weNeedYourName" value="<?= $t['weNeedYourName'] ?>"