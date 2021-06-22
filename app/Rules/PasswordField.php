<?php
    
namespace App\Rules;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\MatchOldPassword;
use Illuminate\Http\Request;

class PasswordField extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        return [
            'current_password' => ['required', new MatchOldPassword],
            'new_password' => ['required'],
            'new_password_confirmation' => ['same:new_password'],
        ];

    }
}

