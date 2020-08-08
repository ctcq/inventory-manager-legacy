<?php
//Requests rows in current with missing entries
//Returns amount in html response header

require_once "db_guest.php";

$query = "SELECT * FROM bar_inventar.current WHERE (start IS NULL OR finish IS NULL) AND (price IS NOT NULL OR article = 'Bargeld')";

$result = $db->query($query);

$num = mysqli_num_rows($result);

//total revenue
$sum = 0;

//insert into current abrechnung
if($num === 0) {
    $money_start = (float)$_POST['$money_start'];
    $money_finish = (float)$_POST['$money_finish'];
    $money_diff = (float)$_POST['$money_diff'];
    $comment = $_POST['$comment'];

    //get all user article counts for calculating total revenue
    $query = "SELECT price, start, count, finish FROM current WHERE id!=0";
    $result = $db->query($query);

    //calc total revenue
    $sum = 0;
    while($row = $result->fetch_object()){
        $sum += $row->price * ($row->start + $row->count - $row->finish);
    }

    //copy name and timestamp from sessions table into current_abrechnung
    $query = "INSERT INTO current_abrechnung(name, timestamp) SELECT name, timestamp FROM sessions LIMIT 1";
    $db->query($query);

    //update current_abrechnung with values
    $query = "UPDATE current_abrechnung SET money_before='" . $money_start . "', money_after='" . $money_finish . "', balance='" . $money_diff . "', articles_money='" . $sum . "', comment = " . $comment;
    $db->query($query);

}
$db->close();
echo json_encode(array($num, $sum));

?>