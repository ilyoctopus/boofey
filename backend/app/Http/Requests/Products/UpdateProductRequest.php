<?php

namespace App\Http\Requests\Products;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;

class UpdateProductRequest extends FormRequest
{
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        $product = request()->route('product');

        return [
            'name' => 'required|string|max:500|unique:products,name,'.$product->id,
            'name_ar' => 'required|string|max:500|unique:products,name_ar,'.$product->id,

            'description' => 'nullable|string',
            'description_ar' => 'nullable|string',

            'sale_price' => 'nullable|numeric|min:0',
            'price' => 'required|numeric|min:0',

            'category_id' => 'required|exists:categories,id',

            'edit_image' => 'required|boolean',
            'file' => 'required_if:edit_image,true|file|mimes:jpeg,png'          
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new ValidationException($validator, $this->buildFailedValidationResponse($validator));
    }

    protected function buildFailedValidationResponse(\Illuminate\Contracts\Validation\Validator $validator)
    {
        return new JsonResponse([
            'status' => 'error',
            'errors' => $validator->errors(),
        ], 422);
    }
}
