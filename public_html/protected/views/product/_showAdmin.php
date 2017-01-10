    		<h2><?php echo $product->name; ?></h2>
	    	<div class="body">
				<div class="item" data-item="<?php echo $product->id; ?>">
					<div class="flex">
						<?php if(!empty($product->picture) && file_exists($this->getConfig('pictures_path')."/".$product->id."/".$product->picture)):?>
		    			<img src="<?php echo $this->getConfig('base_url')."/img/products/".$product->id."/".$product->picture; ?>" id="picture-preview" alt="picture preview">
					    <?php else: ?>
						<img src="holder.js/100px200" alt="">
						<?php endif; ?>
						<div>
							<h3>intro</h3>
							<p><?php echo nl2br($product->intro); ?></p>
							<h3>description</h3>
							<p><?php echo nl2br($product->description); ?></p>
						</div>
					</div>
					<div class="price">€<?php echo number_format($product->price, 2); ?></div>
					<div class="item-footer">
						<div class="amount">stock: <?php echo $product->stock; ?></div>
					</div>
				</div>
	    	</div>
	    	<div class="buttons"><a href="" class="button" id="close">tanca</a></div>