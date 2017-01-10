<?php if(!empty($admins)): ?>	
<div class="flex">
	<div class="col1">usuari</div>
	<div class="col2">&nbsp;</div>
	<div class="actions">&nbsp;</div>
</div>
<?php foreach($admins as $admin): ?>
<div class="flex">
	<div class="col1"><?php echo $admin->username; ?></div>
	<div class="col2">&nbsp;</div>
	<div class="actions"><a href="<?php echo $this->getConfig('base_url');?>/admin/admin/<?php echo $admin->id; ?>" class="edit"><i class="fa fa-edit"></i></a><a href="<?php echo $this->getConfig('base_url');?>/admin/<?php echo $admin->id; ?>"  class="delete"><i class="fa fa-trash"></i></a><a href="<?php echo $this->getConfig('base_url');?>/admin/<?php echo $admin->id; ?>"  class="show"><i class="fa fa-eye"></i></a></div>
</div>
<?php endforeach; ?>
<?php else: ?>
	vacio
<?php endif; ?>	