<?php
/**
 * Created by PhpStorm.
 * User: shakti
 * Date: 12/22/16
 * Time: 4:53 PM
 */
namespace Core\Session;
use Aura\Session\SessionFactory;

class Session
{
    /**
     * Session constructor.
     */
    protected function __construct()
    {

    }

    /**
     * Initialize Session Library
     * @return \Aura\Session\Segment
     */
    private static function init()
    {
        $session_factory = new SessionFactory;
        $session = $session_factory->newInstance($_COOKIE);
        $session->setCookieParams(array('lifetime' => $_ENV['session_life']));
        $session->setName($_ENV['session_prefix'].'session');
        $segment = $session->getSegment('Vendor\Package\ClassName');
        return $segment;
    }

    /**
     * Set session Data
     * @param $key
     * @param $value
     */
    public static function set($key, $value)
    {
        $segment = self::init();
        $segment->set($key, $value);
    }

    /**
     * Get session Data
     * @param null $key
     * @return mixed
     */
    public static function get($key=null)
    {
        $segment = self::init();
        if($key==null)
        {
            return flase;
        }
        else
        {
            return $segment->get($key);
        }
    }

    /**
     * Checks if session has data
     * @param $key
     * @return bool
     */
    public static function has($key)
    {
        $segment = self::init();
        if($segment->get($key) == null)
        {
            return false;
        }
        else
        {
            return true;
        }
    }
}