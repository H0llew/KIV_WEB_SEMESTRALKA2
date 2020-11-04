<?php

/**
 * Rozrhrani pro vsechny ovladace
 */
interface IController
{
    /**
     * Preda kod stranky ve stringu
     *
     * @param string $pageTitle titulek stranky
     * @return string kod stranky
     */
    public function show(string $pageTitle): string;
}