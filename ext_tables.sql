CREATE TABLE pages (
    unity_path text,
    canonical_url text
);

CREATE TABLE pages_language_overlay (
    unity_path text,
    canonical_url VARCHAR(255) DEFAULT '' NOT NULL
);

CREATE TABLE be_users (
    tx_t3unity_standalone tinyint(1) unsigned DEFAULT '1' NOT NULL
);
