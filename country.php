<?php 

session_start();
require 'config.php';

?>


<?php 

	echo "<td><a href='main.php'>"."Back to All Countries"."</a></td>";
	echo "<br>";
	echo "<br>";
	$country = $_GET['id'];
	echo "$country";

	$sql_country_details = "SELECT r.city as r_city, SUM(f.num_attack) as num_attack, r.country as country
			FROM region AS r, fact AS f 
			WHERE r.region_id=f.region_id and r.country = '".$country."'
			GROUP BY r.country, r.city 
			ORDER BY SUM(f.num_attack) DESC";
	echo "<br>";

	$results_show_table = $conn->query($sql_country_details);

	if($results_show_table)
	{
		echo "<table class='country_specific_details' border=1px>";
		echo "<tr>";
		echo "<td>City</td><td>Total Number of Attacks</td>";
		echo "</tr>";
		while($row = $results_show_table->fetch_assoc())
		{
			echo "<td><a href='city.php?id=".$row['country']."&city=".$row['r_city']."'>".$row['r_city']."</a></td>";
			echo "<td>".$row['num_attack']."</td>";
			echo "</tr>";
		}
		echo "</table>";
	}

?>