<div class="flex">
	<div class="col1">total</div>
	<div class="col1">updated</div>
	<div class="col1">estat</div>
	<div class="col3">userame</div>
	<div class="actions">&nbsp;</div>
</div>
<?php if(!empty($orders)): ?>	
<?php foreach($orders as $order): ?>
<div class="flex">
	<div class="col1"><?php echo number_format($order->total,2); ?></div>
	<div class="col1"><?php echo $order->updated; ?></div>
	<div class="col1"><?php echo $order->status; ?></div>
	<div class="col3"><?php echo $order->username; ?></div>
	<div class="actions"><a href="<?php echo $this->getConfig('base_url');?>/comanda/<?php echo $order->id; ?>"  class="delete"><i class="fa fa-trash"></i></a><a href="<?php echo $this->getConfig('base_url');?>/comanda/<?php echo $order->id; ?>"  class="show"><i class="fa fa-eye"></i></a></div>
</div>
<?php endforeach; ?>
<?php else: ?>
	<div class="flex">
		<div style="text-align:center;">No hi ha comandes</div>
	</div>
<?php endif; ?>	
