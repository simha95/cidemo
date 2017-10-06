<?php
defined('BASEPATH') || exit('No direct script access allowed');

use Clickatell\Rest;
use Clickatell\ClickatellException;

/**
 * Description of Clickatel SMS Library
 *
 * @category libraries
 * 
 * @package libraries
 *
 * @module SMS
 * 
 * @class Clickatel.php
 * 
 * @path application\libraries\Clickatel.php
 * 
 * @version 4.0
 * 
 * @author CIT Dev Team
 * 
 * @since 01.08.2016
 */
class Clickatel
{

    protected $CI;
    protected $client;
    protected $from_no;

    public function __construct($auth = array())
    {
        $this->CI = & get_instance();
        require_once($this->CI->config->item('third_party') . "clickatell/vendor/autoload.php");

        $this->client = new \Clickatell\Rest($auth['token']);

        if (!empty($auth['from_no'])) {
            $this->setFromNumber($auth['from_no']);
        }
    }

    public function setFromNumber($from_no = '')
    {
        $this->from_no = $from_no;
    }

    public function sendMessage($to_no = '', $message = '')
    {
        try {
            $result = $this->client->sendMessage([
                'to' => [$to_no],
                'content' => $message
            ]);
            if(!$result[0]['accepted']){
                throw new Exception($result[0]['error']);
            }
            $success = 1;
            $message = 'Message sent.';
        } catch (ClickatellException $e) {
            $success = 0;
            $message = $e->getMessage();
        } catch (Exception $e) {
            $success = 0;
            $message = $e->getMessage();
        }
        return array(
            'success' => $success,
            'message' => $message
        );
    }
}

/* End of file Nexmo.php */
/* Location: ./application/libraries/Nexmo.php */