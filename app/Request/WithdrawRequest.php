<?php

declare(strict_types=1);

namespace App\Request;

use App\Enum\PixKeyType;
use App\Enum\WithdrawMethod;
use Hyperf\Validation\Request\FormRequest;

class WithdrawRequest extends FormRequest
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
            'method' => 'required|string|in:' . implode(',', WithdrawMethod::values()),
            'amount' => 'required|numeric|min:0.01',
            'schedule' => 'nullable|date_format:Y-m-d H:i|after:now',
            'pix' => 'required_if:method,' . WithdrawMethod::PIX->value . '|array',
            'pix.key' => 'required_if:method,' . WithdrawMethod::PIX->value . '|string',
            'pix.type' => 'required_if:method,' . WithdrawMethod::PIX->value . '|string|in:email'
        ];
    }

    public function messages(): array
    {
        return [
            'method.in' => 'O método de saque deve ser um dos tipos válidos: ' . implode(', ', WithdrawMethod::values()) . '.', 
            'pix.type.in' => 'O tipo de chave PIX deve ser um dos tipos válidos: ' . implode(', ', PixKeyType::values()) . '.', 
            'schedule.date_format' => 'O formato do agendamento deve ser YYYY-MM-DD HH:MM.',
            'schedule.after' => 'A data informada precisar ser posterior a data e hora atuais.',
        ];
    }
}
