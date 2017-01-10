
<?php if(!empty($users)): ?>	
<div class="flex">
	<div class="username">usuari</div>
	<div class="name">nom</div>
	<div class="actions">&nbsp;</div>
</div>
<?php foreach($users as $user): ?>
<div class="flex">
	<div class="username"><?php echo $user->username; ?></div>
	<div class="name"><?php echo $user->name; ?></div>
	<div class="actions"><a href="<?php echo $this->getConfig('base_url');?>/admin/usuari/<?php echo $user->id; ?>" class="edit"><i class="fa fa-edit"></i></a><a href="<?php echo $this->getConfig('base_url');?>/usuari/<?php echo $user->id; ?>"  class="delete"><i class="fa fa-trash"></i></a><a href="<?php echo $this->getConfig('base_url');?>/usuari/<?php echo $user->id; ?>"  class="show"><i class="fa fa-eye"></i></a></div>
</div>
<?php endforeach; ?>
<?php else: ?>
	vacio
<?php endif; ?>