<?php

return [
    'accepted'             => ':Attribute баталсан байх шаардлагатай.',
    'active_url'           => ':Attribute талбарт зөв URL хаяг оруулна уу.',
    'after'                => ':Attribute талбарт :date-с хойш огноо оруулна уу.',
    'after_or_equal'       => ':Attribute талбарт :date эсвэл түүнээс хойш огноо оруулна уу.',
    'alpha'                => ':Attribute талбарт латин үсэг оруулна уу.',
    'alpha_dash'           => ':Attribute талбарт латин үсэг, тоо болон зураас оруулах боломжтой.',
    'alpha_num'            => ':Attribute талбарт латин үсэг болон тоо оруулах боломжтой.',
    'array'                => ':Attribute талбар массив байх шаардлагатай.',
    'attached'             => 'This :attribute is already attached.',
    'before'               => ':Attribute талбарт :date-с өмнөх огноо оруулна уу.',
    'before_or_equal'      => ':attribute талбарт :date эсвэл түүнээс өмнөх огноо оруулна уу.',
    'between'              => [
        'array'   => ':Attribute массивт :min-:max элемэнт байх шаардлагатай.',
        'file'    => ':Attribute талбарт :min-:max килобайт хэмжээтэй файл оруулна уу.',
        'numeric' => ':Attribute талбарт :min-:max хооронд тоо оруулна уу.',
        'string'  => ':Attribute талбарт :min-:max урттай текст оруулна уу.',
    ],
    'boolean'              => ':Attribute талбарын утга үнэн эсвэл худал байх шаардлагатай.',
    'confirmed'            => ':Attribute талбарын баталагажуулалт тохирохгүй байна.',
    'date'                 => ':Attribute талбарт оруулсан огноо буруу байна.',
    'date_equals'          => 'The :attribute must be a date equal to :date.',
    'date_format'          => ':Attribute талбарт :format хэлбэртэй огноо оруулна уу.',
    'different'            => ':Attribute талбарт :other -с өөр утга оруулах шаардлагатай.',
    'digits'               => ':Attribute талбарт дараах цифрүүдээс оруулах боломжтой. :digits.',
    'digits_between'       => ':Attribute талбарт :min-:max хоорондох цифр оруулах боломжтой.',
    'dimensions'           => ':Attribute талбарийн зургийн хэмжээс буруу байна.',
    'distinct'             => ':Attribute талбарт ялгаатай утга оруулах шаардлагатай.',
    'email'                => ':Attribute талбарт зөв и-мэйл хаяг оруулах шаардлагатай.',
    'ends_with'            => 'The :attribute must end with one of the following: :values.',
    'exists'               => 'Сонгогдсон :attribute буруу байна.',
    'file'                 => ':Attribute талбарт файл оруулах шаардлагатай.',
    'filled'               => ':Attribute талбар шаардлагатай.',
    'gt'                   => [
        'array'   => 'The :attribute must have more than :value items.',
        'file'    => 'The :attribute must be greater than :value kilobytes.',
        'numeric' => 'The :attribute must be greater than :value.',
        'string'  => 'The :attribute must be greater than :value characters.',
    ],
    'gte'                  => [
        'array'   => 'The :attribute must have :value items or more.',
        'file'    => 'The :attribute must be greater than or equal :value kilobytes.',
        'numeric' => 'The :attribute must be greater than or equal :value.',
        'string'  => 'The :attribute must be greater than or equal :value characters.',
    ],
    'image'                => ':Attribute талбарт зураг оруулна уу.',
    'in'                   => 'Сонгогдсон :attribute буруу байна.',
    'in_array'             => ':Attribute талбарт оруулсан утга :other -д байхгүй байна.',
    'integer'              => ':Attribute талбарт бүхэл тоо оруулах шаардлагатай.',
    'ip'                   => ':Attribute талбарт зөв IP хаяг оруулах шаардлагатай.',
    'ipv4'                 => 'The :attribute must be a valid IPv4 address.',
    'ipv6'                 => 'The :attribute must be a valid IPv6 address.',
    'json'                 => ':Attribute талбарт зөв JSON тэмдэгт мөр оруулах шаардлагатай.',
    'lt'                   => [
        'array'   => 'The :attribute must have less than :value items.',
        'file'    => 'The :attribute must be less than :value kilobytes.',
        'numeric' => 'The :attribute must be less than :value.',
        'string'  => 'The :attribute must be less than :value characters.',
    ],
    'lte'                  => [
        'array'   => 'The :attribute must not have more than :value items.',
        'file'    => 'The :attribute must be less than or equal :value kilobytes.',
        'numeric' => 'The :attribute must be less than or equal :value.',
        'string'  => 'The :attribute must be less than or equal :value characters.',
    ],
    'max'                  => [
        'array'   => ':Attribute талбарт хамгийн ихдээ :max элемэнт оруулах боломжтой.',
        'file'    => ':Attribute талбарт :max килобайтаас бага хэмжээтэй файл оруулна уу.',
        'numeric' => ':Attribute талбарт :max буюу түүнээс бага утга оруулна уу.',
        'string'  => ':Attribute талбарт :max-с бага урттай текст оруулна уу.',
    ],
    'mimes'                => ':Attribute талбарт дараах төрлийн файл оруулах боломжтой: :values.',
    'mimetypes'            => ':Attribute талбарт дараах төрлийн файл оруулах боломжтой: :values.',
    'min'                  => [
        'array'   => ':Attribute талбарт хамгийн багадаа :min элемэнт оруулах боломжтой.',
        'file'    => ':Attribute талбарт :min килобайтаас их хэмжээтэй файл оруулна уу.',
        'numeric' => ':Attribute талбарт :min буюу түүнээс их тоо оруулна уу.',
        'string'  => ':Attribute талбарт :min буюу түүнээс их үсэг бүхий текст оруулна уу.',
    ],
    'multiple_of'          => 'The :attribute must be a multiple of :value',
    'not_in'               => 'Буруу :attribute сонгогдсон байна.',
    'not_regex'            => 'The :attribute format is invalid.',
    'numeric'              => ':Attribute талбарт тоон утга оруулна уу.',
    'password'             => 'The password is incorrect.',
    'present'              => ':Attribute талбар байх шаардлагатай.',
    'prohibited'           => 'The :attribute field is prohibited.',
    'prohibited_if'        => 'The :attribute field is prohibited when :other is :value.',
    'prohibited_unless'    => 'The :attribute field is prohibited unless :other is in :values.',
    'regex'                => ':Attribute талбарт оруулсан утга буруу байна.',
    'relatable'            => 'This :attribute may not be associated with this resource.',
    'required'             => ':Attribute талбар шаардлагатай.',
    'required_if'          => 'Хэрэв :other :value бол :attribute табларт утга оруулах шаардлагатай.',
    'required_unless'      => ':other :values дотор байхгүй бол :attribute талбарт утга оруулах шаардлагатай.',
    'required_with'        => ':values утгуудийн аль нэг байвал :attribute талбар шаардлагатай.',
    'required_with_all'    => ':values утгууд байвал :attribute талбар шаардлагатай.',
    'required_without'     => 'The :attribute field is required when :values is not present.',
    'required_without_all' => 'The :attribute field is required when none of :values are present.',
    'same'                 => 'The :attribute and :other must match.',
    'size'                 => [
        'array'   => ':Attribute :size элемэнттэй байх шаардлагатай.',
        'file'    => ':Attribute :size килобайт хэмжээтэй байх шаардлагатай.',
        'numeric' => ':Attribute :size хэмжээтэй байх шаардлагатай.',
        'string'  => ':Attribute :size тэмдэгтийн урттай байх шаардлагатай.',
    ],
    'starts_with'          => 'The :attribute must start with one of the following: :values.',
    'string'               => ':Attribute талбарт текст оруулна уу.',
    'timezone'             => ':Attribute талбарт зөв цагийн бүс оруулна уу.',
    'unique'               => 'Оруулсан :attribute аль хэдий нь бүртгэгдсэн байна.',
    'uploaded'             => ':Attribute талбарт оруулсан файлыг хуулхад алдаа гарлаа.',
    'url'                  => ':Attribute зөв url хаяг оруулна уу.',
    'uuid'                 => 'The :attribute must be a valid UUID.',
    'custom'               => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],
    'attributes'           => [],
];