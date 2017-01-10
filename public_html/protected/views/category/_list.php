<?php if(!empty($categories)): ?>	
<div class="flex">
	<div class="col1">nom</div>
	<div class="col2">nom</div>
	<div class="actions">&nbsp;</div>
</div>
<?php foreach($categories as $category): ?>
<div class="flex">
	<div class="col1"><?php echo $category->name; ?></div>
	<div class="col2"><?php echo $category->name; ?></div>
	<div class="actions"><a href="<?php echo $this->getConfig('base_url');?>/admin/categoria/<?php echo $category->id; ?>" class="edit"><i class="fa fa-edit"></i></a><a href="<?php echo $this->getConfig('base_url');?>/categoria/<?php echo $category->id; ?>"  class="delete"><i class="fa fa-trash"></i></a><a href="<?php echo $this->getConfig('base_url');?>/categoria/<?php echo $category->id; ?>"  class="show"><i class="fa fa-eye"></i></a></div>
</div>
<?php endforeach; ?>
<?php else: ?>
	vacio
<?php endif; ?>		