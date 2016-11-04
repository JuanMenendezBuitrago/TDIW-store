    	
	    	<div class="container">
	    		<div class="row">
	    			<div class="col-4">
	    				<h1>Registra't</h1>
	    				<p>Els camps marcats amb * son obligatoris</p>
	    			</div>
	    			<div class="col-8">
				   		<form action="/usuari" method="post">
			    			<div class="form-group">
			    				<label for="name">Nom</label>
			    				<!-- camp amb només caràcters i espais -->
			    				<input type="text" class="form-control" name="name" id="name" pattern="((?![-_+.,!@#$%^&*();\\/|<>'\u0022])\D)+" title="Camp amb només caràcters i espais." required>
								<div class="error"></div>
			    			</div>
			    			<div class="form-group">
			    				<label for="username">Usuari</label>
			    				<!-- camp alfanumèric sense espais -->
			    				<input type="text" class="form-control" name="username" id="username" pattern="([^\s])+" title="Camp alfanumèric sense espais." required>
			    				<div class="error"></div>
			    			</div>
			    			<div class="form-group">
			    				<label for="password">Password</label>
			    				<!-- camp alfanumèric -->
			    				<input type="password" class="form-control" name="password" id="password" maxlength="30" title="Camp alfanumèric amb 30 caracters màxim." required>
			    				<div class="error"></div>
			    			</div>
			    			<div class="form-group">
			    				<label for="password2">Confirma el password</label>
			    				<!-- camp alfanumèric -->
			    				<input type="password" class="form-control" name="password2" id="password2" maxlength="30" title="Camp alfanumèric amb 30 caracters màxim." required>
			    				<div class="error"></div>
			    			</div>
			    			<div class="form-group">
			    				<label for="email">Email</label>
			    				<!-- correu vàlid -->
			    				<input type="email" class="form-control" name="email" id="email" title="Camp amb una adreça de email vàlida." required>
			    				<div class="error"></div>
			    			</div>
			    			<div class="form-group">
			    				<label for="phone">Telèfon</label>
			    				<!-- 9 dígits -->
			    				<input type="text" class="form-control" name="phone" id="phone" pattern="\d{9}" title="Camp amb 9 dígits." required>
			    				<div class="error"></div>
			    			</div>
			    			<div class="form-group">
			    				<label for="address">Adreça</label>
			    				<!-- fins a 30 caràcters alfanumérics -->
			    				<input type="text" class="form-control" name="address" id="address" maxlength="30" title="Camp alfanumèric amb 30 caracters màxim." required>
			    				<div class="error"></div>
			    			</div>
			    			<div class="form-group">
			    				<label for="city">Població</label>
			    				<!-- fins a 30 caracters y espais -->
			    				<input type="text" class="form-control" name="city" id="city" pattern="((?![-_+.,!@#$%^&*();\\/|<>'\u0022])\D){0,30}" title="Camp amb 30 caracters màxim sense nombres." required>
			    				<div class="error"></div>
			    			</div>
			    			<div class="form-group">
			    				<label for="zip">Codi Postal</label>
								<!-- 4 dígits -->
			    				<input type="text" class="form-control" name="zip" id="zip" pattern="[0-9]{5}" title="Camp amb 5 dìgits" required>
			    				<div class="error"></div>
			    			</div>
			    			<div class="form-group">
			    				<label for="card">Targeta Bancaria</label>
								<!-- 16 dígits -->
			    				<input type="text" class="form-control" name="card" id="card" pattern="[0-9]{16}" title="Camp amb 16 dìgits" required>
			    				<div class="error"></div>
			    			</div>
			    			<?php if(isset($_SESSION['user']) && $_SESSION['user'].isAdmin()): ?>
			    			<div class="form-group">
			    				<label for="">Estat</label>
			    				<label for="status-enabled"><input type="radio" class="form-control" name="status" id="status-enabled" value="1"> Actiu</label>
			    				<label for="status-disabled"><input type="radio" class="form-control" name="status" id="status-disabled" value="0"> Cancel·lat</label>
			    			</div>
			    			<?php endif; ?>
			    			<div class="form-group"><button type="submit" class="button">desa</button></div>
			    		</form>
	    			</div>
	    		</div>
	 
	    	</div>
