
	    	<div class="container admin">
	    		<div class="row">
	    			<div class="col-4">
	    				<h1>Administra proveidors</h1>
	    				<p>Els camps marcats amb * son obligatoris</p>
	    				<?php if(!empty($this->menu)): ?>
	    				<ul id="menu">
	    					<?php foreach($this->menu as $menuItem): ?>
	    					<li <?php echo (in_array('active', $menuItem))?'class="active"':''; ?>>
	    						<a href="<?php echo $menuItem['href'];?>"><i class="fa fa-chevron-right"></i><?php echo $menuItem['text'];?></a>
	    					</li>
	    					<?php endforeach; ?>
	    				</ul>
						<?php endif; ?>		
	    			</div>
	    			<div class="col-8" id="admin-list">
	    				<?php if(!empty($suppliers)): ?>	
	    				<div class="flex">
	    					<div class="col1">usuari</div>
	    					<div class="col2">nom</div>
	    					<div class="actions">&nbsp;</div>
	    				</div>
    					<?php foreach($suppliers as $supplier): ?>
    					<div class="flex">
    						<div class="col1"><?php echo $supplier->name; ?></div>
    						<div class="col2"><?php echo $supplier->name; ?></div>
    						<div class="actions"><a href=""><i class="fa fa-edit"></i></a><a href=""><i class="fa fa-trash"></i></a><a href=""><i class="fa fa-eye"></i></a></div>
    					</div>
    					<?php endforeach; ?>
    					<?php else: ?>
    						vacio
						<?php endif; ?>		
	    			</div>
	    		</div>
	 
	    	</div>
