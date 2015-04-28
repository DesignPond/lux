<?php

class IpAuthMiddleware extends \Slim\Middleware {

    public function call()
    {
        $granted = array('194.126.200.59','130.125.41.184');

        if(!in_array($_SERVER['REMOTE_ADDR'],$granted))
        {
            return $this->app->redirect('/');
        }

        $this->next->call();
    }

}