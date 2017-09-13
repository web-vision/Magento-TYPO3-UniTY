CREATE TABLE pages (
    unity_path TEXT NOT NULL,
    canonical_url TEXT NOT NULL
);

CREATE TABLE pages_language_overlay (
    unity_path TEXT NOT NULL,
    canonical_url VARCHAR(255) DEFAULT '' NOT NULL
);

#
# Table structure for table 'be_users'
#
CREATE TABLE be_users (
	tx_t3unity_standalone tinyint(1) unsigned DEFAULT '1' NOT NULL
);