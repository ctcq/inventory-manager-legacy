 <html>
<head>
	<meta charset='UTF-8'>
	
	<!-- Bootstrap -->
	<link rel='stylesheet' type='text/css' href='bootstrap-4.1.3-dist/css/bootstrap.css'/>
	
	<!--Css Stylesheets-->
	<link rel='stylesheet' type='text/css' href='menu.css'/>
	<link rel='stylesheet' type='text/css' href='layout.css'/>
	<link rel='stylesheet' type='text/css' href='content.css'/>
	<link rel='stylesheet' type='text/css' href='markup.css'/>
	
	<?php
	//Contains all data from db for current list, whether its a new one or not
	require "getListData.php";
	?>
	
	<!-- some data for js to use -->
	<script>
		<?php
			echo "var \$list_length = ".count($data).";";
			if(isset($_POST['obk'])){
				echo "var \$obk_login = true;";
			}else{
				echo "var \$obk_login = false;";
			}
			
			echo "var \$data = ".json_encode($data).";";
			echo "var \$tag_list = ".json_encode($tag_list).";";
		?>
		
		var $order = null;
		
	</script>
	
	<script src='jQuery.js'></script>
	<script src='liste.js'></script>
	
</head>

<body class='bg-light'>

<div id='menu-wrap' class='navbar nav-tabs bg-dark'>
<?php require 'menuHTML.php';?>
</div>
<div id='content-wrap' class='container col-xs-2'>
	<?php
		echo "<div id='table-wrap' class='table table-striped table-hover'>";
		echo "<div id='search-result'><span>Gesuchter Artikel wurde nicht gefunden.</span></div>";
		require "tableHTML.php";
		echo "</div>";
	?>

	<div id='order-wrap' class='container-fluid'>
		<h5 id='order-header'>Warenkorb</h5>
	<!--- Table gets filled by js --->
		<table id='order-table'>
		</table>
	</div>
	
	<!--- inner html is generated by clicking on menu icon --->
	<div id='finish-wrap' class='container'>
		<table id='finish-table' class='table table-border'>
		</table>
        <p class="btn btn-link" onclick="$('#comment-item').click()">Kommentar an den OBK schreiben</p>
		<div id='finish-buttons-wrap'>
			<img id='end-button' src='fa-icons/solid/check-circle.svg' title='Bardienst beenden'/>
			<img id='abort-button' src='fa-icons/solid/times-circle.svg' title='Abbrechen'/>
		</div>
	</div>

	<div id='comment-wrap' class='container'>
        <div class='container'>
            <h3>Kommentar an den OBK</h3>
            <textarea id='comment' class='form-control'></textarea>
        </div>
    </div>
</div>
<!--- Tail-wrap with search input, finish-button and clock --->
<!---<img id='search-icon' src='fa-icons/solid/search.svg'/> Search icon an input field --->
<div id="tail-wrap" class="ontainer-fluid bg-dark">
    <div id="tail-content-wrap" class="navbar">
        <!---- finish button---->
        <label id='finish-item' class='menu-item btn btn-primary'> Abrechnung ansehen und <br> Bardienst beenden
            <!--<img id='button-finish' class='menu-icon' src='fa-icons/solid/check-circle.svg' title='Bardienst beenden'/>-->
            <input id='show-finish' type='radio' = name='menu'/>
        </label>

        <input id='search' class='form-control menu-item' placeholder="Artikel suchen"/>

        <span id="clock" class="text-center menu-item"></span>
    </div>
</div>
</body>

</html>