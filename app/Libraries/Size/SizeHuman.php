<?php

namespace App\Libraries\Size;

use Illuminate\Support\Str;
use Carbon\Carbon;

class SizeHuman
{
    public static function parse($size)
    {
        $_ALL_LIST = collect([]);

        if( empty($size) )
        {
            return $_ALL_LIST;
        }

        $_ALL_LIST = collect([
            'B' => collect([
                'count' => number_format($size, 0, ',', ''),
                'text' => collect([
                    'ru' => collect([
                        'short' => 'Б',
                        'long' => 'Байт',
                    ]),
                    'en' => collect([
                        'short' => 'B',
                        'long' => 'Bytes',
                    ]),
                ])
            ]),
            'KB' => collect([
                'count' => number_format($size / 1024, 2, ',', ''),
                'text' => collect([
                    'ru' => collect([
                        'short' => 'KB',
                        'long' => 'Килобайт',
                    ]),
                    'en' => collect([
                        'short' => 'KB',
                        'long' => 'Kilobytes',
                    ]),
                ])
            ]),
            'MB' => collect([
                'count' => number_format($size / 1048576, 2, ',', ''),
                'text' => collect([
                    'ru' => collect([
                        'short' => 'МБ',
                        'long' => 'Мегабайт',
                    ]),
                    'en' => collect([
                        'short' => 'MB',
                        'long' => 'Megabytes',
                    ]),
                ])
            ]),
            'GB' => collect([
                'count' => number_format($size / 1073741824, 2, ',', ''),
                'text' => collect([
                    'ru' => collect([
                        'short' => 'ГБ',
                        'long' => 'Гигабайт',
                    ]),
                    'en' => collect([
                        'short' => 'GB',
                        'long' => 'Gigabytes',
                    ]),
                ]),
            ]),
            'TB' => collect([
                'count' => number_format($size / 1099511627776, 2, ',', ''),
                'text' => collect([
                    'ru' => collect([
                        'short' => 'ТБ',
                        'long' => 'Терабай',
                    ]),
                    'en' => collect([
                        'short' => 'TB',
                        'long' => 'terabytes',
                    ]),
                ])
            ])
        ]);

        if ($size >= 1024 && $size < 1048576)
        {
            $to_human = $_ALL_LIST['KB'];
        } else if ($size >= 1048576 && $size < 1073741824)
        {
            $to_human = $_ALL_LIST['MB'];
        } else if ($size >= 1073741824 && $size < 1099511627776)
        {
            $to_human = $_ALL_LIST['GB'];
        } else if ($size >= 1099511627776 && $size < 1125899906842624)
        {
            $to_human = $_ALL_LIST['TB'];
        } else {
            $to_human = $_ALL_LIST['B'];
        }

        $_ALL_LIST->put('human', $to_human);

        return $_ALL_LIST;
    }
}
