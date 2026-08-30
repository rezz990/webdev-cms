<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ContentStatus;
use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\MediaAsset;
use App\Models\Post;
use App\Models\Project;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'projectCount' => Project::query()->count(),
            'publishedCount' => Post::query()->where('status', ContentStatus::Published)->count(),
            'draftCount' => Post::query()->where('status', ContentStatus::Draft)->count(),
            'unreadCount' => ContactMessage::query()->whereNull('read_at')->count(),
            'mediaCount' => MediaAsset::query()->count(),
            'scheduledPosts' => Post::query()
                ->select(['id', 'title', 'slug', 'status', 'published_at'])
                ->where('status', ContentStatus::Scheduled)
                ->where('published_at', '>', now())
                ->orderBy('published_at')
                ->limit(5)
                ->get(),
            'recentPosts' => Post::query()
                ->select(['id', 'title', 'slug', 'status', 'updated_at'])
                ->latest('updated_at')
                ->limit(5)
                ->get(),
            'recentProjects' => Project::query()
                ->select(['id', 'name', 'slug', 'status', 'updated_at'])
                ->latest('updated_at')
                ->limit(5)
                ->get(),
            'unreadMessages' => ContactMessage::query()
                ->select(['id', 'name', 'subject', 'created_at'])
                ->whereNull('read_at')
                ->latest()
                ->limit(4)
                ->get(),
        ]);
    }
}
