
* Create tables

CREATE TABLE users (
	uid INT UNSIGNED AUTO_INCREMENT NOT NULL,
	username VARCHAR(50) NOT NULL,
	email VARCHAR(50) NOT NULL,
	token VARCHAR(255) NOT NULL,
	UNIQUE(username),
	PRIMARY KEY (uid)
	);
<p>&nbsp;</p>  	

* Token is fixed size but it is recommended to use VARCHAR(255)
* PASSWORD_DEFAULT - Use the bcrypt algorithm (default as of PHP 5.5.0).
* Note that this constant is designed to change over time as new and stronger algorithms are added to PHP.
* For that reason, the length of the result from using this identifier can change over time. 
* Therefore, it is recommended to store the result in a database column that can expand beyond 60 characters (255  characters would be a good choice).  
<p>&nbsp;</p>


CREATE TABLE tasks (
	id INT UNSIGNED AUTO_INCREMENT NOT NULL,
	title VARCHAR(50) NOT NULL,
	description VARCHAR(255) NOT NULL,
	create_date TIMESTAMP,
	state INT(1) NOT NULL,
	uname VARCHAR(50) NOT NULL,
	FOREIGN KEY (uname)REFERENCES users(username) ON DELETE CASCADE,
	PRIMARY KEY (id)
	);	