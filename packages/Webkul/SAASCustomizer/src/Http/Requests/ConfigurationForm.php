<?php

namespace Webkul\SAASCustomizer\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ConfigurationForm extends FormRequest
{
    /**
     * Determine if the Configuraion is authorized to make this request.
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
        $this->rules = [];

        if (request()->has('general.design.super_admin_logo.logo_image') && ! request()->input('general.design.super_admin_logo.logo_image.delete')) {
            $this->rules = [
                'general.design.super_admin_logo.logo_image'  => 'required|mimes:jpeg,bmp,png,jpg'
            ];
        }

        return $this->rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
    */
    public function messages()
    {
        return [
            // 'sales.orderSettings.order_number.order_number_prefix.regex' => 'Invalid format. Can not use #.',
            // 'sales.orderSettings.order_number.order_number_suffix.regex' => 'Invalid format. Can not use #.',
            'general.design.super_admin_logo.logo_image.mimes' => 'Invalid file format. Use only jpeg, bmp, png, jpg.'
        ];
    }
}
