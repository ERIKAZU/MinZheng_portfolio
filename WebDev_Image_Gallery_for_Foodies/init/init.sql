/* TODO: create tables */
CREATE TABLE photos (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    file_name varchar(255),
    file_ext TEXT,
    restaurant varchar(255),
    dish_name varchar(255),
    rating INTEGER,
    price INTEGER,
    comments varchar(255),
    user_uploaded INTEGER
);

INSERT INTO photos (file_name, file_ext, dish_name, restaurant,rating,price,user_uploaded)
VALUES ("1","jpg","happy meal","mcdonalds","2","2","1");
INSERT INTO photos (file_name, file_ext, dish_name, restaurant,rating,price,user_uploaded)
VALUES ("2","jpg","burger","Burger King","2","2","1");
INSERT INTO photos (file_name, file_ext, dish_name, restaurant,rating,price,user_uploaded)
VALUES ("3","jpg","dim sum","QuanTong","3","1","1");
INSERT INTO photos (file_name, file_ext, dish_name, restaurant,rating,price,user_uploaded)
VALUES ("4","jpg","fried chicken","KFC","2","1","1");
INSERT INTO photos (file_name, file_ext, dish_name, restaurant,rating,price,user_uploaded)
VALUES ("5","jpg","egg salad","WholeFood","1","1","1");
INSERT INTO photos (file_name, file_ext, dish_name, restaurant,rating,price,user_uploaded)
VALUES ("6","jpg","noodles","Nice Soup","2","2","1");
INSERT INTO photos (file_name, file_ext, dish_name, restaurant,rating,price,user_uploaded)
VALUES ("7","jpg","basic combo","mcdonalds","2","1","1");
INSERT INTO photos (file_name, file_ext, dish_name, restaurant,rating,price,user_uploaded)
VALUES ("8","jpg","pasta","Little Italy","1","1","1");
INSERT INTO photos (file_name, file_ext, dish_name, restaurant,rating,price,user_uploaded)
VALUES ("9","jpg","shrimp plate","Seafood King","3","3","1");
INSERT INTO photos (file_name, file_ext, dish_name, restaurant,rating,price,user_uploaded)
VALUES ("10","jpg","fish balls","Seafood King","3","2","1");
INSERT INTO photos (file_name, file_ext, dish_name, restaurant,rating,price,user_uploaded)
VALUES ("11","jpg","donut","Sweet things","2","1","1");
INSERT INTO photos (file_name, file_ext, dish_name, restaurant,rating,price,user_uploaded)
VALUES ("12","jpg","Gyoza","Japan Town","1","1","1");
INSERT INTO photos (file_name, file_ext, dish_name, restaurant,rating,price,user_uploaded)
VALUES ("13","jpg","donut","Sweet things","1","1","2");
INSERT INTO photos (file_name, file_ext, dish_name, restaurant,rating,price,user_uploaded)
VALUES ("14","jpg","vegies","Fun Food","3","2","2");


CREATE TABLE tags (
    tag_id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    tagName varchar(255) NOT NULL UNIQUE
);

INSERT INTO tags (tagName)
VALUES ('Korean');

INSERT INTO tags (tagName)
VALUES ('Breakfast');

INSERT INTO tags (tagName)
VALUES ('Asian');

INSERT INTO tags (tagName)
VALUES ('Chinese');

INSERT INTO tags (tagName)
VALUES ('Lunch');

INSERT INTO tags (tagName)
VALUES ('American');

INSERT INTO tags (tagName)
VALUES ('Healthy');

CREATE TABLE photo_x_tags (
    relation_id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    photoID int NOT NULL,
    tagName varchar(255) NOT NULL
);
INSERT INTO photo_x_Tags (photoID,tagName)
VALUES (1,'Lunch');
INSERT INTO photo_x_Tags (photoID,tagName)
VALUES (1,'American');

INSERT INTO photo_x_Tags (photoID,tagName)
VALUES (2,'Lunch');
INSERT INTO photo_x_Tags (photoID,tagName)
VALUES (2,'American');

INSERT INTO photo_x_Tags (photoID,tagName)
VALUES (3,'Breakfast');
INSERT INTO photo_x_Tags (photoID,tagName)
VALUES (3,'Lunch');
INSERT INTO photo_x_Tags (photoID,tagName)
VALUES (3,'Chinese');
INSERT INTO photo_x_Tags (photoID,tagName)
VALUES (3,'Asian');
INSERT INTO photo_x_Tags (photoID,tagName)
VALUES (3,'Healthy');

INSERT INTO photo_x_Tags (photoID,tagName)
VALUES (4,'Lunch');
INSERT INTO photo_x_Tags (photoID,tagName)
VALUES (4,'Korean');
INSERT INTO photo_x_Tags (photoID,tagName)
VALUES (4,'Asian');

INSERT INTO photo_x_Tags (photoID,tagName)
VALUES (5,'Breakfast');

INSERT INTO photo_x_Tags (photoID,tagName)
VALUES (6,'Lunch');
INSERT INTO photo_x_Tags (photoID,tagName)
VALUES (6,'Chinese');
INSERT INTO photo_x_Tags (photoID,tagName)
VALUES (6,'Asian');

INSERT INTO photo_x_Tags (photoID,tagName)
VALUES (7,'Lunch');
INSERT INTO photo_x_Tags (photoID,tagName)
VALUES (7,'American');

INSERT INTO photo_x_Tags (photoID,tagName)
VALUES (8,'Lunch');

INSERT INTO photo_x_Tags (photoID,tagName)
VALUES (9,'Lunch');
INSERT INTO photo_x_Tags (photoID,tagName)
VALUES (9,'American');
INSERT INTO photo_x_Tags (photoID,tagName)
VALUES (9,'Healthy');

/* user accounts */
CREATE TABLE `accounts` (
	`id`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	`username`	TEXT NOT NULL UNIQUE,
	`password`	TEXT NOT NULL,
	`session`	TEXT UNIQUE
);
INSERT INTO accounts (username, password) VALUES ('min', '$2y$10$96CpR3EkBtAmHBI5CtlzBOW4II39AmJeNU1YTEroLb5.QHTfOA3yG'); /* password: monkey */
INSERT INTO accounts (username, password) VALUES ('Abby', '$2y$10$S9qtPbZ8DDWCGd1fxKBz5.Z3uAfaE10ad2tKfy/dIYHNsGvBbVeCK'); /* password: 1 */
