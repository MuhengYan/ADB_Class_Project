<?php 

session_start();
require 'header.php';
require 'config.php';


?>
<button type="button" class="btn"><a href="main.php">Back to homepage</a></button><br>
<h3>Filter Terrorism Attacks</h3><br>
<form name="search" action="filter.php?action=search" method="POST">
Date From: <input type="date" name="startdate" min="1993-01-01" max="1993-12-31">
To: <input type="date" name="enddate"  placeholder=" Select Date" min="1993-01-01" max="1993-12-31">
<br><br>
Region of Attack: <input type="text" name="region"><br><br>
Target: <input type="text" name="target"><br><br>
Attack Type: <input type="text" name="attack"><br><br>
Weapon Type: <input type="text" name="weapon"><br><br>
Gang Name: <input type="text" name="gname"><br><br>


<input class="btn btn-primary" type="submit" value="submit">
</form>

<?php
if ($_GET['action'] == 'search') {
    $_startdate = $_POST["startdate"];
    $_enddate = $_POST["enddate"];
    $_weapon = $_POST["weapon"];
    $_attack = $_POST["attack"];
    $_region = $_POST["region"];
    $_gname = $_POST["gname"];
    $_target = $_POST["target"];

    $sql_search= "SELECT * FROM fact as f, attack as a, gname as g, region as r, target as t, weapon as w, 
    (SELECT date_id, CAST(CONCAT(iyear, '-', imonth, '-', iday) AS date) as dt FROM date) as d
    WHERE f.attack_id = a.attack_id
    AND f.date_id = d.date_id
    AND f.gname_id = g.gname_id
    AND f.region_id = r.region_id
    AND f.target_id = t.target_id
    AND f.weapon_id = w.weapon_id";

    echo "<h4> Search Results for: ";
    if ( $_startdate!=null && $_enddate!=null){
       $sql_search .=" AND d.dt BETWEEN '$_startdate' AND '$_enddate'";
       echo "<h6> Date between ".$_startdate." and ".$_enddate."</h4>"; 
    
    }
    if ( $_region!=null){
        $sql_search .=" AND r.region = '$_region'";
        echo "<h6> Region of Attack is ".$_region."</h4>"; 
     
    }

    if ( $_target!=null){
        $sql_search .=" AND t.target_type = '$_target'";
        echo "<h6> Target is ".$_target."</h4>"; 
    }

    if ( $_attack!=null){
        $sql_search .=" AND a.attack_type = '$_attack'";
        echo "<h6> Attack Type is ".$_attack."</h4>"; 
    }

    if ( $_weapon!=null){
        $sql_search .=" AND w.weapontype = '$_weapon'";
        echo "<h6> Weapon Type is ".$_weapon."</h4>";
        
    }
    if ( $_gname!=null){
        $sql_search .=" AND g.gname = '$_gname'";
        echo "<h6> Gang name is ".$_gname."</h4>";
    }

    
    echo "</h4>";

    $sql_search .=" ORDER BY d.dt";
    
        $result_search = $conn->query($sql_search);

        if($result_search)
        { 
            echo "<table class='filtertable' border=1px>";
            echo "<tr>";
            echo "<td>Date</td>";
            echo "<td>Region</td>";
            echo "<td>Country</td>";
            echo "<td>City</td>";
            echo "<td>Target</td>";
            echo "<td>Attack Type</td>";
            echo "<td>Weapon Type</td>";
            echo "<td>Gang Name</td>";
            echo "<td>Success</td>";
            echo "<td>Number of Attacks</td>";

            echo "</tr>";
			while($row = $result_search->fetch_assoc())
			{  
                echo "<td>".$row['dt']."</td>";
                echo "<td>".$row['region']."</td>";
                echo "<td>".$row['country']."</td>";
                echo "<td>".$row['city']."</td>";
                echo "<td>".$row['target_type']."</td>";
                echo "<td>".$row['attack_type']."</td>";
                echo "<td>".$row['weapontype']."</td>";
                echo "<td>".$row['gname']."</td>";
                if($row['success']==1.0){
                    echo "<td> Yes </td>";
                }
                else{
                    echo "<td>No</td>";
                }
                echo "<td>".$row['num_attack']."</td>";
                echo "</tr>";
            }
            echo "</table>";

        }   
}

?>

<?php
require 'footer.php'
?>
