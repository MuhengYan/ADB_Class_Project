create database terrorismometer;

use terrorismometer;

CREATE TABLE date (
    date_id INT AUTO_INCREMENT PRIMARY KEY,
    iyear INT,
    imonth INT,
    iday INT,
    UNIQUE KEY (iyear, imonth, iday)
);

CREATE TABLE region ( 
    region_id INT AUTO_INCREMENT PRIMARY KEY,  
    region VARCHAR(255), 
    country VARCHAR(255), 
    provstate VARCHAR(255), 
    city VARCHAR(255), 
    UNIQUE KEY (region, country, provstate, city) 
);

 CREATE TABLE attack ( 
    attack_id INT AUTO_INCREMENT PRIMARY KEY,  
    attack_type VARCHAR(255), 
    UNIQUE KEY (attack_type) 
);

CREATE TABLE target ( 
    target_id INT AUTO_INCREMENT PRIMARY KEY,  
    target_type VARCHAR(255), 
    UNIQUE KEY (target_type) 
);

 CREATE TABLE gname ( 
    gname_id INT AUTO_INCREMENT PRIMARY KEY,  
    gname_txt VARCHAR(255), 
    gsubname VARCHAR(255), 
    UNIQUE KEY (gname_txt,gsubname) 
);

 CREATE TABLE weapon ( 
    weapon_id INT AUTO_INCREMENT PRIMARY KEY,  
    weapontype VARCHAR(255), 
    weapsubtype VARCHAR(255), 
    UNIQUE KEY (weapontype, weapsubtype) 
);

CREATE TABLE fact (
    date_id INT,
    region_id INT,
    attack_id INT,
    target_id INT,
    gname_id INT,
    weapon_id INT,
    success INT,
    claimed INT,
    ishostkid INT,
    num_attack INT
);

ALTER TABLE fact
ADD FOREIGN KEY (attack_id) REFERENCES attack(attack_id),  
ADD FOREIGN KEY (date_id) REFERENCES date(date_id),
ADD FOREIGN KEY (gname_id) REFERENCES gname(gname_id),  
ADD FOREIGN KEY (region_id) REFERENCES region(region_id),  
ADD FOREIGN KEY (target_id) REFERENCES target(target_id),  
ADD FOREIGN KEY (weapon_id) REFERENCES weapon(weapon_id);  



