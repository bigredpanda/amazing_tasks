<?php

namespace Core;

use Twig_Environment;
use Twig_Loader_Filesystem;

/**
 * Class Controller
 * @package Core
 * @author Pavel Spichak
 */
class Controller
{

    protected $twig;
    protected $user;


    function __construct()
    {
        $this->user = App::getUser();
        $loader = new Twig_Loader_Filesystem(ROOT . '/app/src/App/View');
        $this->twig = new Twig_Environment($loader);
        $this->twig->addGlobal('user', $this->user);
        $this->twig->addGlobal('site_name', Config::get('site_name'));
        $this->twig->addGlobal('flash', Session::get('flash'));

        Session::delete('flash');
    }


    protected function getUser(): array
    {
        return $this->user;
    }
}