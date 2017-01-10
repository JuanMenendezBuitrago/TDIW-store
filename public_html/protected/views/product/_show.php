    		<h2><?php echo htmlspecialchars($product->name); ?></h2>
	    	<div class="body">
				<div class="item" data-item="<?php echo $product->id; ?>">
					<div class="flex">
						<?php if(!empty($product->picture) && file_exists($this->getConfig('pictures_path')."/".$product->id."/".$product->picture)):?>
		    			<img src="<?php echo $this->getConfig('base_url')."/img/products/".$product->id."/".$product->picture; ?>" id="picture-preview" alt="picture preview">
					    <?php else: ?>
						<img src="holder.js/100px200" alt="">
						<?php endif; ?>
						<p><?php echo nl2br(htmlspecialchars($product->description)); ?></p>
					</div>
					<div class="price">€<?php echo htmlspecialchars(number_format($product->price, 2)); ?></div>
					<div class="item-footer">
						<div class="button minus" data-target="<?php echo $this->getConfig('base_url'); ?>/comanda/dec/<?php echo $product->id; ?>"><i class="fa fa-minus"></i></div>
						<div class="amount"><?php echo isset($_SESSION['cart'][$product->id])?$_SESSION['cart'][$product->id]:0; ?></div>
						<div class="button plus" data-target="<?php echo $this->getConfig('base_url'); ?>/comanda/inc/<?php echo $product->id; ?>"><i class="fa fa-plus"></i></div>
					</div>
				</div>
	    	</div>
	    	<div class="buttons"><a href="" class="button" id="close">tanca</a></div>