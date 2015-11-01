<?php

namespace GreenwichFreecycle\Web\Utilities;

class PageManagement
{
    public function handlePage($template, $templateParameters)
    {
        $html = file_get_contents("../View/" . $template);
        $html = str_replace("{{topNav}}", $this->getTopNav(false), $html);
        if (is_null($templateParameters)) return $html;
        foreach ($templateParameters as $templateParameter) 
        {
            $html = str_replace("{{{$templateParameter->name}}}", $templateParameter->content, $html);
        }
        return $html;
    }

    private function getTopNav($loggedIn)
    {
        if($loggedIn)
        {
            return file_get_contents("../View/topnavloggedin.html");
        }
        return file_get_contents("../View/topnavloggedout.html");;
    }
}

?>