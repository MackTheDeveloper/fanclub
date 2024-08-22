<?php

// TEST API KEY
// return [
//     /**
//      * You can generate API keys here: https://cloudconvert.com/dashboard/api/v2/keys.
//      */
//     'api_key' => env('CLOUDCONVERT_API_KEY', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiMjBhNjgzYTRmZjUzYjdmZDE2ODM2YjNmZDIxYzZhNjQ2YzQ2ZTExZjQyNTY2ZDY1OWRjMWY0MTk0ZGUyYjM0OTIwMmYyN2RlYWRkNDY4NzQiLCJpYXQiOjE2Mzg5NTc4NTIuMzQ2MDAxLCJuYmYiOjE2Mzg5NTc4NTIuMzQ2MDA0LCJleHAiOjQ3OTQ2MzE0NTIuMzQzMzYxLCJzdWIiOiI1NTExNzU2OSIsInNjb3BlcyI6WyJ1c2VyLnJlYWQiLCJ1c2VyLndyaXRlIiwidGFzay5yZWFkIiwidGFzay53cml0ZSIsIndlYmhvb2sucmVhZCIsIndlYmhvb2sud3JpdGUiLCJwcmVzZXQucmVhZCIsInByZXNldC53cml0ZSJdfQ.Sv9vevwiCSzsDerch8AfMhaA7njHs9FZXaafJn5b1b69ywShTKNCA_UE6xrQ5C8FjwZb9_qGfdIUW-4mqhEiPxOxkfwCB6t8vuksZZg4am4rXxGshKTPph4ohRiQZl0yp9WDcDMU1EVQXRPE1fiYIK5xTFOf_MWLZvqKKtgWHF3ssqaEJ4KVXLyc7PaHxwG-0UwOL-jYy6xAT8hGtLHHKTdZyr6gOKY7l16YNa2-gDOvE5egaX-4rUXCC8IU6a09f5LkzAXeT2NYJYbAd2HFzXLOuI8iMlpScrlE20ZaL125lVUrUyCgS6uKaAZ5rltmHkpnweJqN19Hepj_4YuhgoLcgCb2Xv5Pv2n9pG0ZN5wbDG_mruGSJPb-iz1LH4GR3qwho39ocBGonftb676sKodoVfpN2nO2eq_zBmpGOz61cztbnxjoHVTCYqHGriVJRe1wv-762JLqR4hVf_Ylu6rja5lh2zjEq3QVy1Av541mWohh66Jic7X5t96WA27IJMbnT_EAGmYMQJ4FqwRmAD_KeKK6xjurxnUMcV2Lb0CVy2JEmLmBoVMErmz8gdi9Cl8s2vPvNbFlrMTdraSXGUKEPTBsBjyecfkIocZjUn3ROEAsPGQkRw7MszXsBYYWd6qusKLroKPamFbwCJ1EVSinYAPTQ7F1hIg5VWMeOh0'),
//     // 'api_key' => env('CLOUDCONVERT_API_KEY', 'i85LQlJlvtA5hqxTNCuzejHJkc6oJg3TSGmoF2V5'),

//     /**
//      * Use the CloudConvert Sanbox API (Defaults to false, which enables the Production API).
//      */
//     'sandbox' => env('CLOUDCONVERT_SANDBOX', true),

//     /**
//      * You can find the secret used at the webhook settings: https://cloudconvert.com/dashboard/api/v2/webhooks
//      */
//     'webhook_signing_secret' => env('CLOUDCONVERT_WEBHOOK_SIGNING_SECRET', '2sANujNBg0jNkJ6EzQimTlIyonaxSgv5'),

// ];

// LIVE API KEY
return [

    /**
     * You can generate API keys here: https://cloudconvert.com/dashboard/api/v2/keys.
     */
    'api_key' => env('CLOUDCONVERT_API_KEY', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiZmM4NjMzYTRkNDI4OWMzNjM4NGU3ZjcxNDRjMmE3ZTQxMGQ2YjQ0OWFmODk2ODIzM2JiNmE5ODZmNzY0NDc0YWNhZmZjZWQ3OTRjNzUzYTMiLCJpYXQiOjE2NDI1NjQ1NzEuNTQyMDcsIm5iZiI6MTY0MjU2NDU3MS41NDIwNzIsImV4cCI6NDc5ODIzODE3MS41Mjg3OSwic3ViIjoiNTU3MzM3MDIiLCJzY29wZXMiOlsidXNlci5yZWFkIiwidXNlci53cml0ZSIsInRhc2sucmVhZCIsInRhc2sud3JpdGUiLCJ3ZWJob29rLnJlYWQiLCJ3ZWJob29rLndyaXRlIiwicHJlc2V0LnJlYWQiLCJwcmVzZXQud3JpdGUiXX0.i_BsjF3fRauiRTbus5kQ6Z0TSgTDStUfKkXZvuu_eKoFumudxoSCosqhcv62qw0Ht2A5GEvdVfJsrfGKI6DDFLMRmUVlHqIUCdk6kQhWgXXWMGVbcYKkP3Fp4iqtwKhyGI4SZMG0EMhaYa_3U6755tL8yZm0G_WgHdOyvOTz-hBshhWj5w1NcwFdm3BXeofsFhzTbzJoOD6jwStbUnmG8Js1mp7jNu0r8N2nvDWE8CyVyh9xuNt_tJRowGP-HGaGvOCYGJsnrEKTbdseTpsZMdgAucfzMmMQHf30jyehyZZfJw7vt9TXafE1bXGYyZBecD01gTHRERsysbDma4uyF4pg5sCgPPxsXtF1YVJdoLMgaJYFZANy_Crep9dpH-cLqTJK7H47dYpc9uiPCgqeI7MNsEaejZqtyupGAj1qehTgl_Wz8Ek1h5Oi9obnQhBsHkfpDVq6DcJD3f57yVXSjhn7yoSDQxzTOHq8CbMfjSVRFVOpgrvBYoGttDK-habAo4Y0NiVrGVEu9F6bLtLyidhFT3Bgrqw975beYL3gIjemDzxO8KVZtf01IexnKmPVxT6C0R3Z8emkofHF2xUUmVjaRwLNDmLqwn3eN0FVI0eHyyHXwqj6xQhMx9imZbMd9xYhJ1aEK61tniDA8Pb60S51Rmejsg6PeqWdDpzV8aY'),

    /**
     * Use the CloudConvert Sanbox API (Defaults to false, which enables the Production API).
     */
    'sandbox' => env('CLOUDCONVERT_SANDBOX', false),
    

    /**
     * You can find the secret used at the webhook settings: https://cloudconvert.com/dashboard/api/v2/webhooks
     */
    'webhook_signing_secret' => env('CLOUDCONVERT_WEBHOOK_SIGNING_SECRET', 'DhYr1Ln27LtSVpsGZNVjzk1eZRtsrhbJ'),

];
