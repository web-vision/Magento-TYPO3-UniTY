#
# Table structure for table 'pages'
#
CREATE TABLE pages (
	unity_path text NOT NULL,
	canonical_url text NOT NULL
);

#
# Table structure for table 'pages_language_overlay'
#
CREATE TABLE pages_language_overlay (
	unity_path text NOT NULL,
	canonical_url varchar(255) DEFAULT '' NOT NULL
);

#
# Table structure for table 'tt_content'
#
CREATE TABLE tt_content (
  hidden_xs tinyint(3) DEFAULT '0' NOT NULL,
  hidden_sm tinyint(3) DEFAULT '0' NOT NULL,
  hidden_md tinyint(3) DEFAULT '0' NOT NULL,
  hidden_lg tinyint(3) DEFAULT '0' NOT NULL
);
