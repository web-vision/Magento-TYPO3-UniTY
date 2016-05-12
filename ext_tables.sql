CREATE TABLE pages (
    unity_path TEXT NOT NULL,
    canonical_url TEXT NOT NULL
);

CREATE TABLE pages_language_overlay (
    unity_path TEXT NOT NULL,
    canonical_url VARCHAR(255) DEFAULT '' NOT NULL
);
