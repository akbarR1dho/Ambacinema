<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateShowtimeRequest extends FormRequest
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
            'movie_id' => ['required', 'exists:movies,id'],
            'studio_id' => ['required', 'exists:studios,id'],
            'start_time' => ['required', 'date_format:Y-m-d\TH:i'],
        ];
    }
}
