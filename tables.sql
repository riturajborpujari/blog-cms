
CREATE TABLE IF NOT EXISTS articles
(
	id					smallint unsigned NOT NULL auto_increment,
	publicationDate		date NOT NULL,
	title				varchar(255) NOT NULL,
	summary				text NOT NULL,
	content				mediumtext NOT NULL,

	PRIMARY KEY(id)
);

CREATE TABLE IF NOT EXISTS comments
(
	id					smallint unsigned NOT NULL auto_increment,
	publicationDate		date NOT NULL,
	username			varchar(30) NOT NULL,
	comment				text NOT NULL,
	articleId			smallint unsigned NOT NULL,
	PRIMARY KEY (id, articleId)
);