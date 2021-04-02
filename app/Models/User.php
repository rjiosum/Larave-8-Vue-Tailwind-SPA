<?php

namespace App\Models;

use App\Facades\Helper;
use App\Notifications\ResetPassword;
use App\Notifications\VerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable //implements MustVerifyEmail
{
    use HasFactory, HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'avatar',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = ['avatar_url', 'full_name'];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    /**
     * Accessor
     * @return string
     */
    public function getAvatarUrlAttribute(): string
    {
        return ($this->avatar === 'avatar.png')
            ? Storage::url('avatars/' . $this->avatar)
            : Storage::url('avatars/' . Helper::path($this->id) . $this->avatar);
    }

    /**
     * Accessor
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return ucwords($this->first_name . ' ' . $this->last_name);
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail);
    }

    /**
     * A user can have many Articles
     *
     * @return HasMany
     */
    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    /**
     * User owns the article
     *
     * @param Article $article
     * @return bool
     */
    public function ownsArticle(Article $article)
    {
        return $this->id == $article->user_id;
    }

    /**
     * A user can have many Addresses
     *
     * @return HasMany
     */
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    /**
     * User owns the address
     *
     * @param Address $address
     * @return bool
     */
    public function ownsAddress(Address $address)
    {
        return $this->id == $address->user_id;
    }
}
