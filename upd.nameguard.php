<?php

class Nameguard_upd
{
    public $version = '1.0.0';

    /**
     * Install the add-on
     *
     * @return bool
     */
    public function install()
    {
        // Register the module for installation tracking
        ee()->db->insert('modules', [
            'module_name' => 'Nameguard',
            'module_version' => $this->version,
            'has_cp_backend' => 'n',
            'has_publish_fields' => 'n'
        ]);

        // Register the extension hook for registration errors
        ee()->db->insert('extensions', [
            'class' => 'Nameguard_ext',
            'method' => 'validate_screen_name',
            'hook' => 'member_member_register_errors',
            'settings' => '',
            'priority' => 5,
            'version' => $this->version,
            'enabled' => 'y'
        ]);

        return true;
    }

    /**
     * Uninstall the add-on
     *
     * @return bool
     */
    public function uninstall()
    {
        // Remove the module
        ee()->db->where('module_name', 'Nameguard')->delete('modules');

        // Remove the extension
        ee()->db->where('class', 'Nameguard_ext')->delete('extensions');

        return true;
    }

    /**
     * Update the add-on
     *
     * @param string $current Current version
     * @return bool
     */
    public function update($current = '')
    {
        if (version_compare($current, $this->version, '==')) {
            return false;
        }

        return true;
    }
}
