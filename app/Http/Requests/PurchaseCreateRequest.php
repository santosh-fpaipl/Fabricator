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
            'po_sid' => ['required', 'string'],
            'so_sids' => ['required', 'string'],
        ];
    }
}
