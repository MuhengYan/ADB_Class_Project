/*Queries Used*/

-- Main Table, by Country 
SELECT AG.country, SUM(AG.n_attack) AS sum_attack, AVG(AG.n_attack) as avg_attack, COUNT(DISTINCT AG.city) as count_city
    FROM 
        (SELECT r.country, r.city, SUM(f.num_attack) as n_attack 
        FROM region AS r, fact AS f 
        WHERE r.region_id=f.region_id GROUP BY r.country, r.city) AS AG 
    GROUP BY AG.country 
    ORDER BY SUM(AG.n_attack) DESC

-- Search by Entity 
--Date
SELECT d.dt, SUM(f.num_attack) as num_attack FROM fact as f, (SELECT date_id, CAST(CONCAT(iyear, '-', imonth, '-', iday) AS date) as dt FROM date) as d
        WHERE f.date_id = d.date_id
        GROUP BY d.dt
        ORDER BY num_attack DESC

--Attack type
SELECT a.attack_type, SUM(f.num_attack) as num_attack FROM fact as f, attack as a
        WHERE f.attack_id = a.attack_id
        GROUP BY a.attack_type
        ORDER BY num_attack DESC

--Region
SELECT r.region, SUM(f.num_attack) as num_attack FROM fact as f, region as r
        WHERE f.region_id = r.region_id
        GROUP BY r.region
        ORDER BY num_attack DESC

--Target
SELECT t.target_type, SUM(f.num_attack) as num_attack FROM fact as f, target as t
        WHERE f.target_id = t.target_id
        GROUP BY t.target_type
        ORDER BY num_attack DESC

--Wapon Type
SELECT w.weapontype, SUM(f.num_attack) as num_attack FROM fact as f, weapon as w
        WHERE f.weapon_id = w.weapon_id
        GROUP BY w.weapontype
        ORDER BY num_attack DESC

--Gang Name
SELECT g.gname, SUM(f.num_attack) as num_attack FROM fact as f, gname as g
        WHERE f.gname_id = g.gname_id
        GROUP BY g.gname
        ORDER BY num_attack DESC

--Main Search 
SELECT * FROM fact as f, attack as a, gname as g, region as r, target as t, weapon as w, 
    (SELECT date_id, CAST(CONCAT(iyear, '-', imonth, '-', iday) AS date) as dt FROM date) as d
    WHERE f.attack_id = a.attack_id
    AND f.date_id = d.date_id
    AND f.gname_id = g.gname_id
    AND f.region_id = r.region_id
    AND f.target_id = t.target_id
    AND f.weapon_id = w.weapon_id
    AND d.dt BETWEEN '$_startdate' AND '$_enddate'  --Only use if given input
    AND r.region = '$_region'                       --Only use if given input
    AND t.target_type = '$_target'                  --Only use if given input
    AND a.attack_type = '$_attack'                  --Only use if given input
    AND w.weapontype = '$_weapon'                   --Only use if given input
    AND g.gname = '$_gname'                         --Only use if given input

--City-specific group by date
select d.imonth as month, d.iday as day, d.iyear as year, num_attack as num_attack from 
    (select r.country as country, r.city as city, f.date_id as date_id,
        f.num_attack as num_attack from fact as f, region as r where r.region_id = f.region_id and r.country = '".$country."' and r.city = '".$city."') as subquery,
         date as d where subquery.date_id = d.date_id

--Country-specific data to list number of attacks by city in each country
SELECT r.city as r_city, SUM(f.num_attack) as num_attack
            FROM region AS r, fact AS f 
            WHERE r.region_id=f.region_id and r.country = '".$country."'
            GROUP BY r.country, r.city 
            ORDER BY SUM(f.num_attack) DESC