<?php

namespace App\Models;

use App\Enums\ContentStatus;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['user_id', 'category_id', 'title', 'slug', 'excerpt', 'content', 'cover_image', 'status', 'is_featured', 'published_at', 'seo_title', 'seo_description'];
    protected function casts(): array { return ['status' => ContentStatus::class, 'is_featured' => 'boolean', 'published_at' => 'datetime']; }
    #[Scope]
    protected function published(Builder $query): Builder
    {
        return $query
            ->whereIn('status', [ContentStatus::Published->value, ContentStatus::Scheduled->value])
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }
    public function getRouteKeyName(): string { return 'slug'; }
    public function author(): BelongsTo { return $this->belongsTo(User::class, 'user_id'); }
    public function category(): BelongsTo { return $this->belongsTo(Category::class); }
    public function tags(): BelongsToMany { return $this->belongsToMany(Tag::class); }
    public function getReadingTimeAttribute(): int { return max(1, (int) ceil(str_word_count(strip_tags($this->content)) / 200)); }
}
