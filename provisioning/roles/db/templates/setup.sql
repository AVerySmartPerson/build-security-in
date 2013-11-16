/* 
 * Creates the Build Security In Notes Application database tables.
 *
 * Author: Mark Johnman
 * Last Modified: 17/11/2013 
 */

/* 
 * Ensures that there are no tables with the same name as the ones being created.
 * 
 * It should be noted that the order in which this removal occurs is important as
 * the BSI_Note table contains a foreign key that references the User_ID field in
 * the BSI_User table. If the deletion was done the other way around, it would
 * fail as the deletion of the BSI_User table would remove the reference required
 * by the BSI_Note's foreign key.
 */

DROP TABLE IF EXISTS BSI_Note;
DROP TABLE IF EXISTS BSI_User;

/* Creates the database tables */

CREATE TABLE BSI_User
(
   User_ID INT NOT NULL AUTO_INCREMENT,
   Email VARCHAR (255) NOT NULL,
   Password VARCHAR(255) NOT NULL,
   First_Name VARCHAR (60) NOT NULL,
   Last_Name VARCHAR (60) NOT NULL, 
   Status ENUM ("Active", "Inactive") NOT NULL DEFAULT "Active",
   CONSTRAINT BSI_User_PK PRIMARY KEY (User_ID),
   CONSTRAINT BSI_User_Email_Check CHECK (Email LIKE "^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$"),
   INDEX BSI_User_Email (Email)   
) ENGINE = "MYSIAM";


CREATE TABLE BSI_Note
(
   Note_ID INT NOT NULL AUTO_INCREMENT, 
   User_ID INT NOT NULL,
   Date_Last_Modified TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
   Title VARCHAR (150) NOT NULL,
   Content TEXT NOT NULL, 
   Status ENUM ("Active", "Inactive") NOT NULL DEFAULT "Active",
   CONSTRAINT BSI_Note_PK PRIMARY KEY (Note_ID),
   CONSTRAINT BSI_Note_FK FOREIGN KEY (User_ID) REFERENCES BSI_User(User_ID),
   INDEX BSI_Note_Title (Title)
) ENGINE = "MYSIAM";

