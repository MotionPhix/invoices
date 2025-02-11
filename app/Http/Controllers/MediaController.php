<?php

namespace App\Http\Controllers;

use App\Http\Requests\MediaUploadRequest;
use App\Jobs\HandleFailedMediaConversion;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Invoice;
use App\Models\Client;
use App\Models\SupportRequest;
use Illuminate\Support\Facades\Log;
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
        ->withGeneratedConversions([
          'preview' => function ($image) {
            return $image->fit(400, 400);
          },
          'thumbnail' => function ($image) {
            return $image->fit(100, 100);
          }
        ])
        ->toMediaCollection($request->input('collection_name', 'documents'));

      // For PDFs, generate preview image using Imagick
      if ($media->mime_type === 'application/pdf') {
        $this->generatePdfPreview($media);
      }

      return response()->json([
        'message' => 'Document uploaded successfully',
        'media' => $this->formatMediaResponse($media)
      ], 201);
    } catch (\Exception $e) {
      if (isset($media)) {
        HandleFailedMediaConversion::dispatch($media, $e->getMessage());
      }

      return response()->json([
        'message' => 'File uploaded but processing failed. We\'ll try again automatically.',
        'status' => 'pending'
      ], 202);
    }
  }

  protected function generatePdfPreview(Media $media)
  {
    try {
      $imagick = new \Imagick();
      $imagick->readImage($media->getPath() . '[0]'); // Get first page
      $imagick->setImageFormat('jpg');
      $imagick->setCompressionQuality(85);

      $previewPath = $media->getPath('preview');
      $imagick->writeImage($previewPath);

      $media->manipulations = array_merge(
        $media->manipulations ?? [],
        ['preview' => ['generated' => true]]
      );
      $media->save();
    } catch (\Exception $e) {
      Log::error('PDF preview generation failed: ' . $e->getMessage());
    }
  }

  protected function formatMediaResponse(Media $media)
  {
    return [
      'id' => $media->id,
      'name' => $media->name,
      'file_name' => $media->file_name,
      'mime_type' => $media->mime_type,
      'size' => $media->size,
      'custom_properties' => $media->custom_properties,
      'created_at' => $media->created_at,
      'preview_url' => $media->hasGeneratedConversion('preview')
        ? $media->getUrl('preview')
        : null,
      'thumbnail_url' => $media->hasGeneratedConversion('thumbnail')
        ? $media->getUrl('thumbnail')
        : null,
      'original_url' => $media->getUrl()
    ];
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
