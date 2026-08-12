<?php

namespace App\Http\Requests\Admin;

use App\Models\HealthArticle;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateHealthArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage-settings');
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', Rule::in(HealthArticle::CATEGORIES)],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'featured_image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:4096'],
            'status' => ['required', Rule::in([HealthArticle::STATUS_DRAFT, HealthArticle::STATUS_PUBLISHED])],
            'published_at' => ['nullable', 'date'],
        ];
    }
}
