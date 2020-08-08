<?php
	/*
	Contains all html for the table, main part of the program
	*/
	
	
	//Checks if a row contains data to be edited
	function isRowEditable($art){
		return !(
		$art === ""
		|| strtolower($art) === "leergut"
		);
	}
	
	/*Translates column name to readable name*/
	function translateCol($source){
		global $last_name;
		switch($source){
			case "article" : return "Artikel"; break;
			case "price" : return "Preis"; break;
			case "last" : return "Zählung von ".$last_name; break;
			case "start" : return "Anfangsbestand"; break;
			case "count" : return "OBK Änderung"; break;
			case "finish" : return "Endbestand"; break;
			case "revenue" : return "Einnahmen"; break;
			default : return "";
		}
	}
	
	//You can add entry in $header to add column ONLY on page. 
	//Values from new columns won't be saved. 
	// To add savable things edit sql in processlogin.php and ajax calls in liste.js and update.php accordingly
	
	//Requires $data from getListData.php
	$headers = array_merge(array(""), array_keys((array)$data[0]));
	
	//Prints header for a table including
	// label, table start, table header
	// table id = table-$tag
	//<table> needs to be closed before
	function printHeader($headers, $tag){			
			//Label
			$tag_trimmed = strtolower(str_replace(" ", "-", trim($tag)));
			echo "<h5 id='label-".$tag_trimmed."'>".$tag."</h5>";
			//Table start
            $table_class = $tag === 'money' ? "" : "article-table";
			echo "<table id='table-".$tag_trimmed."' class='container-fluid articles-table $table_class'><tr class='header-".$tag_trimmed."'>";
			//Print table head
			foreach($headers as $cell){
				echo "<th class='".$cell."-col'>".translateCol($cell)."</th>";
			}
			echo "</tr>";
	}
	//Print $data
	
	$tag = "";
	foreach($data as $row){
		//check if new table should begin
		if($row->id === '0'){
			if($row->tag){$tag = $row->tag;}
			printHeader($headers, $tag);
			$tag = strtolower(str_replace(" ", "-", trim($row->tag)));
		}else if($row->tag && $tag !== strtolower(str_replace(" ", "-", trim($row->tag)))){
			//Set new current tag
			$tag = $row->tag;
			echo "</table>";
			printHeader($headers, $tag);
			//Used for html ids
			$tag = strtolower(str_replace(" ", "-", trim($row->tag)));
		}
		
		echo "<tr id='row-".$row->id."' class='list tag-".$tag."' data-article='$row->article'>";
		$editable = isRowEditable($row->price);
		$row_id = $row->id;
		$input_step_size = $row->tag === 'money' ? .01 : 1;

		//Article image as first col
        $img_src = "img.php?name="
            .urlencode(
                $row->article
            );

        echo "<td class='img-col'><img class='article-img' src='$img_src'/></td>";
		foreach($row as $col=>$cell){
			//Print available data
			// Distinct between editable an non editable cells
			
			if(($col === 'start' || $col === 'finish' || $col === 'count') && $editable){
				echo "<td class='center ".$col."-col'><input id='".$col."-".$row_id."' class='".$col."-col center form-control' type='number' step=".$input_step_size." min=0 value='".$cell."'/></td>";
			}else if($col === 'price' && $editable){
				echo "<td id='price-".$row_id."' class='".$col."-col center money'>".$cell."</td>";
			}else if($col !== 'revenue'){
				echo "<td id='".$col."-".$row_id."' class='".$col."-col'>".$cell."</td>";
			}
		}
		//Add column for article revenue		
		echo "<td id='revenue-".$row_id."' class='money revenue-col center'>".$row->revenue."</td>";
		echo "</tr>";
	}
	/*
	//Add last row for total revenue
	echo "<tr id='last-row'>";
	foreach($headers as $cell){
		echo "<td class='".$cell."-col'></td>";
	}
	echo "</tr>";
	*/
	echo "</table>";
?>