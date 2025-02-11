<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    return [
      'name' => ['required', 'string', 'max:255'],
      'slug' => [
        'nullable',
        'string',
        'max:255',
        Rule::unique('products')->ignore($this->product),
      ],
      'sku' => [
        'nullable',
        'string',
        'max:50',
        Rule::unique('products')->ignore($this->product),
      ],
      'description' => ['nullable', 'string'],
      'price' => ['required', 'numeric', 'min:0'],
      'cost' => ['nullable', 'numeric', 'min:0'],
      'type' => ['required', 'in:product,service'],
      'is_active' => ['boolean'],
      'category_id' => ['nullable', 'exists:categories,id'],
      'unit' => ['required', 'string', 'max:50'],
      'track_inventory' => ['boolean'],
      'stock' => ['required_if:track_inventory,true', 'integer', 'min:0'],
      'low_stock_threshold' => [
        'nullable',
        'required_if:track_inventory,true',
        'integer',
        'min:0'
      ],
      'media' => ['nullable', 'array'],
      'media.*' => ['file', 'max:10240', 'mimes:jpg,jpeg,png,gif'],
    ];
  }
}
