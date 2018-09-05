/* TODO: create tables */
BEGIN TRANSACTION;

CREATE TABLE `users` (
	`id`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
	`username`	TEXT NOT NULL UNIQUE,
	`password`	TEXT NOT NULL
);

CREATE TABLE clients (
	`client_id`	 INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
  `client_name` TEXT NOT NULL
);

CREATE TABLE projects (
`proj_id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
`client_id` INTEGER NOT NULL,
`project_name` TEXT NOT NULL,
`project_address` TEXT,
`project_status` TEXT NOT NULL,
`project_description` TEXT,
FOREIGN KEY(client_id) REFERENCES clients(client_id)
);

CREATE TABLE images (
  `img_id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
  `file_name` TEXT NOT NULL,
	`file_ext` TEXT NOT NULL,
  `proj_id` TEXT NOT NULL,
	`citation` TEXT,
  FOREIGN KEY(proj_id) REFERENCES projects(proj_id)
);

CREATE TABLE tags (
	`tag_id`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
  `tag` TEXT NOT NULL UNIQUE
);

CREATE TABLE project_tags (
	`id`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
  `tag_id` INTEGER NOT NULL,
  `proj_id` INTEGER NOT NULL,
	FOREIGN KEY(proj_id) REFERENCES projects(proj_id),
	FOREIGN KEY(tag_id) REFERENCES tags(tag_id)
);

CREATE TABLE schedule (
	`id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
  `client_id` INTEGER NOT NULL,
  `proj_id` INTEGER NOT NULL,
  `date` TEXT NOT NULL,
	`time` TEXT NOT NULL,
	`pending_approval` BIT NOT NULL,
	`email` TEXT,
	FOREIGN KEY(proj_id) REFERENCES projects(proj_id)
);


CREATE TABLE team (
	id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
	title TEXT NOT NULL,
	first_name TEXT NOT NULL,
	last_name TEXT NOT NULL,
	description TEXT NOT NULL
);

COMMIT;

/* TODO: initial seed data */
/* seed data for users */
INSERT INTO users (username, password)
VALUES ('admin', '$2y$10$dHZ5r2kVvrLEHHn8/kJZzOuLl/5AEFD0EqSkyhHSLRZ5dassUzgvS'); /*access*/


INSERT INTO clients (client_name) VALUES ("Devon Management");
INSERT INTO clients (client_name) VALUES ("Venda Properties");
INSERT INTO clients (client_name) VALUES ("Lafayette Ridge");
INSERT INTO clients (client_name) VALUES ("Heritage of Goshen");
INSERT INTO clients (client_name) VALUES ("BMG Monroe I LLC");
INSERT INTO clients (client_name) VALUES ("Stewart Hills Distribution Center LLC");
INSERT INTO clients (client_name) VALUES ("Sullivan Catskills");
INSERT INTO clients (client_name) VALUES ("Butch Resnick");
INSERT INTO clients (client_name) VALUES ("Mansion Ridge Golf");

/*client id is arbitrarily chosen*/
INSERT INTO projects(client_id, project_name, project_address, project_status, project_description)
VALUES (1,"Liberty Green", "1 Libery Court, Warwich, NY", "Finished", "The Liberty Green apartments offer a comfortable yet stylish lifestyle right in the heart of the community. With a wide range of amenities and an efficient floor plan, each airy one-bedroom apartment provides the perfect solution for comfortable senior living.");
INSERT INTO projects(client_id, project_name, project_address, project_status, project_description)
VALUES (1,"Bella Vista", "Bella Vista Drive, Middletown, NY", "In Progress", "Our Bella Vista Apartments offer a comfortable yet stylish lifestyle right in the heart of the community. With a wide range of amenities and an efficient 706 square foot room plan, each airy one-bedroom apartment provides the perfect home for individuals and families. Bella Vista also offers a limited number of 2-bedroom and 3-bedroom units.");
INSERT INTO projects(client_id, project_name, project_address, project_status, project_description)
VALUES (3,"Lafayette Ridge", "Lafayette Drive, New Windsor, NY", "In Progress", "Apartments");
INSERT INTO projects(client_id, project_name, project_address, project_status, project_description)
VALUES (4,"Heritage of Goshen", "16 S. Church Street, Goshen, NY", "In Progress", "Single Family");
INSERT INTO projects(client_id, project_name, project_address, project_status, project_description)
VALUES (5,"Smith Farm", "Gilbert Street, Monroe, NY", "In Progress", "181 Residential Homes Clustered on 79 Acre Site
63 Detached Single Family Homes
64 Patio Homes
54 Duplex Units Containing 2 Single-Family Dwelling Units Each
36 of the Duplex Units Are Age-Restricted
Community Recreation Area
Extension of Village Water District");
INSERT INTO projects(client_id, project_name, project_address, project_status, project_description)
VALUES (6,"Stewart Hills", "NYS Route 207, New Windsor, NY", "Finished", "Distribution Center");
INSERT INTO projects(client_id, project_name, project_address, project_status, project_description)
VALUES (2,"Venda Properties", "2349 Goshen Turnpike, Middletown, NY", "Finished", "Final site plan approval that will support a structure up to a total of 400,000 SF +/- with required parking as per code. This lot is zoned Office-Research & Warehousing. Site is serviced by municipal water and sewer as well as natural gas. Site plans for the approved 400,000 sf warehouse, topo, etc. upon request and there is an aerial in document file. The property was formerly a soil mining operation. The site is easily accessible to NYS Rte. 17, I-84, I-86 & NYS Rte. 211.");
INSERT INTO projects(client_id, project_name, project_address, project_status, project_description)
VALUES (7,"Thompson Education Center", "Wild Turnpike, Fallsburg, NY", "Finished", "This Thompson Education center is built for higher education in the neighborhoods of Fallsburg in New York State. Thompson Education Center is a project that plans to create a high-end education community. It is located in Sullivan County, Town of Thompson, covering 575 acres.");
INSERT INTO projects(client_id, project_name, project_address, project_status, project_description)
VALUES (8,"Apollo Plaza", "99 East Broadway, Monticello, NY", "Finished", "Commercial Redevelopment");
INSERT INTO projects(client_id, project_name, project_address, project_status, project_description)
VALUES (9,"Mansion Ridge Golf Facility", "1292 Orange Turnpike, Monroe, NY", "Finished", "Design Building");


INSERT INTO images (file_name, file_ext, proj_id, citation)
VALUES ("under_construction","png", 3, "https://pixabay.com/en/users/Josethestoryteller-5100055/");

INSERT INTO images (file_name, file_ext, proj_id, citation)
VALUES ("under_construction","png", 4, "https://pixabay.com/en/users/Josethestoryteller-5100055/");


INSERT INTO images (file_name, file_ext, proj_id, citation)
VALUES ("under_construction","png", 6, "https://pixabay.com/en/users/Josethestoryteller-5100055/");

INSERT INTO images (file_name, file_ext, proj_id, citation)
VALUES ("under_construction","png", 7, "https://pixabay.com/en/users/Josethestoryteller-5100055/");

INSERT INTO images (file_name, file_ext, proj_id, citation)
VALUES ("under_construction","png", 8, "https://pixabay.com/en/users/Josethestoryteller-5100055/");

INSERT INTO images (file_name, file_ext, proj_id, citation)
VALUES ("under_construction","png", 9, "https://pixabay.com/en/users/Josethestoryteller-5100055/");

INSERT INTO images (file_name, file_ext, proj_id, citation)
VALUES ("project1-1","png", 1, "http://www.devonmgt.com/wp/locations/liberty-green/");
INSERT INTO images (file_name, file_ext, proj_id, citation)
VALUES ("project1-2","png", 1, "https://www.google.com/maps/place/Liberty+Green/@41.2625376,-74.3676896,517m/data=!3m1!1e3!4m5!3m4!1s0x89c2d4b90da3deb3:0xaf35c6bee12eee52!8m2!3d41.26344!4d-74.3663591");
INSERT INTO images (file_name, file_ext, proj_id, citation)
VALUES ("project1-3","png", 1, "http://www.devonmgt.com/wp/locations/liberty-green/");
INSERT INTO images (file_name, file_ext, proj_id, citation)
VALUES ("project1-4","png", 1, "http://www.devonmgt.com/wp/locations/liberty-green/");
INSERT INTO images (file_name, file_ext, proj_id, citation)
VALUES ("project1-5","png", 1, "http://www.devonmgt.com/wp/locations/liberty-green/");
INSERT INTO images (file_name, file_ext, proj_id)
VALUES ("project1-6", "jpg", 1);
INSERT INTO images (file_name, file_ext, proj_id)
VALUES ("project1-7", "jpg", 1);
INSERT INTO images (file_name, file_ext, proj_id)
VALUES ("project1-8", "jpg", 1);
INSERT INTO images (file_name, file_ext, proj_id)
VALUES ("project1-9", "jpg", 1);
INSERT INTO images (file_name, file_ext, proj_id)
VALUES ("project1-10", "jpg", 1);
INSERT INTO images (file_name, file_ext, proj_id)
VALUES ("project1-11", "jpg", 1);
INSERT INTO images (file_name, file_ext, proj_id)
VALUES ("project1-12", "jpg", 1);
INSERT INTO images (file_name, file_ext, proj_id)
VALUES ("project1-13", "jpg", 1);
INSERT INTO images (file_name, file_ext, proj_id)
VALUES ("project1-14", "jpg", 1);
INSERT INTO images (file_name, file_ext, proj_id)
VALUES ("project1-15", "jpg", 1);
INSERT INTO images (file_name, file_ext, proj_id)
VALUES ("project1-16", "jpg", 1);
INSERT INTO images (file_name, file_ext, proj_id)
VALUES ("project1-17", "jpg", 1);
INSERT INTO images (file_name, file_ext, proj_id)
VALUES ("project1-18", "jpg", 1);
INSERT INTO images (file_name, file_ext, proj_id)
VALUES ("project1-19", "jpg", 1);
INSERT INTO images (file_name, file_ext, proj_id)
VALUES ("project1-20", "jpg", 1);
INSERT INTO images (file_name, file_ext, proj_id)
VALUES ("project1-21", "jpg", 1);

INSERT INTO images (file_name, file_ext, proj_id, citation)
VALUES ("project2-1","png", 2, "http://www.devonmgt.com/wp/locations/bella-vista/");
INSERT INTO images (file_name, file_ext, proj_id, citation)
VALUES ("project2-2","png", 2, "https://www.google.com/maps?ll=41.426444,-74.43675&z=16&t=h&hl=en-US&gl=US&mapclient=embed");
INSERT INTO images (file_name, file_ext, proj_id)
VALUES ("project2-3", "jpg", 2);
INSERT INTO images (file_name, file_ext, proj_id)
VALUES ("project2-4", "jpg", 2);
INSERT INTO images (file_name, file_ext, proj_id)
VALUES ("project2-5", "jpg", 2);
INSERT INTO images (file_name, file_ext, proj_id)
VALUES ("project2-6", "jpg", 2);
INSERT INTO images (file_name, file_ext, proj_id)
VALUES ("project2-7", "jpg", 2);
INSERT INTO images (file_name, file_ext, proj_id)
VALUES ("project2-8", "jpg", 2);
INSERT INTO images (file_name, file_ext, proj_id)
VALUES ("project2-9", "jpg", 2);
INSERT INTO images (file_name, file_ext, proj_id)
VALUES ("project2-10", "jpg", 2);
INSERT INTO images (file_name, file_ext, proj_id)
VALUES ("project2-11", "jpg", 2);
INSERT INTO images (file_name, file_ext, proj_id)
VALUES ("project2-12", "jpg", 2);
INSERT INTO images (file_name, file_ext, proj_id)
VALUES ("project2-13", "jpg", 2);
INSERT INTO images (file_name, file_ext, proj_id)
VALUES ("project2-14", "jpg", 2);
INSERT INTO images (file_name, file_ext, proj_id)
VALUES ("project2-15", "jpg", 2);
INSERT INTO images (file_name, file_ext, proj_id)
VALUES ("project2-16", "jpg", 2);
INSERT INTO images (file_name, file_ext, proj_id)
VALUES ("project2-17", "jpg", 2);

INSERT INTO images (file_name, file_ext, proj_id)
VALUES ("project3-1", "jpg", 3);
INSERT INTO images (file_name, file_ext, proj_id)
VALUES ("project3-2", "jpg", 3);
INSERT INTO images (file_name, file_ext, proj_id)
VALUES ("project3-3", "jpg", 3);
INSERT INTO images (file_name, file_ext, proj_id)
VALUES ("project3-4", "jpg", 3);
INSERT INTO images (file_name, file_ext, proj_id)
VALUES ("project3-5", "jpg", 3);
INSERT INTO images (file_name, file_ext, proj_id)
VALUES ("project3-6", "jpg", 3);
INSERT INTO images (file_name, file_ext, proj_id)
VALUES ("project3-7", "jpg", 3);
INSERT INTO images (file_name, file_ext, proj_id)
VALUES ("project3-8", "jpg", 3);
INSERT INTO images (file_name, file_ext, proj_id)
VALUES ("project3-9", "jpg", 3);
INSERT INTO images (file_name, file_ext, proj_id)
VALUES ("project3-10", "jpg", 3);
INSERT INTO images (file_name, file_ext, proj_id)
VALUES ("project3-11", "jpg", 3);
INSERT INTO images (file_name, file_ext, proj_id)
VALUES ("project3-12", "jpg", 3);
INSERT INTO images (file_name, file_ext, proj_id)
VALUES ("project3-13", "jpg", 3);
INSERT INTO images (file_name, file_ext, proj_id)
VALUES ("project3-14", "jpg", 3);
INSERT INTO images (file_name, file_ext, proj_id)
VALUES ("project3-15", "jpg", 3);

INSERT INTO images (file_name, file_ext, proj_id, citation)
VALUES ("project5-1","png", 5, "http://www.recordonline.com/news/20180420/181-home-smith-farm-project-in-monroe-on-hold");
INSERT INTO images (file_name, file_ext, proj_id, citation)
VALUES ("project5-2","png", 5, "http://www.recordonline.com/news/20180420/181-home-smith-farm-project-in-monroe-on-hold");
INSERT INTO images (file_name, file_ext, proj_id, citation)
VALUES ("project5-3","png", 5, "https://www.google.com/maps/search/Smith+Farm%22,+%22Gilbert+Street,+Monroe,+NY/@41.3345193,-74.1990399,755m/data=!3m1!1e3?hl=en-US");

INSERT INTO images (file_name, file_ext, proj_id, citation)
VALUES ("project8-1","png", 8, "https://pixabay.com/en/users/Josethestoryteller-5100055/");

INSERT INTO images (file_name, file_ext, proj_id, citation)
VALUES ("project9-1","png", 9, "https://www.google.com/maps/place/99+E+Broadway,+Monticello,+NY+12701/@41.645209,-74.6574715,628m/data=!3m1!1e3!4m5!3m4!1s0x89dcb710eee5682f:0xf1d7f822361184da!8m2!3d41.645213!4d-74.655648");
INSERT INTO images (file_name, file_ext, proj_id, citation)
VALUES ("project9-2","png", 9, "https://www.google.com/maps/place/99+E+Broadway,+Monticello,+NY+12701/@41.645209,-74.6574715,628m/data=!3m1!1e3!4m5!3m4!1s0x89dcb710eee5682f:0xf1d7f822361184da!8m2!3d41.645213!4d-74.655648");

INSERT INTO images (file_name, file_ext, proj_id, citation)
VALUES ("project10-1","png", 10, "https://www.google.com/maps/place/The+Golf+Club+at+Mansion+Ridge/@41.3003051,-74.1866893,17z/data=!3m1!4b1!4m5!3m4!1s0x89c2d7865fa0b43d:0x1cc14b5fec182fc3!8m2!3d41.3003011!4d-74.1845006");
INSERT INTO images (file_name, file_ext, proj_id, citation)
VALUES ("project10-2","png", 10, "https://www.google.com/maps/place/The+Golf+Club+at+Mansion+Ridge/@41.3003051,-74.1866893,17z/data=!3m1!4b1!4m5!3m4!1s0x89c2d7865fa0b43d:0x1cc14b5fec182fc3!8m2!3d41.3003011!4d-74.1845006");


INSERT INTO team (title, first_name, last_name, description)
VALUES ("Principal", "Vincent", "Pietrzak", "Vincent Pietrzak is one of the two principals of the firm. Mr. Pietzrak is a native to Orange County and now resides in the hamlet of Craigville, only five minutes from the Village of Goshen. He graduated from Syracuse University with a Bachelor of Science in Civil Engineering." );
INSERT INTO team (title, first_name, last_name, description)
VALUES ("Principal", "Joseph", "Pfau", "Joseph Pfau is one of the two principals of the firm. Mr. Pfau is from Lake George, New York and now lives in the Village of Monticello. He graduated from Syracuse University with a Bachelor of Science in Civil Engineering.");
INSERT INTO team (title, first_name, last_name, description)
VALUES ("Professional Licensed Engineer", "Mark", "Siemers", "Mr. Siemers is a Licensed Professional Engineer. He is responsible for overseeing the project management, analysis, design and permitting of engineering projects for both private and municipal clients. ");
INSERT INTO team (title, first_name, last_name, description)
VALUES ("Professional Licensed Engineer", "Anthony", "Trochiano", "Mr. Trochiano is a Licensed Professional Engineer. He is responsible for overseeing assigned design projects from concept to building permit as well as managing construction and permits by working closely with regulatory agencies and site contractors.");
INSERT INTO team (title, first_name, last_name, description)
VALUES ("Professional Licensed Engineer", "Michael", "Creegan", "Mr. Creegan is a Licensed Professional Engineer. He is responsible for the supervision of field and office operations of the firm’s survey department and assigned projects.");
