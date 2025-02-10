<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class FilenameSafe implements Rule
{
  public function passes($attribute, $value): bool
  {
    if (!$value instanceof \Illuminate\Http\UploadedFile) {
      return false;
    }

    $filename = $value->getClientOriginalName();

    // Check for invalid characters
    if (preg_match('/[<>:"/\\|?*]/', $filename)) {
      return false;
    }

    // Check for hidden files
    if (str_starts_with($filename, '.')) {
      return false;
    }

    // Check filename length
    if (strlen($filename) > 255) {
      return false;
    }

    return true;
  }

  public function message(): string
  {
    return 'The filename contains invalid characters or is not allowed.';
  }
}
