<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventCompleteRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'event_id'    => 'required|integer|exists:events,id',
            'title'       => 'required|max:255',
            'date'        => 'required|date',
            'start_time'  => 'required',
            'end_time'    => 'required',
            'format'      => 'required',
            'capacity'    => 'required|integer|min:1',
            'status'      => 'required|string|in:public,private',
            'description' => 'nullable|max:2000',
            'image_path'  => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'event_id.required' => 'イベントIDが不正です。',
            'event_id.exists'   => '指定されたイベントが存在しません。',
            'title.required'    => 'タイトルは必ず入力してください。',
            'title.max'         => 'タイトルは255文字以内で入力してください。',
            'date.required'     => '開催日を選択してください。',
            'date.date'         => '開催日の形式が正しくありません。',
            'start_time.required' => '開始時間を入力してください。',
            'end_time.required'   => '終了時間を入力してください。',
            'format.required'    => '開催形式を選択してください。',
            'capacity.required'  => '参加人数を入力してください。',
            'capacity.integer'   => '参加人数は数字で入力してください。',
            'capacity.min'       => '参加人数は1人以上にしてください。',
            'status.required'    => 'ステータスを選択してください。',
            'status.in'          => 'ステータスの値が不正です。',
            'description.max'    => '説明文は2000文字以内で入力してください。',
        ];
    }
}