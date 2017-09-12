<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TournamentRequest extends FormRequest
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
                    'navn' => 'required|min:1|max:70|unique:tournaments,name',
                    'title' => 'required|min:1|max:100|unique:tournaments',
                    'tier' => 'required|min:1|max:15',
                    'wiki' => 'min:1|max:100',
                    'logo' => 'mimes:png|dimensions:width=50,height=50',
                    'start_dato' => 'required|date|before:slut_dato',
                    'slut_dato' => 'required|date',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'navn' => 'min:1|max:70|unique:tournaments,name,'.$this->id,
                    'title' => 'min:1|max:100|unique:tournaments,title,'.$this->id,
                    'tier' => 'max:15',
                    'wiki' => 'max:100',
                    'logo' => 'mimes:png|dimensions:width=50,height=50',
                    'start_dato' => 'date|before:slut_dato',
                    'slut_dato' => 'date',
                ];
            }
            default:break;
        }
    }
}
