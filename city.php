<?php 

session_start();
require 'header.php';
require 'config.php';

?>
<button type="button" class="btn"><a href="main.php">Back to All Countries</a></button> 
<br><br>

<?php

	$country = $_GET['id'];
	$city = $_GET['city'];

	echo "<button type='button' class='btn'><a href='country.php?id=".$country."'> Back to ".$country."</a></button>";
	echo "<br>";

	echo "<br><h4>City: ".$city."</h4><br>";

	$sql_all_attacks = "select d.imonth as month, d.iday as day, d.iyear as year, num_attack as num_attack from (
	select r.country as country, r.city as city, f.date_id as date_id, f.num_attack as num_attack from fact as f, region as r where r.region_id = f.region_id and r.country = '".$country."' and r.city = '".$city."') as subquery, date as d where subquery.date_id = d.date_id";

	$results_show_table = $conn->query($sql_all_attacks);

	if($results_show_table)
	{
		echo "<table class='table' border=1px>";
		echo "<thead class='thead-dark'><tr>";
		echo "<th>Month</th><th>Day</th><th>Year</th><th>Number of Attacks</th>";
		echo "</tr></thead><tbody>";

		while($row = $results_show_table->fetch_assoc())
		{
			echo "<td>".$row['month']."</td>";
			echo "<td>".$row['day']."</td>";
			echo "<td>".$row['year']."</td>";
			echo "<td>".$row['num_attack']."</td>";
			echo "</tr>";
		}
		echo "</tbody></table>";
	}
?>

<?php
require 'footer.php'
?>
