var updateTime = function(){
	let x = new Date().toLocaleString();
	xs = x.split(",");
	x = xs[1] + "<br>" + xs[0];
	$("#clock").html(x);
};

//Flag if order alert was shown once
var $order_alert_shown = false;

//Hightlights a cell if wrong number format
// Parameter: jQuery dom object of call input
var show_wrong_format = function($cell){
	//get parent element (the actual table cell)
	$($cell).addClass("wrong-input-format");
}

//Hightlights a cell if update went ok
// Parameter: jQuery dom object of call input
var show_update_ok = function($cell){
}

//Sends ajax to update.php with GET parameters indicating where and what to update
//update.php should check against injection while this function checks for datatype;
var send_update_request = function($cell_object){
	
	//Retrieve relevant info from cell object
	// id and col are contained in object's id
	// i.e. id = 'input-start-23'
	$val = $cell_object.val();
	$col =$cell_object.attr('id').split("-")[0];
	$id = $cell_object.attr('id').split("-")[1];
	
	//Check for correct number format
	if(($val !== ""
		//If true if value postive integer OR value positive float and id = 0
		&& ($val.match("^[0-9]+$") 
			|| ($val.match("^[\+]?[0-9]+[\.\,][0-9]{0,2}$") 
				&& $id === '0')
			))
	// if true when count-col and value is integer
	|| ($val.match("^[\+\-]?[0-9]+$") && $col === 'count')
		){
	
	$($cell_object).removeClass("wrong-input-format");
	
	$.ajax({
		url : "update.php?id="+$id+"&col="+$col+"&val="+$val,
		statusCode :{
			400 : function(){
				show_db_error($cell_object);
			},
			200 : function(){
				show_update_ok($cell_object);
			}
		},
		success : (function(data, json){
				$("#revenue-"+$id).html(data);
		})
	});
	
		}else{
			show_wrong_format($cell_object);
		}
	
}

class Order{
	
	constructor(){
		this.articles = [];
		this.amounts = [];
		this.update();
	}
	
	add_item($id, $amount){
		if(this.articles.includes($id)){
			this.amounts[this.articles.indexOf($id)]+= $amount;
		}else{
			this.articles.push($id);
			this.amounts.push($amount);
		}
		this.update();
	}
	
	remove_item($id, $amount){
		$index = this.articles.indexOf($id);
		
		if(this.articles.includes($id)){
			this.amounts[this.articles.indexOf($id)]-= $amount;
		}
		this.update();
	}
	
	set_amount($id, $amount){
		this.amount[$id] = $amount;
	}
	
	// set count to 0 and update, wont display in html
	delete_row($id){
		this.amounts[$id] = 0;
		this.update();
	}
	
	calc_total(){
		let $sum = 0;
		for($i=0; $i<this.articles.length; $i++){
			$sum+= $data[this.articles[$i]].price * this.amounts[$i];
		}
		return $sum;
	}
	
	// updates the order display in #order-wrap
	update(){
		let $sum = 0;
		if(true /*this.articles.length > 0*/){
			let $string = "<tr><th>Artikel</th><th>Anzahl</th><th>Preis</th><th>Summe</th><th></th></tr>";
			for($i=0; $i<this.articles.length; $i++){
				let $name = $data[this.articles[$i]].article;
				let $amount = this.amounts[$i];
				let $price = $data[this.articles[$i]].price;
				let $revenue = (parseFloat($price)*parseInt($amount));
				$sum += $revenue;
				//Adds table row to string. columns: article, price, amount, cost, delete_row
				// Very messy....
				// label for delete icon has onclick function $order.delete_row bound
				if($amount != 0){
					$string+= "<tr id='order-row-"+$i+"' class='order-row'><td>"+ $name +"</td><td><input id='count-for-"+$i+"' class='order-count form-control' type='number' step= 1 value='"+ $amount +"'/></td><td class='money'>"+ $price +"</td><td id='order-sum-"+$i+"' class='money order-sum'>"+ $revenue.toFixed(2) +"</td><td><img onclick='$order.delete_row("+$i+")' class='order-row-delete' src='fa-icons/solid/times-circle.svg'/></td></tr>";
				}
			}
			$string += "<tr><td><b>Zu zahlen:</b></td><td></td><td></td><td id='order-total' class='money'>"+ $sum.toFixed(2) +"</td></tr>";
			
			//Buttons for counting / not counting this order and finishing it
			$string += "<tr><td></td><td><img id='pay-button' src='fa-icons/solid/money-bill-alt.svg' title='Bezahlt und Einkauf korrekt?'/></td><td><img id='storno-button' title='Storno' src='fa-icons/solid/times-circle.svg'/></td><td></td></tr>";
			$("#order-table").html($string);
			
			//Click on 
			//Increase by clicking on list item is bound on pageload
			$(".order-count").on("keyup change paste", function(){
				if($(this).val() === ""){return;}
				
				let $id = $(this).attr('id').split("-")[2].trim();
				let $value = $(this).val();
				let $price = $data[$order.articles[$id]].price;
				
				$order.amounts[$id] = $(this).val();
				let $sum = ($order.amounts[$id]*$price);
				
				//update the sum for this row
				$("#order-sum-"+$id).html($sum.toFixed(2));
				//And the sum at the end of the list
				$("#order-total").html($order.calc_total().toFixed(2));
			});
			
			//count and storno buttons
			$("#pay-button").click(function(){
				for($i=0; $i<$order.articles.length; $i++){
					if($("#count-for-"+$i)){
						$data[$order.articles[$i]].count = $("#count-for-"+$i).val();
					}
				}
				$order = new Order();
				$order.update();
			});
			
			$("#storno-button").click(function(){
				$order = new Order();
				$order.update();
			});
		}
	}
}

$(document).ready(function(){

	updateTime();
	setInterval(updateTime, 1000);

/* Menu label functionality */
	$(":radio").change(function(){
		//Remove all classes, then add this id as class
		$("#content-wrap").removeClass();
		$("#content-wrap").addClass("container");
		$("#content-wrap").addClass($(this).attr('id'));
	});

	
// bind the order button
	$("#order-item").click(function(){
		$order = new Order();
		$order.update();

		if(!$order_alert_shown){
			$order_alert_shown = true;
			alert("Achtung! Der Einkauf wird nach Verlassen dieser Ansicht gelöscht!");
		}
	});
	
// clicking on a table row in table-wrap adds 1 item when in order view
$("tr.list").click(function(){
	if($(this).parents(".show-order").length){
		$row_id = $(this).attr("id").split("-")[1];
		$order.add_item($row_id, 1);
		$order.update();
	}
});

//bind finish button
$("#finish-item").click(function(){	
	$money_start = parseFloat($("#start-0").val());
	$money_finish = parseFloat($("#finish-0").val());
	$money_diff = $money_start-$money_finish;
	$comment = $("#comment").val();
	
	//Check if every every row has a saved entry in db
	$.ajax({
		url : 'checkMissing.php',
		method : 'POST',
		data : {$money_start, $money_finish, $money_diff, $comment},
		success : function(data){

			data = JSON.parse(data);
			// Response should contain a number as string of amount of articles not set correctly
			if(data[0] !== 0){
				//Show error
				$("#finish-table").html("<tr><th>"+data[0]+" Artikel wurden noch nicht gezählt.</th></tr>");
				//Hide end and abort buttons
				$("#end-button").hide();
				$("#abort-button").hide();
				return;
			}else{
				$("#end-button").show();
				$("#abort-button").show();
			}
			
			// write data in finish table

			let $string = "<tr><td>Eingenommenes Geld</td><td class='money'>"+$money_diff.toFixed(2)+"</td></tr>";
			$string += "<tr><td>Einnahmen Artikel</td><td class='money'>"+data[1]+"</td></tr>";
			$string += "<tr><td class='bold'>Differenz</td><td id=''' class='money bold'>"+($money_diff - data[1])+"</td></tr>";
			$string += "<tr><td></td><td></td></tr>";
			$string += "<tr><td>Kühlschrank aufgefüllt</td><td><input class='form-control' type='checkbox'/></td></tr>";
			$string += "<tr><td>Bar gefegt</td><td><input class='form-control' type='checkbox'/></td></tr>";
			$string += "<tr><td>Theke gewischt</td><td><input class='form-control' type='checkbox'/></td></tr>";
			$string += "<tr><td>Vollen Müll rausgebracht</td><td><input class='form-control' type='checkbox'/></td></tr>";
			$string += "<tr><td>Die Kasse stimmt</td><td><input class='form-control' type='checkbox'/></td></tr>";

			$("#finish-table").html($string);
		}
	});
});
// Bind abort button to show liste
$("#abort-button").click(function(){$("#liste-item").click()});

//Bind end button to finish Bardienst
$("#end-button").click(function(){
	if(confirm("Möchtest du den Bardienst wirklich beenden? Du kannst die Liste dann nicht mehr bearbeiten.")){
		$.ajax({
			url : 'finish.php',
			success : function(){
				alert("Liste wurde gespeichert!");
				//Back to Login
				window.location.replace("http://localhost/login.php");
			}
		});
	}
});

// Bind send_update_request function to inputs' blur
var $val = 0;
var $html_id = "";
for($i=0; $i<$list_length; $i++){
	//start column
	$html_id = "#start-"+$i;
	if($($html_id)){
		$($html_id).blur(function(){
			// Send update
			send_update_request($(this));

			//check if same value as last
			$id = $(this).attr('id').split("-")[1];

			if($data[$id]){
				if($(this).val() != $data[$id].last){
					$(this).parent().parent().removeClass("last-same");
					$(this).parent().parent().addClass("last-diff");
				}else{
					$(this).parent().parent().removeClass("last-diff");
					$(this).parent().parent().addClass("last-same");
				}
			}
			// Make sure finish view reloads when you enter new end bargeld
			if($id === "0" && $("#content-wrap").hasClass("show-finish")){
				$('#finish-item').click();
			}
		});
	}else{
		continue;
	}
	// also for the finish column
	$html_id = "#finish-"+$i;
	if($($html_id)){
		$($html_id).blur(function(){
			send_update_request($(this));
			// Make sure finish view reloads when you enter new end bargeld
			if($id === "0" && $("#content-wrap").hasClass("show-finish")){
				$('#finish-item').click();
			}
		});
	}
}
/*
$(".articles-table tr").hover(
	//Hover in
	function(e){
		let article_name = $(this).data('article').replace(" ", "_");
		let img_src = "img.php?name="+article_name;
		$("<div id='img-wrap'><img class='hover-image' src='"+img_src+"'/></div>")
			.css({
				"height": 25+"px",
				"position": "fixed",
				"left" : "0px",
				"top" : e.screenY + "px"
			})
			.appendTo(document.body);
	},
	//Hover out
	function(e){
			$("#img-wrap").remove();
	});
	*/
// Update comment on blur
	$("#comment").blur(function(){
		$comment = $(this).val();

		$.ajax({
			url : "update_comment.php?comment="+$comment,
			statusCode :{
				400 : function(){
					alert("Speichern nicht möglich.\\n Bitte neu laden.");
				}
			}
		});
	});

//Show/hide count column whether there is data or not
	$obk_change = false;

	for($i=0; $i<$list_length; $i++){
		if($("#count-"+$i)){
			if($("#count-"+$i).val() !== "" && $("#count-"+$i).val() !== "0"){
				$obk_change = true;
			}
		}
	}
	if($obk_change){
		alert("Der OBK hat auf dieser Liste den Bestand geändert! " +
			"\nDie geänderten Werte sind in der Spalte 'OBK Änderung' eingetragen und werden auf deinen Anfangsbestand addiert.")
	}
	if(!$obk_change && !$obk_login){
		$(".count-col").hide();
	}
	if(!$obk_login){
		$("input.count-col").prop("disabled", true);
	}else{
		$("input.start-col").prop("disabled", true);
		$("input.finish-col").prop("disabled", true);
		$("input.count-col").blur(function(){
			send_update_request($(this));
		});
		/* Take values from last col and insert them into start and finish col and save1*/
		$("#cheat-item").click(function(){
			if(confirm("Bist du sicher, dass du den gesamten Plan neu beschreiben möchtest?<br> Das kann nicht mehr rückgängig gemacht werden.")){
				for($i=0; $i< $list_length; $i++){
					if($("#start-"+$i) && $("#finish-"+$i) && $("#last-"+$i)){
						$("#start-"+$i).val($("#last-"+$i).html());
						$("#start-"+$i).blur();
						$("#finish-"+$i).val($("#last-"+$i).html());
						$("#finish-"+$i).blur();
					}
				}
			}
		});

		$("#cheat-first-item").click(function(){
			if(confirm("Bist du sicher, dass du den gesamten Plan neu beschreiben möchtest?<br> Das kann nicht mehr rückgängig gemacht werden.")){
				for($i=0; $i< $list_length; $i++){
					if($("#start-"+$i) && $("#finish-"+$i) && $("#last-"+$i)){
						$("#start-"+$i).val($("#last-"+$i).html());
						$("#start-"+$i).blur();
					}
				}
			}
		});
	}
	
// Bind search input functionality
	$("#search").on("change paste keyup", function(){
		//Search for article with matching name
		$input = $("#search").val().toLowerCase();
		$count = 0;
		//Show last tag
		$tag = "";
		//stores tags with match
		$tags = [];
		for($i=0; $i<$list_length; $i++){
			if($data[$i].tag && $data[$i].tag !== $tag){
				$tag = $data[$i].tag.trim();
			}
			
		//If true hide row
			if($input && !$data[$i].article.toLowerCase().match("^.*("+$input.toLowerCase()+").*$")){
				$("#row-"+$i).hide(400);
			//Else show row
			}else{
				$("#row-"+$i).show(400);
				$tags.push($tag.toLowerCase().replace(" ", "-"));
				$count++;
			}
		}
		
		// Hide/show table + header if nescessary
		for($j=0; $j<$tag_list.length; $j++){
			$tag_from_list = "";
			if($tag_list[$j]){
				$tag_from_list = $tag_list[$j].trim();
			}
			if($tags.includes($tag_from_list)){
				$("#label-"+$tag_from_list).show(400);
				$("#table-"+$tag_from_list).show(400);
			}else{
				$("#label-"+$tag_from_list).hide(400);
				$("#table-"+$tag_from_list).hide(400);
			}
		}
		/**
		Show alert when no entry is found
		**/
		if($tags.length === 0){
			$("#search-result").show(400);
		}else{
			$("#search-result").hide(400);
		}
	});
	
});