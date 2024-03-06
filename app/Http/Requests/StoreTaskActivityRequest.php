<?php

namespace App\Http\Requests;

use App\Rules\Base64;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

class StoreTaskActivityRequest extends FormRequest
{
    /**
     * Get the validation rules for the activity.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'from' => 'required',
            'to' => 'required',
            'seconds' => 'integer|required',
            'date' => 'required|date',
            'keyboard_count' => 'required|integer',
            'mouse_count' => 'required|integer',
            'total_activity' => 'required|required',
            'total_activity_percentage' => 'required|required',
            'screenshots' => 'array|nullable',
            'task_id'     => 'exists:tasks,id'
            // 'screenshots.*' => ['string', new Base64],
        ];
    }

    /**
     * Remove the image validated field, add the user id and the project id.
     *
     * @return array
     */
    public function validated()
    {
        $validated = Arr::except($this->validator->validated(), ['screenshots']);
        $validated['user_id'] = request()->user()->id;
        $validated['project_id'] =  isset(request()->project_id)?request()->project_id:0;
        $validated['account_id'] = request()->account->id;

        return $validated;
    }
}
