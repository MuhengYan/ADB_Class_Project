<?php 

session_start();
require 'header.php';
require 'config.php';


?>
<button type="button" class="btn"><a href="main.php">Back to homepage</a></button><br>

<br>
<div class="row">
    <div class="col">
    <h3>Search For Specific Terrorism Attacks</h3><br>

    <form name="search" action="filter.php?action=search" method="POST">
    <div class="form-group">
    Date From: <input type="date" name="startdate" min="1993-01-01" max="1993-12-31">
    To: <input type="date" name="enddate"  placeholder=" Select Date" min="1993-01-01" max="1993-12-31">
    <br>
    </div>
    <div class="form-group">
    Region of Attack: <input type="text" name="region"><br>
    </div>
    <div class="form-group">
    Target: <input type="text" name="target"><br>
    </div>
    <div class="form-group">
    Attack Type: <input type="text" name="attack"><br>
    </div>
    <div class="form-group">
    Weapon Type: <input type="text" name="weapon"><br>
    </div>
    <div class="form-group">
    Gang Name: <input type="text" name="gname"><br>
    </div>
    <input class="btn btn-primary" type="submit" value="Search"><br>
    </form><br>
    </div>

    <div class="col">
    <h3>List Number of Attacks by Category</h3>
    <form name="bycategory" action="filter.php?action=bycategory" method="POST">
    <div class="form-group">
    <label for="cars">Select Category</label>
    <select name="category">
                <option value="Date">Date</option>
                <option value="Attack Type">Attack Type</option>
                <option value="Region">Region of Attack</option>
                <option value="Target">Target</option>
                <option value="Weapon Type">Weapon Type</option>
                <option value="Gang Name">Gang Name</option>
    </select>
    </div>
    <input class="btn btn-primary" type="submit" value="Search"><br>
    </form>
    </div>
</div>



<?php

if ($_GET['action'] == 'bycategory') {
    $_category = $_POST['category'];   
    $_sql=""; 

    if($_category == 'Date'){
        $_sql = "SELECT d.dt as class, SUM(f.num_attack) as num_attack FROM fact as f, (SELECT date_id, CAST(CONCAT(iyear, '-', imonth, '-', iday) AS date) as dt FROM date) as d
        WHERE f.date_id = d.date_id
        GROUP BY d.dt
        ORDER BY num_attack DESC";
    }
    else if($_category == 'Attack Type'){
        $_sql = "SELECT a.attack_type as class, SUM(f.num_attack) as num_attack FROM fact as f, attack as a
        WHERE f.attack_id = a.attack_id
        GROUP BY a.attack_type
        ORDER BY num_attack DESC";
    }
    
    else if($_category == 'Region'){
        $_sql = "SELECT r.region as class, SUM(f.num_attack) as num_attack FROM fact as f, region as r
        WHERE f.region_id = r.region_id
        GROUP BY r.region
        ORDER BY num_attack DESC";
    }

    else if($_category == 'Target'){
        $_sql = "SELECT t.target_type as class, SUM(f.num_attack) as num_attack FROM fact as f, target as t
        WHERE f.target_id = t.target_id
        GROUP BY t.target_type
        ORDER BY num_attack DESC";
    }

    else if($_category == 'Weapon Type'){
        $_sql = "SELECT w.weapontype as class, SUM(f.num_attack) as num_attack FROM fact as f, weapon as w
        WHERE f.weapon_id = w.weapon_id
        GROUP BY w.weapontype
        ORDER BY num_attack DESC";
    }

    else if($_category == 'Gang Name'){
        $_sql = "SELECT g.gname as class, SUM(f.num_attack) as num_attack FROM fact as f, gname as g
        WHERE f.gname_id = g.gname_id
        GROUP BY g.gname
        ORDER BY num_attack DESC";
    }

    $result = $conn->query($_sql);

    if($result)
    { 
        echo "<h4> Search By: ".$_category;
        $rowcount=mysqli_num_rows($result);

        echo "<br><h4>".$rowcount." Results Returned</h4>";
        echo "<table class='table' border=1px>";
        echo "<thead class='thead-dark'><tr>";
        echo "<th>".$_category."</th>";
        echo "<th>Total # of Attacks</th>";
        echo "</tr></thead><tbody>";


        while($row = $result->fetch_assoc())
        {  

            echo "<td>".$row['class']."</td>";
            echo "<td>".$row['num_attack']."</td>";
            echo "</tr>";
        }
            echo "</tbody></table> <br>";

    }



}

else if ($_GET['action'] == 'search') {
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

    echo "<h4> Search Criteria: ";
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
            $rowcount=mysqli_num_rows($result_search);
            echo "<br><h4>".$rowcount." Results Returned</h4>";
            

            echo "<table class='table' border=1px>";
            echo "<thead class='thead-dark'><tr>";
            echo "<th>Date</th>";
            echo "<th>Region</th>";
            echo "<th>Country</th>";
            echo "<th>City, State</th>";
            echo "<th>Target</th>";
            echo "<th>Attack Type</th>";
            echo "<th>Weapon Type</th>";
            echo "<th>Gang Name</th>";
            echo "<th>Success</th>";
            echo "<th># of Attacks</th>";

            echo "</tr></thead><tbody>";
			while($row = $result_search->fetch_assoc())
			{  
                echo "<td>".$row['dt']."</td>";
                echo "<td>".$row['region']."</td>";
                echo "<td>".$row['country']."</td>";
                echo "<td>".$row['city'].", ".$row['provstate']."</td>";
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
            echo "</tbody></table> <br>";

        }   
}

?>

<?php
require 'footer.php'
?>
