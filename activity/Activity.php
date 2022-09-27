<?php
use ceLTIc\LTI;

Class Activity {

    public static $toolUrl = null;

    public static function validate($messageUrl, $targetLinkUri){

        if($messageUrl !== $targetLinkUri ){
            self::$toolUrl =  $targetLinkUri;
            return true;
        }else{
            return false;
        }
    }
}