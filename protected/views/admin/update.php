    	
	    	<div class="container">
	    		<div class="row">
	    			<div class="col-4">
	    				<h1>Registra un administrador</h1>
	    				<p>Els camps marcats amb * son obligatoris</p>
	    			</div>
	    			<div class="col-8">
				   		<form action="/admin" method="<?php echo $method;?>">


			    			<!-- username: camp alfanumèric sense espais -->
			    			<div class="form-group">
			    				<label for="username"><?php echo ucfirst($admin->attributesLabels()['username']);?></label>
			    				<input type="text" class="form-control" name="username" id="username" pattern="[\w\d]{1,20}" title="Camp alfanumèric sense espais, 20 caràcters màxim." value="<?php echo $admin->username;?>" required>
			    				<div class="error"></div>
			    			</div>

			    			<!-- email: correu vàlid -->
			    			<div class="form-group">
			    				<label for="email"><?php echo ucfirst($admin->attributesLabels()['email']);?></label>
			    				<input type="email" class="form-control" name="email" id="email" title="Camp amb una adreça de email vàlida." value="<?php echo $admin->email;?>" required>
			    				<div class="error"></div>
			    			</div>

			    			<!-- password: camp alfanumèric -->
			    			<div class="form-group">
			    				<label for="password"><?php echo ucfirst($admin->attributesLabels()['password']);?></label>
			    				<input type="password" class="form-control" name="password" id="password" maxlength="32" title="Camp alfanumèric amb 30 caracters màxim." required>
			    				<div class="error"></div>
			    			</div>


			    			<div class="form-group">
			    				<label for="password2"><?php echo ucfirst($admin->attributesLabels()['password2']);?></label>
			    				<!-- camp alfanumèric -->
			    				<input type="password" class="form-control" name="password2" id="password2" maxlength="32" title="Camp alfanumèric amb 30 caracters màxim." required>
			    				<div class="error"></div>
			    			</div>
			    			
			    			<?php if(isset($_SESSION['user']) && $_SESSION['user'].isAdmin()): ?>
			    			<div class="form-group">
			    				<label for="">Estat</label>
			    				<label for="status-enabled"><input type="radio" class="form-control" name="status" id="status-enabled" value="1" <?php echo (isset($admin->status) && $admin->status == '1')?'checked':'';?>> Actiu</label>
			    				<label for="status-disabled"><input type="radio" class="form-control" name="status" id="status-disabled" value="0" <?php echo (isset($admin->status) && $admin->status == '0')?'checked':'';?>> Cancel·lat</label>
			    			</div>
			    			<?php endif; ?>

			    			
			    			<div class="form-group"><button type="submit" class="button">desa</button></div>
			    		</form>
	    			</div>
	    		</div>
	 
	    	</div>
