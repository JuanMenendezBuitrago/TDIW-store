<h3>Detalls de la comanda</h3>
<?php if(!empty($list)): 
$sum=0;
?>	
<div class="flex table">
	<div>product</div>
	<div>price</div>
	<div>amount</div>
	<div>subtotal</div>
</div>
<?php foreach($list as $item): ?>
<div class="flex table">
	<div><?php echo $item['name']; ?></div>
	<div><?php echo $item['price']; ?></div>
	<div><?php echo $item['amount']; ?></div>
	<div><?php echo $item['subtotal']; $sum+=$item['subtotal'];?></div>
	<!-- <div class="actions"><a href="<?php echo $this->getConfig('base_url');?>/comanda/<?php echo $item['id']; ?>"  class="delete"><i class="fa fa-trash"></i></a><a href="<?php echo $this->getConfig('base_url');?>/comanda/<?php echo $item['id']; ?>"  class="show"><i class="fa fa-eye"></i></a></div> -->

</div>
<?php endforeach; ?>
<div id="total" class="flex table">
	<div>TOTAL: €<?php echo number_format($sum, 2);?></div>
</div>
<div class="buttons"><a href="" class="button" id="close">tanca</a></div>
<?php else: ?>
	vacio
<?php endif; ?>		