<?php
$volt = [
    0  =>
        [
            'match'   => '0BCD 200D',
            'replace' => '014B',
        ],
    1  =>
        [
            'match'   => '0BCD 200C',
            'replace' => 'E002',
        ],
    2  =>
        [
            'match'   => '200D 0BCD',
            'replace' => '014A',
        ],
    3  =>
        [
            'match'   => '0B95 0BCD 0BB7',
            'replace' => 'E005',
        ],
    4  =>
        [
            'match'   => '0BB8 0BCD 0BB0 0BC0',
            'replace' => 'E04B',
        ],
    5  =>
        [
            'match'   => '0B93 0BAE 0BCD',
            'replace' => 'E04C',
        ],
    6  =>
        [
            'match'   => '(0BB8) 0BC1',
            'replace' => '\\1 E00C',
        ],
    7  =>
        [
            'match'   => '(0BB8) 0BC2',
            'replace' => '\\1 E00D',
        ],
    8  =>
        [
            'match'   => '0B95 0BC2',
            'replace' => 'E00F',
        ],
    9  =>
        [
            'match'   => '0B9C 0BC1',
            'replace' => 'E014',
        ],
    10 =>
        [
            'match'   => '0B9C 0BC2',
            'replace' => 'E015',
        ],
    11 =>
        [
            'match'   => '0B9F 0BBF',
            'replace' => 'E018',
        ],
    12 =>
        [
            'match'   => '0BB2 0BBF',
            'replace' => 'E033',
        ],
    13 =>
        [
            'match'   => '0BB7 0BBF',
            'replace' => 'E03F',
        ],
    14 =>
        [
            'match'   => '0BB7 0BC1',
            'replace' => 'E041',
        ],
    15 =>
        [
            'match'   => '0BB7 0BC2',
            'replace' => 'E042',
        ],
    16 =>
        [
            'match'   => '0BB8 0BBF',
            'replace' => 'E043',
        ],
    17 =>
        [
            'match'   => '0BB9 0BC1',
            'replace' => 'E045',
        ],
    18 =>
        [
            'match'   => '0BB9 0BC2',
            'replace' => 'E046',
        ],
    19 =>
        [
            'match'   => 'E005 0BBF',
            'replace' => 'E047',
        ],
    20 =>
        [
            'match'   => 'E005 0BC1',
            'replace' => 'E049',
        ],
    21 =>
        [
            'match'   => 'E005 0BC2',
            'replace' => 'E04A',
        ],
    22 =>
        [
            'match'   => '((0BAA|0BAF|0B99|0BB5)) 0BC0',
            'replace' => '\\1 E00B',
        ],
    23 =>
        [
            'match'   => '((0BAE|0B9A|0BB9|0B9C|0BB4|0BB1)) 0BBF',
            'replace' => '\\1 E006',
        ],
    24 =>
        [
            'match'   => '((0BB0|0BB3|0BA3|0BA9)) 0BBF',
            'replace' => '\\1 E007',
        ],
    25 =>
        [
            'match'   => '((0B95|0BA4)) 0BBF',
            'replace' => '\\1 E008',
        ],
    26 =>
        [
            'match'   => '((0BAA|0BAF|0B99|0BB5)) 0BBF',
            'replace' => '\\1 E009',
        ],
    27 =>
        [
            'match'   => '((0BA8|0B9E)) 0BBF',
            'replace' => '\\1 E00A',
        ],
    28 =>
        [
            'match'   => '0BA3 200C 0BC8',
            'replace' => 'E01F',
        ],
    29 =>
        [
            'match'   => '0BA9 200C 0BC8',
            'replace' => 'E027',
        ],
    30 =>
        [
            'match'   => '0BB2 200C 0BC8',
            'replace' => 'E037',
        ],
    31 =>
        [
            'match'   => '0BB3 200C 0BC8',
            'replace' => 'E03A',
        ],
    32 =>
        [
            'match'   => '0B9F 0BC0',
            'replace' => 'E019',
        ],
    33 =>
        [
            'match'   => '0BB2 0BC0',
            'replace' => 'E034',
        ],
    34 =>
        [
            'match'   => '0BB7 0BC0',
            'replace' => 'E040',
        ],
    35 =>
        [
            'match'   => '0BB8 0BC0',
            'replace' => 'E044',
        ],
    36 =>
        [
            'match'   => 'E005 0BC0',
            'replace' => 'E048',
        ],
    37 =>
        [
            'match'   => '0B95 0BC1',
            'replace' => 'E00E',
        ],
    38 =>
        [
            'match'   => '0B99 0BC1',
            'replace' => 'E010',
        ],
    39 =>
        [
            'match'   => '0B99 0BC2',
            'replace' => 'E011',
        ],
    40 =>
        [
            'match'   => '0B9A 0BC1',
            'replace' => 'E012',
        ],
    41 =>
        [
            'match'   => '0B9A 0BC2',
            'replace' => 'E013',
        ],
    42 =>
        [
            'match'   => '0B9E 0BC1',
            'replace' => 'E016',
        ],
    43 =>
        [
            'match'   => '0B9E 0BC2',
            'replace' => 'E017',
        ],
    44 =>
        [
            'match'   => '0B9F 0BC1',
            'replace' => 'E01A',
        ],
    45 =>
        [
            'match'   => '0B9F 0BC2',
            'replace' => 'E01B',
        ],
    46 =>
        [
            'match'   => '0BA3 200C 0BBE',
            'replace' => 'E01C',
        ],
    47 =>
        [
            'match'   => '0BA3 0BC1',
            'replace' => 'E01D',
        ],
    48 =>
        [
            'match'   => '0BA3 0BC2',
            'replace' => 'E01E',
        ],
    49 =>
        [
            'match'   => '0BA4 0BC1',
            'replace' => 'E020',
        ],
    50 =>
        [
            'match'   => '0BA4 0BC2',
            'replace' => 'E021',
        ],
    51 =>
        [
            'match'   => '0BA8 0BC1',
            'replace' => 'E022',
        ],
    52 =>
        [
            'match'   => '0BA8 0BC2',
            'replace' => 'E023',
        ],
    53 =>
        [
            'match'   => '0BA9 200C 0BBE',
            'replace' => 'E024',
        ],
    54 =>
        [
            'match'   => '0BA9 0BC1',
            'replace' => 'E025',
        ],
    55 =>
        [
            'match'   => '0BA9 0BC2',
            'replace' => 'E026',
        ],
    56 =>
        [
            'match'   => '0BAA 0BC1',
            'replace' => 'E028',
        ],
    57 =>
        [
            'match'   => '0BAA 0BC2',
            'replace' => 'E029',
        ],
    58 =>
        [
            'match'   => '0BAE 0BC1',
            'replace' => 'E02A',
        ],
    59 =>
        [
            'match'   => '0BAE 0BC2',
            'replace' => 'E02B',
        ],
    60 =>
        [
            'match'   => '0BAF 0BC1',
            'replace' => 'E02C',
        ],
    61 =>
        [
            'match'   => '0BAF 0BC2',
            'replace' => 'E02D',
        ],
    62 =>
        [
            'match'   => '0BB0 0BC1',
            'replace' => 'E02E',
        ],
    63 =>
        [
            'match'   => '0BB0 0BC2',
            'replace' => 'E02F',
        ],
    64 =>
        [
            'match'   => '0BB1 200C 0BBE',
            'replace' => 'E030',
        ],
    65 =>
        [
            'match'   => '0BB1 0BC1',
            'replace' => 'E031',
        ],
    66 =>
        [
            'match'   => '0BB1 0BC2',
            'replace' => 'E032',
        ],
    67 =>
        [
            'match'   => '0BB2 0BC1',
            'replace' => 'E035',
        ],
    68 =>
        [
            'match'   => '0BB2 0BC2',
            'replace' => 'E036',
        ],
    69 =>
        [
            'match'   => '0BB3 0BC1',
            'replace' => 'E038',
        ],
    70 =>
        [
            'match'   => '0BB3 0BC2',
            'replace' => 'E039',
        ],
    71 =>
        [
            'match'   => '0BB4 0BC1',
            'replace' => 'E03B',
        ],
    72 =>
        [
            'match'   => '0BB4 0BC2',
            'replace' => 'E03C',
        ],
    73 =>
        [
            'match'   => '0BB5 0BC1',
            'replace' => 'E03D',
        ],
    74 =>
        [
            'match'   => '0BB5 0BC2',
            'replace' => 'E03E',
        ],
    75 =>
        [
            'match'   => '014B',
            'replace' => '0BCD',
        ],
    76 =>
        [
            'match'   => 'E002',
            'replace' => '0BCD',
        ],
    77 =>
        [
            'match'   => '014A',
            'replace' => '0BCD',
        ],
];
?>