<?php
defined('BASEPATH') || exit('No direct script access allowed');

#####GENERATED_CONFIG_SETTINGS_START#####
//Admin Settings               
$config["admin"] = array(
    "name" => "ADMIN_ADMIN",
    "link" => "user/admin/index",
    "table" => "mod_admin",
    "primary" => "iAdminId",
    "cols" => array(
        "iAdminId" => array(
            "name" => "iAdminId",
            "type" => "int"
        ),
        "vName" => array(
            "name" => "ADMIN_NAME",
            "type" => "varchar",
            "rules" => array(
                "required" => TRUE
            )
        ),
        "vEmail" => array(
            "name" => "ADMIN_EMAIL",
            "type" => "varchar",
            "rules" => array(
                "required" => TRUE,
                "email" => TRUE
            )
        ),
        "vUserName" => array(
            "name" => "ADMIN_USER_NAME",
            "type" => "varchar",
            "rules" => array(
                "required" => TRUE
            )
        ),
        "vPassword" => array(
            "name" => "ADMIN_PASSWORD",
            "type" => "varchar",
            "rules" => array(
                "required" => TRUE
            )
        ),
        "vPhonenumber" => array(
            "name" => "ADMIN_PHONENUMBER",
            "type" => "varchar",
            "null" => TRUE,
            "rules" => array(
                "required" => TRUE
            )
        ),
        "iGroupId" => array(
            "name" => "ADMIN_GROUP",
            "type" => "int",
            "null" => TRUE,
            "rules" => array(
                "required" => TRUE
            ),
            "lookup" => array(
                "type" => "table",
                "table" => array("mod_group_master","iGroupId","vGroupName")
            )
        ),
        "eStatus" => array(
            "name" => "ADMIN_STATUS",
            "type" => "enum",
            "null" => TRUE,
            "rules" => array(
                "required" => TRUE
            ),
            "lookup" => array(
                "type" => "list",
                "list" => array("Active","Inactive",)
            )
        ),
        "dLastAccess" => array(
            "name" => "ADMIN_LAST_ACCESS",
            "type" => "datetime",
            "null" => TRUE,
            "hide" => TRUE
        )
    ),
    "unique" => array(
        "type" => "OR",
        "cols" => array("vEmail","vUserName")
    )
);
//Country Settings               
$config["country"] = array(
    "name" => "COUNTRY_COUNTRY",
    "link" => "tools/country/index",
    "table" => "mod_country",
    "primary" => "iCountryId",
    "cols" => array(
        "iCountryId" => array(
            "name" => "iCountryId",
            "type" => "int"
        ),
        "vCountry" => array(
            "name" => "COUNTRY_COUNTRY",
            "type" => "varchar",
            "rules" => array(
                "required" => TRUE
            )
        ),
        "vCountryCode" => array(
            "name" => "COUNTRY_COUNTRY_CODE",
            "type" => "char",
            "rules" => array(
                "required" => TRUE,
                "minlength" => 2,
                "maxlength" => 2
            )
        ),
        "vCountryCodeISO_3" => array(
            "name" => "COUNTRY_COUNTRY_CODE_ISO_C453",
            "type" => "char",
            "null" => TRUE,
            "rules" => array(
                "required" => TRUE
            )
        ),
        "tDescription" => array(
            "name" => "COUNTRY_DESCRIPTION",
            "type" => "text",
            "null" => TRUE
        ),
        "eStatus" => array(
            "name" => "COUNTRY_STATUS",
            "type" => "enum",
            "null" => TRUE,
            "rules" => array(
                "required" => TRUE
            ),
            "lookup" => array(
                "type" => "list",
                "list" => array("Active","Inactive",)
            )
        )
    ),
    "unique" => array(
        "type" => "AND",
        "cols" => array("vCountryCode")
    )
);
//State Settings               
$config["state"] = array(
    "name" => "STATE_STATE",
    "link" => "tools/state/index",
    "table" => "mod_state",
    "primary" => "iStateId",
    "cols" => array(
        "iStateId" => array(
            "name" => "iStateId",
            "type" => "int"
        ),
        "iCountryId" => array(
            "name" => "STATE_COUNTRY",
            "type" => "int",
            "null" => TRUE,
            "rules" => array(
                "required" => TRUE
            ),
            "lookup" => array(
                "type" => "table",
                "table" => array("mod_country","iCountryId","vCountry")
            )
        ),
        "vState" => array(
            "name" => "STATE_STATE",
            "type" => "varchar",
            "rules" => array(
                "required" => TRUE
            )
        ),
        "vStateCode" => array(
            "name" => "STATE_STATE_CODE",
            "type" => "varchar",
            "rules" => array(
                "required" => TRUE
            )
        ),
        "eStatus" => array(
            "name" => "STATE_STATUS",
            "type" => "enum",
            "null" => TRUE,
            "rules" => array(
                "required" => TRUE
            ),
            "lookup" => array(
                "type" => "list",
                "list" => array("Active","Inactive",)
            )
        ),
        "vCountryCode" => array(
            "name" => "STATE_COUNTRY_CODE",
            "type" => "varchar",
            "null" => TRUE,
            "hide" => TRUE
        )
    ),
    "unique" => array(
        "type" => "AND",
        "cols" => array("vStateCode","iCountryId")
    )
);
#####GENERATED_CONFIG_SETTINGS_END#####

/* End of file cit_importdata.php */
/* Location: ./application/config/cit_importdata.php */                
