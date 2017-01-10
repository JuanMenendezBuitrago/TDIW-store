	    	<div class="container">
		    	<div class="row flex">
			    	<div class="col-8">
				    	<ul class="nav">
							<li class="dropdown-toggle" ><a href="#">categories<i class="caret"></i></a>
								<ul  class="dropdown categories-list">
								<?php foreach($this->getCategories() as $category): ?>
									<li><a href="<?php echo $this->getConfig('base_url'); ?>/producte/<?php echo $category->alias; ?>"><?php echo $category->name; ?></a></li>
								<?php endforeach; ?>
								</ul>
							</li>
						</ul>
					</div>
		    	</div>
		    	<div id="list">
		    	<?php $this->renderPartial('_productList',array('products'=>$products)); ?>
    			</div>
	    	</div>