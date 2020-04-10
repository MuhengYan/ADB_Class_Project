<?php 

session_start();
require 'header.php';
require 'config.php';

?>

<button type="button" class="btn"><a href="main.php">Back to All Countries</a></button> 
<br><br>
<?php 

	$country = $_GET['id'];
	echo "<h4>Country: ".$country."</h4>";

	$sql_country_details = "SELECT r.city as r_city, SUM(f.num_attack) as num_attack, r.country as country
			FROM region AS r, fact AS f 
			WHERE r.region_id=f.region_id and r.country = '".$country."'
			GROUP BY r.country, r.city 
			ORDER BY SUM(f.num_attack) DESC";
	echo "<br>";

	$results_show_table = $conn->query($sql_country_details);

	if($results_show_table)
	{
		echo "<table class='table' border=1px>";
		echo "<thead class='thead-dark'><tr>";
		echo "<th>City</th><th>Total Number of Attacks</th>";
		echo "</tr></thead><tbody>";
		while($row = $results_show_table->fetch_assoc())
		{
			echo "<td><a href='city.php?id=".$row['country']."&city=".$row['r_city']."'>".$row['r_city']."</a></td>";
			echo "<td>".$row['num_attack']."</td>";
			echo "</tr>";
		}
		echo "</tbody></table>";
	}

?>

<?php
require 'footer.php'
?>
