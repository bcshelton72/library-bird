<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'author_id',
        'publisher_id',
        'category_id',
        'cover_image',
        'description',
        'publication_date',
        'isbn',
        'page_count',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'available' => 'boolean',
            // 'availability_date' => 'datetime:M j, Y',
            'availability_date' => 'datetime:Y-m-j',
            'publication_date' => 'datetime:Y',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    public function publisher(): BelongsTo
    {
        return $this->belongsTo(Publisher::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function calculateAverageRating(): void
    {
        $this->average_rating = round(($this->reviews->sum('rating')) / ($this->reviews->count() ?: 1), 2);
        $this->save();
    }

    /**
     * Mark the book as checked out
     */
    public function checkout(): void
    {
        $this->availability_date = Carbon::now()->addDays(5)->toDateString();
        $this->available = false;
        $this->save();
    }

    /**
     * Mark the book as returned (no longer checked out)
     */
    public function return(): void
    {
        $this->availability_date = null;
        $this->available = true;
        $this->save();
    }
}
