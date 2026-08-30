<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ContentStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\SaveProjectRequest;
use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Project;
use App\Models\Technology;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function __construct(private ImageUploadService $images) {}

    public function index(Request $request): View
    {
        $projects = Project::query()
            ->with('category')
            ->when($request->filled('q'), fn ($query) => $query->where('name', 'like', '%'.$request->string('q')->toString().'%'))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')->toString()))
            ->latest('updated_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.projects.index', [
            'projects' => $projects,
        ]);
    }

    public function create(): View
    {
        return $this->formView(new Project);
    }

    public function store(SaveProjectRequest $request): RedirectResponse
    {
        $project = DB::transaction(function () use ($request): Project {
            $data = $this->projectData($request);
            if ($request->filled('new_category')) {
                $category = Category::query()->firstOrCreate(
                    ['slug' => Str::slug($request->string('new_category')->toString()).'-project'],
                    ['name' => $request->string('new_category')->toString(), 'type' => 'project'],
                );
                $data['category_id'] = $category->id;
            }
            $project = Project::query()->create($data);
            $project->technologies()->sync($this->technologyIds($request));
            $this->logActivity($request->user()->id, 'project.created', $project);

            return $project;
        });

        return $this->savedResponse($project, 'Project berhasil dibuat dan status publikasinya sudah diterapkan.');
    }

    public function edit(Project $project): View
    {
        return $this->formView($project->load('technologies'));
    }

    public function update(SaveProjectRequest $request, Project $project): RedirectResponse
    {
        DB::transaction(function () use ($request, $project): void {
            $data = $this->projectData($request);
            if ($request->filled('new_category')) {
                $category = Category::query()->firstOrCreate(
                    ['slug' => Str::slug($request->string('new_category')->toString()).'-project'],
                    ['name' => $request->string('new_category')->toString(), 'type' => 'project'],
                );
                $data['category_id'] = $category->id;
            }
            $project->update($data);
            $project->technologies()->sync($this->technologyIds($request));
            $this->logActivity($request->user()->id, 'project.updated', $project);
        });

        return $this->savedResponse($project->fresh(), 'Project berhasil diperbarui.');
    }

    public function preview(Project $project): View
    {
        $project->load(['category', 'technologies', 'images']);

        return view('public.projects.show', [
            'project' => $project,
            'related' => collect(),
            'isPreview' => true,
        ]);
    }

    public function destroy(Project $project): RedirectResponse
    {
        $project->delete();

        return back()->with('success', 'Project dipindahkan ke sampah.');
    }

    private function formView(Project $project): View
    {
        return view('admin.projects.form', [
            'project' => $project,
            'categories' => Category::query()->where('type', 'project')->orderBy('name')->get(),
            'technologies' => Technology::query()->orderBy('sort_order')->orderBy('name')->get(),
        ]);
    }

    /** @return array<string, mixed> */
    private function projectData(SaveProjectRequest $request): array
    {
        $data = $request->safe()->except(['cover_image', 'technologies', 'new_technologies', 'new_category']);

        if ($data['status'] === ContentStatus::Published->value && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $this->images->store($request->file('cover_image'), 'projects');
        }

        return $data;
    }

    /** @return array<int, int> */
    private function technologyIds(SaveProjectRequest $request): array
    {
        $technologyIds = collect($request->input('technologies', []));
        $names = Str::of($request->string('new_technologies')->toString())->explode(',')->map(fn ($name) => trim((string) $name))->filter();

        foreach ($names as $name) {
            $technologyIds->push(Technology::query()->firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'sort_order' => (Technology::query()->max('sort_order') ?? 0) + 1],
            )->id);
        }

        return $technologyIds->unique()->values()->all();
    }

    private function savedResponse(Project $project, string $message): RedirectResponse
    {
        $isPublic = in_array($project->status, [ContentStatus::Published, ContentStatus::Scheduled], true)
            && $project->published_at?->isPast();

        return redirect()
            ->route('admin.projects.edit', $project)
            ->with('success', $message)
            ->with('public_url', $isPublic ? route('projects.show', $project) : null);
    }

    private function logActivity(int $userId, string $action, Project $project): void
    {
        ActivityLog::query()->create([
            'user_id' => $userId,
            'action' => $action,
            'subject_type' => Project::class,
            'subject_id' => $project->id,
        ]);
    }
}
