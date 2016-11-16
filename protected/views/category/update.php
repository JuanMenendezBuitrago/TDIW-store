    	
	    	<div class="container">
	    		<div class="row">
	    			<div class="col-4">
	    				<h1>Editar categoria</h1>
	    				<p>Els camps marcats amb * son obligatoris</p>
	    			</div>
	    			<div class="col-8">
				   		<form action="/categoria" method="<?php echo $method;?>">
			    			
			    			<!-- name: camp amb només caràcters i espais -->
			    			<div class="form-group">
			    				<label for="name"><?php echo ucfirst($category->attributesLabels()['name']);?></label>
			    				<input type="text" class="form-control" name="name" id="name" pattern="((?![-_+.,!@#$%^&*();\\/|<>'\u0022])\D|\s+|\d+){1,32}" title="Camp amb només caràcters i espais. Entre 1 y 32 caracters." value="<?php echo $category->name;?>" required>
								<div class="error"></div>
			    			</div>

			    			<!-- alias: camp amb només caràcters en minúscules, nombres i guións -->
			    			<div class="form-group">
			    				<label for="alias"><?php echo ucfirst($category->attributesLabels()['alias']);?></label>
			    				<input type="text" class="form-control" name="alias" id="alias" pattern="[a-z0-9-]{1,32}" title="Camp amb només nombres, lleteres minúscules sense accentuar i guions. Entre 1 y 32 caracters." value="<?php echo $category->alias;?>" required>
								<div class="error"></div>
			    			</div>
			    			
			    			<!-- description: 128 chracaters max -->
			    			<div class="form-group">
			    				<label for="description"><?php echo ucfirst($category->attributesLabels()['description']);?></label>
			    				<input type="text" class="form-control" name="description" id="description" maxlength="128" title="Aquí cabe de todo entre 1 y 128 caracters." value="<?php echo $category->description;?>" required>
								<div class="error"></div>
			    			</div>	
			    					    			
			    			<?php if(isset($_SESSION['user']) && $_SESSION['user']->isAdmin): ?>
			    			<div class="form-group">
			    				<label for="">Estat</label>
			    				<label for="status-enabled"><input type="radio" class="form-control" name="status" id="status-enabled" value="1" <?php echo (isset($category->status) && $user->status == '1')?'checked':'';?>> Actiu</label>
			    				<label for="status-disabled"><input type="radio" class="form-control" name="status" id="status-disabled" value="0" <?php echo (isset($category->status) && $user->status == '1')?'checked':'';?>> Cancel·lat</label>
			    			</div>
			    			<?php endif; ?>
			    			<div class="form-group"><button type="submit" class="button">desa</button></div>
			    		</form>
	    			</div>
	    		</div>
	 
	    	</div>
