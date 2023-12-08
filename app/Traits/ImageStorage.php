<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Upload Image for Profile
 * And Attendance
 */
trait ImageStorage
{
  /**
   * For Upload Photo
   * @param mixed $photo
   * @param mixed $name
   * @param mixed $path
   * @param bool $update
   * @param mixed|null $old_photo
   * @return void
   */
  public function uploadImage($photo, $name, $path, $unique = false, $update = false, $old_photo = null)
  {
    $name = Str::slug($name);
    if ($update) {
      Storage::delete("/assets/img/{$path}/" . $old_photo);
    }
    if ($unique) {
      $name = Str::slug($name) . '-' . time();
    }

    $extension = $photo->getClientOriginalExtension();
    $newName = $name . '.' . $extension;
    Storage::putFileAs("/assets/img/{$path}", $photo, $newName);
    return $newName;
  }

  /**
   *
   * @param mixed $old_photo
   * @param mixed $path
   * @return void
   */
  public function deleteImage($old_photo, $path)
  {
    Storage::delete("/assets/img/{$path}/" . $old_photo);
  }

  public function renameImage($imagePath, $newTitle, $path)
  {
    $newName = Str::slug($newTitle) . '-' . time();
    $extension = pathinfo($imagePath, PATHINFO_EXTENSION); // Get image extension from old path
    $newImageName = $newName . '.' . $extension;

    // Rename image file in storage directory
    Storage::move("/assets/img/{$path}/{$imagePath}", "/assets/img/{$path}/{$newImageName}");

    return $newImageName;
  }
}
