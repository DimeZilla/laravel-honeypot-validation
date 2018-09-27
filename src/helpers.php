<?php

/**
 * A quick way to get our honeypot service
 */
if (!function_exists('honeypot')) {
    function honeypot() {
        return app('honeypot');
    }
}

/**
 * Good for our randomized honeypot names
 */
if (!function_exists('honeypot_add_rules')) {
    function honeypot_add_rules($rules = []) {
        return app('honeypot')->addValidationRules($rules);
    }
}
