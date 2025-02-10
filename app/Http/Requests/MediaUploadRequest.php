<?php

namespace App\Http\Requests;

use App\Rules\MediaValidation;
use Illuminate\Foundation\Http\FormRequest;

class MediaUploadRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true; // Authorization is handled in the controller
  }

  public function rules(): array
  {
    return MediaValidation::rules($this->input('model_type'));
  }

  public function messages(): array
  {
    return [
      'file.max' => 'The file size cannot be larger than :max kilobytes.',
      'file.mimes' => 'The file must be a file of type: :values.',
      'model_type.in' => 'Invalid model type selected.',
      'collection_name.in' => 'Invalid collection name selected.',
    ];
  }

  protected function prepareForValidation(): void
  {
    // Convert file size to kilobytes for validation
    if ($this->hasFile('file')) {
      $this->merge([
        'file_size' => $this->file('file')->getSize() / 1024
      ]);
    }
  }
}
