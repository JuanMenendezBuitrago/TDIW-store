
<div class="flex">
	<div class="col1">producte</div>
	<div class="col1">preu</div>
	<div class="col3">quantitat</div>
	<div class="actions">&nbsp;</div>
</div>
<?php $sum=0; if(!empty($list)): ?>
<?php foreach($list as $item): ?>
<div class="flex" data-item="<?php echo $item['product']->id; ?>">
	<div class="col1"><?php echo $item['product']->name; ?></div>
	<div class="col1"><?php echo  number_format($item['product']->price, 2); ?></div>
	<div class="col3"><a class="update" href="<?php echo $this->getConfig('base_url'); ?>/comanda/inc/<?php echo $item['product']->id; ?>"><i class="fa fa-plus-circle"></i></a><span class="amount"><?php echo $item['amount']; $sum+=$item['product']->price*$item['amount'];?></span><a class="update" href="<?php echo $this->getConfig('base_url'); ?>/comanda/dec/<?php echo $item['product']->id; ?>"><i class="fa fa-minus-circle"></i></a></div>
	<div class="actions"><a href="<?php echo $this->getConfig('base_url');?>/producte/<?php echo $item['product']->id; ?>"  class="show"><i class="fa fa-eye"></i></a></div>
</div>
<?php endforeach; ?>
<?php else: ?>
	<div class="flex">
		<div style="text-align:center;">El cistell està buit</div>
	</div>
<?php endif; ?>		
<div id="total" class="flex table">
	<div>TOTAL: €<?php echo number_format($sum, 2);?></div>
</div>
<span id="result"></span>
<div class="buttons"><a href="<?php echo $this->getConfig('base_url'); ?>/comanda/buida" class="button red empty">BUIDAR EL CISTELL</a><a href="<?php echo $this->getConfig('base_url'); ?>/" class="button">CONTINUAR COMPRANT</a>
<?php if($this->getUser()->isGuest()): ?>
<a href="<?php echo $this->getConfig('base_url'); ?>/registrat" class="button pink checkout">REGISTRA'T</a></div>
<?php elseif($this->getUser()->isRegistered()): ?>
<a href="<?php echo $this->getConfig('base_url'); ?>/comanda/checkout" class="button pink checkout">CHECKOUT</a></div>
<?php endif; ?>