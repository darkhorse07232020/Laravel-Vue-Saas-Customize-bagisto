<?php

namespace Webkul\SAASCustomizer\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AgentForm extends FormRequest
{
    protected $rules;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:super_admins,email',
            'password' => 'nullable|confirmed',
            'status' => 'sometimes',
            // 'role_id' => 'required'
        ];

        if ($this->method() == 'PUT') {
            $this->rules['email'] = 'email|unique:super_admins,email,' . $this->route('id');
        }

        return $this->rules;
    }
}
