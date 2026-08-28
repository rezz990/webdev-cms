<?php
namespace App\Http\Requests;
use App\Enums\ContentStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class SavePostRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge(['is_featured' => $this->boolean('is_featured')]);
    }

    public function authorize(): bool
    {
        return (bool) $this->user()?->is_admin;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'alpha_dash:ascii', 'max:255', Rule::unique('posts', 'slug')->ignore($this->route('post'))],
            'excerpt' => ['required', 'string', 'max:1000'],
            'content' => ['required', 'string'],
            'status' => ['required', Rule::enum(ContentStatus::class)],
            'published_at' => ['nullable', 'date', Rule::requiredIf($this->string('status')->toString() === ContentStatus::Scheduled->value)],
            'category_id' => ['nullable', 'exists:categories,id'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'is_featured' => ['boolean'],
            'seo_title' => ['nullable', 'string', 'max:70'],
            'seo_description' => ['nullable', 'string', 'max:160'],
            'tags' => ['array'],
            'tags.*' => ['integer', 'exists:tags,id'],
            'new_tags' => ['nullable', 'string', 'max:500'],
            'new_category' => ['nullable', 'string', 'max:100'],
        ];
    }
}
