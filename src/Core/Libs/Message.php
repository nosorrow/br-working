<?php

namespace Core\Libs;

/*
 * Употреба
 *
 * $message = new Libs\Message();
 *
 * $error = $message->get('ErrorFile')->line('line');
 *
 */

class Message
{
    public $dir = null;

    public $_msg = array();


    /**
     * Message constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        if (!empty(request_get('lang'))){

            $this->dir = !empty(request_get('lang'));

        } elseif (!empty(sessionData("lang"))) {

            $this->dir = sessionData("lang");

        } elseif(!empty(get_cookie('lang'))){

            $this->dir = get_cookie('lang');

        } else {
            $this->dir = Config::getConfigFromFile('lang') ? Config::getConfigFromFile('lang') :'bg_BG';

        }
    }

    /**
     * @param $file
     * @return $this
     * @throws \Exception
     */
    public function get($file)
    {
        $path = SYSTEM_DIR . 'Messages' . DIRECTORY_SEPARATOR . $this->dir . DIRECTORY_SEPARATOR . $file . '.php';

        global $message;

        if (realpath($path) && is_readable($path)) {

            include_once $path;

        } else {
            throw new \Exception('No lang file found ->[ ' . $path . ' ]', 500);
        }

        $this->_msg = $message;

        return $this;
    }

    /**
     * @param $line
     * @return mixed
     */
    public function line($line)
    {
        return $this->_msg[$line];
    }

}