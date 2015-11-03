<?php

namespace GreenwichFreecycle\Web\Utilities;

require_once (dirname(__DIR__). '/Utilities/Autoloader.php');

use GreenwichFreecycle\Web\Utilities\SessionManagement;

class PageManagement
{
    public function handlePage($template, $templateParameters)
    {
        $html = file_get_contents('../View/' . $template);
        $html = str_replace('{{topNav}}', $this->getTopNav(), $html);
        if (is_null($templateParameters)) return $html;
        foreach ($templateParameters as $templateParameter) 
        {
            $html = str_replace("{{{$templateParameter->name}}}", $templateParameter->content, $html);
        }
        return $html;
    }

    private function getTopNav()
    {
        $user = SessionManagement::instance()->get('user');
        if(is_null($user.loggedIn))
        {
            $html = file_get_contents('../View/topnavloggedin.html');
            $html = str_replace('{{username}}', $user.Username, $html);
            return file_get_contents('../View/topnavloggedin.html');
        }
        return file_get_contents('../View/topnavloggedout.html');
    }
}

?>