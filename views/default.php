<!doctype HTML>
<html>
<head>
	<title><?= BLOG_NAME ?></title>
	
	<style>
		* {
		    transition: all 0.6s;
		}
		
		html {
		    height: 100%;
		}
		
		body {
		    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
		    color: #888;
		    margin: 0;
		}
		
		#main {
		    display: table;
		    width: 100%;
		    height: 100vh;
		    text-align: center;
		}
		
		.error {
			display: table-cell;
			vertical-align: middle;
		}
		
		.error h1 {
			font-size: 50px;
			display: inline-block;
		}
		
		.error a {
			color: #419cff;
			font-weight: bold;
			text-decoration: none;
		}
	</style>
</head>
<body>
	<div id="main">
		<div class="error">
	    	<h1><?= BLOG_NAME ?></h1>
	    	<p>Powered by <a href="https://github.com/adamgreenough/nicholas">Nicholas</a> ✨</p>
		</div>
	</div>
</body>
</html>