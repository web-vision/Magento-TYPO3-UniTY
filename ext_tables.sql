CREATE TABLE pages (
    unity_path TEXT NOT NULL,
    canonical_url TEXT NOT NULL
);

CREATE TABLE pages_language_overlay (
    unity_path TEXT NOT NULL,
    canonical_url VARCHAR(255) DEFAULT '' NOT NULL
);

CREATE TABLE tt_content (
    hidden_xs TINYINT(3) DEFAULT '0'  NOT NULL,
    hidden_sm TINYINT(3) DEFAULT '0'  NOT NULL,
    hidden_md TINYINT(3) DEFAULT '0'  NOT NULL,
    hidden_lg TINYINT(3) DEFAULT '0'  NOT NULL
);
