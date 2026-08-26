<?php

namespace App\Models;

use App\Enums\ContentStatus;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['category_id', 'name', 'slug', 'summary', 'content', 'cover_image', 'status', 'project_status', 'year', 'role', 'demo_url', 'repository_url', 'is_featured', 'sort_order', 'published_at', 'seo_title', 'seo_description'];
    protected function casts(): array { return ['status' => ContentStatus::class, 'is_featured' => 'boolean', 'published_at' => 'datetime']; }
    #[Scope] protected function published(Builder $query): Builder { return $query->where('status', ContentStatus::Published)->where(fn (Builder $query) => $query->whereNull('published_at')->orWhere('published_at', '<=', now())); }
    public function getRouteKeyName(): string { return 'slug'; }
    public function category(): BelongsTo { return $this->belongsTo(Category::class); }
    public function technologies(): BelongsToMany { return $this->belongsToMany(Technology::class); }
    public function images(): HasMany { return $this->hasMany(ProjectImage::class)->orderBy('sort_order'); }
}
