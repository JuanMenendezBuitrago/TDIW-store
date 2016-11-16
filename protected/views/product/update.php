    	
	    	<div class="container">
	    		<div class="row">
	    			<div class="col-4">
	    				<h1>Registra't</h1>
	    				<p>Els camps marcats amb * son obligatoris</p>
	    			</div>
	    			<div class="col-8">
				   		<form action="/producte" method="<?php echo $method;?>">
							<input type="hidden" name="tempId" id="tempId" value="<?php echo $tempId; ?>">
			    			<!-- name: camp amb només caràcters i espais -->
			    			<div class="form-group">
			    				<label for="name"><?php echo ucfirst($product->attributesLabels()['name']);?></label>
			    				<input type="text" class="form-control" name="name" id="name" pattern="[\s\S]{1,50}" title="Camp alfanumèric de longitud max 50." value="<?php echo $product->name;?>" required>
								<div class="error"></div>
			    			</div>
							
							<!-- category_id:  -->
			    			<div class="form-group">
			    				<label for="name"><?php echo ucfirst($product->attributesLabels()['category_id']);?></label>
			    				<select class="form-control" name="category_id" id="category_id" required>
			    					<option value="0">Selecciona una categoria</option>
			    					<?php foreach($categories as $category): ?>
			    					<option value="<?php echo $category->id?>" <?php echo ($product->category_id == $category->id)?"selected":""; ?>><?php echo $category->name; ?></option>
			    				<?php endforeach; ?>
			    				</select>
								<div class="error"></div>
			    			</div>
							
							<!-- supplier_id:  -->
			    			<div class="form-group">
			    				<label for="name"><?php echo ucfirst($product->attributesLabels()['supplier_id']);?></label>
			    				<select class="form-control" name="supplier_id" id="supplier_id" required>
			    					<option value="0">Selecciona un proveidor</option>
			    					<?php foreach($suppliers as $supplier): ?>
			    					<option value="<?php echo $supplier->id?>" <?php echo ($product->supplier_id == $supplier->id)?"selected":""; ?>><?php echo $supplier->name; ?></option>
			    				<?php endforeach; ?>
			    				</select>
								<div class="error"></div>
			    			</div>

			    			<!-- intro: camp alfanumèric 140 caracteres max -->
			    			<div class="form-group">
			    				<label for="intro"><?php echo ucfirst($product->attributesLabels()['intro']);?></label>
			    				<textarea class="form-control" name="intro" id="intro" pattern="[\s\S]{1,140}" title="Camp alfanumèric de longitud max 140." required><?php echo $product->intro;?></textarea>
			    				<div class="error"></div>
			    			</div>


			    			<!-- description: camp alfanumèric 500 caracteres max -->
			    			<div class="form-group">
			    				<label for="description"><?php echo ucfirst($product->attributesLabels()['description']);?></label>
			    				<textarea class="form-control" name="description" rows="4" id="description" pattern="[\s\S]{1,500}" title="Camp alfanumèric de longitud max 140." required><?php echo $product->description;?></textarea>
			    				<div class="error"></div>
			    			</div>

			    			<!-- price: camp numèric amb dues xifres de imals-->
			    			<div class="form-group">
			    				<label for="price"><?php echo ucfirst($product->attributesLabels()['price']);?></label>
			    				<input type="text" class="form-control" name="price" rows="8" id="price" pattern="\d+(\.\d{2})?" title="Camp numèric amb dues xifres decimals." required>
			    				<div class="error"></div>
			    			</div>

			    			<!-- stock: camp numèric sencer -->
			    			<div class="form-group">
			    				<label for="stock"><?php echo ucfirst($product->attributesLabels()['stock']);?></label>
			    				<input type="number" min="0" step="1" class="form-control" name="stock" id="stock" title="Camp numèric. Valors sencers positius o zero." required>
			    				<div class="error"></div>
			    			</div>

			    			<!-- picture:  -->
				    		<div class="form-group">
				    				<label for="picture">Imatge</label>
				    				<div id="image-form-group">
					    				<div class="col-4">
					    					<i class="fa fa-camera"></i>
						    				<img src="#" id="picture-preview" alt="picture preview" style="display:none;">
					    				</div>
					    				<div class="col-8">
										    <input name="picture" id="picture" type="file" class="form-control" />
										    <div class="error"></div>
										    <button class="button pink" id="upload-picture">puja!</button>
					    				</div>
				    				</div>
								<progress></progress>
							</div>
			    			

			    			<?php if(isset($_SESSION['user']) && $_SESSION['user']->isAdmin): ?>
							<!-- status:  -->
			    			<div class="form-group">
			    				<label for="">Estat</label>
			    				<label for="status-enabled"><input type="radio" class="form-control" name="status" id="status-enabled" value="1" <?php echo (isset($product->status) && $product->status == '1')?'checked':'';?>> Actiu</label>
			    				<label for="status-disabled"><input type="radio" class="form-control" name="status" id="status-disabled" value="0" <?php echo (isset($product->status) && $product->status == '0')?'checked':'';?>> Cancel·lat</label>
			    			</div>
			    			<?php endif; ?>
			    			<div class="form-group"><button type="submit" class="button">desa</button></div>
			    		</form>
	    			</div>
	    		</div>
	 
	    	</div>
