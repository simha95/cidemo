<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Extended General Library
 *
 * @category libraries
 * 
 * @package libraries
 *
 * @module General
 * 
 * @class Cit_general.php
 * 
 * @path application\libraries\Cit_general.php
 * 
 * @version 4.0
 * 
 * @author CIT Dev Team
 * 
 * @since 01.08.2016
 */
include_once(APPPATH . 'libraries' . DS . 'General.php');

class Cit_general extends General
{

    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Code will be generated dynamically
     * Please do not write or change the content below this line
     * Five hashes must be there on either side of string.
     */
    #####GENERATED_CUSTOM_FUNCTION_START#####

    public function generateRandomPassword($input_params = array())
    {
        $length = 10;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $characters_length = strlen($characters);
        $random_string = '';
        for ($i = 0; $i < $length; $i++)
        {
            $random_string .= $characters[rand(0, $characters_length-1)];
        }
        return $random_string;
    }
    #####GENERATED_CUSTOM_FUNCTION_END#####
}

/* End of file Cit_General.php */
/* Location: ./application/libraries/Cit_General.php */