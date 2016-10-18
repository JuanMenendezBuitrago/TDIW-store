    	<main>			
			<img data-src="holder.js/100px500?text=Hello world! This is HTML5 Boilerplate">
	    	<div class="container">
	    		<div class="row">
	    			<div class="col-12">
	    			<?php
	    				echo basename($_SERVER['SCRIPT_FILENAME'])."<br>";
						echo $_SERVER['SCRIPT_FILENAME']."<br><br>";
						echo basename($_SERVER['SCRIPT_NAME'])."<br>";
						echo $_SERVER['SCRIPT_NAME']."<br><br>";
						echo basename($_SERVER['PHP_SELF'])."<br>";
						echo $_SERVER['PHP_SELF']."<br>";

						if(isset($_SERVER['HTTP_X_REWRITE_URL'])) // IIS
							$requestUri=$_SERVER['HTTP_X_REWRITE_URL'];

						elseif(isset($_SERVER['REQUEST_URI'])){
							$requestUri=$_SERVER['REQUEST_URI'];
							if(!empty($_SERVER['HTTP_HOST']))
							{
								if(strpos($requestUri,$_SERVER['HTTP_HOST'])!==false)
									$requestUri=preg_replace('/^\w+:\/\/[^\/]+/','',$requestUri);
							}
							else
								$requestUri=preg_replace('/^(http|https):\/\/[^\/]+/i','',$requestUri);
						}

						elseif(isset($_SERVER['ORIG_PATH_INFO']))  // IIS 5.0 CGI
						{
							$requestUri=$_SERVER['ORIG_PATH_INFO'];
							if(!empty($_SERVER['QUERY_STRING']))
								$requestUri.='?'.$_SERVER['QUERY_STRING'];
						}

						echo $requestUri;
					?>
	    			</div>
	    		</div>
	    	</div>
    	</main>