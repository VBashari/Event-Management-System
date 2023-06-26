-- Table structure for table `user`
CREATE TABLE user (
  user_id int UNSIGNED NOT NULL AUTO_INCREMENT,
  user_type char(4) NOT NULL,
  username varchar(40) NOT NULL UNIQUE,
  full_name varchar(80) NOT NULL,
  email varchar(50) NOT NULL,
  password varchar(255) NOT NULL,
  PRIMARY KEY (user_id),
  CHECK (user_type IN ('USER', 'VNDR', 'ORG', 'ADMN')),
  UNIQUE (username) 
);

-- Dumping data for table `user`
INSERT INTO user (user_type, username, full_name, email, password) VALUES 
('USER',  'user1',          'Zaid Turner',        'user1@gmail.com',      'Userpass1'),
('USER',  'user2',          'Summer Burns',       'user2@gmail.com',      'Userpass2'),
('USER',  'user3',          'Rob Huffman',        'user3@gmail.com',      'Userpass2'),
('VNDR',  'vendor1',        'Elin Owens',         'vendor1@example.com',  'Vendorpass1'),
('VNDR',  'vendor2',        'Peter Hartley',      'vendor2@gmail.com',    'Vendorpass2'),
('VNDR',  'Elegant Events', 'Edward Carrillo',    'vendor3@gmail.com',    'Vendorpass3'),
('ORG',   'event_org1',     'Kabir Wiggins',      'evorg1@gmail.com',     'Eorgpass1'),
('ORG',   'event_org2',     'Sebastien Summers',  'evorg2@yahoo.com',     'Eorgpass2'),
('ORG',   'Glamour Galas',  'Alastair Norman',    'evorg3@yahoo.com',     'Eorgpass3'),
('ADMN',  'admin',          'Theo Foley',         'admin@example.com',    'Adminpass1');


-- Table structure for table `request`
CREATE TABLE request (
  request_id int UNSIGNED NOT NULL AUTO_INCREMENT,
  requester_id int UNSIGNED NOT NULL,
  servicer_id int UNSIGNED NOT NULL,
  title tinytext NOT NULL,
  description text(750),
  scheduled_date datetime NOT NULL,
  status tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (request_id),
  FOREIGN KEY (requester_id) REFERENCES user(user_id) 
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  FOREIGN KEY (servicer_id) REFERENCES user(user_id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CHECK (status IN (-1, 0, 1)),
  CONSTRAINT requester_schedule UNIQUE (requester_id, scheduled_date)
);

-- Dumping data for table `request`
INSERT INTO request (requester_id, servicer_id, title, description, scheduled_date, status) VALUES
(1,4,'Birthday party for a 6 y/o',NULL,'2023-06-04 09:30:00',0),
(1,4,'Late minute baby shower',NULL,'2023-06-30 02:30:00',-1),
(2,4,'Bachelor party','Vestibulum id elit tincidunt, eleifend odio ut, interdum felis. Duis dignissim maximus elementum. Phasellus nec commodo velit. Vestibulum consectetur purus in risus commodo blandit. Aenean et est fringilla, eleifend risus id, tempus justo. Ut vestibulum, sapien quis aliquam auctor, lectus velit mollis lorem, accumsan facilisis turpis neque ac magna.','2023-07-02 18:00:00',0),
(2,4,'Small downtown rave party?','Mauris nunc nisl, vehicula sit amet justo sit amet, mattis posuere turpis. Nam vel velit vitae urna pharetra posuere. Proin maximus lobortis massa, sit amet sagittis lacus suscipit nec. Nunc efficitur, dolor bibendum sollicitudin hendrerit, augue quam ullamcorper ipsum, vitae facilisis massa lorem vel arcu.','2023-08-12 22:30:00',1),
(3,4,'Small halloween party for kindergarten',NULL,'2023-10-31 10:00:00',0),
(3,4,'Birthday organization',NULL,'2023-11-30 11:59:10',-1),

(1,5,'Office Christmas party',NULL,'2023-12-25 12:30:00',0),
(1,5,'Downtown rave party',NULL,'2023-09-15 20:00:00',1),
(2,5,'Office farewell party','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur scelerisque est nec tincidunt dapibus. Vivamus blandit convallis pulvinar.','2023-07-01 10:15:00',0),
(2,5,'Birthday party',NULL,'2023-08-15 11:00:00',-1),
(3,5,'Bridal shower!!','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur scelerisque est nec tincidunt dapibus. Vivamus blandit convallis pulvinar.','2023-06-30 02:30:00',0),
(3,5,'Neighborhood halloween party',NULL,'2023-10-31 12:00:00',1),

(1,7,'Graduation venue','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur scelerisque est nec tincidunt dapibus. Vivamus blandit convallis pulvinar. Vestibulum id elit tincidunt, eleifend odio ut, interdum felis. Duis dignissim maximus elementum. Phasellus nec commodo velit. Vestibulum consectetur purus in risus commodo blandit. Aenean et est fringilla, eleifend risus id, tempus justo. Ut vestibulum, sapien quis aliquam auctor, lectus velit mollis lorem, accumsan facilisis turpis neque ac magna.','2023-06-30 09:30:00',0),
(1,7,'Small open summer wedding',NULL,'2023-07-24 09:00:00',-1),
(2,7,'Farm wedding','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur scelerisque est nec tincidunt dapibus. Vivamus blandit convallis pulvinar. Vestibulum id elit tincidunt, eleifend odio ut, interdum felis. Duis dignissim maximus elementum. Phasellus nec commodo velit. Vestibulum consectetur purus in risus commodo blandit. Aenean et est fringilla, eleifend risus id, tempus justo.','2023-10-23 10:00:00',0),
(2,7,'company New years party',NULL,'2023-12-31 20:30:00',1),
(3,7,'Banquet',NULL,'2023-08-01 09:20:00',0),
(3,7,'Carnival party','Mauris nunc nisl, vehicula sit amet justo sit amet, mattis posuere turpis. Nam vel velit vitae urna pharetra posuere. Proin maximus lobortis massa, sit amet sagittis lacus suscipit nec. Nunc efficitur, dolor bibendum sollicitudin hendrerit, augue quam ullamcorper ipsum, vitae facilisis massa lorem vel arcu.','2023-10-30 21:20:00',1),

(1,8,'Graduation venue','Curabitur scelerisque est nec tincidunt dapibus. Vivamus blandit convallis pulvinar.','2023-06-29 09:30:00',0),
(1,8,'Small open summer wedding',NULL,'2023-07-23 09:00:00',1),
(2,8,'Farm wedding','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur scelerisque est nec tincidunt dapibus. Vivamus blandit convallis pulvinar. Vestibulum id elit tincidunt, eleifend odio ut, interdum felis. Duis dignissim maximus elementum. Phasellus nec commodo velit. Vestibulum consectetur purus in risus commodo blandit. Aenean et est fringilla, eleifend risus id, tempus justo.','2023-10-22 10:00:00',0),
(2,8,'company New years party',NULL,'2023-08-01 09:30:00',-1),
(3,8,'Banquet',NULL,'2023-08-01 09:25:00',0),
(3,8,'Carnival party','Mauris nunc nisl, vehicula sit amet justo sit amet, mattis posuere turpis. Nam vel velit vitae urna pharetra posuere. Proin maximus lobortis massa, sit amet sagittis lacus suscipit nec. Nunc efficitur, dolor bibendum sollicitudin hendrerit, augue quam ullamcorper ipsum, vitae facilisis massa lorem vel arcu.','2023-10-25 20:10:00',1);


-- Table structure for table `service`
CREATE TABLE service (
  service_id int UNSIGNED NOT NULL AUTO_INCREMENT,
  servicer_id int UNSIGNED NOT NULL,
  title varchar(120) NOT NULL,
  description text(750),
  avg_price int UNSIGNED NOT NULL,
  PRIMARY KEY (service_id),
  FOREIGN KEY (servicer_id) REFERENCES user(user_id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

-- Dumping data for table `service`
INSERT INTO service (servicer_id, title, description, avg_price) VALUES 
(4,'Birthday party organization', 'Mauris nunc nisl, vehicula sit amet justo sit amet, mattis posuere turpis. Nam vel velit vitae urna pharetra posuere. Proin maximus lobortis massa, sit amet sagittis lacus suscipit nec. Nunc efficitur, dolor bibendum sollicitudin hendrerit, augue quam ullamcorper ipsum, vitae facilisis massa lorem vel arcu.',120),
(4,'Baby showers',NULL,130.50),
(5,'Christmas party/gathering organization',NULL,95),
(5,'Birthday Parties for Kids','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur scelerisque est nec tincidunt dapibus. Vivamus blandit convallis pulvinar.',55),
(6,'Club and discoquete parties','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur scelerisque est nec tincidunt dapibus. Vivamus blandit convallis pulvinar.',133),
(6,'Festivities, carnivals, small scale parties',NULL,108),
(7,'Bridal Shower organization',NULL,100),
(7,'Graduation venue organization','Vestibulum id elit tincidunt, eleifend odio ut, interdum felis. Duis dignissim maximus elementum. Phasellus nec commodo velit. Vestibulum consectetur purus in risus commodo blandit. Aenean et est fringilla, eleifend risus id, tempus justo. Ut vestibulum, sapien quis aliquam auctor, lectus velit mollis lorem, accumsan facilisis turpis neque ac magna. Mauris nunc nisl, vehicula sit amet justo sit amet, mattis posuere turpis.',166),
(8,'Wedding reception venue and organization','Vestibulum id elit tincidunt, eleifend odio ut, interdum felis. Duis dignissim maximus elementum. Phasellus nec commodo velit. Vestibulum consectetur purus in risus commodo blandit. Aenean et est fringilla, eleifend risus id, tempus justo. Ut vestibulum, sapien quis aliquam auctor, lectus velit mollis lorem, accumsan facilisis turpis neque ac magna. Mauris nunc nisl, vehicula sit amet justo sit amet, mattis posuere turpis.', 760),
(8,'Company organizations',NULL,600),
(9,'Office Organizations for Parties or Speeches',NULL,560),
(9,'Wedding planning and organization','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur scelerisque est nec tincidunt dapibus. Vivamus blandit convallis pulvinar. Vestibulum id elit tincidunt, eleifend odio ut, interdum felis. Duis dignissim maximus elementum. Phasellus nec commodo velit. Vestibulum consectetur purus in risus commodo blandit. Aenean et est fringilla, eleifend risus id, tempus justo. Ut vestibulum, sapien quis aliquam auctor, lectus velit mollis lorem, accumsan facilisis turpis neque ac magna. Mauris nunc nisl, vehicula sit amet justo sit amet, mattis posuere turpis.',699);


-- Table structure for table `service_tag`
CREATE TABLE service_tag (
  service_id int UNSIGNED NOT NULL,
  tag varchar(25),
  PRIMARY KEY (service_id, tag),
  FOREIGN KEY (service_id) REFERENCES service(service_id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

-- Dumping data for table `service_tag`
INSERT INTO service_tag VALUES 
(1,'birthday'),(1,'party'),
(3,'christmas'),(3,'christmas party'),(3,'new years party'),(3,'family'),
(4,'birthday'),(4,'birthday party'),(4,'kids parties'),(4,'childrens party'),
(5,'club'),(5,'doscoquete'),(5, 'disco'),(5,'disco party'),(5,'rave'),
(7,'bridal party'),(7, 'marriage'),
(9,'wedding'),(9,'marriage'),(9,'open wedding'),
(12,'weddings');


-- Table structure for table `service_photo`
CREATE TABLE service_photo (
  service_id int UNSIGNED NOT NULL,
  photo_reference varchar(255) NOT NULL,
  alt_text varchar(255),
  caption varchar(120),
  PRIMARY KEY (service_id, photo_reference),
  FOREIGN KEY (service_id) REFERENCES service(service_id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

INSERT INTO service_photo (service_id, photo_reference) VALUES
(1,'birthday1.jpg'),(1,'birthday2.jpg'),(1,'birthday3.jpg'),
(2,'babyshower1.jpg'),(2,'babyshower2.jpg'),(2,'babyshower3.jpg'),
(3,'christmasparty.jpg'),
(4,'birthdaykids1.jpg'),(4,'birthdaykids2.jpg'),
(5,'disco.jpg'),
(6,'carnival.jpg'),
(7,'bridalshower1.jpg'),(7,'bridalshower2.jpg'),
(8,'graduation1.jpg'),(8,'graduation2.jpg'),
(9,'weddingrec1.jpg'),(9,'weddingrec2.jpg'),(9,'weddingrec3.jpg'),
(10,'orgparty.jpg'),
(11,'officeparty.jpg'),
(12,'wedding1.jpg'),(12,'wedding2.jpg'),(12,'wedding3.jpg');


-- Table structure for table `event`
CREATE TABLE event (
  event_id int UNSIGNED NOT NULL AUTO_INCREMENT,
  requester_id int UNSIGNED NOT NULL,
  organizer_id int UNSIGNED NOT NULL,
  title varchar(120) NOT NULL,
  scheduled_date datetime NOT NULL,
  PRIMARY KEY (event_id),
  FOREIGN KEY (requester_id) REFERENCES user(user_id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  FOREIGN KEY (organizer_id) REFERENCES user(user_id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT requester_schedule UNIQUE (requester_id, scheduled_date)
);

-- Dumping data for table `event`
INSERT INTO event (requester_id, organizer_id, title, scheduled_date) VALUES 
(1,4,'Open summer wedding planning','2024-06-25 10:35:00'),

(1,5,'Fall Theme wedding venue','2023-06-15 10:35:00'),
(2,5,'Birthday party','2023-06-20 10:00:00'),
(1,5,'birthday party for classroom','2023-06-01 12:30:00'),

(1,8,'Office christmas party','2024-12-23 22:30:00'),
(3,8,'Graduation party','2023-06-02 11:00:00'),

(1,9,'Baby shower party','2023-06-05 12:50:00');


-- Table structure for table `event_vendor`
CREATE TABLE event_vendor (
  event_id int UNSIGNED NOT NULL,
  vendor_id int UNSIGNED NOT NULL,
  PRIMARY KEY (event_id, vendor_id),
  FOREIGN KEY (event_id) REFERENCES event(event_id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  FOREIGN KEY (vendor_id) REFERENCES user(user_id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

-- Dumping data for table `event_vendor`
INSERT INTO event_vendor VALUES (5,5),(5,6),(7,4);


-- Table structure for table `post`
CREATE TABLE post (
  post_id int UNSIGNED NOT NULL AUTO_INCREMENT,
  servicer_id int UNSIGNED NOT NULL,
  title varchar(120) NOT NULL,
  PRIMARY KEY (post_id),
  FOREIGN KEY (servicer_id) REFERENCES user(user_id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

-- Dumping data for table `post`
INSERT INTO post (servicer_id, title) VALUES 
(4,'Party for twin kids'),
(4,'Baby shower with nature theme'),
(5,'Christmas gathering'),
(6,'Disco event preparations'),
(7,'Bridal shower'),
(7,'Graduation party for local school'),
(8,'Outside Wedding'),
(9,'Wedding organization');


-- Table structure for table `post_photo`
CREATE TABLE post_photo (
  post_id int UNSIGNED NOT NULL,
  photo_reference varchar(255) NOT NULL,
  alt_text varchar(255),
  caption varchar(120),
  PRIMARY KEY (post_id, photo_reference),
  FOREIGN KEY (post_id) REFERENCES post(post_id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

-- Dumping data for table `post_photo`
INSERT INTO post_photo (post_id, photo_reference) VALUES 
(1,'birthday.jpg'),
(2,'babyshow1.jpg'),(2,'babyshow2.jpg'),
(3,'christmas.jpg'),
(4,'disco.jpg'),
(5,'bridalshower.jpg'),
(6,'graduation.jpg'),
(7,'wedding.jpg'),
(8,'wedding1.jpg');