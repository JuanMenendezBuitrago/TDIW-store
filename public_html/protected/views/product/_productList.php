<?php $i=0; 
    				foreach($products as $product):
    					if($i%3 == 0): ?>
	    		<div class="row flex">
	    		<?php endif; ?>
	    			<div class="col-4">
	    				<div class="item" data-item="<?php echo $product->id; ?>">
		    				<?php if(!empty($product->picture) && file_exists($this->getConfig('pictures_path')."/".$product->id."/".$product->picture)):?>
			    			<img src="<?php echo $this->getConfig('base_url')."/img/products/".$product->id."/".$product->picture; ?>" id="picture-preview" alt="picture preview">
						    <?php else: ?>
							<img src="holder.js/100px200" alt="">
							<?php endif; ?>
	    					<h3><a href="<?php echo $this->getConfig('base_url'); ?>/producte/<?php echo $product->id; ?>"><?php echo htmlspecialchars($product->name); ?></a></h3>
	    					<p><?php echo nl2br(htmlspecialchars($product->intro)); ?></p>
	    					<div class="price">€<?php echo htmlspecialchars(number_format($product->price, 2)); ?></div>
	    					<div class="item-footer">
	    						<div class="button minus" data-target="<?php echo $this->getConfig('base_url'); ?>/comanda/dec/<?php echo $product->id; ?>"><i class="fa fa-minus"></i></div>
	    						<div class="amount"><?php echo isset($_SESSION['cart'][$product->id])?$_SESSION['cart'][$product->id]:0; ?></div>
	    						<div class="button plus" data-target="<?php echo $this->getConfig('base_url'); ?>/comanda/inc/<?php echo $product->id; ?>"><i class="fa fa-plus"></i></div>
	    					</div>
	    				</div>
	    			</div>
	    		<?php if($i%3 == 2): ?>
	    		</div>
	    		<?php endif; $i++; ?>
	    		<?php endforeach; ?>