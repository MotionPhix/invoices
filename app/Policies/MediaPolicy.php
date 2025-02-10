<?php

namespace App\Policies;

use App\Models\User;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Auth\Access\HandlesAuthorization;

class MediaPolicy
{
  use HandlesAuthorization;

  /**
   * Determine whether the user can view the media.
   */
  public function view(User $user, Media $media): bool
  {
    // If the media belongs to a model the user owns or has access to
    return match ($media->model_type) {
      'App\Models\Invoice' => $user->can('view', $media->model),
      'App\Models\Client' => $user->can('view', $media->model),
      'App\Models\SupportRequest' => $user->can('view', $media->model),
      default => false,
    };
  }

  /**
   * Determine whether the user can create media.
   */
  public function create(User $user, string $modelType, string $modelId): bool
  {
    // Map the model type to its fully qualified class name
    $modelClass = match ($modelType) {
      'invoice' => 'App\Models\Invoice',
      'client' => 'App\Models\Client',
      'support_request' => 'App\Models\SupportRequest',
      default => null,
    };

    if (!$modelClass) {
      return false;
    }

    // Find the model and check if user can update it
    $model = $modelClass::find($modelId);
    return $model ? $user->can('update', $model) : false;
  }

  /**
   * Determine whether the user can delete the media.
   */
  public function delete(User $user, Media $media): bool
  {
    // Only allow deletion if user can manage the parent model
    return match ($media->model_type) {
      'App\Models\Invoice' => $user->can('update', $media->model),
      'App\Models\Client' => $user->can('update', $media->model),
      'App\Models\SupportRequest' => $user->can('update', $media->model),
      default => false,
    };
  }
}
