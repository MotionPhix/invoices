<?php

namespace App\Rules;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Config;

class MediaValidation
{
  /**
   * Get validation rules for media uploads
   */
  public static function rules(string $modelType = null): array
  {
    return [
      'file' => [
        'required',
        'file',
        'max:' . config('media-library.max_file_size'),
        function ($attribute, $value, $fail) {
          if (!$value->isValid()) {
            $fail('The file upload failed. Please try again.');
          }
        },
        self::allowedMimeTypes(),
      ],
      'model_type' => [
        'required',
        'string',
        Rule::in(['invoice', 'client', 'support_request']),
      ],
      'model_id' => [
        'required',
        'string',
        function ($attribute, $value, $fail) use ($modelType) {
          if ($modelType) {
            $modelClass = self::getModelClass($modelType);
            if (!$modelClass::find($value)) {
              $fail("The selected {$modelType} does not exist.");
            }
          }
        },
      ],
      'description' => ['nullable', 'string', 'max:500'],
      'collection_name' => ['sometimes', 'string', Rule::in(['documents', 'avatars', 'signatures'])],
    ];
  }

  /**
   * Get allowed mime types based on file type
   */
  public static function allowedMimeTypes(): \Illuminate\Validation\Rules\In
  {
    return Rule::in([
      // Documents
      'application/pdf',
      'application/msword',
      'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
      'application/vnd.ms-excel',
      'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',

      // Images
      'image/jpeg',
      'image/png',
      'image/webp',

      // Optional: Add more mime types as needed
      // 'application/zip',
      // 'text/csv',
      // 'application/json',
    ]);
  }

  /**
   * Get maximum file size for different types of files
   */
  public static function getMaxFileSize(string $mimeType): int
  {
    return match (true) {
      str_starts_with($mimeType, 'image/') => 5 * 1024, // 5MB for images
      str_starts_with($mimeType, 'application/pdf') => 10 * 1024, // 10MB for PDFs
      str_starts_with($mimeType, 'application/vnd.openxmlformats-officedocument') => 15 * 1024, // 15MB for Office docs
      default => 20 * 1024, // 20MB default
    };
  }

  /**
   * Get model class from type
   */
  protected static function getModelClass(string $type): string
  {
    return match ($type) {
      'invoice' => \App\Models\Invoice::class,
      'client' => \App\Models\Client::class,
      'support_request' => \App\Models\SupportRequest::class,
      default => throw new \InvalidArgumentException("Invalid model type: {$type}"),
    };
  }
}
