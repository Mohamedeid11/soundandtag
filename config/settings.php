<?php
return [

    [
        [
            'name' => 'maintenance',
        ],
        [
            "display_name" => "Maintenance Mode",
            "input_type" => 'checkbox',
            'validation' => "nullable",
        ]
    ],
    [
        [
            'name'=>'logo',

        ],
        [
            'validation' => "nullable|image",
            "display_name" => "Logo",
            "input_type" => 'file',
            'value' => 'defaults/default-logo.png'
        ]
    ],
    [
        [
            'name' => 'credimax_user',
        ],
        [
            "display_name" => "Credimax User",
            "input_type" => 'text',
            'validation' => "",
        ]
    ],
    [
        [
            'name' => 'credimax_password',
        ],
        [
            "display_name" => "Credimax Password",
            "input_type" => 'text',
            'validation' => "required",
        ]
    ],
    [
        [
            'name' => 'subscription_price',
        ],
        [
            "display_name" => "Subscription Price",
            "input_type" => 'number',
            'validation' => "required",
            'value' => 12
        ]
    ],
    [
        [
            'name' => 'trial_days',
        ],
        [
            "display_name" => "Trial Days",
            "input_type" => 'number',
            'validation' => "required",
            'value' => '-1'
        ]
    ],
    [
        [
            'name' => 'contact_emails',
        ],
        [
            "display_name" => "Contact Emails (, separated)",
            "input_type" => 'text',
            'validation' => "required",
            'value' => 'support@soundandtag.com,admin@soundandtag.com'
        ]
    ],
    [
        [
            'name' => 'contact_numbers',
        ],
        [
            "display_name" => "Contact Numbers (, separated)",
            "input_type" => 'text',
            'validation' => "nullable",
            'value' => '+973 17820702'
        ]
    ],
    [
        [
            'name' => 'office_location',
        ],
        [
            "display_name" => "Office Location",
            "input_type" => 'textarea',
            'validation' => "nullable",
            'value' => 'Manama center Building
Manama 307, Bahrain'
        ]
    ],

];
