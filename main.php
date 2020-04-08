<?php 

session_start();
require 'header.php';
require 'config.php';

?>

<br>
<h3>Confirmed Cases of Terrorist Attacks in Year 1993 by Country</h3>
<p>There are 709 terrorist attacks reported in 63 countries in the year 1993.</p><br>
<p>For more information on the specifics of each attack, click below to search for attacks by date, region, target, attack type, weapon type, and gang name.</p>
<button type="button" class="btn"><a href="filter.php">Search</a></button> 

<?php

	$sql_country_table = "SELECT AG.country, SUM(AG.n_attack) AS sum_attack, AVG(AG.n_attack) as avg_attack, COUNT(DISTINCT AG.city) as count_city
    FROM 
        (SELECT r.country, r.city, SUM(f.num_attack) as n_attack 
        FROM region AS r, fact AS f 
        WHERE r.region_id=f.region_id GROUP BY r.country, r.city) AS AG 
    GROUP BY AG.country 
    ORDER BY SUM(AG.n_attack) DESC";


    $result_show_country_table = $conn->query($sql_country_table);

    if($result_show_country_table)
        
		{
            echo "<br><br> <h4></h4>";

            echo "<table class='table' border=1px>";
            echo "<thead class='thead-dark'><tr>";
            echo "<th> Country </th>";
            echo "<th> Total Number of Attacks </th>";
            echo "<th> Number of Cities Attacked </th>";
            echo"<th> Average Number of Attack Per City </th>";
            echo "</tr></thead><tbody>";
			while($row = $result_show_country_table->fetch_assoc())
			{  
                echo "<td><a href='country.php?id=".$row['country']."'>".$row['country']."</a></td>";
                echo "<td> ".$row['sum_attack']." </td>";
                echo "<td> ".$row['count_city']." </td>";
                echo "<td> ".$row['avg_attack']." </td>";
                echo "</tr>";
            }
            echo "</tbody></table>";

		}

?>

<?php
require 'footer.php'
?>
