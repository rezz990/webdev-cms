<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\SavePostRequest;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
class PostController extends Controller { public function __construct(private ImageUploadService $images) {} public function index(Request $request): View { return view('admin.posts.index',['posts'=>Post::with('category')->when($request->filled('q'),fn($q)=>$q->where('title','like','%'.$request->string('q').'%'))->latest()->paginate(15)]); } public function create(): View { return view('admin.posts.form',['post'=>new Post,'categories'=>Category::where('type','post')->get(),'tags'=>Tag::all()]); } public function store(SavePostRequest $request): RedirectResponse { $data=$request->safe()->except(['cover_image','tags']); $data['user_id']=$request->user()->id; if($request->hasFile('cover_image')){$data['cover_image']=$this->images->store($request->file('cover_image'),'posts');} $post=Post::create($data); $post->tags()->sync($request->input('tags',[])); return redirect()->route('admin.posts.index')->with('success','Tulisan dibuat.'); } public function edit(Post $post): View { return view('admin.posts.form',['post'=>$post->load('tags'),'categories'=>Category::where('type','post')->get(),'tags'=>Tag::all()]); } public function update(SavePostRequest $request,Post $post): RedirectResponse { $data=$request->safe()->except(['cover_image','tags']); if($request->hasFile('cover_image')){$data['cover_image']=$this->images->store($request->file('cover_image'),'posts');} $post->update($data); $post->tags()->sync($request->input('tags',[])); return redirect()->route('admin.posts.index')->with('success','Tulisan diperbarui.'); } public function destroy(Post $post): RedirectResponse { $post->delete(); return back()->with('success','Tulisan dipindahkan ke sampah.'); } }
