<?php

namespace ceLTIc\LTI\OAuth;

/**
 * Class to represent an %OAuth Consumer
 *
 * @copyright  Andy Smith
 * @version  2008-08-04
 * @license  https://opensource.org/licenses/MIT The MIT License
 */
class OAuthConsumer
{

    public $key;
    public $secret;
    public $callback_url;

    function __construct($key, $secret, $callback_url = null)
    {
        $this->key = $key;
        $this->secret = $secret;
        $this->callback_url = $callback_url;
    }

    function __toString()
    {
        return "OAuthConsumer[key=$this->key,secret=$this->secret]";
    }

}
