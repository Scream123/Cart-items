<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Traits\ValidationErrorHandler;
use Illuminate\Foundation\Http\FormRequest;

class AddToCartRequest extends FormRequest
{
    use ValidationErrorHandler;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ];
    }
}
