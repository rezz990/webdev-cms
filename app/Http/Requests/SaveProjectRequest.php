<?php
namespace App\Http\Requests;
use App\Enums\ContentStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class SaveProjectRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_featured' => $this->boolean('is_featured'),
            'sort_order' => $this->input('sort_order', 0),
        ]);
    }

    public function authorize(): bool
    {
        return (bool) $this->user()?->is_admin;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'alpha_dash:ascii', 'max:255', Rule::unique('projects', 'slug')->ignore($this->route('project'))],
            'summary' => ['required', 'string', 'max:1200'],
            'content' => ['required', 'string'],
            'status' => ['required', Rule::enum(ContentStatus::class)],
            'project_status' => ['required', 'string', 'max:50'],
            'year' => ['required', 'integer', 'between:2000,2100'],
            'role' => ['nullable', 'string', 'max:100'],
            'demo_url' => ['nullable', 'url:http,https'],
            'repository_url' => ['nullable', 'url:http,https'],
            'published_at' => ['nullable', 'date', Rule::requiredIf($this->string('status')->toString() === ContentStatus::Scheduled->value)],
            'category_id' => ['nullable', 'exists:categories,id'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'is_featured' => ['boolean'],
            'sort_order' => ['integer', 'min:0'],
            'seo_title' => ['nullable', 'string', 'max:70'],
            'seo_description' => ['nullable', 'string', 'max:160'],
            'technologies' => ['array'],
            'technologies.*' => ['integer', 'exists:technologies,id'],
            'new_technologies' => ['nullable', 'string', 'max:500'],
            'new_category' => ['nullable', 'string', 'max:100'],
        ];
    }
}
