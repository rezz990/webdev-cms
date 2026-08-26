<?php
namespace App\Http\Controllers\Admin;
use App\Enums\ContentStatus;
use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Post;
use App\Models\Project;
use Illuminate\View\View;
class DashboardController extends Controller { public function __invoke(): View { return view('admin.dashboard',['projectCount'=>Project::count(),'publishedCount'=>Post::where('status',ContentStatus::Published)->count(),'draftCount'=>Post::where('status',ContentStatus::Draft)->count(),'unreadCount'=>ContactMessage::whereNull('read_at')->count(),'scheduled'=>Post::where('status',ContentStatus::Scheduled)->where('published_at','>',now())->orderBy('published_at')->first()]); } }
