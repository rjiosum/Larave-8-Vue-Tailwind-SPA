<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Article extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'slug', 'description', 'status'];

    protected $appends = ['body'];

    /**
     * Mutate description field before persisting to database
     *
     * @param $description
     */
    public function setDescriptionAttribute($description)
    {
        $this->attributes['description'] = htmlentities($description, ENT_QUOTES, 'UTF-8', FALSE);
    }

    /**
     * Access description field and append as body
     *
     * @return string
     */
    public function getBodyAttribute(): string
    {
        return html_entity_decode($this->description, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Article belongs to a user, a user can have many articles
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
