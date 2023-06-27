# Event Management System

## Setup
* Download [MySQL](https://www.mysql.com/downloads/) and [XAMPP](https://www.apachefriends.org/download.html) (Make sure MySQL is selected as a component during XAMPP setup)

* Clone the repository by running this command in your Git Bash:
```
git clone https://github.com/VBashari/Event-Management-System.git
```

* Create a MySQL database with the help of `eventmanagementdb.sql` in the `api` folder.

Besides the database structure, the file contains dummy data. To delete it, execute these commands in your MySQL shell:
```
SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE event; TRUNCATE TABLE event_vendor;
TRUNCATE TABLE post; TRUNCATE TABLE post_photo;
TRUNCATE TABLE user; TRUNCATE TABLE request;
TRUNCATE TABLE service; TRUNCATE TABLE service_photo; TRUNCATE TABLE service_tag;
SET FOREIGN_KEY_CHECKS = 1;
```

* In the `api` folder, rename the `.env_example` file to `.env`, and change its contents in accordance with your database's credentials.
* Relocate project to `htdocs` folder in your XAMPP folder.

## Running
Make sure to start Apache and MySQL with the XAMPP control panel, and open the website with [localhost](http://localhost)

## Example server
This project can also be accessed from https://event-management.alwaysdata.net/. Users present on this server can be found at users.txt

# API
This project has an API which you can use for more flexible access. The API provides endpoints for accessing various resources such as services, requests, posts, events, users, and sessions. The API follows RESTful principles and supports HTTP methods like GET, POST, PATCH, and DELETE.

See [API.md](API.md) for the API specification.
