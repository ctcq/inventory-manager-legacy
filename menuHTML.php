
	<div>
		<label id='liste-item' class='menu-item btn btn-secondary'> Bestände eintragen
			<!--<img id='button-liste' class='menu-icon' src='fa-icons/solid/clipboard-list.svg' title='Inventar bearbeiten'/>-->
			<input id='show-liste' type='radio' = name='menu'/>
		</label>
	</div>

	<div>
		<label id='order-item' class='menu-item btn btn-secondary'> Einkauf zusammenrechnen
			<!--<img id='button-order' class='menu-icon' src='fa-icons/solid/shopping-cart.svg' title='Einkauf abrechnen'/>-->
			<input id='show-order' type='radio' = name='menu'/>
		</label>
	</div>
	
	<div>
		<label id='lock-item' class='menu-item btn btn-secondary'> Eingabe sperren
			<!--<img id='button-lock' class='menu-icon' src='fa-icons/solid/lock-open.svg' title='Eingabe sperren-/entsperren'/>-->
			<input id='show-lock' type='radio' = name='menu'/>
		</label>
	</div>

	<div>
		<label id='comment-item' class='menu-item btn btn-secondary'> Kommentar anzeigen/schreiben
			<!--<img id='button-comment' class='menu-icon' src='fa-icons/solid/comment.svg' title='Kommentare ein-/ausblenden'/>-->
			<input id='show-comment' type='radio' name='menu'/>
		</label>
	</div>
<?php
	//Cheat Button for OBK
	if(isset($_POST['obk'])){
		echo <<<eof
		<div>
		    <label id="cheat-first-item" class="menu-item btn btn-light">Anfangsbestand automatisch füllen</label>
</div>
		
		<div>
			<label id='cheat-item' class='menu-item btn btn-light'>Alles automatisch ausfüllen</label>
		</div>
		
        <div>
			<label id='dbmaster-item' class='menu-item btn btn-secondary' onclick='window.location="http://localhost/db_verwaltung.html"'>Datenbank verwalten</a></label>
		</div>
eof;
	}
?>

	<div>
		<a id='help-item' class='menu-item btn btn-secondary' href='help.html' target='_blank'> Hilfe
			<!--<img id='button-help' class='menu-icon' src='fa-icons/solid/question-circle.svg' title='Hilfe'/>-->
			<input id='show-help' type='radio' = name='menu'/>
		</a>
	</div>
    <!---finish item removed to #tail-wrap--->
