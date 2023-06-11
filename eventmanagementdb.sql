-- Table structure for table `user`
CREATE TABLE user (
  user_id int UNSIGNED NOT NULL AUTO_INCREMENT,
  user_type char(4) NOT NULL,
  username varchar(40) NOT NULL UNIQUE,
  email varchar(50) NOT NULL,
  password varchar(255) NOT NULL,
  PRIMARY KEY (user_id),
  CHECK (user_type IN ('USER', 'VNDR', 'ORG', 'ADMN')),
  UNIQUE (username) 
);

-- Dumping data for table `user`
INSERT INTO user VALUES (1,'USER','user1','User1 Last1', 'user1@gmail.com','password123'),(4,'USER','user2','User2 Last2', 'user2@gmail.com','password123'),(5,'VNDR','vendor1','Vendor1 Last3','vendr1@gmail.com','passi1234'),(6,'VNDR','vendor2','Vendor2 Last4','vendr2@gmail.com','passi1234'),(8,'ORG','event_org1','Organization 1','evorg1@gmail.com','passi234'),(9,'ORG','event_org2','Organization 2', 'evorg2@yahoo.com','password123');


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
INSERT INTO request VALUES (1,4,1,'birthday party for a 6 yo',NULL,'2023-06-04 04:30:00',0),(6,1,6,'quick planning',NULL,'2023-05-24 12:30:00',-1),(9,1,8,'open summer wedding planning',NULL,'2024-06-25 10:35:00',1);


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
INSERT INTO service VALUES (1,8,'birthday party organization','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam luctus finibus turpis, a eleifend ante laoreet vitae. Morbi in purus a lectus faucibus sagittis. In sed aliquam libero, ut fermentum augue. Proin vitae lacus mollis, porta felis ac, fringilla sapien.',75),(2,8,'wedding organization',NULL,500),(3,9,'wedding organizers',NULL,840),(4,6,'prom party organization','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc sodales accumsan magna.',470);


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
INSERT INTO service_tag VALUES (1,'birthday'),(1,'party'),(3,'marriage'),(3,'wedding');


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
INSERT INTO event VALUES (1,1,8,'open summer wedding planning','2024-06-25 10:35:00'),(2,4,8,'testing','2030-02-02 00:00:00'),(6,1,5,'birthday party','2023-12-25 12:30:00'),(7,1,9,'graduation party','2023-12-05 12:50:00'),(8,4,5,'birthday party','2023-12-25 12:30:00');


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
INSERT INTO event_vendor VALUES (1,5),(1,6);


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
INSERT INTO post VALUES (1,5,'testing testing'),(2,8,'hello');


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
INSERT INTO post_photo VALUES (2,'testing.png','wow',NULL),(2,'testing2.png',NULL,NULL);