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


                