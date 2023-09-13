<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\WeekdayValidationRule;
use App\Services\PresencesService;

class PresencesRequest extends FormRequest
{

    private const PRESENCE_IN = 'in';
    private const PRESENCE_OUT = 'out';
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type' => ['required', 'in:in,out', new WeekdayValidationRule],
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            $type = $this->input('type');

            $presencesService = app(PresencesService::class);

            // Validation in or out use "existingData"
            $dateIn = now()->format('Y-m-d');
            $existingData = $presencesService->getPresencesByDate($dateIn);
            if (
                $existingData &&
                ($type === self::PRESENCE_IN && !empty($existingData['clock_in'])) ||
                ($type === self::PRESENCE_OUT && !empty($existingData['clock_out']))
            ) {
                $validator->errors()->add('type', 'Only allowed one time in and one time out in a day.');
            }

            if ($type === self::PRESENCE_OUT && empty($existingData)) {
                $validator->errors()->add('type', 'Only after presences in.');
            }
        });
    }

    protected function prepareForValidation()
    {
        // Convert 'in_out' input to Carbon instance
        $inOut = $this->input('date', now()->format('Y-m-d H:i:s'));
        $this->merge([
            'date' => \Carbon\Carbon::parse($inOut)->format('Y-m-d H:i:s'),
        ]);
    }
}
