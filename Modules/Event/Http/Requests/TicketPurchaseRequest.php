<?php

namespace Modules\Event\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TicketPurchaseRequest extends FormRequest
{
    const EMAIL = 'email';
    const TOKEN = 'token';
    public function rules(): array
    {
        return [
            self::EMAIL => [
                'required'
            ],
            self::TOKEN => [
                'required'
            ],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function getEmail(): string
    {
        return $this->input(self::EMAIL);
    }

    public function getToken(): string
    {
        return $this->input(self::TOKEN);
    }
}
