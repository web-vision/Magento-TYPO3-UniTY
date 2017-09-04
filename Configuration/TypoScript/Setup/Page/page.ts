page = PAGE
page {
    meta {
        X-UA-Compatible >
    }

    10 {
        templateName {
            stdWrap {
                ifEmpty = 1column
            }
        }

        templateRootPaths {
            10 = EXT:wv_t3unity/Resources/Private/Templates/
        }

        partialRootPaths {
            10 = EXT:wv_t3unity/Resources/Private/Partials/
        }

        layoutRootPaths {
            10 = EXT:wv_t3unity/Resources/Private/Layouts/
        }

        // Set variables in templates and render content
        // Variables in template
        variables {
            content < styles.content.get
            content {
                select {
                    where = colPos = 1
                }
            }

            sidebarLeft < styles.content.get
            sidebarLeft {
                select {
                    where = colPos = 2
                }
            }

            sidebarRight < styles.content.get
            sidebarRight {
                select {
                    where = colPos = 3
                }
            }
        }
    }
}
