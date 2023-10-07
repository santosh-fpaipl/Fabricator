<?php

namespace App\Http\Requests;

use App\Http\Requests\BaseRequest;

class PurchaseCreateRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'jwo_sid' => ['required'],
            'so_sids' => ['required', 'string'],
        ];
    }
}
