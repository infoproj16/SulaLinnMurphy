DROP TABLE IF EXISTS pages;

/* This is a very simple table for a mysql-php example */
CREATE TABLE pages (
    PageID INT NOT NULL AUTO_INCREMENT,
    OrginizationID INT NOT NULL,
    urlTitle VARCHAR(32) NOT NULL, /* what word goes into the url that distinguishes this page from others */
    pageTitle VARCHAR(32) NOT NULL, /* title shown on bookmarks, tab, etc. */
    menuTitle VARCHAR(32) NOT NULL, /* title shown in menus */
    parent INT, /* parent page */
    bodyTitle VARCHAR(128) NOT NULL, /* title shown in the body of the page */
    body TEXT, /* content of the page (only text for now) */
    PRIMARY KEY (PageID)


/* Insert home page */

DROP TABLE IF EXISTS users;

CREATE TABLE users (
	Userid INT NOT NULL AUTO_INCREMENT,
	email VARCHAR(100) NOT NULL,
	FirstName VARCHAR(25),
	LastName VARCHAR (25),
	hashedpass VARCHAR(255) NOT NULL,
	OrginizationID int,
	Admin int,
	PRIMARY KEY (Userid)
);



Drop table if exists Orginization;
	
Create TABLE Orginization (
	OrginizationID INT NOT NULL,
	OrgTemplateID INT NOT Null,
	primary key (OrginizationID)
);

Create Table OrgTemp(
	OrgTemplateID NOT NULL Auto_Increment,
	TemplateID Int NOT NULL (foreign key),
	OrginizationID INT NOT NULL (foreign key),
	Primary key (OrgTemplateID)
;)

Drop table if exists Template;
Create Table Template (
	TemplateID int not null Auto_Increment,
	primary key (TemplateID)
);

DROP TABLE IF EXISTS UserOrg
Create Table UserOrg (
	UserOrgID NOT NULL AUTO_INCREMENT,
	UserID int not null (foreign key),
	OrginizationID int not null (foreign key),
	Adminlevel int not null,
	Approval int not null,
	PRIMARY key (UserOrgID)
);


