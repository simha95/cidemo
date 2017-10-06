<?php

defined('BASEPATH') OR exit('No direct script access allowed');

#####GENERATED_CONFIG_SETTINGS_START#####

$config["change_password"] = array(
    "title" => "Change Password",
    "folder" => "user",
    "method" => "BOTH",
    "params" => array(
        "old_password",
        "new_password",
        "customer_id"
    )
);
$config["country_list"] = array(
    "title" => "Country List",
    "folder" => "tools",
    "method" => "BOTH",
    "params" => array(
    )
);
$config["country_with_states"] = array(
    "title" => "Country With States",
    "folder" => "tools",
    "method" => "BOTH",
    "params" => array(
        "country_id"
    )
);
$config["crud_delete_mod_customer"] = array(
    "title" => "crud_delete_mod_customer",
    "folder" => "user",
    "method" => "BOTH",
    "params" => array(
        "mc_customer_id"
    )
);
$config["crud_insert_mod_customer"] = array(
    "title" => "crud_insert_mod_customer",
    "folder" => "user",
    "method" => "POST",
    "params" => array(
        "first_name",
        "last_name",
        "email",
        "user_name",
        "password",
        "profile_image",
        "registered_date",
        "status"
    )
);
$config["crud_select_mod_customer"] = array(
    "title" => "crud_select_mod_customer",
    "folder" => "user",
    "method" => "GET",
    "params" => array(
    )
);
$config["crud_sel_id_mod_customer"] = array(
    "title" => "crud_sel_id_mod_customer",
    "folder" => "user",
    "method" => "GET",
    "params" => array(
        "customer_id"
    )
);
$config["crud_update_mod_customer"] = array(
    "title" => "crud_update_mod_customer",
    "folder" => "user",
    "method" => "POST",
    "params" => array(
        "first_name",
        "customer_id",
        "last_name",
        "email",
        "user_name",
        "password",
        "profile_image",
        "registered_date",
        "status"
    )
);
$config["customer_add"] = array(
    "title" => "Customer Add",
    "folder" => "user",
    "method" => "BOTH",
    "params" => array(
        "first_name",
        "last_name",
        "email",
        "username",
        "password",
        "profile_image"
    )
);
$config["customer_detail"] = array(
    "title" => "Customer Detail",
    "folder" => "user",
    "method" => "BOTH",
    "params" => array(
        "customer_id"
    )
);
$config["customer_login"] = array(
    "title" => "Customer Login",
    "folder" => "user",
    "method" => "BOTH",
    "params" => array(
        "username",
        "password"
    )
);
$config["customer_update"] = array(
    "title" => "Customer Update",
    "folder" => "user",
    "method" => "BOTH",
    "params" => array(
        "customer_id",
        "first_name",
        "last_name",
        "profile_image"
    )
);
$config["forgot_password"] = array(
    "title" => "Forgot Password",
    "folder" => "user",
    "method" => "BOTH",
    "params" => array(
        "email"
    )
);#####GENERATED_CONFIG_SETTINGS_END#####

/* End of file cit_webservices.php */
/* Location: ./application/config/cit_webservices.php */
    