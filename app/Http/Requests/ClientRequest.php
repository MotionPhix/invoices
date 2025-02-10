<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Propaganistas\LaravelPhone\PhoneNumber;

class ClientRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    $rules = [
      'name' => ['required', 'string', 'max:255'],
      'email' => [
        'required', 'email',
        Rule::unique('clients')->ignore($this->client),
      ],
      'phone' => [
        'nullable', 'string',
        'phone:MW,ZA,ZM,ZW', // Allow phone numbers from Malawi and neighboring countries
      ],
      'company_name' => ['nullable', 'string', 'max:255'],
      'vat_number' => ['nullable', 'string', 'max:50'],

      // Billing Address
      'billing_address' => ['required', 'string'],
      'billing_city' => ['required', 'string', 'max:100'],
      'billing_state' => ['required', 'string', 'max:100'],
      'billing_postal_code' => ['required', 'string', 'max:20'],
      'billing_country' => ['required', 'string', 'max:100'],

      // Shipping Address
      'use_billing_for_shipping' => ['boolean'],
      'shipping_address' => ['required_if:use_billing_for_shipping,false', 'nullable', 'string'],
      'shipping_city' => ['required_if:use_billing_for_shipping,false', 'nullable', 'string', 'max:100'],
      'shipping_state' => ['required_if:use_billing_for_shipping,false', 'nullable', 'string', 'max:100'],
      'shipping_postal_code' => ['required_if:use_billing_for_shipping,false', 'nullable', 'string', 'max:20'],
      'shipping_country' => ['required_if:use_billing_for_shipping,false', 'nullable', 'string', 'max:100'],

      'notes' => ['nullable', 'string'],
      'currency' => ['required', 'string', 'size:3'],

      'user_id' => ['required', 'exists:users,id']
    ];

    // Add status validation only for update requests
    if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
      $rules['status'] = ['required', Rule::in(['active', 'inactive'])];
    }

    return $rules;
  }

  public function messages(): array
  {
    return [
      'name.required' => 'Please provide a name for the client.',
      'email.required' => 'Email address is required.',
      'email.email' => 'Please provide a valid email address.',
      'email.unique' => 'This email is already registered with another client.',

      'phone.phone' => 'Please enter a valid phone number.',

      'billing_address.required' => 'Billing address is required.',
      'billing_city.required' => 'Billing city is required.',
      'billing_state.required' => 'Billing state/province is required.',
      'billing_postal_code.required' => 'Billing postal code is required.',
      'billing_country.required' => 'Billing country is required.',

      'shipping_address.required_if' => 'Shipping address is required when not using billing address.',
      'shipping_city.required_if' => 'Shipping city is required when not using billing address.',
      'shipping_state.required_if' => 'Shipping state/province is required when not using billing address.',
      'shipping_postal_code.required_if' => 'Shipping postal code is required when not using billing address.',
      'shipping_country.required_if' => 'Shipping country is required when not using billing address.',

      'currency.required' => 'Please select a currency.',
      'currency.size' => 'Currency code must be 3 characters long (e.g., MWK, USD).',

      'status.required' => 'Please select a status.',
      'status.in' => 'Status must be either active or inactive.',

      'user_id.required' => 'User ID is required.',
      'user_id.exists' => 'Selected user does not exist.',
    ];
  }

  protected function prepareForValidation()
  {
    $this->merge([
      'user_id' => auth()->id()
    ]);

    if ($this->use_billing_for_shipping) {
      $this->merge([
        'shipping_address' => $this->billing_address,
        'shipping_city' => $this->billing_city,
        'shipping_state' => $this->billing_state,
        'shipping_postal_code' => $this->billing_postal_code,
        'shipping_country' => $this->billing_country,
      ]);
    }

    if ($this->phone) {
      try {
        $phone = new PhoneNumber($this->phone, 'MW'); // Default to Malawi
        $this->merge([
          'phone' => $phone->formatE164(),
        ]);
      } catch (\Exception $e) {
        // If parsing fails, leave the original value
      }
    }
  }
}
