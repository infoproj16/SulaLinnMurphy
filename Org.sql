DROP TABLE IF EXISTS pages;

Drop table if exists Organization;
	
Create TABLE Organization (
	OrganizationID INT NOT NULL AUTO_INCREMENT,
	OrgTemplateID INT NOT Null,
	primary key (OrganizationID)
);


/* This is a very simple table for a mysql-php example */
CREATE TABLE pages (
    PageID INT NOT NULL AUTO_INCREMENT,
    OrganizationID INT NOT NULL,
    urlTitle VARCHAR(32) NOT NULL, /* what word goes into the url that distinguishes this page from others */
    pageTitle VARCHAR(32) NOT NULL, /* title shown on bookmarks, tab, etc. */
    menuTitle VARCHAR(32) NOT NULL, /* title shown in menus */
    parent INT, /* parent page */
    bodyTitle VARCHAR(128) NOT NULL, /* title shown in the body of the page */
    body TEXT, /* content of the page (only text for now) */
    PRIMARY KEY (PageID)
);

/* Insert home page */
INSERT INTO Organization (OrgTemplateID) VALUES (1);
INSERT INTO pages (urlTitle, pageTitle, menuTitle, parent, bodyTitle, body, OrganizationID) VALUES ("home", "Home - Soccer Lover's Club", "home", -1, "Welcome to the Soccer Lover's Club", "Cleats, goals, and tackles.", 1);


DROP TABLE IF EXISTS users;

CREATE TABLE users (
	Userid INT NOT NULL AUTO_INCREMENT,
	email VARCHAR(100) NOT NULL,
	hashedpass VARCHAR(255) NOT NULL,
	OrganizationID int NOT NULL,
	Admin int NOT NULL,
	PRIMARY KEY (Userid)
);


Create Table OrgTemp(
	OrgTemplateID INT NOT NULL Auto_Increment,
	TemplateID Int,
	OrganizationID INT,
	Primary key (OrgTemplateID)
);

Drop table if exists Template;

Create Table Template (
	TemplateID int not null Auto_Increment,
	primary key (TemplateID)
);
