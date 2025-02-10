<?php

namespace App\Http\Controllers;

use App\Http\Requests\MediaUploadRequest;
use App\Jobs\HandleFailedMediaConversion;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Client;
use App\Models\SupportRequest;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaController extends Controller
{
  use AuthorizesRequests;

  protected $models = [
    'invoice' => Invoice::class,
    'client' => Client::class,
    'support_request' => SupportRequest::class,
  ];

  public function store(MediaUploadRequest $request)
  {
    // Check if user can create media for this model
    $this->authorize('create', [Media::class, $request->model_type, $request->model_id]);

    $file = $request->file('file');
    $modelClass = $this->models[$request->model_type];
    $model = $modelClass::findOrFail($request->model_id);

    try {
      $media = $model
        ->addMediaFromRequest('file')
        ->withCustomProperties([
          'description' => $request->description,
          'uploaded_by' => auth()->id(),
          'original_filename' => $file->getClientOriginalName(),
          'extension' => $file->getClientOriginalExtension(),
          'mime_type' => $file->getMimeType(),
          'size' => $file->getSize(),
          'upload_ip' => $request->ip(),
        ])
        ->withResponsiveImages()
        ->toMediaCollection($request->input('collection_name', 'documents'));

      return response()->json([
        'message' => 'Document uploaded successfully. Image conversions will be processed in the background.',
        'media' => $media->only([
          'id',
          'name',
          'file_name',
          'mime_type',
          'size',
          'custom_properties',
          'created_at'
        ])
      ], 201);
    } catch (\Exception $e) {
      if (isset($media)) {
        HandleFailedMediaConversion::dispatch($media, $e->getMessage());
      }

      return response()->json([
        'message' => 'File uploaded but conversions failed. We\'ll process them again automatically.',
        'status' => 'pending'
      ], 202);
    }
  }

  public function show(Media $media)
  {
    $this->authorize('view', $media);

    return response()->download($media->getPath(), $media->file_name);
  }

  public function update(Media $media, MediaUploadRequest $request)
  {
    $this->authorize('update', $media);

    $media->update([
      'custom_properties' => array_merge(
        $media->custom_properties ?? [],
        ['description' => $request->description]
      )
    ]);

    return response()->json([
      'message' => 'Document updated successfully',
      'media' => $media->refresh()
    ]);
  }

  public function validateFile(MediaUploadRequest $request)
  {
    // If we get here, validation passed
    return response()->json([
      'valid' => true,
      'message' => 'File validation passed'
    ]);
  }

  public function destroy(Media $media)
  {
    $this->authorize('delete', $media);

    $media->delete();

    return response()->json([
      'message' => 'Document deleted successfully'
    ]);
  }
}
