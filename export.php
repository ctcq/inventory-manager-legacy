<?php
/*
 *  1) Exorts sessions finished to csv file
 *  2) Exports data from current to $folder
 */
require_once "db.php";
//Export  sessions_finished as csv
$folder = "C:\\Barlisten\\";

$query = "SELECT * FROM sessions_finished ORDER BY timestamp DESC LIMIT 1";
$result = $db->query($query);

$all_exists = file_exists($folder."alles.csv");

$all_file = fopen($folder."alles.csv", 'a');

//Print header in the first line if alles.csv has to be created

if(!$all_exists){
    $header = array();
    while($hinfo = $result->fetch_field()){
        $header[] = $hinfo->name;
    }
    fputcsv($all_file, $header, ",", "\"");
}

//Print newest line from sessions_finished
$row = $result->fetch_assoc();
fputcsv($all_file, $row, ",", "\"");

// flag whether comment was set
$comment_set = !$row['comment'] == null;

fclose($all_file);

//Get name from sessionsfinished with highest id
$query = "SELECT timestamp, name, money_before, money_after, articles_money FROM sessions_finished ORDER BY timestamp DESC";
$result = $db->query($query);

$row = $result->fetch_assoc();

$id = $row['timestamp'];
$name = trim($row['name']);
$money_diff = $row['money_after'] - $row['money_before'] - $row['articles_money'];

//Filename without flags and ending
$filename = str_replace(" ", "", $id."_".$name);
// replace ':' with '-'
$filename = str_replace(":", "-", $filename);

//Export current as csv, check if last and start cols match everywhere -> $last_diff = false
$last_diff = false;
$query = "
  SELECT 
    article AS 'Artikel' ,
    price AS 'Preis' ,
    `last` AS 'Letzter Endbestand',
    start AS 'Anfangsbestand',
    `count` AS 'OBK Veränderung',
    finish AS 'Endbestand' 
  FROM current";
$result = $db->query($query);

$data = array();

$headers = array();
while($hinfo = $result->fetch_field()){
    $headers[] = $hinfo->name;
}

// set flag if last and start dont match
while($row = $result->fetch_assoc()){
    if($row['Letzter Endbestand'] !== $row['Anfangsbestand']){
        $last_diff = true;
    }

    $data[] = $row;
}

/*
 * append "CLEAN" to filename if
 *
 * last and start match everywhere
 * AND
 * earned money is at least the sum of all sold articles
*/

if(!$last_diff && $money_diff>=0){$filename.="#CLEAN";}
if($comment_set){$filename.="#COMMENT";}

// save csv as $file
$file = fopen($folder.$filename.".csv", "a");

//Print headers
fputcsv($file, $headers, ",", "\"");
//Print data
foreach ($data as $row){
    if(is_null($row['OBK Veränderung'])){
        $row['OBK Veränderung'] = '0';
    }
    if(!fputcsv($file, $row, ",", "\"")){
        die("Speichern fehlgeschlagen :(");
    }
}

fclose($file);