@include('errors.layout', [
    'code' => '403',
    'title' => 'アクセスが許可されていません',
    'message' => 'このページを表示する権限がありません。',
    'subMessage' => 'ログイン状態をご確認のうえ、再度お試しください。',
])
