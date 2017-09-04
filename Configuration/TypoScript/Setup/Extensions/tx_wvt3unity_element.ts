// Element mode
tx_wvt3unity_element < tx_wvt3unity_abstract
tx_wvt3unity_element {
    typeNum = 3213

    10 = TEMPLATE
    10 {
        template = TEXT
        template {
            value = ###CONTENT###
        }

        marks {
            CONTENT = RECORDS
            CONTENT {
                tables = tt_content
                dontCheckPid = 1
                source {
                    data = GP:uid
                }
            }
        }
    }
}
