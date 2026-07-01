<?php

namespace App\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'showtime_id' => ['required', 'exists:showtimes,id'],
            'seats' => ['required', 'array', 'min:1'],
            'seats.*' => ['exists:seats,id'],
            'payment_type' => ['required', 'in:bca_va,echannel,gopay'],
        ];
    }
}
