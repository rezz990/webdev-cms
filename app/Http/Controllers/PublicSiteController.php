<?php
namespace App\Http\Controllers;
use App\Mail\ContactMessageReceived;
use App\Http\Requests\ContactRequest;
use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\Post;
use App\Models\Project;
use App\Models\Technology;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Throwable;
use Illuminate\View\View;
class PublicSiteController extends Controller
{
    public function home(): View { return view('public.home', ['projects'=>Project::published()->with('technologies')->where('is_featured',true)->orderBy('sort_order')->limit(3)->get(),'posts'=>Post::published()->with('category')->latest('published_at')->limit(3)->get(),'technologies'=>Technology::orderBy('sort_order')->limit(12)->get()]); }
    public function projects(Request $request): View { $projects=Project::published()->with(['category','technologies'])->when($request->string('category')->isNotEmpty(),fn($q)=>$q->whereHas('category',fn($q)=>$q->where('slug',$request->string('category'))))->orderBy('sort_order')->latest('published_at')->paginate(9)->withQueryString(); return view('public.projects.index',['projects'=>$projects,'categories'=>Category::where('type','project')->orderBy('name')->get()]); }
    public function project(Project $project): View { abort_unless(Project::published()->whereKey($project->getKey())->exists(),404); $project->load(['category','technologies','images']); return view('public.projects.show',['project'=>$project,'related'=>Project::published()->whereKeyNot($project->getKey())->limit(3)->get()]); }
    public function blog(Request $request): View { $posts=Post::published()->with(['category','tags'])->when($request->filled('q'),fn($q)=>$q->where(fn($q)=>$q->where('title','like','%'.$request->string('q').'%')->orWhere('excerpt','like','%'.$request->string('q').'%')))->when($request->filled('category'),fn($q)=>$q->whereHas('category',fn($q)=>$q->where('slug',$request->string('category'))))->when($request->filled('tag'),fn($q)=>$q->whereHas('tags',fn($q)=>$q->where('slug',$request->string('tag'))))->latest('published_at')->paginate(9)->withQueryString(); return view('public.blog.index',compact('posts')); }
    public function post(Post $post): View { abort_unless(Post::published()->whereKey($post->getKey())->exists(),404); $post->load(['category','tags','author']); return view('public.blog.show',['post'=>$post,'related'=>Post::published()->whereKeyNot($post->getKey())->limit(3)->get()]); }
    public function about(): View { return view('public.about',['technologies'=>Technology::orderBy('sort_order')->get(),'projectCount'=>Project::published()->count(),'postCount'=>Post::published()->count()]); }
    public function contact(): View { return view('public.contact'); }
    public function storeContact(ContactRequest $request): RedirectResponse
    {
        $message = ContactMessage::query()->create($request->safe()->except('website'));

        if (config('mail.default') !== 'log' && config('mail.contact_recipient')) {
            try {
                Mail::to(config('mail.contact_recipient'))->send(new ContactMessageReceived($message));
            } catch (Throwable $exception) {
                report($exception);
            }
        }

        return back()->with('success', 'Pesan sudah tersimpan. Terima kasih sudah menghubungi Reza.');
    }
    public function sitemap() { $posts=Post::published()->get(['slug','updated_at']); $projects=Project::published()->get(['slug','updated_at']); return response()->view('public.sitemap',compact('posts','projects'))->header('Content-Type','application/xml'); }
    public function feed() { return response()->view('public.feed',['posts'=>Post::published()->latest('published_at')->limit(20)->get()])->header('Content-Type','application/rss+xml'); }
}
