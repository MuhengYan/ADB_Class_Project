__author__ = "Muheng Yan"


import mysql.connector
import pandas as pd
import xlrd 
import tqdm
import math

mydb = mysql.connector.connect(
        host="localhost",
        user="root",
        passwd="mysql",
        database="terrorismometer"
     )


if __name__ == '__main__':
    
    cursor = mydb.cursor()
    df = pd.read_excel('gtd1993_0919dist.xlsx')
    
    query = ('CREATE TABLE date ('
            'date_id INT AUTO_INCREMENT PRIMARY KEY,' 
            'iyear INT,'
            'imonth INT,'
            'iday INT,'
            'UNIQUE KEY (iyear, imonth, iday)'
            ')')
    cursor.execute(query)
    query = ('CREATE TABLE region ('
                'region_id INT AUTO_INCREMENT PRIMARY KEY,' 
                'region VARCHAR(255),'
                'country VARCHAR(255),'
                'provstate VARCHAR(255),'
                'city VARCHAR(255),'
                'UNIQUE KEY (region, country, provstate, city)'
                ')')
    cursor.execute(query)
    query = ('CREATE TABLE attack ('
                'attack_id INT AUTO_INCREMENT PRIMARY KEY,' 
                'attack_type VARCHAR(255),'
                'UNIQUE KEY (attack_type)'
                ')')
    cursor.execute(query)

    query = ('CREATE TABLE target ('
                'target_id INT AUTO_INCREMENT PRIMARY KEY,' 
                'target_type VARCHAR(255),'
                'UNIQUE KEY (target_type)'
                ')')
    cursor.execute(query)

    query = ('CREATE TABLE gname ('
                'gname_id INT AUTO_INCREMENT PRIMARY KEY,' 
                'gname_txt VARCHAR(255),'
                'gsubname VARCHAR(255),'
                'UNIQUE KEY (gname_txt,gsubname)'
                ')')
    cursor.execute(query)
    query = ('CREATE TABLE weapon ('
                'weapon_id INT AUTO_INCREMENT PRIMARY KEY,' 
                'weapontype VARCHAR(255),'
                'weapsubtype VARCHAR(255),'
                'UNIQUE KEY (weapontype, weapsubtype)'
                ')')
    cursor.execute(query)
    
    query = ('CREATE TABLE fact ('
            'date_id INT,'
            'region_id INT,'
            'attack_id INT,'
            'target_id INT,'
            'gname_id INT,'
            'weapon_id INT,'
            'success VARCHAR(255),'
            'claimed VARCHAR(255),'
            'ishostkid VARCHAR(255),'
            'num_attack INT,' 
            'FOREIGN KEY (date_id) references date (date_id),'
            'FOREIGN KEY (region_id) references region (region_id),'
            'FOREIGN KEY (attack_id) references attack (attack_id),'
            'FOREIGN KEY (target_id) references target (target_id),'
            'FOREIGN KEY (gname_id) references gname (gname_id),'
            'FOREIGN KEY (weapon_id) references weapon (weapon_id)'
            ')')
    cursor.execute(query)
    
       
    feature_count = {}

    for i, row in df.iterrows():
        #date
        year = row['iyear']
        month = row['imonth']
        day = row['iday']
        insert_query = 'INSERT INTO date (iyear, imonth, iday) VALUES ("{}", "{}", "{}")'.format(year, month, day)
        select_query = 'SELECT date_id FROM date WHERE iyear="{}" AND imonth="{}" AND iday="{}"'.format(year, month, day)

        try:
            cursor.execute(insert_query)
        except Exception:
            pass
        cursor.execute(select_query)
        _res = cursor.fetchall()
        date_id = _res[0][0]

        #region
        region = row['region_txt']
        country = row['country_txt']
        provstate = row['provstate']
        city = row['city']

        insert_query = 'INSERT INTO region (region, country, provstate, city) VALUES ("{}", "{}", "{}", "{}")'.format(region, country, provstate, city)
        select_query = 'SELECT region_id FROM region WHERE country="{}" AND provstate="{}" AND city="{}"'.format(country, provstate, city)
        try:
            cursor.execute(insert_query)
        except Exception:
            pass
    #     try:
    #         cursor.execute(select_query)
    #     except Exception:
    #         continue
        cursor.execute(select_query)
        _res = cursor.fetchall()
        region_id = _res[0][0]


        attack = row['attacktype1_txt']
        insert_query = 'INSERT INTO attack (attack_type) VALUES ("{}")'.format(attack)
        select_query = 'SELECT attack_id FROM attack WHERE attack_type="{}"'.format(attack)
        try:
            cursor.execute(insert_query)
        except Exception:
            pass
        cursor.execute(select_query)
        _res = cursor.fetchall()
        attack_id = _res[0][0]


        target = row['targtype1_txt']
        insert_query = 'INSERT INTO target (target_type) VALUES ("{}")'.format(target)
        select_query = 'SELECT target_id FROM target WHERE target_type="{}"'.format(target)
        try:
            cursor.execute(insert_query)
        except Exception:
            pass
        cursor.execute(select_query)
        _res = cursor.fetchall()
        target_id = _res[0][0]


        gname = row['gname']
        gsubname = row['gsubname']
        insert_query = 'INSERT INTO gname (gname_txt, gsubname) VALUES ("{}", "{}")'.format(gname, gsubname)
        select_query = 'SELECT gname_id FROM gname WHERE gname_txt="{}" and gsubname="{}"'.format(gname, gsubname)
        try:
            cursor.execute(insert_query)
        except Exception:
            pass
        cursor.execute(select_query)
        _res = cursor.fetchall()
        gname_id = _res[0][0]

        weapon = row['weaptype1_txt']
        weapsubtype = row['weapsubtype1_txt']


    #     if math.isnan(weaponsubtype):
    #         weaponsubtype = 'NONE'

        insert_query = 'INSERT INTO weapon (weapontype, weapsubtype) VALUES ("{}", "{}")'.format(weapon, weapsubtype)
        select_query = 'SELECT weapon_id FROM weapon WHERE weapontype="{}" AND weapsubtype="{}"'.format(weapon, weapsubtype)
        try:
            cursor.execute(insert_query)
        except Exception:
            pass
        cursor.execute(select_query)
        _res = cursor.fetchall()
        weapon_id = _res[0][0]


        # fact_features = ['success', 'claimed', 'nkill', 'nwound', 'ishostkid']
        success = row['success']
        claimed = row['claimed']
        ishostkid = row['ishostkid']

        features = [date_id, region_id, attack_id, target_id, gname_id, weapon_id, success, claimed, ishostkid]
        _feat = [str(item) for item in features]
        feature_string = '::'.join(_feat)
        if feature_string in feature_count:
            feature_count[feature_string] += 1
        else:
            feature_count[feature_string] = 1

    query = ('CREATE TABLE fact ('
            'date_id INT,'
            'region_id INT,'
            'attack_id INT,'
            'target_id INT,'
            'gname_id INT,'
            'weapon_id INT,'
            'success VARCHAR(255),'
            'claimed VARCHAR(255),'
            'ishostkid VARCHAR(255),'
            'num_attack INT,' 
            'FOREIGN KEY (date_id) references date (date_id),'
            'FOREIGN KEY (region_id) references region (region_id),'
            'FOREIGN KEY (attack_id) references attack (attack_id),'
            'FOREIGN KEY (target_id) references target (target_id),'
            'FOREIGN KEY (gname_id) references gname (gname_id),'
            'FOREIGN KEY (weapon_id) references weapon (weapon_id)'
            ')')

    for feat in feature_count:
        expanded = feat.split('::')
        date_id, region_id, attack_id, target_id, gname_id, weapon_id, success, claimed, ishostkid = expanded
        num_attack = feature_count[feat]
        insert_query = 'INSERT INTO fact (date_id,  region_id, attack_id, target_id, gname_id, weapon_id, success, claimed, ishostkid, num_attack) VALUES ("{}", "{}", "{}", "{}", "{}", "{}", "{}", "{}", "{}", "{}")'.format(date_id,  region_id, attack_id, target_id, gname_id, weapon_id, success, claimed, ishostkid, num_attack)
        cursor.execute(insert_query)
        
    
    tables = ['fact', 'date', 'region', 'attack', 'target', 'gname', 'weapon']
    
    if not os.path.exists('export_csvs'):
        os.makedirs('export_csvs')
        
    for table in tables:
        q = 'SELECT * FROM {}'.format(table)
        cursor.execute(q)
        _res = cursor.fetchall()

        import csv
        with open('export_csvs/{}.csv'.format(table), 'w') as f:
            writer = csv.writer(f, delimiter=',')
            for row in _res:
                writer.writerow(row)
