<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
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
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'venue' => 'required|string|max:255',
            'event_date' => 'required|date|after:now',
            'total_quota' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'artist_ids' => 'required|array|min:1',
            'artist_ids.*' => 'exists:artists,id',
            'performance_stages' => 'nullable|array',
            'performance_times' => 'nullable|array',
        ];
    }
}
