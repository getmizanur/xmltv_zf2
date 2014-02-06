<?php
return array (
    "endpoints" => array (
        "Epg" => array (
            "epg-fetch-todays-data" => array (
                "methods" => array (
                    "MethodName" => "Fetch today's EPG data",
                    "Synopsis" => "Epg schedule",
                    "HttpMethod" => "GET",
                    "URI" => "/:service/programmes/schedules",
                    "parameters" => array (
                        ":service" => array (
                            "Required" => "Y",
                            "Default" => "",
                            "Type" => "Text",
                            "Description" => "Service provider",
                        ),
                    ),
                ),
            ),
            "epg-fetch-yesterdays-data" => array (
                "methods" => array (
                    "MethodName" => "Fetch yesterday's EPG data",
                    "Synopsis" => "Epg schedule",
                    "HttpMethod" => "GET",
                    "URI" => "/:service/programmes/schedules/yesterday",
                    "parameters" => array (
                        ":service" => array (
                            "Required" => "Y",
                            "Default" => "",
                            "Type" => "Text",
                            "Description" => "Service provider",
                        ),
                    ),
                ),
            ),
            "epg-fetch-tomorrows-data" => array (
                "methods" => array (
                    "MethodName" => "Fetch tomorrow's EPG data",
                    "Synopsis" => "Epg schedule",
                    "HttpMethod" => "GET",
                    "URI" => "/:service/programmes/schedules/tomorrow",
                    "parameters" => array (
                        ":service" => array (
                            "Required" => "Y",
                            "Default" => "",
                            "Type" => "Text",
                            "Description" => "Service provider",
                        ),
                    ),
                ),
            ),
        ),
    ),
);
