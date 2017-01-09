<?php
$volt = [
    0   =>
        [
            'match'   => '0A4D 200C',
            'replace' => 'E055',
        ],
    1   =>
        [
            'match'   => '0A4D 200D',
            'replace' => 'E057',
        ],
    2   =>
        [
            'match'   => '((0A15|0A16|0A17|0A18|0A19|0A1A|0A1B|0A1C|0A1D|0A1E|0A1F|0A20|0A21|0A22|0A23|0A24|0A25|0A26|0A27|0A28|0A2A|0A2B|0A2C|0A2D|0A2E|0A2F|0A30|0A32|0A33|0A35|0A36|0A38|0A39|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E|E04F|E050|E051|E052|E053|E054|0A59|0A5A|0A5B|0A5C|0A5E)) 0A4D',
            'replace' => '\\1 E056',
        ],
    3   =>
        [
            'match'   => '(200D) 0A4D',
            'replace' => '\\1 E056',
        ],
    4   =>
        [
            'match'   => '(0020) 0A4D',
            'replace' => '\\1 E056',
        ],
    5   =>
        [
            'match'   => '(25CC) 0A4D',
            'replace' => '\\1 E056',
        ],
    6   =>
        [
            'match'   => '200D E056',
            'replace' => 'E056',
        ],
    7   =>
        [
            'match'   => '0A05 0A3C',
            'replace' => 'E02A',
        ],
    8   =>
        [
            'match'   => '0A06 0A3C',
            'replace' => 'E02B',
        ],
    9   =>
        [
            'match'   => '0A07 0A3C',
            'replace' => 'E02C',
        ],
    10  =>
        [
            'match'   => '0A08 0A3C',
            'replace' => 'E02D',
        ],
    11  =>
        [
            'match'   => '0A09 0A3C',
            'replace' => 'E02E',
        ],
    12  =>
        [
            'match'   => '0A0A 0A3C',
            'replace' => 'E02F',
        ],
    13  =>
        [
            'match'   => '0A0F 0A3C',
            'replace' => 'E030',
        ],
    14  =>
        [
            'match'   => '0A10 0A3C',
            'replace' => 'E031',
        ],
    15  =>
        [
            'match'   => '0A13 0A3C',
            'replace' => 'E032',
        ],
    16  =>
        [
            'match'   => '0A14 0A3C',
            'replace' => 'E033',
        ],
    17  =>
        [
            'match'   => '0A15 0A3C',
            'replace' => 'E034',
        ],
    18  =>
        [
            'match'   => '0A16 0A3C',
            'replace' => 'E035',
        ],
    19  =>
        [
            'match'   => '0A17 0A3C',
            'replace' => 'E036',
        ],
    20  =>
        [
            'match'   => '0A18 0A3C',
            'replace' => 'E037',
        ],
    21  =>
        [
            'match'   => '0A19 0A3C',
            'replace' => 'E038',
        ],
    22  =>
        [
            'match'   => '0A1A 0A3C',
            'replace' => 'E039',
        ],
    23  =>
        [
            'match'   => '0A1B 0A3C',
            'replace' => 'E03A',
        ],
    24  =>
        [
            'match'   => '0A1C 0A3C',
            'replace' => 'E03B',
        ],
    25  =>
        [
            'match'   => '0A1D 0A3C',
            'replace' => 'E03C',
        ],
    26  =>
        [
            'match'   => '0A1E 0A3C',
            'replace' => 'E03D',
        ],
    27  =>
        [
            'match'   => '0A1F 0A3C',
            'replace' => 'E03E',
        ],
    28  =>
        [
            'match'   => '0A20 0A3C',
            'replace' => 'E03F',
        ],
    29  =>
        [
            'match'   => '0A21 0A3C',
            'replace' => 'E040',
        ],
    30  =>
        [
            'match'   => '0A22 0A3C',
            'replace' => 'E041',
        ],
    31  =>
        [
            'match'   => '0A23 0A3C',
            'replace' => 'E042',
        ],
    32  =>
        [
            'match'   => '0A24 0A3C',
            'replace' => 'E043',
        ],
    33  =>
        [
            'match'   => '0A25 0A3C',
            'replace' => 'E044',
        ],
    34  =>
        [
            'match'   => '0A26 0A3C',
            'replace' => 'E045',
        ],
    35  =>
        [
            'match'   => '0A27 0A3C',
            'replace' => 'E046',
        ],
    36  =>
        [
            'match'   => '0A28 0A3C',
            'replace' => 'E047',
        ],
    37  =>
        [
            'match'   => '0A2A 0A3C',
            'replace' => 'E048',
        ],
    38  =>
        [
            'match'   => '0A2B 0A3C',
            'replace' => 'E049',
        ],
    39  =>
        [
            'match'   => '0A2C 0A3C',
            'replace' => 'E04A',
        ],
    40  =>
        [
            'match'   => '0A2D 0A3C',
            'replace' => 'E04B',
        ],
    41  =>
        [
            'match'   => '0A2E 0A3C',
            'replace' => 'E04C',
        ],
    42  =>
        [
            'match'   => '0A2F 0A3C',
            'replace' => 'E04D',
        ],
    43  =>
        [
            'match'   => '0A30 0A3C',
            'replace' => 'E04E',
        ],
    44  =>
        [
            'match'   => '0A32 0A3C',
            'replace' => 'E04F',
        ],
    45  =>
        [
            'match'   => '0A33 0A3C',
            'replace' => 'E050',
        ],
    46  =>
        [
            'match'   => '0A35 0A3C',
            'replace' => 'E051',
        ],
    47  =>
        [
            'match'   => '0A36 0A3C',
            'replace' => 'E052',
        ],
    48  =>
        [
            'match'   => '0A38 0A3C',
            'replace' => 'E053',
        ],
    49  =>
        [
            'match'   => '0A39 0A3C',
            'replace' => 'E054',
        ],
    50  =>
        [
            'match'   => 'E056 0A15',
            'replace' => 'E07B',
        ],
    51  =>
        [
            'match'   => 'E056 0A16',
            'replace' => 'E07C',
        ],
    52  =>
        [
            'match'   => 'E056 0A17',
            'replace' => 'E07D',
        ],
    53  =>
        [
            'match'   => 'E056 0A18',
            'replace' => 'E07E',
        ],
    54  =>
        [
            'match'   => 'E056 0A19',
            'replace' => 'E07F',
        ],
    55  =>
        [
            'match'   => 'E056 0A1A',
            'replace' => 'E080',
        ],
    56  =>
        [
            'match'   => 'E056 0A1B',
            'replace' => 'E081',
        ],
    57  =>
        [
            'match'   => 'E056 0A1C',
            'replace' => 'E082',
        ],
    58  =>
        [
            'match'   => 'E056 0A1D',
            'replace' => 'E083',
        ],
    59  =>
        [
            'match'   => 'E056 0A1E',
            'replace' => 'E084',
        ],
    60  =>
        [
            'match'   => 'E056 0A1F',
            'replace' => 'E085',
        ],
    61  =>
        [
            'match'   => 'E056 0A20',
            'replace' => 'E086',
        ],
    62  =>
        [
            'match'   => 'E056 0A21',
            'replace' => 'E087',
        ],
    63  =>
        [
            'match'   => 'E056 0A22',
            'replace' => 'E088',
        ],
    64  =>
        [
            'match'   => 'E056 0A23',
            'replace' => 'E089',
        ],
    65  =>
        [
            'match'   => 'E056 0A24',
            'replace' => 'E08A',
        ],
    66  =>
        [
            'match'   => 'E056 0A25',
            'replace' => 'E08B',
        ],
    67  =>
        [
            'match'   => 'E056 0A26',
            'replace' => 'E08C',
        ],
    68  =>
        [
            'match'   => 'E056 0A27',
            'replace' => 'E08D',
        ],
    69  =>
        [
            'match'   => 'E056 0A28',
            'replace' => 'E08E',
        ],
    70  =>
        [
            'match'   => 'E056 0A2A',
            'replace' => 'E08F',
        ],
    71  =>
        [
            'match'   => 'E056 0A2B',
            'replace' => 'E090',
        ],
    72  =>
        [
            'match'   => 'E056 0A2C',
            'replace' => 'E091',
        ],
    73  =>
        [
            'match'   => 'E056 0A2D',
            'replace' => 'E092',
        ],
    74  =>
        [
            'match'   => 'E056 0A2E',
            'replace' => 'E093',
        ],
    75  =>
        [
            'match'   => 'E056 0A2F',
            'replace' => 'E094',
        ],
    76  =>
        [
            'match'   => 'E056 0A30',
            'replace' => 'E095',
        ],
    77  =>
        [
            'match'   => 'E056 0A32',
            'replace' => 'E096',
        ],
    78  =>
        [
            'match'   => 'E056 0A35',
            'replace' => 'E097',
        ],
    79  =>
        [
            'match'   => 'E056 0A36',
            'replace' => 'E098',
        ],
    80  =>
        [
            'match'   => 'E056 0A38',
            'replace' => 'E099',
        ],
    81  =>
        [
            'match'   => 'E056 0A39',
            'replace' => 'E09A',
        ],
    82  =>
        [
            'match'   => 'E056 0A59',
            'replace' => 'E09B',
        ],
    83  =>
        [
            'match'   => 'E056 0A5A',
            'replace' => 'E09C',
        ],
    84  =>
        [
            'match'   => 'E056 0A5B',
            'replace' => 'E09D',
        ],
    85  =>
        [
            'match'   => 'E056 0A5C',
            'replace' => 'E09E',
        ],
    86  =>
        [
            'match'   => 'E056 0A5E',
            'replace' => 'E09F',
        ],
    87  =>
        [
            'match'   => 'E056 0A33',
            'replace' => 'E0BB',
        ],
    88  =>
        [
            'match'   => 'E07B ((E07B|E07C|E07D|E07E|E07F|E080|E081|E082|E083|E084|E085|E086|E087|E088|E089|E08A|E08B|E08C|E08D|E08E|E08F|E090|E091|E092|E093|E094|E095|E096|E097|E098|E099|E09A|E09B|E09C|E09D|E09E|E09F))',
            'replace' => '0A4D 0A15 \\1',
        ],
    89  =>
        [
            'match'   => 'E07C ((E07B|E07C|E07D|E07E|E07F|E080|E081|E082|E083|E084|E085|E086|E087|E088|E089|E08A|E08B|E08C|E08D|E08E|E08F|E090|E091|E092|E093|E094|E095|E096|E097|E098|E099|E09A|E09B|E09C|E09D|E09E|E09F))',
            'replace' => '0A4D 0A16 \\1',
        ],
    90  =>
        [
            'match'   => 'E07D ((E07B|E07C|E07D|E07E|E07F|E080|E081|E082|E083|E084|E085|E086|E087|E088|E089|E08A|E08B|E08C|E08D|E08E|E08F|E090|E091|E092|E093|E094|E095|E096|E097|E098|E099|E09A|E09B|E09C|E09D|E09E|E09F))',
            'replace' => '0A4D 0A17 \\1',
        ],
    91  =>
        [
            'match'   => 'E07E ((E07B|E07C|E07D|E07E|E07F|E080|E081|E082|E083|E084|E085|E086|E087|E088|E089|E08A|E08B|E08C|E08D|E08E|E08F|E090|E091|E092|E093|E094|E095|E096|E097|E098|E099|E09A|E09B|E09C|E09D|E09E|E09F))',
            'replace' => '0A4D 0A18 \\1',
        ],
    92  =>
        [
            'match'   => 'E07F ((E07B|E07C|E07D|E07E|E07F|E080|E081|E082|E083|E084|E085|E086|E087|E088|E089|E08A|E08B|E08C|E08D|E08E|E08F|E090|E091|E092|E093|E094|E095|E096|E097|E098|E099|E09A|E09B|E09C|E09D|E09E|E09F))',
            'replace' => '0A4D 0A19 \\1',
        ],
    93  =>
        [
            'match'   => 'E080 ((E07B|E07C|E07D|E07E|E07F|E080|E081|E082|E083|E084|E085|E086|E087|E088|E089|E08A|E08B|E08C|E08D|E08E|E08F|E090|E091|E092|E093|E094|E095|E096|E097|E098|E099|E09A|E09B|E09C|E09D|E09E|E09F))',
            'replace' => '0A4D 0A1A \\1',
        ],
    94  =>
        [
            'match'   => 'E081 ((E07B|E07C|E07D|E07E|E07F|E080|E081|E082|E083|E084|E085|E086|E087|E088|E089|E08A|E08B|E08C|E08D|E08E|E08F|E090|E091|E092|E093|E094|E095|E096|E097|E098|E099|E09A|E09B|E09C|E09D|E09E|E09F))',
            'replace' => '0A4D 0A1B \\1',
        ],
    95  =>
        [
            'match'   => 'E082 ((E07B|E07C|E07D|E07E|E07F|E080|E081|E082|E083|E084|E085|E086|E087|E088|E089|E08A|E08B|E08C|E08D|E08E|E08F|E090|E091|E092|E093|E094|E095|E096|E097|E098|E099|E09A|E09B|E09C|E09D|E09E|E09F))',
            'replace' => '0A4D 0A1C \\1',
        ],
    96  =>
        [
            'match'   => 'E083 ((E07B|E07C|E07D|E07E|E07F|E080|E081|E082|E083|E084|E085|E086|E087|E088|E089|E08A|E08B|E08C|E08D|E08E|E08F|E090|E091|E092|E093|E094|E095|E096|E097|E098|E099|E09A|E09B|E09C|E09D|E09E|E09F))',
            'replace' => '0A4D 0A1D \\1',
        ],
    97  =>
        [
            'match'   => 'E084 ((E07B|E07C|E07D|E07E|E07F|E080|E081|E082|E083|E084|E085|E086|E087|E088|E089|E08A|E08B|E08C|E08D|E08E|E08F|E090|E091|E092|E093|E094|E095|E096|E097|E098|E099|E09A|E09B|E09C|E09D|E09E|E09F))',
            'replace' => '0A4D 0A1E \\1',
        ],
    98  =>
        [
            'match'   => 'E085 ((E07B|E07C|E07D|E07E|E07F|E080|E081|E082|E083|E084|E085|E086|E087|E088|E089|E08A|E08B|E08C|E08D|E08E|E08F|E090|E091|E092|E093|E094|E095|E096|E097|E098|E099|E09A|E09B|E09C|E09D|E09E|E09F))',
            'replace' => '0A4D 0A1F \\1',
        ],
    99  =>
        [
            'match'   => 'E086 ((E07B|E07C|E07D|E07E|E07F|E080|E081|E082|E083|E084|E085|E086|E087|E088|E089|E08A|E08B|E08C|E08D|E08E|E08F|E090|E091|E092|E093|E094|E095|E096|E097|E098|E099|E09A|E09B|E09C|E09D|E09E|E09F))',
            'replace' => '0A4D 0A20 \\1',
        ],
    100 =>
        [
            'match'   => 'E087 ((E07B|E07C|E07D|E07E|E07F|E080|E081|E082|E083|E084|E085|E086|E087|E088|E089|E08A|E08B|E08C|E08D|E08E|E08F|E090|E091|E092|E093|E094|E095|E096|E097|E098|E099|E09A|E09B|E09C|E09D|E09E|E09F))',
            'replace' => '0A4D 0A21 \\1',
        ],
    101 =>
        [
            'match'   => 'E088 ((E07B|E07C|E07D|E07E|E07F|E080|E081|E082|E083|E084|E085|E086|E087|E088|E089|E08A|E08B|E08C|E08D|E08E|E08F|E090|E091|E092|E093|E094|E095|E096|E097|E098|E099|E09A|E09B|E09C|E09D|E09E|E09F))',
            'replace' => '0A4D 0A22 \\1',
        ],
    102 =>
        [
            'match'   => 'E089 ((E07B|E07C|E07D|E07E|E07F|E080|E081|E082|E083|E084|E085|E086|E087|E088|E089|E08A|E08B|E08C|E08D|E08E|E08F|E090|E091|E092|E093|E094|E095|E096|E097|E098|E099|E09A|E09B|E09C|E09D|E09E|E09F))',
            'replace' => '0A4D 0A23 \\1',
        ],
    103 =>
        [
            'match'   => 'E08A ((E07B|E07C|E07D|E07E|E07F|E080|E081|E082|E083|E084|E085|E086|E087|E088|E089|E08A|E08B|E08C|E08D|E08E|E08F|E090|E091|E092|E093|E094|E095|E096|E097|E098|E099|E09A|E09B|E09C|E09D|E09E|E09F))',
            'replace' => '0A4D 0A24 \\1',
        ],
    104 =>
        [
            'match'   => 'E08B ((E07B|E07C|E07D|E07E|E07F|E080|E081|E082|E083|E084|E085|E086|E087|E088|E089|E08A|E08B|E08C|E08D|E08E|E08F|E090|E091|E092|E093|E094|E095|E096|E097|E098|E099|E09A|E09B|E09C|E09D|E09E|E09F))',
            'replace' => '0A4D 0A25 \\1',
        ],
    105 =>
        [
            'match'   => 'E08C ((E07B|E07C|E07D|E07E|E07F|E080|E081|E082|E083|E084|E085|E086|E087|E088|E089|E08A|E08B|E08C|E08D|E08E|E08F|E090|E091|E092|E093|E094|E095|E096|E097|E098|E099|E09A|E09B|E09C|E09D|E09E|E09F))',
            'replace' => '0A4D 0A26 \\1',
        ],
    106 =>
        [
            'match'   => 'E08D ((E07B|E07C|E07D|E07E|E07F|E080|E081|E082|E083|E084|E085|E086|E087|E088|E089|E08A|E08B|E08C|E08D|E08E|E08F|E090|E091|E092|E093|E094|E095|E096|E097|E098|E099|E09A|E09B|E09C|E09D|E09E|E09F))',
            'replace' => '0A4D 0A27 \\1',
        ],
    107 =>
        [
            'match'   => 'E08E ((E07B|E07C|E07D|E07E|E07F|E080|E081|E082|E083|E084|E085|E086|E087|E088|E089|E08A|E08B|E08C|E08D|E08E|E08F|E090|E091|E092|E093|E094|E095|E096|E097|E098|E099|E09A|E09B|E09C|E09D|E09E|E09F))',
            'replace' => '0A4D 0A28 \\1',
        ],
    108 =>
        [
            'match'   => 'E08F ((E07B|E07C|E07D|E07E|E07F|E080|E081|E082|E083|E084|E085|E086|E087|E088|E089|E08A|E08B|E08C|E08D|E08E|E08F|E090|E091|E092|E093|E094|E095|E096|E097|E098|E099|E09A|E09B|E09C|E09D|E09E|E09F))',
            'replace' => '0A4D 0A2A \\1',
        ],
    109 =>
        [
            'match'   => 'E090 ((E07B|E07C|E07D|E07E|E07F|E080|E081|E082|E083|E084|E085|E086|E087|E088|E089|E08A|E08B|E08C|E08D|E08E|E08F|E090|E091|E092|E093|E094|E095|E096|E097|E098|E099|E09A|E09B|E09C|E09D|E09E|E09F))',
            'replace' => '0A4D 0A2B \\1',
        ],
    110 =>
        [
            'match'   => 'E091 ((E07B|E07C|E07D|E07E|E07F|E080|E081|E082|E083|E084|E085|E086|E087|E088|E089|E08A|E08B|E08C|E08D|E08E|E08F|E090|E091|E092|E093|E094|E095|E096|E097|E098|E099|E09A|E09B|E09C|E09D|E09E|E09F))',
            'replace' => '0A4D 0A2C \\1',
        ],
    111 =>
        [
            'match'   => 'E092 ((E07B|E07C|E07D|E07E|E07F|E080|E081|E082|E083|E084|E085|E086|E087|E088|E089|E08A|E08B|E08C|E08D|E08E|E08F|E090|E091|E092|E093|E094|E095|E096|E097|E098|E099|E09A|E09B|E09C|E09D|E09E|E09F))',
            'replace' => '0A4D 0A2D \\1',
        ],
    112 =>
        [
            'match'   => 'E093 ((E07B|E07C|E07D|E07E|E07F|E080|E081|E082|E083|E084|E085|E086|E087|E088|E089|E08A|E08B|E08C|E08D|E08E|E08F|E090|E091|E092|E093|E094|E095|E096|E097|E098|E099|E09A|E09B|E09C|E09D|E09E|E09F))',
            'replace' => '0A4D 0A2E \\1',
        ],
    113 =>
        [
            'match'   => 'E094 ((E07B|E07C|E07D|E07E|E07F|E080|E081|E082|E083|E084|E085|E086|E087|E088|E089|E08A|E08B|E08C|E08D|E08E|E08F|E090|E091|E092|E093|E094|E095|E096|E097|E098|E099|E09A|E09B|E09C|E09D|E09E|E09F))',
            'replace' => '0A4D 0A2F \\1',
        ],
    114 =>
        [
            'match'   => 'E095 ((E07B|E07C|E07D|E07E|E07F|E080|E081|E082|E083|E084|E085|E086|E087|E088|E089|E08A|E08B|E08C|E08D|E08E|E08F|E090|E091|E092|E093|E094|E095|E096|E097|E098|E099|E09A|E09B|E09C|E09D|E09E|E09F))',
            'replace' => '0A4D 0A30 \\1',
        ],
    115 =>
        [
            'match'   => 'E096 ((E07B|E07C|E07D|E07E|E07F|E080|E081|E082|E083|E084|E085|E086|E087|E088|E089|E08A|E08B|E08C|E08D|E08E|E08F|E090|E091|E092|E093|E094|E095|E096|E097|E098|E099|E09A|E09B|E09C|E09D|E09E|E09F))',
            'replace' => '0A4D 0A32 \\1',
        ],
    116 =>
        [
            'match'   => 'E097 ((E07B|E07C|E07D|E07E|E07F|E080|E081|E082|E083|E084|E085|E086|E087|E088|E089|E08A|E08B|E08C|E08D|E08E|E08F|E090|E091|E092|E093|E094|E095|E096|E097|E098|E099|E09A|E09B|E09C|E09D|E09E|E09F))',
            'replace' => '0A4D 0A35 \\1',
        ],
    117 =>
        [
            'match'   => 'E098 ((E07B|E07C|E07D|E07E|E07F|E080|E081|E082|E083|E084|E085|E086|E087|E088|E089|E08A|E08B|E08C|E08D|E08E|E08F|E090|E091|E092|E093|E094|E095|E096|E097|E098|E099|E09A|E09B|E09C|E09D|E09E|E09F))',
            'replace' => '0A4D 0A36 \\1',
        ],
    118 =>
        [
            'match'   => 'E099 ((E07B|E07C|E07D|E07E|E07F|E080|E081|E082|E083|E084|E085|E086|E087|E088|E089|E08A|E08B|E08C|E08D|E08E|E08F|E090|E091|E092|E093|E094|E095|E096|E097|E098|E099|E09A|E09B|E09C|E09D|E09E|E09F))',
            'replace' => '0A4D 0A38 \\1',
        ],
    119 =>
        [
            'match'   => 'E09A ((E07B|E07C|E07D|E07E|E07F|E080|E081|E082|E083|E084|E085|E086|E087|E088|E089|E08A|E08B|E08C|E08D|E08E|E08F|E090|E091|E092|E093|E094|E095|E096|E097|E098|E099|E09A|E09B|E09C|E09D|E09E|E09F))',
            'replace' => '0A4D 0A39 \\1',
        ],
    120 =>
        [
            'match'   => 'E09B ((E07B|E07C|E07D|E07E|E07F|E080|E081|E082|E083|E084|E085|E086|E087|E088|E089|E08A|E08B|E08C|E08D|E08E|E08F|E090|E091|E092|E093|E094|E095|E096|E097|E098|E099|E09A|E09B|E09C|E09D|E09E|E09F))',
            'replace' => '0A4D 0A59 \\1',
        ],
    121 =>
        [
            'match'   => 'E09C ((E07B|E07C|E07D|E07E|E07F|E080|E081|E082|E083|E084|E085|E086|E087|E088|E089|E08A|E08B|E08C|E08D|E08E|E08F|E090|E091|E092|E093|E094|E095|E096|E097|E098|E099|E09A|E09B|E09C|E09D|E09E|E09F))',
            'replace' => '0A4D 0A5A \\1',
        ],
    122 =>
        [
            'match'   => 'E09D ((E07B|E07C|E07D|E07E|E07F|E080|E081|E082|E083|E084|E085|E086|E087|E088|E089|E08A|E08B|E08C|E08D|E08E|E08F|E090|E091|E092|E093|E094|E095|E096|E097|E098|E099|E09A|E09B|E09C|E09D|E09E|E09F))',
            'replace' => '0A4D 0A5B \\1',
        ],
    123 =>
        [
            'match'   => 'E09E ((E07B|E07C|E07D|E07E|E07F|E080|E081|E082|E083|E084|E085|E086|E087|E088|E089|E08A|E08B|E08C|E08D|E08E|E08F|E090|E091|E092|E093|E094|E095|E096|E097|E098|E099|E09A|E09B|E09C|E09D|E09E|E09F))',
            'replace' => '0A4D 0A5C \\1',
        ],
    124 =>
        [
            'match'   => 'E09F ((E07B|E07C|E07D|E07E|E07F|E080|E081|E082|E083|E084|E085|E086|E087|E088|E089|E08A|E08B|E08C|E08D|E08E|E08F|E090|E091|E092|E093|E094|E095|E096|E097|E098|E099|E09A|E09B|E09C|E09D|E09E|E09F))',
            'replace' => '0A4D 0A5E \\1',
        ],
    125 =>
        [
            'match'   => '(0A3F (0A15|0A16|0A17|0A18|0A19|0A1A|0A1B|0A1C|0A1D|0A1E|0A1F|0A20|0A21|0A22|0A23|0A24|0A25|0A26|0A27|0A28|0A2A|0A2B|0A2C|0A2D|0A2E|0A2F|0A30|0A32|0A33|0A35|0A36|0A38|0A39|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E|E04F|E050|E051|E052|E053|E054|0A59|0A5A|0A5B|0A5C|0A5E)) 0A4D',
            'replace' => '\\1 0A4D 0A3F',
        ],
    126 =>
        [
            'match'   => '0A3F 0A15 (0A4D 0A3F)',
            'replace' => '0A15 \\1',
        ],
    127 =>
        [
            'match'   => '0A3F 0A16 (0A4D 0A3F)',
            'replace' => '0A16 \\1',
        ],
    128 =>
        [
            'match'   => '0A3F 0A17 (0A4D 0A3F)',
            'replace' => '0A17 \\1',
        ],
    129 =>
        [
            'match'   => '0A3F 0A18 (0A4D 0A3F)',
            'replace' => '0A18 \\1',
        ],
    130 =>
        [
            'match'   => '0A3F 0A19 (0A4D 0A3F)',
            'replace' => '0A19 \\1',
        ],
    131 =>
        [
            'match'   => '0A3F 0A1A (0A4D 0A3F)',
            'replace' => '0A1A \\1',
        ],
    132 =>
        [
            'match'   => '0A3F 0A1B (0A4D 0A3F)',
            'replace' => '0A1B \\1',
        ],
    133 =>
        [
            'match'   => '0A3F 0A1C (0A4D 0A3F)',
            'replace' => '0A1C \\1',
        ],
    134 =>
        [
            'match'   => '0A3F 0A1D (0A4D 0A3F)',
            'replace' => '0A1D \\1',
        ],
    135 =>
        [
            'match'   => '0A3F 0A1E (0A4D 0A3F)',
            'replace' => '0A1E \\1',
        ],
    136 =>
        [
            'match'   => '0A3F 0A1F (0A4D 0A3F)',
            'replace' => '0A1F \\1',
        ],
    137 =>
        [
            'match'   => '0A3F 0A20 (0A4D 0A3F)',
            'replace' => '0A20 \\1',
        ],
    138 =>
        [
            'match'   => '0A3F 0A21 (0A4D 0A3F)',
            'replace' => '0A21 \\1',
        ],
    139 =>
        [
            'match'   => '0A3F 0A22 (0A4D 0A3F)',
            'replace' => '0A22 \\1',
        ],
    140 =>
        [
            'match'   => '0A3F 0A23 (0A4D 0A3F)',
            'replace' => '0A23 \\1',
        ],
    141 =>
        [
            'match'   => '0A3F 0A24 (0A4D 0A3F)',
            'replace' => '0A24 \\1',
        ],
    142 =>
        [
            'match'   => '0A3F 0A25 (0A4D 0A3F)',
            'replace' => '0A25 \\1',
        ],
    143 =>
        [
            'match'   => '0A3F 0A26 (0A4D 0A3F)',
            'replace' => '0A26 \\1',
        ],
    144 =>
        [
            'match'   => '0A3F 0A27 (0A4D 0A3F)',
            'replace' => '0A27 \\1',
        ],
    145 =>
        [
            'match'   => '0A3F 0A28 (0A4D 0A3F)',
            'replace' => '0A28 \\1',
        ],
    146 =>
        [
            'match'   => '0A3F 0A2A (0A4D 0A3F)',
            'replace' => '0A2A \\1',
        ],
    147 =>
        [
            'match'   => '0A3F 0A2B (0A4D 0A3F)',
            'replace' => '0A2B \\1',
        ],
    148 =>
        [
            'match'   => '0A3F 0A2C (0A4D 0A3F)',
            'replace' => '0A2C \\1',
        ],
    149 =>
        [
            'match'   => '0A3F 0A2D (0A4D 0A3F)',
            'replace' => '0A2D \\1',
        ],
    150 =>
        [
            'match'   => '0A3F 0A2E (0A4D 0A3F)',
            'replace' => '0A2E \\1',
        ],
    151 =>
        [
            'match'   => '0A3F 0A2F (0A4D 0A3F)',
            'replace' => '0A2F \\1',
        ],
    152 =>
        [
            'match'   => '0A3F 0A30 (0A4D 0A3F)',
            'replace' => '0A30 \\1',
        ],
    153 =>
        [
            'match'   => '0A3F 0A32 (0A4D 0A3F)',
            'replace' => '0A32 \\1',
        ],
    154 =>
        [
            'match'   => '0A3F 0A33 (0A4D 0A3F)',
            'replace' => '0A33 \\1',
        ],
    155 =>
        [
            'match'   => '0A3F 0A35 (0A4D 0A3F)',
            'replace' => '0A35 \\1',
        ],
    156 =>
        [
            'match'   => '0A3F 0A36 (0A4D 0A3F)',
            'replace' => '0A36 \\1',
        ],
    157 =>
        [
            'match'   => '0A3F 0A38 (0A4D 0A3F)',
            'replace' => '0A38 \\1',
        ],
    158 =>
        [
            'match'   => '0A3F 0A39 (0A4D 0A3F)',
            'replace' => '0A39 \\1',
        ],
    159 =>
        [
            'match'   => '0A3F E034 (0A4D 0A3F)',
            'replace' => 'E034 \\1',
        ],
    160 =>
        [
            'match'   => '0A3F E035 (0A4D 0A3F)',
            'replace' => 'E035 \\1',
        ],
    161 =>
        [
            'match'   => '0A3F E036 (0A4D 0A3F)',
            'replace' => 'E036 \\1',
        ],
    162 =>
        [
            'match'   => '0A3F E037 (0A4D 0A3F)',
            'replace' => 'E037 \\1',
        ],
    163 =>
        [
            'match'   => '0A3F E038 (0A4D 0A3F)',
            'replace' => 'E038 \\1',
        ],
    164 =>
        [
            'match'   => '0A3F E039 (0A4D 0A3F)',
            'replace' => 'E039 \\1',
        ],
    165 =>
        [
            'match'   => '0A3F E03A (0A4D 0A3F)',
            'replace' => 'E03A \\1',
        ],
    166 =>
        [
            'match'   => '0A3F E03B (0A4D 0A3F)',
            'replace' => 'E03B \\1',
        ],
    167 =>
        [
            'match'   => '0A3F E03C (0A4D 0A3F)',
            'replace' => 'E03C \\1',
        ],
    168 =>
        [
            'match'   => '0A3F E03D (0A4D 0A3F)',
            'replace' => 'E03D \\1',
        ],
    169 =>
        [
            'match'   => '0A3F E03E (0A4D 0A3F)',
            'replace' => 'E03E \\1',
        ],
    170 =>
        [
            'match'   => '0A3F E03F (0A4D 0A3F)',
            'replace' => 'E03F \\1',
        ],
    171 =>
        [
            'match'   => '0A3F E040 (0A4D 0A3F)',
            'replace' => 'E040 \\1',
        ],
    172 =>
        [
            'match'   => '0A3F E041 (0A4D 0A3F)',
            'replace' => 'E041 \\1',
        ],
    173 =>
        [
            'match'   => '0A3F E042 (0A4D 0A3F)',
            'replace' => 'E042 \\1',
        ],
    174 =>
        [
            'match'   => '0A3F E043 (0A4D 0A3F)',
            'replace' => 'E043 \\1',
        ],
    175 =>
        [
            'match'   => '0A3F E044 (0A4D 0A3F)',
            'replace' => 'E044 \\1',
        ],
    176 =>
        [
            'match'   => '0A3F E045 (0A4D 0A3F)',
            'replace' => 'E045 \\1',
        ],
    177 =>
        [
            'match'   => '0A3F E046 (0A4D 0A3F)',
            'replace' => 'E046 \\1',
        ],
    178 =>
        [
            'match'   => '0A3F E047 (0A4D 0A3F)',
            'replace' => 'E047 \\1',
        ],
    179 =>
        [
            'match'   => '0A3F E048 (0A4D 0A3F)',
            'replace' => 'E048 \\1',
        ],
    180 =>
        [
            'match'   => '0A3F E049 (0A4D 0A3F)',
            'replace' => 'E049 \\1',
        ],
    181 =>
        [
            'match'   => '0A3F E04A (0A4D 0A3F)',
            'replace' => 'E04A \\1',
        ],
    182 =>
        [
            'match'   => '0A3F E04B (0A4D 0A3F)',
            'replace' => 'E04B \\1',
        ],
    183 =>
        [
            'match'   => '0A3F E04C (0A4D 0A3F)',
            'replace' => 'E04C \\1',
        ],
    184 =>
        [
            'match'   => '0A3F E04D (0A4D 0A3F)',
            'replace' => 'E04D \\1',
        ],
    185 =>
        [
            'match'   => '0A3F E04E (0A4D 0A3F)',
            'replace' => 'E04E \\1',
        ],
    186 =>
        [
            'match'   => '0A3F E04F (0A4D 0A3F)',
            'replace' => 'E04F \\1',
        ],
    187 =>
        [
            'match'   => '0A3F E050 (0A4D 0A3F)',
            'replace' => 'E050 \\1',
        ],
    188 =>
        [
            'match'   => '0A3F E051 (0A4D 0A3F)',
            'replace' => 'E051 \\1',
        ],
    189 =>
        [
            'match'   => '0A3F E052 (0A4D 0A3F)',
            'replace' => 'E052 \\1',
        ],
    190 =>
        [
            'match'   => '0A3F E053 (0A4D 0A3F)',
            'replace' => 'E053 \\1',
        ],
    191 =>
        [
            'match'   => '0A3F E054 (0A4D 0A3F)',
            'replace' => 'E054 \\1',
        ],
    192 =>
        [
            'match'   => '0A3F 0A59 (0A4D 0A3F)',
            'replace' => '0A59 \\1',
        ],
    193 =>
        [
            'match'   => '0A3F 0A5A (0A4D 0A3F)',
            'replace' => '0A5A \\1',
        ],
    194 =>
        [
            'match'   => '0A3F 0A5B (0A4D 0A3F)',
            'replace' => '0A5B \\1',
        ],
    195 =>
        [
            'match'   => '0A3F 0A5C (0A4D 0A3F)',
            'replace' => '0A5C \\1',
        ],
    196 =>
        [
            'match'   => '0A3F 0A5E (0A4D 0A3F)',
            'replace' => '0A5E \\1',
        ],
    197 =>
        [
            'match'   => 'E055',
            'replace' => '0A4D',
        ],
    198 =>
        [
            'match'   => 'E056',
            'replace' => '0A4D',
        ],
    199 =>
        [
            'match'   => 'E057',
            'replace' => '0A4D',
        ],
    200 =>
        [
            'match'   => '0A15 0A4D',
            'replace' => 'E004',
        ],
    201 =>
        [
            'match'   => '0A16 0A4D',
            'replace' => 'E005',
        ],
    202 =>
        [
            'match'   => '0A17 0A4D',
            'replace' => 'E006',
        ],
    203 =>
        [
            'match'   => '0A18 0A4D',
            'replace' => 'E007',
        ],
    204 =>
        [
            'match'   => '0A19 0A4D',
            'replace' => 'E008',
        ],
    205 =>
        [
            'match'   => '0A1A 0A4D',
            'replace' => 'E009',
        ],
    206 =>
        [
            'match'   => '0A1B 0A4D',
            'replace' => 'E00A',
        ],
    207 =>
        [
            'match'   => '0A1C 0A4D',
            'replace' => 'E00B',
        ],
    208 =>
        [
            'match'   => '0A1D 0A4D',
            'replace' => 'E00C',
        ],
    209 =>
        [
            'match'   => '0A1E 0A4D',
            'replace' => 'E00D',
        ],
    210 =>
        [
            'match'   => '0A1F 0A4D',
            'replace' => 'E00E',
        ],
    211 =>
        [
            'match'   => '0A20 0A4D',
            'replace' => 'E00F',
        ],
    212 =>
        [
            'match'   => '0A21 0A4D',
            'replace' => 'E010',
        ],
    213 =>
        [
            'match'   => '0A22 0A4D',
            'replace' => 'E011',
        ],
    214 =>
        [
            'match'   => '0A23 0A4D',
            'replace' => 'E012',
        ],
    215 =>
        [
            'match'   => '0A24 0A4D',
            'replace' => 'E013',
        ],
    216 =>
        [
            'match'   => '0A25 0A4D',
            'replace' => 'E014',
        ],
    217 =>
        [
            'match'   => '0A26 0A4D',
            'replace' => 'E015',
        ],
    218 =>
        [
            'match'   => '0A27 0A4D',
            'replace' => 'E016',
        ],
    219 =>
        [
            'match'   => '0A28 0A4D',
            'replace' => 'E017',
        ],
    220 =>
        [
            'match'   => '0A2A 0A4D',
            'replace' => 'E018',
        ],
    221 =>
        [
            'match'   => '0A2B 0A4D',
            'replace' => 'E019',
        ],
    222 =>
        [
            'match'   => '0A2C 0A4D',
            'replace' => 'E01A',
        ],
    223 =>
        [
            'match'   => '0A2D 0A4D',
            'replace' => 'E01B',
        ],
    224 =>
        [
            'match'   => '0A2E 0A4D',
            'replace' => 'E01C',
        ],
    225 =>
        [
            'match'   => '0A2F 0A4D',
            'replace' => 'E01D',
        ],
    226 =>
        [
            'match'   => '0A30 0A4D',
            'replace' => 'E01E',
        ],
    227 =>
        [
            'match'   => '0A32 0A4D',
            'replace' => 'E01F',
        ],
    228 =>
        [
            'match'   => '0A33 0A4D',
            'replace' => 'E020',
        ],
    229 =>
        [
            'match'   => '0A35 0A4D',
            'replace' => 'E021',
        ],
    230 =>
        [
            'match'   => '0A36 0A4D',
            'replace' => 'E022',
        ],
    231 =>
        [
            'match'   => '0A38 0A4D',
            'replace' => 'E023',
        ],
    232 =>
        [
            'match'   => '0A39 0A4D',
            'replace' => 'E024',
        ],
    233 =>
        [
            'match'   => '0A59 0A4D',
            'replace' => 'E025',
        ],
    234 =>
        [
            'match'   => '0A5A 0A4D',
            'replace' => 'E026',
        ],
    235 =>
        [
            'match'   => '0A5B 0A4D',
            'replace' => 'E027',
        ],
    236 =>
        [
            'match'   => '0A5C 0A4D',
            'replace' => 'E028',
        ],
    237 =>
        [
            'match'   => '0A5E 0A4D',
            'replace' => 'E029',
        ],
    238 =>
        [
            'match'   => '((E07B|E07C|E07D|E07E|E07F|E080|E081|E082|E083|E084|E085|E086|E087|E088|E089|E08A|E08B|E08C|E08D|E08E|E08F|E090|E091|E092|E093|E094|E095|E096|E097|E098|E099|E09A|E09B|E09C|E09D|E09E|E09F|E0BB)) 0A41',
            'replace' => '\\1 E002',
        ],
    239 =>
        [
            'match'   => '((E07B|E07C|E07D|E07E|E07F|E080|E081|E082|E083|E084|E085|E086|E087|E088|E089|E08A|E08B|E08C|E08D|E08E|E08F|E090|E091|E092|E093|E094|E095|E096|E097|E098|E099|E09A|E09B|E09C|E09D|E09E|E09F|E0BB)) 0A42',
            'replace' => '\\1 E003',
        ],
    240 =>
        [
            'match'   => '0A2F 0A4D',
            'replace' => 'E0A0',
        ],
    241 =>
        [
            'match'   => '0A09 0A02',
            'replace' => 'E0A1',
        ],
    242 =>
        [
            'match'   => '0A09 0A70',
            'replace' => 'E0A1',
        ],
    243 =>
        [
            'match'   => '0A0A 0A02',
            'replace' => 'E0A2',
        ],
    244 =>
        [
            'match'   => '0A0A 0A70',
            'replace' => 'E0A2',
        ],
    245 =>
        [
            'match'   => '0A13 0A02',
            'replace' => 'E0A3',
        ],
    246 =>
        [
            'match'   => '0A13 0A70',
            'replace' => 'E0A3',
        ],
    247 =>
        [
            'match'   => '0A05 0A02',
            'replace' => 'E0B4',
        ],
    248 =>
        [
            'match'   => '0A05 0A70',
            'replace' => 'E0B4',
        ],
    249 =>
        [
            'match'   => '0A06 0A02',
            'replace' => 'E0B5',
        ],
    250 =>
        [
            'match'   => '0A06 0A70',
            'replace' => 'E0B5',
        ],
    251 =>
        [
            'match'   => '0A07 0A02',
            'replace' => 'E0B6',
        ],
    252 =>
        [
            'match'   => '0A07 0A70',
            'replace' => 'E0B6',
        ],
    253 =>
        [
            'match'   => '0A08 0A02',
            'replace' => 'E0B7',
        ],
    254 =>
        [
            'match'   => '0A08 0A70',
            'replace' => 'E0B7',
        ],
    255 =>
        [
            'match'   => '0A0F 0A02',
            'replace' => 'E0B8',
        ],
    256 =>
        [
            'match'   => '0A0F 0A70',
            'replace' => 'E0B8',
        ],
    257 =>
        [
            'match'   => '0A10 0A02',
            'replace' => 'E0B9',
        ],
    258 =>
        [
            'match'   => '0A10 0A70',
            'replace' => 'E0B9',
        ],
    259 =>
        [
            'match'   => '0A14 0A02',
            'replace' => 'E0BA',
        ],
    260 =>
        [
            'match'   => '0A14 0A70',
            'replace' => 'E0BA',
        ],
    261 =>
        [
            'match'   => '((0A15|0A16|0A17|0A18|0A19|0A1A|0A1B|0A1C|0A1D|0A1E|0A1F|0A20|0A21|0A22|0A23|0A24|0A25|0A26|0A27|0A28|0A2A|0A2B|0A2C|0A2D|0A2E|0A2F|0A30|0A32|0A33|0A35|0A36|0A38|0A39|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E|E04F|E050|E051|E052|E053|E054|0A59|0A5A|0A5B|0A5C|0A5E)) 0A02',
            'replace' => '\\1 0A70',
        ],
    262 =>
        [
            'match'   => '((E004|E005|E006|E007|E008|E009|E00A|E00B|E00C|E00D|E00E|E00F|E010|E011|E012|E013|E014|E015|E016|E017|E018|E019|E01A|E01B|E01C|E01D|E01E|E01F|E020|E021|E022|E023|E024|E025|E026|E027|E028|E029|E055|E056|E057|E058|E059|E05A|E05B|E05C|E05D|E05E|E05F|E060|E061|E062|E063|E064|E065|E066|E067|E068|E069|E06A|E06B|E06C|E06D|E06E|E06F|E070|E071|E072|E073|E074|E075|E076|E077|E078|E079|E07A|E02A|E02C)) 0A02',
            'replace' => '\\1 0A70',
        ],
    263 =>
        [
            'match'   => '((E07B|E07C|E07D|E07E|E07F|E080|E081|E082|E083|E084|E085|E086|E087|E088|E089|E08A|E08B|E08C|E08D|E08E|E08F|E090|E091|E092|E093|E094|E095|E096|E097|E098|E099|E09A|E09B|E09C|E09D|E09E|E09F|E0BB)) 0A02',
            'replace' => '\\1 0A70',
        ],
    264 =>
        [
            'match'   => '(0A41) 0A02',
            'replace' => '\\1 0A70',
        ],
    265 =>
        [
            'match'   => '(0A42) 0A02',
            'replace' => '\\1 0A70',
        ],
    266 =>
        [
            'match'   => '(E002) 0A02',
            'replace' => '\\1 0A70',
        ],
    267 =>
        [
            'match'   => '(E003) 0A02',
            'replace' => '\\1 0A70',
        ],
    268 =>
        [
            'match'   => '0A3E 0A02',
            'replace' => 'E0A8',
        ],
    269 =>
        [
            'match'   => '0A3E 0A70',
            'replace' => 'E0A8',
        ],
    270 =>
        [
            'match'   => '0A40 0A02',
            'replace' => 'E0A9',
        ],
    271 =>
        [
            'match'   => '0A40 0A70',
            'replace' => 'E0A9',
        ],
    272 =>
        [
            'match'   => '0A47 0A02',
            'replace' => 'E0AA',
        ],
    273 =>
        [
            'match'   => '0A47 0A70',
            'replace' => 'E0AA',
        ],
    274 =>
        [
            'match'   => '0A48 0A02',
            'replace' => 'E0AB',
        ],
    275 =>
        [
            'match'   => '0A48 0A70',
            'replace' => 'E0AB',
        ],
    276 =>
        [
            'match'   => '0A4B 0A02',
            'replace' => 'E0AC',
        ],
    277 =>
        [
            'match'   => '0A4B 0A70',
            'replace' => 'E0AC',
        ],
    278 =>
        [
            'match'   => '0A4C 0A02',
            'replace' => 'E0AD',
        ],
    279 =>
        [
            'match'   => '0A4C 0A70',
            'replace' => 'E0AD',
        ],
    280 =>
        [
            'match'   => '0A3E 0A01',
            'replace' => 'E0AE',
        ],
    281 =>
        [
            'match'   => '0A40 0A01',
            'replace' => 'E0AF',
        ],
    282 =>
        [
            'match'   => '0A47 0A01',
            'replace' => 'E0B0',
        ],
    283 =>
        [
            'match'   => '0A48 0A01',
            'replace' => 'E0B1',
        ],
    284 =>
        [
            'match'   => '0A4B 0A01',
            'replace' => 'E0B2',
        ],
    285 =>
        [
            'match'   => '0A4C 0A01',
            'replace' => 'E0B3',
        ],
    286 =>
        [
            'match'   => '((0A08|0A0F|0A10|0A13|0A14|E0B6|E0B7|E0B8|E0B9|E0BA)) 0A01',
            'replace' => '\\1 E0A5',
        ],
    287 =>
        [
            'match'   => '((E0A8|E0A9|E0AA|E0AB|E0AC|E0AD)) 0A01',
            'replace' => '\\1 E0A5',
        ],
];
?>