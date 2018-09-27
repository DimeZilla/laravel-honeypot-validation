<?php

namespace DiamondHoneyPot;

use DiamondHoneyPot\Utilities\Randomizer;
use Illuminate\Support\Facades\Crypt;

/**
 * Our main HoneyPot generator
 */
class HoneyPot
{

    private $timeDiff = 3;

    private $active = true;

    /**
     * Creates our honeypot form field
     * @return string
     */
    public function make($name = '')
    {
        // If nothing gets passed to us, then we are going
        // to make this session based. The user must then use
        // the honeypot_add_rules to their validation rules
        if (empty($name)) {
            $name = $this->getRandom();
            // tell the session what our honeypot name is
            $existing_names = $this->sessionNames();
            // make sure the name is unique
            while (in_array($name, $existing_names)) {
                $name = $this->getRandom();
            }
            session()->push('honeypot_names', $name);
        }

        if (!empty(config('honeypot.time'))) {
            $this->timeDiff = config('honeypot.time');
        }

        if (!empty(config('honeypot.active'))) {
            $this->active = config('honeypot.active');
        }

        $html = '<div class="wrap" style="display: none;">';
        $html .= '<input name="' . $name . '" type="text" autocomplete="off" value="" />';
        $html .= '<input name="' . $name . '_time" type="text" autocomplete="off" value="' . $this->getEncryptedTime() . '" />';
        $html .= '</div>';
        return $html;
    }

    /**
     * gets the already stored honeypot names
     * @return array
     */
    private function sessionNames()
    {
        return session()->get('honeypot_names');
    }

    /**
     * Validates our honeypot field
     * @param  string $name  the name of the field
     * @param  mixed $value the valude of the field
     * @return boolean
     */
    public function validateHoneyPot(string $name, $value = null)
    {
        if (!$this->active) {
            return true;
        }

        return is_null($value) || $value === "";
    }

    /**
     * Validates the honeypot input time
     * @param  string $name  the attribute name of the field
     * @param  int $value the time
     * @return boolean
     */
    public function validateHoneyPotTime(string $name, $value = null, $parameters = [])
    {
        if (!$this->active) {
            return true;
        }

        // decrypt the value
        $value = $this->decryptedTime($value);
        // if we don't have parameters passed then lets fallback to the default
        $timeDiff = $parameters[0] ?? $this->timeDiff;

        return (is_numeric($value) && time() > ($value + $timeDiff));
    }

    /**
     * Sends back encrypted time
     * @return string
     */
    private function getEncryptedTime()
    {
        return Crypt::encrypt(time());
    }

    /**
     * Decrypts time
     * @param  value $value  the value we are going to attempt to decrypt
     * @return mixed
     */
    public function decryptedTime($value)
    {
        try {
            return Crypt::decrypt($value);
        } catch (\Exception $exception) {
            return null;
        }
    }

    /**
     * This is for the validation side, when we are adding rules to our
     * form, this will add all of the known honeypot session names to our
     * @param array $rules [description]
     */
    public function addValidationRules($rules = [])
    {
        $session_names = $this->sessionNames();
        foreach ($session_names as $name) {
            $rules[$name] = 'honeypot';
            $rules[$name . '_time'] = 'honeypot_time';
        }
        return $rules;
    }

    /**
     * From our randomizer scheme, get something good and random
     * @return [type] [description]
     */
    public function getRandom()
    {
        return Randomizer::randomNameGenerator(rand(1,7), ['-','_'][rand(0,1)]);
    }
}
