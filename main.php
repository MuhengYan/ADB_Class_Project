<?php 

session_start();
require 'config.php';

?>

<?php

	$sql_country_table = "SELECT AG.country, SUM(AG.n_attack) AS sum_attack, AVG(AG.n_attack) as avg_attack, COUNT(DISTINCT AG.city) as count_city
    FROM 
        (SELECT r.country, r.city, SUM(f.num_attack) as n_attack 
        FROM region AS r, fact AS f 
        WHERE r.region_id=f.region_id GROUP BY r.country, r.city) AS AG 
    GROUP BY AG.country 
    ORDER BY SUM(AG.n_attack) DESC ";
	
	$result_show_country_table = $conn->query($sql_country_table);
	if($result_show_country_table)
		{
            echo "<table class='countrytable' border=1px>";
            echo "<tr>";
            echo "<td>Country</td><td>Total Number of Attacks</td><td>Number of Cities Attacked</td><td>Average Number of Attack Per City</td>";
            echo "</tr>";
			while($row = $result_show_products->fetch_assoc())
			{
                echo "<td>".$row['country']."</td>";
                echo "<td>".$row['sum_attack']."</td>";
                echo "<td>".$row['avg_attack']."</td>";
                echo "<td>".$row['count_city']."</td>";
                echo "</tr>";
            }
            echo "</table>";

		}

?>