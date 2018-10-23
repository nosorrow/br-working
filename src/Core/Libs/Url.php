<?php

namespace Core\Libs;

/**
 * Class Url
 * @package Core\Libs
 */
class Url
{
    protected static $instance;

    public $urlnormalizer;

    public $session;

    public $site_url;

    public $base_url;

    public $index_page;

    public $_previous;

    public $_current;

    /**
     * Url constructor.
     */
    protected function __construct()
    {
        $this->session = Session::getInstance();
        $this->urlInit();
        $this->session->store('_previous', $this->getReferer());
    }

    /**
     *
     */
    private function urlInit()
    {
        $base_url = Config::getConfigFromFile('base_url');

        $index_page = Config::getConfigFromFile('index_page') . '/';

        if ($index_page == '') {
            $this->index_page = null;
        } else {
            $this->index_page = $index_page;
        }

        if (!empty($base_url)) {
            $this->base_url = $base_url . $index_page;

        } else {
            // ако не е хардкоднат $base_url го взима от глобалната променлива
            $request_scheme = (!$_SERVER['REQUEST_SCHEME']) ? Config::getConfigFromFile('REQUEST_SCHEME') : $_SERVER['REQUEST_SCHEME'];
            $this->base_url = $request_scheme . '://' . $_SERVER['HTTP_HOST'] .
                substr($_SERVER['SCRIPT_NAME'], 0, strpos($_SERVER['SCRIPT_NAME'], basename($_SERVER['SCRIPT_FILENAME'])))
                . $index_page;
        }

        $n = new UrlNormalizer($this->base_url);

        $this->base_url = $n->normalize();

        if ($this->session->getData('_previous')) {
            $this->_previous = $this->session->getData('_previous');

        }
    }

    /**
     * @param $path
     * @return bool
     */
    public function isValidUrl($path)
    {
        if (!preg_match('~^(#|//|https?://|mailto:|tel:)~', $path)) {
            return filter_var($path, FILTER_VALIDATE_URL) !== false;
        }

        return true;
    }

    /**
     * @return mixed
     */
    public function getBaseUrl()
    {
        return $this->base_url;
    }

    /**
     * @param null $uri
     * @return mixed
     */
    public function getSiteUrl($uri = null)
    {
        $uri = XssSecure::xss_clean($uri);
        $n = new UrlNormalizer($this->getBaseUrl() . $uri);

        return $n->normalize();
    }

    /**
     *
     * @param null $uri
     * @return bool|string
     */
    public function getMediaUrl($uri = null)
    {
        return substr(str_replace("index.php/", "", $this->getSiteUrl($uri)), 0);
    }

    /**
     * @return mixed
     */
    public function getReferer()
    {
        $_referer = new UrlNormalizer($_SERVER["HTTP_REFERER"]);

        return $_referer->normalize();
    }

    /**
     * @return null
     */
    public function getPrevious()
    {
        return $this->_previous;
    }


    /**
     * Redirect to previous
     */
    public function redirect_back()
    {

        header("location:" . $this->_previous);
    }

    /**
     * @param string $text
     * @return string
     */
    public function link_back($text = 'back', $button = false)
    {
        if ($button !== false) {
            $btn = $button;
        } else {
            $btn = '';
        }

        return '<a class="' . $btn . '" href="' . $this->_previous . '">' . $text . '</a>';
    }

    /**
     * @return Session|null
     */
    public static function getInstance()
    {
        if (self::$instance === null) {

            self::$instance = new self();
        }

        return self::$instance;
    }
}