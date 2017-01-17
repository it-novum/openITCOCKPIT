<?php
$volt = [
    0   =>
        [
            'match'   => '0CCD 200C',
            'replace' => 'E0AD',
        ],
    1   =>
        [
            'match'   => '200D 0CCD',
            'replace' => 'E0AC',
        ],
    2   =>
        [
            'match'   => '0CC6 0CC2',
            'replace' => '0CCA',
        ],
    3   =>
        [
            'match'   => '0C95 0CCD 0CB7',
            'replace' => 'E07D',
        ],
    4   =>
        [
            'match'   => '0C9C 0CCD 0C9E',
            'replace' => 'E07E',
        ],
    5   =>
        [
            'match'   => '0CB0 0CCD',
            'replace' => 'E00B',
        ],
    6   =>
        [
            'match'   => '((0C95|0C96|0C97|0C98|0C99|0C9A|0C9B|0C9C|0C9D|0C9E|0C9F|0CA0|0CA1|0CA2|0CA3|0CA4|0CA5|0CA6|0CA7|0CA8|0CAA|0CAB|0CAC|0CAD|0CAE|0CAF|0CB0|0CB1|0CB2|0CB3|0CB5|0CB6|0CB7|0CB8|0CB9|E07D|E07E|E0A3)) 0CCD',
            'replace' => '\\1 E0AC',
        ],
    7   =>
        [
            'match'   => '((0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) 0CCD',
            'replace' => '\\1 E0AC',
        ],
    8   =>
        [
            'match'   => '(0CBC) 0CCD',
            'replace' => '\\1 E0AC',
        ],
    9   =>
        [
            'match'   => '(0020) 0CCD',
            'replace' => '\\1 E0AC',
        ],
    10  =>
        [
            'match'   => '(25CC) 0CCD',
            'replace' => '\\1 E0AC',
        ],
    11  =>
        [
            'match'   => '0C95 0CBC',
            'replace' => 'E0E6',
        ],
    12  =>
        [
            'match'   => '0C96 0CBC',
            'replace' => 'E0E7',
        ],
    13  =>
        [
            'match'   => '0C97 0CBC',
            'replace' => 'E172',
        ],
    14  =>
        [
            'match'   => '0C98 0CBC',
            'replace' => 'E173',
        ],
    15  =>
        [
            'match'   => '0C99 0CBC',
            'replace' => 'E174',
        ],
    16  =>
        [
            'match'   => '0C9A 0CBC',
            'replace' => 'E175',
        ],
    17  =>
        [
            'match'   => '0C9B 0CBC',
            'replace' => 'E176',
        ],
    18  =>
        [
            'match'   => '0C9C 0CBC',
            'replace' => 'E0E8',
        ],
    19  =>
        [
            'match'   => '0C9D 0CBC',
            'replace' => 'E0E9',
        ],
    20  =>
        [
            'match'   => '0C9E 0CBC',
            'replace' => 'E177',
        ],
    21  =>
        [
            'match'   => '0C9F 0CBC',
            'replace' => 'E178',
        ],
    22  =>
        [
            'match'   => '0CA0 0CBC',
            'replace' => 'E179',
        ],
    23  =>
        [
            'match'   => '0CA1 0CBC',
            'replace' => 'E17A',
        ],
    24  =>
        [
            'match'   => '0CA2 0CBC',
            'replace' => 'E17B',
        ],
    25  =>
        [
            'match'   => '0CA3 0CBC',
            'replace' => 'E17C',
        ],
    26  =>
        [
            'match'   => '0CA4 0CBC',
            'replace' => 'E17D',
        ],
    27  =>
        [
            'match'   => '0CA5 0CBC',
            'replace' => 'E17E',
        ],
    28  =>
        [
            'match'   => '0CA6 0CBC',
            'replace' => 'E17F',
        ],
    29  =>
        [
            'match'   => '0CA7 0CBC',
            'replace' => 'E180',
        ],
    30  =>
        [
            'match'   => '0CA8 0CBC',
            'replace' => 'E181',
        ],
    31  =>
        [
            'match'   => '0CAA 0CBC',
            'replace' => 'E182',
        ],
    32  =>
        [
            'match'   => '0CAB 0CBC',
            'replace' => 'E0EA',
        ],
    33  =>
        [
            'match'   => '0CAC 0CBC',
            'replace' => 'E183',
        ],
    34  =>
        [
            'match'   => '0CAD 0CBC',
            'replace' => 'E184',
        ],
    35  =>
        [
            'match'   => '0CAE 0CBC',
            'replace' => 'E185',
        ],
    36  =>
        [
            'match'   => '0CAF 0CBC',
            'replace' => 'E186',
        ],
    37  =>
        [
            'match'   => '0CB0 0CBC',
            'replace' => 'E0EB',
        ],
    38  =>
        [
            'match'   => '0CB1 0CBC',
            'replace' => 'E187',
        ],
    39  =>
        [
            'match'   => '0CB2 0CBC',
            'replace' => 'E188',
        ],
    40  =>
        [
            'match'   => '0CB3 0CBC',
            'replace' => 'E189',
        ],
    41  =>
        [
            'match'   => '0CB5 0CBC',
            'replace' => 'E18A',
        ],
    42  =>
        [
            'match'   => '0CB6 0CBC',
            'replace' => 'E18B',
        ],
    43  =>
        [
            'match'   => '0CB7 0CBC',
            'replace' => 'E18C',
        ],
    44  =>
        [
            'match'   => '0CB8 0CBC',
            'replace' => 'E18D',
        ],
    45  =>
        [
            'match'   => '0CB9 0CBC',
            'replace' => 'E18E',
        ],
    46  =>
        [
            'match'   => 'E07D 0CBC',
            'replace' => 'E117',
        ],
    47  =>
        [
            'match'   => 'E07E 0CBC',
            'replace' => 'E118',
        ],
    48  =>
        [
            'match'   => 'E0A3 0CBC',
            'replace' => 'E136',
        ],
    49  =>
        [
            'match'   => 'E0AC 0C95',
            'replace' => 'E02E',
        ],
    50  =>
        [
            'match'   => 'E0AC 0C96',
            'replace' => 'E02F',
        ],
    51  =>
        [
            'match'   => 'E0AC 0C97',
            'replace' => 'E030',
        ],
    52  =>
        [
            'match'   => 'E0AC 0C98',
            'replace' => 'E031',
        ],
    53  =>
        [
            'match'   => 'E0AC 0C99',
            'replace' => 'E032',
        ],
    54  =>
        [
            'match'   => 'E0AC 0C9A',
            'replace' => 'E033',
        ],
    55  =>
        [
            'match'   => 'E0AC 0C9B',
            'replace' => 'E034',
        ],
    56  =>
        [
            'match'   => 'E0AC 0C9C',
            'replace' => 'E035',
        ],
    57  =>
        [
            'match'   => 'E0AC 0C9D',
            'replace' => 'E036',
        ],
    58  =>
        [
            'match'   => 'E0AC 0C9E',
            'replace' => 'E037',
        ],
    59  =>
        [
            'match'   => 'E0AC 0C9F',
            'replace' => 'E038',
        ],
    60  =>
        [
            'match'   => 'E0AC 0CA0',
            'replace' => 'E039',
        ],
    61  =>
        [
            'match'   => 'E0AC 0CA1',
            'replace' => 'E03A',
        ],
    62  =>
        [
            'match'   => 'E0AC 0CA2',
            'replace' => 'E03B',
        ],
    63  =>
        [
            'match'   => 'E0AC 0CA3',
            'replace' => 'E03C',
        ],
    64  =>
        [
            'match'   => 'E0AC 0CA4',
            'replace' => 'E03D',
        ],
    65  =>
        [
            'match'   => 'E0AC 0CA5',
            'replace' => 'E03E',
        ],
    66  =>
        [
            'match'   => 'E0AC 0CA6',
            'replace' => 'E03F',
        ],
    67  =>
        [
            'match'   => 'E0AC 0CA7',
            'replace' => 'E040',
        ],
    68  =>
        [
            'match'   => 'E0AC 0CA8',
            'replace' => 'E041',
        ],
    69  =>
        [
            'match'   => 'E0AC 0CAA',
            'replace' => 'E042',
        ],
    70  =>
        [
            'match'   => 'E0AC 0CAB',
            'replace' => 'E043',
        ],
    71  =>
        [
            'match'   => 'E0AC 0CAC',
            'replace' => 'E044',
        ],
    72  =>
        [
            'match'   => 'E0AC 0CAD',
            'replace' => 'E045',
        ],
    73  =>
        [
            'match'   => 'E0AC 0CAE',
            'replace' => 'E046',
        ],
    74  =>
        [
            'match'   => 'E0AC 0CAF',
            'replace' => 'E047',
        ],
    75  =>
        [
            'match'   => 'E0AC 0CB0',
            'replace' => 'E048',
        ],
    76  =>
        [
            'match'   => 'E0AC 0CB1',
            'replace' => 'E049',
        ],
    77  =>
        [
            'match'   => 'E0AC 0CB2',
            'replace' => 'E04A',
        ],
    78  =>
        [
            'match'   => 'E0AC 0CB3',
            'replace' => 'E04B',
        ],
    79  =>
        [
            'match'   => 'E0AC 0CB5',
            'replace' => 'E04C',
        ],
    80  =>
        [
            'match'   => 'E0AC 0CB6',
            'replace' => 'E04D',
        ],
    81  =>
        [
            'match'   => 'E0AC 0CB7',
            'replace' => 'E04E',
        ],
    82  =>
        [
            'match'   => 'E0AC 0CB8',
            'replace' => 'E04F',
        ],
    83  =>
        [
            'match'   => 'E0AC 0CB9',
            'replace' => 'E050',
        ],
    84  =>
        [
            'match'   => 'E0AC E07D',
            'replace' => 'E081',
        ],
    85  =>
        [
            'match'   => 'E07D E03C',
            'replace' => 'E0A3',
        ],
    86  =>
        [
            'match'   => '((E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E|E04F|E050|E081)) E02E',
            'replace' => '\\1 E052',
        ],
    87  =>
        [
            'match'   => '((E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E|E04F|E050|E081)) E02F',
            'replace' => '\\1 E053',
        ],
    88  =>
        [
            'match'   => '((E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E|E04F|E050|E081)) E030',
            'replace' => '\\1 E054',
        ],
    89  =>
        [
            'match'   => '((E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E|E04F|E050|E081)) E031',
            'replace' => '\\1 E055',
        ],
    90  =>
        [
            'match'   => '((E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E|E04F|E050|E081)) E032',
            'replace' => '\\1 E056',
        ],
    91  =>
        [
            'match'   => '((E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E|E04F|E050|E081)) E033',
            'replace' => '\\1 E057',
        ],
    92  =>
        [
            'match'   => '((E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E|E04F|E050|E081)) E034',
            'replace' => '\\1 E058',
        ],
    93  =>
        [
            'match'   => '((E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E|E04F|E050|E081)) E035',
            'replace' => '\\1 E059',
        ],
    94  =>
        [
            'match'   => '((E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E|E04F|E050|E081)) E036',
            'replace' => '\\1 E05A',
        ],
    95  =>
        [
            'match'   => '((E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E|E04F|E050|E081)) E037',
            'replace' => '\\1 E05B',
        ],
    96  =>
        [
            'match'   => '((E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E|E04F|E050|E081)) E038',
            'replace' => '\\1 E05C',
        ],
    97  =>
        [
            'match'   => '((E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E|E04F|E050|E081)) E039',
            'replace' => '\\1 E05D',
        ],
    98  =>
        [
            'match'   => '((E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E|E04F|E050|E081)) E03A',
            'replace' => '\\1 E05E',
        ],
    99  =>
        [
            'match'   => '((E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E|E04F|E050|E081)) E03B',
            'replace' => '\\1 E05F',
        ],
    100 =>
        [
            'match'   => '((E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E|E04F|E050|E081)) E03C',
            'replace' => '\\1 E060',
        ],
    101 =>
        [
            'match'   => '((E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E|E04F|E050|E081)) E03D',
            'replace' => '\\1 E061',
        ],
    102 =>
        [
            'match'   => '((E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E|E04F|E050|E081)) E03E',
            'replace' => '\\1 E062',
        ],
    103 =>
        [
            'match'   => '((E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E|E04F|E050|E081)) E03F',
            'replace' => '\\1 E063',
        ],
    104 =>
        [
            'match'   => '((E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E|E04F|E050|E081)) E040',
            'replace' => '\\1 E064',
        ],
    105 =>
        [
            'match'   => '((E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E|E04F|E050|E081)) E041',
            'replace' => '\\1 E065',
        ],
    106 =>
        [
            'match'   => '((E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E|E04F|E050|E081)) E042',
            'replace' => '\\1 E066',
        ],
    107 =>
        [
            'match'   => '((E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E|E04F|E050|E081)) E043',
            'replace' => '\\1 E067',
        ],
    108 =>
        [
            'match'   => '((E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E|E04F|E050|E081)) E044',
            'replace' => '\\1 E068',
        ],
    109 =>
        [
            'match'   => '((E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E|E04F|E050|E081)) E045',
            'replace' => '\\1 E069',
        ],
    110 =>
        [
            'match'   => '((E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E|E04F|E050|E081)) E046',
            'replace' => '\\1 E06A',
        ],
    111 =>
        [
            'match'   => '((E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E|E04F|E050|E081)) E047',
            'replace' => '\\1 E06B',
        ],
    112 =>
        [
            'match'   => '((E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E|E04F|E050|E081)) E048',
            'replace' => '\\1 E06C',
        ],
    113 =>
        [
            'match'   => '((E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E|E04F|E050|E081)) E049',
            'replace' => '\\1 E06D',
        ],
    114 =>
        [
            'match'   => '((E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E|E04F|E050|E081)) E04A',
            'replace' => '\\1 E06E',
        ],
    115 =>
        [
            'match'   => '((E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E|E04F|E050|E081)) E04B',
            'replace' => '\\1 E06F',
        ],
    116 =>
        [
            'match'   => '((E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E|E04F|E050|E081)) E04C',
            'replace' => '\\1 E070',
        ],
    117 =>
        [
            'match'   => '((E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E|E04F|E050|E081)) E04D',
            'replace' => '\\1 E071',
        ],
    118 =>
        [
            'match'   => '((E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E|E04F|E050|E081)) E04E',
            'replace' => '\\1 E072',
        ],
    119 =>
        [
            'match'   => '((E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E|E04F|E050|E081)) E04F',
            'replace' => '\\1 E073',
        ],
    120 =>
        [
            'match'   => '((E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E|E04F|E050|E081)) E050',
            'replace' => '\\1 E074',
        ],
    121 =>
        [
            'match'   => '((E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E|E04F|E050|E081)) E081',
            'replace' => '\\1 E081',
        ],
    122 =>
        [
            'match'   => '((E052|E053|E054|E055|E056|E057|E058|E059|E05A|E05B|E05C|E05D|E05E|E05F|E060|E061|E062|E063|E064|E065|E066|E067|E068|E069|E06A|E06B|E06C|E06D|E06E|E06F|E070|E071|E072|E073|E074|E081)) E02E',
            'replace' => '\\1 E052',
        ],
    123 =>
        [
            'match'   => '((E052|E053|E054|E055|E056|E057|E058|E059|E05A|E05B|E05C|E05D|E05E|E05F|E060|E061|E062|E063|E064|E065|E066|E067|E068|E069|E06A|E06B|E06C|E06D|E06E|E06F|E070|E071|E072|E073|E074|E081)) E02F',
            'replace' => '\\1 E053',
        ],
    124 =>
        [
            'match'   => '((E052|E053|E054|E055|E056|E057|E058|E059|E05A|E05B|E05C|E05D|E05E|E05F|E060|E061|E062|E063|E064|E065|E066|E067|E068|E069|E06A|E06B|E06C|E06D|E06E|E06F|E070|E071|E072|E073|E074|E081)) E030',
            'replace' => '\\1 E054',
        ],
    125 =>
        [
            'match'   => '((E052|E053|E054|E055|E056|E057|E058|E059|E05A|E05B|E05C|E05D|E05E|E05F|E060|E061|E062|E063|E064|E065|E066|E067|E068|E069|E06A|E06B|E06C|E06D|E06E|E06F|E070|E071|E072|E073|E074|E081)) E031',
            'replace' => '\\1 E055',
        ],
    126 =>
        [
            'match'   => '((E052|E053|E054|E055|E056|E057|E058|E059|E05A|E05B|E05C|E05D|E05E|E05F|E060|E061|E062|E063|E064|E065|E066|E067|E068|E069|E06A|E06B|E06C|E06D|E06E|E06F|E070|E071|E072|E073|E074|E081)) E032',
            'replace' => '\\1 E056',
        ],
    127 =>
        [
            'match'   => '((E052|E053|E054|E055|E056|E057|E058|E059|E05A|E05B|E05C|E05D|E05E|E05F|E060|E061|E062|E063|E064|E065|E066|E067|E068|E069|E06A|E06B|E06C|E06D|E06E|E06F|E070|E071|E072|E073|E074|E081)) E033',
            'replace' => '\\1 E057',
        ],
    128 =>
        [
            'match'   => '((E052|E053|E054|E055|E056|E057|E058|E059|E05A|E05B|E05C|E05D|E05E|E05F|E060|E061|E062|E063|E064|E065|E066|E067|E068|E069|E06A|E06B|E06C|E06D|E06E|E06F|E070|E071|E072|E073|E074|E081)) E034',
            'replace' => '\\1 E058',
        ],
    129 =>
        [
            'match'   => '((E052|E053|E054|E055|E056|E057|E058|E059|E05A|E05B|E05C|E05D|E05E|E05F|E060|E061|E062|E063|E064|E065|E066|E067|E068|E069|E06A|E06B|E06C|E06D|E06E|E06F|E070|E071|E072|E073|E074|E081)) E035',
            'replace' => '\\1 E059',
        ],
    130 =>
        [
            'match'   => '((E052|E053|E054|E055|E056|E057|E058|E059|E05A|E05B|E05C|E05D|E05E|E05F|E060|E061|E062|E063|E064|E065|E066|E067|E068|E069|E06A|E06B|E06C|E06D|E06E|E06F|E070|E071|E072|E073|E074|E081)) E036',
            'replace' => '\\1 E05A',
        ],
    131 =>
        [
            'match'   => '((E052|E053|E054|E055|E056|E057|E058|E059|E05A|E05B|E05C|E05D|E05E|E05F|E060|E061|E062|E063|E064|E065|E066|E067|E068|E069|E06A|E06B|E06C|E06D|E06E|E06F|E070|E071|E072|E073|E074|E081)) E037',
            'replace' => '\\1 E05B',
        ],
    132 =>
        [
            'match'   => '((E052|E053|E054|E055|E056|E057|E058|E059|E05A|E05B|E05C|E05D|E05E|E05F|E060|E061|E062|E063|E064|E065|E066|E067|E068|E069|E06A|E06B|E06C|E06D|E06E|E06F|E070|E071|E072|E073|E074|E081)) E038',
            'replace' => '\\1 E05C',
        ],
    133 =>
        [
            'match'   => '((E052|E053|E054|E055|E056|E057|E058|E059|E05A|E05B|E05C|E05D|E05E|E05F|E060|E061|E062|E063|E064|E065|E066|E067|E068|E069|E06A|E06B|E06C|E06D|E06E|E06F|E070|E071|E072|E073|E074|E081)) E039',
            'replace' => '\\1 E05D',
        ],
    134 =>
        [
            'match'   => '((E052|E053|E054|E055|E056|E057|E058|E059|E05A|E05B|E05C|E05D|E05E|E05F|E060|E061|E062|E063|E064|E065|E066|E067|E068|E069|E06A|E06B|E06C|E06D|E06E|E06F|E070|E071|E072|E073|E074|E081)) E03A',
            'replace' => '\\1 E05E',
        ],
    135 =>
        [
            'match'   => '((E052|E053|E054|E055|E056|E057|E058|E059|E05A|E05B|E05C|E05D|E05E|E05F|E060|E061|E062|E063|E064|E065|E066|E067|E068|E069|E06A|E06B|E06C|E06D|E06E|E06F|E070|E071|E072|E073|E074|E081)) E03B',
            'replace' => '\\1 E05F',
        ],
    136 =>
        [
            'match'   => '((E052|E053|E054|E055|E056|E057|E058|E059|E05A|E05B|E05C|E05D|E05E|E05F|E060|E061|E062|E063|E064|E065|E066|E067|E068|E069|E06A|E06B|E06C|E06D|E06E|E06F|E070|E071|E072|E073|E074|E081)) E03C',
            'replace' => '\\1 E060',
        ],
    137 =>
        [
            'match'   => '((E052|E053|E054|E055|E056|E057|E058|E059|E05A|E05B|E05C|E05D|E05E|E05F|E060|E061|E062|E063|E064|E065|E066|E067|E068|E069|E06A|E06B|E06C|E06D|E06E|E06F|E070|E071|E072|E073|E074|E081)) E03D',
            'replace' => '\\1 E061',
        ],
    138 =>
        [
            'match'   => '((E052|E053|E054|E055|E056|E057|E058|E059|E05A|E05B|E05C|E05D|E05E|E05F|E060|E061|E062|E063|E064|E065|E066|E067|E068|E069|E06A|E06B|E06C|E06D|E06E|E06F|E070|E071|E072|E073|E074|E081)) E03E',
            'replace' => '\\1 E062',
        ],
    139 =>
        [
            'match'   => '((E052|E053|E054|E055|E056|E057|E058|E059|E05A|E05B|E05C|E05D|E05E|E05F|E060|E061|E062|E063|E064|E065|E066|E067|E068|E069|E06A|E06B|E06C|E06D|E06E|E06F|E070|E071|E072|E073|E074|E081)) E03F',
            'replace' => '\\1 E063',
        ],
    140 =>
        [
            'match'   => '((E052|E053|E054|E055|E056|E057|E058|E059|E05A|E05B|E05C|E05D|E05E|E05F|E060|E061|E062|E063|E064|E065|E066|E067|E068|E069|E06A|E06B|E06C|E06D|E06E|E06F|E070|E071|E072|E073|E074|E081)) E040',
            'replace' => '\\1 E064',
        ],
    141 =>
        [
            'match'   => '((E052|E053|E054|E055|E056|E057|E058|E059|E05A|E05B|E05C|E05D|E05E|E05F|E060|E061|E062|E063|E064|E065|E066|E067|E068|E069|E06A|E06B|E06C|E06D|E06E|E06F|E070|E071|E072|E073|E074|E081)) E041',
            'replace' => '\\1 E065',
        ],
    142 =>
        [
            'match'   => '((E052|E053|E054|E055|E056|E057|E058|E059|E05A|E05B|E05C|E05D|E05E|E05F|E060|E061|E062|E063|E064|E065|E066|E067|E068|E069|E06A|E06B|E06C|E06D|E06E|E06F|E070|E071|E072|E073|E074|E081)) E042',
            'replace' => '\\1 E066',
        ],
    143 =>
        [
            'match'   => '((E052|E053|E054|E055|E056|E057|E058|E059|E05A|E05B|E05C|E05D|E05E|E05F|E060|E061|E062|E063|E064|E065|E066|E067|E068|E069|E06A|E06B|E06C|E06D|E06E|E06F|E070|E071|E072|E073|E074|E081)) E043',
            'replace' => '\\1 E067',
        ],
    144 =>
        [
            'match'   => '((E052|E053|E054|E055|E056|E057|E058|E059|E05A|E05B|E05C|E05D|E05E|E05F|E060|E061|E062|E063|E064|E065|E066|E067|E068|E069|E06A|E06B|E06C|E06D|E06E|E06F|E070|E071|E072|E073|E074|E081)) E044',
            'replace' => '\\1 E068',
        ],
    145 =>
        [
            'match'   => '((E052|E053|E054|E055|E056|E057|E058|E059|E05A|E05B|E05C|E05D|E05E|E05F|E060|E061|E062|E063|E064|E065|E066|E067|E068|E069|E06A|E06B|E06C|E06D|E06E|E06F|E070|E071|E072|E073|E074|E081)) E045',
            'replace' => '\\1 E069',
        ],
    146 =>
        [
            'match'   => '((E052|E053|E054|E055|E056|E057|E058|E059|E05A|E05B|E05C|E05D|E05E|E05F|E060|E061|E062|E063|E064|E065|E066|E067|E068|E069|E06A|E06B|E06C|E06D|E06E|E06F|E070|E071|E072|E073|E074|E081)) E046',
            'replace' => '\\1 E06A',
        ],
    147 =>
        [
            'match'   => '((E052|E053|E054|E055|E056|E057|E058|E059|E05A|E05B|E05C|E05D|E05E|E05F|E060|E061|E062|E063|E064|E065|E066|E067|E068|E069|E06A|E06B|E06C|E06D|E06E|E06F|E070|E071|E072|E073|E074|E081)) E047',
            'replace' => '\\1 E06B',
        ],
    148 =>
        [
            'match'   => '((E052|E053|E054|E055|E056|E057|E058|E059|E05A|E05B|E05C|E05D|E05E|E05F|E060|E061|E062|E063|E064|E065|E066|E067|E068|E069|E06A|E06B|E06C|E06D|E06E|E06F|E070|E071|E072|E073|E074|E081)) E048',
            'replace' => '\\1 E06C',
        ],
    149 =>
        [
            'match'   => '((E052|E053|E054|E055|E056|E057|E058|E059|E05A|E05B|E05C|E05D|E05E|E05F|E060|E061|E062|E063|E064|E065|E066|E067|E068|E069|E06A|E06B|E06C|E06D|E06E|E06F|E070|E071|E072|E073|E074|E081)) E049',
            'replace' => '\\1 E06D',
        ],
    150 =>
        [
            'match'   => '((E052|E053|E054|E055|E056|E057|E058|E059|E05A|E05B|E05C|E05D|E05E|E05F|E060|E061|E062|E063|E064|E065|E066|E067|E068|E069|E06A|E06B|E06C|E06D|E06E|E06F|E070|E071|E072|E073|E074|E081)) E04A',
            'replace' => '\\1 E06E',
        ],
    151 =>
        [
            'match'   => '((E052|E053|E054|E055|E056|E057|E058|E059|E05A|E05B|E05C|E05D|E05E|E05F|E060|E061|E062|E063|E064|E065|E066|E067|E068|E069|E06A|E06B|E06C|E06D|E06E|E06F|E070|E071|E072|E073|E074|E081)) E04B',
            'replace' => '\\1 E06F',
        ],
    152 =>
        [
            'match'   => '((E052|E053|E054|E055|E056|E057|E058|E059|E05A|E05B|E05C|E05D|E05E|E05F|E060|E061|E062|E063|E064|E065|E066|E067|E068|E069|E06A|E06B|E06C|E06D|E06E|E06F|E070|E071|E072|E073|E074|E081)) E04C',
            'replace' => '\\1 E070',
        ],
    153 =>
        [
            'match'   => '((E052|E053|E054|E055|E056|E057|E058|E059|E05A|E05B|E05C|E05D|E05E|E05F|E060|E061|E062|E063|E064|E065|E066|E067|E068|E069|E06A|E06B|E06C|E06D|E06E|E06F|E070|E071|E072|E073|E074|E081)) E04D',
            'replace' => '\\1 E071',
        ],
    154 =>
        [
            'match'   => '((E052|E053|E054|E055|E056|E057|E058|E059|E05A|E05B|E05C|E05D|E05E|E05F|E060|E061|E062|E063|E064|E065|E066|E067|E068|E069|E06A|E06B|E06C|E06D|E06E|E06F|E070|E071|E072|E073|E074|E081)) E04E',
            'replace' => '\\1 E072',
        ],
    155 =>
        [
            'match'   => '((E052|E053|E054|E055|E056|E057|E058|E059|E05A|E05B|E05C|E05D|E05E|E05F|E060|E061|E062|E063|E064|E065|E066|E067|E068|E069|E06A|E06B|E06C|E06D|E06E|E06F|E070|E071|E072|E073|E074|E081)) E04F',
            'replace' => '\\1 E073',
        ],
    156 =>
        [
            'match'   => '((E052|E053|E054|E055|E056|E057|E058|E059|E05A|E05B|E05C|E05D|E05E|E05F|E060|E061|E062|E063|E064|E065|E066|E067|E068|E069|E06A|E06B|E06C|E06D|E06E|E06F|E070|E071|E072|E073|E074|E081)) E050',
            'replace' => '\\1 E074',
        ],
    157 =>
        [
            'match'   => '((E052|E053|E054|E055|E056|E057|E058|E059|E05A|E05B|E05C|E05D|E05E|E05F|E060|E061|E062|E063|E064|E065|E066|E067|E068|E069|E06A|E06B|E06C|E06D|E06E|E06F|E070|E071|E072|E073|E074|E081)) E081',
            'replace' => '\\1 E081',
        ],
    158 =>
        [
            'match'   => '(E07D) E02E',
            'replace' => '\\1 E052',
        ],
    159 =>
        [
            'match'   => '(E07D) E02F',
            'replace' => '\\1 E053',
        ],
    160 =>
        [
            'match'   => '(E07D) E030',
            'replace' => '\\1 E054',
        ],
    161 =>
        [
            'match'   => '(E07D) E031',
            'replace' => '\\1 E055',
        ],
    162 =>
        [
            'match'   => '(E07D) E032',
            'replace' => '\\1 E056',
        ],
    163 =>
        [
            'match'   => '(E07D) E033',
            'replace' => '\\1 E057',
        ],
    164 =>
        [
            'match'   => '(E07D) E034',
            'replace' => '\\1 E058',
        ],
    165 =>
        [
            'match'   => '(E07D) E035',
            'replace' => '\\1 E059',
        ],
    166 =>
        [
            'match'   => '(E07D) E036',
            'replace' => '\\1 E05A',
        ],
    167 =>
        [
            'match'   => '(E07D) E037',
            'replace' => '\\1 E05B',
        ],
    168 =>
        [
            'match'   => '(E07D) E038',
            'replace' => '\\1 E05C',
        ],
    169 =>
        [
            'match'   => '(E07D) E039',
            'replace' => '\\1 E05D',
        ],
    170 =>
        [
            'match'   => '(E07D) E03A',
            'replace' => '\\1 E05E',
        ],
    171 =>
        [
            'match'   => '(E07D) E03B',
            'replace' => '\\1 E05F',
        ],
    172 =>
        [
            'match'   => '(E07D) E03C',
            'replace' => '\\1 E060',
        ],
    173 =>
        [
            'match'   => '(E07D) E03D',
            'replace' => '\\1 E061',
        ],
    174 =>
        [
            'match'   => '(E07D) E03E',
            'replace' => '\\1 E062',
        ],
    175 =>
        [
            'match'   => '(E07D) E03F',
            'replace' => '\\1 E063',
        ],
    176 =>
        [
            'match'   => '(E07D) E040',
            'replace' => '\\1 E064',
        ],
    177 =>
        [
            'match'   => '(E07D) E041',
            'replace' => '\\1 E065',
        ],
    178 =>
        [
            'match'   => '(E07D) E042',
            'replace' => '\\1 E066',
        ],
    179 =>
        [
            'match'   => '(E07D) E043',
            'replace' => '\\1 E067',
        ],
    180 =>
        [
            'match'   => '(E07D) E044',
            'replace' => '\\1 E068',
        ],
    181 =>
        [
            'match'   => '(E07D) E045',
            'replace' => '\\1 E069',
        ],
    182 =>
        [
            'match'   => '(E07D) E046',
            'replace' => '\\1 E06A',
        ],
    183 =>
        [
            'match'   => '(E07D) E047',
            'replace' => '\\1 E06B',
        ],
    184 =>
        [
            'match'   => '(E07D) E048',
            'replace' => '\\1 E06C',
        ],
    185 =>
        [
            'match'   => '(E07D) E049',
            'replace' => '\\1 E06D',
        ],
    186 =>
        [
            'match'   => '(E07D) E04A',
            'replace' => '\\1 E06E',
        ],
    187 =>
        [
            'match'   => '(E07D) E04B',
            'replace' => '\\1 E06F',
        ],
    188 =>
        [
            'match'   => '(E07D) E04C',
            'replace' => '\\1 E070',
        ],
    189 =>
        [
            'match'   => '(E07D) E04D',
            'replace' => '\\1 E071',
        ],
    190 =>
        [
            'match'   => '(E07D) E04E',
            'replace' => '\\1 E072',
        ],
    191 =>
        [
            'match'   => '(E07D) E04F',
            'replace' => '\\1 E073',
        ],
    192 =>
        [
            'match'   => '(E07D) E050',
            'replace' => '\\1 E074',
        ],
    193 =>
        [
            'match'   => '(E07D) E081',
            'replace' => '\\1 E081',
        ],
    194 =>
        [
            'match'   => '(E07D (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E02E',
            'replace' => '\\1 E052',
        ],
    195 =>
        [
            'match'   => '(E07D (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E02F',
            'replace' => '\\1 E053',
        ],
    196 =>
        [
            'match'   => '(E07D (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E030',
            'replace' => '\\1 E054',
        ],
    197 =>
        [
            'match'   => '(E07D (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E031',
            'replace' => '\\1 E055',
        ],
    198 =>
        [
            'match'   => '(E07D (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E032',
            'replace' => '\\1 E056',
        ],
    199 =>
        [
            'match'   => '(E07D (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E033',
            'replace' => '\\1 E057',
        ],
    200 =>
        [
            'match'   => '(E07D (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E034',
            'replace' => '\\1 E058',
        ],
    201 =>
        [
            'match'   => '(E07D (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E035',
            'replace' => '\\1 E059',
        ],
    202 =>
        [
            'match'   => '(E07D (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E036',
            'replace' => '\\1 E05A',
        ],
    203 =>
        [
            'match'   => '(E07D (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E037',
            'replace' => '\\1 E05B',
        ],
    204 =>
        [
            'match'   => '(E07D (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E038',
            'replace' => '\\1 E05C',
        ],
    205 =>
        [
            'match'   => '(E07D (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E039',
            'replace' => '\\1 E05D',
        ],
    206 =>
        [
            'match'   => '(E07D (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E03A',
            'replace' => '\\1 E05E',
        ],
    207 =>
        [
            'match'   => '(E07D (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E03B',
            'replace' => '\\1 E05F',
        ],
    208 =>
        [
            'match'   => '(E07D (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E03C',
            'replace' => '\\1 E060',
        ],
    209 =>
        [
            'match'   => '(E07D (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E03D',
            'replace' => '\\1 E061',
        ],
    210 =>
        [
            'match'   => '(E07D (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E03E',
            'replace' => '\\1 E062',
        ],
    211 =>
        [
            'match'   => '(E07D (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E03F',
            'replace' => '\\1 E063',
        ],
    212 =>
        [
            'match'   => '(E07D (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E040',
            'replace' => '\\1 E064',
        ],
    213 =>
        [
            'match'   => '(E07D (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E041',
            'replace' => '\\1 E065',
        ],
    214 =>
        [
            'match'   => '(E07D (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E042',
            'replace' => '\\1 E066',
        ],
    215 =>
        [
            'match'   => '(E07D (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E043',
            'replace' => '\\1 E067',
        ],
    216 =>
        [
            'match'   => '(E07D (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E044',
            'replace' => '\\1 E068',
        ],
    217 =>
        [
            'match'   => '(E07D (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E045',
            'replace' => '\\1 E069',
        ],
    218 =>
        [
            'match'   => '(E07D (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E046',
            'replace' => '\\1 E06A',
        ],
    219 =>
        [
            'match'   => '(E07D (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E047',
            'replace' => '\\1 E06B',
        ],
    220 =>
        [
            'match'   => '(E07D (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E048',
            'replace' => '\\1 E06C',
        ],
    221 =>
        [
            'match'   => '(E07D (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E049',
            'replace' => '\\1 E06D',
        ],
    222 =>
        [
            'match'   => '(E07D (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E04A',
            'replace' => '\\1 E06E',
        ],
    223 =>
        [
            'match'   => '(E07D (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E04B',
            'replace' => '\\1 E06F',
        ],
    224 =>
        [
            'match'   => '(E07D (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E04C',
            'replace' => '\\1 E070',
        ],
    225 =>
        [
            'match'   => '(E07D (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E04D',
            'replace' => '\\1 E071',
        ],
    226 =>
        [
            'match'   => '(E07D (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E04E',
            'replace' => '\\1 E072',
        ],
    227 =>
        [
            'match'   => '(E07D (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E04F',
            'replace' => '\\1 E073',
        ],
    228 =>
        [
            'match'   => '(E07D (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E050',
            'replace' => '\\1 E074',
        ],
    229 =>
        [
            'match'   => '(E07D (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E081',
            'replace' => '\\1 E081',
        ],
    230 =>
        [
            'match'   => '(E07E) E02E',
            'replace' => '\\1 E052',
        ],
    231 =>
        [
            'match'   => '(E07E) E02F',
            'replace' => '\\1 E053',
        ],
    232 =>
        [
            'match'   => '(E07E) E030',
            'replace' => '\\1 E054',
        ],
    233 =>
        [
            'match'   => '(E07E) E031',
            'replace' => '\\1 E055',
        ],
    234 =>
        [
            'match'   => '(E07E) E032',
            'replace' => '\\1 E056',
        ],
    235 =>
        [
            'match'   => '(E07E) E033',
            'replace' => '\\1 E057',
        ],
    236 =>
        [
            'match'   => '(E07E) E034',
            'replace' => '\\1 E058',
        ],
    237 =>
        [
            'match'   => '(E07E) E035',
            'replace' => '\\1 E059',
        ],
    238 =>
        [
            'match'   => '(E07E) E036',
            'replace' => '\\1 E05A',
        ],
    239 =>
        [
            'match'   => '(E07E) E037',
            'replace' => '\\1 E05B',
        ],
    240 =>
        [
            'match'   => '(E07E) E038',
            'replace' => '\\1 E05C',
        ],
    241 =>
        [
            'match'   => '(E07E) E039',
            'replace' => '\\1 E05D',
        ],
    242 =>
        [
            'match'   => '(E07E) E03A',
            'replace' => '\\1 E05E',
        ],
    243 =>
        [
            'match'   => '(E07E) E03B',
            'replace' => '\\1 E05F',
        ],
    244 =>
        [
            'match'   => '(E07E) E03C',
            'replace' => '\\1 E060',
        ],
    245 =>
        [
            'match'   => '(E07E) E03D',
            'replace' => '\\1 E061',
        ],
    246 =>
        [
            'match'   => '(E07E) E03E',
            'replace' => '\\1 E062',
        ],
    247 =>
        [
            'match'   => '(E07E) E03F',
            'replace' => '\\1 E063',
        ],
    248 =>
        [
            'match'   => '(E07E) E040',
            'replace' => '\\1 E064',
        ],
    249 =>
        [
            'match'   => '(E07E) E041',
            'replace' => '\\1 E065',
        ],
    250 =>
        [
            'match'   => '(E07E) E042',
            'replace' => '\\1 E066',
        ],
    251 =>
        [
            'match'   => '(E07E) E043',
            'replace' => '\\1 E067',
        ],
    252 =>
        [
            'match'   => '(E07E) E044',
            'replace' => '\\1 E068',
        ],
    253 =>
        [
            'match'   => '(E07E) E045',
            'replace' => '\\1 E069',
        ],
    254 =>
        [
            'match'   => '(E07E) E046',
            'replace' => '\\1 E06A',
        ],
    255 =>
        [
            'match'   => '(E07E) E047',
            'replace' => '\\1 E06B',
        ],
    256 =>
        [
            'match'   => '(E07E) E048',
            'replace' => '\\1 E06C',
        ],
    257 =>
        [
            'match'   => '(E07E) E049',
            'replace' => '\\1 E06D',
        ],
    258 =>
        [
            'match'   => '(E07E) E04A',
            'replace' => '\\1 E06E',
        ],
    259 =>
        [
            'match'   => '(E07E) E04B',
            'replace' => '\\1 E06F',
        ],
    260 =>
        [
            'match'   => '(E07E) E04C',
            'replace' => '\\1 E070',
        ],
    261 =>
        [
            'match'   => '(E07E) E04D',
            'replace' => '\\1 E071',
        ],
    262 =>
        [
            'match'   => '(E07E) E04E',
            'replace' => '\\1 E072',
        ],
    263 =>
        [
            'match'   => '(E07E) E04F',
            'replace' => '\\1 E073',
        ],
    264 =>
        [
            'match'   => '(E07E) E050',
            'replace' => '\\1 E074',
        ],
    265 =>
        [
            'match'   => '(E07E) E081',
            'replace' => '\\1 E081',
        ],
    266 =>
        [
            'match'   => '(E07E (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E02E',
            'replace' => '\\1 E052',
        ],
    267 =>
        [
            'match'   => '(E07E (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E02F',
            'replace' => '\\1 E053',
        ],
    268 =>
        [
            'match'   => '(E07E (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E030',
            'replace' => '\\1 E054',
        ],
    269 =>
        [
            'match'   => '(E07E (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E031',
            'replace' => '\\1 E055',
        ],
    270 =>
        [
            'match'   => '(E07E (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E032',
            'replace' => '\\1 E056',
        ],
    271 =>
        [
            'match'   => '(E07E (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E033',
            'replace' => '\\1 E057',
        ],
    272 =>
        [
            'match'   => '(E07E (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E034',
            'replace' => '\\1 E058',
        ],
    273 =>
        [
            'match'   => '(E07E (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E035',
            'replace' => '\\1 E059',
        ],
    274 =>
        [
            'match'   => '(E07E (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E036',
            'replace' => '\\1 E05A',
        ],
    275 =>
        [
            'match'   => '(E07E (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E037',
            'replace' => '\\1 E05B',
        ],
    276 =>
        [
            'match'   => '(E07E (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E038',
            'replace' => '\\1 E05C',
        ],
    277 =>
        [
            'match'   => '(E07E (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E039',
            'replace' => '\\1 E05D',
        ],
    278 =>
        [
            'match'   => '(E07E (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E03A',
            'replace' => '\\1 E05E',
        ],
    279 =>
        [
            'match'   => '(E07E (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E03B',
            'replace' => '\\1 E05F',
        ],
    280 =>
        [
            'match'   => '(E07E (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E03C',
            'replace' => '\\1 E060',
        ],
    281 =>
        [
            'match'   => '(E07E (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E03D',
            'replace' => '\\1 E061',
        ],
    282 =>
        [
            'match'   => '(E07E (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E03E',
            'replace' => '\\1 E062',
        ],
    283 =>
        [
            'match'   => '(E07E (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E03F',
            'replace' => '\\1 E063',
        ],
    284 =>
        [
            'match'   => '(E07E (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E040',
            'replace' => '\\1 E064',
        ],
    285 =>
        [
            'match'   => '(E07E (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E041',
            'replace' => '\\1 E065',
        ],
    286 =>
        [
            'match'   => '(E07E (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E042',
            'replace' => '\\1 E066',
        ],
    287 =>
        [
            'match'   => '(E07E (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E043',
            'replace' => '\\1 E067',
        ],
    288 =>
        [
            'match'   => '(E07E (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E044',
            'replace' => '\\1 E068',
        ],
    289 =>
        [
            'match'   => '(E07E (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E045',
            'replace' => '\\1 E069',
        ],
    290 =>
        [
            'match'   => '(E07E (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E046',
            'replace' => '\\1 E06A',
        ],
    291 =>
        [
            'match'   => '(E07E (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E047',
            'replace' => '\\1 E06B',
        ],
    292 =>
        [
            'match'   => '(E07E (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E048',
            'replace' => '\\1 E06C',
        ],
    293 =>
        [
            'match'   => '(E07E (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E049',
            'replace' => '\\1 E06D',
        ],
    294 =>
        [
            'match'   => '(E07E (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E04A',
            'replace' => '\\1 E06E',
        ],
    295 =>
        [
            'match'   => '(E07E (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E04B',
            'replace' => '\\1 E06F',
        ],
    296 =>
        [
            'match'   => '(E07E (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E04C',
            'replace' => '\\1 E070',
        ],
    297 =>
        [
            'match'   => '(E07E (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E04D',
            'replace' => '\\1 E071',
        ],
    298 =>
        [
            'match'   => '(E07E (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E04E',
            'replace' => '\\1 E072',
        ],
    299 =>
        [
            'match'   => '(E07E (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E04F',
            'replace' => '\\1 E073',
        ],
    300 =>
        [
            'match'   => '(E07E (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E050',
            'replace' => '\\1 E074',
        ],
    301 =>
        [
            'match'   => '(E07E (0CBE|0CBF|0CC6|0CC1|0CC2|0CCC|0CCA)) E081',
            'replace' => '\\1 E081',
        ],
    302 =>
        [
            'match'   => '((E052|E053|E054|E055|E056|E057|E058|E059|E05A|E05B|E05C|E05D|E05E|E05F|E060|E061|E062|E063|E064|E065|E066|E067|E068|E069|E06A|E06B|E06C|E06D|E06E|E06F|E070|E071|E072|E073|E074|E081)) E03D',
            'replace' => '\\1 E076',
        ],
    303 =>
        [
            'match'   => '((E052|E053|E054|E055|E056|E057|E058|E059|E05A|E05B|E05C|E05D|E05E|E05F|E060|E061|E062|E063|E064|E065|E066|E067|E068|E069|E06A|E06B|E06C|E06D|E06E|E06F|E070|E071|E072|E073|E074|E081)) E041',
            'replace' => '\\1 E077',
        ],
    304 =>
        [
            'match'   => '((E052|E053|E054|E055|E056|E057|E058|E059|E05A|E05B|E05C|E05D|E05E|E05F|E060|E061|E062|E063|E064|E065|E066|E067|E068|E069|E06A|E06B|E06C|E06D|E06E|E06F|E070|E071|E072|E073|E074|E081)) E046',
            'replace' => '\\1 E078',
        ],
    305 =>
        [
            'match'   => '((E052|E053|E054|E055|E056|E057|E058|E059|E05A|E05B|E05C|E05D|E05E|E05F|E060|E061|E062|E063|E064|E065|E066|E067|E068|E069|E06A|E06B|E06C|E06D|E06E|E06F|E070|E071|E072|E073|E074|E081)) E047',
            'replace' => '\\1 E079',
        ],
    306 =>
        [
            'match'   => '((E052|E053|E054|E055|E056|E057|E058|E059|E05A|E05B|E05C|E05D|E05E|E05F|E060|E061|E062|E063|E064|E065|E066|E067|E068|E069|E06A|E06B|E06C|E06D|E06E|E06F|E070|E071|E072|E073|E074|E081)) E048',
            'replace' => '\\1 E07A',
        ],
    307 =>
        [
            'match'   => '((E052|E053|E054|E055|E056|E057|E058|E059|E05A|E05B|E05C|E05D|E05E|E05F|E060|E061|E062|E063|E064|E065|E066|E067|E068|E069|E06A|E06B|E06C|E06D|E06E|E06F|E070|E071|E072|E073|E074|E081)) E04A',
            'replace' => '\\1 E07B',
        ],
    308 =>
        [
            'match'   => '((E052|E053|E054|E055|E056|E057|E058|E059|E05A|E05B|E05C|E05D|E05E|E05F|E060|E061|E062|E063|E064|E065|E066|E067|E068|E069|E06A|E06B|E06C|E06D|E06E|E06F|E070|E071|E072|E073|E074|E081)) E04E',
            'replace' => '\\1 E07C',
        ],
    309 =>
        [
            'match'   => '((E0E6|E0E7|E172|E173|E174|E175|E176|E0E8|E0E9|E177|E178|E179|E17A|E17B|E17C|E17D|E17E|E17F|E180|E181|E182|E0EA|E183|E184|E185|E186|E0EB|E187|E188|E189|E18A|E18B|E18C|E18D|E18E|E0F2|E0F3|E11B|E11C|E11D|E11E|E0F4|E0F5|E11F|E120|E121|E122|E123|E124|E125|E126|E127|E128|E0F9|E129|E12A|E12B|E12C|E0FD|E12D|E12E|E12F|E130|E131|E132|E133)) E030',
            'replace' => '\\1 E104',
        ],
    310 =>
        [
            'match'   => '((E0E6|E0E7|E172|E173|E174|E175|E176|E0E8|E0E9|E177|E178|E179|E17A|E17B|E17C|E17D|E17E|E17F|E180|E181|E182|E0EA|E183|E184|E185|E186|E0EB|E187|E188|E189|E18A|E18B|E18C|E18D|E18E|E0F2|E0F3|E11B|E11C|E11D|E11E|E0F4|E0F5|E11F|E120|E121|E122|E123|E124|E125|E126|E127|E128|E0F9|E129|E12A|E12B|E12C|E0FD|E12D|E12E|E12F|E130|E131|E132|E133)) E032',
            'replace' => '\\1 E105',
        ],
    311 =>
        [
            'match'   => '((E0E6|E0E7|E172|E173|E174|E175|E176|E0E8|E0E9|E177|E178|E179|E17A|E17B|E17C|E17D|E17E|E17F|E180|E181|E182|E0EA|E183|E184|E185|E186|E0EB|E187|E188|E189|E18A|E18B|E18C|E18D|E18E|E0F2|E0F3|E11B|E11C|E11D|E11E|E0F4|E0F5|E11F|E120|E121|E122|E123|E124|E125|E126|E127|E128|E0F9|E129|E12A|E12B|E12C|E0FD|E12D|E12E|E12F|E130|E131|E132|E133)) E034',
            'replace' => '\\1 E106',
        ],
    312 =>
        [
            'match'   => '((E0E6|E0E7|E172|E173|E174|E175|E176|E0E8|E0E9|E177|E178|E179|E17A|E17B|E17C|E17D|E17E|E17F|E180|E181|E182|E0EA|E183|E184|E185|E186|E0EB|E187|E188|E189|E18A|E18B|E18C|E18D|E18E|E0F2|E0F3|E11B|E11C|E11D|E11E|E0F4|E0F5|E11F|E120|E121|E122|E123|E124|E125|E126|E127|E128|E0F9|E129|E12A|E12B|E12C|E0FD|E12D|E12E|E12F|E130|E131|E132|E133)) E035',
            'replace' => '\\1 E107',
        ],
    313 =>
        [
            'match'   => '((E0E6|E0E7|E172|E173|E174|E175|E176|E0E8|E0E9|E177|E178|E179|E17A|E17B|E17C|E17D|E17E|E17F|E180|E181|E182|E0EA|E183|E184|E185|E186|E0EB|E187|E188|E189|E18A|E18B|E18C|E18D|E18E|E0F2|E0F3|E11B|E11C|E11D|E11E|E0F4|E0F5|E11F|E120|E121|E122|E123|E124|E125|E126|E127|E128|E0F9|E129|E12A|E12B|E12C|E0FD|E12D|E12E|E12F|E130|E131|E132|E133)) E036',
            'replace' => '\\1 E108',
        ],
    314 =>
        [
            'match'   => '((E0E6|E0E7|E172|E173|E174|E175|E176|E0E8|E0E9|E177|E178|E179|E17A|E17B|E17C|E17D|E17E|E17F|E180|E181|E182|E0EA|E183|E184|E185|E186|E0EB|E187|E188|E189|E18A|E18B|E18C|E18D|E18E|E0F2|E0F3|E11B|E11C|E11D|E11E|E0F4|E0F5|E11F|E120|E121|E122|E123|E124|E125|E126|E127|E128|E0F9|E129|E12A|E12B|E12C|E0FD|E12D|E12E|E12F|E130|E131|E132|E133)) E037',
            'replace' => '\\1 E109',
        ],
    315 =>
        [
            'match'   => '((E0E6|E0E7|E172|E173|E174|E175|E176|E0E8|E0E9|E177|E178|E179|E17A|E17B|E17C|E17D|E17E|E17F|E180|E181|E182|E0EA|E183|E184|E185|E186|E0EB|E187|E188|E189|E18A|E18B|E18C|E18D|E18E|E0F2|E0F3|E11B|E11C|E11D|E11E|E0F4|E0F5|E11F|E120|E121|E122|E123|E124|E125|E126|E127|E128|E0F9|E129|E12A|E12B|E12C|E0FD|E12D|E12E|E12F|E130|E131|E132|E133)) E039',
            'replace' => '\\1 E10A',
        ],
    316 =>
        [
            'match'   => '((E0E6|E0E7|E172|E173|E174|E175|E176|E0E8|E0E9|E177|E178|E179|E17A|E17B|E17C|E17D|E17E|E17F|E180|E181|E182|E0EA|E183|E184|E185|E186|E0EB|E187|E188|E189|E18A|E18B|E18C|E18D|E18E|E0F2|E0F3|E11B|E11C|E11D|E11E|E0F4|E0F5|E11F|E120|E121|E122|E123|E124|E125|E126|E127|E128|E0F9|E129|E12A|E12B|E12C|E0FD|E12D|E12E|E12F|E130|E131|E132|E133)) E03A',
            'replace' => '\\1 E10B',
        ],
    317 =>
        [
            'match'   => '((E0E6|E0E7|E172|E173|E174|E175|E176|E0E8|E0E9|E177|E178|E179|E17A|E17B|E17C|E17D|E17E|E17F|E180|E181|E182|E0EA|E183|E184|E185|E186|E0EB|E187|E188|E189|E18A|E18B|E18C|E18D|E18E|E0F2|E0F3|E11B|E11C|E11D|E11E|E0F4|E0F5|E11F|E120|E121|E122|E123|E124|E125|E126|E127|E128|E0F9|E129|E12A|E12B|E12C|E0FD|E12D|E12E|E12F|E130|E131|E132|E133)) E03B',
            'replace' => '\\1 E10C',
        ],
    318 =>
        [
            'match'   => '((E0E6|E0E7|E172|E173|E174|E175|E176|E0E8|E0E9|E177|E178|E179|E17A|E17B|E17C|E17D|E17E|E17F|E180|E181|E182|E0EA|E183|E184|E185|E186|E0EB|E187|E188|E189|E18A|E18B|E18C|E18D|E18E|E0F2|E0F3|E11B|E11C|E11D|E11E|E0F4|E0F5|E11F|E120|E121|E122|E123|E124|E125|E126|E127|E128|E0F9|E129|E12A|E12B|E12C|E0FD|E12D|E12E|E12F|E130|E131|E132|E133)) E03C',
            'replace' => '\\1 E10D',
        ],
    319 =>
        [
            'match'   => '((E0E6|E0E7|E172|E173|E174|E175|E176|E0E8|E0E9|E177|E178|E179|E17A|E17B|E17C|E17D|E17E|E17F|E180|E181|E182|E0EA|E183|E184|E185|E186|E0EB|E187|E188|E189|E18A|E18B|E18C|E18D|E18E|E0F2|E0F3|E11B|E11C|E11D|E11E|E0F4|E0F5|E11F|E120|E121|E122|E123|E124|E125|E126|E127|E128|E0F9|E129|E12A|E12B|E12C|E0FD|E12D|E12E|E12F|E130|E131|E132|E133)) E03E',
            'replace' => '\\1 E10E',
        ],
    320 =>
        [
            'match'   => '((E0E6|E0E7|E172|E173|E174|E175|E176|E0E8|E0E9|E177|E178|E179|E17A|E17B|E17C|E17D|E17E|E17F|E180|E181|E182|E0EA|E183|E184|E185|E186|E0EB|E187|E188|E189|E18A|E18B|E18C|E18D|E18E|E0F2|E0F3|E11B|E11C|E11D|E11E|E0F4|E0F5|E11F|E120|E121|E122|E123|E124|E125|E126|E127|E128|E0F9|E129|E12A|E12B|E12C|E0FD|E12D|E12E|E12F|E130|E131|E132|E133)) E03F',
            'replace' => '\\1 E10F',
        ],
    321 =>
        [
            'match'   => '((E0E6|E0E7|E172|E173|E174|E175|E176|E0E8|E0E9|E177|E178|E179|E17A|E17B|E17C|E17D|E17E|E17F|E180|E181|E182|E0EA|E183|E184|E185|E186|E0EB|E187|E188|E189|E18A|E18B|E18C|E18D|E18E|E0F2|E0F3|E11B|E11C|E11D|E11E|E0F4|E0F5|E11F|E120|E121|E122|E123|E124|E125|E126|E127|E128|E0F9|E129|E12A|E12B|E12C|E0FD|E12D|E12E|E12F|E130|E131|E132|E133)) E040',
            'replace' => '\\1 E110',
        ],
    322 =>
        [
            'match'   => '((E0E6|E0E7|E172|E173|E174|E175|E176|E0E8|E0E9|E177|E178|E179|E17A|E17B|E17C|E17D|E17E|E17F|E180|E181|E182|E0EA|E183|E184|E185|E186|E0EB|E187|E188|E189|E18A|E18B|E18C|E18D|E18E|E0F2|E0F3|E11B|E11C|E11D|E11E|E0F4|E0F5|E11F|E120|E121|E122|E123|E124|E125|E126|E127|E128|E0F9|E129|E12A|E12B|E12C|E0FD|E12D|E12E|E12F|E130|E131|E132|E133)) E049',
            'replace' => '\\1 E111',
        ],
    323 =>
        [
            'match'   => '((E0E6|E0E7|E172|E173|E174|E175|E176|E0E8|E0E9|E177|E178|E179|E17A|E17B|E17C|E17D|E17E|E17F|E180|E181|E182|E0EA|E183|E184|E185|E186|E0EB|E187|E188|E189|E18A|E18B|E18C|E18D|E18E|E0F2|E0F3|E11B|E11C|E11D|E11E|E0F4|E0F5|E11F|E120|E121|E122|E123|E124|E125|E126|E127|E128|E0F9|E129|E12A|E12B|E12C|E0FD|E12D|E12E|E12F|E130|E131|E132|E133)) E04A',
            'replace' => '\\1 E112',
        ],
    324 =>
        [
            'match'   => '((E0E6|E0E7|E172|E173|E174|E175|E176|E0E8|E0E9|E177|E178|E179|E17A|E17B|E17C|E17D|E17E|E17F|E180|E181|E182|E0EA|E183|E184|E185|E186|E0EB|E187|E188|E189|E18A|E18B|E18C|E18D|E18E|E0F2|E0F3|E11B|E11C|E11D|E11E|E0F4|E0F5|E11F|E120|E121|E122|E123|E124|E125|E126|E127|E128|E0F9|E129|E12A|E12B|E12C|E0FD|E12D|E12E|E12F|E130|E131|E132|E133)) E04C',
            'replace' => '\\1 E113',
        ],
    325 =>
        [
            'match'   => '((E0E6|E0E7|E172|E173|E174|E175|E176|E0E8|E0E9|E177|E178|E179|E17A|E17B|E17C|E17D|E17E|E17F|E180|E181|E182|E0EA|E183|E184|E185|E186|E0EB|E187|E188|E189|E18A|E18B|E18C|E18D|E18E|E0F2|E0F3|E11B|E11C|E11D|E11E|E0F4|E0F5|E11F|E120|E121|E122|E123|E124|E125|E126|E127|E128|E0F9|E129|E12A|E12B|E12C|E0FD|E12D|E12E|E12F|E130|E131|E132|E133)) E04E',
            'replace' => '\\1 E114',
        ],
    326 =>
        [
            'match'   => '((E0E6|E0E7|E172|E173|E174|E175|E176|E0E8|E0E9|E177|E178|E179|E17A|E17B|E17C|E17D|E17E|E17F|E180|E181|E182|E0EA|E183|E184|E185|E186|E0EB|E187|E188|E189|E18A|E18B|E18C|E18D|E18E|E0F2|E0F3|E11B|E11C|E11D|E11E|E0F4|E0F5|E11F|E120|E121|E122|E123|E124|E125|E126|E127|E128|E0F9|E129|E12A|E12B|E12C|E0FD|E12D|E12E|E12F|E130|E131|E132|E133)) E050',
            'replace' => '\\1 E115',
        ],
    327 =>
        [
            'match'   => '((E0E6|E0E7|E172|E173|E174|E175|E176|E0E8|E0E9|E177|E178|E179|E17A|E17B|E17C|E17D|E17E|E17F|E180|E181|E182|E0EA|E183|E184|E185|E186|E0EB|E187|E188|E189|E18A|E18B|E18C|E18D|E18E|E0F2|E0F3|E11B|E11C|E11D|E11E|E0F4|E0F5|E11F|E120|E121|E122|E123|E124|E125|E126|E127|E128|E0F9|E129|E12A|E12B|E12C|E0FD|E12D|E12E|E12F|E130|E131|E132|E133)) E051',
            'replace' => '\\1 E116',
        ],
    328 =>
        [
            'match'   => '((0CAA|0CAB|0CB5|E182|E0EA|E18A)) 0CC1',
            'replace' => '\\1 E007',
        ],
    329 =>
        [
            'match'   => '((0CAA|0CAB|0CB5|E182|E0EA|E18A)) 0CC2',
            'replace' => '\\1 E009',
        ],
    330 =>
        [
            'match'   => '((0CB3|E189)) 0CC1',
            'replace' => '\\1 E008',
        ],
    331 =>
        [
            'match'   => '((0CB3|E189)) 0CC2',
            'replace' => '\\1 E00A',
        ],
    332 =>
        [
            'match'   => '0C98 0CC1',
            'replace' => 'E0A5',
        ],
    333 =>
        [
            'match'   => '0C98 0CC6',
            'replace' => 'E0A6',
        ],
    334 =>
        [
            'match'   => '0C98 0CCA',
            'replace' => 'E0A7',
        ],
    335 =>
        [
            'match'   => '0C98 0CCB',
            'replace' => 'E0A8',
        ],
    336 =>
        [
            'match'   => '0C99 0CC6',
            'replace' => 'E0A9',
        ],
    337 =>
        [
            'match'   => '0C99 0CCA',
            'replace' => 'E0AA',
        ],
    338 =>
        [
            'match'   => '0C99 0CCB',
            'replace' => 'E0AB',
        ],
    339 =>
        [
            'match'   => '0C9E 0CC1',
            'replace' => 'E0D8',
        ],
    340 =>
        [
            'match'   => '0C9E 0CC2',
            'replace' => 'E0D9',
        ],
    341 =>
        [
            'match'   => '0C9E 0CC6',
            'replace' => 'E0DA',
        ],
    342 =>
        [
            'match'   => '0C9E 0CCA',
            'replace' => 'E0DB',
        ],
    343 =>
        [
            'match'   => '0CB1 0CC6',
            'replace' => 'E0AC',
        ],
    344 =>
        [
            'match'   => '0CB1 0CCA',
            'replace' => 'E0AD',
        ],
    345 =>
        [
            'match'   => '0CB1 0CCB',
            'replace' => 'E0AE',
        ],
    346 =>
        [
            'match'   => '0CAA 0CCA',
            'replace' => 'E0AF',
        ],
    347 =>
        [
            'match'   => '0CAB 0CCA',
            'replace' => 'E0B0',
        ],
    348 =>
        [
            'match'   => '0CB5 0CCA',
            'replace' => 'E0B1',
        ],
    349 =>
        [
            'match'   => '0C9D 0CC6',
            'replace' => 'E0DD',
        ],
    350 =>
        [
            'match'   => '0C9D 0CCA',
            'replace' => 'E0DE',
        ],
    351 =>
        [
            'match'   => '0C9D 0CCB',
            'replace' => 'E0DF',
        ],
    352 =>
        [
            'match'   => '0CAE 0CC6',
            'replace' => 'E0E0',
        ],
    353 =>
        [
            'match'   => '0CAE 0CCA',
            'replace' => 'E0E1',
        ],
    354 =>
        [
            'match'   => '0CAE 0CCB',
            'replace' => 'E0E2',
        ],
    355 =>
        [
            'match'   => '0CAF 0CC6',
            'replace' => 'E0E3',
        ],
    356 =>
        [
            'match'   => '0CAF 0CCA',
            'replace' => 'E0E4',
        ],
    357 =>
        [
            'match'   => '0CAF 0CCB',
            'replace' => 'E0E5',
        ],
    358 =>
        [
            'match'   => '0CB3 0CCA',
            'replace' => 'E0DC',
        ],
    359 =>
        [
            'match'   => 'E173 0CC1',
            'replace' => 'E138',
        ],
    360 =>
        [
            'match'   => 'E173 0CC6',
            'replace' => 'E139',
        ],
    361 =>
        [
            'match'   => 'E173 0CCA',
            'replace' => 'E13A',
        ],
    362 =>
        [
            'match'   => 'E174 0CC6',
            'replace' => 'E13C',
        ],
    363 =>
        [
            'match'   => 'E174 0CCA',
            'replace' => 'E13D',
        ],
    364 =>
        [
            'match'   => 'E187 0CC6',
            'replace' => 'E13F',
        ],
    365 =>
        [
            'match'   => 'E187 0CCA',
            'replace' => 'E140',
        ],
    366 =>
        [
            'match'   => 'E182 0CCA',
            'replace' => 'E142',
        ],
    367 =>
        [
            'match'   => 'E0EA 0CCA',
            'replace' => 'E0FC',
        ],
    368 =>
        [
            'match'   => 'E18A 0CCA',
            'replace' => 'E143',
        ],
    369 =>
        [
            'match'   => 'E177 0CC1',
            'replace' => 'E164',
        ],
    370 =>
        [
            'match'   => 'E177 0CC2',
            'replace' => 'E165',
        ],
    371 =>
        [
            'match'   => 'E177 0CC6',
            'replace' => 'E166',
        ],
    372 =>
        [
            'match'   => 'E177 0CCA',
            'replace' => 'E167',
        ],
    373 =>
        [
            'match'   => 'E189 0CCA',
            'replace' => 'E168',
        ],
    374 =>
        [
            'match'   => 'E0E9 0CC6',
            'replace' => 'E0F6',
        ],
    375 =>
        [
            'match'   => 'E0E9 0CCA',
            'replace' => 'E0F8',
        ],
    376 =>
        [
            'match'   => 'E185 0CC6',
            'replace' => 'E16C',
        ],
    377 =>
        [
            'match'   => 'E185 0CCA',
            'replace' => 'E16D',
        ],
    378 =>
        [
            'match'   => 'E186 0CC6',
            'replace' => 'E16F',
        ],
    379 =>
        [
            'match'   => 'E186 0CCA',
            'replace' => 'E170',
        ],
    380 =>
        [
            'match'   => '0C95 0CBF',
            'replace' => 'E082',
        ],
    381 =>
        [
            'match'   => '0C96 0CBF',
            'replace' => 'E083',
        ],
    382 =>
        [
            'match'   => '0C97 0CBF',
            'replace' => 'E084',
        ],
    383 =>
        [
            'match'   => '0C98 0CBF',
            'replace' => 'E085',
        ],
    384 =>
        [
            'match'   => '0C9A 0CBF',
            'replace' => 'E086',
        ],
    385 =>
        [
            'match'   => '0C9B 0CBF',
            'replace' => 'E087',
        ],
    386 =>
        [
            'match'   => '0C9C 0CBF',
            'replace' => 'E088',
        ],
    387 =>
        [
            'match'   => '0C9D 0CBF',
            'replace' => 'E089',
        ],
    388 =>
        [
            'match'   => '0CA0 0CBF',
            'replace' => 'E08A',
        ],
    389 =>
        [
            'match'   => '0CA1 0CBF',
            'replace' => 'E08B',
        ],
    390 =>
        [
            'match'   => '0CA2 0CBF',
            'replace' => 'E08C',
        ],
    391 =>
        [
            'match'   => '0CA3 0CBF',
            'replace' => 'E08D',
        ],
    392 =>
        [
            'match'   => '0CA4 0CBF',
            'replace' => 'E08E',
        ],
    393 =>
        [
            'match'   => '0CA5 0CBF',
            'replace' => 'E08F',
        ],
    394 =>
        [
            'match'   => '0CA6 0CBF',
            'replace' => 'E090',
        ],
    395 =>
        [
            'match'   => '0CA7 0CBF',
            'replace' => 'E091',
        ],
    396 =>
        [
            'match'   => '0CA8 0CBF',
            'replace' => 'E092',
        ],
    397 =>
        [
            'match'   => '0CAA 0CBF',
            'replace' => 'E093',
        ],
    398 =>
        [
            'match'   => '0CAB 0CBF',
            'replace' => 'E094',
        ],
    399 =>
        [
            'match'   => '0CAC 0CBF',
            'replace' => 'E095',
        ],
    400 =>
        [
            'match'   => '0CAD 0CBF',
            'replace' => 'E096',
        ],
    401 =>
        [
            'match'   => '0CAE 0CBF',
            'replace' => 'E097',
        ],
    402 =>
        [
            'match'   => '0CAF 0CBF',
            'replace' => 'E098',
        ],
    403 =>
        [
            'match'   => '0CB0 0CBF',
            'replace' => 'E099',
        ],
    404 =>
        [
            'match'   => '0CB2 0CBF',
            'replace' => 'E09A',
        ],
    405 =>
        [
            'match'   => '0CB3 0CBF',
            'replace' => 'E09B',
        ],
    406 =>
        [
            'match'   => '0CB5 0CBF',
            'replace' => 'E09C',
        ],
    407 =>
        [
            'match'   => '0CB6 0CBF',
            'replace' => 'E09D',
        ],
    408 =>
        [
            'match'   => '0CB7 0CBF',
            'replace' => 'E09E',
        ],
    409 =>
        [
            'match'   => '0CB8 0CBF',
            'replace' => 'E09F',
        ],
    410 =>
        [
            'match'   => '0CB9 0CBF',
            'replace' => 'E0A0',
        ],
    411 =>
        [
            'match'   => 'E07D 0CBF',
            'replace' => 'E0A1',
        ],
    412 =>
        [
            'match'   => 'E07E 0CBF',
            'replace' => 'E0A2',
        ],
    413 =>
        [
            'match'   => 'E0E6 0CBF',
            'replace' => 'E0F2',
        ],
    414 =>
        [
            'match'   => 'E0E7 0CBF',
            'replace' => 'E0F3',
        ],
    415 =>
        [
            'match'   => 'E172 0CBF',
            'replace' => 'E11B',
        ],
    416 =>
        [
            'match'   => 'E173 0CBF',
            'replace' => 'E11C',
        ],
    417 =>
        [
            'match'   => 'E175 0CBF',
            'replace' => 'E11D',
        ],
    418 =>
        [
            'match'   => 'E176 0CBF',
            'replace' => 'E11E',
        ],
    419 =>
        [
            'match'   => 'E0E8 0CBF',
            'replace' => 'E0F4',
        ],
    420 =>
        [
            'match'   => 'E0E9 0CBF',
            'replace' => 'E0F5',
        ],
    421 =>
        [
            'match'   => 'E179 0CBF',
            'replace' => 'E11F',
        ],
    422 =>
        [
            'match'   => 'E17A 0CBF',
            'replace' => 'E120',
        ],
    423 =>
        [
            'match'   => 'E17B 0CBF',
            'replace' => 'E121',
        ],
    424 =>
        [
            'match'   => 'E17C 0CBF',
            'replace' => 'E122',
        ],
    425 =>
        [
            'match'   => 'E17D 0CBF',
            'replace' => 'E123',
        ],
    426 =>
        [
            'match'   => 'E17E 0CBF',
            'replace' => 'E124',
        ],
    427 =>
        [
            'match'   => 'E17F 0CBF',
            'replace' => 'E125',
        ],
    428 =>
        [
            'match'   => 'E180 0CBF',
            'replace' => 'E126',
        ],
    429 =>
        [
            'match'   => 'E181 0CBF',
            'replace' => 'E127',
        ],
    430 =>
        [
            'match'   => 'E182 0CBF',
            'replace' => 'E128',
        ],
    431 =>
        [
            'match'   => 'E0EA 0CBF',
            'replace' => 'E0F9',
        ],
    432 =>
        [
            'match'   => 'E183 0CBF',
            'replace' => 'E129',
        ],
    433 =>
        [
            'match'   => 'E184 0CBF',
            'replace' => 'E12A',
        ],
    434 =>
        [
            'match'   => 'E185 0CBF',
            'replace' => 'E12B',
        ],
    435 =>
        [
            'match'   => 'E186 0CBF',
            'replace' => 'E12C',
        ],
    436 =>
        [
            'match'   => 'E0EB 0CBF',
            'replace' => 'E0FD',
        ],
    437 =>
        [
            'match'   => 'E188 0CBF',
            'replace' => 'E12D',
        ],
    438 =>
        [
            'match'   => 'E189 0CBF',
            'replace' => 'E12E',
        ],
    439 =>
        [
            'match'   => 'E18A 0CBF',
            'replace' => 'E12F',
        ],
    440 =>
        [
            'match'   => 'E18B 0CBF',
            'replace' => 'E130',
        ],
    441 =>
        [
            'match'   => 'E18C 0CBF',
            'replace' => 'E131',
        ],
    442 =>
        [
            'match'   => 'E18D 0CBF',
            'replace' => 'E132',
        ],
    443 =>
        [
            'match'   => 'E18E 0CBF',
            'replace' => 'E133',
        ],
    444 =>
        [
            'match'   => 'E117 0CBF',
            'replace' => 'E134',
        ],
    445 =>
        [
            'match'   => 'E118 0CBF',
            'replace' => 'E135',
        ],
    446 =>
        [
            'match'   => 'E136 0CBF',
            'replace' => 'E137',
        ],
    447 =>
        [
            'match'   => 'E0AD',
            'replace' => '0CCD',
        ],
    448 =>
        [
            'match'   => 'E0AC',
            'replace' => '0CCD',
        ],
    449 =>
        [
            'match'   => '0C95 ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E00C \\1',
        ],
    450 =>
        [
            'match'   => '0C96 ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E00D \\1',
        ],
    451 =>
        [
            'match'   => '0C97 ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E00E \\1',
        ],
    452 =>
        [
            'match'   => '0C98 ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E00F \\1',
        ],
    453 =>
        [
            'match'   => '0C99 ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E010 \\1',
        ],
    454 =>
        [
            'match'   => '0C9A ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E011 \\1',
        ],
    455 =>
        [
            'match'   => '0C9B ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E012 \\1',
        ],
    456 =>
        [
            'match'   => '0C9C ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E013 \\1',
        ],
    457 =>
        [
            'match'   => '0C9D ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E014 \\1',
        ],
    458 =>
        [
            'match'   => '0C9F ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E015 \\1',
        ],
    459 =>
        [
            'match'   => '0CA0 ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E016 \\1',
        ],
    460 =>
        [
            'match'   => '0CA1 ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E017 \\1',
        ],
    461 =>
        [
            'match'   => '0CA2 ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E018 \\1',
        ],
    462 =>
        [
            'match'   => '0CA3 ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E019 \\1',
        ],
    463 =>
        [
            'match'   => '0CA4 ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E01A \\1',
        ],
    464 =>
        [
            'match'   => '0CA5 ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E01B \\1',
        ],
    465 =>
        [
            'match'   => '0CA6 ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E01C \\1',
        ],
    466 =>
        [
            'match'   => '0CA7 ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E01D \\1',
        ],
    467 =>
        [
            'match'   => '0CA8 ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E01E \\1',
        ],
    468 =>
        [
            'match'   => '0CAA ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E01F \\1',
        ],
    469 =>
        [
            'match'   => '0CAB ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E020 \\1',
        ],
    470 =>
        [
            'match'   => '0CAC ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E021 \\1',
        ],
    471 =>
        [
            'match'   => '0CAD ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E022 \\1',
        ],
    472 =>
        [
            'match'   => '0CAE ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E023 \\1',
        ],
    473 =>
        [
            'match'   => '0CAF ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E024 \\1',
        ],
    474 =>
        [
            'match'   => '0CB0 ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E025 \\1',
        ],
    475 =>
        [
            'match'   => '0CB1 ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E026 \\1',
        ],
    476 =>
        [
            'match'   => '0CB2 ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E027 \\1',
        ],
    477 =>
        [
            'match'   => '0CB3 ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E028 \\1',
        ],
    478 =>
        [
            'match'   => '0CB5 ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E029 \\1',
        ],
    479 =>
        [
            'match'   => '0CB6 ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E02A \\1',
        ],
    480 =>
        [
            'match'   => '0CB7 ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E02B \\1',
        ],
    481 =>
        [
            'match'   => '0CB8 ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E02C \\1',
        ],
    482 =>
        [
            'match'   => '0CB9 ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E02D \\1',
        ],
    483 =>
        [
            'match'   => 'E07D ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E07F \\1',
        ],
    484 =>
        [
            'match'   => 'E07E ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E080 \\1',
        ],
    485 =>
        [
            'match'   => 'E0E6 ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E0EC \\1',
        ],
    486 =>
        [
            'match'   => 'E0E7 ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E0ED \\1',
        ],
    487 =>
        [
            'match'   => 'E172 ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E18F \\1',
        ],
    488 =>
        [
            'match'   => 'E173 ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E190 \\1',
        ],
    489 =>
        [
            'match'   => 'E174 ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E191 \\1',
        ],
    490 =>
        [
            'match'   => 'E175 ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E192 \\1',
        ],
    491 =>
        [
            'match'   => 'E176 ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E193 \\1',
        ],
    492 =>
        [
            'match'   => 'E0E8 ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E0EE \\1',
        ],
    493 =>
        [
            'match'   => 'E0E9 ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E0EF \\1',
        ],
    494 =>
        [
            'match'   => 'E178 ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E194 \\1',
        ],
    495 =>
        [
            'match'   => 'E179 ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E195 \\1',
        ],
    496 =>
        [
            'match'   => 'E17A ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E196 \\1',
        ],
    497 =>
        [
            'match'   => 'E17B ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E197 \\1',
        ],
    498 =>
        [
            'match'   => 'E17C ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E198 \\1',
        ],
    499 =>
        [
            'match'   => 'E17D ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E199 \\1',
        ],
    500 =>
        [
            'match'   => 'E17E ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E19A \\1',
        ],
    501 =>
        [
            'match'   => 'E17F ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E19B \\1',
        ],
    502 =>
        [
            'match'   => 'E180 ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E19C \\1',
        ],
    503 =>
        [
            'match'   => 'E181 ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E19D \\1',
        ],
    504 =>
        [
            'match'   => 'E182 ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E19E \\1',
        ],
    505 =>
        [
            'match'   => 'E0EA ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E0F0 \\1',
        ],
    506 =>
        [
            'match'   => 'E183 ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E19F \\1',
        ],
    507 =>
        [
            'match'   => 'E184 ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E1A0 \\1',
        ],
    508 =>
        [
            'match'   => 'E185 ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E1A1 \\1',
        ],
    509 =>
        [
            'match'   => 'E186 ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E1A2 \\1',
        ],
    510 =>
        [
            'match'   => 'E0EB ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E0F1 \\1',
        ],
    511 =>
        [
            'match'   => 'E187 ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E1A3 \\1',
        ],
    512 =>
        [
            'match'   => 'E188 ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E1A4 \\1',
        ],
    513 =>
        [
            'match'   => 'E189 ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E1A5 \\1',
        ],
    514 =>
        [
            'match'   => 'E18A ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E1A6 \\1',
        ],
    515 =>
        [
            'match'   => 'E18B ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E1A7 \\1',
        ],
    516 =>
        [
            'match'   => 'E18C ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E1A8 \\1',
        ],
    517 =>
        [
            'match'   => 'E18D ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E1A9 \\1',
        ],
    518 =>
        [
            'match'   => 'E18E ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E1AA \\1',
        ],
    519 =>
        [
            'match'   => 'E117 ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E119 \\1',
        ],
    520 =>
        [
            'match'   => 'E118 ((0CBE|0CC6|0CC7|E003|0CCA|0CCB|0CCC))',
            'replace' => 'E11A \\1',
        ],
    521 =>
        [
            'match'   => '0C95 (0CCD)',
            'replace' => 'E00C \\1',
        ],
    522 =>
        [
            'match'   => '0C96 (0CCD)',
            'replace' => 'E00D \\1',
        ],
    523 =>
        [
            'match'   => '0C97 (0CCD)',
            'replace' => 'E00E \\1',
        ],
    524 =>
        [
            'match'   => '0C98 (0CCD)',
            'replace' => 'E00F \\1',
        ],
    525 =>
        [
            'match'   => '0C99 (0CCD)',
            'replace' => 'E010 \\1',
        ],
    526 =>
        [
            'match'   => '0C9A (0CCD)',
            'replace' => 'E011 \\1',
        ],
    527 =>
        [
            'match'   => '0C9B (0CCD)',
            'replace' => 'E012 \\1',
        ],
    528 =>
        [
            'match'   => '0C9C (0CCD)',
            'replace' => 'E013 \\1',
        ],
    529 =>
        [
            'match'   => '0C9D (0CCD)',
            'replace' => 'E014 \\1',
        ],
    530 =>
        [
            'match'   => '0C9F (0CCD)',
            'replace' => 'E015 \\1',
        ],
    531 =>
        [
            'match'   => '0CA0 (0CCD)',
            'replace' => 'E016 \\1',
        ],
    532 =>
        [
            'match'   => '0CA1 (0CCD)',
            'replace' => 'E017 \\1',
        ],
    533 =>
        [
            'match'   => '0CA2 (0CCD)',
            'replace' => 'E018 \\1',
        ],
    534 =>
        [
            'match'   => '0CA3 (0CCD)',
            'replace' => 'E019 \\1',
        ],
    535 =>
        [
            'match'   => '0CA4 (0CCD)',
            'replace' => 'E01A \\1',
        ],
    536 =>
        [
            'match'   => '0CA5 (0CCD)',
            'replace' => 'E01B \\1',
        ],
    537 =>
        [
            'match'   => '0CA6 (0CCD)',
            'replace' => 'E01C \\1',
        ],
    538 =>
        [
            'match'   => '0CA7 (0CCD)',
            'replace' => 'E01D \\1',
        ],
    539 =>
        [
            'match'   => '0CA8 (0CCD)',
            'replace' => 'E01E \\1',
        ],
    540 =>
        [
            'match'   => '0CAA (0CCD)',
            'replace' => 'E01F \\1',
        ],
    541 =>
        [
            'match'   => '0CAB (0CCD)',
            'replace' => 'E020 \\1',
        ],
    542 =>
        [
            'match'   => '0CAC (0CCD)',
            'replace' => 'E021 \\1',
        ],
    543 =>
        [
            'match'   => '0CAD (0CCD)',
            'replace' => 'E022 \\1',
        ],
    544 =>
        [
            'match'   => '0CAE (0CCD)',
            'replace' => 'E023 \\1',
        ],
    545 =>
        [
            'match'   => '0CAF (0CCD)',
            'replace' => 'E024 \\1',
        ],
    546 =>
        [
            'match'   => '0CB0 (0CCD)',
            'replace' => 'E025 \\1',
        ],
    547 =>
        [
            'match'   => '0CB1 (0CCD)',
            'replace' => 'E026 \\1',
        ],
    548 =>
        [
            'match'   => '0CB2 (0CCD)',
            'replace' => 'E027 \\1',
        ],
    549 =>
        [
            'match'   => '0CB3 (0CCD)',
            'replace' => 'E028 \\1',
        ],
    550 =>
        [
            'match'   => '0CB5 (0CCD)',
            'replace' => 'E029 \\1',
        ],
    551 =>
        [
            'match'   => '0CB6 (0CCD)',
            'replace' => 'E02A \\1',
        ],
    552 =>
        [
            'match'   => '0CB7 (0CCD)',
            'replace' => 'E02B \\1',
        ],
    553 =>
        [
            'match'   => '0CB8 (0CCD)',
            'replace' => 'E02C \\1',
        ],
    554 =>
        [
            'match'   => '0CB9 (0CCD)',
            'replace' => 'E02D \\1',
        ],
    555 =>
        [
            'match'   => 'E07D (0CCD)',
            'replace' => 'E07F \\1',
        ],
    556 =>
        [
            'match'   => 'E07E (0CCD)',
            'replace' => 'E080 \\1',
        ],
    557 =>
        [
            'match'   => 'E0E6 (0CCD)',
            'replace' => 'E0EC \\1',
        ],
    558 =>
        [
            'match'   => 'E0E7 (0CCD)',
            'replace' => 'E0ED \\1',
        ],
    559 =>
        [
            'match'   => 'E172 (0CCD)',
            'replace' => 'E18F \\1',
        ],
    560 =>
        [
            'match'   => 'E173 (0CCD)',
            'replace' => 'E190 \\1',
        ],
    561 =>
        [
            'match'   => 'E174 (0CCD)',
            'replace' => 'E191 \\1',
        ],
    562 =>
        [
            'match'   => 'E175 (0CCD)',
            'replace' => 'E192 \\1',
        ],
    563 =>
        [
            'match'   => 'E176 (0CCD)',
            'replace' => 'E193 \\1',
        ],
    564 =>
        [
            'match'   => 'E0E8 (0CCD)',
            'replace' => 'E0EE \\1',
        ],
    565 =>
        [
            'match'   => 'E0E9 (0CCD)',
            'replace' => 'E0EF \\1',
        ],
    566 =>
        [
            'match'   => 'E178 (0CCD)',
            'replace' => 'E194 \\1',
        ],
    567 =>
        [
            'match'   => 'E179 (0CCD)',
            'replace' => 'E195 \\1',
        ],
    568 =>
        [
            'match'   => 'E17A (0CCD)',
            'replace' => 'E196 \\1',
        ],
    569 =>
        [
            'match'   => 'E17B (0CCD)',
            'replace' => 'E197 \\1',
        ],
    570 =>
        [
            'match'   => 'E17C (0CCD)',
            'replace' => 'E198 \\1',
        ],
    571 =>
        [
            'match'   => 'E17D (0CCD)',
            'replace' => 'E199 \\1',
        ],
    572 =>
        [
            'match'   => 'E17E (0CCD)',
            'replace' => 'E19A \\1',
        ],
    573 =>
        [
            'match'   => 'E17F (0CCD)',
            'replace' => 'E19B \\1',
        ],
    574 =>
        [
            'match'   => 'E180 (0CCD)',
            'replace' => 'E19C \\1',
        ],
    575 =>
        [
            'match'   => 'E181 (0CCD)',
            'replace' => 'E19D \\1',
        ],
    576 =>
        [
            'match'   => 'E182 (0CCD)',
            'replace' => 'E19E \\1',
        ],
    577 =>
        [
            'match'   => 'E0EA (0CCD)',
            'replace' => 'E0F0 \\1',
        ],
    578 =>
        [
            'match'   => 'E183 (0CCD)',
            'replace' => 'E19F \\1',
        ],
    579 =>
        [
            'match'   => 'E184 (0CCD)',
            'replace' => 'E1A0 \\1',
        ],
    580 =>
        [
            'match'   => 'E185 (0CCD)',
            'replace' => 'E1A1 \\1',
        ],
    581 =>
        [
            'match'   => 'E186 (0CCD)',
            'replace' => 'E1A2 \\1',
        ],
    582 =>
        [
            'match'   => 'E0EB (0CCD)',
            'replace' => 'E0F1 \\1',
        ],
    583 =>
        [
            'match'   => 'E187 (0CCD)',
            'replace' => 'E1A3 \\1',
        ],
    584 =>
        [
            'match'   => 'E188 (0CCD)',
            'replace' => 'E1A4 \\1',
        ],
    585 =>
        [
            'match'   => 'E189 (0CCD)',
            'replace' => 'E1A5 \\1',
        ],
    586 =>
        [
            'match'   => 'E18A (0CCD)',
            'replace' => 'E1A6 \\1',
        ],
    587 =>
        [
            'match'   => 'E18B (0CCD)',
            'replace' => 'E1A7 \\1',
        ],
    588 =>
        [
            'match'   => 'E18C (0CCD)',
            'replace' => 'E1A8 \\1',
        ],
    589 =>
        [
            'match'   => 'E18D (0CCD)',
            'replace' => 'E1A9 \\1',
        ],
    590 =>
        [
            'match'   => 'E18E (0CCD)',
            'replace' => 'E1AA \\1',
        ],
    591 =>
        [
            'match'   => 'E117 (0CCD)',
            'replace' => 'E119 \\1',
        ],
    592 =>
        [
            'match'   => 'E118 (0CCD)',
            'replace' => 'E11A \\1',
        ],
    593 =>
        [
            'match'   => '0C98 (0CBE)',
            'replace' => 'E00F \\1',
        ],
    594 =>
        [
            'match'   => '0CB1 (0CBE)',
            'replace' => 'E026 \\1',
        ],
    595 =>
        [
            'match'   => 'E173 (0CBE)',
            'replace' => 'E190 \\1',
        ],
    596 =>
        [
            'match'   => 'E187 (0CBE)',
            'replace' => 'E1A3 \\1',
        ],
    597 =>
        [
            'match'   => '((E00F|0C9E|E026|E190|E177|E1A3)) 0CBE',
            'replace' => '\\1 E004',
        ],
];
?>