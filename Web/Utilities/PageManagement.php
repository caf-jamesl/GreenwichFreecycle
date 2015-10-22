<?php

namespace GreenwichFreecycle\Web\Utilities;

class PageManagement
{
    public function handlePage($template, $parameters)
    {
        $html = file_get_contents("../View/" . $template);
        $html = str_replace("{{topNav}}", $this->getTopNav, $html);

        foreach ($parameters as $parameter) 
        {
            $html = str_replace("{{usernameInputErrorMessage}}", $usernameInputErrorMessage, $html);
        }
        $html = str_replace("{{usernameInputErrorMessage}}", $usernameInputErrorMessage, $html);
    }

    private function getTopNav($loggedIn)
    {
        if($loggedIn)
        {
            return $html;
        }
        return $html;
    }
}

?>
