<?php

namespace App\Http\Requests;

use App\Exceptions\ThrottleException;
use App\Models\Reply;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Gate;

class CreateReplyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('create', new Reply);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'body' => ['required', 'spamfree']
        ];
    }

    protected function failedAuthorization()
    {
        throw new ThrottleException();
    }

    // Disable redirection
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json('Sorry, your reply could not be saved at this moment.', 422));
    }
}
