<?php

namespace App\Models;

use App\Models\User;
use App\Models\Activity;
use App\Traits\BelongsToAccount;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Intervention\Image\Facades\Image;
use Intervention\Image\Facades\Image;

class Screenshot extends Model
{
    use HasFactory, BelongsToAccount;

    const STORAGE_DISK = 'screenshots';
    const FOLDER_URL = 'filesystems.disks.screenshots.url';

    protected $fillable = [
        'path',
        'activity_id',
        'account_id',
    ];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function fullPath(): string
    {
        return url("/screenshots/{$this->path}");
    }

    public static function saveFile(User $user, $imageFile): string
    {
        $image = Image::make($imageFile);
        $name = now()->timestamp . str_pad(now()->milli, 3, '0', STR_PAD_LEFT).'.webp';
        $path = storage_path('app/screenshots/'.$user->id.'/'.$name);
        $image->encode('webp')->save($path);
        return $user->id.'/'.$name;
        
        // Old Code
        // return Storage::disk(self::STORAGE_DISK)
        //     ->putFileAs($user->id, $imageFile, static::fileName());
    }

    public static function fileName(string $extension = '.png'): string
    {
        return now()->timestamp . str_pad(now()->milli, 3, '0', STR_PAD_LEFT) . $extension;
    }
}
