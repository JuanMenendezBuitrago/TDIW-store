<?php if(!empty($products)): ?>	
<div class="flex">
	<div></div>
	<div class="col1">categoria</div>
	<div class="col2">nom</div>
	<div class="actions">&nbsp;</div>
</div>
<?php foreach($products as $product): ?>
<div class="flex">
	<div>
		<?php if(!empty($product->picture) && file_exists($this->getConfig('pictures_path')."/".$product->id."/".$product->picture)):?>
		<img src="<?php echo $this->getConfig('base_url')."/img/products/".$product->id."/".$product->picture; ?>" id="picture-preview" alt="picture preview" width="30">
	    <?php else: ?>
		<img src="holder.js/30x30" alt="">
		<?php endif; ?>
	</div>
	<div class="col1"><?php echo $product->category; ?></div>
	<div class="col2"><?php echo $product->name; ?></div>
	<div class="actions"><a href="<?php  echo $this->getConfig('base_url');?>/admin/producte/<?php echo $product->id; ?>" class="edit"><i class="fa fa-edit"></i></a><a href="<?php echo $this->getConfig('base_url');?>/producte/<?php echo $product->id; ?>"  class="delete"><i class="fa fa-trash"></i></a><a href="<?php echo $this->getConfig('base_url');?>/admin/show/producte/<?php echo $product->id; ?>"  class="show"><i class="fa fa-eye"></i></a></div>
</div>
<?php endforeach; ?>
<?php else: ?>
	vacio
<?php endif; ?>		