    	
	    	<div class="container">
	    		<div class="row">
	    			<div class="col-4">
	    				<h1>Producte</h1>
	    				<p>Els camps marcats amb * son obligatoris</p>
	    				<?php if(!empty($this->menu)): ?>
	    				<ul id="menu">
	    					<?php foreach($this->menu as $menuItem): ?>
	    					<li <?php echo (in_array('active', $menuItem))?'class="active"':''; ?>><a href="<?php echo $menuItem['href'];?>"><i class="fa fa-chevron-right"></i><?php echo $menuItem['text'];?></a></li>
	    					<?php endforeach; ?>
	    				</ul>
						<?php endif; ?>
	    			</div>
	    			<div class="col-8">
						<?php $this->renderPartial('_list', array('products'=>$products)); ?>
	    			</div>
	    		</div>
	 
	    	</div>
