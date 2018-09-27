<?php

return [
    /**
     * Activate HoneyPot. If false then the validation rules will always return true
     */
    'activated' => true,

    /**
     * Default time in seconds for honeypot_time validation. This is the fallback
     * time difference to validate the honeypot time field. You can override it
     * on a validation by validation case by passing a parameter to honeypot_time like so
     *
     * @code
     * $rules => [
     *  'honeypot_time_field_name' => 'required|honeypot_time:8'
     * ]
     * @endcode
     *
     * The above example will change the time difference to 8 seconds
     */
    'time' => 3,
];
