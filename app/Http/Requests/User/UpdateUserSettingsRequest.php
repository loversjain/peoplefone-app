<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateUserSettingsRequest
 * @package App\Http\Requests
 *
 * Request validation for updating user settings.
 */
class UpdateUserSettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true; // Assuming all users can update their settings
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'notification_switch' => 'sometimes|boolean',
            'email' => 'required|email|unique:users,email,' . $this->userId,
            'phone_number' => 'required|phone:AUTO,US',
        ];
    }
    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'notification_switch.boolean' => 'The notification switch field must be true or false.',
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.unique' => 'The email has already been taken.',
            'phone_number.required' => 'The phone number field is required.',
            'phone_number.phone' => 'The phone number is not valid.',
        ];
    }
}
