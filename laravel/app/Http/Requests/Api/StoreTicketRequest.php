<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email:rfc', 'max:255'],
            'phone_e164' => ['required', $this->e164PhoneRule()],

            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],

            'attachments' => ['sometimes', 'array'],
            'attachments.*' => [
                'file',
                'max:10240',
                'mimes:jpg,jpeg,png,pdf,doc,docx',
            ],
        ];
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'phone_e164.regex' => 'Номер телефона должен быть в формате E.164 (например, +1234567890)',
        ];
    }

    /**
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $files = $this->file('attachments', []);

        if (is_array($this->input('attachments')) && empty($files)) {
            $this->request->remove('attachments');
        }
    }

    /**
     * @return string
     */
    private function e164PhoneRule(): string
    {
        return 'regex:/^\+[1-9]\d{6,14}$/';
    }
}
