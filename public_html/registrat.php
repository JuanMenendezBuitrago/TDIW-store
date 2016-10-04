<!doctype html>
<html class="no-js" lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>
        	<!-- TODO: insertar título -->
        </title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
        <link rel="apple-touch-icon" href="apple-touch-icon.png">
		<link rel="icon" href="favicon.png">

        <link rel="stylesheet" href="css/normalize.css">
        <!-- <link rel="stylesheet" href="css/grid.css"> -->
        <link rel="stylesheet" href="css/webfonts.css">
        <link rel="stylesheet" href="css/font-awesome.css">
        <link rel="stylesheet" href="css/common.css">

		<!-- jQuery -->
        <script src="http://code.jquery.com/jquery-1.12.4.min.js"  defer></script>
		<!-- holder.js crea imágenes de relleno para la etapa de diseño -->
        <script src="https://cdn.rawgit.com/imsky/holder/master/holder.js"  defer></script>
        <!-- custom code -->
        <script src="js/common.js"  defer></script>
        <script src="js/form.js"  defer></script>

        <style>
        	main > .container > .row{
        		margin-top:  40px;
        	}

        	.col-4{
        		text-align: right;
        	}

        	.col-4 h1{
        		margin-bottom: 5px;
        	}

        	.col-4 p{
        		color: orange;
        		margin-top: 0;
        	}

        	.col-8 {
        		margin-top:  20px;
        	}
        </style>

    </head>
    <body>
    	<header>
	    	<nav>
	    		<div class="container">
	    			<ul class="nav">
	    				<li><a href="#"><i class="fa fa-home"></i>inici</a></li>
	    				<li class="dropdown-toggle"><a href="ayuda.html">productes<i class="caret"></i></a>
							<ul class="dropdown">
								<li><a href="#">foo</a></li>
								<li><a href="#">bar</a></li>
								<li><a href="#">baz</a></li>
							</ul>
						</li>
	    			</ul>
	    			<ul class="nav login">
	    				<li><a href="#">registra't</a></li>
	    				<li><a href="#"><i class="fa fa-user" aria-hidden="true"></i>entra</a></li>
	    				<li><a href="#"><i class="fa fa-shopping-cart" aria-hidden="true"><span class="bubble">10</span></i>cistell</a></li>
	    			</ul>
	    		</div>
	    	</nav>
    	</header>
    	<main>
	    	<div class="container">
	    		<div class="row">
	    			<div class="col-4">
	    				<h1>Registra't</h1>
	    				<p>Els camp marcats amb * son obligatoris</p>
	    			</div>
	    			<div class="col-8">
				   		<form action="">
			    			<div class="form-group">
			    				<label for="name">Nom</label>
			    				<!-- camp amb només caràcters i espais -->
			    				<input type="text" class="form-control" name="name" id="name" pattern="((?![-_+.,!@#$%^&*();\\/|<>'\u0022])\D)+" title="Camp amb només caràcters i espais." required>
			    			</div>
			    			<div class="form-group">
			    				<label for="user">Usuari</label>
			    				<!-- camp alfanumèric sense espais -->
			    				<input type="text" class="form-control" name="user" id="user" pattern="([^\s])+" title="Camp alfanumèric sense espais." required>
			    			</div>
			    			<div class="form-group">
			    				<label for="passwd">Password</label>
			    				<!-- camp alfanumèric -->
			    				<input type="password" class="form-control" name="passwd" id="passwd" maxlength="30" title="Camp alfanumèric amb 30 caracters màxim." required>
			    			</div>
			    			<div class="form-group">
			    				<label for="email">Email</label>
			    				<!-- correu vàlid -->
			    				<input type="email" class="form-control" name="email" id="email" title="Camp amb una adreça de email vàlida." required>
			    			</div>
			    			<div class="form-group">
			    				<label for="phone">Telèfon</label>
			    				<!-- 9 dígits -->
			    				<input type="text" class="form-control" name="phone" id="phone" pattern="\s{9}" title="Camp amb 9 dígits." required>
			    			</div>
			    			<div class="form-group">
			    				<label for="address">Adreça</label>
			    				<!-- fins a 30 caràcters alfanumérics -->
			    				<input type="text" class="form-control" name="address" id="address" maxlength="30" title="Camp alfanumèric amb 30 caracters màxim." required>
			    			</div>
			    			<div class="form-group">
			    				<label for="city">Població</label>
			    				<!-- fins a 30 caracters y espais -->
			    				<input type="text" class="form-control" name="city" id="city" pattern="((?![-_+.,!@#$%^&*();\\/|<>'\u0022])\D){0,30}" title="Camp amb 30 caracters màxim sense nombres." required>
			    			</div>
			    			<div class="form-group">
			    				<label for="zip">Codi Postal</label>
								<!-- 4 dígits -->
			    				<input type="text" class="form-control" name="zip" id="zip" pattern="[0-9]{5}" title="Camp amb 5 dìgits" required>
			    			</div>
			    			<div class="form-group">
			    				<label for="card">Targeta Bancaria</label>
								<!-- 16 dígits -->
			    				<input type="text" class="form-control" name="card" id="card" pattern="[0-9]{16}" title="Camp amb 16 dìgits" required>
			    			</div>
			    			<div class="form-group"><button type="submit" class="button">desa</button></div>
			    		</form>
	    			</div>
	    		</div>
	 
	    	</div>
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