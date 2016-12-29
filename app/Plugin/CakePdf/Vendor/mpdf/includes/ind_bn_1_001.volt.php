<?php
$volt = [
    0   =>
        [
            'match'   => '0995 09CD 09B7',
            'replace' => 'E002',
        ],
    1   =>
        [
            'match'   => '099C 09CD 099E',
            'replace' => 'E003',
        ],
    2   =>
        [
            'match'   => '09CD 200D',
            'replace' => '007E',
        ],
    3   =>
        [
            'match'   => '09CD 200C',
            'replace' => '200C',
        ],
    4   =>
        [
            'match'   => '200D 09CD',
            'replace' => '00D0',
        ],
    5   =>
        [
            'match'   => '((0995|0996|0997|0998|0999|099A|099B|099C|099D|099E|099F|09A0|09A1|09A2|09A3|09A4|09A5|09A6|09A7|09A8|09AA|09AB|09AC|09AD|09AE|09AF|09B0|09B2|09B6|09B7|09B8|09B9)) 09CD 09B0',
            'replace' => '\\1 E1CD',
        ],
    6   =>
        [
            'match'   => '((0995|0996|0997|0998|0999|099A|099B|099C|099D|099E|099F|09A0|09A1|09A2|09A3|09A4|09A5|09A6|09A7|09A8|09AA|09AB|09AC|09AD|09AE|09AF|09B0|09B2|09B6|09B7|09B8|09B9)) 09B0 09CD',
            'replace' => '\\1 E068',
        ],
    7   =>
        [
            'match'   => '((09BE|09C0|09C1|09C2|09C3|09C4|09CB|09CC|09D7|09BC)) 09CD 09B0',
            'replace' => '\\1 E1CD',
        ],
    8   =>
        [
            'match'   => '((09BE|09C0|09C1|09C2|09C3|09C4|09CB|09CC|09D7|09BC)) 09B0 09CD',
            'replace' => '\\1 E068',
        ],
    9   =>
        [
            'match'   => '(0020) 09CD 09B0',
            'replace' => '\\1 E1CD',
        ],
    10  =>
        [
            'match'   => '(0020) 09B0 09CD',
            'replace' => '\\1 E068',
        ],
    11  =>
        [
            'match'   => '(25CC) 09CD 09B0',
            'replace' => '\\1 E1CD',
        ],
    12  =>
        [
            'match'   => '(25CC) 09B0 09CD',
            'replace' => '\\1 E068',
        ],
    13  =>
        [
            'match'   => '((09B0|E042|E043|E044|E048|E049|E04E|E04F|E050|E051|E052|E053|E054|E056|E057|E058|E059|E05B|E05C|E05D|E05E|E062|E063|E064|E065|E0A8|E0BC|E0EF|E0FD|E101|E11C|E11E|E14F|E151|E152|E164|E17D|E18E|E190)) 09C1',
            'replace' => '\\1 E03C',
        ],
    14  =>
        [
            'match'   => '((09B0|E042|E043|E044|E048|E049|E04E|E04F|E050|E051|E052|E053|E054|E056|E057|E058|E059|E05B|E05C|E05D|E05E|E062|E063|E064|E065|E0A8|E0BC|E0EF|E0FD|E101|E11C|E11E|E14F|E151|E152|E164|E17D|E18E|E190)) 09C2',
            'replace' => '\\1 E03E',
        ],
    15  =>
        [
            'match'   => '((E045|E046|E047|E04A|E04B|E04C|E04D|E05F|E060|E061|E07C|E07D|E0B1|E0E8|E0E9|E11A|E11B|E163|E17B|E18D)) 09C1',
            'replace' => '\\1 E03D',
        ],
    16  =>
        [
            'match'   => 'E068 0981',
            'replace' => 'E069',
        ],
    17  =>
        [
            'match'   => '0995 09CD 0995',
            'replace' => 'E06A',
        ],
    18  =>
        [
            'match'   => '0995 09CD 0996',
            'replace' => 'E06B',
        ],
    19  =>
        [
            'match'   => '0995 09CD 099A',
            'replace' => 'E06C',
        ],
    20  =>
        [
            'match'   => '0995 09CD 099B',
            'replace' => 'E06D',
        ],
    21  =>
        [
            'match'   => '0995 09CD 099F',
            'replace' => 'E06E',
        ],
    22  =>
        [
            'match'   => '0995 09CD 09A0',
            'replace' => 'E06F',
        ],
    23  =>
        [
            'match'   => '0995 09CD 09A3',
            'replace' => 'E070',
        ],
    24  =>
        [
            'match'   => '0995 09CD 09A4',
            'replace' => 'E071',
        ],
    25  =>
        [
            'match'   => '0995 09CD 09A5',
            'replace' => 'E072',
        ],
    26  =>
        [
            'match'   => '0995 09CD 09A8',
            'replace' => 'E073',
        ],
    27  =>
        [
            'match'   => '0995 09CD 09AA',
            'replace' => 'E074',
        ],
    28  =>
        [
            'match'   => '0995 09CD 09AB',
            'replace' => 'E075',
        ],
    29  =>
        [
            'match'   => '0995 09CD 09AE',
            'replace' => 'E076',
        ],
    30  =>
        [
            'match'   => '0995 09CD 09B2',
            'replace' => 'E077',
        ],
    31  =>
        [
            'match'   => '0995 09CD 09AC',
            'replace' => 'E078',
        ],
    32  =>
        [
            'match'   => '0995 09CD 09B6',
            'replace' => 'E079',
        ],
    33  =>
        [
            'match'   => '0995 09CD 09B8',
            'replace' => 'E07A',
        ],
    34  =>
        [
            'match'   => '0996 09CD 0996',
            'replace' => 'E083',
        ],
    35  =>
        [
            'match'   => '0996 09CD 09A4',
            'replace' => 'E084',
        ],
    36  =>
        [
            'match'   => '0996 09CD 09A8',
            'replace' => 'E085',
        ],
    37  =>
        [
            'match'   => '0996 09CD 09AE',
            'replace' => 'E086',
        ],
    38  =>
        [
            'match'   => '0996 09CD 09AC',
            'replace' => 'E087',
        ],
    39  =>
        [
            'match'   => '0997 09CD 0997',
            'replace' => 'E088',
        ],
    40  =>
        [
            'match'   => '0997 09CD 0998',
            'replace' => 'E089',
        ],
    41  =>
        [
            'match'   => '0997 09CD 099C',
            'replace' => 'E08A',
        ],
    42  =>
        [
            'match'   => '0997 09CD 099D',
            'replace' => 'E08B',
        ],
    43  =>
        [
            'match'   => '0997 09CD 09A1',
            'replace' => 'E08C',
        ],
    44  =>
        [
            'match'   => '0997 09CD 09A2',
            'replace' => 'E08D',
        ],
    45  =>
        [
            'match'   => '0997 09CD 09A3',
            'replace' => 'E08E',
        ],
    46  =>
        [
            'match'   => '0997 09CD 09A6',
            'replace' => 'E08F',
        ],
    47  =>
        [
            'match'   => '0997 09CD 09A7',
            'replace' => 'E090',
        ],
    48  =>
        [
            'match'   => '0997 09CD 09A8',
            'replace' => 'E091',
        ],
    49  =>
        [
            'match'   => '0997 09CD 09AB',
            'replace' => 'E092',
        ],
    50  =>
        [
            'match'   => '0997 09CD 09AC',
            'replace' => 'E093',
        ],
    51  =>
        [
            'match'   => '0997 09CD 09AD',
            'replace' => 'E094',
        ],
    52  =>
        [
            'match'   => '0997 09CD 09AE',
            'replace' => 'E095',
        ],
    53  =>
        [
            'match'   => '0997 09CD 09B2',
            'replace' => 'E096',
        ],
    54  =>
        [
            'match'   => '0998 09CD 09A8',
            'replace' => 'E099',
        ],
    55  =>
        [
            'match'   => '0998 09CD 09AE',
            'replace' => 'E09A',
        ],
    56  =>
        [
            'match'   => '0998 09CD 09AC',
            'replace' => 'E09B',
        ],
    57  =>
        [
            'match'   => '0999 09CD 0995',
            'replace' => 'E09C',
        ],
    58  =>
        [
            'match'   => '0999 09CD 0996',
            'replace' => 'E09D',
        ],
    59  =>
        [
            'match'   => '0999 09CD 0997',
            'replace' => 'E09E',
        ],
    60  =>
        [
            'match'   => '0999 09CD 0998',
            'replace' => 'E09F',
        ],
    61  =>
        [
            'match'   => '0999 09CD 09A8',
            'replace' => 'E0A0',
        ],
    62  =>
        [
            'match'   => '0999 09CD 09AD',
            'replace' => 'E0A1',
        ],
    63  =>
        [
            'match'   => '0999 09CD 09AE',
            'replace' => 'E0A2',
        ],
    64  =>
        [
            'match'   => '0999 09CD 09AC',
            'replace' => 'E0A3',
        ],
    65  =>
        [
            'match'   => '0999 09CD 09B6',
            'replace' => 'E0A4',
        ],
    66  =>
        [
            'match'   => '0999 09CD 09B7',
            'replace' => 'E0A5',
        ],
    67  =>
        [
            'match'   => '0999 09CD 09B9',
            'replace' => 'E0A6',
        ],
    68  =>
        [
            'match'   => '099A 09CD 099A',
            'replace' => 'E0AC',
        ],
    69  =>
        [
            'match'   => '099A 09CD 099B',
            'replace' => 'E0AD',
        ],
    70  =>
        [
            'match'   => '099A 09CD 099E',
            'replace' => 'E0AE',
        ],
    71  =>
        [
            'match'   => '099A 09CD 09AE',
            'replace' => 'E0AF',
        ],
    72  =>
        [
            'match'   => '099A 09CD 09AC',
            'replace' => 'E0B0',
        ],
    73  =>
        [
            'match'   => '099B 09CD 099B',
            'replace' => 'E0B4',
        ],
    74  =>
        [
            'match'   => '099B 09CD 09B2',
            'replace' => 'E0B5',
        ],
    75  =>
        [
            'match'   => '099B 09CD 09AC',
            'replace' => 'E0B6',
        ],
    76  =>
        [
            'match'   => '099C 09CD 099C',
            'replace' => 'E0B7',
        ],
    77  =>
        [
            'match'   => '099C 09CD 099D',
            'replace' => 'E0B8',
        ],
    78  =>
        [
            'match'   => '099C 09CD 09A6',
            'replace' => 'E0B9',
        ],
    79  =>
        [
            'match'   => '099C 09CD 09AC',
            'replace' => 'E0BA',
        ],
    80  =>
        [
            'match'   => '099C 09CD 09AE',
            'replace' => 'E0BB',
        ],
    81  =>
        [
            'match'   => '099D 09CD 099D',
            'replace' => 'E0BE',
        ],
    82  =>
        [
            'match'   => '099D 09CD 09AE',
            'replace' => 'E0BF',
        ],
    83  =>
        [
            'match'   => '099D 09CD 09AC',
            'replace' => 'E0C0',
        ],
    84  =>
        [
            'match'   => '099E 09CD 099A',
            'replace' => 'E0C1',
        ],
    85  =>
        [
            'match'   => '099E 09CD 099B',
            'replace' => 'E0C2',
        ],
    86  =>
        [
            'match'   => '099E 09CD 099C',
            'replace' => 'E0C3',
        ],
    87  =>
        [
            'match'   => '099E 09CD 099D',
            'replace' => 'E0C4',
        ],
    88  =>
        [
            'match'   => '099E 09CD 09B6',
            'replace' => 'E0C5',
        ],
    89  =>
        [
            'match'   => '099F 09CD 0995',
            'replace' => 'E0C6',
        ],
    90  =>
        [
            'match'   => '099F 09CD 0996',
            'replace' => 'E0C7',
        ],
    91  =>
        [
            'match'   => '099F 09CD 099A',
            'replace' => 'E0C8',
        ],
    92  =>
        [
            'match'   => '099F 09CD 099B',
            'replace' => 'E0C9',
        ],
    93  =>
        [
            'match'   => '099F 09CD 099F',
            'replace' => 'E0CA',
        ],
    94  =>
        [
            'match'   => '099F 09CD 09A0',
            'replace' => 'E0CB',
        ],
    95  =>
        [
            'match'   => '099F 09CD 09A4',
            'replace' => 'E0CC',
        ],
    96  =>
        [
            'match'   => '099F 09CD 09A5',
            'replace' => 'E0CD',
        ],
    97  =>
        [
            'match'   => '099F 09CD 09AA',
            'replace' => 'E0CE',
        ],
    98  =>
        [
            'match'   => '099F 09CD 09AB',
            'replace' => 'E0CF',
        ],
    99  =>
        [
            'match'   => '099F 09CD 09AC',
            'replace' => 'E0D0',
        ],
    100 =>
        [
            'match'   => '099F 09CD 09AE',
            'replace' => 'E0D1',
        ],
    101 =>
        [
            'match'   => '099F 09CD 09B6',
            'replace' => 'E0D2',
        ],
    102 =>
        [
            'match'   => '099F 09CD 09B7',
            'replace' => 'E0D3',
        ],
    103 =>
        [
            'match'   => '099F 09CD 09B8',
            'replace' => 'E0D4',
        ],
    104 =>
        [
            'match'   => '09A0 09CD 09A0',
            'replace' => 'E0D5',
        ],
    105 =>
        [
            'match'   => '09A0 09CD 09A3',
            'replace' => 'E0D6',
        ],
    106 =>
        [
            'match'   => '09A0 09CD 09AC',
            'replace' => 'E0D7',
        ],
    107 =>
        [
            'match'   => '09A1 09CD 0997',
            'replace' => 'E0D8',
        ],
    108 =>
        [
            'match'   => '09A1 09CD 09A1',
            'replace' => 'E0D9',
        ],
    109 =>
        [
            'match'   => '09A1 09CD 09A2',
            'replace' => 'E0DA',
        ],
    110 =>
        [
            'match'   => '09A1 09CD 09AE',
            'replace' => 'E0DB',
        ],
    111 =>
        [
            'match'   => '09A1 09CD 09AC',
            'replace' => 'E0DC',
        ],
    112 =>
        [
            'match'   => '09A2 09CD 09A2',
            'replace' => 'E0DD',
        ],
    113 =>
        [
            'match'   => '09A2 09CD 09A3',
            'replace' => 'E0DE',
        ],
    114 =>
        [
            'match'   => '09A2 09CD 09AC',
            'replace' => 'E0DF',
        ],
    115 =>
        [
            'match'   => '09A3 09CD 099F',
            'replace' => 'E0E0',
        ],
    116 =>
        [
            'match'   => '09A3 09CD 09A0',
            'replace' => 'E0E1',
        ],
    117 =>
        [
            'match'   => '09A3 09CD 09A1',
            'replace' => 'E0E2',
        ],
    118 =>
        [
            'match'   => '09A3 09CD 09A2',
            'replace' => 'E0E3',
        ],
    119 =>
        [
            'match'   => '09A3 09CD 09A3',
            'replace' => 'E0E4',
        ],
    120 =>
        [
            'match'   => '09A3 09CD 09AE',
            'replace' => 'E0E5',
        ],
    121 =>
        [
            'match'   => '09A3 09CD 09AC',
            'replace' => 'E0E6',
        ],
    122 =>
        [
            'match'   => '09A3 09CD 09B8',
            'replace' => 'E0E7',
        ],
    123 =>
        [
            'match'   => '09A4 09CD 09A4',
            'replace' => 'E0EA',
        ],
    124 =>
        [
            'match'   => '09A4 09CD 09A5',
            'replace' => 'E0EB',
        ],
    125 =>
        [
            'match'   => '09A4 09CD 09A8',
            'replace' => 'E0EC',
        ],
    126 =>
        [
            'match'   => '09A4 09CD 09AE',
            'replace' => 'E0ED',
        ],
    127 =>
        [
            'match'   => '09A4 09CD 09AC',
            'replace' => 'E0EE',
        ],
    128 =>
        [
            'match'   => '09A5 09CD 09A5',
            'replace' => 'E0F1',
        ],
    129 =>
        [
            'match'   => '09A5 09CD 09A8',
            'replace' => 'E0F2',
        ],
    130 =>
        [
            'match'   => '09A5 09CD 09AC',
            'replace' => 'E0F3',
        ],
    131 =>
        [
            'match'   => '09A6 09CD 0997',
            'replace' => 'E0F4',
        ],
    132 =>
        [
            'match'   => '09A6 09CD 0998',
            'replace' => 'E0F5',
        ],
    133 =>
        [
            'match'   => '09A6 09CD 09A6',
            'replace' => 'E0F6',
        ],
    134 =>
        [
            'match'   => '09A6 09CD 09A7',
            'replace' => 'E0F7',
        ],
    135 =>
        [
            'match'   => '09A6 09CD 09A8',
            'replace' => 'E0F8',
        ],
    136 =>
        [
            'match'   => '09A6 09CD 09AC',
            'replace' => 'E0F9',
        ],
    137 =>
        [
            'match'   => '09A6 09CD 09AE',
            'replace' => 'E0FB',
        ],
    138 =>
        [
            'match'   => '09A6 09CD 09AF',
            'replace' => 'E0FC',
        ],
    139 =>
        [
            'match'   => '09A7 09CD 09A7',
            'replace' => 'E102',
        ],
    140 =>
        [
            'match'   => '09A7 09CD 09A8',
            'replace' => 'E103',
        ],
    141 =>
        [
            'match'   => '09A7 09CD 09AE',
            'replace' => 'E104',
        ],
    142 =>
        [
            'match'   => '09A7 09CD 09AC',
            'replace' => 'E105',
        ],
    143 =>
        [
            'match'   => '09A8 09CD 0995',
            'replace' => 'E106',
        ],
    144 =>
        [
            'match'   => '09A8 09CD 0997',
            'replace' => 'E107',
        ],
    145 =>
        [
            'match'   => '09A8 09CD 099A',
            'replace' => 'E108',
        ],
    146 =>
        [
            'match'   => '09A8 09CD 099C',
            'replace' => 'E109',
        ],
    147 =>
        [
            'match'   => '09A8 09CD 099F',
            'replace' => 'E10A',
        ],
    148 =>
        [
            'match'   => '09A8 09CD 09A0',
            'replace' => 'E10B',
        ],
    149 =>
        [
            'match'   => '09A8 09CD 09A1',
            'replace' => 'E10C',
        ],
    150 =>
        [
            'match'   => '09A8 09CD 09A4',
            'replace' => 'E10D',
        ],
    151 =>
        [
            'match'   => '09A8 09CD 09A5',
            'replace' => 'E10E',
        ],
    152 =>
        [
            'match'   => '09A8 09CD 09A6',
            'replace' => 'E10F',
        ],
    153 =>
        [
            'match'   => '09A8 09CD 09A7',
            'replace' => 'E110',
        ],
    154 =>
        [
            'match'   => '09A8 09CD 09A8',
            'replace' => 'E111',
        ],
    155 =>
        [
            'match'   => '09A8 09CD 09AB',
            'replace' => 'E112',
        ],
    156 =>
        [
            'match'   => '09A8 09CD 09AD',
            'replace' => 'E113',
        ],
    157 =>
        [
            'match'   => '09A8 09CD 09AE',
            'replace' => 'E114',
        ],
    158 =>
        [
            'match'   => '09A8 09CD 09AF',
            'replace' => 'E115',
        ],
    159 =>
        [
            'match'   => '09A8 09CD 09AC',
            'replace' => 'E116',
        ],
    160 =>
        [
            'match'   => '09A8 09CD 09B6',
            'replace' => 'E117',
        ],
    161 =>
        [
            'match'   => '09A8 09CD 09B7',
            'replace' => 'E118',
        ],
    162 =>
        [
            'match'   => '09A8 09CD 09B8',
            'replace' => 'E119',
        ],
    163 =>
        [
            'match'   => '09AA 09CD 0995',
            'replace' => 'E122',
        ],
    164 =>
        [
            'match'   => '09AA 09CD 0996',
            'replace' => 'E123',
        ],
    165 =>
        [
            'match'   => '09AA 09CD 099A',
            'replace' => 'E124',
        ],
    166 =>
        [
            'match'   => '09AA 09CD 099B',
            'replace' => 'E125',
        ],
    167 =>
        [
            'match'   => '09AA 09CD 099F',
            'replace' => 'E126',
        ],
    168 =>
        [
            'match'   => '09AA 09CD 09A0',
            'replace' => 'E127',
        ],
    169 =>
        [
            'match'   => '09AA 09CD 09A4',
            'replace' => 'E128',
        ],
    170 =>
        [
            'match'   => '09AA 09CD 09A8',
            'replace' => 'E129',
        ],
    171 =>
        [
            'match'   => '09AA 09CD 09AA',
            'replace' => 'E12A',
        ],
    172 =>
        [
            'match'   => '09AA 09CD 09AB',
            'replace' => 'E12B',
        ],
    173 =>
        [
            'match'   => '09AA 09CD 09AE',
            'replace' => 'E12C',
        ],
    174 =>
        [
            'match'   => '09AA 09CD 09B2',
            'replace' => 'E12D',
        ],
    175 =>
        [
            'match'   => '09AA 09CD 09AC',
            'replace' => 'E12E',
        ],
    176 =>
        [
            'match'   => '09AA 09CD 09B6',
            'replace' => 'E12F',
        ],
    177 =>
        [
            'match'   => '09AA 09CD 09B7',
            'replace' => 'E130',
        ],
    178 =>
        [
            'match'   => '09AA 09CD 09B8',
            'replace' => 'E131',
        ],
    179 =>
        [
            'match'   => '09AB 09CD 099F',
            'replace' => 'E132',
        ],
    180 =>
        [
            'match'   => '09AB 09CD 09A4',
            'replace' => 'E133',
        ],
    181 =>
        [
            'match'   => '09AB 09CD 09AA',
            'replace' => 'E134',
        ],
    182 =>
        [
            'match'   => '09AB 09CD 09AB',
            'replace' => 'E135',
        ],
    183 =>
        [
            'match'   => '09AB 09CD 09B2',
            'replace' => 'E136',
        ],
    184 =>
        [
            'match'   => '09AC 09CD 099C',
            'replace' => 'E137',
        ],
    185 =>
        [
            'match'   => '09AC 09CD 099D',
            'replace' => 'E138',
        ],
    186 =>
        [
            'match'   => '09AC 09CD 09A1',
            'replace' => 'E139',
        ],
    187 =>
        [
            'match'   => '09AC 09CD 09A2',
            'replace' => 'E13A',
        ],
    188 =>
        [
            'match'   => '09AC 09CD 09A6',
            'replace' => 'E13B',
        ],
    189 =>
        [
            'match'   => '09AC 09CD 09A7',
            'replace' => 'E13C',
        ],
    190 =>
        [
            'match'   => '09AC 09CD 09A8',
            'replace' => 'E13D',
        ],
    191 =>
        [
            'match'   => '09AC 09CD 09B2',
            'replace' => 'E13E',
        ],
    192 =>
        [
            'match'   => '09AC 09CD 09AC',
            'replace' => 'E13F',
        ],
    193 =>
        [
            'match'   => '09AD 09CD 09A3',
            'replace' => 'E141',
        ],
    194 =>
        [
            'match'   => '09AD 09CD 09A8',
            'replace' => 'E142',
        ],
    195 =>
        [
            'match'   => '09AD 09CD 09AD',
            'replace' => 'E143',
        ],
    196 =>
        [
            'match'   => '09AD 09CD 09AE',
            'replace' => 'E144',
        ],
    197 =>
        [
            'match'   => '09AD 09CD 09B2',
            'replace' => 'E145',
        ],
    198 =>
        [
            'match'   => '09AD 09CD 09AC',
            'replace' => 'E146',
        ],
    199 =>
        [
            'match'   => '09AE 09CD 09A3',
            'replace' => 'E147',
        ],
    200 =>
        [
            'match'   => '09AE 09CD 09A8',
            'replace' => 'E148',
        ],
    201 =>
        [
            'match'   => '09AE 09CD 09AA',
            'replace' => 'E149',
        ],
    202 =>
        [
            'match'   => '09AE 09CD 09AB',
            'replace' => 'E14A',
        ],
    203 =>
        [
            'match'   => '09AE 09CD 09AC',
            'replace' => 'E14B',
        ],
    204 =>
        [
            'match'   => '09AE 09CD 09AD',
            'replace' => 'E14C',
        ],
    205 =>
        [
            'match'   => '09AE 09CD 09AE',
            'replace' => 'E14D',
        ],
    206 =>
        [
            'match'   => '09AE 09CD 09B2',
            'replace' => 'E14E',
        ],
    207 =>
        [
            'match'   => '09B2 09CD 0995',
            'replace' => 'E153',
        ],
    208 =>
        [
            'match'   => '09B2 09CD 0996',
            'replace' => 'E154',
        ],
    209 =>
        [
            'match'   => '09B2 09CD 0997',
            'replace' => 'E155',
        ],
    210 =>
        [
            'match'   => '09B2 09CD 099A',
            'replace' => 'E156',
        ],
    211 =>
        [
            'match'   => '09B2 09CD 099C',
            'replace' => 'E157',
        ],
    212 =>
        [
            'match'   => '09B2 09CD 099F',
            'replace' => 'E158',
        ],
    213 =>
        [
            'match'   => '09B2 09CD 09A1',
            'replace' => 'E159',
        ],
    214 =>
        [
            'match'   => '09B2 09CD 09A6',
            'replace' => 'E15A',
        ],
    215 =>
        [
            'match'   => '09B2 09CD 09AA',
            'replace' => 'E15B',
        ],
    216 =>
        [
            'match'   => '09B2 09CD 09AB',
            'replace' => 'E15C',
        ],
    217 =>
        [
            'match'   => '09B2 09CD 09AC',
            'replace' => 'E15D',
        ],
    218 =>
        [
            'match'   => '09B2 09CD 09AE',
            'replace' => 'E15E',
        ],
    219 =>
        [
            'match'   => '09B2 09CD 09B2',
            'replace' => 'E15F',
        ],
    220 =>
        [
            'match'   => '09B2 09CD 09B6',
            'replace' => 'E160',
        ],
    221 =>
        [
            'match'   => '09B2 09CD 09B8',
            'replace' => 'E161',
        ],
    222 =>
        [
            'match'   => '09B2 09CD 09B9',
            'replace' => 'E162',
        ],
    223 =>
        [
            'match'   => '09B6 09CD 0995',
            'replace' => 'E166',
        ],
    224 =>
        [
            'match'   => '09B6 09CD 099A',
            'replace' => 'E167',
        ],
    225 =>
        [
            'match'   => '09B6 09CD 099B',
            'replace' => 'E168',
        ],
    226 =>
        [
            'match'   => '09B6 09CD 09A4',
            'replace' => 'E169',
        ],
    227 =>
        [
            'match'   => '09B6 09CD 09A8',
            'replace' => 'E16A',
        ],
    228 =>
        [
            'match'   => '09B6 09CD 09AA',
            'replace' => 'E16B',
        ],
    229 =>
        [
            'match'   => '09B6 09CD 09AE',
            'replace' => 'E16C',
        ],
    230 =>
        [
            'match'   => '09B6 09CD 09AF',
            'replace' => 'E16D',
        ],
    231 =>
        [
            'match'   => '09B6 09CD 09B2',
            'replace' => 'E16E',
        ],
    232 =>
        [
            'match'   => '09B6 09CD 09AC',
            'replace' => 'E16F',
        ],
    233 =>
        [
            'match'   => '09B6 09CD 09B6',
            'replace' => 'E170',
        ],
    234 =>
        [
            'match'   => '09B7 09CD 0995',
            'replace' => 'E171',
        ],
    235 =>
        [
            'match'   => '09B7 09CD 099F',
            'replace' => 'E172',
        ],
    236 =>
        [
            'match'   => '09B7 09CD 09A0',
            'replace' => 'E173',
        ],
    237 =>
        [
            'match'   => '09B7 09CD 09A3',
            'replace' => 'E174',
        ],
    238 =>
        [
            'match'   => '09B7 09CD 09AA',
            'replace' => 'E175',
        ],
    239 =>
        [
            'match'   => '09B7 09CD 09AB',
            'replace' => 'E176',
        ],
    240 =>
        [
            'match'   => '09B7 09CD 09AE',
            'replace' => 'E177',
        ],
    241 =>
        [
            'match'   => '09B7 09CD 09AF',
            'replace' => 'E178',
        ],
    242 =>
        [
            'match'   => '09B7 09CD 09AC',
            'replace' => 'E179',
        ],
    243 =>
        [
            'match'   => '09B8 09CD 0995',
            'replace' => 'E17E',
        ],
    244 =>
        [
            'match'   => '09B8 09CD 0996',
            'replace' => 'E17F',
        ],
    245 =>
        [
            'match'   => '09B8 09CD 099C',
            'replace' => 'E180',
        ],
    246 =>
        [
            'match'   => '09B8 09CD 099F',
            'replace' => 'E181',
        ],
    247 =>
        [
            'match'   => '09B8 09CD 09A4',
            'replace' => 'E182',
        ],
    248 =>
        [
            'match'   => '09B8 09CD 09A5',
            'replace' => 'E183',
        ],
    249 =>
        [
            'match'   => '09B8 09CD 09A8',
            'replace' => 'E184',
        ],
    250 =>
        [
            'match'   => '09B8 09CD 09AA',
            'replace' => 'E185',
        ],
    251 =>
        [
            'match'   => '09B8 09CD 09AB',
            'replace' => 'E186',
        ],
    252 =>
        [
            'match'   => '09B8 09CD 09AE',
            'replace' => 'E187',
        ],
    253 =>
        [
            'match'   => '09B8 09CD 09AF',
            'replace' => 'E188',
        ],
    254 =>
        [
            'match'   => '09B8 09CD 09B2',
            'replace' => 'E189',
        ],
    255 =>
        [
            'match'   => '09B8 09CD 09AC',
            'replace' => 'E18A',
        ],
    256 =>
        [
            'match'   => '09B8 09CD 09B8',
            'replace' => 'E18B',
        ],
    257 =>
        [
            'match'   => '09B9 09CD 09A3',
            'replace' => 'E192',
        ],
    258 =>
        [
            'match'   => '09B9 09CD 09A8',
            'replace' => 'E193',
        ],
    259 =>
        [
            'match'   => '09B9 09CD 09AE',
            'replace' => 'E194',
        ],
    260 =>
        [
            'match'   => '09B9 09CD 09AF',
            'replace' => 'E195',
        ],
    261 =>
        [
            'match'   => '09B9 09CD 09B2',
            'replace' => 'E196',
        ],
    262 =>
        [
            'match'   => '09B9 09CD 09AC',
            'replace' => 'E197',
        ],
    263 =>
        [
            'match'   => '09DC 09CD 0997',
            'replace' => 'E198',
        ],
    264 =>
        [
            'match'   => '09DC 09CD 099C',
            'replace' => 'E199',
        ],
    265 =>
        [
            'match'   => '09DC 09CD 09A7',
            'replace' => 'E19A',
        ],
    266 =>
        [
            'match'   => '0995 E1CD',
            'replace' => 'E041',
        ],
    267 =>
        [
            'match'   => '0996 E1CD',
            'replace' => 'E042',
        ],
    268 =>
        [
            'match'   => '0997 E1CD',
            'replace' => 'E043',
        ],
    269 =>
        [
            'match'   => '0998 E1CD',
            'replace' => 'E044',
        ],
    270 =>
        [
            'match'   => '0999 E1CD',
            'replace' => 'E045',
        ],
    271 =>
        [
            'match'   => '099A E1CD',
            'replace' => 'E046',
        ],
    272 =>
        [
            'match'   => '099B E1CD',
            'replace' => 'E047',
        ],
    273 =>
        [
            'match'   => '099C E1CD',
            'replace' => 'E048',
        ],
    274 =>
        [
            'match'   => '099D E1CD',
            'replace' => 'E049',
        ],
    275 =>
        [
            'match'   => '099F E1CD',
            'replace' => 'E04A',
        ],
    276 =>
        [
            'match'   => '09A0 E1CD',
            'replace' => 'E04B',
        ],
    277 =>
        [
            'match'   => '09A1 E1CD',
            'replace' => 'E04C',
        ],
    278 =>
        [
            'match'   => '09A2 E1CD',
            'replace' => 'E04D',
        ],
    279 =>
        [
            'match'   => '09A3 E1CD',
            'replace' => 'E04E',
        ],
    280 =>
        [
            'match'   => '09A4 E1CD',
            'replace' => 'E04F',
        ],
    281 =>
        [
            'match'   => '09A5 E1CD',
            'replace' => 'E050',
        ],
    282 =>
        [
            'match'   => '09A6 E1CD',
            'replace' => 'E051',
        ],
    283 =>
        [
            'match'   => '09A7 E1CD',
            'replace' => 'E052',
        ],
    284 =>
        [
            'match'   => '09A8 E1CD',
            'replace' => 'E053',
        ],
    285 =>
        [
            'match'   => '09AA E1CD',
            'replace' => 'E054',
        ],
    286 =>
        [
            'match'   => '09AB E1CD',
            'replace' => 'E055',
        ],
    287 =>
        [
            'match'   => '09AC E1CD',
            'replace' => 'E056',
        ],
    288 =>
        [
            'match'   => '09AD E1CD',
            'replace' => 'E057',
        ],
    289 =>
        [
            'match'   => '09AE E1CD',
            'replace' => 'E058',
        ],
    290 =>
        [
            'match'   => '09AF E1CD',
            'replace' => 'E059',
        ],
    291 =>
        [
            'match'   => '09B0 E1CD',
            'replace' => 'E05A',
        ],
    292 =>
        [
            'match'   => '09B2 E1CD',
            'replace' => 'E05B',
        ],
    293 =>
        [
            'match'   => '09B6 E1CD',
            'replace' => 'E05C',
        ],
    294 =>
        [
            'match'   => '09B7 E1CD',
            'replace' => 'E05D',
        ],
    295 =>
        [
            'match'   => '09B8 E1CD',
            'replace' => 'E05E',
        ],
    296 =>
        [
            'match'   => '09B9 E1CD',
            'replace' => 'E05F',
        ],
    297 =>
        [
            'match'   => '09DC E1CD',
            'replace' => 'E060',
        ],
    298 =>
        [
            'match'   => '09DD E1CD',
            'replace' => 'E061',
        ],
    299 =>
        [
            'match'   => '09DF E1CD',
            'replace' => 'E062',
        ],
    300 =>
        [
            'match'   => '00D0 09B0',
            'replace' => 'E1CD',
        ],
    301 =>
        [
            'match'   => 'E06A E1CD',
            'replace' => 'E07B',
        ],
    302 =>
        [
            'match'   => 'E06E E1CD',
            'replace' => 'E07C',
        ],
    303 =>
        [
            'match'   => 'E071 E1CD',
            'replace' => 'E07D',
        ],
    304 =>
        [
            'match'   => 'E071 09CD 09AC',
            'replace' => 'E07E',
        ],
    305 =>
        [
            'match'   => 'E002 09CD 09A3',
            'replace' => 'E07F',
        ],
    306 =>
        [
            'match'   => 'E002 09CD 09AE',
            'replace' => 'E080',
        ],
    307 =>
        [
            'match'   => 'E002 E1CD',
            'replace' => 'E081',
        ],
    308 =>
        [
            'match'   => 'E002 09CD 09AC',
            'replace' => 'E082',
        ],
    309 =>
        [
            'match'   => 'E090 E1CD',
            'replace' => 'E097',
        ],
    310 =>
        [
            'match'   => 'E090 09CD 09AC',
            'replace' => 'E098',
        ],
    311 =>
        [
            'match'   => 'E09C E1CD',
            'replace' => 'E0A7',
        ],
    312 =>
        [
            'match'   => 'E09F E1CD',
            'replace' => 'E0A8',
        ],
    313 =>
        [
            'match'   => '0999 09CD E002',
            'replace' => 'E0A9',
        ],
    314 =>
        [
            'match'   => 'E0AD E1CD',
            'replace' => 'E0B1',
        ],
    315 =>
        [
            'match'   => 'E0AD 09CD 09B2',
            'replace' => 'E0B2',
        ],
    316 =>
        [
            'match'   => 'E0AD 09CD 09AC',
            'replace' => 'E0B3',
        ],
    317 =>
        [
            'match'   => 'E0B7 E1CD',
            'replace' => 'E0BC',
        ],
    318 =>
        [
            'match'   => 'E0B7 09CD 09AC',
            'replace' => 'E0BD',
        ],
    319 =>
        [
            'match'   => 'E0E0 E1CD',
            'replace' => 'E0E8',
        ],
    320 =>
        [
            'match'   => 'E0E2 E1CD',
            'replace' => 'E0E9',
        ],
    321 =>
        [
            'match'   => 'E0EA E1CD',
            'replace' => 'E0EF',
        ],
    322 =>
        [
            'match'   => 'E0EA 09CD 09AC',
            'replace' => 'E0F0',
        ],
    323 =>
        [
            'match'   => 'E0F6 E1CD',
            'replace' => 'E0FD',
        ],
    324 =>
        [
            'match'   => 'E0F6 09CD 09AC',
            'replace' => 'E0FE',
        ],
    325 =>
        [
            'match'   => 'E0F7 E1CD',
            'replace' => 'E0FF',
        ],
    326 =>
        [
            'match'   => 'E0F7 09CD 09AC',
            'replace' => 'E100',
        ],
    327 =>
        [
            'match'   => 'E0FA E1CD',
            'replace' => 'E101',
        ],
    328 =>
        [
            'match'   => 'E10A E1CD',
            'replace' => 'E11A',
        ],
    329 =>
        [
            'match'   => 'E10C E1CD',
            'replace' => 'E11B',
        ],
    330 =>
        [
            'match'   => 'E10D E1CD',
            'replace' => 'E11C',
        ],
    331 =>
        [
            'match'   => 'E10D 09CD 09AC',
            'replace' => 'E11D',
        ],
    332 =>
        [
            'match'   => 'E10F E1CD',
            'replace' => 'E11E',
        ],
    333 =>
        [
            'match'   => 'E10F 09CD 09AC',
            'replace' => 'E11F',
        ],
    334 =>
        [
            'match'   => 'E110 E1CD',
            'replace' => 'E120',
        ],
    335 =>
        [
            'match'   => 'E110 09CD 09AC',
            'replace' => 'E121',
        ],
    336 =>
        [
            'match'   => 'E13C 09CD 09AC',
            'replace' => 'E140',
        ],
    337 =>
        [
            'match'   => 'E149 E1CD',
            'replace' => 'E14F',
        ],
    338 =>
        [
            'match'   => 'E14A E1CD',
            'replace' => 'E150',
        ],
    339 =>
        [
            'match'   => 'E14B E1CD',
            'replace' => 'E151',
        ],
    340 =>
        [
            'match'   => 'E14C E1CD',
            'replace' => 'E152',
        ],
    341 =>
        [
            'match'   => 'E158 E1CD',
            'replace' => 'E163',
        ],
    342 =>
        [
            'match'   => 'E15B E1CD',
            'replace' => 'E164',
        ],
    343 =>
        [
            'match'   => 'E161 09CD 099F',
            'replace' => 'E165',
        ],
    344 =>
        [
            'match'   => 'E171 E1CD',
            'replace' => 'E17A',
        ],
    345 =>
        [
            'match'   => 'E172 E1CD',
            'replace' => 'E17B',
        ],
    346 =>
        [
            'match'   => 'E172 09CD 09AC',
            'replace' => 'E17C',
        ],
    347 =>
        [
            'match'   => 'E175 E1CD',
            'replace' => 'E17D',
        ],
    348 =>
        [
            'match'   => 'E17E E1CD',
            'replace' => 'E18C',
        ],
    349 =>
        [
            'match'   => 'E181 E1CD',
            'replace' => 'E18D',
        ],
    350 =>
        [
            'match'   => 'E182 E1CD',
            'replace' => 'E18E',
        ],
    351 =>
        [
            'match'   => 'E182 09CD 09AC',
            'replace' => 'E18F',
        ],
    352 =>
        [
            'match'   => 'E185 E1CD',
            'replace' => 'E190',
        ],
    353 =>
        [
            'match'   => 'E185 09CD 09B2',
            'replace' => 'E191',
        ],
    354 =>
        [
            'match'   => '((0995|0996|0997|0998|0999|099A|099B|099C|099D|099F|09A0|09A1|09A2|09A3|09A4|09A5|09A6|09A7|09A8|09AA|09AB|09AC|09AD|09AE|09AF|09B0|09B2|09B6|09B7|09B8|09B9|09DC|09DD|09DF)) 09CD 09AF',
            'replace' => '\\1 E067',
        ],
    355 =>
        [
            'match'   => '((E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E|E04F|E050|E051|E052|E053|E054|E055|E056|E057|E058|E059|E05A|E05B|E05C|E05D|E05E|E05F|E060|E061|E062)) 09CD 09AF',
            'replace' => '\\1 E067',
        ],
    356 =>
        [
            'match'   => '((E002|E003|E06B|E06C|E06D|E06E|E06F|E070|E071|E072|E073|E074|E075|E076|E079|E07A|E07C|E07D|E07E|E07F|E080|E081|E082|E083|E086|E089|E08A|E08B|E08C|E08D|E08F|E090|E092|E095|E097|E098|E09A|E09B|E09D|E09F|E0A2|E0A4|E0A5|E0A6|E0A7|E0A8|E0A9|E0AA|E0AB|E0AC|E0AD|E0AE|E0AF|E0B1|E0B2|E0B3|E0B4|E0B7|E0B8|E0B9|E0BB|E0BC|E0BD|E0BE|E0BF|E0C5|E0C6|E0C7|E0C8|E0C9|E0CB|E0CC|E0CD|E0CE|E0CF|E0D2|E0D3|E0D5|E0D6|E0D8|E0D9|E0DA|E0DB|E0E0|E0E1|E0E3|E0E5|E0E7|E0E8|E0ED|E0F1|E0F5|E0F6|E0FB|E0FC|E0FD|E0FE|E102|E104|E105|E106|E108|E109|E10A|E10B|E10C|E10F|E110|E112|E114|E115|E117|E118|E119|E11A|E11B|E11E|E11F|E120|E121|E122|E123|E124|E125|E126|E127|E12B|E12C|E130|E131|E132|E133|E134|E135|E136|E137|E139|E13A|E13B|E13F|E144|E149|E14A|E14D|E14F|E150|E153|E154|E156|E157|E158|E159|E15A|E15C|E161|E162|E163|E165|E166|E167|E168|E16C|E16D|E174|E175|E176|E177|E178|E17A|E17B|E17C|E17D|E17E|E17F|E180|E181|E185|E186|E187|E188|E18B|E18C|E18D|E190|E191|E193|E194|E195|E198|E199|E19A|0995|0999|099A|099B|099E|09A1|09A2|09A4|09AB|09AD|09B9|E002|E003|E06A|E073|E074|E077|E078|E07B|E07D|E07E|E07F|E081|E082|E084|E08A|E08C|E08D|E090|E092|E094|E097|E098|E09C|E0A0|E0A1|E0A6|E0A7|E0A9|E0AA|E0AB|E0AC|E0AD|E0AE|E0B1|E0B2|E0B3|E0B4|E0B5|E0B6|E0C1|E0C2|E0C3|E0C4|E0C6|E0C8|E0C9|E0CC|E0CF|E0D9|E0DA|E0DC|E0DD|E0DF|E0E2|E0E3|E0E9|E0EA|E0F0|E108|E109|E10C|E10D|E10E|E110|E112|E113|E11B|E120|E121|E122|E124|E125|E128|E12B|E133|E134|E135|E136|E137|E139|E13A|E13C|E140|E143|E14A|E14C|E153|E156|E157|E159|E15C|E162|E166|E167|E168|E169|E171|E174|E176|E17A|E17E|E180|E182|E183|E18C|E192|E193|E194|E195|E196|E197|09A6|E08F|E0B9|E0F4|E0F6|E0F7|E0F8|E0F9|E0FA|E0FD|E0DC|E0FF|E100|E101|E10F|E11E|E11F|E13B|E15A|099F|09A0|E06E|E06F|E07C|E0CA|E0CB|E0D0|E0D1|E0D4|E0D5|E0D7|E0E0|E0E1|E0E8|E10A|E10B|E11A|E126|E127|E132|E158|E163|E165|E172|E173|E17B|E17C|E181|E18D)) 09CD 09AF',
            'replace' => '\\1 E067',
        ],
    357 =>
        [
            'match'   => '(200C) 09CD 09AF',
            'replace' => '\\1 E067',
        ],
    358 =>
        [
            'match'   => 'E0A9 E1CD',
            'replace' => 'E0AA',
        ],
    359 =>
        [
            'match'   => 'E0A9 E1CD',
            'replace' => 'E0AB',
        ],
    360 =>
        [
            'match'   => '(09BF (0995|0996|0997|0998|0999|099A|099B|099C|099D|099F|09A0|09A1|09A2|09A3|09A4|09A5|09A6|09A7|09A8|09AA|09AB|09AC|09AD|09AE|09AF|09B0|09B2|09B6|09B7|09B8|09B9|09DC|09DD|09DF)) 09CD',
            'replace' => '\\1 09CD 09BF',
        ],
    361 =>
        [
            'match'   => '(09BF (0995|0996|0997|0998|0999|099A|099B|099C|099D|099F|09A0|09A1|09A2|09A3|09A4|09A5|09A6|09A7|09A8|09AA|09AB|09AC|09AD|09AE|09AF|09B0|09B2|09B6|09B7|09B8|09B9|09DC|09DD|09DF)) 007E',
            'replace' => '\\1 007E 09BF',
        ],
    362 =>
        [
            'match'   => '(09C7 (0995|0996|0997|0998|0999|099A|099B|099C|099D|099F|09A0|09A1|09A2|09A3|09A4|09A5|09A6|09A7|09A8|09AA|09AB|09AC|09AD|09AE|09AF|09B0|09B2|09B6|09B7|09B8|09B9|09DC|09DD|09DF)) 09CD',
            'replace' => '\\1 09CD 09C7',
        ],
    363 =>
        [
            'match'   => '(09C7 (0995|0996|0997|0998|0999|099A|099B|099C|099D|099F|09A0|09A1|09A2|09A3|09A4|09A5|09A6|09A7|09A8|09AA|09AB|09AC|09AD|09AE|09AF|09B0|09B2|09B6|09B7|09B8|09B9|09DC|09DD|09DF)) 007E',
            'replace' => '\\1 007E 09C7',
        ],
    364 =>
        [
            'match'   => '(09C8 (0995|0996|0997|0998|0999|099A|099B|099C|099D|099F|09A0|09A1|09A2|09A3|09A4|09A5|09A6|09A7|09A8|09AA|09AB|09AC|09AD|09AE|09AF|09B0|09B2|09B6|09B7|09B8|09B9|09DC|09DD|09DF)) 09CD',
            'replace' => '\\1 09CD 09C8',
        ],
    365 =>
        [
            'match'   => '(09C8 (0995|0996|0997|0998|0999|099A|099B|099C|099D|099F|09A0|09A1|09A2|09A3|09A4|09A5|09A6|09A7|09A8|09AA|09AB|09AC|09AD|09AE|09AF|09B0|09B2|09B6|09B7|09B8|09B9|09DC|09DD|09DF)) 007E',
            'replace' => '\\1 007E 09C8',
        ],
    366 =>
        [
            'match'   => '09BF 0995 (09CD (09BF|09C7|09C8))',
            'replace' => '0995 \\1',
        ],
    367 =>
        [
            'match'   => '09BF 0996 (09CD (09BF|09C7|09C8))',
            'replace' => '0996 \\1',
        ],
    368 =>
        [
            'match'   => '09BF 0997 (09CD (09BF|09C7|09C8))',
            'replace' => '0997 \\1',
        ],
    369 =>
        [
            'match'   => '09BF 0998 (09CD (09BF|09C7|09C8))',
            'replace' => '0998 \\1',
        ],
    370 =>
        [
            'match'   => '09BF 0999 (09CD (09BF|09C7|09C8))',
            'replace' => '0999 \\1',
        ],
    371 =>
        [
            'match'   => '09BF 099A (09CD (09BF|09C7|09C8))',
            'replace' => '099A \\1',
        ],
    372 =>
        [
            'match'   => '09BF 099B (09CD (09BF|09C7|09C8))',
            'replace' => '099B \\1',
        ],
    373 =>
        [
            'match'   => '09BF 099C (09CD (09BF|09C7|09C8))',
            'replace' => '099C \\1',
        ],
    374 =>
        [
            'match'   => '09BF 099D (09CD (09BF|09C7|09C8))',
            'replace' => '099D \\1',
        ],
    375 =>
        [
            'match'   => '09BF 099F (09CD (09BF|09C7|09C8))',
            'replace' => '099F \\1',
        ],
    376 =>
        [
            'match'   => '09BF 09A0 (09CD (09BF|09C7|09C8))',
            'replace' => '09A0 \\1',
        ],
    377 =>
        [
            'match'   => '09BF 09A1 (09CD (09BF|09C7|09C8))',
            'replace' => '09A1 \\1',
        ],
    378 =>
        [
            'match'   => '09BF 09A2 (09CD (09BF|09C7|09C8))',
            'replace' => '09A2 \\1',
        ],
    379 =>
        [
            'match'   => '09BF 09A3 (09CD (09BF|09C7|09C8))',
            'replace' => '09A3 \\1',
        ],
    380 =>
        [
            'match'   => '09BF 09A4 (09CD (09BF|09C7|09C8))',
            'replace' => '09A4 \\1',
        ],
    381 =>
        [
            'match'   => '09BF 09A5 (09CD (09BF|09C7|09C8))',
            'replace' => '09A5 \\1',
        ],
    382 =>
        [
            'match'   => '09BF 09A6 (09CD (09BF|09C7|09C8))',
            'replace' => '09A6 \\1',
        ],
    383 =>
        [
            'match'   => '09BF 09A7 (09CD (09BF|09C7|09C8))',
            'replace' => '09A7 \\1',
        ],
    384 =>
        [
            'match'   => '09BF 09A8 (09CD (09BF|09C7|09C8))',
            'replace' => '09A8 \\1',
        ],
    385 =>
        [
            'match'   => '09BF 09AA (09CD (09BF|09C7|09C8))',
            'replace' => '09AA \\1',
        ],
    386 =>
        [
            'match'   => '09BF 09AB (09CD (09BF|09C7|09C8))',
            'replace' => '09AB \\1',
        ],
    387 =>
        [
            'match'   => '09BF 09AC (09CD (09BF|09C7|09C8))',
            'replace' => '09AC \\1',
        ],
    388 =>
        [
            'match'   => '09BF 09AD (09CD (09BF|09C7|09C8))',
            'replace' => '09AD \\1',
        ],
    389 =>
        [
            'match'   => '09BF 09AE (09CD (09BF|09C7|09C8))',
            'replace' => '09AE \\1',
        ],
    390 =>
        [
            'match'   => '09BF 09AF (09CD (09BF|09C7|09C8))',
            'replace' => '09AF \\1',
        ],
    391 =>
        [
            'match'   => '09BF 09B0 (09CD (09BF|09C7|09C8))',
            'replace' => '09B0 \\1',
        ],
    392 =>
        [
            'match'   => '09BF 09B2 (09CD (09BF|09C7|09C8))',
            'replace' => '09B2 \\1',
        ],
    393 =>
        [
            'match'   => '09BF 09B6 (09CD (09BF|09C7|09C8))',
            'replace' => '09B6 \\1',
        ],
    394 =>
        [
            'match'   => '09BF 09B7 (09CD (09BF|09C7|09C8))',
            'replace' => '09B7 \\1',
        ],
    395 =>
        [
            'match'   => '09BF 09B8 (09CD (09BF|09C7|09C8))',
            'replace' => '09B8 \\1',
        ],
    396 =>
        [
            'match'   => '09BF 09B9 (09CD (09BF|09C7|09C8))',
            'replace' => '09B9 \\1',
        ],
    397 =>
        [
            'match'   => '09BF 09DC (09CD (09BF|09C7|09C8))',
            'replace' => '09DC \\1',
        ],
    398 =>
        [
            'match'   => '09BF 09DD (09CD (09BF|09C7|09C8))',
            'replace' => '09DD \\1',
        ],
    399 =>
        [
            'match'   => '09BF 09DF (09CD (09BF|09C7|09C8))',
            'replace' => '09DF \\1',
        ],
    400 =>
        [
            'match'   => '09C7 0995 (09CD (09BF|09C7|09C8))',
            'replace' => '0995 \\1',
        ],
    401 =>
        [
            'match'   => '09C7 0996 (09CD (09BF|09C7|09C8))',
            'replace' => '0996 \\1',
        ],
    402 =>
        [
            'match'   => '09C7 0997 (09CD (09BF|09C7|09C8))',
            'replace' => '0997 \\1',
        ],
    403 =>
        [
            'match'   => '09C7 0998 (09CD (09BF|09C7|09C8))',
            'replace' => '0998 \\1',
        ],
    404 =>
        [
            'match'   => '09C7 0999 (09CD (09BF|09C7|09C8))',
            'replace' => '0999 \\1',
        ],
    405 =>
        [
            'match'   => '09C7 099A (09CD (09BF|09C7|09C8))',
            'replace' => '099A \\1',
        ],
    406 =>
        [
            'match'   => '09C7 099B (09CD (09BF|09C7|09C8))',
            'replace' => '099B \\1',
        ],
    407 =>
        [
            'match'   => '09C7 099C (09CD (09BF|09C7|09C8))',
            'replace' => '099C \\1',
        ],
    408 =>
        [
            'match'   => '09C7 099D (09CD (09BF|09C7|09C8))',
            'replace' => '099D \\1',
        ],
    409 =>
        [
            'match'   => '09C7 099F (09CD (09BF|09C7|09C8))',
            'replace' => '099F \\1',
        ],
    410 =>
        [
            'match'   => '09C7 09A0 (09CD (09BF|09C7|09C8))',
            'replace' => '09A0 \\1',
        ],
    411 =>
        [
            'match'   => '09C7 09A1 (09CD (09BF|09C7|09C8))',
            'replace' => '09A1 \\1',
        ],
    412 =>
        [
            'match'   => '09C7 09A2 (09CD (09BF|09C7|09C8))',
            'replace' => '09A2 \\1',
        ],
    413 =>
        [
            'match'   => '09C7 09A3 (09CD (09BF|09C7|09C8))',
            'replace' => '09A3 \\1',
        ],
    414 =>
        [
            'match'   => '09C7 09A4 (09CD (09BF|09C7|09C8))',
            'replace' => '09A4 \\1',
        ],
    415 =>
        [
            'match'   => '09C7 09A5 (09CD (09BF|09C7|09C8))',
            'replace' => '09A5 \\1',
        ],
    416 =>
        [
            'match'   => '09C7 09A6 (09CD (09BF|09C7|09C8))',
            'replace' => '09A6 \\1',
        ],
    417 =>
        [
            'match'   => '09C7 09A7 (09CD (09BF|09C7|09C8))',
            'replace' => '09A7 \\1',
        ],
    418 =>
        [
            'match'   => '09C7 09A8 (09CD (09BF|09C7|09C8))',
            'replace' => '09A8 \\1',
        ],
    419 =>
        [
            'match'   => '09C7 09AA (09CD (09BF|09C7|09C8))',
            'replace' => '09AA \\1',
        ],
    420 =>
        [
            'match'   => '09C7 09AB (09CD (09BF|09C7|09C8))',
            'replace' => '09AB \\1',
        ],
    421 =>
        [
            'match'   => '09C7 09AC (09CD (09BF|09C7|09C8))',
            'replace' => '09AC \\1',
        ],
    422 =>
        [
            'match'   => '09C7 09AD (09CD (09BF|09C7|09C8))',
            'replace' => '09AD \\1',
        ],
    423 =>
        [
            'match'   => '09C7 09AE (09CD (09BF|09C7|09C8))',
            'replace' => '09AE \\1',
        ],
    424 =>
        [
            'match'   => '09C7 09AF (09CD (09BF|09C7|09C8))',
            'replace' => '09AF \\1',
        ],
    425 =>
        [
            'match'   => '09C7 09B0 (09CD (09BF|09C7|09C8))',
            'replace' => '09B0 \\1',
        ],
    426 =>
        [
            'match'   => '09C7 09B2 (09CD (09BF|09C7|09C8))',
            'replace' => '09B2 \\1',
        ],
    427 =>
        [
            'match'   => '09C7 09B6 (09CD (09BF|09C7|09C8))',
            'replace' => '09B6 \\1',
        ],
    428 =>
        [
            'match'   => '09C7 09B7 (09CD (09BF|09C7|09C8))',
            'replace' => '09B7 \\1',
        ],
    429 =>
        [
            'match'   => '09C7 09B8 (09CD (09BF|09C7|09C8))',
            'replace' => '09B8 \\1',
        ],
    430 =>
        [
            'match'   => '09C7 09B9 (09CD (09BF|09C7|09C8))',
            'replace' => '09B9 \\1',
        ],
    431 =>
        [
            'match'   => '09C7 09DC (09CD (09BF|09C7|09C8))',
            'replace' => '09DC \\1',
        ],
    432 =>
        [
            'match'   => '09C7 09DD (09CD (09BF|09C7|09C8))',
            'replace' => '09DD \\1',
        ],
    433 =>
        [
            'match'   => '09C7 09DF (09CD (09BF|09C7|09C8))',
            'replace' => '09DF \\1',
        ],
    434 =>
        [
            'match'   => '09C8 0995 (09CD (09BF|09C7|09C8))',
            'replace' => '0995 \\1',
        ],
    435 =>
        [
            'match'   => '09C8 0996 (09CD (09BF|09C7|09C8))',
            'replace' => '0996 \\1',
        ],
    436 =>
        [
            'match'   => '09C8 0997 (09CD (09BF|09C7|09C8))',
            'replace' => '0997 \\1',
        ],
    437 =>
        [
            'match'   => '09C8 0998 (09CD (09BF|09C7|09C8))',
            'replace' => '0998 \\1',
        ],
    438 =>
        [
            'match'   => '09C8 0999 (09CD (09BF|09C7|09C8))',
            'replace' => '0999 \\1',
        ],
    439 =>
        [
            'match'   => '09C8 099A (09CD (09BF|09C7|09C8))',
            'replace' => '099A \\1',
        ],
    440 =>
        [
            'match'   => '09C8 099B (09CD (09BF|09C7|09C8))',
            'replace' => '099B \\1',
        ],
    441 =>
        [
            'match'   => '09C8 099C (09CD (09BF|09C7|09C8))',
            'replace' => '099C \\1',
        ],
    442 =>
        [
            'match'   => '09C8 099D (09CD (09BF|09C7|09C8))',
            'replace' => '099D \\1',
        ],
    443 =>
        [
            'match'   => '09C8 099F (09CD (09BF|09C7|09C8))',
            'replace' => '099F \\1',
        ],
    444 =>
        [
            'match'   => '09C8 09A0 (09CD (09BF|09C7|09C8))',
            'replace' => '09A0 \\1',
        ],
    445 =>
        [
            'match'   => '09C8 09A1 (09CD (09BF|09C7|09C8))',
            'replace' => '09A1 \\1',
        ],
    446 =>
        [
            'match'   => '09C8 09A2 (09CD (09BF|09C7|09C8))',
            'replace' => '09A2 \\1',
        ],
    447 =>
        [
            'match'   => '09C8 09A3 (09CD (09BF|09C7|09C8))',
            'replace' => '09A3 \\1',
        ],
    448 =>
        [
            'match'   => '09C8 09A4 (09CD (09BF|09C7|09C8))',
            'replace' => '09A4 \\1',
        ],
    449 =>
        [
            'match'   => '09C8 09A5 (09CD (09BF|09C7|09C8))',
            'replace' => '09A5 \\1',
        ],
    450 =>
        [
            'match'   => '09C8 09A6 (09CD (09BF|09C7|09C8))',
            'replace' => '09A6 \\1',
        ],
    451 =>
        [
            'match'   => '09C8 09A7 (09CD (09BF|09C7|09C8))',
            'replace' => '09A7 \\1',
        ],
    452 =>
        [
            'match'   => '09C8 09A8 (09CD (09BF|09C7|09C8))',
            'replace' => '09A8 \\1',
        ],
    453 =>
        [
            'match'   => '09C8 09AA (09CD (09BF|09C7|09C8))',
            'replace' => '09AA \\1',
        ],
    454 =>
        [
            'match'   => '09C8 09AB (09CD (09BF|09C7|09C8))',
            'replace' => '09AB \\1',
        ],
    455 =>
        [
            'match'   => '09C8 09AC (09CD (09BF|09C7|09C8))',
            'replace' => '09AC \\1',
        ],
    456 =>
        [
            'match'   => '09C8 09AD (09CD (09BF|09C7|09C8))',
            'replace' => '09AD \\1',
        ],
    457 =>
        [
            'match'   => '09C8 09AE (09CD (09BF|09C7|09C8))',
            'replace' => '09AE \\1',
        ],
    458 =>
        [
            'match'   => '09C8 09AF (09CD (09BF|09C7|09C8))',
            'replace' => '09AF \\1',
        ],
    459 =>
        [
            'match'   => '09C8 09B0 (09CD (09BF|09C7|09C8))',
            'replace' => '09B0 \\1',
        ],
    460 =>
        [
            'match'   => '09C8 09B2 (09CD (09BF|09C7|09C8))',
            'replace' => '09B2 \\1',
        ],
    461 =>
        [
            'match'   => '09C8 09B6 (09CD (09BF|09C7|09C8))',
            'replace' => '09B6 \\1',
        ],
    462 =>
        [
            'match'   => '09C8 09B7 (09CD (09BF|09C7|09C8))',
            'replace' => '09B7 \\1',
        ],
    463 =>
        [
            'match'   => '09C8 09B8 (09CD (09BF|09C7|09C8))',
            'replace' => '09B8 \\1',
        ],
    464 =>
        [
            'match'   => '09C8 09B9 (09CD (09BF|09C7|09C8))',
            'replace' => '09B9 \\1',
        ],
    465 =>
        [
            'match'   => '09C8 09DC (09CD (09BF|09C7|09C8))',
            'replace' => '09DC \\1',
        ],
    466 =>
        [
            'match'   => '09C8 09DD (09CD (09BF|09C7|09C8))',
            'replace' => '09DD \\1',
        ],
    467 =>
        [
            'match'   => '09C8 09DF (09CD (09BF|09C7|09C8))',
            'replace' => '09DF \\1',
        ],
    468 =>
        [
            'match'   => '09BF 0995 (007E (09BF|09C7|09C8))',
            'replace' => '0995 \\1',
        ],
    469 =>
        [
            'match'   => '09BF 0996 (007E (09BF|09C7|09C8))',
            'replace' => '0996 \\1',
        ],
    470 =>
        [
            'match'   => '09BF 0997 (007E (09BF|09C7|09C8))',
            'replace' => '0997 \\1',
        ],
    471 =>
        [
            'match'   => '09BF 0998 (007E (09BF|09C7|09C8))',
            'replace' => '0998 \\1',
        ],
    472 =>
        [
            'match'   => '09BF 0999 (007E (09BF|09C7|09C8))',
            'replace' => '0999 \\1',
        ],
    473 =>
        [
            'match'   => '09BF 099A (007E (09BF|09C7|09C8))',
            'replace' => '099A \\1',
        ],
    474 =>
        [
            'match'   => '09BF 099B (007E (09BF|09C7|09C8))',
            'replace' => '099B \\1',
        ],
    475 =>
        [
            'match'   => '09BF 099C (007E (09BF|09C7|09C8))',
            'replace' => '099C \\1',
        ],
    476 =>
        [
            'match'   => '09BF 099D (007E (09BF|09C7|09C8))',
            'replace' => '099D \\1',
        ],
    477 =>
        [
            'match'   => '09BF 099F (007E (09BF|09C7|09C8))',
            'replace' => '099F \\1',
        ],
    478 =>
        [
            'match'   => '09BF 09A0 (007E (09BF|09C7|09C8))',
            'replace' => '09A0 \\1',
        ],
    479 =>
        [
            'match'   => '09BF 09A1 (007E (09BF|09C7|09C8))',
            'replace' => '09A1 \\1',
        ],
    480 =>
        [
            'match'   => '09BF 09A2 (007E (09BF|09C7|09C8))',
            'replace' => '09A2 \\1',
        ],
    481 =>
        [
            'match'   => '09BF 09A3 (007E (09BF|09C7|09C8))',
            'replace' => '09A3 \\1',
        ],
    482 =>
        [
            'match'   => '09BF 09A4 (007E (09BF|09C7|09C8))',
            'replace' => '09A4 \\1',
        ],
    483 =>
        [
            'match'   => '09BF 09A5 (007E (09BF|09C7|09C8))',
            'replace' => '09A5 \\1',
        ],
    484 =>
        [
            'match'   => '09BF 09A6 (007E (09BF|09C7|09C8))',
            'replace' => '09A6 \\1',
        ],
    485 =>
        [
            'match'   => '09BF 09A7 (007E (09BF|09C7|09C8))',
            'replace' => '09A7 \\1',
        ],
    486 =>
        [
            'match'   => '09BF 09A8 (007E (09BF|09C7|09C8))',
            'replace' => '09A8 \\1',
        ],
    487 =>
        [
            'match'   => '09BF 09AA (007E (09BF|09C7|09C8))',
            'replace' => '09AA \\1',
        ],
    488 =>
        [
            'match'   => '09BF 09AB (007E (09BF|09C7|09C8))',
            'replace' => '09AB \\1',
        ],
    489 =>
        [
            'match'   => '09BF 09AC (007E (09BF|09C7|09C8))',
            'replace' => '09AC \\1',
        ],
    490 =>
        [
            'match'   => '09BF 09AD (007E (09BF|09C7|09C8))',
            'replace' => '09AD \\1',
        ],
    491 =>
        [
            'match'   => '09BF 09AE (007E (09BF|09C7|09C8))',
            'replace' => '09AE \\1',
        ],
    492 =>
        [
            'match'   => '09BF 09AF (007E (09BF|09C7|09C8))',
            'replace' => '09AF \\1',
        ],
    493 =>
        [
            'match'   => '09BF 09B0 (007E (09BF|09C7|09C8))',
            'replace' => '09B0 \\1',
        ],
    494 =>
        [
            'match'   => '09BF 09B2 (007E (09BF|09C7|09C8))',
            'replace' => '09B2 \\1',
        ],
    495 =>
        [
            'match'   => '09BF 09B6 (007E (09BF|09C7|09C8))',
            'replace' => '09B6 \\1',
        ],
    496 =>
        [
            'match'   => '09BF 09B7 (007E (09BF|09C7|09C8))',
            'replace' => '09B7 \\1',
        ],
    497 =>
        [
            'match'   => '09BF 09B8 (007E (09BF|09C7|09C8))',
            'replace' => '09B8 \\1',
        ],
    498 =>
        [
            'match'   => '09BF 09B9 (007E (09BF|09C7|09C8))',
            'replace' => '09B9 \\1',
        ],
    499 =>
        [
            'match'   => '09BF 09DC (007E (09BF|09C7|09C8))',
            'replace' => '09DC \\1',
        ],
    500 =>
        [
            'match'   => '09BF 09DD (007E (09BF|09C7|09C8))',
            'replace' => '09DD \\1',
        ],
    501 =>
        [
            'match'   => '09BF 09DF (007E (09BF|09C7|09C8))',
            'replace' => '09DF \\1',
        ],
    502 =>
        [
            'match'   => '09C7 0995 (007E (09BF|09C7|09C8))',
            'replace' => '0995 \\1',
        ],
    503 =>
        [
            'match'   => '09C7 0996 (007E (09BF|09C7|09C8))',
            'replace' => '0996 \\1',
        ],
    504 =>
        [
            'match'   => '09C7 0997 (007E (09BF|09C7|09C8))',
            'replace' => '0997 \\1',
        ],
    505 =>
        [
            'match'   => '09C7 0998 (007E (09BF|09C7|09C8))',
            'replace' => '0998 \\1',
        ],
    506 =>
        [
            'match'   => '09C7 0999 (007E (09BF|09C7|09C8))',
            'replace' => '0999 \\1',
        ],
    507 =>
        [
            'match'   => '09C7 099A (007E (09BF|09C7|09C8))',
            'replace' => '099A \\1',
        ],
    508 =>
        [
            'match'   => '09C7 099B (007E (09BF|09C7|09C8))',
            'replace' => '099B \\1',
        ],
    509 =>
        [
            'match'   => '09C7 099C (007E (09BF|09C7|09C8))',
            'replace' => '099C \\1',
        ],
    510 =>
        [
            'match'   => '09C7 099D (007E (09BF|09C7|09C8))',
            'replace' => '099D \\1',
        ],
    511 =>
        [
            'match'   => '09C7 099F (007E (09BF|09C7|09C8))',
            'replace' => '099F \\1',
        ],
    512 =>
        [
            'match'   => '09C7 09A0 (007E (09BF|09C7|09C8))',
            'replace' => '09A0 \\1',
        ],
    513 =>
        [
            'match'   => '09C7 09A1 (007E (09BF|09C7|09C8))',
            'replace' => '09A1 \\1',
        ],
    514 =>
        [
            'match'   => '09C7 09A2 (007E (09BF|09C7|09C8))',
            'replace' => '09A2 \\1',
        ],
    515 =>
        [
            'match'   => '09C7 09A3 (007E (09BF|09C7|09C8))',
            'replace' => '09A3 \\1',
        ],
    516 =>
        [
            'match'   => '09C7 09A4 (007E (09BF|09C7|09C8))',
            'replace' => '09A4 \\1',
        ],
    517 =>
        [
            'match'   => '09C7 09A5 (007E (09BF|09C7|09C8))',
            'replace' => '09A5 \\1',
        ],
    518 =>
        [
            'match'   => '09C7 09A6 (007E (09BF|09C7|09C8))',
            'replace' => '09A6 \\1',
        ],
    519 =>
        [
            'match'   => '09C7 09A7 (007E (09BF|09C7|09C8))',
            'replace' => '09A7 \\1',
        ],
    520 =>
        [
            'match'   => '09C7 09A8 (007E (09BF|09C7|09C8))',
            'replace' => '09A8 \\1',
        ],
    521 =>
        [
            'match'   => '09C7 09AA (007E (09BF|09C7|09C8))',
            'replace' => '09AA \\1',
        ],
    522 =>
        [
            'match'   => '09C7 09AB (007E (09BF|09C7|09C8))',
            'replace' => '09AB \\1',
        ],
    523 =>
        [
            'match'   => '09C7 09AC (007E (09BF|09C7|09C8))',
            'replace' => '09AC \\1',
        ],
    524 =>
        [
            'match'   => '09C7 09AD (007E (09BF|09C7|09C8))',
            'replace' => '09AD \\1',
        ],
    525 =>
        [
            'match'   => '09C7 09AE (007E (09BF|09C7|09C8))',
            'replace' => '09AE \\1',
        ],
    526 =>
        [
            'match'   => '09C7 09AF (007E (09BF|09C7|09C8))',
            'replace' => '09AF \\1',
        ],
    527 =>
        [
            'match'   => '09C7 09B0 (007E (09BF|09C7|09C8))',
            'replace' => '09B0 \\1',
        ],
    528 =>
        [
            'match'   => '09C7 09B2 (007E (09BF|09C7|09C8))',
            'replace' => '09B2 \\1',
        ],
    529 =>
        [
            'match'   => '09C7 09B6 (007E (09BF|09C7|09C8))',
            'replace' => '09B6 \\1',
        ],
    530 =>
        [
            'match'   => '09C7 09B7 (007E (09BF|09C7|09C8))',
            'replace' => '09B7 \\1',
        ],
    531 =>
        [
            'match'   => '09C7 09B8 (007E (09BF|09C7|09C8))',
            'replace' => '09B8 \\1',
        ],
    532 =>
        [
            'match'   => '09C7 09B9 (007E (09BF|09C7|09C8))',
            'replace' => '09B9 \\1',
        ],
    533 =>
        [
            'match'   => '09C7 09DC (007E (09BF|09C7|09C8))',
            'replace' => '09DC \\1',
        ],
    534 =>
        [
            'match'   => '09C7 09DD (007E (09BF|09C7|09C8))',
            'replace' => '09DD \\1',
        ],
    535 =>
        [
            'match'   => '09C7 09DF (007E (09BF|09C7|09C8))',
            'replace' => '09DF \\1',
        ],
    536 =>
        [
            'match'   => '09C8 0995 (007E (09BF|09C7|09C8))',
            'replace' => '0995 \\1',
        ],
    537 =>
        [
            'match'   => '09C8 0996 (007E (09BF|09C7|09C8))',
            'replace' => '0996 \\1',
        ],
    538 =>
        [
            'match'   => '09C8 0997 (007E (09BF|09C7|09C8))',
            'replace' => '0997 \\1',
        ],
    539 =>
        [
            'match'   => '09C8 0998 (007E (09BF|09C7|09C8))',
            'replace' => '0998 \\1',
        ],
    540 =>
        [
            'match'   => '09C8 0999 (007E (09BF|09C7|09C8))',
            'replace' => '0999 \\1',
        ],
    541 =>
        [
            'match'   => '09C8 099A (007E (09BF|09C7|09C8))',
            'replace' => '099A \\1',
        ],
    542 =>
        [
            'match'   => '09C8 099B (007E (09BF|09C7|09C8))',
            'replace' => '099B \\1',
        ],
    543 =>
        [
            'match'   => '09C8 099C (007E (09BF|09C7|09C8))',
            'replace' => '099C \\1',
        ],
    544 =>
        [
            'match'   => '09C8 099D (007E (09BF|09C7|09C8))',
            'replace' => '099D \\1',
        ],
    545 =>
        [
            'match'   => '09C8 099F (007E (09BF|09C7|09C8))',
            'replace' => '099F \\1',
        ],
    546 =>
        [
            'match'   => '09C8 09A0 (007E (09BF|09C7|09C8))',
            'replace' => '09A0 \\1',
        ],
    547 =>
        [
            'match'   => '09C8 09A1 (007E (09BF|09C7|09C8))',
            'replace' => '09A1 \\1',
        ],
    548 =>
        [
            'match'   => '09C8 09A2 (007E (09BF|09C7|09C8))',
            'replace' => '09A2 \\1',
        ],
    549 =>
        [
            'match'   => '09C8 09A3 (007E (09BF|09C7|09C8))',
            'replace' => '09A3 \\1',
        ],
    550 =>
        [
            'match'   => '09C8 09A4 (007E (09BF|09C7|09C8))',
            'replace' => '09A4 \\1',
        ],
    551 =>
        [
            'match'   => '09C8 09A5 (007E (09BF|09C7|09C8))',
            'replace' => '09A5 \\1',
        ],
    552 =>
        [
            'match'   => '09C8 09A6 (007E (09BF|09C7|09C8))',
            'replace' => '09A6 \\1',
        ],
    553 =>
        [
            'match'   => '09C8 09A7 (007E (09BF|09C7|09C8))',
            'replace' => '09A7 \\1',
        ],
    554 =>
        [
            'match'   => '09C8 09A8 (007E (09BF|09C7|09C8))',
            'replace' => '09A8 \\1',
        ],
    555 =>
        [
            'match'   => '09C8 09AA (007E (09BF|09C7|09C8))',
            'replace' => '09AA \\1',
        ],
    556 =>
        [
            'match'   => '09C8 09AB (007E (09BF|09C7|09C8))',
            'replace' => '09AB \\1',
        ],
    557 =>
        [
            'match'   => '09C8 09AC (007E (09BF|09C7|09C8))',
            'replace' => '09AC \\1',
        ],
    558 =>
        [
            'match'   => '09C8 09AD (007E (09BF|09C7|09C8))',
            'replace' => '09AD \\1',
        ],
    559 =>
        [
            'match'   => '09C8 09AE (007E (09BF|09C7|09C8))',
            'replace' => '09AE \\1',
        ],
    560 =>
        [
            'match'   => '09C8 09AF (007E (09BF|09C7|09C8))',
            'replace' => '09AF \\1',
        ],
    561 =>
        [
            'match'   => '09C8 09B0 (007E (09BF|09C7|09C8))',
            'replace' => '09B0 \\1',
        ],
    562 =>
        [
            'match'   => '09C8 09B2 (007E (09BF|09C7|09C8))',
            'replace' => '09B2 \\1',
        ],
    563 =>
        [
            'match'   => '09C8 09B6 (007E (09BF|09C7|09C8))',
            'replace' => '09B6 \\1',
        ],
    564 =>
        [
            'match'   => '09C8 09B7 (007E (09BF|09C7|09C8))',
            'replace' => '09B7 \\1',
        ],
    565 =>
        [
            'match'   => '09C8 09B8 (007E (09BF|09C7|09C8))',
            'replace' => '09B8 \\1',
        ],
    566 =>
        [
            'match'   => '09C8 09B9 (007E (09BF|09C7|09C8))',
            'replace' => '09B9 \\1',
        ],
    567 =>
        [
            'match'   => '09C8 09DC (007E (09BF|09C7|09C8))',
            'replace' => '09DC \\1',
        ],
    568 =>
        [
            'match'   => '09C8 09DD (007E (09BF|09C7|09C8))',
            'replace' => '09DD \\1',
        ],
    569 =>
        [
            'match'   => '09C8 09DF (007E (09BF|09C7|09C8))',
            'replace' => '09DF \\1',
        ],
    570 =>
        [
            'match'   => '09A4 09CD',
            'replace' => 'E066',
        ],
    571 =>
        [
            'match'   => '09A4 007E',
            'replace' => 'E066',
        ],
    572 =>
        [
            'match'   => 'E066 200D',
            'replace' => 'E066',
        ],
    573 =>
        [
            'match'   => '09BF 200D',
            'replace' => '09BF',
        ],
    574 =>
        [
            'match'   => '09C7 200D',
            'replace' => '09C7',
        ],
    575 =>
        [
            'match'   => '09C8 200D',
            'replace' => '09C8',
        ],
    576 =>
        [
            'match'   => '007E',
            'replace' => '09CD',
        ],
    577 =>
        [
            'match'   => '200C',
            'replace' => '09CD',
        ],
    578 =>
        [
            'match'   => '00D0',
            'replace' => '09CD',
        ],
    579 =>
        [
            'match'   => '0997 09C1',
            'replace' => 'E00A',
        ],
    580 =>
        [
            'match'   => '09DC 09C1',
            'replace' => 'E012',
        ],
    581 =>
        [
            'match'   => '09DC 09C2',
            'replace' => 'E013',
        ],
    582 =>
        [
            'match'   => '09DC 09C3',
            'replace' => 'E014',
        ],
    583 =>
        [
            'match'   => '09DC 09C4',
            'replace' => 'E015',
        ],
    584 =>
        [
            'match'   => '09DD 09C1',
            'replace' => 'E016',
        ],
    585 =>
        [
            'match'   => '09DD 09C2',
            'replace' => 'E017',
        ],
    586 =>
        [
            'match'   => '09DD 09C3',
            'replace' => 'E018',
        ],
    587 =>
        [
            'match'   => '09DD 09C4',
            'replace' => 'E019',
        ],
    588 =>
        [
            'match'   => '09B6 09C1',
            'replace' => 'E00F',
        ],
    589 =>
        [
            'match'   => '09B9 09C1',
            'replace' => 'E010',
        ],
    590 =>
        [
            'match'   => '09B9 09C3',
            'replace' => 'E011',
        ],
    591 =>
        [
            'match'   => 'E084 09C1',
            'replace' => 'E19B',
        ],
    592 =>
        [
            'match'   => 'E0F4 09C1',
            'replace' => 'E19C',
        ],
    593 =>
        [
            'match'   => 'E10D 09C1',
            'replace' => 'E19D',
        ],
    594 =>
        [
            'match'   => 'E128 09C1',
            'replace' => 'E19E',
        ],
    595 =>
        [
            'match'   => 'E133 09C1',
            'replace' => 'E19F',
        ],
    596 =>
        [
            'match'   => 'E155 09C1',
            'replace' => 'E1A0',
        ],
    597 =>
        [
            'match'   => 'E169 09C1',
            'replace' => 'E1A1',
        ],
    598 =>
        [
            'match'   => 'E182 09C1',
            'replace' => 'E1A2',
        ],
    599 =>
        [
            'match'   => '09BF ((E002|E003|E06B|E06C|E06D|E06E|E06F|E070|E071|E072|E073|E074|E075|E076|E079|E07A|E07C|E07D|E07E|E07F|E080|E081|E082|E083|E086|E089|E08A|E08B|E08C|E08D|E08F|E090|E092|E095|E097|E098|E09A|E09B|E09D|E09F|E0A2|E0A4|E0A5|E0A6|E0A7|E0A8|E0A9|E0AA|E0AB|E0AC|E0AD|E0AE|E0AF|E0B1|E0B2|E0B3|E0B4|E0B7|E0B8|E0B9|E0BB|E0BC|E0BD|E0BE|E0BF|E0C5|E0C6|E0C7|E0C8|E0C9|E0CB|E0CC|E0CD|E0CE|E0CF|E0D2|E0D3|E0D5|E0D6|E0D8|E0D9|E0DA|E0DB|E0E0|E0E1|E0E3|E0E5|E0E7|E0E8|E0ED|E0F1|E0F5|E0F6|E0FB|E0FC|E0FD|E0FE|E102|E104|E105|E106|E108|E109|E10A|E10B|E10C|E10F|E110|E112|E114|E115|E117|E118|E119|E11A|E11B|E11E|E11F|E120|E121|E122|E123|E124|E125|E126|E127|E12B|E12C|E130|E131|E132|E133|E134|E135|E136|E137|E139|E13A|E13B|E13F|E144|E149|E14A|E14D|E14F|E150|E153|E154|E156|E157|E158|E159|E15A|E15C|E161|E162|E163|E165|E166|E167|E168|E16C|E16D|E174|E175|E176|E177|E178|E17A|E17B|E17C|E17D|E17E|E17F|E180|E181|E185|E186|E187|E188|E18B|E18C|E18D|E190|E191|E193|E194|E195|E198|E199|E19A))',
            'replace' => 'E01C \\1',
        ],
    600 =>
        [
            'match'   => '((0995|0999|099A|099B|099F|09A0|09A1|09A2|09A4|09AB|09AD|09B9)) 09BE',
            'replace' => '\\1 E01A',
        ],
    601 =>
        [
            'match'   => '((0995|0999|099A|099B|099F|09A0|09A1|09A2|09A4|09AB|09AD|09B9)) 09D7',
            'replace' => '\\1 E03F',
        ],
    602 =>
        [
            'match'   => '((09A6)) 09BE',
            'replace' => '\\1 E01B',
        ],
    603 =>
        [
            'match'   => '((09A6)) 09D7',
            'replace' => '\\1 E040',
        ],
    604 =>
        [
            'match'   => '09C0 0981',
            'replace' => 'E1B4',
        ],
    605 =>
        [
            'match'   => '09D7 0981',
            'replace' => 'E1B5',
        ],
    606 =>
        [
            'match'   => 'E01D 0981',
            'replace' => 'E1B6',
        ],
    607 =>
        [
            'match'   => 'E01E 0981',
            'replace' => 'E1B7',
        ],
    608 =>
        [
            'match'   => 'E01F 0981',
            'replace' => 'E1B8',
        ],
    609 =>
        [
            'match'   => 'E03F 0981',
            'replace' => 'E1B9',
        ],
    610 =>
        [
            'match'   => 'E040 0981',
            'replace' => 'E1BA',
        ],
    611 =>
        [
            'match'   => '09C0 E069',
            'replace' => 'E1BB',
        ],
    612 =>
        [
            'match'   => '09D7 E069',
            'replace' => 'E1BC',
        ],
    613 =>
        [
            'match'   => 'E01D E069',
            'replace' => 'E1BD',
        ],
    614 =>
        [
            'match'   => 'E01E E069',
            'replace' => 'E1BE',
        ],
    615 =>
        [
            'match'   => 'E01F E069',
            'replace' => 'E1BF',
        ],
    616 =>
        [
            'match'   => 'E03F E069',
            'replace' => 'E1C0',
        ],
    617 =>
        [
            'match'   => 'E040 E069',
            'replace' => 'E1C1',
        ],
    618 =>
        [
            'match'   => '0987 0981',
            'replace' => 'E1C2',
        ],
    619 =>
        [
            'match'   => '0988 0981',
            'replace' => 'E1C3',
        ],
    620 =>
        [
            'match'   => '098A 0981',
            'replace' => 'E1C4',
        ],
    621 =>
        [
            'match'   => '0990 0981',
            'replace' => 'E1C5',
        ],
    622 =>
        [
            'match'   => '0994 0981',
            'replace' => 'E1C6',
        ],
    623 =>
        [
            'match'   => '099F 0981',
            'replace' => 'E1C7',
        ],
    624 =>
        [
            'match'   => '09A0 0981',
            'replace' => 'E1C8',
        ],
    625 =>
        [
            'match'   => '099F E068',
            'replace' => 'E1C9',
        ],
    626 =>
        [
            'match'   => '09A0 E068',
            'replace' => 'E1CA',
        ],
    627 =>
        [
            'match'   => '099F E069',
            'replace' => 'E1CB',
        ],
    628 =>
        [
            'match'   => '09A0 E069',
            'replace' => 'E1CC',
        ],
    629 =>
        [
            'match'   => '((0995|0999|099A|099B|099E|09A1|09A2|09A4|09AB|09AD|09B9|E002|E003|E06A|E073|E074|E077|E078|E07B|E07D|E07E|E07F|E081|E082|E084|E08A|E08C|E08D|E090|E092|E094|E097|E098|E09C|E0A0|E0A1|E0A6|E0A7|E0A9|E0AA|E0AB|E0AC|E0AD|E0AE|E0B1|E0B2|E0B3|E0B4|E0B5|E0B6|E0C1|E0C2|E0C3|E0C4|E0C6|E0C8|E0C9|E0CC|E0CF|E0D9|E0DA|E0DC|E0DD|E0DF|E0E2|E0E3|E0E9|E0EA|E0F0|E108|E109|E10C|E10D|E10E|E110|E112|E113|E11B|E120|E121|E122|E124|E125|E128|E12B|E133|E134|E135|E136|E137|E139|E13A|E13C|E140|E143|E14A|E14C|E153|E156|E157|E159|E15C|E162|E166|E167|E168|E169|E171|E174|E176|E17A|E17E|E180|E182|E183|E18C|E192|E193|E194|E195|E196|E197)) 09C0',
            'replace' => '\\1 E01D',
        ],
    630 =>
        [
            'match'   => '((09A6|E08F|E0B9|E0F4|E0F6|E0F7|E0F8|E0F9|E0FA|E0FD|E0DC|E0FF|E100|E101|E10F|E11E|E11F|E13B|E15A)) 09C0',
            'replace' => '\\1 E01E',
        ],
    631 =>
        [
            'match'   => '((099F|09A0|E06E|E06F|E07C|E0CA|E0CB|E0D0|E0D1|E0D4|E0D5|E0D7|E0E0|E0E1|E0E8|E10A|E10B|E11A|E126|E127|E132|E158|E163|E165|E172|E173|E17B|E17C|E181|E18D)) 09C0',
            'replace' => '\\1 E01F',
        ],
];
?>