<?php

declare(strict_types=1);

namespace App\Http\Requests\Webhooks;

use Illuminate\Foundation\Http\FormRequest;

class MetaSignedRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'signed_request' => ['required', 'string'],
        ];
    }
}
