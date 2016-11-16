	    	<div class="container">
	    		<?php if(isset($category)):?>
	    		<div class="row">
	    			<div class="col-12">
	    				<h1><?php echo $category; ?></h1>
	    			</div class="col-12">
	    		</div>
	    		<?php endif; ?>
    			<?php $i=0; 
    				foreach($products as $product):
    					if($i%3 == 0): ?>
	    		<div class="row">
	    		<?php endif; ?>
	    			<div class="col-4">
	    				<div class="item">
	    					<img src="holder.js/100px200" alt="">
	    					<h3><?php echo $product->name; ?></h3>
	    					<a href="/producte/<?php echo $product->alias; ?>"><?php echo $product->category; ?></a>
	    					<p><?php echo nl2br($product->intro); ?></p>
	    					<div class="price">€<?php echo number_format($product->price, 2); ?></div>
	    					<div class="item-footer">
	    						<div class="button minus" data-id="<?php echo $product->id; ?>"><i class="fa fa-minus"></i></div>
	    						<div class="amount"><?php echo isset($GLOBALS['cart_items'][$product->id])?$GLOBALS['cart_items'][$product->id]:0; ?></div>
	    						<div class="button plus" data-id="<?php echo $product->id; ?>"><i class="fa fa-plus"></i></div>
	    					</div>
	    				</div>
	    			</div>
	    		<?php if($i%3 == 2): ?>
	    		</div>
	    		<?php endif; $i++; ?>
	    		<?php endforeach; ?>
	    	</div>