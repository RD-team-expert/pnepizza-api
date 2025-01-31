<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;

class UpdateUserRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $data = Role::pluck('name')->toArray();
        $roles = implode(',', $data);
        return [
            'name' => 'string|min:2|max:255',
            'email' => 'email|unique:users,email,'. $this->user->id,
            'image' => 'image|mimes:png,jpg,jpeg',
            'role' => 'string|in:'.$roles
        ];
    }
}
