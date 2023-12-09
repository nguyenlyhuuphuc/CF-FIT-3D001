<?php

namespace App\Http\Requests\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|min:3|max:255',
            'price' => 'required|numeric|min:0|max:999999999',
            'short_description' => 'required',
            'description' => 'required',
            'qty' => 'required|numeric|min:0||max:999999999',
            'image_url' => 'required|image|max:10240',
            'status' => 'required',
            'product_category_id' => 'required'
        ];
    }

    public function messages():array
    {
        return [
            'name.required'  => 'Name bat buoc phai nhap'
        ];
    }
}
