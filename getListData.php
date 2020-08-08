<?php
	
	require_once "db.php";
	
	//Read all data from db as jsonstring $data
	$query = "SELECT * FROM current";
	$data = array();
	$result = $db->query($query);

	while($row = $result->fetch_object()){
        $res = $row->price * ($row->start + $row->count - $row->finish);
        if(!is_nan($res)){
		$row->revenue = str_replace(".",",", number_format($res,2, ",", ""));
			$data[] = $row;
		}else{
			$data[] = "";
		}
	}
	
	//Read last name from last_comment
	$query = "SELECT * FROM last_comment";
	$result = $db->query($query);
	$last_data = $result->fetch_object();
	$last_name = $last_data->name;
	
	$db->close();
	$result->close();
	
	$tag_list = array();
	//Get tag list
	foreach($data as $row){
		if(!in_array($row->tag, $tag_list)){
			$tag_list[] = strtolower(str_replace(" ", "-", trim($row->tag)));
		}
	}
	
?>