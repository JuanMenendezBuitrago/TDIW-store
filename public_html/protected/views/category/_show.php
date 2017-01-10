    		<h2><?php echo $category->name; ?></h2>
	    	<div class="body">
				<div class="row">
					<div class="col-3 label">id: </div><div class="col-9"><?php echo nl2br($category->id); ?></div>
				</div>
				<div class="row">
					<div class="col-3 label">name: </div><div class="col-9"><?php echo nl2br($category->name); ?></div>
				</div>
				<div class="row">
					<div class="col-3 label">alias: </div><div class="col-9"><?php echo nl2br($category->alias); ?></div>
				</div>
				<div class="row">
					<div class="col-3 label">descripció: </div><div class="col-9"><?php echo nl2br($category->description); ?></div>
				</div>
				<div class="row">
					<div class="col-3 label">estat: </div><div class="col-9"><?php echo nl2br($category->status)=='1'?'actiu':'bloquejat'; ?></div>
				</div>
	    	</div>
	    	<div class="buttons"><a href="" class="button" id="close">tanca</a></div>