<?php

use ceLTIc\LTI;

class Activity
{

    public static $toolUrl = null;

    public static function validate($messageUrl, $targetLinkUri)
    {

        if ($messageUrl !== $targetLinkUri) {
            self::$toolUrl =  $targetLinkUri;
            return true;
        } else {
            return false;
        }
    }

    private static function parse_custom_parameters($customparams)
    {
        $custom = array();
        $params = null;
        if (!empty($customparams)) {
            parse_str(str_replace('&#13;&#10;', '&', $customparams), $custom);
            foreach ($custom as $name => $value) {
                $name = preg_replace('/[^a-z0-9]/', '_', strtolower(trim($name)));
                if (!empty($name)) {
                    $params["custom_{$name}"] = $value;
                }
            }
        }
        return $params;
    }

    private static function check_content_type($contenturl)
    {
        if (str_contains($contenturl['path'], '/mod/curriki') && str_contains($contenturl['query'], 'activity=')) {
            parse_str($contenturl['query'], $params);
            $contentpublicurl = "/lti-tools/activity/" . $params['activity'];
        }

        return $contentpublicurl;
    }

    public static function is_public()
    {
        $post = get_post(intval(sanitize_text_field($_GET['post'])));
        $link_atts = LTI_Platform_Public::get_link_atts($post, sanitize_text_field($_GET['id']));
        $contenturl = parse_url($link_atts['url']);
        $contentpublicurl = self::check_content_type($contenturl);
        $tool = LTI_Platform_Tool::fromCode($link_atts['tool'], LTI_Platform::$ltiPlatformDataConnector);
        $customparams = self::parse_custom_parameters($tool->getSetting('custom'));
        $contentpublicurl = $customparams['custom_currikistudiohost'] . $contentpublicurl;
        $page = <<< EOD
<html>
<head>
<title>1EdTech LTI message</title>
</head>
<body>
<iframe style="border: none; overflow: scroll;" width="100%" height="100%" src="$contentpublicurl" allowfullscreen="true"></iframe>
</body>
</html>
EOD;
        echo $page;
        exit;
    }
}
