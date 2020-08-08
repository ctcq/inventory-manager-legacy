<!-- Contains HTML for Order Window -->

<h1> Bestellung #0 </h1>

<div id=order-select-wrap>
	<?php
	
		//Prints item selector
		foreach($data as $row){
			if($row->price){
				echo <<<eof
					<div id='order-item-{$row->id}' class='order-item'>
						<p id='order-item-{$row->id}-name'>{$row->article}</p>
						<p id='oder-item-{$row->id}-price'>{$row->price}</p>
					</div>
eof;
			
			}
		}
	?>
	
</div>