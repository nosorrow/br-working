<?php

namespace Core\Libs;

use Core\Libs\Session;

class Csrf
{

    /**
     * @var Session
     */
    public $session;
    /**
     * @var
     */
    public $input;
    /**
     * @var mixed|null
     */
    public $csrf_old_token = null;

    /**
     * @var null
     */
    public $csrf_token = null;

    /**
     * Csfr constructor.
     * @param \Core\Libs\Request $request
     */
    public function __construct(Request $request)
    {
        $this->input = $request;

        $this->session = Session::getInstance();

        if ($this->session->getData('csrf_token')) {

            $this->csrf_old_token = $this->session->getData('csrf_token');
        }
    }

    /**
     * @return string
     */
    public function _token()
    {
        $token = md5(bin2hex(openssl_random_pseudo_bytes(5)));

        return $token;

    }

    /**
     * @return csrf_field
     */
    public function csrf_field()
    {

        $this->csrf_token = $this->_token();

        $this->session->store('csrf_token', $this->csrf_token);

        echo "<input type='hidden' name='csrf_token' id='csrf_token' value='" . $this->csrf_token . "' />";
    }

    /**
     * @return bool
     */
    public function csrf_validate()
    {
        $input = $this->input;

        if ($input->method() == 'POST' && $input->post('csrf_token') == $this->csrf_old_token) {

            return true;

        } else {

            return false;
        }

    }

    /**
     * @param $name
     * @param $arguments
     * @throws \Exception
     */
    public function __call($name, $arguments)
    {
        throw new \Exception("Несъществуващ Csrf метод '$name' "
            . implode(', ', $arguments));
    }

}