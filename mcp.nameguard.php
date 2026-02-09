<?php

class Nameguard_mcp
{
    /**
     * Constructor
     */
    public function __construct()
    {
        // Nothing to do here - extension-only add-on
    }

    /**
     * Index page (required but not used)
     *
     * @return string
     */
    public function index()
    {
        return '<p>NameGuard has no settings. The extension is active and protecting member registrations.</p>';
    }
}
