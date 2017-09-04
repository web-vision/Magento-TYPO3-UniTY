// Column mode
tx_wvt3unity_column < tx_wvt3unity_abstract
tx_wvt3unity_column {
    typeNum = 3212

    10 = TEMPLATE
    10 {
        template = TEXT
        template {
            value = ###CONTENT###
        }

        marks {
            CONTENT < styles.content.get
            CONTENT {
                select {
                    where = colPos={GP:colPos}
                    where {
                        insertData = 1
                    }
                }
            }
        }
    }
}
