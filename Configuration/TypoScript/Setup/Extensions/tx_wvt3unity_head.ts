// Header data
tx_wvt3unity_head < page
tx_wvt3unity_head {
    typeNum = 3210

    config {
        absRefPrefix = %TYPO3_URL%/
        // set proper content type
        additionalHeaders = Content-Type: application/json; charset=utf-8
        // disable parsetime output
        debug = 0
        // make sure that header data is fetched
        disableAllHeaderCode = 0
        // disable body tag
        disableBodyTag = 1
        // change template for rendering the whole page
        pageRendererTemplateFile = EXT:wv_t3unity/Resources/Private/Templates/PageRenderer/PageRenderer.html
        // write inline CSS/JS in file
        removeDefaultCss = external
        removeDefaultJS = external
        // enable realurl
        tx_realurl_enable = 1
    }

    meta {
        abstract {
            field = abstract
        }

        keywords {
            field = keywords
        }

        description {
            field = description
        }

        author {
            field = author
        }

        author_email {
            field = author_email
        }

        last_updated {
            field = lastUpdated
        }
    }

    10 = TEMPLATE
    10 {
        template = TEXT
        template {
            value (
                "title": "###TITLE###",
                "canonical": "###CANONICAL###",
            )
        }

        marks {
            // Get Content to make sure the CSS/JS/Meta-Tags included in the content are in the header // NO OUTPUT
            content < styles.content.get
            content {
                select {
                    where = colPos = 1
                }
            }

            sidebar_left < styles.content.get
            sidebar_left {
                select {
                    where = colPos = 2
                }
            }

            sidebar_right < styles.content.get
            sidebar_right {
                select {
                    where = colPos = 3
                }
            }

            // relevant data
            TITLE = TEXT
            TITLE {
                data = page:title
            }

            CANONICAL = TEXT
            CANONICAL {
                data = page:canonical_url
            }
        }
    }
}
