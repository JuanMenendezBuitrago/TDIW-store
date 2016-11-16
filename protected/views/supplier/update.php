    	
	    	<div class="container">
	    		<div class="row">
	    			<div class="col-4">
	    				<h1>Editar proveidor</h1>
	    				<p>Els camps marcats amb * son obligatoris</p>
	    			</div>
	    			<div class="col-8">
				   		<form action="/proveidor<?php echo $supplier->id?'/'.$supplier->id:''; ?>" method="<?php echo $method;?>">
			    			
			    			<!-- name: camp amb només caràcters i espais -->
			    			<div class="form-group">
			    				<label for="name"><?php echo ucfirst($supplier->attributesLabels()['name']);?></label>
			    				<input type="text" class="form-control" name="name" id="name" pattern="[\s\S]{1,50}" title="Camp amb només caràcters i espais. Entre 1 y 32 caracters." value="<?php echo $supplier->name;?>" required>
								<div class="error"></div>
			    			</div>

			    			<!-- phone: 9 dígits -->
			    			<div class="form-group">
			    				<label for="phone"><?php echo ucfirst($supplier->attributesLabels()['phone']);?></label>
			    				<input type="text" class="form-control" name="phone" id="phone" pattern="\d{9}" title="Camp amb 9 dígits." value="<?php echo $supplier->phone;?>" required>
			    				<div class="error"></div>
			    			</div>
			    			

			    			<!-- email: correu vàlid -->
			    			<div class="form-group">
			    				<label for="email"><?php echo ucfirst($supplier->attributesLabels()['email']);?></label>
			    				<input type="email" class="form-control" name="email" id="email" title="Camp amb una adreça de email vàlida." value="<?php echo $supplier->email;?>" required>
			    				<div class="error"></div>
			    			</div>


			    			<!-- address: fins a 30 caràcters alfanumérics -->
			    			<div class="form-group">
			    				<label for="address"><?php echo ucfirst($supplier->attributesLabels()['address']);?></label>
			    				<input type="text" class="form-control" name="address" id="address" maxlength="50" title="Camp alfanumèric amb 5p0 caracters màxim." value="<?php echo $supplier->address;?>" required>
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
