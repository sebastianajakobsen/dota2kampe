<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeamRequest extends FormRequest
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
    public function rules()
    {
        switch($this->method())
        {
            case 'GET':
            case 'DELETE':
            {
                return [];
            }
            case 'POST':
            {
                return [
                    'navn' => 'required|min:1|max:30|unique:teams,name',
                    'title' => 'required|min:1|max:50|unique:teams',
                    'tag' => 'min:1|max:30|unique:teams',
                    'wiki' => 'min:1|max:100',
                    'logo' => 'mimes:png|dimensions:width=120,height=50'
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'navn' => 'min:1|max:30|unique:teams,name,'.$this->id,
                    'title' => 'min:1|max:50|unique:teams,title,'.$this->id,
                    'tag' => 'max:30|unique:teams',
                    'wiki' => 'max:100',
                    'logo' => 'mimes:png|dimensions:width=120,height=50'
                ];
            }
            default:break;
        }
    }
}
