<?php 

return [
    'valid_method' => 'The :attribute method must be in the format "Controller@method"',
    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => 'Field must be accepted.',
    'active_url'           => 'Field is not a valid URL.',
    'after'                => 'Field must be a date after :date.',
    'after_or_equal'       => 'Field must be a date after or equal to :date.',
    'alpha'                => 'Field may only contain letters.',
    'alpha_dash'           => 'Field may only contain letters, numbers, and dashes.',
    'alpha_num'            => 'Field may only contain letters and numbers.',
    'array'                => 'Field must be an array.',
    'before'               => 'Field must be a date before :date.',
    'before_or_equal'      => 'Field must be a date before or equal to :date.',
    'between'              => [
        'numeric' => 'Field must be between :min and :max.',
        'file'    => 'Field must be between :min and :max kilobytes.',
        'string'  => 'Field must be between :min and :max characters.',
        'array'   => 'Field must have between :min and :max items.',
    ],
    'boolean'              => 'Field must be true or false.',
    'confirmed'            => 'Field confirmation does not match.',
    'date'                 => 'Field is not a valid date.',
    'date_format'          => 'Field does not match the format :format.',
    'different'            => 'Field and :other must be different.',
    'digits'               => 'Field must be :digits digits.',
    'digits_between'       => 'Field must be between :min and :max digits.',
    'dimensions'           => 'Field has invalid image dimensions.',
    'distinct'             => 'Field has a duplicate value.',
    'email'                => 'Field must be a valid email address.',
    'exists'               => 'The selected :attribute is invalid.',
    'file'                 => 'Field must be a file.',
    'filled'               => 'Field must have a value.',
    'image'                => 'Field must be an image.',
    'in'                   => 'The selected :attribute is invalid.',
    'in_array'             => 'Field does not exist in :other.',
    'integer'              => 'Field must be an integer.',
    'ip'                   => 'Field must be a valid IP address.',
    'ipv4'                 => 'Field must be a valid IPv4 address.',
    'ipv6'                 => 'Field must be a valid IPv6 address.',
    'json'                 => 'Field must be a valid JSON string.',
    'max'                  => [
        'numeric' => 'Field may not be greater than :max.',
        'file'    => 'Field may not be greater than :max kilobytes.',
        'string'  => 'Field may not be greater than :max characters.',
        'array'   => 'Field may not have more than :max items.',
    ],
    'mimes'                => 'Field must be a file of type: :values.',
    'mimetypes'            => 'Field must be a file of type: :values.',
    'min'                  => [
        'numeric' => 'Field must be at least :min.',
        'file'    => 'Field must be at least :min kilobytes.',
        'string'  => 'Field must be at least :min characters.',
        'array'   => 'Field must have at least :min items.',
    ],
    'not_in'               => 'The selected :attribute is invalid.',
    'numeric'              => 'Field must be a number.',
    'present'              => 'Field must be present.',
    'regex'                => 'Field format is invalid.',
    'required'             => 'Field is required.',
    'required_if'          => 'Field is required when :other is :value.',
    'required_unless'      => 'Field is required unless :other is in :values.',
    'required_with'        => 'Field is required when :values is present.',
    'required_with_all'    => 'Field is required when :values is present.',
    'required_without'     => 'Field is required when :values is not present.',
    'required_without_all' => 'Field is required when none of :values are present.',
    'same'                 => 'Field and :other must match.',
    'size'                 => [
        'numeric' => 'Field must be :size.',
        'file'    => 'Field must be :size kilobytes.',
        'string'  => 'Field must be :size characters.',
        'array'   => 'Field must contain :size items.',
    ],
    'string'               => 'Field must be a string.',
    'timezone'             => 'Field must be a valid zone.',
    'unique'               => 'Field has already been taken.',
    'uploaded'             => 'Field failed to upload.',
    'url'                  => 'Field format is invalid.',
];
