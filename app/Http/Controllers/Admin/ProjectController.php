<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\SaveProjectRequest;
use App\Models\Category;
use App\Models\Project;
use App\Models\Technology;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
class ProjectController extends Controller { public function __construct(private ImageUploadService $images) {} public function index(): View { return view('admin.projects.index',['projects'=>Project::with('category')->latest()->paginate(15)]); } public function create(): View { return view('admin.projects.form',['project'=>new Project,'categories'=>Category::where('type','project')->get(),'technologies'=>Technology::all()]); } public function store(SaveProjectRequest $request): RedirectResponse { $data=$request->safe()->except(['cover_image','technologies']); if($request->hasFile('cover_image')){$data['cover_image']=$this->images->store($request->file('cover_image'),'projects');} $project=Project::create($data); $project->technologies()->sync($request->input('technologies',[])); return redirect()->route('admin.projects.index')->with('success','Project dibuat.'); } public function edit(Project $project): View { return view('admin.projects.form',['project'=>$project->load('technologies'),'categories'=>Category::where('type','project')->get(),'technologies'=>Technology::all()]); } public function update(SaveProjectRequest $request,Project $project): RedirectResponse { $data=$request->safe()->except(['cover_image','technologies']); if($request->hasFile('cover_image')){$data['cover_image']=$this->images->store($request->file('cover_image'),'projects');} $project->update($data); $project->technologies()->sync($request->input('technologies',[])); return redirect()->route('admin.projects.index')->with('success','Project diperbarui.'); } public function destroy(Project $project): RedirectResponse { $project->delete(); return back()->with('success','Project dipindahkan ke sampah.'); } }
