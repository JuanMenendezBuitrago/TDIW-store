						<form id="main-form" action="<?php echo $this->getConfig('base_url'); ?>/usuari<?php echo !empty($user->id)?"/{$user->id}":"";?>" method="<?php echo $method;?>">

			    			<!-- name: camp amb només caràcters i espais -->
			    			<div class="form-group">
			    				<label for="name"><?php echo ucfirst($user->attributesLabels()['name']);?></label>
			    				<input type="text" class="form-control" name="name" id="name" pattern="((?![-_+.,!@#$%^&*();\\/|<>'\u0022])\D|\s+){1,50}" title="Camp amb només caràcters i espais." value="<?php echo $user->name;?>" required>
								<div class="error"></div>
			    			</div>

			    			<!-- username: camp alfanumèric sense espais -->
			    			<div class="form-group">
			    				<label for="username"><?php echo ucfirst($user->attributesLabels()['username']);?></label>
			    				<input type="text" class="form-control" name="username" id="username" pattern="[\w\d]{1,20}" title="Camp alfanumèric sense espais." value="<?php echo $user->username;?>" required>
			    				<div class="error"></div>
			    			</div>


			    			<!-- password: camp alfanumèric -->
			    			<div class="form-group">
			    				<label for="password"><?php echo ucfirst($user->attributesLabels()['password']);?></label>
			    				<input type="password" class="form-control" name="password" id="password" pattern="[\s\S]{1,30}" title="Camp alfanumèric amb 30 caracters màxim." onchange="this.setCustomValidity(this.validity.patternMismatch ? '30 caràcters màxim.' : ''); if(this.checkValidity()) document.getElementById('main-form').password2.pattern = this.value; " <?php echo $method == 'put'?:'required';?>>
			    				<div class="error"></div>
			    			</div>

			    			<!-- password2: camp alfanumèric -->
			    			<div class="form-group">
			    				<label for="password2"><?php echo ucfirst($user->attributesLabels()['password2']);?></label>
			    				<input type="password" class="form-control" name="password2" id="password2" onchange="this.setCustomValidity(this.validity.patternMismatch ? 'Les contrasenyes no coincideixen' : '');"  <?php echo $method == 'put'?:'required';?>>
			    				<div class="error"></div>
			    			</div>

			    			<!-- email: correu vàlid -->
			    			<div class="form-group">
			    				<label for="email"><?php echo ucfirst($user->attributesLabels()['email']);?></label>
			    				<input type="email" class="form-control" name="email" id="email" title="Camp amb una adreça de email vàlida." value="<?php echo $user->email;?>" required>
			    				<div class="error"></div>
			    			</div>


			    			<!-- phone: 9 dígits -->
			    			<div class="form-group">
			    				<label for="phone"><?php echo ucfirst($user->attributesLabels()['phone']);?></label>
			    				<input type="text" class="form-control" name="phone" id="phone" pattern="\d{9}" title="Camp amb 9 dígits." value="<?php echo $user->phone;?>" required>
			    				<div class="error"></div>
			    			</div>


			    			<!-- address: fins a 30 caràcters alfanumérics -->
			    			<div class="form-group">
			    				<label for="address"><?php echo ucfirst($user->attributesLabels()['address']);?></label>
			    				<input type="text" class="form-control" name="address" id="address" maxlength="30" title="Camp alfanumèric amb 30 caracters màxim." value="<?php echo $user->address;?>" required>
			    				<div class="error"></div>
			    			</div>


			    			<!-- city: fins a 30 caracters y espais -->
			    			<div class="form-group">
			    				<label for="city"><?php echo ucfirst($user->attributesLabels()['city']);?></label>
			    				<input type="text" class="form-control" name="city" id="city" pattern="((?![-_+.,!@#$%^&*();\\/|<>'\u0022])\D|\s+){1,30}" title="Camp amb 30 caracters màxim sense nombres." value="<?php echo $user->city;?>" required>
			    				<div class="error"></div>
			    			</div>


							<!-- zip: 5 dígits -->
			    			<div class="form-group">
			    				<label for="zip"><?php echo ucfirst($user->attributesLabels()['zip']);?></label>
			    				<input type="text" class="form-control" name="zip" id="zip" pattern="\d{5}" title="Camp amb 5 dìgits" value="<?php echo $user->zip;?>" required>
			    				<div class="error"></div>
			    			</div>

							<!-- card: 16 dígits -->
			    			<div class="form-group">
			    				<label for="card"><?php echo ucfirst($user->attributesLabels()['card']);?></label>
			    				<input type="text" class="form-control" name="card" id="card" pattern="[0-9]{16}" title="Camp amb 16 dìgits" value="<?php echo $user->card;?>" required>
			    				<div class="error"></div>
			    			</div>

			    			<?php if(isset($_SESSION['user']) && $_SESSION['user']->isAdmin): ?>
							<!-- status:  -->
			    			<div class="form-group">
			    				<label for="">Estat</label>
			    				<label for="status-enabled"><input type="radio" class="form-control" name="status" id="status-enabled" value="1" <?php echo (isset($user->status) && $user->status == '1')?'checked':'';?>> Actiu</label>
			    				<label for="status-disabled"><input type="radio" class="form-control" name="status" id="status-disabled" value="0" <?php echo (isset($user->status) && $user->status == '0')?'checked':'';?>> Cancel·lat</label>
			    			</div>
			    			<?php endif; ?>
			    			<div class="form-group"><button type="submit" class="button">desa<i class="fa fa-spinner fa-pulse fa-fw"></i></button><span id="result"></span></div>
			    		</form>