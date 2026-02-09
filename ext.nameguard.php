<?php

class Nameguard_ext
{
    public $name = 'NameGuard';
    public $version = '1.0.0';
    public $settings = [];
    public $settings_exist = 'n';
    public $required_by = ['module'];

    /**
     * Constructor
     *
     * @param array $settings
     */
    public function __construct($settings = [])
    {
        $this->settings = $settings;
    }

    /**
     * Validate screen name to block suspicious patterns
     * Hook: member_member_register_errors
     *
     * According to EE7 docs, this hook:
     * - Receives $this (Member_register object)
     * - Returns Void
     * - Adds errors via $member_register->errors[] array
     *
     * @param object $member_register The Member_register object
     * @return void
     */
    public function validate_screen_name($member_register)
    {
        ee()->lang->loadfile('nameguard');

        $to_check = [];

        $screen_name = $this->get_post_or_property('screen_name', $member_register);
        if (!empty($screen_name) && is_string($screen_name)) {
            $to_check[] = $screen_name;
        }

        // Also validate username when it's not an email (EE may use it as screen name or display it)
        $username = $this->get_post_or_property('username', $member_register);
        if (!empty($username) && is_string($username) && strpos($username, '@') === false) {
            $to_check[] = $username;
        }

        foreach ($to_check as $value) {
            $error_message = $this->check_name_suspicious($value);
            if ($error_message !== '') {
                if (is_object($member_register) && property_exists($member_register, 'errors')) {
                    $member_register->errors[] = $error_message;
                }
                return;
            }
        }
    }

    private function get_post_or_property($key, $member_register)
    {
        $val = ee()->input->post($key);
        if ($val === false || $val === null || $val === '') {
            if (is_object($member_register) && isset($member_register->{$key})) {
                $val = $member_register->{$key};
            }
        }
        return $val;
    }

    private function check_name_suspicious($name)
    {
        $clean = preg_replace('/[^a-zA-Z]/', '', $name);
        $len = strlen($clean);

        if ($len < 4) {
            return '';
        }

        preg_match_all('/[A-Z]/', $clean, $upper);
        preg_match_all('/[a-z]/', $clean, $lower);
        preg_match_all('/([A-Z][a-z]|[a-z][A-Z])/', $clean, $alts);
        $num_upper = count($upper[0]);
        $num_lower = count($lower[0]);
        $num_alts = count($alts[0]);

        if ($num_upper >= 2 && $num_lower >= 2) {
            if ($num_alts > $len / 3 || ($len >= 8 && $num_alts >= 4)) {
                return $this->lang_message('nameguard_suspicious_name');
            }
        }

        $vowel_count = preg_match_all('/[aeiou]/i', $clean);
        if ($len >= 10 && $vowel_count < $len * 0.15) {
            return $this->lang_message('nameguard_gibberish_name');
        }

        preg_match_all('/[^aeiou]{4,}/i', $clean, $clusters);
        $num_clusters = count($clusters[0]);
        if ($num_clusters >= 2 || ($num_clusters >= 1 && $len >= 12)) {
            return $this->lang_message('nameguard_unreadable_name');
        }

        return '';
    }

    private function lang_message($key)
    {
        $msg = lang($key);
        return $msg !== '' ? $msg : 'Please use a valid screen name.';
    }
}
