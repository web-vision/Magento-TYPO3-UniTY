mod {
    web_layout {
        BackendLayouts {
            1column {
                title = LLL:EXT:wv_t3unity/Resources/Private/Language/locallang_backend.xlf:backend_layout.1column
                config {
                    backend_layout {
                        colCount = 1
                        rowCount = 1
                        rows {
                            1 {
                                columns {
                                    1 {
                                        name = LLL:EXT:wv_t3unity/Resources/Private/Language/locallang_backend.xlf:backend_layout.column.content
                                        colPos = 1
                                    }
                                }
                            }
                        }
                    }
                }
            }

            2columns-left {
                title = LLL:EXT:wv_t3unity/Resources/Private/Language/locallang_backend.xlf:backend_layout.2columns-left
                config {
                    backend_layout {
                        colCount = 3
                        rowCount = 1
                        rows {
                            1 {
                                columns {
                                    1 {
                                        name = LLL:EXT:wv_t3unity/Resources/Private/Language/locallang_backend.xlf:backend_layout.column.sidebar_left
                                        colPos = 2
                                        colspan = 1
                                    }

                                    2 {
                                        name = LLL:EXT:wv_t3unity/Resources/Private/Language/locallang_backend.xlf:backend_layout.column.content
                                        colPos = 1
                                        colspan = 2
                                    }
                                }
                            }
                        }
                    }
                }
            }

            2columns-right {
                title = LLL:EXT:wv_t3unity/Resources/Private/Language/locallang_backend.xlf:backend_layout.2columns-right
                config {
                    backend_layout {
                        colCount = 3
                        rowCount = 1
                        rows {
                            1 {
                                columns {
                                    1 {
                                        name = LLL:EXT:wv_t3unity/Resources/Private/Language/locallang_backend.xlf:backend_layout.column.content
                                        colPos = 1
                                        colspan = 2
                                    }

                                    2 {
                                        name = LLL:EXT:wv_t3unity/Resources/Private/Language/locallang_backend.xlf:backend_layout.column.sidebar_right
                                        colPos = 3
                                        colspan = 1
                                    }
                                }
                            }
                        }
                    }
                }
            }

            3columns {
                title = LLL:EXT:wv_t3unity/Resources/Private/Language/locallang_backend.xlf:backend_layout.3columns
                config {
                    backend_layout {
                        colCount = 4
                        rowCount = 1
                        rows {
                            1 {
                                columns {
                                    1 {
                                        name = LLL:EXT:wv_t3unity/Resources/Private/Language/locallang_backend.xlf:backend_layout.column.sidebar_left
                                        colPos = 2
                                        colspan = 1
                                    }

                                    2 {
                                        name = LLL:EXT:wv_t3unity/Resources/Private/Language/locallang_backend.xlf:backend_layout.column.content
                                        colPos = 1
                                        colspan = 2
                                    }

                                    3 {
                                        name = LLL:EXT:wv_t3unity/Resources/Private/Language/locallang_backend.xlf:backend_layout.column.sidebar_right
                                        colPos = 3
                                        colspan = 1
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
