<?php


namespace App\Domains\User\Actions;


use App\Facades\Helper;
use App\Models\User;
use App\Domains\User\Contracts\IUpdateUserAvatar;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UpdateUserAvatar implements IUpdateUserAvatar
{
    /**
     * @var User
     */
    private $user;

    /**
     * UpdateUserAvatar constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Update avatar filename in the resource.
     *
     * @param array $params
     * @return mixed
     */
    public function updateAvatar(array $params)
    {
        return DB::transaction(function () use (&$params) {
            return $this->user
                ->where('id', $params['id'])
                ->update(['avatar' => $params['avatar']]);
        }, 5);
    }

    /**
     * Store user avatar image on the disk.
     *
     * @param UploadedFile $uploadedFile
     * @param User $user
     * @param string $disk
     * @return string
     */
    public function storeAvatarImage(UploadedFile $uploadedFile, User $user, string $disk = 'public'): string
    {
        //profile image name
        $filename = Helper::randomKey(8) . '.' . $uploadedFile->getClientOriginalExtension();
        //upload path
        $path = 'avatars/' . Helper::path($user->id); //get sub path 000/000/123

        //store file
        //$uploadedFile->storeAs($path, $filename, $disk); same as below
        Storage::disk($disk)->putFileAs($path, $uploadedFile, $filename);

        //resize image
        $width = config('laravolt.avatar.width');
        $height = config('laravolt.avatar.height');

        Image::make(public_path('storage/' . $path . $filename))
            ->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            })->save();

        //Delete old file
        Storage::disk($disk)->delete(public_path('storage/' . $path . $user->avatar));
        return $filename;
    }
}
