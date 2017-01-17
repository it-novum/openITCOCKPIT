<?php
$volt = [
    0   =>
        [
            'match'   => '0915 094D 0937',
            'replace' => 'E028',
        ],
    1   =>
        [
            'match'   => '091C 094D 091E',
            'replace' => 'E029',
        ],
    2   =>
        [
            'match'   => '0926 094D 092F',
            'replace' => 'E128',
        ],
    3   =>
        [
            'match'   => '094D (200D)',
            'replace' => 'E00E \\1',
        ],
    4   =>
        [
            'match'   => '094D ((0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029))',
            'replace' => 'E00E \\1',
        ],
    5   =>
        [
            'match'   => '094D (25CC)',
            'replace' => 'E00E \\1',
        ],
    6   =>
        [
            'match'   => '094D 200C',
            'replace' => 'E00C',
        ],
    7   =>
        [
            'match'   => '((0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029|0958|0959|095A|E02A|E02B|E02C|E02D|095B|E02E|E02F|E030|E031|095C|095D|E032|E033|E034|E035|E036|0929|E037|095E|E038|E039|E03A|095F|0931|E03B|0934|E03C|E03D|E03E|E03F|E040|E041|E042|E08B|E08C|E08D|E08E|E08F|E090|E091|E092|E093|E094|E095|E096|E097|E098|E099|E09A|E09B|E09C|E09D|E09E|E09F|E0A0|E0A1|E0A2|E0A3|E0A4|E0A5|E0A6|E0A7|E0A8|E0A9|E0AA|E0AB|E0AC|E0AD|E0AE|E0AF|E0B0|E0B1|E0B2|E0B3|E0B4|E0B5|E0B6|E0B7|E0B8|E0B9|E0BA|E0BB|E0BC|E0BD|E0BE|E0BF|E0C0|E0C1|E0C2|E0C3|E0C4|E0C5|E0C6|E0C7|E0C8|E0C9|E0CA|E0CB|E0CC|E0CD|E0CE|E0CF|E0D0|E0D1|E0D2|E11E|E11F|E120|E121|E122|E123|E124|E125|E126|E127|E128|E129|E12A|E12B|E12C|E12D|E12E|E12F|E130|E131|E132|E133)) E00E 0930',
            'replace' => '\\1 E013',
        ],
    8   =>
        [
            'match'   => '(093C) E00E 0930',
            'replace' => '\\1 E013',
        ],
    9   =>
        [
            'match'   => '(200D) E00E 0930',
            'replace' => '\\1 E013',
        ],
    10  =>
        [
            'match'   => '(25CC) E00E 0930',
            'replace' => '\\1 E013',
        ],
    11  =>
        [
            'match'   => '(0020) E00E 0930',
            'replace' => '\\1 E013',
        ],
    12  =>
        [
            'match'   => '((0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029|0958|0959|095A|E02A|E02B|E02C|E02D|095B|E02E|E02F|E030|E031|095C|095D|E032|E033|E034|E035|E036|0929|E037|095E|E038|E039|E03A|095F|0931|E03B|0934|E03C|E03D|E03E|E03F|E040|E041|E042|E08B|E08C|E08D|E08E|E08F|E090|E091|E092|E093|E094|E095|E096|E097|E098|E099|E09A|E09B|E09C|E09D|E09E|E09F|E0A0|E0A1|E0A2|E0A3|E0A4|E0A5|E0A6|E0A7|E0A8|E0A9|E0AA|E0AB|E0AC|E0AD|E0AE|E0AF|E0B0|E0B1|E0B2|E0B3|E0B4|E0B5|E0B6|E0B7|E0B8|E0B9|E0BA|E0BB|E0BC|E0BD|E0BE|E0BF|E0C0|E0C1|E0C2|E0C3|E0C4|E0C5|E0C6|E0C7|E0C8|E0C9|E0CA|E0CB|E0CC|E0CD|E0CE|E0CF|E0D0|E0D1|E0D2|E11E|E11F|E120|E121|E122|E123|E124|E125|E126|E127|E128|E129|E12A|E12B|E12C|E12D|E12E|E12F|E130|E131|E132|E133)) 0930 094D',
            'replace' => '\\1 E015',
        ],
    13  =>
        [
            'match'   => '((E013|E015|0941|0942|0943|0962|093E|0940|0949|094A|094B|094C|0945|0946|0947|0948|0901)) 0930 094D',
            'replace' => '\\1 E015',
        ],
    14  =>
        [
            'match'   => '(093C) 0930 094D',
            'replace' => '\\1 E015',
        ],
    15  =>
        [
            'match'   => '(25CC) 0930 094D',
            'replace' => '\\1 E015',
        ],
    16  =>
        [
            'match'   => '(E128) 0930 094D',
            'replace' => '\\1 E015',
        ],
    17  =>
        [
            'match'   => '0915 093C',
            'replace' => '0958',
        ],
    18  =>
        [
            'match'   => '0916 093C',
            'replace' => '0959',
        ],
    19  =>
        [
            'match'   => '0917 093C',
            'replace' => '095A',
        ],
    20  =>
        [
            'match'   => '0918 093C',
            'replace' => 'E02A',
        ],
    21  =>
        [
            'match'   => '0919 093C',
            'replace' => 'E02B',
        ],
    22  =>
        [
            'match'   => '091A 093C',
            'replace' => 'E02C',
        ],
    23  =>
        [
            'match'   => '091B 093C',
            'replace' => 'E02D',
        ],
    24  =>
        [
            'match'   => '091C 093C',
            'replace' => '095B',
        ],
    25  =>
        [
            'match'   => '091D 093C',
            'replace' => 'E02E',
        ],
    26  =>
        [
            'match'   => '091E 093C',
            'replace' => 'E02F',
        ],
    27  =>
        [
            'match'   => '091F 093C',
            'replace' => 'E030',
        ],
    28  =>
        [
            'match'   => '0920 093C',
            'replace' => 'E031',
        ],
    29  =>
        [
            'match'   => '0921 093C',
            'replace' => '095C',
        ],
    30  =>
        [
            'match'   => '0922 093C',
            'replace' => '095D',
        ],
    31  =>
        [
            'match'   => '0923 093C',
            'replace' => 'E032',
        ],
    32  =>
        [
            'match'   => '0924 093C',
            'replace' => 'E033',
        ],
    33  =>
        [
            'match'   => '0925 093C',
            'replace' => 'E034',
        ],
    34  =>
        [
            'match'   => '0926 093C',
            'replace' => 'E035',
        ],
    35  =>
        [
            'match'   => '0927 093C',
            'replace' => 'E036',
        ],
    36  =>
        [
            'match'   => '0928 093C',
            'replace' => '0929',
        ],
    37  =>
        [
            'match'   => '092A 093C',
            'replace' => 'E037',
        ],
    38  =>
        [
            'match'   => '092B 093C',
            'replace' => '095E',
        ],
    39  =>
        [
            'match'   => '092C 093C',
            'replace' => 'E038',
        ],
    40  =>
        [
            'match'   => '092D 093C',
            'replace' => 'E039',
        ],
    41  =>
        [
            'match'   => '092E 093C',
            'replace' => 'E03A',
        ],
    42  =>
        [
            'match'   => '092F 093C',
            'replace' => '095F',
        ],
    43  =>
        [
            'match'   => '0930 093C',
            'replace' => '0931',
        ],
    44  =>
        [
            'match'   => '0932 093C',
            'replace' => 'E03B',
        ],
    45  =>
        [
            'match'   => '0933 093C',
            'replace' => '0934',
        ],
    46  =>
        [
            'match'   => '0935 093C',
            'replace' => 'E03C',
        ],
    47  =>
        [
            'match'   => '0936 093C',
            'replace' => 'E03D',
        ],
    48  =>
        [
            'match'   => '0937 093C',
            'replace' => 'E03E',
        ],
    49  =>
        [
            'match'   => '0938 093C',
            'replace' => 'E03F',
        ],
    50  =>
        [
            'match'   => '0939 093C',
            'replace' => 'E040',
        ],
    51  =>
        [
            'match'   => 'E028 093C',
            'replace' => 'E041',
        ],
    52  =>
        [
            'match'   => 'E029 093C',
            'replace' => 'E042',
        ],
    53  =>
        [
            'match'   => '0915 E013',
            'replace' => 'E08B',
        ],
    54  =>
        [
            'match'   => '0916 E013',
            'replace' => 'E08C',
        ],
    55  =>
        [
            'match'   => '0917 E013',
            'replace' => 'E08D',
        ],
    56  =>
        [
            'match'   => '0918 E013',
            'replace' => 'E08E',
        ],
    57  =>
        [
            'match'   => '0919 E013',
            'replace' => 'E08F',
        ],
    58  =>
        [
            'match'   => '091A E013',
            'replace' => 'E090',
        ],
    59  =>
        [
            'match'   => '091B E013',
            'replace' => 'E091',
        ],
    60  =>
        [
            'match'   => '091C E013',
            'replace' => 'E092',
        ],
    61  =>
        [
            'match'   => '091D E013',
            'replace' => 'E093',
        ],
    62  =>
        [
            'match'   => '091E E013',
            'replace' => 'E094',
        ],
    63  =>
        [
            'match'   => '091F E013',
            'replace' => 'E095',
        ],
    64  =>
        [
            'match'   => '0920 E013',
            'replace' => 'E096',
        ],
    65  =>
        [
            'match'   => '0921 E013',
            'replace' => 'E097',
        ],
    66  =>
        [
            'match'   => '0922 E013',
            'replace' => 'E098',
        ],
    67  =>
        [
            'match'   => '0923 E013',
            'replace' => 'E099',
        ],
    68  =>
        [
            'match'   => '0924 E013',
            'replace' => 'E09A',
        ],
    69  =>
        [
            'match'   => '0925 E013',
            'replace' => 'E09B',
        ],
    70  =>
        [
            'match'   => '0926 E013',
            'replace' => 'E09C',
        ],
    71  =>
        [
            'match'   => '0927 E013',
            'replace' => 'E09D',
        ],
    72  =>
        [
            'match'   => '0928 E013',
            'replace' => 'E09E',
        ],
    73  =>
        [
            'match'   => '092A E013',
            'replace' => 'E09F',
        ],
    74  =>
        [
            'match'   => '092B E013',
            'replace' => 'E0A0',
        ],
    75  =>
        [
            'match'   => '092C E013',
            'replace' => 'E0A1',
        ],
    76  =>
        [
            'match'   => '092D E013',
            'replace' => 'E0A2',
        ],
    77  =>
        [
            'match'   => '092E E013',
            'replace' => 'E0A3',
        ],
    78  =>
        [
            'match'   => '092F E013',
            'replace' => 'E0A4',
        ],
    79  =>
        [
            'match'   => '0930 E013',
            'replace' => 'E0A5',
        ],
    80  =>
        [
            'match'   => '0932 E013',
            'replace' => 'E0A6',
        ],
    81  =>
        [
            'match'   => '0933 E013',
            'replace' => 'E0A7',
        ],
    82  =>
        [
            'match'   => '0935 E013',
            'replace' => 'E0A8',
        ],
    83  =>
        [
            'match'   => '0936 E013',
            'replace' => 'E0A9',
        ],
    84  =>
        [
            'match'   => '0937 E013',
            'replace' => 'E0AA',
        ],
    85  =>
        [
            'match'   => '0938 E013',
            'replace' => 'E0AB',
        ],
    86  =>
        [
            'match'   => '0939 E013',
            'replace' => 'E0AC',
        ],
    87  =>
        [
            'match'   => 'E028 E013',
            'replace' => 'E0AD',
        ],
    88  =>
        [
            'match'   => 'E029 E013',
            'replace' => 'E0AE',
        ],
    89  =>
        [
            'match'   => '0958 E013',
            'replace' => 'E0AF',
        ],
    90  =>
        [
            'match'   => '0959 E013',
            'replace' => 'E0B0',
        ],
    91  =>
        [
            'match'   => '095A E013',
            'replace' => 'E0B1',
        ],
    92  =>
        [
            'match'   => 'E02A E013',
            'replace' => 'E0B2',
        ],
    93  =>
        [
            'match'   => 'E02B E013',
            'replace' => 'E0B3',
        ],
    94  =>
        [
            'match'   => 'E02C E013',
            'replace' => 'E0B4',
        ],
    95  =>
        [
            'match'   => 'E02D E013',
            'replace' => 'E0B5',
        ],
    96  =>
        [
            'match'   => '095B E013',
            'replace' => 'E0B6',
        ],
    97  =>
        [
            'match'   => 'E02E E013',
            'replace' => 'E0B7',
        ],
    98  =>
        [
            'match'   => 'E02F E013',
            'replace' => 'E0B8',
        ],
    99  =>
        [
            'match'   => 'E030 E013',
            'replace' => 'E0B9',
        ],
    100 =>
        [
            'match'   => 'E031 E013',
            'replace' => 'E0BA',
        ],
    101 =>
        [
            'match'   => '095C E013',
            'replace' => 'E0BB',
        ],
    102 =>
        [
            'match'   => '095D E013',
            'replace' => 'E0BC',
        ],
    103 =>
        [
            'match'   => 'E032 E013',
            'replace' => 'E0BD',
        ],
    104 =>
        [
            'match'   => 'E033 E013',
            'replace' => 'E0BE',
        ],
    105 =>
        [
            'match'   => 'E034 E013',
            'replace' => 'E0BF',
        ],
    106 =>
        [
            'match'   => 'E035 E013',
            'replace' => 'E0C0',
        ],
    107 =>
        [
            'match'   => 'E036 E013',
            'replace' => 'E0C1',
        ],
    108 =>
        [
            'match'   => '0929 E013',
            'replace' => 'E0C2',
        ],
    109 =>
        [
            'match'   => 'E037 E013',
            'replace' => 'E0C3',
        ],
    110 =>
        [
            'match'   => '095E E013',
            'replace' => 'E0C4',
        ],
    111 =>
        [
            'match'   => 'E038 E013',
            'replace' => 'E0C5',
        ],
    112 =>
        [
            'match'   => 'E039 E013',
            'replace' => 'E0C6',
        ],
    113 =>
        [
            'match'   => 'E03A E013',
            'replace' => 'E0C7',
        ],
    114 =>
        [
            'match'   => '095F E013',
            'replace' => 'E0C8',
        ],
    115 =>
        [
            'match'   => '0931 E013',
            'replace' => 'E0C9',
        ],
    116 =>
        [
            'match'   => 'E03B E013',
            'replace' => 'E0CA',
        ],
    117 =>
        [
            'match'   => '0934 E013',
            'replace' => 'E0CB',
        ],
    118 =>
        [
            'match'   => 'E03C E013',
            'replace' => 'E0CC',
        ],
    119 =>
        [
            'match'   => 'E03D E013',
            'replace' => 'E0CD',
        ],
    120 =>
        [
            'match'   => 'E03E E013',
            'replace' => 'E0CE',
        ],
    121 =>
        [
            'match'   => 'E03F E013',
            'replace' => 'E0CF',
        ],
    122 =>
        [
            'match'   => 'E040 E013',
            'replace' => 'E0D0',
        ],
    123 =>
        [
            'match'   => 'E041 E013',
            'replace' => 'E0D1',
        ],
    124 =>
        [
            'match'   => 'E042 E013',
            'replace' => 'E0D2',
        ],
    125 =>
        [
            'match'   => 'E028 E013',
            'replace' => 'E0AD',
        ],
    126 =>
        [
            'match'   => 'E029 E013',
            'replace' => 'E0AE',
        ],
    127 =>
        [
            'match'   => '0915 E00E',
            'replace' => 'E043',
        ],
    128 =>
        [
            'match'   => '0916 E00E',
            'replace' => 'E044',
        ],
    129 =>
        [
            'match'   => '0917 E00E',
            'replace' => 'E045',
        ],
    130 =>
        [
            'match'   => '0918 E00E',
            'replace' => 'E046',
        ],
    131 =>
        [
            'match'   => '0919 E00E',
            'replace' => 'E047',
        ],
    132 =>
        [
            'match'   => '091A E00E',
            'replace' => 'E048',
        ],
    133 =>
        [
            'match'   => '091B E00E',
            'replace' => 'E049',
        ],
    134 =>
        [
            'match'   => '091C E00E',
            'replace' => 'E04A',
        ],
    135 =>
        [
            'match'   => '091D E00E',
            'replace' => 'E04B',
        ],
    136 =>
        [
            'match'   => '091E E00E',
            'replace' => 'E04C',
        ],
    137 =>
        [
            'match'   => '091F E00E',
            'replace' => 'E04D',
        ],
    138 =>
        [
            'match'   => '0920 E00E',
            'replace' => 'E04E',
        ],
    139 =>
        [
            'match'   => '0921 E00E',
            'replace' => 'E04F',
        ],
    140 =>
        [
            'match'   => '0922 E00E',
            'replace' => 'E050',
        ],
    141 =>
        [
            'match'   => '0923 E00E',
            'replace' => 'E051',
        ],
    142 =>
        [
            'match'   => '0924 E00E',
            'replace' => 'E052',
        ],
    143 =>
        [
            'match'   => '0925 E00E',
            'replace' => 'E053',
        ],
    144 =>
        [
            'match'   => '0926 E00E',
            'replace' => 'E054',
        ],
    145 =>
        [
            'match'   => '0927 E00E',
            'replace' => 'E055',
        ],
    146 =>
        [
            'match'   => '0928 E00E',
            'replace' => 'E056',
        ],
    147 =>
        [
            'match'   => '092A E00E',
            'replace' => 'E057',
        ],
    148 =>
        [
            'match'   => '092B E00E',
            'replace' => 'E058',
        ],
    149 =>
        [
            'match'   => '092C E00E',
            'replace' => 'E059',
        ],
    150 =>
        [
            'match'   => '092D E00E',
            'replace' => 'E05A',
        ],
    151 =>
        [
            'match'   => '092E E00E',
            'replace' => 'E05B',
        ],
    152 =>
        [
            'match'   => '092F E00E',
            'replace' => 'E05C',
        ],
    153 =>
        [
            'match'   => '0930 E00E',
            'replace' => 'E05D',
        ],
    154 =>
        [
            'match'   => '0932 E00E',
            'replace' => 'E05E',
        ],
    155 =>
        [
            'match'   => '0933 E00E',
            'replace' => 'E05F',
        ],
    156 =>
        [
            'match'   => '0935 E00E',
            'replace' => 'E060',
        ],
    157 =>
        [
            'match'   => '0936 E00E',
            'replace' => 'E061',
        ],
    158 =>
        [
            'match'   => '0937 E00E',
            'replace' => 'E062',
        ],
    159 =>
        [
            'match'   => '0938 E00E',
            'replace' => 'E063',
        ],
    160 =>
        [
            'match'   => '0939 E00E',
            'replace' => 'E064',
        ],
    161 =>
        [
            'match'   => 'E028 E00E',
            'replace' => 'E065',
        ],
    162 =>
        [
            'match'   => 'E029 E00E',
            'replace' => 'E066',
        ],
    163 =>
        [
            'match'   => 'E08B E00E',
            'replace' => 'E0D3',
        ],
    164 =>
        [
            'match'   => 'E08C E00E',
            'replace' => 'E0D4',
        ],
    165 =>
        [
            'match'   => 'E08D E00E',
            'replace' => 'E0D5',
        ],
    166 =>
        [
            'match'   => 'E08E E00E',
            'replace' => 'E0D6',
        ],
    167 =>
        [
            'match'   => 'E08F E00E',
            'replace' => 'E0D7',
        ],
    168 =>
        [
            'match'   => 'E090 E00E',
            'replace' => 'E0D8',
        ],
    169 =>
        [
            'match'   => 'E091 E00E',
            'replace' => 'E0D9',
        ],
    170 =>
        [
            'match'   => 'E092 E00E',
            'replace' => 'E0DA',
        ],
    171 =>
        [
            'match'   => 'E093 E00E',
            'replace' => 'E0DB',
        ],
    172 =>
        [
            'match'   => 'E094 E00E',
            'replace' => 'E0DC',
        ],
    173 =>
        [
            'match'   => 'E095 E00E',
            'replace' => 'E0DD',
        ],
    174 =>
        [
            'match'   => 'E096 E00E',
            'replace' => 'E0DE',
        ],
    175 =>
        [
            'match'   => 'E097 E00E',
            'replace' => 'E0DF',
        ],
    176 =>
        [
            'match'   => 'E098 E00E',
            'replace' => 'E0E0',
        ],
    177 =>
        [
            'match'   => 'E099 E00E',
            'replace' => 'E0E1',
        ],
    178 =>
        [
            'match'   => 'E09A E00E',
            'replace' => 'E0E2',
        ],
    179 =>
        [
            'match'   => 'E09B E00E',
            'replace' => 'E0E3',
        ],
    180 =>
        [
            'match'   => 'E09C E00E',
            'replace' => 'E0E4',
        ],
    181 =>
        [
            'match'   => 'E09D E00E',
            'replace' => 'E0E5',
        ],
    182 =>
        [
            'match'   => 'E09E E00E',
            'replace' => 'E0E6',
        ],
    183 =>
        [
            'match'   => 'E09F E00E',
            'replace' => 'E0E7',
        ],
    184 =>
        [
            'match'   => 'E0A0 E00E',
            'replace' => 'E0E8',
        ],
    185 =>
        [
            'match'   => 'E0A1 E00E',
            'replace' => 'E0E9',
        ],
    186 =>
        [
            'match'   => 'E0A2 E00E',
            'replace' => 'E0EA',
        ],
    187 =>
        [
            'match'   => 'E0A3 E00E',
            'replace' => 'E0EB',
        ],
    188 =>
        [
            'match'   => 'E0A4 E00E',
            'replace' => 'E0EC',
        ],
    189 =>
        [
            'match'   => 'E0A5 E00E',
            'replace' => 'E0ED',
        ],
    190 =>
        [
            'match'   => 'E0A6 E00E',
            'replace' => 'E0EE',
        ],
    191 =>
        [
            'match'   => 'E0A7 E00E',
            'replace' => 'E0EF',
        ],
    192 =>
        [
            'match'   => 'E0A8 E00E',
            'replace' => 'E0F0',
        ],
    193 =>
        [
            'match'   => 'E0A9 E00E',
            'replace' => 'E0F1',
        ],
    194 =>
        [
            'match'   => 'E0AA E00E',
            'replace' => 'E0F2',
        ],
    195 =>
        [
            'match'   => 'E0AB E00E',
            'replace' => 'E0F3',
        ],
    196 =>
        [
            'match'   => 'E0AC E00E',
            'replace' => 'E0F4',
        ],
    197 =>
        [
            'match'   => 'E0AD E00E',
            'replace' => 'E0F5',
        ],
    198 =>
        [
            'match'   => 'E0AE E00E',
            'replace' => 'E0F6',
        ],
    199 =>
        [
            'match'   => 'E0AF E00E',
            'replace' => 'E0F7',
        ],
    200 =>
        [
            'match'   => 'E0B0 E00E',
            'replace' => 'E0F8',
        ],
    201 =>
        [
            'match'   => 'E0B1 E00E',
            'replace' => 'E0F9',
        ],
    202 =>
        [
            'match'   => 'E0B2 E00E',
            'replace' => 'E0FA',
        ],
    203 =>
        [
            'match'   => 'E0B3 E00E',
            'replace' => 'E0FB',
        ],
    204 =>
        [
            'match'   => 'E0B4 E00E',
            'replace' => 'E0FC',
        ],
    205 =>
        [
            'match'   => 'E0B5 E00E',
            'replace' => 'E0FD',
        ],
    206 =>
        [
            'match'   => 'E0B6 E00E',
            'replace' => 'E0FE',
        ],
    207 =>
        [
            'match'   => 'E0B7 E00E',
            'replace' => 'E0FF',
        ],
    208 =>
        [
            'match'   => 'E0B8 E00E',
            'replace' => 'E100',
        ],
    209 =>
        [
            'match'   => 'E0B9 E00E',
            'replace' => 'E101',
        ],
    210 =>
        [
            'match'   => 'E0BA E00E',
            'replace' => 'E102',
        ],
    211 =>
        [
            'match'   => 'E0BB E00E',
            'replace' => 'E103',
        ],
    212 =>
        [
            'match'   => 'E0BC E00E',
            'replace' => 'E104',
        ],
    213 =>
        [
            'match'   => 'E0BD E00E',
            'replace' => 'E105',
        ],
    214 =>
        [
            'match'   => 'E0BE E00E',
            'replace' => 'E106',
        ],
    215 =>
        [
            'match'   => 'E0BF E00E',
            'replace' => 'E107',
        ],
    216 =>
        [
            'match'   => 'E0C0 E00E',
            'replace' => 'E108',
        ],
    217 =>
        [
            'match'   => 'E0C1 E00E',
            'replace' => 'E109',
        ],
    218 =>
        [
            'match'   => 'E0C2 E00E',
            'replace' => 'E10A',
        ],
    219 =>
        [
            'match'   => 'E0C3 E00E',
            'replace' => 'E10B',
        ],
    220 =>
        [
            'match'   => 'E0C4 E00E',
            'replace' => 'E10C',
        ],
    221 =>
        [
            'match'   => 'E0C5 E00E',
            'replace' => 'E10D',
        ],
    222 =>
        [
            'match'   => 'E0C6 E00E',
            'replace' => 'E10E',
        ],
    223 =>
        [
            'match'   => 'E0C7 E00E',
            'replace' => 'E10F',
        ],
    224 =>
        [
            'match'   => 'E0C8 E00E',
            'replace' => 'E110',
        ],
    225 =>
        [
            'match'   => 'E0C9 E00E',
            'replace' => 'E111',
        ],
    226 =>
        [
            'match'   => 'E0CA E00E',
            'replace' => 'E112',
        ],
    227 =>
        [
            'match'   => 'E0CB E00E',
            'replace' => 'E113',
        ],
    228 =>
        [
            'match'   => 'E0CC E00E',
            'replace' => 'E114',
        ],
    229 =>
        [
            'match'   => 'E0CD E00E',
            'replace' => 'E115',
        ],
    230 =>
        [
            'match'   => 'E0CE E00E',
            'replace' => 'E116',
        ],
    231 =>
        [
            'match'   => 'E0CF E00E',
            'replace' => 'E117',
        ],
    232 =>
        [
            'match'   => 'E0D0 E00E',
            'replace' => 'E118',
        ],
    233 =>
        [
            'match'   => 'E0D1 E00E',
            'replace' => 'E119',
        ],
    234 =>
        [
            'match'   => 'E0D2 E00E',
            'replace' => 'E11A',
        ],
    235 =>
        [
            'match'   => '0958 E00E',
            'replace' => 'E067',
        ],
    236 =>
        [
            'match'   => '0959 E00E',
            'replace' => 'E068',
        ],
    237 =>
        [
            'match'   => '095A E00E',
            'replace' => 'E069',
        ],
    238 =>
        [
            'match'   => 'E02A E00E',
            'replace' => 'E06A',
        ],
    239 =>
        [
            'match'   => 'E02B E00E',
            'replace' => 'E06B',
        ],
    240 =>
        [
            'match'   => 'E02C E00E',
            'replace' => 'E06C',
        ],
    241 =>
        [
            'match'   => 'E02D E00E',
            'replace' => 'E06D',
        ],
    242 =>
        [
            'match'   => '095B E00E',
            'replace' => 'E06E',
        ],
    243 =>
        [
            'match'   => 'E02E E00E',
            'replace' => 'E06F',
        ],
    244 =>
        [
            'match'   => 'E02F E00E',
            'replace' => 'E070',
        ],
    245 =>
        [
            'match'   => 'E030 E00E',
            'replace' => 'E071',
        ],
    246 =>
        [
            'match'   => 'E031 E00E',
            'replace' => 'E072',
        ],
    247 =>
        [
            'match'   => '095C E00E',
            'replace' => 'E073',
        ],
    248 =>
        [
            'match'   => '095D E00E',
            'replace' => 'E074',
        ],
    249 =>
        [
            'match'   => 'E032 E00E',
            'replace' => 'E075',
        ],
    250 =>
        [
            'match'   => 'E033 E00E',
            'replace' => 'E076',
        ],
    251 =>
        [
            'match'   => 'E034 E00E',
            'replace' => 'E077',
        ],
    252 =>
        [
            'match'   => 'E035 E00E',
            'replace' => 'E078',
        ],
    253 =>
        [
            'match'   => 'E036 E00E',
            'replace' => 'E079',
        ],
    254 =>
        [
            'match'   => '0929 E00E',
            'replace' => 'E07A',
        ],
    255 =>
        [
            'match'   => 'E037 E00E',
            'replace' => 'E07B',
        ],
    256 =>
        [
            'match'   => '095E E00E',
            'replace' => 'E07C',
        ],
    257 =>
        [
            'match'   => 'E038 E00E',
            'replace' => 'E07D',
        ],
    258 =>
        [
            'match'   => 'E039 E00E',
            'replace' => 'E07E',
        ],
    259 =>
        [
            'match'   => 'E03A E00E',
            'replace' => 'E07F',
        ],
    260 =>
        [
            'match'   => '095F E00E',
            'replace' => 'E080',
        ],
    261 =>
        [
            'match'   => '0931 E00E',
            'replace' => 'E081',
        ],
    262 =>
        [
            'match'   => 'E03B E00E',
            'replace' => 'E082',
        ],
    263 =>
        [
            'match'   => '0934 E00E',
            'replace' => 'E083',
        ],
    264 =>
        [
            'match'   => 'E03C E00E',
            'replace' => 'E084',
        ],
    265 =>
        [
            'match'   => 'E03D E00E',
            'replace' => 'E085',
        ],
    266 =>
        [
            'match'   => 'E03E E00E',
            'replace' => 'E086',
        ],
    267 =>
        [
            'match'   => 'E03F E00E',
            'replace' => 'E087',
        ],
    268 =>
        [
            'match'   => 'E040 E00E',
            'replace' => 'E088',
        ],
    269 =>
        [
            'match'   => 'E041 E00E',
            'replace' => 'E089',
        ],
    270 =>
        [
            'match'   => 'E042 E00E',
            'replace' => 'E08A',
        ],
    271 =>
        [
            'match'   => 'E043 0924',
            'replace' => 'E11B',
        ],
    272 =>
        [
            'match'   => 'E044 0928',
            'replace' => 'E11C',
        ],
    273 =>
        [
            'match'   => 'E047 092E',
            'replace' => 'E11D',
        ],
    274 =>
        [
            'match'   => 'E052 0924',
            'replace' => 'E11E',
        ],
    275 =>
        [
            'match'   => 'E052 0928',
            'replace' => 'E11F',
        ],
    276 =>
        [
            'match'   => 'E054 0917',
            'replace' => 'E120',
        ],
    277 =>
        [
            'match'   => 'E054 0918',
            'replace' => 'E121',
        ],
    278 =>
        [
            'match'   => 'E054 0926',
            'replace' => 'E122',
        ],
    279 =>
        [
            'match'   => 'E054 0927',
            'replace' => 'E123',
        ],
    280 =>
        [
            'match'   => 'E054 0928',
            'replace' => 'E124',
        ],
    281 =>
        [
            'match'   => 'E054 092C',
            'replace' => 'E125',
        ],
    282 =>
        [
            'match'   => 'E054 092D',
            'replace' => 'E126',
        ],
    283 =>
        [
            'match'   => 'E054 E05C 092D',
            'replace' => 'E127',
        ],
    284 =>
        [
            'match'   => 'E054 092F',
            'replace' => 'E128',
        ],
    285 =>
        [
            'match'   => 'E054 0935',
            'replace' => 'E129',
        ],
    286 =>
        [
            'match'   => 'E057 0924',
            'replace' => 'E12A',
        ],
    287 =>
        [
            'match'   => 'E061 0928',
            'replace' => 'E12B',
        ],
    288 =>
        [
            'match'   => 'E061 091A',
            'replace' => 'E12C',
        ],
    289 =>
        [
            'match'   => 'E061 0932',
            'replace' => 'E12D',
        ],
    290 =>
        [
            'match'   => 'E061 0935',
            'replace' => 'E12E',
        ],
    291 =>
        [
            'match'   => 'E062 091F',
            'replace' => 'E12F',
        ],
    292 =>
        [
            'match'   => 'E062 0920',
            'replace' => 'E130',
        ],
    293 =>
        [
            'match'   => 'E062 E095',
            'replace' => 'E131',
        ],
    294 =>
        [
            'match'   => 'E062 E096',
            'replace' => 'E132',
        ],
    295 =>
        [
            'match'   => 'E063 E09A',
            'replace' => 'E133',
        ],
    296 =>
        [
            'match'   => 'E064 0928',
            'replace' => 'E134',
        ],
    297 =>
        [
            'match'   => 'E064 092E',
            'replace' => 'E135',
        ],
    298 =>
        [
            'match'   => 'E064 092F',
            'replace' => 'E136',
        ],
    299 =>
        [
            'match'   => 'E064 0923',
            'replace' => 'E137',
        ],
    300 =>
        [
            'match'   => 'E064 0932',
            'replace' => 'E138',
        ],
    301 =>
        [
            'match'   => 'E064 0935',
            'replace' => 'E139',
        ],
    302 =>
        [
            'match'   => 'E044 E056',
            'replace' => 'E13B',
        ],
    303 =>
        [
            'match'   => 'E052 E052',
            'replace' => 'E13D',
        ],
    304 =>
        [
            'match'   => 'E052 E056',
            'replace' => 'E13E',
        ],
    305 =>
        [
            'match'   => 'E054 E05B',
            'replace' => 'E13F',
        ],
    306 =>
        [
            'match'   => 'E057 E052',
            'replace' => 'E140',
        ],
    307 =>
        [
            'match'   => 'E061 E056',
            'replace' => 'E141',
        ],
    308 =>
        [
            'match'   => 'E061 E048',
            'replace' => 'E142',
        ],
    309 =>
        [
            'match'   => 'E061 E05E',
            'replace' => 'E143',
        ],
    310 =>
        [
            'match'   => 'E061 E060',
            'replace' => 'E144',
        ],
    311 =>
        [
            'match'   => 'E063 E0E2',
            'replace' => 'E145',
        ],
    312 =>
        [
            'match'   => 'E047 ((0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029) E015)',
            'replace' => 'E047 E015 \\1',
        ],
    313 =>
        [
            'match'   => 'E04D ((0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029) E015)',
            'replace' => 'E04D E015 \\1',
        ],
    314 =>
        [
            'match'   => 'E04E ((0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029) E015)',
            'replace' => 'E04E E015 \\1',
        ],
    315 =>
        [
            'match'   => 'E04F ((0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029) E015)',
            'replace' => 'E04F E015 \\1',
        ],
    316 =>
        [
            'match'   => 'E050 ((0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029) E015)',
            'replace' => 'E050 E015 \\1',
        ],
    317 =>
        [
            'match'   => 'E054 ((0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029) E015)',
            'replace' => 'E054 E015 \\1',
        ],
    318 =>
        [
            'match'   => 'E06B ((0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029) E015)',
            'replace' => 'E06B E015 \\1',
        ],
    319 =>
        [
            'match'   => 'E071 ((0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029) E015)',
            'replace' => 'E071 E015 \\1',
        ],
    320 =>
        [
            'match'   => 'E072 ((0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029) E015)',
            'replace' => 'E072 E015 \\1',
        ],
    321 =>
        [
            'match'   => 'E073 ((0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029) E015)',
            'replace' => 'E073 E015 \\1',
        ],
    322 =>
        [
            'match'   => 'E074 ((0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029) E015)',
            'replace' => 'E074 E015 \\1',
        ],
    323 =>
        [
            'match'   => 'E078 ((0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029) E015)',
            'replace' => 'E078 E015 \\1',
        ],
    324 =>
        [
            'match'   => 'E0FB ((0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029) E015)',
            'replace' => 'E0FB E015 \\1',
        ],
    325 =>
        [
            'match'   => 'E101 ((0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029) E015)',
            'replace' => 'E101 E015 \\1',
        ],
    326 =>
        [
            'match'   => 'E102 ((0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029) E015)',
            'replace' => 'E102 E015 \\1',
        ],
    327 =>
        [
            'match'   => 'E103 ((0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029) E015)',
            'replace' => 'E103 E015 \\1',
        ],
    328 =>
        [
            'match'   => 'E104 ((0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029) E015)',
            'replace' => 'E104 E015 \\1',
        ],
    329 =>
        [
            'match'   => 'E108 ((0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029) E015)',
            'replace' => 'E108 E015 \\1',
        ],
    330 =>
        [
            'match'   => 'E047 ((0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029) (E015|0947|E1A8|E1A7|E199|E1B2|E1B1|0946|E202|E201|E1A3|E1C6|E1C5|0945|E200|E1FF|0948|E1AA|E1A9|E19A|E1B4|E1B3|0902|E00F|0901|0953|0954|0951) E015)',
            'replace' => 'E047 E015 \\1',
        ],
    331 =>
        [
            'match'   => 'E04D ((0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029) (E015|0947|E1A8|E1A7|E199|E1B2|E1B1|0946|E202|E201|E1A3|E1C6|E1C5|0945|E200|E1FF|0948|E1AA|E1A9|E19A|E1B4|E1B3|0902|E00F|0901|0953|0954|0951) E015)',
            'replace' => 'E04D E015 \\1',
        ],
    332 =>
        [
            'match'   => 'E04E ((0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029) (E015|0947|E1A8|E1A7|E199|E1B2|E1B1|0946|E202|E201|E1A3|E1C6|E1C5|0945|E200|E1FF|0948|E1AA|E1A9|E19A|E1B4|E1B3|0902|E00F|0901|0953|0954|0951) E015)',
            'replace' => 'E04E E015 \\1',
        ],
    333 =>
        [
            'match'   => 'E04F ((0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029) (E015|0947|E1A8|E1A7|E199|E1B2|E1B1|0946|E202|E201|E1A3|E1C6|E1C5|0945|E200|E1FF|0948|E1AA|E1A9|E19A|E1B4|E1B3|0902|E00F|0901|0953|0954|0951) E015)',
            'replace' => 'E04F E015 \\1',
        ],
    334 =>
        [
            'match'   => 'E050 ((0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029) (E015|0947|E1A8|E1A7|E199|E1B2|E1B1|0946|E202|E201|E1A3|E1C6|E1C5|0945|E200|E1FF|0948|E1AA|E1A9|E19A|E1B4|E1B3|0902|E00F|0901|0953|0954|0951) E015)',
            'replace' => 'E050 E015 \\1',
        ],
    335 =>
        [
            'match'   => 'E054 ((0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029) (E015|0947|E1A8|E1A7|E199|E1B2|E1B1|0946|E202|E201|E1A3|E1C6|E1C5|0945|E200|E1FF|0948|E1AA|E1A9|E19A|E1B4|E1B3|0902|E00F|0901|0953|0954|0951) E015)',
            'replace' => 'E054 E015 \\1',
        ],
    336 =>
        [
            'match'   => 'E06B ((0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029) (E015|0947|E1A8|E1A7|E199|E1B2|E1B1|0946|E202|E201|E1A3|E1C6|E1C5|0945|E200|E1FF|0948|E1AA|E1A9|E19A|E1B4|E1B3|0902|E00F|0901|0953|0954|0951) E015)',
            'replace' => 'E06B E015 \\1',
        ],
    337 =>
        [
            'match'   => 'E071 ((0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029) (E015|0947|E1A8|E1A7|E199|E1B2|E1B1|0946|E202|E201|E1A3|E1C6|E1C5|0945|E200|E1FF|0948|E1AA|E1A9|E19A|E1B4|E1B3|0902|E00F|0901|0953|0954|0951) E015)',
            'replace' => 'E071 E015 \\1',
        ],
    338 =>
        [
            'match'   => 'E072 ((0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029) (E015|0947|E1A8|E1A7|E199|E1B2|E1B1|0946|E202|E201|E1A3|E1C6|E1C5|0945|E200|E1FF|0948|E1AA|E1A9|E19A|E1B4|E1B3|0902|E00F|0901|0953|0954|0951) E015)',
            'replace' => 'E072 E015 \\1',
        ],
    339 =>
        [
            'match'   => 'E073 ((0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029) (E015|0947|E1A8|E1A7|E199|E1B2|E1B1|0946|E202|E201|E1A3|E1C6|E1C5|0945|E200|E1FF|0948|E1AA|E1A9|E19A|E1B4|E1B3|0902|E00F|0901|0953|0954|0951) E015)',
            'replace' => 'E073 E015 \\1',
        ],
    340 =>
        [
            'match'   => 'E074 ((0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029) (E015|0947|E1A8|E1A7|E199|E1B2|E1B1|0946|E202|E201|E1A3|E1C6|E1C5|0945|E200|E1FF|0948|E1AA|E1A9|E19A|E1B4|E1B3|0902|E00F|0901|0953|0954|0951) E015)',
            'replace' => 'E074 E015 \\1',
        ],
    341 =>
        [
            'match'   => 'E078 ((0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029) (E015|0947|E1A8|E1A7|E199|E1B2|E1B1|0946|E202|E201|E1A3|E1C6|E1C5|0945|E200|E1FF|0948|E1AA|E1A9|E19A|E1B4|E1B3|0902|E00F|0901|0953|0954|0951) E015)',
            'replace' => 'E078 E015 \\1',
        ],
    342 =>
        [
            'match'   => 'E0FB ((0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029) (E015|0947|E1A8|E1A7|E199|E1B2|E1B1|0946|E202|E201|E1A3|E1C6|E1C5|0945|E200|E1FF|0948|E1AA|E1A9|E19A|E1B4|E1B3|0902|E00F|0901|0953|0954|0951) E015)',
            'replace' => 'E0FB E015 \\1',
        ],
    343 =>
        [
            'match'   => 'E101 ((0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029) (E015|0947|E1A8|E1A7|E199|E1B2|E1B1|0946|E202|E201|E1A3|E1C6|E1C5|0945|E200|E1FF|0948|E1AA|E1A9|E19A|E1B4|E1B3|0902|E00F|0901|0953|0954|0951) E015)',
            'replace' => 'E101 E015 \\1',
        ],
    344 =>
        [
            'match'   => 'E102 ((0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029) (E015|0947|E1A8|E1A7|E199|E1B2|E1B1|0946|E202|E201|E1A3|E1C6|E1C5|0945|E200|E1FF|0948|E1AA|E1A9|E19A|E1B4|E1B3|0902|E00F|0901|0953|0954|0951) E015)',
            'replace' => 'E102 E015 \\1',
        ],
    345 =>
        [
            'match'   => 'E103 ((0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029) (E015|0947|E1A8|E1A7|E199|E1B2|E1B1|0946|E202|E201|E1A3|E1C6|E1C5|0945|E200|E1FF|0948|E1AA|E1A9|E19A|E1B4|E1B3|0902|E00F|0901|0953|0954|0951) E015)',
            'replace' => 'E103 E015 \\1',
        ],
    346 =>
        [
            'match'   => 'E104 ((0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029) (E015|0947|E1A8|E1A7|E199|E1B2|E1B1|0946|E202|E201|E1A3|E1C6|E1C5|0945|E200|E1FF|0948|E1AA|E1A9|E19A|E1B4|E1B3|0902|E00F|0901|0953|0954|0951) E015)',
            'replace' => 'E104 E015 \\1',
        ],
    347 =>
        [
            'match'   => 'E108 ((0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029) (E015|0947|E1A8|E1A7|E199|E1B2|E1B1|0946|E202|E201|E1A3|E1C6|E1C5|0945|E200|E1FF|0948|E1AA|E1A9|E19A|E1B4|E1B3|0902|E00F|0901|0953|0954|0951) E015)',
            'replace' => 'E108 E015 \\1',
        ],
    348 =>
        [
            'match'   => 'E047 ((0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029) (0941|0942|0943|0944|0952|E013) E015)',
            'replace' => 'E047 E015 \\1',
        ],
    349 =>
        [
            'match'   => 'E04D ((0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029) (0941|0942|0943|0944|0952|E013) E015)',
            'replace' => 'E04D E015 \\1',
        ],
    350 =>
        [
            'match'   => 'E04E ((0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029) (0941|0942|0943|0944|0952|E013) E015)',
            'replace' => 'E04E E015 \\1',
        ],
    351 =>
        [
            'match'   => 'E04F ((0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029) (0941|0942|0943|0944|0952|E013) E015)',
            'replace' => 'E04F E015 \\1',
        ],
    352 =>
        [
            'match'   => 'E050 ((0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029) (0941|0942|0943|0944|0952|E013) E015)',
            'replace' => 'E050 E015 \\1',
        ],
    353 =>
        [
            'match'   => 'E054 ((0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029) (0941|0942|0943|0944|0952|E013) E015)',
            'replace' => 'E054 E015 \\1',
        ],
    354 =>
        [
            'match'   => 'E06B ((0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029) (0941|0942|0943|0944|0952|E013) E015)',
            'replace' => 'E06B E015 \\1',
        ],
    355 =>
        [
            'match'   => 'E071 ((0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029) (0941|0942|0943|0944|0952|E013) E015)',
            'replace' => 'E071 E015 \\1',
        ],
    356 =>
        [
            'match'   => 'E072 ((0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029) (0941|0942|0943|0944|0952|E013) E015)',
            'replace' => 'E072 E015 \\1',
        ],
    357 =>
        [
            'match'   => 'E073 ((0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029) (0941|0942|0943|0944|0952|E013) E015)',
            'replace' => 'E073 E015 \\1',
        ],
    358 =>
        [
            'match'   => 'E074 ((0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029) (0941|0942|0943|0944|0952|E013) E015)',
            'replace' => 'E074 E015 \\1',
        ],
    359 =>
        [
            'match'   => 'E078 ((0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029) (0941|0942|0943|0944|0952|E013) E015)',
            'replace' => 'E078 E015 \\1',
        ],
    360 =>
        [
            'match'   => 'E0FB ((0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029) (0941|0942|0943|0944|0952|E013) E015)',
            'replace' => 'E0FB E015 \\1',
        ],
    361 =>
        [
            'match'   => 'E101 ((0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029) (0941|0942|0943|0944|0952|E013) E015)',
            'replace' => 'E101 E015 \\1',
        ],
    362 =>
        [
            'match'   => 'E102 ((0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029) (0941|0942|0943|0944|0952|E013) E015)',
            'replace' => 'E102 E015 \\1',
        ],
    363 =>
        [
            'match'   => 'E103 ((0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029) (0941|0942|0943|0944|0952|E013) E015)',
            'replace' => 'E103 E015 \\1',
        ],
    364 =>
        [
            'match'   => 'E104 ((0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029) (0941|0942|0943|0944|0952|E013) E015)',
            'replace' => 'E104 E015 \\1',
        ],
    365 =>
        [
            'match'   => 'E108 ((0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029) (0941|0942|0943|0944|0952|E013) E015)',
            'replace' => 'E108 E015 \\1',
        ],
    366 =>
        [
            'match'   => '200D E00E',
            'replace' => '094D',
        ],
    367 =>
        [
            'match'   => 'E00C',
            'replace' => '094D',
        ],
    368 =>
        [
            'match'   => 'E00E',
            'replace' => '094D',
        ],
    369 =>
        [
            'match'   => 'E043 200D',
            'replace' => 'E043',
        ],
    370 =>
        [
            'match'   => 'E044 200D',
            'replace' => 'E044',
        ],
    371 =>
        [
            'match'   => 'E045 200D',
            'replace' => 'E045',
        ],
    372 =>
        [
            'match'   => 'E046 200D',
            'replace' => 'E046',
        ],
    373 =>
        [
            'match'   => 'E047 200D',
            'replace' => 'E047',
        ],
    374 =>
        [
            'match'   => 'E048 200D',
            'replace' => 'E048',
        ],
    375 =>
        [
            'match'   => 'E049 200D',
            'replace' => 'E049',
        ],
    376 =>
        [
            'match'   => 'E04A 200D',
            'replace' => 'E04A',
        ],
    377 =>
        [
            'match'   => 'E04B 200D',
            'replace' => 'E04B',
        ],
    378 =>
        [
            'match'   => 'E04C 200D',
            'replace' => 'E04C',
        ],
    379 =>
        [
            'match'   => 'E04D 200D',
            'replace' => 'E04D',
        ],
    380 =>
        [
            'match'   => 'E04E 200D',
            'replace' => 'E04E',
        ],
    381 =>
        [
            'match'   => 'E04F 200D',
            'replace' => 'E04F',
        ],
    382 =>
        [
            'match'   => 'E050 200D',
            'replace' => 'E050',
        ],
    383 =>
        [
            'match'   => 'E051 200D',
            'replace' => 'E051',
        ],
    384 =>
        [
            'match'   => 'E052 200D',
            'replace' => 'E052',
        ],
    385 =>
        [
            'match'   => 'E053 200D',
            'replace' => 'E053',
        ],
    386 =>
        [
            'match'   => 'E054 200D',
            'replace' => 'E054',
        ],
    387 =>
        [
            'match'   => 'E055 200D',
            'replace' => 'E055',
        ],
    388 =>
        [
            'match'   => 'E056 200D',
            'replace' => 'E056',
        ],
    389 =>
        [
            'match'   => 'E057 200D',
            'replace' => 'E057',
        ],
    390 =>
        [
            'match'   => 'E058 200D',
            'replace' => 'E058',
        ],
    391 =>
        [
            'match'   => 'E059 200D',
            'replace' => 'E059',
        ],
    392 =>
        [
            'match'   => 'E05A 200D',
            'replace' => 'E05A',
        ],
    393 =>
        [
            'match'   => 'E05B 200D',
            'replace' => 'E05B',
        ],
    394 =>
        [
            'match'   => 'E05C 200D',
            'replace' => 'E05C',
        ],
    395 =>
        [
            'match'   => 'E05D 200D',
            'replace' => 'E05D',
        ],
    396 =>
        [
            'match'   => 'E05E 200D',
            'replace' => 'E05E',
        ],
    397 =>
        [
            'match'   => 'E05F 200D',
            'replace' => 'E05F',
        ],
    398 =>
        [
            'match'   => 'E060 200D',
            'replace' => 'E060',
        ],
    399 =>
        [
            'match'   => 'E061 200D',
            'replace' => 'E061',
        ],
    400 =>
        [
            'match'   => 'E062 200D',
            'replace' => 'E062',
        ],
    401 =>
        [
            'match'   => 'E063 200D',
            'replace' => 'E063',
        ],
    402 =>
        [
            'match'   => 'E064 200D',
            'replace' => 'E064',
        ],
    403 =>
        [
            'match'   => 'E065 200D',
            'replace' => 'E065',
        ],
    404 =>
        [
            'match'   => 'E066 200D',
            'replace' => 'E066',
        ],
    405 =>
        [
            'match'   => 'E067 200D',
            'replace' => 'E067',
        ],
    406 =>
        [
            'match'   => 'E068 200D',
            'replace' => 'E068',
        ],
    407 =>
        [
            'match'   => 'E069 200D',
            'replace' => 'E069',
        ],
    408 =>
        [
            'match'   => 'E06A 200D',
            'replace' => 'E06A',
        ],
    409 =>
        [
            'match'   => 'E06B 200D',
            'replace' => 'E06B',
        ],
    410 =>
        [
            'match'   => 'E06C 200D',
            'replace' => 'E06C',
        ],
    411 =>
        [
            'match'   => 'E06D 200D',
            'replace' => 'E06D',
        ],
    412 =>
        [
            'match'   => 'E06E 200D',
            'replace' => 'E06E',
        ],
    413 =>
        [
            'match'   => 'E06F 200D',
            'replace' => 'E06F',
        ],
    414 =>
        [
            'match'   => 'E070 200D',
            'replace' => 'E070',
        ],
    415 =>
        [
            'match'   => 'E071 200D',
            'replace' => 'E071',
        ],
    416 =>
        [
            'match'   => 'E072 200D',
            'replace' => 'E072',
        ],
    417 =>
        [
            'match'   => 'E073 200D',
            'replace' => 'E073',
        ],
    418 =>
        [
            'match'   => 'E074 200D',
            'replace' => 'E074',
        ],
    419 =>
        [
            'match'   => 'E075 200D',
            'replace' => 'E075',
        ],
    420 =>
        [
            'match'   => 'E076 200D',
            'replace' => 'E076',
        ],
    421 =>
        [
            'match'   => 'E077 200D',
            'replace' => 'E077',
        ],
    422 =>
        [
            'match'   => 'E078 200D',
            'replace' => 'E078',
        ],
    423 =>
        [
            'match'   => 'E079 200D',
            'replace' => 'E079',
        ],
    424 =>
        [
            'match'   => 'E07A 200D',
            'replace' => 'E07A',
        ],
    425 =>
        [
            'match'   => 'E07B 200D',
            'replace' => 'E07B',
        ],
    426 =>
        [
            'match'   => 'E07C 200D',
            'replace' => 'E07C',
        ],
    427 =>
        [
            'match'   => 'E07D 200D',
            'replace' => 'E07D',
        ],
    428 =>
        [
            'match'   => 'E07E 200D',
            'replace' => 'E07E',
        ],
    429 =>
        [
            'match'   => 'E07F 200D',
            'replace' => 'E07F',
        ],
    430 =>
        [
            'match'   => 'E080 200D',
            'replace' => 'E080',
        ],
    431 =>
        [
            'match'   => 'E081 200D',
            'replace' => 'E081',
        ],
    432 =>
        [
            'match'   => 'E082 200D',
            'replace' => 'E082',
        ],
    433 =>
        [
            'match'   => 'E083 200D',
            'replace' => 'E083',
        ],
    434 =>
        [
            'match'   => 'E084 200D',
            'replace' => 'E084',
        ],
    435 =>
        [
            'match'   => 'E085 200D',
            'replace' => 'E085',
        ],
    436 =>
        [
            'match'   => 'E086 200D',
            'replace' => 'E086',
        ],
    437 =>
        [
            'match'   => 'E087 200D',
            'replace' => 'E087',
        ],
    438 =>
        [
            'match'   => 'E088 200D',
            'replace' => 'E088',
        ],
    439 =>
        [
            'match'   => 'E089 200D',
            'replace' => 'E089',
        ],
    440 =>
        [
            'match'   => 'E08A 200D',
            'replace' => 'E08A',
        ],
    441 =>
        [
            'match'   => 'E0D3 200D',
            'replace' => 'E0D3',
        ],
    442 =>
        [
            'match'   => 'E0D4 200D',
            'replace' => 'E0D4',
        ],
    443 =>
        [
            'match'   => 'E0D5 200D',
            'replace' => 'E0D5',
        ],
    444 =>
        [
            'match'   => 'E0D6 200D',
            'replace' => 'E0D6',
        ],
    445 =>
        [
            'match'   => 'E0D7 200D',
            'replace' => 'E0D7',
        ],
    446 =>
        [
            'match'   => 'E0D8 200D',
            'replace' => 'E0D8',
        ],
    447 =>
        [
            'match'   => 'E0D9 200D',
            'replace' => 'E0D9',
        ],
    448 =>
        [
            'match'   => 'E0DA 200D',
            'replace' => 'E0DA',
        ],
    449 =>
        [
            'match'   => 'E0DB 200D',
            'replace' => 'E0DB',
        ],
    450 =>
        [
            'match'   => 'E0DC 200D',
            'replace' => 'E0DC',
        ],
    451 =>
        [
            'match'   => 'E0DD 200D',
            'replace' => 'E0DD',
        ],
    452 =>
        [
            'match'   => 'E0DE 200D',
            'replace' => 'E0DE',
        ],
    453 =>
        [
            'match'   => 'E0DF 200D',
            'replace' => 'E0DF',
        ],
    454 =>
        [
            'match'   => 'E0E0 200D',
            'replace' => 'E0E0',
        ],
    455 =>
        [
            'match'   => 'E0E1 200D',
            'replace' => 'E0E1',
        ],
    456 =>
        [
            'match'   => 'E0E2 200D',
            'replace' => 'E0E2',
        ],
    457 =>
        [
            'match'   => 'E0E3 200D',
            'replace' => 'E0E3',
        ],
    458 =>
        [
            'match'   => 'E0E4 200D',
            'replace' => 'E0E4',
        ],
    459 =>
        [
            'match'   => 'E0E5 200D',
            'replace' => 'E0E5',
        ],
    460 =>
        [
            'match'   => 'E0E6 200D',
            'replace' => 'E0E6',
        ],
    461 =>
        [
            'match'   => 'E0E7 200D',
            'replace' => 'E0E7',
        ],
    462 =>
        [
            'match'   => 'E0E8 200D',
            'replace' => 'E0E8',
        ],
    463 =>
        [
            'match'   => 'E0E9 200D',
            'replace' => 'E0E9',
        ],
    464 =>
        [
            'match'   => 'E0EA 200D',
            'replace' => 'E0EA',
        ],
    465 =>
        [
            'match'   => 'E0EB 200D',
            'replace' => 'E0EB',
        ],
    466 =>
        [
            'match'   => 'E0EC 200D',
            'replace' => 'E0EC',
        ],
    467 =>
        [
            'match'   => 'E0ED 200D',
            'replace' => 'E0ED',
        ],
    468 =>
        [
            'match'   => 'E0EE 200D',
            'replace' => 'E0EE',
        ],
    469 =>
        [
            'match'   => 'E0EF 200D',
            'replace' => 'E0EF',
        ],
    470 =>
        [
            'match'   => 'E0F0 200D',
            'replace' => 'E0F0',
        ],
    471 =>
        [
            'match'   => 'E0F1 200D',
            'replace' => 'E0F1',
        ],
    472 =>
        [
            'match'   => 'E0F2 200D',
            'replace' => 'E0F2',
        ],
    473 =>
        [
            'match'   => 'E0F3 200D',
            'replace' => 'E0F3',
        ],
    474 =>
        [
            'match'   => 'E0F4 200D',
            'replace' => 'E0F4',
        ],
    475 =>
        [
            'match'   => 'E0F5 200D',
            'replace' => 'E0F5',
        ],
    476 =>
        [
            'match'   => 'E0F6 200D',
            'replace' => 'E0F6',
        ],
    477 =>
        [
            'match'   => 'E0F7 200D',
            'replace' => 'E0F7',
        ],
    478 =>
        [
            'match'   => 'E0F8 200D',
            'replace' => 'E0F8',
        ],
    479 =>
        [
            'match'   => 'E0F9 200D',
            'replace' => 'E0F9',
        ],
    480 =>
        [
            'match'   => 'E0FA 200D',
            'replace' => 'E0FA',
        ],
    481 =>
        [
            'match'   => 'E0FB 200D',
            'replace' => 'E0FB',
        ],
    482 =>
        [
            'match'   => 'E0FC 200D',
            'replace' => 'E0FC',
        ],
    483 =>
        [
            'match'   => 'E0FD 200D',
            'replace' => 'E0FD',
        ],
    484 =>
        [
            'match'   => 'E0FE 200D',
            'replace' => 'E0FE',
        ],
    485 =>
        [
            'match'   => 'E0FF 200D',
            'replace' => 'E0FF',
        ],
    486 =>
        [
            'match'   => 'E100 200D',
            'replace' => 'E100',
        ],
    487 =>
        [
            'match'   => 'E101 200D',
            'replace' => 'E101',
        ],
    488 =>
        [
            'match'   => 'E102 200D',
            'replace' => 'E102',
        ],
    489 =>
        [
            'match'   => 'E103 200D',
            'replace' => 'E103',
        ],
    490 =>
        [
            'match'   => 'E104 200D',
            'replace' => 'E104',
        ],
    491 =>
        [
            'match'   => 'E105 200D',
            'replace' => 'E105',
        ],
    492 =>
        [
            'match'   => 'E106 200D',
            'replace' => 'E106',
        ],
    493 =>
        [
            'match'   => 'E107 200D',
            'replace' => 'E107',
        ],
    494 =>
        [
            'match'   => 'E108 200D',
            'replace' => 'E108',
        ],
    495 =>
        [
            'match'   => 'E109 200D',
            'replace' => 'E109',
        ],
    496 =>
        [
            'match'   => 'E10A 200D',
            'replace' => 'E10A',
        ],
    497 =>
        [
            'match'   => 'E10B 200D',
            'replace' => 'E10B',
        ],
    498 =>
        [
            'match'   => 'E10C 200D',
            'replace' => 'E10C',
        ],
    499 =>
        [
            'match'   => 'E10D 200D',
            'replace' => 'E10D',
        ],
    500 =>
        [
            'match'   => 'E10E 200D',
            'replace' => 'E10E',
        ],
    501 =>
        [
            'match'   => 'E10F 200D',
            'replace' => 'E10F',
        ],
    502 =>
        [
            'match'   => 'E110 200D',
            'replace' => 'E110',
        ],
    503 =>
        [
            'match'   => 'E111 200D',
            'replace' => 'E111',
        ],
    504 =>
        [
            'match'   => 'E112 200D',
            'replace' => 'E112',
        ],
    505 =>
        [
            'match'   => 'E113 200D',
            'replace' => 'E113',
        ],
    506 =>
        [
            'match'   => 'E114 200D',
            'replace' => 'E114',
        ],
    507 =>
        [
            'match'   => 'E115 200D',
            'replace' => 'E115',
        ],
    508 =>
        [
            'match'   => 'E116 200D',
            'replace' => 'E116',
        ],
    509 =>
        [
            'match'   => 'E117 200D',
            'replace' => 'E117',
        ],
    510 =>
        [
            'match'   => 'E118 200D',
            'replace' => 'E118',
        ],
    511 =>
        [
            'match'   => 'E119 200D',
            'replace' => 'E119',
        ],
    512 =>
        [
            'match'   => 'E11A 200D',
            'replace' => 'E11A',
        ],
    513 =>
        [
            'match'   => 'E13D 200D',
            'replace' => 'E13D',
        ],
    514 =>
        [
            'match'   => 'E13E 200D',
            'replace' => 'E13E',
        ],
    515 =>
        [
            'match'   => 'E13F 200D',
            'replace' => 'E13F',
        ],
    516 =>
        [
            'match'   => 'E140 200D',
            'replace' => 'E140',
        ],
    517 =>
        [
            'match'   => 'E141 200D',
            'replace' => 'E141',
        ],
    518 =>
        [
            'match'   => 'E142 200D',
            'replace' => 'E142',
        ],
    519 =>
        [
            'match'   => 'E143 200D',
            'replace' => 'E143',
        ],
    520 =>
        [
            'match'   => 'E144 200D',
            'replace' => 'E144',
        ],
    521 =>
        [
            'match'   => 'E145 200D',
            'replace' => 'E145',
        ],
    522 =>
        [
            'match'   => '200D E013',
            'replace' => 'E013',
        ],
    523 =>
        [
            'match'   => '200D',
            'replace' => '200B',
        ],
    524 =>
        [
            'match'   => '200C',
            'replace' => '200B',
        ],
    525 =>
        [
            'match'   => '(093F) E047',
            'replace' => '\\1 E047 093F',
        ],
    526 =>
        [
            'match'   => '(093F) E04D',
            'replace' => '\\1 E04D 093F',
        ],
    527 =>
        [
            'match'   => '(093F) E04E',
            'replace' => '\\1 E04E 093F',
        ],
    528 =>
        [
            'match'   => '(093F) E04F',
            'replace' => '\\1 E04F 093F',
        ],
    529 =>
        [
            'match'   => '(093F) E050',
            'replace' => '\\1 E050 093F',
        ],
    530 =>
        [
            'match'   => '(093F) E054',
            'replace' => '\\1 E054 093F',
        ],
    531 =>
        [
            'match'   => '(093F) E06B',
            'replace' => '\\1 E06B 093F',
        ],
    532 =>
        [
            'match'   => '(093F) E071',
            'replace' => '\\1 E071 093F',
        ],
    533 =>
        [
            'match'   => '(093F) E072',
            'replace' => '\\1 E072 093F',
        ],
    534 =>
        [
            'match'   => '(093F) E073',
            'replace' => '\\1 E073 093F',
        ],
    535 =>
        [
            'match'   => '(093F) E074',
            'replace' => '\\1 E074 093F',
        ],
    536 =>
        [
            'match'   => '(093F) E078',
            'replace' => '\\1 E078 093F',
        ],
    537 =>
        [
            'match'   => '(093F) E0FB',
            'replace' => '\\1 E0FB 093F',
        ],
    538 =>
        [
            'match'   => '(093F) E101',
            'replace' => '\\1 E101 093F',
        ],
    539 =>
        [
            'match'   => '(093F) E102',
            'replace' => '\\1 E102 093F',
        ],
    540 =>
        [
            'match'   => '(093F) E103',
            'replace' => '\\1 E103 093F',
        ],
    541 =>
        [
            'match'   => '(093F) E104',
            'replace' => '\\1 E104 093F',
        ],
    542 =>
        [
            'match'   => '(093F) E108',
            'replace' => '\\1 E108 093F',
        ],
    543 =>
        [
            'match'   => '093F E047 (093F)',
            'replace' => 'E047 \\1',
        ],
    544 =>
        [
            'match'   => '093F E04D (093F)',
            'replace' => 'E04D \\1',
        ],
    545 =>
        [
            'match'   => '093F E04E (093F)',
            'replace' => 'E04E \\1',
        ],
    546 =>
        [
            'match'   => '093F E04F (093F)',
            'replace' => 'E04F \\1',
        ],
    547 =>
        [
            'match'   => '093F E050 (093F)',
            'replace' => 'E050 \\1',
        ],
    548 =>
        [
            'match'   => '093F E054 (093F)',
            'replace' => 'E054 \\1',
        ],
    549 =>
        [
            'match'   => '093F E06B (093F)',
            'replace' => 'E06B \\1',
        ],
    550 =>
        [
            'match'   => '093F E071 (093F)',
            'replace' => 'E071 \\1',
        ],
    551 =>
        [
            'match'   => '093F E072 (093F)',
            'replace' => 'E072 \\1',
        ],
    552 =>
        [
            'match'   => '093F E073 (093F)',
            'replace' => 'E073 \\1',
        ],
    553 =>
        [
            'match'   => '093F E074 (093F)',
            'replace' => 'E074 \\1',
        ],
    554 =>
        [
            'match'   => '093F E078 (093F)',
            'replace' => 'E078 \\1',
        ],
    555 =>
        [
            'match'   => '093F E0FB (093F)',
            'replace' => 'E0FB \\1',
        ],
    556 =>
        [
            'match'   => '093F E101 (093F)',
            'replace' => 'E101 \\1',
        ],
    557 =>
        [
            'match'   => '093F E102 (093F)',
            'replace' => 'E102 \\1',
        ],
    558 =>
        [
            'match'   => '093F E103 (093F)',
            'replace' => 'E103 \\1',
        ],
    559 =>
        [
            'match'   => '093F E104 (093F)',
            'replace' => 'E104 \\1',
        ],
    560 =>
        [
            'match'   => '093F E108 (093F)',
            'replace' => 'E108 \\1',
        ],
    561 =>
        [
            'match'   => '(E015) 0915 E015',
            'replace' => '\\1 0915',
        ],
    562 =>
        [
            'match'   => '(E015) 0916 E015',
            'replace' => '\\1 0916',
        ],
    563 =>
        [
            'match'   => '(E015) 0917 E015',
            'replace' => '\\1 0917',
        ],
    564 =>
        [
            'match'   => '(E015) 0918 E015',
            'replace' => '\\1 0918',
        ],
    565 =>
        [
            'match'   => '(E015) 0919 E015',
            'replace' => '\\1 0919',
        ],
    566 =>
        [
            'match'   => '(E015) 091A E015',
            'replace' => '\\1 091A',
        ],
    567 =>
        [
            'match'   => '(E015) 091B E015',
            'replace' => '\\1 091B',
        ],
    568 =>
        [
            'match'   => '(E015) 091C E015',
            'replace' => '\\1 091C',
        ],
    569 =>
        [
            'match'   => '(E015) 091D E015',
            'replace' => '\\1 091D',
        ],
    570 =>
        [
            'match'   => '(E015) 091E E015',
            'replace' => '\\1 091E',
        ],
    571 =>
        [
            'match'   => '(E015) 091F E015',
            'replace' => '\\1 091F',
        ],
    572 =>
        [
            'match'   => '(E015) 0920 E015',
            'replace' => '\\1 0920',
        ],
    573 =>
        [
            'match'   => '(E015) 0921 E015',
            'replace' => '\\1 0921',
        ],
    574 =>
        [
            'match'   => '(E015) 0922 E015',
            'replace' => '\\1 0922',
        ],
    575 =>
        [
            'match'   => '(E015) 0923 E015',
            'replace' => '\\1 0923',
        ],
    576 =>
        [
            'match'   => '(E015) 0924 E015',
            'replace' => '\\1 0924',
        ],
    577 =>
        [
            'match'   => '(E015) 0925 E015',
            'replace' => '\\1 0925',
        ],
    578 =>
        [
            'match'   => '(E015) 0926 E015',
            'replace' => '\\1 0926',
        ],
    579 =>
        [
            'match'   => '(E015) 0927 E015',
            'replace' => '\\1 0927',
        ],
    580 =>
        [
            'match'   => '(E015) 0928 E015',
            'replace' => '\\1 0928',
        ],
    581 =>
        [
            'match'   => '(E015) 092A E015',
            'replace' => '\\1 092A',
        ],
    582 =>
        [
            'match'   => '(E015) 092B E015',
            'replace' => '\\1 092B',
        ],
    583 =>
        [
            'match'   => '(E015) 092C E015',
            'replace' => '\\1 092C',
        ],
    584 =>
        [
            'match'   => '(E015) 092D E015',
            'replace' => '\\1 092D',
        ],
    585 =>
        [
            'match'   => '(E015) 092E E015',
            'replace' => '\\1 092E',
        ],
    586 =>
        [
            'match'   => '(E015) 092F E015',
            'replace' => '\\1 092F',
        ],
    587 =>
        [
            'match'   => '(E015) 0930 E015',
            'replace' => '\\1 0930',
        ],
    588 =>
        [
            'match'   => '(E015) 0932 E015',
            'replace' => '\\1 0932',
        ],
    589 =>
        [
            'match'   => '(E015) 0933 E015',
            'replace' => '\\1 0933',
        ],
    590 =>
        [
            'match'   => '(E015) 0935 E015',
            'replace' => '\\1 0935',
        ],
    591 =>
        [
            'match'   => '(E015) 0936 E015',
            'replace' => '\\1 0936',
        ],
    592 =>
        [
            'match'   => '(E015) 0937 E015',
            'replace' => '\\1 0937',
        ],
    593 =>
        [
            'match'   => '(E015) 0938 E015',
            'replace' => '\\1 0938',
        ],
    594 =>
        [
            'match'   => '(E015) 0939 E015',
            'replace' => '\\1 0939',
        ],
    595 =>
        [
            'match'   => '(E015) E028 E015',
            'replace' => '\\1 E028',
        ],
    596 =>
        [
            'match'   => '(E015) E029 E015',
            'replace' => '\\1 E029',
        ],
    597 =>
        [
            'match'   => '(E015) E015 E015',
            'replace' => '\\1 E015',
        ],
    598 =>
        [
            'match'   => '(E015) 0947 E015',
            'replace' => '\\1 0947',
        ],
    599 =>
        [
            'match'   => '(E015) E1A8 E015',
            'replace' => '\\1 E1A8',
        ],
    600 =>
        [
            'match'   => '(E015) E1A7 E015',
            'replace' => '\\1 E1A7',
        ],
    601 =>
        [
            'match'   => '(E015) E199 E015',
            'replace' => '\\1 E199',
        ],
    602 =>
        [
            'match'   => '(E015) E1B2 E015',
            'replace' => '\\1 E1B2',
        ],
    603 =>
        [
            'match'   => '(E015) E1B1 E015',
            'replace' => '\\1 E1B1',
        ],
    604 =>
        [
            'match'   => '(E015) 0946 E015',
            'replace' => '\\1 0946',
        ],
    605 =>
        [
            'match'   => '(E015) E202 E015',
            'replace' => '\\1 E202',
        ],
    606 =>
        [
            'match'   => '(E015) E201 E015',
            'replace' => '\\1 E201',
        ],
    607 =>
        [
            'match'   => '(E015) E1A3 E015',
            'replace' => '\\1 E1A3',
        ],
    608 =>
        [
            'match'   => '(E015) E1C6 E015',
            'replace' => '\\1 E1C6',
        ],
    609 =>
        [
            'match'   => '(E015) E1C5 E015',
            'replace' => '\\1 E1C5',
        ],
    610 =>
        [
            'match'   => '(E015) 0945 E015',
            'replace' => '\\1 0945',
        ],
    611 =>
        [
            'match'   => '(E015) E200 E015',
            'replace' => '\\1 E200',
        ],
    612 =>
        [
            'match'   => '(E015) E1FF E015',
            'replace' => '\\1 E1FF',
        ],
    613 =>
        [
            'match'   => '(E015) 0948 E015',
            'replace' => '\\1 0948',
        ],
    614 =>
        [
            'match'   => '(E015) E1AA E015',
            'replace' => '\\1 E1AA',
        ],
    615 =>
        [
            'match'   => '(E015) E1A9 E015',
            'replace' => '\\1 E1A9',
        ],
    616 =>
        [
            'match'   => '(E015) E19A E015',
            'replace' => '\\1 E19A',
        ],
    617 =>
        [
            'match'   => '(E015) E1B4 E015',
            'replace' => '\\1 E1B4',
        ],
    618 =>
        [
            'match'   => '(E015) E1B3 E015',
            'replace' => '\\1 E1B3',
        ],
    619 =>
        [
            'match'   => '(E015) 0902 E015',
            'replace' => '\\1 0902',
        ],
    620 =>
        [
            'match'   => '(E015) E00F E015',
            'replace' => '\\1 E00F',
        ],
    621 =>
        [
            'match'   => '(E015) 0901 E015',
            'replace' => '\\1 0901',
        ],
    622 =>
        [
            'match'   => '(E015) 0953 E015',
            'replace' => '\\1 0953',
        ],
    623 =>
        [
            'match'   => '(E015) 0954 E015',
            'replace' => '\\1 0954',
        ],
    624 =>
        [
            'match'   => '(E015) 0951 E015',
            'replace' => '\\1 0951',
        ],
    625 =>
        [
            'match'   => '(E015) 0941 E015',
            'replace' => '\\1 0941',
        ],
    626 =>
        [
            'match'   => '(E015) 0942 E015',
            'replace' => '\\1 0942',
        ],
    627 =>
        [
            'match'   => '(E015) 0943 E015',
            'replace' => '\\1 0943',
        ],
    628 =>
        [
            'match'   => '(E015) 0944 E015',
            'replace' => '\\1 0944',
        ],
    629 =>
        [
            'match'   => '(E015) 0952 E015',
            'replace' => '\\1 0952',
        ],
    630 =>
        [
            'match'   => '(E015) E013 E015',
            'replace' => '\\1 E013',
        ],
    631 =>
        [
            'match'   => '(E015 (0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029)) 0915 E015',
            'replace' => '\\1 0915',
        ],
    632 =>
        [
            'match'   => '(E015 (0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029)) 0916 E015',
            'replace' => '\\1 0916',
        ],
    633 =>
        [
            'match'   => '(E015 (0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029)) 0917 E015',
            'replace' => '\\1 0917',
        ],
    634 =>
        [
            'match'   => '(E015 (0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029)) 0918 E015',
            'replace' => '\\1 0918',
        ],
    635 =>
        [
            'match'   => '(E015 (0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029)) 0919 E015',
            'replace' => '\\1 0919',
        ],
    636 =>
        [
            'match'   => '(E015 (0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029)) 091A E015',
            'replace' => '\\1 091A',
        ],
    637 =>
        [
            'match'   => '(E015 (0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029)) 091B E015',
            'replace' => '\\1 091B',
        ],
    638 =>
        [
            'match'   => '(E015 (0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029)) 091C E015',
            'replace' => '\\1 091C',
        ],
    639 =>
        [
            'match'   => '(E015 (0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029)) 091D E015',
            'replace' => '\\1 091D',
        ],
    640 =>
        [
            'match'   => '(E015 (0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029)) 091E E015',
            'replace' => '\\1 091E',
        ],
    641 =>
        [
            'match'   => '(E015 (0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029)) 091F E015',
            'replace' => '\\1 091F',
        ],
    642 =>
        [
            'match'   => '(E015 (0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029)) 0920 E015',
            'replace' => '\\1 0920',
        ],
    643 =>
        [
            'match'   => '(E015 (0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029)) 0921 E015',
            'replace' => '\\1 0921',
        ],
    644 =>
        [
            'match'   => '(E015 (0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029)) 0922 E015',
            'replace' => '\\1 0922',
        ],
    645 =>
        [
            'match'   => '(E015 (0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029)) 0923 E015',
            'replace' => '\\1 0923',
        ],
    646 =>
        [
            'match'   => '(E015 (0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029)) 0924 E015',
            'replace' => '\\1 0924',
        ],
    647 =>
        [
            'match'   => '(E015 (0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029)) 0925 E015',
            'replace' => '\\1 0925',
        ],
    648 =>
        [
            'match'   => '(E015 (0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029)) 0926 E015',
            'replace' => '\\1 0926',
        ],
    649 =>
        [
            'match'   => '(E015 (0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029)) 0927 E015',
            'replace' => '\\1 0927',
        ],
    650 =>
        [
            'match'   => '(E015 (0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029)) 0928 E015',
            'replace' => '\\1 0928',
        ],
    651 =>
        [
            'match'   => '(E015 (0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029)) 092A E015',
            'replace' => '\\1 092A',
        ],
    652 =>
        [
            'match'   => '(E015 (0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029)) 092B E015',
            'replace' => '\\1 092B',
        ],
    653 =>
        [
            'match'   => '(E015 (0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029)) 092C E015',
            'replace' => '\\1 092C',
        ],
    654 =>
        [
            'match'   => '(E015 (0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029)) 092D E015',
            'replace' => '\\1 092D',
        ],
    655 =>
        [
            'match'   => '(E015 (0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029)) 092E E015',
            'replace' => '\\1 092E',
        ],
    656 =>
        [
            'match'   => '(E015 (0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029)) 092F E015',
            'replace' => '\\1 092F',
        ],
    657 =>
        [
            'match'   => '(E015 (0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029)) 0930 E015',
            'replace' => '\\1 0930',
        ],
    658 =>
        [
            'match'   => '(E015 (0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029)) 0932 E015',
            'replace' => '\\1 0932',
        ],
    659 =>
        [
            'match'   => '(E015 (0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029)) 0933 E015',
            'replace' => '\\1 0933',
        ],
    660 =>
        [
            'match'   => '(E015 (0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029)) 0935 E015',
            'replace' => '\\1 0935',
        ],
    661 =>
        [
            'match'   => '(E015 (0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029)) 0936 E015',
            'replace' => '\\1 0936',
        ],
    662 =>
        [
            'match'   => '(E015 (0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029)) 0937 E015',
            'replace' => '\\1 0937',
        ],
    663 =>
        [
            'match'   => '(E015 (0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029)) 0938 E015',
            'replace' => '\\1 0938',
        ],
    664 =>
        [
            'match'   => '(E015 (0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029)) 0939 E015',
            'replace' => '\\1 0939',
        ],
    665 =>
        [
            'match'   => '(E015 (0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029)) E028 E015',
            'replace' => '\\1 E028',
        ],
    666 =>
        [
            'match'   => '(E015 (0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029)) E029 E015',
            'replace' => '\\1 E029',
        ],
    667 =>
        [
            'match'   => '(E015 (0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029)) E015 E015',
            'replace' => '\\1 E015',
        ],
    668 =>
        [
            'match'   => '(E015 (0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029)) 0947 E015',
            'replace' => '\\1 0947',
        ],
    669 =>
        [
            'match'   => '(E015 (0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029)) E1A8 E015',
            'replace' => '\\1 E1A8',
        ],
    670 =>
        [
            'match'   => '(E015 (0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029)) E1A7 E015',
            'replace' => '\\1 E1A7',
        ],
    671 =>
        [
            'match'   => '(E015 (0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029)) E199 E015',
            'replace' => '\\1 E199',
        ],
    672 =>
        [
            'match'   => '(E015 (0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029)) E1B2 E015',
            'replace' => '\\1 E1B2',
        ],
    673 =>
        [
            'match'   => '(E015 (0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029)) E1B1 E015',
            'replace' => '\\1 E1B1',
        ],
    674 =>
        [
            'match'   => '(E015 (0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029)) 0946 E015',
            'replace' => '\\1 0946',
        ],
    675 =>
        [
            'match'   => '(E015 (0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029)) E202 E015',
            'replace' => '\\1 E202',
        ],
    676 =>
        [
            'match'   => '(E015 (0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029)) E201 E015',
            'replace' => '\\1 E201',
        ],
    677 =>
        [
            'match'   => '(E015 (0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029)) E1A3 E015',
            'replace' => '\\1 E1A3',
        ],
    678 =>
        [
            'match'   => '(E015 (0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029)) E1C6 E015',
            'replace' => '\\1 E1C6',
        ],
    679 =>
        [
            'match'   => '(E015 (0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029)) E1C5 E015',
            'replace' => '\\1 E1C5',
        ],
    680 =>
        [
            'match'   => '(E015 (0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029)) 0945 E015',
            'replace' => '\\1 0945',
        ],
    681 =>
        [
            'match'   => '(E015 (0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029)) E200 E015',
            'replace' => '\\1 E200',
        ],
    682 =>
        [
            'match'   => '(E015 (0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029)) E1FF E015',
            'replace' => '\\1 E1FF',
        ],
    683 =>
        [
            'match'   => '(E015 (0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029)) 0948 E015',
            'replace' => '\\1 0948',
        ],
    684 =>
        [
            'match'   => '(E015 (0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029)) E1AA E015',
            'replace' => '\\1 E1AA',
        ],
    685 =>
        [
            'match'   => '(E015 (0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029)) E1A9 E015',
            'replace' => '\\1 E1A9',
        ],
    686 =>
        [
            'match'   => '(E015 (0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029)) E19A E015',
            'replace' => '\\1 E19A',
        ],
    687 =>
        [
            'match'   => '(E015 (0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029)) E1B4 E015',
            'replace' => '\\1 E1B4',
        ],
    688 =>
        [
            'match'   => '(E015 (0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029)) E1B3 E015',
            'replace' => '\\1 E1B3',
        ],
    689 =>
        [
            'match'   => '(E015 (0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029)) 0902 E015',
            'replace' => '\\1 0902',
        ],
    690 =>
        [
            'match'   => '(E015 (0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029)) E00F E015',
            'replace' => '\\1 E00F',
        ],
    691 =>
        [
            'match'   => '(E015 (0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029)) 0901 E015',
            'replace' => '\\1 0901',
        ],
    692 =>
        [
            'match'   => '(E015 (0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029)) 0953 E015',
            'replace' => '\\1 0953',
        ],
    693 =>
        [
            'match'   => '(E015 (0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029)) 0954 E015',
            'replace' => '\\1 0954',
        ],
    694 =>
        [
            'match'   => '(E015 (0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029)) 0951 E015',
            'replace' => '\\1 0951',
        ],
    695 =>
        [
            'match'   => '(E015 (0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029)) 0941 E015',
            'replace' => '\\1 0941',
        ],
    696 =>
        [
            'match'   => '(E015 (0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029)) 0942 E015',
            'replace' => '\\1 0942',
        ],
    697 =>
        [
            'match'   => '(E015 (0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029)) 0943 E015',
            'replace' => '\\1 0943',
        ],
    698 =>
        [
            'match'   => '(E015 (0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029)) 0944 E015',
            'replace' => '\\1 0944',
        ],
    699 =>
        [
            'match'   => '(E015 (0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029)) 0952 E015',
            'replace' => '\\1 0952',
        ],
    700 =>
        [
            'match'   => '(E015 (0915|0916|0917|0918|0919|091A|091B|091C|091D|091E|091F|0920|0921|0922|0923|0924|0925|0926|0927|0928|092A|092B|092C|092D|092E|092F|0930|0932|0933|0935|0936|0937|0938|0939|E028|E029)) E013 E015',
            'replace' => '\\1 E013',
        ],
    701 =>
        [
            'match'   => '(093F) E015',
            'replace' => '\\1 E015 093F',
        ],
    702 =>
        [
            'match'   => '093F E015',
            'replace' => 'E015',
        ],
    703 =>
        [
            'match'   => '0915 0962',
            'replace' => 'E153',
        ],
    704 =>
        [
            'match'   => '0915 0963',
            'replace' => 'E154',
        ],
    705 =>
        [
            'match'   => 'E08F 0941',
            'replace' => 'E155',
        ],
    706 =>
        [
            'match'   => 'E08F 0942',
            'replace' => 'E156',
        ],
    707 =>
        [
            'match'   => 'E08F 0943',
            'replace' => 'E157',
        ],
    708 =>
        [
            'match'   => 'E08F 0944',
            'replace' => 'E158',
        ],
    709 =>
        [
            'match'   => 'E091 0941',
            'replace' => 'E159',
        ],
    710 =>
        [
            'match'   => 'E091 0942',
            'replace' => 'E15A',
        ],
    711 =>
        [
            'match'   => 'E091 0943',
            'replace' => 'E15B',
        ],
    712 =>
        [
            'match'   => 'E091 0944',
            'replace' => 'E15C',
        ],
    713 =>
        [
            'match'   => 'E095 0941',
            'replace' => 'E15D',
        ],
    714 =>
        [
            'match'   => 'E095 0942',
            'replace' => 'E15E',
        ],
    715 =>
        [
            'match'   => 'E095 0943',
            'replace' => 'E15F',
        ],
    716 =>
        [
            'match'   => 'E095 0944',
            'replace' => 'E160',
        ],
    717 =>
        [
            'match'   => 'E096 0941',
            'replace' => 'E161',
        ],
    718 =>
        [
            'match'   => 'E096 0942',
            'replace' => 'E162',
        ],
    719 =>
        [
            'match'   => 'E096 0943',
            'replace' => 'E163',
        ],
    720 =>
        [
            'match'   => 'E096 0944',
            'replace' => 'E164',
        ],
    721 =>
        [
            'match'   => 'E097 0941',
            'replace' => 'E165',
        ],
    722 =>
        [
            'match'   => 'E097 0942',
            'replace' => 'E166',
        ],
    723 =>
        [
            'match'   => 'E097 0943',
            'replace' => 'E167',
        ],
    724 =>
        [
            'match'   => 'E097 0944',
            'replace' => 'E168',
        ],
    725 =>
        [
            'match'   => 'E098 0941',
            'replace' => 'E169',
        ],
    726 =>
        [
            'match'   => 'E098 0942',
            'replace' => 'E16A',
        ],
    727 =>
        [
            'match'   => 'E098 0943',
            'replace' => 'E16B',
        ],
    728 =>
        [
            'match'   => 'E098 0944',
            'replace' => 'E16C',
        ],
    729 =>
        [
            'match'   => '0926 0943',
            'replace' => 'E16D',
        ],
    730 =>
        [
            'match'   => '092A 0962',
            'replace' => 'E16E',
        ],
    731 =>
        [
            'match'   => '092A 0963',
            'replace' => 'E16F',
        ],
    732 =>
        [
            'match'   => '0939 0943',
            'replace' => 'E172',
        ],
    733 =>
        [
            'match'   => '0939 0944',
            'replace' => 'E173',
        ],
    734 =>
        [
            'match'   => 'E0B3 0941',
            'replace' => 'E174',
        ],
    735 =>
        [
            'match'   => 'E0B3 0942',
            'replace' => 'E175',
        ],
    736 =>
        [
            'match'   => 'E0B3 0943',
            'replace' => 'E176',
        ],
    737 =>
        [
            'match'   => 'E0B3 0944',
            'replace' => 'E177',
        ],
    738 =>
        [
            'match'   => 'E0B5 0941',
            'replace' => 'E178',
        ],
    739 =>
        [
            'match'   => 'E0B5 0942',
            'replace' => 'E179',
        ],
    740 =>
        [
            'match'   => 'E0B5 0943',
            'replace' => 'E17A',
        ],
    741 =>
        [
            'match'   => 'E0B5 0944',
            'replace' => 'E17B',
        ],
    742 =>
        [
            'match'   => 'E0B9 0941',
            'replace' => 'E17C',
        ],
    743 =>
        [
            'match'   => 'E0B9 0942',
            'replace' => 'E17D',
        ],
    744 =>
        [
            'match'   => 'E0B9 0943',
            'replace' => 'E17E',
        ],
    745 =>
        [
            'match'   => 'E0B9 0944',
            'replace' => 'E17F',
        ],
    746 =>
        [
            'match'   => 'E0BA 0941',
            'replace' => 'E180',
        ],
    747 =>
        [
            'match'   => 'E0BA 0942',
            'replace' => 'E181',
        ],
    748 =>
        [
            'match'   => 'E0BA 0943',
            'replace' => 'E182',
        ],
    749 =>
        [
            'match'   => 'E0BA 0944',
            'replace' => 'E183',
        ],
    750 =>
        [
            'match'   => 'E0BB 0941',
            'replace' => 'E184',
        ],
    751 =>
        [
            'match'   => 'E0BB 0942',
            'replace' => 'E185',
        ],
    752 =>
        [
            'match'   => 'E0BB 0943',
            'replace' => 'E186',
        ],
    753 =>
        [
            'match'   => 'E0BB 0944',
            'replace' => 'E187',
        ],
    754 =>
        [
            'match'   => 'E0BC 0941',
            'replace' => 'E188',
        ],
    755 =>
        [
            'match'   => 'E0BC 0942',
            'replace' => 'E189',
        ],
    756 =>
        [
            'match'   => 'E0BC 0943',
            'replace' => 'E18A',
        ],
    757 =>
        [
            'match'   => 'E0BC 0944',
            'replace' => 'E18B',
        ],
    758 =>
        [
            'match'   => 'E131 0941',
            'replace' => 'E18C',
        ],
    759 =>
        [
            'match'   => 'E131 0942',
            'replace' => 'E18D',
        ],
    760 =>
        [
            'match'   => 'E131 0943',
            'replace' => 'E18E',
        ],
    761 =>
        [
            'match'   => 'E131 0944',
            'replace' => 'E18F',
        ],
    762 =>
        [
            'match'   => 'E132 0941',
            'replace' => 'E190',
        ],
    763 =>
        [
            'match'   => 'E132 0942',
            'replace' => 'E191',
        ],
    764 =>
        [
            'match'   => 'E132 0943',
            'replace' => 'E192',
        ],
    765 =>
        [
            'match'   => 'E132 0944',
            'replace' => 'E193',
        ],
    766 =>
        [
            'match'   => '((0930|0931|E0A5|E0C9)) 0941',
            'replace' => '\\1 E170',
        ],
    767 =>
        [
            'match'   => '((0930|0931|E0A5|E0C9)) 0942',
            'replace' => '\\1 E171',
        ],
    768 =>
        [
            'match'   => '0947 E015',
            'replace' => 'E199',
        ],
    769 =>
        [
            'match'   => '0948 E015',
            'replace' => 'E19A',
        ],
    770 =>
        [
            'match'   => '0940 E015',
            'replace' => 'E19B',
        ],
    771 =>
        [
            'match'   => 'E194 E015',
            'replace' => 'E19C',
        ],
    772 =>
        [
            'match'   => 'E195 E015',
            'replace' => 'E19D',
        ],
    773 =>
        [
            'match'   => 'E196 E015',
            'replace' => 'E19E',
        ],
    774 =>
        [
            'match'   => 'E197 E015',
            'replace' => 'E19F',
        ],
    775 =>
        [
            'match'   => 'E198 E015',
            'replace' => 'E1A0',
        ],
    776 =>
        [
            'match'   => '094B E015',
            'replace' => 'E1A1',
        ],
    777 =>
        [
            'match'   => '094C E015',
            'replace' => 'E1A2',
        ],
    778 =>
        [
            'match'   => '0946 E015',
            'replace' => 'E1A3',
        ],
    779 =>
        [
            'match'   => '094A E015',
            'replace' => 'E1A4',
        ],
    780 =>
        [
            'match'   => '0908 0901',
            'replace' => 'E1A5',
        ],
    781 =>
        [
            'match'   => '0908 0902',
            'replace' => 'E1A6',
        ],
    782 =>
        [
            'match'   => 'E12F 0901',
            'replace' => 'E1C9',
        ],
    783 =>
        [
            'match'   => 'E130 0901',
            'replace' => 'E1CA',
        ],
    784 =>
        [
            'match'   => '0945 0901',
            'replace' => 'E200',
        ],
    785 =>
        [
            'match'   => '0946 0901',
            'replace' => 'E202',
        ],
    786 =>
        [
            'match'   => '0947 0901',
            'replace' => 'E1A7',
        ],
    787 =>
        [
            'match'   => '0948 0901',
            'replace' => 'E1A9',
        ],
    788 =>
        [
            'match'   => '094B 0901',
            'replace' => 'E1AB',
        ],
    789 =>
        [
            'match'   => '094C 0901',
            'replace' => 'E1AD',
        ],
    790 =>
        [
            'match'   => '0945 0902',
            'replace' => 'E1FF',
        ],
    791 =>
        [
            'match'   => '0946 0902',
            'replace' => 'E201',
        ],
    792 =>
        [
            'match'   => '0947 0902',
            'replace' => 'E1A8',
        ],
    793 =>
        [
            'match'   => '0948 0902',
            'replace' => 'E1AA',
        ],
    794 =>
        [
            'match'   => '094B 0902',
            'replace' => 'E1AC',
        ],
    795 =>
        [
            'match'   => '094C 0902',
            'replace' => 'E1AE',
        ],
    796 =>
        [
            'match'   => 'E015 0902 0901',
            'replace' => 'E1B0 E1AF',
        ],
    797 =>
        [
            'match'   => 'E199 0902 0901',
            'replace' => 'E1B2 E1B1',
        ],
    798 =>
        [
            'match'   => 'E19A 0902 0901',
            'replace' => 'E1B4 E1B3',
        ],
    799 =>
        [
            'match'   => 'E1A1 0902 0901',
            'replace' => 'E1C2 E1C1',
        ],
    800 =>
        [
            'match'   => 'E1A3 0902 0901',
            'replace' => 'E1C6 E1C5',
        ],
    801 =>
        [
            'match'   => 'E1A4 0902 0901',
            'replace' => 'E1C8 E1C7',
        ],
    802 =>
        [
            'match'   => 'E1A2 0902 0901',
            'replace' => 'E1C4 E1C3',
        ],
    803 =>
        [
            'match'   => 'E19B 0901',
            'replace' => 'E1B5',
        ],
    804 =>
        [
            'match'   => 'E19C 0901',
            'replace' => 'E1B6',
        ],
    805 =>
        [
            'match'   => 'E19D 0901',
            'replace' => 'E1B7',
        ],
    806 =>
        [
            'match'   => 'E19E 0901',
            'replace' => 'E1B8',
        ],
    807 =>
        [
            'match'   => 'E19F 0901',
            'replace' => 'E1B9',
        ],
    808 =>
        [
            'match'   => 'E1A0 0901',
            'replace' => 'E1BA',
        ],
    809 =>
        [
            'match'   => 'E19B 0902',
            'replace' => 'E1BB',
        ],
    810 =>
        [
            'match'   => 'E19C 0902',
            'replace' => 'E1BC',
        ],
    811 =>
        [
            'match'   => 'E19D 0902',
            'replace' => 'E1BD',
        ],
    812 =>
        [
            'match'   => 'E19E 0902',
            'replace' => 'E1BE',
        ],
    813 =>
        [
            'match'   => 'E19F 0902',
            'replace' => 'E1BF',
        ],
    814 =>
        [
            'match'   => 'E1A0 0902',
            'replace' => 'E1C0',
        ],
    815 =>
        [
            'match'   => '((0908|E01B|090D|090E|E01F|0910|E021|0914|E025|E016|E017|0940|E194|E195|E196|E197|E198|E19B|E19C|E19D|E19E|E19F|E1A0|0949|094A|E1A4|094B|E1A1|094C|E1A2|E015|0947|E1A8|E1A7|E199|E1B2|E1B1|0946|E202|E201|E1A3|E1C6|E1C5|0945|E200|E1FF|0948|E1AA|E1A9|E19A|E1B4|E1B3|0902|E00F|0901|0953|0954|0951)) 0901',
            'replace' => '\\1 E00F',
        ],
    816 =>
        [
            'match'   => '093F ((0930|0931))',
            'replace' => 'E14B \\1',
        ],
    817 =>
        [
            'match'   => '093F ((0915|0958|E08B|E0AF|0919|E02B|E08F|E0B3|091F|E030|E095|E0B9|0920|E031|E096|E0BA|0921|095C|E097|E0BB|0922|095D|E098|E0BC|0926|E035|E09C|E0C0|092B|095E|E0A0|E0C4|0939|E040|E0AC|E0D0|E120|E122|E124|E125|E129|E134|E139))',
            'replace' => 'E14C \\1',
        ],
    818 =>
        [
            'match'   => '093F ((0924|E033|E09A|E0BE|092A|E037|E09F|E0C3|092C|E038|E0A1|E0C5|0935|E03C|E0A8|E0CC|0937|E03E|E0AA|E0CE|E11B|E11E|E121|E123|E126|E12F|E130|E131|E132|E137|E138))',
            'replace' => 'E14D \\1',
        ],
    819 =>
        [
            'match'   => '093F ((0917|095A|E08D|E0B1|0918|E02A|E08E|E0B2|091A|E02C|E090|E0B4|091B|E02D|E091|E0B5|091E|E02F|E094|E0B8|0925|E034|0927|E036|E09D|E0C1|0928|0929|E09E|E0C2|092D|E039|E0A2|E0C6|092E|E03A|E0A3|E0C7|092F|095F|E0A4|E0C8|0932|E03B|E0A6|E0CA|0933|0934|E0A7|E0CB|0936|E03D|E0A9|E0CD|0938|E03F|E0AB|E0CF|E127|E128|E12A|E135|E136))',
            'replace' => 'E14E \\1',
        ],
    820 =>
        [
            'match'   => '((0940|094A|094B|094C|0946|0947|0948)) E015',
            'replace' => '\\1 E014',
        ],
    821 =>
        [
            'match'   => '((0919|E02B|E08F|E0B3|091F|E030|E095|E0B9|0922|095D|E098|E0BC|0926|E035|E09C|E0C0|0930|0931|0932|E03B|E0A6|E0CA|0939|E040|E0AC|E0D0)) 0940',
            'replace' => '\\1 E194',
        ],
    822 =>
        [
            'match'   => '((0919|E02B|E08F|E0B3|091F|E030|E095|E0B9|0922|095D|E098|E0BC|0926|E035|E09C|E0C0|0930|0931|0932|E03B|E0A6|E0CA|0939|E040|E0AC|E0D0)) E19B',
            'replace' => '\\1 E19C',
        ],
    823 =>
        [
            'match'   => '((0919|E02B|E08F|E0B3|091F|E030|E095|E0B9|0922|095D|E098|E0BC|0926|E035|E09C|E0C0|0930|0931|0932|E03B|E0A6|E0CA|0939|E040|E0AC|E0D0)) E1B5',
            'replace' => '\\1 E1B6',
        ],
    824 =>
        [
            'match'   => '((0919|E02B|E08F|E0B3|091F|E030|E095|E0B9|0922|095D|E098|E0BC|0926|E035|E09C|E0C0|0930|0931|0932|E03B|E0A6|E0CA|0939|E040|E0AC|E0D0)) E1BB',
            'replace' => '\\1 E1BC',
        ],
    825 =>
        [
            'match'   => '((091B|E02D|E091|E0B5|0920|E031|E096|E0BA)) 0940',
            'replace' => '\\1 E195',
        ],
    826 =>
        [
            'match'   => '((091B|E02D|E091|E0B5|0920|E031|E096|E0BA)) E19B',
            'replace' => '\\1 E19D',
        ],
    827 =>
        [
            'match'   => '((091B|E02D|E091|E0B5|0920|E031|E096|E0BA)) E1B5',
            'replace' => '\\1 E1B7',
        ],
    828 =>
        [
            'match'   => '((091B|E02D|E091|E0B5|0920|E031|E096|E0BA)) E1BB',
            'replace' => '\\1 E1BD',
        ],
    829 =>
        [
            'match'   => '((0915|0958|E08B|E0AF|092B|095E|E0A0|E0C4)) 0940',
            'replace' => '\\1 E198',
        ],
    830 =>
        [
            'match'   => '((0915|0958|E08B|E0AF|092B|095E|E0A0|E0C4)) E19B',
            'replace' => '\\1 E1A0',
        ],
    831 =>
        [
            'match'   => '((0915|0958|E08B|E0AF|092B|095E|E0A0|E0C4)) E1B5',
            'replace' => '\\1 E1BA',
        ],
    832 =>
        [
            'match'   => '((0915|0958|E08B|E0AF|092B|095E|E0A0|E0C4)) E1BB',
            'replace' => '\\1 E1C0',
        ],
];
?>