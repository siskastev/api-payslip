<?php

namespace App\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class WeekdayValidationRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if ($value) {
            $inOut = request('date');
            $carbonDate = Carbon::parse($inOut);
            return $carbonDate->isWeekday() && $carbonDate->between('08:00:00', '16:00:00');
        }

        return false;
    }

    public function message()
    {
        return 'Only in/out on a weekday (Monday to Friday) between 08:00 until 16:00.';
    }
}
