use usereg;

CREATE TABLE user (
	userid INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	username VARCHAR(20) NOT NULL,
	password VARCHAR(255) NOT NULL,
	email VARCHAR(100) NOT NULL,
	isactive TINYINT(1) DEFAULT 0,
	PRIMARY KEY (userid)
) 
ENGINE=InnoDB DEFAULT CHARACTER SET latin1 
	COLLATE latin1_general_cs AUTO_INCREMENT=0;


/* 
Constraints: 
   1) username should be unique
   2) email should be unique
*/
ALTER TABLE usereg.user
	ADD CONSTRAINT uc_user_username UNIQUE (username); 

ALTER TABLE usereg.user
	ADD CONSTRAINT uc_user_email UNIQUE (email);
