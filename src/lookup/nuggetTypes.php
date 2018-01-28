 <?php
  $types = [
        'integer' => [
          'type' => 'integer',
          'format' => 'int32',
          'comment' => 'signed 32 bits'  
        ],
        'long' => [
          'type' => 'integer',
          'format' => 'int64',
          'comment' => 'signed 64 bits'  
        ],
        'float' => [
            'type' => 'number',
            'format' => 'float'
        ],
        'double' => [
            'type' => 'number',
            'format' => 'double'
        ],
        'string' =>[
            'type' => 'string'
        ],
        'byte' =>[
            'type' => 'string',
            'format' => 'byte',
            'comment' => 'base64 encoded characters'
        ],
        'binary' =>[
            'type' => 'string',
            'format' => 'binary',
            'comment' => 'any sequence of octets'
        ],
        'boolean' => [
            'type' => ' boolean'
        ],
        'date' =>[
            'type' => 'string',
            'format' => 'date',
            'comment' => 'As defined by full-date - RFC3339'
        ],
        'datetime' =>[
            'type' => 'string',
            'format' => 'date-ime',
            'comment' => 'As defined by date-time - RFC3339'
        ],
        'password' =>[
            'type' => 'string',
            'comment' => 'A hint to UIs to obscure input.'
        ],
        'url' => [
            'type' => 'string'
        ],
        'text' => [
            'type' => 'string'
        ]
    ]
;