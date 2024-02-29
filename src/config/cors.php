<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    // 'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'paths' => ['*'],

    // マッチするHTTPメソッド。 `[*]` だと全てのリクエストにマッチする。
    //GETとPOSTだけを許可する場合: ['GET', 'POST']
    'allowed_methods' => ['*'],

    // 許可するリクエストオリジンの設定
    //`*`かオリジンに完全一致、またはワイルドカードが利用可。
    'allowed_origins' => ['*'],

    //正規表現によるオリジン指定。preg_matchの引数としてそのまま渡される。
    'allowed_origins_patterns' => [],

     // Access-Control-Allow-Headers response header　レスポンスヘッダーの指定
    'allowed_headers' => ['*'],

     //Access-Control-Expose-Headers レスポンスヘッダーの指定
    'exposed_headers' => false,

    //Access-Control-Max-Age レスポンスヘッダーの指定
    'max_age' => false,

    // Access-Control-Allow-Credentialsヘッダーを設定する。
    //falsy値を指定すると出力せず、truthyな値を渡せばtrueが出力される
    'supports_credentials' => false,
];
