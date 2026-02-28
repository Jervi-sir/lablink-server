<?php

namespace App\Domains\Upload\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\BusinessProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
  /**
   * Upload product images (multiple).
   * Expects multipart/form-data with 'images[]' array.
   *
   * @param Request $request
   * @param Product $product
   * @return JsonResponse
   */
  public function uploadProductImages(Request $request, Product $product): JsonResponse
  {
    $user = $request->user();
    if ($product->business->user_id !== $user->id && !$user->isAdmin()) {
      return response()->json(['message' => 'Unauthorized'], 403);
    }

    $request->validate([
      'images' => ['required', 'array', 'max:10'],
      'images.*' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'], // 5MB max per image
    ]);

    $uploadedImages = [];

    foreach ($request->file('images') as $index => $image) {
      $path = $image->store("products/{$product->id}", 'public');
      $url = url('/storage/' . $path);

      $productImage = ProductImage::create([
        'product_id' => $product->id,
        'url' => $url,
        'path' => $path,
        'is_main' => $index === 0 && $product->images()->count() === 0, // First image is main if no existing images
      ]);

      $uploadedImages[] = $productImage->format();
    }

    return response()->json([
      'message' => count($uploadedImages) . ' image(s) uploaded successfully',
      'data' => $uploadedImages,
    ]);
  }

  /**
   * Delete a product image.
   *
   * @param Request $request
   * @param ProductImage $productImage
   * @return JsonResponse
   */
  public function deleteProductImage(Request $request, ProductImage $productImage): JsonResponse
  {
    $user = $request->user();
    $product = $productImage->product;

    if ($product->business->user_id !== $user->id && !$user->isAdmin()) {
      return response()->json(['message' => 'Unauthorized'], 403);
    }

    // Delete file from storage
    if ($productImage->path) {
      Storage::disk('public')->delete($productImage->path);
    }

    $wasMain = $productImage->is_main;
    $productImage->delete();

    // If deleted image was main, make the first remaining image the main one
    if ($wasMain) {
      $firstImage = $product->images()->first();
      if ($firstImage) {
        $firstImage->update(['is_main' => true]);
      }
    }

    return response()->json([
      'message' => 'Image deleted successfully',
    ]);
  }

  /**
   * Set a product image as the main image.
   *
   * @param Request $request
   * @param ProductImage $productImage
   * @return JsonResponse
   */
  public function setMainProductImage(Request $request, ProductImage $productImage): JsonResponse
  {
    $user = $request->user();
    $product = $productImage->product;

    if ($product->business->user_id !== $user->id && !$user->isAdmin()) {
      return response()->json(['message' => 'Unauthorized'], 403);
    }

    // Unset all current main images
    $product->images()->update(['is_main' => false]);

    // Set this one as main
    $productImage->update(['is_main' => true]);

    return response()->json([
      'message' => 'Main image updated successfully',
    ]);
  }

  /**
   * Upload business profile logo.
   * Expects multipart/form-data with 'logo' file.
   *
   * @param Request $request
   * @param BusinessProfile $business
   * @return JsonResponse
   */
  public function uploadBusinessLogo(Request $request, BusinessProfile $business): JsonResponse
  {
    $user = $request->user();
    if ($business->user_id !== $user->id && !$user->isAdmin()) {
      return response()->json(['message' => 'Unauthorized'], 403);
    }

    $request->validate([
      'logo' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'], // 5MB max
    ]);

    // Delete old logo if exists
    if ($business->logo && Storage::disk('public')->exists($business->logo)) {
      Storage::disk('public')->delete($business->logo);
    }

    $path = $request->file('logo')->store("businesses/{$business->id}/logo", 'public');
    $url = url('/storage/' . $path);

    $business->update(['logo' => $url]);

    return response()->json([
      'message' => 'Logo uploaded successfully',
      'data' => ['logo' => $url],
    ]);
  }

  /**
   * Upload business certificate.
   * Expects multipart/form-data with 'certificate' file.
   *
   * @param Request $request
   * @param BusinessProfile $business
   * @return JsonResponse
   */
  public function uploadBusinessCertificate(Request $request, BusinessProfile $business): JsonResponse
  {
    $user = $request->user();
    if ($business->user_id !== $user->id && !$user->isAdmin()) {
      return response()->json(['message' => 'Unauthorized'], 403);
    }

    $request->validate([
      'certificate' => ['required', 'file', 'mimes:pdf,jpeg,png,jpg', 'max:10240'], // 10MB max
    ]);

    // Delete old certificate if exists
    if ($business->certificate_url && Storage::disk('public')->exists($business->certificate_url)) {
      Storage::disk('public')->delete($business->certificate_url);
    }

    $path = $request->file('certificate')->store("businesses/{$business->id}/certificates", 'public');
    $url = url('/storage/' . $path);

    $business->update(['certificate_url' => $url]);

    return response()->json([
      'message' => 'Certificate uploaded successfully',
      'data' => ['certificate_url' => $url],
    ]);
  }

  /**
   * Delete business certificate.
   *
   * @param Request $request
   * @param BusinessProfile $business
   * @return JsonResponse
   */
  public function deleteBusinessCertificate(Request $request, BusinessProfile $business): JsonResponse
  {
    $user = $request->user();
    if ($business->user_id !== $user->id && !$user->isAdmin()) {
      return response()->json(['message' => 'Unauthorized'], 403);
    }

    if ($business->certificate_url) {
      // Try to extract path from URL for deletion
      $urlPath = parse_url($business->certificate_url, PHP_URL_PATH);
      $storagePath = str_replace('/storage/', '', $urlPath);
      if ($storagePath && Storage::disk('public')->exists($storagePath)) {
        Storage::disk('public')->delete($storagePath);
      }
    }

    $business->update(['certificate_url' => null]);

    return response()->json([
      'message' => 'Certificate deleted successfully',
    ]);
  }
}
