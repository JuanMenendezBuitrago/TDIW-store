
<h2><?php echo $user->name; ?></h2>
<div class="body">
	<div class="row">
		<div class="col-3 label">id: </div><div class="col-9"><?php echo htmlspecialchars($user->id); ?></div>
	</div>
	<div class="row">
		<div class="col-3 label">usuari: </div><div class="col-9"><?php echo htmlspecialchars($user->username); ?></div>
	</div>
	<div class="row">
		<div class="col-3 label">nom: </div><div class="col-9"><?php echo htmlspecialchars($user->name); ?></div>
	</div>
	<div class="row">
		<div class="col-3 label">email: </div><div class="col-9"><?php echo htmlspecialchars($user->email); ?></div>
	</div>
	<div class="row">
		<div class="col-3 label">telèfon: </div><div class="col-9"><?php echo htmlspecialchars($user->phone); ?></div>
	</div>
	<div class="row">
		<div class="col-3 label">adreça: </div><div class="col-9"><?php echo htmlspecialchars($user->address); ?></div>
	</div>
	<div class="row">
		<div class="col-3 label">ciutat: </div><div class="col-9"><?php echo htmlspecialchars($user->city); ?></div>
	</div>
	<div class="row">
		<div class="col-3 label">codi postal: </div><div class="col-9"><?php echo htmlspecialchars($user->zip); ?></div>
	</div>
	<div class="row">
		<div class="col-3 label">targeta: </div><div class="col-9"><?php echo htmlspecialchars($user->card); ?></div>
	</div>
	<div class="row">
		<div class="col-3 label">estat: </div><div class="col-9"><?php echo htmlspecialchars($user->status)=='1'?'actiu':'bloquejat'; ?></div>
	</div>
</div>
<div class="buttons"><a href="" class="button" id="close">tanca</a></div>
