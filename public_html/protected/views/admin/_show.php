<h2><?php echo $admin->username; ?></h2>
<div class="body">
	<div class="row">
		<div class="col-3 label">id: </div><div class="col-9"><?php echo nl2br($admin->id); ?></div>
	</div>
	<div class="row">
		<div class="col-3 label">usuari: </div><div class="col-9"><?php echo nl2br($admin->username); ?></div>
	</div>
	<div class="row">
		<div class="col-3 label">email: </div><div class="col-9"><?php echo nl2br($admin->email); ?></div>
	</div>
	<div class="row">
		<div class="col-3 label">estat: </div><div class="col-9"><?php echo nl2br($admin->status)=='1'?'actiu':'bloquejat'; ?></div>
	</div>
</div>
<div class="buttons"><a href="" class="button" id="close">tanca</a></div>