<?php
defined('BASEPATH') || exit('No direct script access allowed');

#####GENERATED_DEBUG_SETTINGS_START#####

$config["change_password"] = array(
    "check_customer_password" => array(
        "type" => "query",
        "next" => "is_password_match"
    ),
    "is_password_match" => array(
        "type" => "condition",
        "next" => array("finish_customer_pwd_failure", "update_customer_password")
    ),
    "update_customer_password" => array(
        "type" => "query",
        "next" => "finish_customer_pwd_success"
    ),
    "finish_customer_pwd_success" => array(
        "type" => "finish"
    ),
    "finish_customer_pwd_failure" => array(
        "type" => "finish"
    )
);
$config["country_list"] = array(
    "get_country_list" => array(
        "type" => "query",
        "next" => "is_country_list_exists"
    ),
    "is_country_list_exists" => array(
        "type" => "condition",
        "next" => array("finish_country_list_failure", "finish_country_list_success")
    ),
    "finish_country_list_success" => array(
        "type" => "finish"
    ),
    "finish_country_list_failure" => array(
        "type" => "finish"
    )
);
$config["country_with_states"] = array(
    "get_country_data" => array(
        "type" => "query",
        "next" => "is_country_data_exists"
    ),
    "is_country_data_exists" => array(
        "type" => "condition",
        "next" => array("finish_country_data_failure", "country_start_loop")
    ),
    "country_start_loop" => array(
        "type" => "startloop",
        "next" => "get_state_list",
        "end" => "country_end_loop",
        "loop" => array("get_country_data", "array")
    ),
    "get_state_list" => array(
        "type" => "query",
        "next" => "country_end_loop"
    ),
    "country_end_loop" => array(
        "type" => "endloop",
        "next" => "finish_country_data_success"
    ),
    "finish_country_data_success" => array(
        "type" => "finish"
    ),
    "finish_country_data_failure" => array(
        "type" => "finish"
    )
);
$config["crud_delete_mod_customer"] = array(
    "delete_query" => array(
        "type" => "query",
        "next" => "delete_finish"
    ),
    "delete_finish" => array(
        "type" => "finish"
    )
);
$config["crud_insert_mod_customer"] = array(
    "insert_query" => array(
        "type" => "query",
        "next" => "insert_finish"
    ),
    "insert_finish" => array(
        "type" => "finish"
    )
);
$config["crud_select_mod_customer"] = array(
    "select_query" => array(
        "type" => "query",
        "next" => "select_finish"
    ),
    "select_finish" => array(
        "type" => "finish"
    )
);
$config["crud_sel_id_mod_customer"] = array(
    "sel_id_query" => array(
        "type" => "query",
        "next" => "sel_id_finish"
    ),
    "sel_id_finish" => array(
        "type" => "finish"
    )
);
$config["crud_update_mod_customer"] = array(
    "update_query" => array(
        "type" => "query",
        "next" => "update_finish"
    ),
    "update_finish" => array(
        "type" => "finish"
    )
);
$config["customer_add"] = array(
    "check_reg_email_exists" => array(
        "type" => "query",
        "next" => "is_email_available"
    ),
    "is_email_available" => array(
        "type" => "condition",
        "next" => array("finish_customer_reg_failure", "insert_customer_data")
    ),
    "insert_customer_data" => array(
        "type" => "query",
        "next" => "is_customer_added"
    ),
    "is_customer_added" => array(
        "type" => "condition",
        "next" => array("finish_customer_add_failure", "send_registration_email")
    ),
    "send_registration_email" => array(
        "type" => "notifyemail",
        "next" => "finish_customer_add_success"
    ),
    "finish_customer_add_success" => array(
        "type" => "finish"
    ),
    "finish_customer_add_failure" => array(
        "type" => "finish"
    ),
    "finish_customer_reg_failure" => array(
        "type" => "finish"
    )
);
$config["customer_detail"] = array(
    "get_customer_detail" => array(
        "type" => "query",
        "next" => "is_customer_found"
    ),
    "is_customer_found" => array(
        "type" => "condition",
        "next" => array("finish_customer_info_failure", "finish_customer_info_success")
    ),
    "finish_customer_info_success" => array(
        "type" => "finish"
    ),
    "finish_customer_info_failure" => array(
        "type" => "finish"
    )
);
$config["customer_login"] = array(
    "get_customer_login_details" => array(
        "type" => "query",
        "next" => "is_login_found"
    ),
    "is_login_found" => array(
        "type" => "condition",
        "next" => array("finish_customer_login_failure", "finish_customer_login_success")
    ),
    "finish_customer_login_success" => array(
        "type" => "finish"
    ),
    "finish_customer_login_failure" => array(
        "type" => "finish"
    )
);
$config["customer_update"] = array(
    "update_customer_data" => array(
        "type" => "query",
        "next" => "is_customer_updated"
    ),
    "is_customer_updated" => array(
        "type" => "condition",
        "next" => array("finish_customer_update_failure", "finish_customer_update_success")
    ),
    "finish_customer_update_success" => array(
        "type" => "finish"
    ),
    "finish_customer_update_failure" => array(
        "type" => "finish"
    )
);
$config["forgot_password"] = array(
    "get_customer_by_email" => array(
        "type" => "query",
        "next" => "is_customer_exists"
    ),
    "is_customer_exists" => array(
        "type" => "condition",
        "next" => array("finish_customer_pwd_failure", "assign_random_password")
    ),
    "assign_random_password" => array(
        "type" => "variable",
        "next" => "is_password_generated"
    ),
    "is_password_generated" => array(
        "type" => "condition",
        "next" => array("finish_customer_pwd_generation", "change_customer_password")
    ),
    "change_customer_password" => array(
        "type" => "query",
        "next" => "forgot_password_email"
    ),
    "forgot_password_email" => array(
        "type" => "notifyemail",
        "next" => "finish_customer_pwd_success"
    ),
    "finish_customer_pwd_success" => array(
        "type" => "finish"
    ),
    "finish_customer_pwd_generation" => array(
        "type" => "finish"
    ),
    "finish_customer_pwd_failure" => array(
        "type" => "finish"
    )
);#####GENERATED_DEBUG_SETTINGS_END#####
/* End of file cit_wsdebugger.php */
/* Location: ./application/config/cit_wsdebugger.php */