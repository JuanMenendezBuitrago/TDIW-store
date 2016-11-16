<!doctype html>
<html class="no-js" lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>
        	<?php echo $this->getPageTitle(); ?>
        </title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
        <link rel="apple-touch-icon" href="apple-touch-icon.png">
		<link rel="icon" href="favicon.png">

        <link rel="stylesheet" href="<?php echo $this->getConfig('base_url'); ?>/css/normalize.css">
        <link rel="stylesheet" href="<?php echo $this->getConfig('base_url'); ?>/css/webfonts.css">
        <link rel="stylesheet" href="<?php echo $this->getConfig('base_url'); ?>/css/font-awesome.css">
        <link rel="stylesheet" href="<?php echo $this->getConfig('base_url'); ?>/css/common.css">
        <?php echo $this->getStyles();?>

		<!-- jQuery -->
        <script src="http://code.jquery.com/jquery-1.12.4.min.js"  defer></script>
        <script src="<?php echo $this->getConfig('base_url'); ?>/js/js.cookie.js"></script>
		<!-- holder.js crea imágenes de relleno para la etapa de diseño -->
        <script src="https://cdn.rawgit.com/imsky/holder/master/holder.js"  defer></script>
        <!-- custom code -->
        <script src="<?php echo $this->getConfig('base_url'); ?>/js/common.js"  defer></script>
        <?php echo $this->getScripts();?>

    </head>
    <body id="<?php echo $this->bodyId; ?>" class="<?php echo $this->bodyClass; ?>">
    	<header>
	    	<nav>
	    		<div class="container">
	    			<ul class="nav">
	    				<li><a href="/"><i class="fa fa-home"></i>inici</a></li>
	    				<li class="dropdown-toggle"><a href="ayuda.html">productes<i class="caret"></i></a>
							<ul class="dropdown">
							<?php foreach($this->getCategories() as $category): ?>
								<li><a href="<?php echo $this->getConfig('base_url'); ?>/producte/<?php echo $category->alias; ?>"><?php echo $category->name; ?></a></li>
							<?php endforeach; ?>
							</ul>
						</li>
						<li id="search"><i class="fa fa-search"></i><i class="caret right"></i><input type="text" class="form-control"></a></li>
	    			</ul>
	    			<ul class="nav login">
	    			<?php if($_SESSION['user']->id == 0): ?>
	    				<li><a href="/registrat">registra't</a></li>
	    				<li class="dropdown-toggle"><a href="#"><i class="fa fa-user" aria-hidden="true"></i>entra<i class="caret"></i></a>
							<ul class="dropdown">
								<form action="/login">
									<div class="form-group">
										<input type="text" class="form-control" name="username" id="username" placeholder="usuari" required>
									</div>
									<div class="form-group">
										<input type="password" class="form-control" name="password" id="password" placeholder="contrasenya" required>
									</div>
									<div class="form-group">
										<button type="submit" class="button">entra</button>
									</div>
								</form>
							</ul>
	    				</li>
	    			<?php else: ?>
	    				<li class="dropdown-toggle"><a href="#"><i class="fa fa-user" aria-hidden="true"></i><?php echo $_SESSION['user']->userName;?><i class="caret"></i></a>
	    					<ul class="dropdown">
	    					<?php if($_SESSION['user']->isAdmin): ?>
								<li><a href="<?php echo $this->getConfig('base_url'); ?>/admin/usuari">usuari</a></li>
								<li><a href="<?php echo $this->getConfig('base_url'); ?>/admin/producte">producte</a></li>
								<li><a href="<?php echo $this->getConfig('base_url'); ?>/admin/categoria">categoria</a></li>
								<li><a href="<?php echo $this->getConfig('base_url'); ?>/admin/proveidor">proveidor</a></li>
								<li><a href="<?php echo $this->getConfig('base_url'); ?>/admin/comandes">comandes</a></li>
							<?php endif; ?>
								<li><a href="<?php echo $this->getConfig('base_url'); ?>/usuari/<?php echo $_SESSION['user']->id;?>">perfil</a></li>
								<li><a href="<?php echo $this->getConfig('base_url'); ?>/comandes">comandes</a></li>
								<li><a href="<?php echo $this->getConfig('base_url'); ?>/logout"><i class="fa fa-sign-out"></i>sortir</a></li>
	    					</ul>
						</li>
	    			<?php endif; ?>
	    				<li><a href="#"><i class="fa fa-shopping-cart" aria-hidden="true"><span class="bubble"></span></i>cistell</a></li>
	    			</ul>
	    		</div>
	    	</nav>
    	</header>
    	<main>

			<!-- content -->
			<?php echo $content; ?>
	    		
    	</main>
    	<footer>
    		<div class="container">
    			<div class="row">	
	    			<div class="col-4 offset-8">
						<ul>
							<li>Contacto</li>
							<li>Lorem Ipsum</li>
							<li>Dolor Sit 123</li>
							<li>Sabadell</li>
							<li>08203 Barcelona</li>
							<li>lorem.ipsum@lorempsum.org</li>
							<li id="social"><a href="#"><i class="fa fa-facebook"></i></a><a href="#"><i class="fa fa-twitter"></i></a><a href="#"><i class="fa fa-instagram"></i></a></li>
						</ul>
	    			</div>
	    		</div>
    		</div>
    	</footer>
    </body>
</html>