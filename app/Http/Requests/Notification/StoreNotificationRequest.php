<?php

namespace App\Http\Requests\Notification;

use Illuminate\Foundation\Http\FormRequest;

class StoreNotificationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Update this to handle authorization as needed
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
            'type' => 'required|in:marketing,invoices,system',
            'short_text' => 'required|string|max:255',
            'expiration' => 'required|date|after_or_equal:today',
            'destination' => 'required|in:user,all',
            'user_id' => 'nullable|exists:users,id',
        ];
    }

    /**
     * Get the custom validation rules that apply when the destination is 'user'.
     *
     * @return array
     */
    public function withValidator($validator)
    {
        $validator->sometimes('user_id', 'required', function ($input) {
            return $input->destination === 'user';
        });
    }
    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'type.required' => 'The type of notification is required.',
            'type.in' => 'The selected type is invalid.',
            'short_text.required' => 'The short text is required.',
            'short_text.string' => 'The short text must be a string.',
            'short_text.max' => 'The short text may not be greater than 255 characters.',
            'expiration.required' => 'The expiration date is required.',
            'expiration.date' => 'The expiration must be a valid date.',
            'expiration.after_or_equal' => 'The expiration date must be today or a future date.',
            'destination.required' => 'The destination is required.',
            'destination.in' => 'The selected destination is invalid.',
            'user_id.exists' => 'The selected user ID is invalid.',
        ];
    }
}
