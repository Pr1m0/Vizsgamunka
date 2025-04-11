<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class RegisterRequest extends FormRequest
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
    public function rules(): array {

        return [
            'name' => 'required|min:3|max:20|regex:/^[\pL\s]+$/u',
            'email' => 'required|email:rfc,dns|unique:users,email',
            'password' => [
                'required',
                'min:8',
                'regex:/[a-z]/',    
                'regex:/[A-Z]/',    
                'regex:/[0-9]/',    
                'regex:/[@$!%*?&]/' 
            ],
            'confirm_password' => 'same:password|required'
        ];
    }

    public function messages() {

        return [
            "name.required" => "Név megadása kötelező.",
            "name.min" => "A név túl rövid.",
            "name.max" => "A név túl hosszú.",
            "name.alpha" => "A név csak betűket tartalmazhat, ékezettel vagy anélkül.",
            "email.required" => "Az email cím megadása kötelező.",
            "email.email" => "Kérjük, érvényes email címet adjon meg.",
            "email.unique" => "Ez az email cím már használatban van.",
            "password.required" => "A jelszó megadása kötelező",
            "password.min" => "A jelszó túl rövid.",
            "password.regex" => "A jelszónak tartalmaznia kell legalább egy kisbetűt, egy nagybetűt, egy számot és egy speciális karaktert.",
            "confirm_password.required" => "A jelszó megerősítése kötelező.",
            "confirm_password.same" => "A megadott jelszavak nem egyeznek."
        ];
    }

    public function failedValidation( Validator $validator ) {

        throw new HttpResponseException( response()->json([
            "success" => false,
            "error" => $validator->errors(),
            "message" => "Adatbeviteli hiba"
        ],422));
    }
}
