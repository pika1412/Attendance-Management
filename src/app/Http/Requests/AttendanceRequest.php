<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceRequest extends FormRequest
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
        return [
            'start_time' => ['nullable','date_format:H:i','required_with:end_time','before:end_time'],
            'end_time' => ['nullable','date_format:H:i','required_with:start_time','after:start_time'],
            'start_break' => ['nullable','date_format:H:i','required_with:start_time,end_time','after_or_equal:start_time','before_or_equal:end_time'],
            'end_break' => ['nullable','date_format:H:i','before_or_equal:end_time','after_or_equal:start_time'],
            'memo' =>['required'],
        ];
    }

    public function messages(){
        return[
        'start_time.date_format' => '出勤時間もしくは退勤時間が不適切な値です',
        'start_time.before' => '出勤時間もしくは退勤時間が不適切な値です',
        'start_time.required_with' => '出勤時間もしくは退勤時間が不適切な値です',

        'end_time.date_format' => '出勤時間もしくは退勤時間が不適切な値です',
        'end_time.after' => '出勤時間もしくは退勤時間が不適切な値です',
        'end_time.required_with' => '出勤時間もしくは退勤時間が不適切な値です',

        'start_break.date_format' => '出勤時間もしくは退勤時間が不適切な値です',
        'start_break.after_or_equal' => '出勤時間もしくは退勤時間が不適切な値です',
        'start_break.before_or_equal' => '出勤時間もしくは退勤時間が不適切な値です',
        'start_break.required_with' => '出勤時間もしくは退勤時間が不適切な値です',

        'end_break.date_format' => '出勤時間もしくは退勤時間が不適切な値です',
        'end_break.after_or_equal' => '出勤時間もしくは退勤時間が不適切な値です',
        'end_break.before_or_equal' => '出勤時間もしくは退勤時間が不適切な値です',

        'memo.required' => '備考を記入してください',
        ];
    }
}
