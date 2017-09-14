#
# Table structure for table 'pages'
#
CREATE TABLE pages (
    unity_path text,
    canonical_url text
);

#
# Table structure for table 'pages_language_overlay'
#
CREATE TABLE pages_language_overlay (
    unity_path text,
    canonical_url VARCHAR(255) DEFAULT '' NOT NULL
);
