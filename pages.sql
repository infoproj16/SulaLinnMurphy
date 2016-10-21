DROP TABLE IF EXISTS pages;

/* This is a very simple table for a mysql-php example */
CREATE TABLE pages (
    id INT NOT NULL AUTO_INCREMENT,
    urlTitle VARCHAR(32) NOT NULL, /* what word goes into the url that distinguishes this page from others */
    pageTitle VARCHAR(32) NOT NULL, /* title shown on bookmarks, tab, etc. */
    menuTitle VARCHAR(32) NOT NULL, /* title shown in menus */
    parent INT, /* parent page */
    bodyTitle VARCHAR(128) NOT NULL, /* title shown in the body of the page */
    body TEXT, /* content of the page (only text for now) */
    PRIMARY KEY (ID)
);

/* Insert home page */
INSERT INTO pages (urlTitle, pageTitle, menuTitle, parent, bodyTitle, body) VALUES ("home", "Home - Student Org", "home", -1, "Welcome to Your Page", "Events, News, & Information");
