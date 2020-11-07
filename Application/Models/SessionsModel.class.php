<?php

/**
 * Trida zajistuje veskerou praci s sessions
 */
class SessionsModel
{
    /**
     * Po vytvoreni nove instance tridy zahaj session
     */
    public function __construct()
    {
        if (!isset($_SESSION))
            session_start();
    }

    /**
     * Ulozi novou hodnotu do session
     *
     * @param string $name nazev
     * @param mixed $value hodnota
     */
    public function addSession(string $name, $value)
    {
        $_SESSION[$name] = $value;
    }

    /**
     * Odstrani session
     *
     * @param string $name nazev
     */
    public function removeSession(string $name)
    {
        unset($name);
    }

    /**
     * Existuje session ..nazev..?
     *
     * @param string $name nazev
     * @return bool je session
     */
    public function isSession(string $name)
    {
        return isset($_SESSION[$name]);
    }

    /**
     * Vrati hodnotu session nebo null
     *
     * @param string $name nazev
     * @return mixed|null hodnota session nebo null
     */
    public function readSession(string $name)
    {
        if ($this->isSession($name))
            return $_SESSION[$name];
        return null;
    }
}