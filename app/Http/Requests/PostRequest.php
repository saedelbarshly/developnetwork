<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * on creation set validation rules
     *
     * @return array
     */
    protected function onCreate()
    {
        return [
            'title' => ['required','string','max:255'],
            'body' => ['required','string'],
            'cover_image' => ['required', 'image','max:2048','mimes:png,jpg'],
        ];
    }

     /**
     * on update set validation rules
     *
     * @return array
     */
    protected function onUpdate() 
    {
        return [
            'title' => ['required','string','max:255'],
            'body' => ['required','string'],
            'cover_image' => ['nullable', 'image', 'max:2048','mimes:png,jpg'],
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return $this->isMethod('put') ? $this->onUpdate() : $this->onCreate();
    }
}
