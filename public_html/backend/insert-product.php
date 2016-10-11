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

        <link rel="stylesheet" href="../css/normalize.css">
        <!-- <link rel="stylesheet" href="../css/grid.css"> -->
        <link rel="stylesheet" href="../css/webfonts.css">
        <link rel="stylesheet" href="../css/font-awesome.css">
        <link rel="stylesheet" href="../css/common.css">

		<!-- jQuery -->
        <script src="http://code.jquery.com/jquery-1.12.4.min.js"  defer></script>
		<!-- holder.js crea imágenes de relleno para la etapa de diseño -->
        <script src="https://cdn.rawgit.com/imsky/holder/master/holder.js"  defer></script>
        <!-- custom code -->
        <script src="../js/common.js"  defer></script>
        <script src="../js/form.js"  defer></script>

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
	    				<li><a href="#">producte</a></li>
	    				<li class="dropdown-toggle"><a href="#"><i class="fa fa-user" aria-hidden="true"></i>entra<i class="caret"></i></a>
							<ul class="dropdown">
								<form action="">
									<div class="form-group">
										<input type="" class="form-control" placeholder="usuari">
									</div>
									<div class="form-group">
										<input type="" class="form-control" placeholder="contrasenya">
									</div>
									<div class="form-group">
										<button class="button">entra</button>
									</div>
								</form>
							</ul>
	    				</li>
	    				<li><a href="#"><i class="fa fa-shopping-cart" aria-hidden="true"><span class="bubble">10</span></i>cistell</a></li>
	    			</ul>
	    		</div>
	    	</nav>
    	</header>
    	<main>
	    	<div class="container">
	    		<div class="row">
	    			<div class="col-4">
	    				<h1>Producte</h1>
	    				<p>Els camp marcats amb * son obligatoris</p>
	    				<img src="holder.js/100px200?text=no picture" alt=""/>
	    			</div>
	    			<div class="col-8">
				   		<form action="">
			    			<div class="form-group">
			    				<label for="producte">Producte</label>
			    				<!-- camp amb només caràcters i espais longitud màxima 30 caracters-->
			    				<input type="text" class="form-control" name="producte" id="producte"  maxlength="30" required>
			    			</div>
			    			<div class="form-group">
			    				<label for="category">Categoria</label>
			    				<!-- camp alfanumèric sense espais -->
			    				<select class="form-control" name="category" id="category"  required>
				    				<option value="foo">foo</option>
				    				<option value="bar">bar</option>
				    				<option value="baz">baz</option>
			    				</select>
			    			</div>
			    			<div class="form-group">
			    				<label for="price">Preu</label>
			    				<!-- Nombre amb coma i dues xifres decimals -->
			    				<input type="text" class="form-control" name="price" id="price" pattern="\d+\,\d{2}" title="Nombre amb coma i dues xifres decimals." required>
			    			</div>
			    			<div class="form-group">
			    				<label for="intro">Descripció breu</label>
			    				<!-- fins a 100 caràcters -->
			    				<textarea  rows="4" class="form-control" name="intro" id="intro" maxlength="100" required></textarea>
			    			</div>
			    			<div class="form-group">
			    				<label for="description">Descripció</label>
			    				<!-- fins a 100 caràcters -->
			    				<textarea  rows="4" class="form-control" name="description" id="description" maxlength="100" required></textarea>
			    			</div>
			    			<div class="form-group">
			    				<label for="">Estat</label>
			    				<!-- fins a 100 caràcters -->
			    				<label for="status-enabled"><input type="radio" class="form-control" name="status" id="status-enabled" value="1"> Actiu</label>
			    				<label for="status-disabled"><input type="radio" class="form-control" name="status" id="status-disabled" value="0"> Cancel·lat</label>
			    			</div>
			    			<div class="form-group">
			    				<label for="city">Imatge</label>
			    				<input type="file" class="form-control" name="picture" id="picture" required>
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