<?php

return [
    /**
     * Activate HoneyPot. If false then the validation rules will always return true
     */
    'activated' => env('HONEYPOT_ACTIVE', true),

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
    'time' => env('HONEYPOT_DEFAULT_TIME', 3),

    /**
     * When honeypot validation fails, this will get used to notify the user
     */
    'failureMessage' => env('HONEYPOT_DEFAULT_FAIL_MESSAGE', 'Possible Spam Attack'),
];
