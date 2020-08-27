<?php

namespace App\Http\Requests\Jobs;

use Illuminate\Foundation\Http\FormRequest;

class JobStoreRequest extends FormRequest
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
     *b
     * @return array
     */
    public function rules()
    {
        $rules = [
            'job_title' => 'required|max:200',
            'job_type'  => 'required|integer',
            'accept_crypto'=> 'required|in:0,1',
            'remote'  => 'required|in:0,1',
            'salary' => 'nullable|numeric',
            'salary_upto' => 'nullable|numeric',
            'category' => 'required|integer',
            'country' => 'required|integer',
            'address' =>  'max:300',
            'smalldescription' => 'required|max:500',
            'description' =>  'required|max:3000',
            'applyType'=>'required|in:email,link',
            'email'=> 'nullable|email|required_if:applyType,==,email|max:1000',
            'link'=> 'nullable|url|required_if:applyType,==,link|max:1000',
        ];
        return $rules;
    }

    public function messages()
    {
        return [
            'salary.numeric' => 'The salary min must be a number.',
            'salary_upto.numeric' => 'The salary max must be a number.',
            'description.required' => 'The Job Description field is required.',
            'smalldescription.required' => 'The Small  Description field is required.'
        ];
    }
}
