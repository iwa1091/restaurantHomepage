<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date'            => ['required', 'date_format:Y-m-d', 'after:today'],
            'start_time'      => ['required', 'date_format:H:i'],
            'party_size'      => ['required', 'integer', 'min:1', 'max:8'],
            'seat_preference' => ['nullable', 'string', 'in:tatami,private,regular'],
            'service_id'      => ['nullable', 'exists:services,id'],
            'name'            => ['required', 'string', 'max:255'],
            'email'           => ['required', 'email', 'max:255'],
            'phone'           => ['required', 'string', 'max:20'],
            'notes'           => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'date.required'           => '日付を選択してください。',
            'date.date_format'        => '日付の形式が正しくありません。',
            'date.after'              => '翌日以降の日付を選択してください。当日のご予約はお電話にて承ります。',

            'start_time.required'     => '時間を選択してください。',
            'start_time.date_format'  => '時間の形式が正しくありません。',

            'service_id.exists'       => '選択されたメニューは存在しません。',
            'party_size.required'     => '人数を選択してください。',
            'party_size.min'          => '1名以上を選択してください。',
            'party_size.max'          => '8名までのご予約はこちらのフォームで承ります。9名以上は宴会予約をご利用ください。',

            'name.required'           => 'お名前を入力してください。',
            'name.max'                => 'お名前は255文字以内で入力してください。',

            'email.required'          => 'メールアドレスを入力してください。',
            'email.email'             => '正しい形式のメールアドレスを入力してください。',
            'email.max'               => 'メールアドレスは255文字以内で入力してください。',

            'phone.required'          => '電話番号を入力してください。',
            'phone.max'               => '電話番号は20文字以内で入力してください。',

            'notes.max'               => '備考は1000文字以内で入力してください。',
        ];
    }
}
