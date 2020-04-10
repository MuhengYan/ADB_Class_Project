<?php 

session_start();
require 'config.php';

?>

<?php

	$country = $_GET['id'];
	$city = $_GET['city'];

	echo $city;
	echo "<br>";

	$sql_all_attacks = "select d.imonth as month, d.iday as day, d.iyear as year, num_attack as num_attack from (
	select r.country as country, r.city as city, f.date_id as date_id, f.num_attack as num_attack from fact as f, region as r where r.region_id = f.region_id and r.country = '".$country."' and r.city = '".$city."') as subquery, date as d where subquery.date_id = d.date_id";

	// $sql_all_attacks = "SELECT ConcatDate.dt as display_date, ConcatDate.n_attack as num_attack
	// 	FROM 
	// 		(SELECT CAST(CONCAT(d.iyear, '-', d.imonth, '-', d.iday) AS datetime) as dt, SUM(f.num_attack) as n_attack, r.country, r.city
	// 		FROM date as d, fact as f, region as r
	// 		WHERE f.date_id=d.date_id
	// 		GROUP BY  CAST(CONCAT(d.iyear, '-', d.imonth, '-', d.iday) AS datetime) HAVING r.country = '".$country."' AND r.city = '".$city."' ) AS ConcatDate
	// 	WHERE ConcatDate.dt > CAST('1993-1-1' AS datetime)
	// 	AND ConcatDate.dt < CAST('1994-1-1' AS datetime)";
	
	$results_show_table = $conn->query($sql_all_attacks);

	if($results_show_table)
	{
		echo "<table class='date_specific_details' border=1px>";
		echo "<tr>";
		echo "<td>Month</td><td>Day</td><td>Year</td><td>Number of Attacks</td>";
		echo "</tr>";

		while($row = $results_show_table->fetch_assoc())
		{
			echo "<td>".$row['month']."</td>";
			echo "<td>".$row['day']."</td>";
			echo "<td>".$row['year']."</td>";
			echo "<td>".$row['num_attack']."</td>";
			echo "</tr>";
		}
		echo "</table>";
	}
?>