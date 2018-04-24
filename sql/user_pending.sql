use usereg;

CREATE TABLE user_pending (
	userid INTEGER UNSIGNED NOT NULL,
	token CHAR(10) NOT NULL,
	create_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY (userid)
		REFERENCES user(userid)
)
ENGINE=InnoDB DEFAULT CHARACTER SET latin1
	COLLATE latin1_general_cs;
	
/*
Constraints:
	1) userid should be unique
*/

ALTER TABLE usereg.user_pending
	ADD CONSTRAINT uc_userPending_userid UNIQUE (userid); 