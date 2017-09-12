<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlayerRequest extends FormRequest
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
                    'ingameid' => 'required|min:1|max:30|unique:players,name',
                    'navn' => 'min:1|max:50',
                    'image' => 'mimes:png|dimensions:width=250,height=250',
                    'solo_mmr' => 'integer|between:1,20000|nullable'
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'ingameid' => 'min:1|max:30|unique:players,name,'.$this->id,
                    'navn' => 'min:1|max:50',
                    'image' => 'mimes:png|dimensions:width=250,height=250',
                    'solo_mmr' => 'integer|between:1,20000|nullable'
                ];
            }
            default:break;
        }
    }
}
