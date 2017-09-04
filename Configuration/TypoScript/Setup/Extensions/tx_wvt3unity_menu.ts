// Menu mode
tx_wvt3unity_menu < tx_wvt3unity_abstract
tx_wvt3unity_menu {
    typeNum = 3214

    10 = CASE
    10 {
        key.data = GP:layout

        default = CASE
        default {
            key {
                data = GP:special
            }

            default = HMENU
            default {
                special = directory
                special.value.data = GP:special-value
                excludeUidList.value.data = GP:exclude-uid-list
                entryLevel.value.data = GP:entry-level

                wrap = <nav class="t3-menu t3-directory">|</nav>

                1 = TMENU
                1 {
                    expAll = 1
                    wrap = <ul class="t3-menu-list">|</ul>
                    NO = 1
                    NO {
                        wrapItemAndSub = <li class="first">|</li> |*| <li>|</li> |*| <li class="last">|</li>
                    }

                    ACT < .NO
                    ACT {
                        wrapItemAndSub = <li class="first active">|</li> |*| <li class="active">|</li> |*| <li class="last active">|</li>
                    }

                    IFSUB < .NO
                    IFSUB {
                        wrapItemAndSub = <li class="first hassub">|</li> |*| <li class="hassub">|</li> |*| <li class="last hassub">|</li>
                    }

                    ACTIFSUB < .NO
                    ACTIFSUB {
                        wrapItemAndSub = <li class="first active hassub">|</li> |*| <li class="active hassub">|</li> |*| <li class="last active hassub">|</li>
                    }
                }

                2 < .1
                2 {
                    wrap = <ul class="t3-menu-sublist">|</ul>
                }

                3 < .2
            }

            list < .default
            list {
                wrap = <nav class="t3-menu t3-list">|</nav>
                special = list
                1 {
                    expAll = 1
                    IFSUB < .NO
                    ACTIFSUB < .ACT
                }
            }
        }

        menuItem < .default
        menuItem {
            default {
                wrap >
                1 {
                    wrap >
                    NO.wrapItemAndSub = <li>|</li>
                    ACT.wrapItemAndSub = <li class="active">|</li>
                    IFSUB.wrapItemAndSub = <li class="hassub">|</li>
                    ACTIFSUB.wrapItemAndSub = <li class="active hassub">|</li>
                }
            }

            list {
                wrap >
                1 {
                    wrap >
                    NO.wrapItemAndSub = <li>|</li>
                    ACT.wrapItemAndSub = <li class="active">|</li>
                    IFSUB.wrapItemAndSub = <li class="hassub">|</li>
                    ACTIFSUB.wrapItemAndSub = <li class="active hassub">|</li>
                }
            }
        }
    }
}
