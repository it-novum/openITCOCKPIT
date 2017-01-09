<?php
$volt = [
    0   =>
        [
            'match'   => '0B4D 200C',
            'replace' => '2018',
        ],
    1   =>
        [
            'match'   => '0B15 0B4D 0B37',
            'replace' => 'E003',
        ],
    2   =>
        [
            'match'   => '0B1C 0B4D 0B1E',
            'replace' => 'E004',
        ],
    3   =>
        [
            'match'   => '((0B15|0B16|0B17|0B18|0B19|0B1A|0B1B|0B1C|0B1D|0B1E|0B1F|0B20|0B21|0B22|0B23|0B24|0B25|0B26|0B27|0B28|0B2A|0B2B|0B2C|0B2D|0B2E|0B2F|0B30|0B32|0B33|0B35|0B36|0B37|0B38|0B39|0B71|E003|E004|E005|E006|E007|E008|E009|E00A|E00B|E00C|E00D|E00E|E00F|E010|E011|E012|E013|E014|E015|E016|E017|E018|E019|E01A|E01B|E01C|E01D|E01E|E01F|E020|E021|E023|E024|E025|E026|E027|E028|E029|E02A|E02B|E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037)) 0B30 0B4D',
            'replace' => '\\1 E069',
        ],
    4   =>
        [
            'match'   => '((0B01|0B3F|0B56|E06B|E041|E064|E06D|E070|E089|E08C)) 0B30 0B4D',
            'replace' => '\\1 E069',
        ],
    5   =>
        [
            'match'   => '((0B3E|0B40|E044|0B57|E068|E074|E08B|E08F)) 0B30 0B4D',
            'replace' => '\\1 E069',
        ],
    6   =>
        [
            'match'   => '(0B3C) 0B30 0B4D',
            'replace' => '\\1 E069',
        ],
    7   =>
        [
            'match'   => '(25CC) 0B30 0B4D',
            'replace' => '\\1 E069',
        ],
    8   =>
        [
            'match'   => '((0B15|0B19|0B1A|0B1B|0B1C|0B1D|0B1E|0B20|0B21|0B22|0B24|0B26|0B28|0B2C|0B2D|0B32|0B33|0B35|0B39|25CC)) E069',
            'replace' => '\\1 E06B',
        ],
    9   =>
        [
            'match'   => '((0B15|0B19|0B1A|0B1B|0B1C|0B1D|0B1E|0B20|0B21|0B22|0B24|0B26|0B28|0B2C|0B2D|0B32|0B33|0B35|0B39|25CC) 0B3C) E069',
            'replace' => '\\1 E06B',
        ],
    10  =>
        [
            'match'   => '0B38 0B4D 0B24 0B4D 0B30',
            'replace' => 'E01B',
        ],
    11  =>
        [
            'match'   => '0B28 0B4D 0B24 0B4D 0B30',
            'replace' => 'E01D',
        ],
    12  =>
        [
            'match'   => '0B28 0B4D 0B24 0B4D 0B30',
            'replace' => 'E01C',
        ],
    13  =>
        [
            'match'   => '0B56 E069',
            'replace' => 'E070',
        ],
    14  =>
        [
            'match'   => '0B57 E069',
            'replace' => 'E074',
        ],
    15  =>
        [
            'match'   => '0B3F E069',
            'replace' => 'E06D',
        ],
    16  =>
        [
            'match'   => '0B40 E069',
            'replace' => 'E14D',
        ],
    17  =>
        [
            'match'   => '0B24 0B4D 0B17 0B4D 0B27',
            'replace' => 'E036',
        ],
    18  =>
        [
            'match'   => '0B24 0B4D 0B38 0B4D 0B28',
            'replace' => 'E030',
        ],
    19  =>
        [
            'match'   => '0B19 0B4D 0B15',
            'replace' => 'E005',
        ],
    20  =>
        [
            'match'   => '0B19 0B4D 0B16',
            'replace' => 'E006',
        ],
    21  =>
        [
            'match'   => '0B19 0B4D 0B17',
            'replace' => 'E007',
        ],
    22  =>
        [
            'match'   => '0B19 0B4D 0B18',
            'replace' => 'E008',
        ],
    23  =>
        [
            'match'   => '0B1A 0B4D 0B1A',
            'replace' => 'E009',
        ],
    24  =>
        [
            'match'   => '0B1F 0B4D 0B1F',
            'replace' => 'E00A',
        ],
    25  =>
        [
            'match'   => '0B24 0B4D 0B24',
            'replace' => 'E00B',
        ],
    26  =>
        [
            'match'   => '0B26 0B4D 0B27',
            'replace' => 'E00C',
        ],
    27  =>
        [
            'match'   => '0B26 0B4D 0B26',
            'replace' => 'E00D',
        ],
    28  =>
        [
            'match'   => '0B23 0B4D 0B23',
            'replace' => 'E00F',
        ],
    29  =>
        [
            'match'   => '0B1E 0B4D 0B1A',
            'replace' => 'E011',
        ],
    30  =>
        [
            'match'   => '0B1E 0B4D 0B1D',
            'replace' => 'E012',
        ],
    31  =>
        [
            'match'   => '0B1E 0B4D 0B1C',
            'replace' => 'E013',
        ],
    32  =>
        [
            'match'   => '0B26 0B4D 0B2D',
            'replace' => 'E014',
        ],
    33  =>
        [
            'match'   => '0B27 0B4D 0B27',
            'replace' => 'E015',
        ],
    34  =>
        [
            'match'   => '0B2C 0B4D 0B26',
            'replace' => 'E016',
        ],
    35  =>
        [
            'match'   => '0B28 0B4D 0B26',
            'replace' => 'E017',
        ],
    36  =>
        [
            'match'   => '0B28 0B4D 0B27',
            'replace' => 'E018',
        ],
    37  =>
        [
            'match'   => '0B2E 0B4D 0B2B',
            'replace' => 'E019',
        ],
    38  =>
        [
            'match'   => '0B2E 0B4D 0B2A',
            'replace' => 'E01A',
        ],
    39  =>
        [
            'match'   => '0B37 0B4D 0B23',
            'replace' => 'E010',
        ],
    40  =>
        [
            'match'   => '0B39 0B4D 0B28',
            'replace' => 'E01E',
        ],
    41  =>
        [
            'match'   => '0B39 0B4D 0B35',
            'replace' => 'E01F',
        ],
    42  =>
        [
            'match'   => '0B39 0B4D 0B2E',
            'replace' => 'E020',
        ],
    43  =>
        [
            'match'   => '0B1A 0B4D 0B1B',
            'replace' => 'E021',
        ],
    44  =>
        [
            'match'   => '0B1E 0B4D 0B1B',
            'replace' => 'E023',
        ],
    45  =>
        [
            'match'   => '0B2E 0B4D 0B2D',
            'replace' => 'E024',
        ],
    46  =>
        [
            'match'   => '0B28 0B4D 0B24',
            'replace' => 'E025',
        ],
    47  =>
        [
            'match'   => '0B38 0B4D 0B24',
            'replace' => 'E026',
        ],
    48  =>
        [
            'match'   => '0B2A 0B4D 0B24',
            'replace' => 'E027',
        ],
    49  =>
        [
            'match'   => '0B15 0B4D 0B24',
            'replace' => 'E028',
        ],
    50  =>
        [
            'match'   => '0B23 0B4D 0B21',
            'replace' => 'E029',
        ],
    51  =>
        [
            'match'   => '0B24 0B4D 0B15',
            'replace' => 'E02A',
        ],
    52  =>
        [
            'match'   => '0B24 0B4D 0B38',
            'replace' => 'E02B',
        ],
    53  =>
        [
            'match'   => '0B24 0B4D 0B2A',
            'replace' => 'E02C',
        ],
    54  =>
        [
            'match'   => '0B23 0B4D 0B22',
            'replace' => 'E031',
        ],
    55  =>
        [
            'match'   => '0B36 0B4D 0B1B',
            'replace' => 'E032',
        ],
    56  =>
        [
            'match'   => '0B24 0B4D 0B28',
            'replace' => 'E033',
        ],
    57  =>
        [
            'match'   => '0B24 0B4D 0B2E',
            'replace' => 'E034',
        ],
    58  =>
        [
            'match'   => '0B17 0B4D 0B27',
            'replace' => 'E035',
        ],
    59  =>
        [
            'match'   => '0B24 0B4D 0B2B',
            'replace' => 'E037',
        ],
    60  =>
        [
            'match'   => '0B2E 0B4D 0B2E',
            'replace' => 'E14A',
        ],
    61  =>
        [
            'match'   => '0B21 0B3C',
            'replace' => '0B5C',
        ],
    62  =>
        [
            'match'   => '0B22 0B3C',
            'replace' => '0B5D',
        ],
    63  =>
        [
            'match'   => '((0B15|0B16|0B17|0B18|0B19|0B1A|0B1B|0B1C|0B1D|0B1E|0B1F|0B20|0B21|0B22|0B23|0B24|0B25|0B26|0B27|0B28|0B2A|0B2B|0B2C|0B2D|0B2E|0B2F|0B30|0B32|0B33|0B35|0B36|0B37|0B38|0B39|0B71|E003|E004|E005|E006|E007|E008|E009|E00A|E00B|E00C|E00D|E00E|E00F|E010|E011|E012|E013|E014|E015|E016|E017|E018|E019|E01A|E01B|E01C|E01D|E01E|E01F|E020|E021|E023|E024|E025|E026|E027|E028|E029|E02A|E02B|E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037)) 0B4D',
            'replace' => '\\1 2019',
        ],
    64  =>
        [
            'match'   => '((0B01|0B3F|0B56|E06B|E041|E064|E06D|E070|E089|E08C)) 0B4D',
            'replace' => '\\1 2019',
        ],
    65  =>
        [
            'match'   => '(E069) 0B4D',
            'replace' => '\\1 2019',
        ],
    66  =>
        [
            'match'   => '((0B41|E045|0B42|0B43|E053|E056|E059|0B3C)) 0B4D',
            'replace' => '\\1 2019',
        ],
    67  =>
        [
            'match'   => '(200D) 0B4D',
            'replace' => '\\1 2019',
        ],
    68  =>
        [
            'match'   => '(25CC) 0B4D',
            'replace' => '\\1 2019',
        ],
    69  =>
        [
            'match'   => '(0020) 0B4D',
            'replace' => '\\1 2019',
        ],
    70  =>
        [
            'match'   => '200D 2019',
            'replace' => '2019',
        ],
    71  =>
        [
            'match'   => '2019 0B30',
            'replace' => 'E075',
        ],
    72  =>
        [
            'match'   => '2019 0B5F',
            'replace' => 'E077',
        ],
    73  =>
        [
            'match'   => '2019 0B35',
            'replace' => 'E078',
        ],
    74  =>
        [
            'match'   => '2019 0B32',
            'replace' => 'E110',
        ],
    75  =>
        [
            'match'   => '2019 0B33',
            'replace' => 'E111',
        ],
    76  =>
        [
            'match'   => '2019 0B2E',
            'replace' => 'E11C',
        ],
    77  =>
        [
            'match'   => '2019 0B15',
            'replace' => 'E0F6',
        ],
    78  =>
        [
            'match'   => '2019 0B16',
            'replace' => 'E0F7',
        ],
    79  =>
        [
            'match'   => '2019 0B17',
            'replace' => 'E0F8',
        ],
    80  =>
        [
            'match'   => '2019 0B18',
            'replace' => 'E0F9',
        ],
    81  =>
        [
            'match'   => '2019 0B19',
            'replace' => 'E0FA',
        ],
    82  =>
        [
            'match'   => '2019 0B1A',
            'replace' => 'E0FB',
        ],
    83  =>
        [
            'match'   => '2019 0B1B',
            'replace' => 'E0FC',
        ],
    84  =>
        [
            'match'   => '2019 0B1C',
            'replace' => 'E0FD',
        ],
    85  =>
        [
            'match'   => '2019 0B1D',
            'replace' => 'E0FE',
        ],
    86  =>
        [
            'match'   => '2019 0B1E',
            'replace' => 'E0FF',
        ],
    87  =>
        [
            'match'   => '2019 0B1F',
            'replace' => 'E100',
        ],
    88  =>
        [
            'match'   => '2019 0B20',
            'replace' => 'E101',
        ],
    89  =>
        [
            'match'   => '2019 0B21',
            'replace' => 'E102',
        ],
    90  =>
        [
            'match'   => '2019 0B22',
            'replace' => 'E103',
        ],
    91  =>
        [
            'match'   => '2019 0B23',
            'replace' => 'E104',
        ],
    92  =>
        [
            'match'   => '2019 0B24',
            'replace' => 'E105',
        ],
    93  =>
        [
            'match'   => '2019 0B25',
            'replace' => 'E106',
        ],
    94  =>
        [
            'match'   => '2019 0B26',
            'replace' => 'E107',
        ],
    95  =>
        [
            'match'   => '2019 0B27',
            'replace' => 'E108',
        ],
    96  =>
        [
            'match'   => '2019 0B28',
            'replace' => 'E109',
        ],
    97  =>
        [
            'match'   => '2019 0B2A',
            'replace' => 'E10A',
        ],
    98  =>
        [
            'match'   => '2019 0B2B',
            'replace' => 'E10B',
        ],
    99  =>
        [
            'match'   => '2019 0B2C',
            'replace' => 'E10C',
        ],
    100 =>
        [
            'match'   => '2019 0B2D',
            'replace' => 'E10D',
        ],
    101 =>
        [
            'match'   => '2019 0B2E',
            'replace' => 'E10E',
        ],
    102 =>
        [
            'match'   => '2019 0B2F',
            'replace' => 'E10F',
        ],
    103 =>
        [
            'match'   => '2019 0B32',
            'replace' => 'E110',
        ],
    104 =>
        [
            'match'   => '2019 0B33',
            'replace' => 'E111',
        ],
    105 =>
        [
            'match'   => '2019 0B35',
            'replace' => 'E112',
        ],
    106 =>
        [
            'match'   => '2019 0B36',
            'replace' => 'E113',
        ],
    107 =>
        [
            'match'   => '2019 0B37',
            'replace' => 'E114',
        ],
    108 =>
        [
            'match'   => '2019 0B38',
            'replace' => 'E115',
        ],
    109 =>
        [
            'match'   => '2019 0B39',
            'replace' => 'E116',
        ],
    110 =>
        [
            'match'   => '2019 E003',
            'replace' => 'E119',
        ],
    111 =>
        [
            'match'   => '2019 E004',
            'replace' => 'E11A',
        ],
    112 =>
        [
            'match'   => '2019 0B35',
            'replace' => 'E078',
        ],
    113 =>
        [
            'match'   => '2019 0B71',
            'replace' => 'E078',
        ],
    114 =>
        [
            'match'   => '0B15 ((E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E090 \\1',
        ],
    115 =>
        [
            'match'   => '0B16 ((E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E091 \\1',
        ],
    116 =>
        [
            'match'   => '0B17 ((E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E092 \\1',
        ],
    117 =>
        [
            'match'   => '0B18 ((E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E093 \\1',
        ],
    118 =>
        [
            'match'   => '0B19 ((E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E094 \\1',
        ],
    119 =>
        [
            'match'   => '0B1A ((E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E095 \\1',
        ],
    120 =>
        [
            'match'   => '0B1B ((E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E096 \\1',
        ],
    121 =>
        [
            'match'   => '0B1C ((E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E097 \\1',
        ],
    122 =>
        [
            'match'   => '0B1D ((E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E098 \\1',
        ],
    123 =>
        [
            'match'   => '0B1E ((E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E099 \\1',
        ],
    124 =>
        [
            'match'   => '0B1F ((E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E09A \\1',
        ],
    125 =>
        [
            'match'   => '0B20 ((E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E09B \\1',
        ],
    126 =>
        [
            'match'   => '0B21 ((E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E09C \\1',
        ],
    127 =>
        [
            'match'   => '0B22 ((E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E09D \\1',
        ],
    128 =>
        [
            'match'   => '0B23 ((E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E09E \\1',
        ],
    129 =>
        [
            'match'   => '0B24 ((E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E09F \\1',
        ],
    130 =>
        [
            'match'   => '0B25 ((E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0A0 \\1',
        ],
    131 =>
        [
            'match'   => '0B26 ((E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0A1 \\1',
        ],
    132 =>
        [
            'match'   => '0B27 ((E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0A2 \\1',
        ],
    133 =>
        [
            'match'   => '0B28 ((E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0A3 \\1',
        ],
    134 =>
        [
            'match'   => '0B2A ((E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0A4 \\1',
        ],
    135 =>
        [
            'match'   => '0B2B ((E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0A5 \\1',
        ],
    136 =>
        [
            'match'   => '0B2C ((E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0A6 \\1',
        ],
    137 =>
        [
            'match'   => '0B2D ((E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0A7 \\1',
        ],
    138 =>
        [
            'match'   => '0B2E ((E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0A8 \\1',
        ],
    139 =>
        [
            'match'   => '0B2F ((E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0A9 \\1',
        ],
    140 =>
        [
            'match'   => '0B32 ((E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0AA \\1',
        ],
    141 =>
        [
            'match'   => '0B33 ((E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0AB \\1',
        ],
    142 =>
        [
            'match'   => '0B35 ((E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0AC \\1',
        ],
    143 =>
        [
            'match'   => '0B36 ((E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0AD \\1',
        ],
    144 =>
        [
            'match'   => '0B37 ((E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0AE \\1',
        ],
    145 =>
        [
            'match'   => '0B38 ((E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0AF \\1',
        ],
    146 =>
        [
            'match'   => '0B39 ((E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0B0 \\1',
        ],
    147 =>
        [
            'match'   => 'E003 ((E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0B1 \\1',
        ],
    148 =>
        [
            'match'   => 'E004 ((E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0B2 \\1',
        ],
    149 =>
        [
            'match'   => '0B15 ((0B01|0B3F|0B56|E06B|E041|E064|E06D|E070|E089|E08C) (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E090 \\1',
        ],
    150 =>
        [
            'match'   => '0B16 ((0B01|0B3F|0B56|E06B|E041|E064|E06D|E070|E089|E08C) (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E091 \\1',
        ],
    151 =>
        [
            'match'   => '0B17 ((0B01|0B3F|0B56|E06B|E041|E064|E06D|E070|E089|E08C) (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E092 \\1',
        ],
    152 =>
        [
            'match'   => '0B18 ((0B01|0B3F|0B56|E06B|E041|E064|E06D|E070|E089|E08C) (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E093 \\1',
        ],
    153 =>
        [
            'match'   => '0B19 ((0B01|0B3F|0B56|E06B|E041|E064|E06D|E070|E089|E08C) (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E094 \\1',
        ],
    154 =>
        [
            'match'   => '0B1A ((0B01|0B3F|0B56|E06B|E041|E064|E06D|E070|E089|E08C) (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E095 \\1',
        ],
    155 =>
        [
            'match'   => '0B1B ((0B01|0B3F|0B56|E06B|E041|E064|E06D|E070|E089|E08C) (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E096 \\1',
        ],
    156 =>
        [
            'match'   => '0B1C ((0B01|0B3F|0B56|E06B|E041|E064|E06D|E070|E089|E08C) (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E097 \\1',
        ],
    157 =>
        [
            'match'   => '0B1D ((0B01|0B3F|0B56|E06B|E041|E064|E06D|E070|E089|E08C) (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E098 \\1',
        ],
    158 =>
        [
            'match'   => '0B1E ((0B01|0B3F|0B56|E06B|E041|E064|E06D|E070|E089|E08C) (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E099 \\1',
        ],
    159 =>
        [
            'match'   => '0B1F ((0B01|0B3F|0B56|E06B|E041|E064|E06D|E070|E089|E08C) (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E09A \\1',
        ],
    160 =>
        [
            'match'   => '0B20 ((0B01|0B3F|0B56|E06B|E041|E064|E06D|E070|E089|E08C) (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E09B \\1',
        ],
    161 =>
        [
            'match'   => '0B21 ((0B01|0B3F|0B56|E06B|E041|E064|E06D|E070|E089|E08C) (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E09C \\1',
        ],
    162 =>
        [
            'match'   => '0B22 ((0B01|0B3F|0B56|E06B|E041|E064|E06D|E070|E089|E08C) (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E09D \\1',
        ],
    163 =>
        [
            'match'   => '0B23 ((0B01|0B3F|0B56|E06B|E041|E064|E06D|E070|E089|E08C) (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E09E \\1',
        ],
    164 =>
        [
            'match'   => '0B24 ((0B01|0B3F|0B56|E06B|E041|E064|E06D|E070|E089|E08C) (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E09F \\1',
        ],
    165 =>
        [
            'match'   => '0B25 ((0B01|0B3F|0B56|E06B|E041|E064|E06D|E070|E089|E08C) (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0A0 \\1',
        ],
    166 =>
        [
            'match'   => '0B26 ((0B01|0B3F|0B56|E06B|E041|E064|E06D|E070|E089|E08C) (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0A1 \\1',
        ],
    167 =>
        [
            'match'   => '0B27 ((0B01|0B3F|0B56|E06B|E041|E064|E06D|E070|E089|E08C) (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0A2 \\1',
        ],
    168 =>
        [
            'match'   => '0B28 ((0B01|0B3F|0B56|E06B|E041|E064|E06D|E070|E089|E08C) (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0A3 \\1',
        ],
    169 =>
        [
            'match'   => '0B2A ((0B01|0B3F|0B56|E06B|E041|E064|E06D|E070|E089|E08C) (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0A4 \\1',
        ],
    170 =>
        [
            'match'   => '0B2B ((0B01|0B3F|0B56|E06B|E041|E064|E06D|E070|E089|E08C) (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0A5 \\1',
        ],
    171 =>
        [
            'match'   => '0B2C ((0B01|0B3F|0B56|E06B|E041|E064|E06D|E070|E089|E08C) (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0A6 \\1',
        ],
    172 =>
        [
            'match'   => '0B2D ((0B01|0B3F|0B56|E06B|E041|E064|E06D|E070|E089|E08C) (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0A7 \\1',
        ],
    173 =>
        [
            'match'   => '0B2E ((0B01|0B3F|0B56|E06B|E041|E064|E06D|E070|E089|E08C) (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0A8 \\1',
        ],
    174 =>
        [
            'match'   => '0B2F ((0B01|0B3F|0B56|E06B|E041|E064|E06D|E070|E089|E08C) (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0A9 \\1',
        ],
    175 =>
        [
            'match'   => '0B32 ((0B01|0B3F|0B56|E06B|E041|E064|E06D|E070|E089|E08C) (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0AA \\1',
        ],
    176 =>
        [
            'match'   => '0B33 ((0B01|0B3F|0B56|E06B|E041|E064|E06D|E070|E089|E08C) (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0AB \\1',
        ],
    177 =>
        [
            'match'   => '0B35 ((0B01|0B3F|0B56|E06B|E041|E064|E06D|E070|E089|E08C) (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0AC \\1',
        ],
    178 =>
        [
            'match'   => '0B36 ((0B01|0B3F|0B56|E06B|E041|E064|E06D|E070|E089|E08C) (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0AD \\1',
        ],
    179 =>
        [
            'match'   => '0B37 ((0B01|0B3F|0B56|E06B|E041|E064|E06D|E070|E089|E08C) (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0AE \\1',
        ],
    180 =>
        [
            'match'   => '0B38 ((0B01|0B3F|0B56|E06B|E041|E064|E06D|E070|E089|E08C) (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0AF \\1',
        ],
    181 =>
        [
            'match'   => '0B39 ((0B01|0B3F|0B56|E06B|E041|E064|E06D|E070|E089|E08C) (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0B0 \\1',
        ],
    182 =>
        [
            'match'   => 'E003 ((0B01|0B3F|0B56|E06B|E041|E064|E06D|E070|E089|E08C) (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0B1 \\1',
        ],
    183 =>
        [
            'match'   => 'E004 ((0B01|0B3F|0B56|E06B|E041|E064|E06D|E070|E089|E08C) (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0B2 \\1',
        ],
    184 =>
        [
            'match'   => '0B15 (0B3C (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E090 \\1',
        ],
    185 =>
        [
            'match'   => '0B16 (0B3C (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E091 \\1',
        ],
    186 =>
        [
            'match'   => '0B17 (0B3C (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E092 \\1',
        ],
    187 =>
        [
            'match'   => '0B18 (0B3C (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E093 \\1',
        ],
    188 =>
        [
            'match'   => '0B19 (0B3C (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E094 \\1',
        ],
    189 =>
        [
            'match'   => '0B1A (0B3C (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E095 \\1',
        ],
    190 =>
        [
            'match'   => '0B1B (0B3C (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E096 \\1',
        ],
    191 =>
        [
            'match'   => '0B1C (0B3C (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E097 \\1',
        ],
    192 =>
        [
            'match'   => '0B1D (0B3C (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E098 \\1',
        ],
    193 =>
        [
            'match'   => '0B1E (0B3C (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E099 \\1',
        ],
    194 =>
        [
            'match'   => '0B1F (0B3C (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E09A \\1',
        ],
    195 =>
        [
            'match'   => '0B20 (0B3C (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E09B \\1',
        ],
    196 =>
        [
            'match'   => '0B21 (0B3C (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E09C \\1',
        ],
    197 =>
        [
            'match'   => '0B22 (0B3C (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E09D \\1',
        ],
    198 =>
        [
            'match'   => '0B23 (0B3C (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E09E \\1',
        ],
    199 =>
        [
            'match'   => '0B24 (0B3C (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E09F \\1',
        ],
    200 =>
        [
            'match'   => '0B25 (0B3C (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0A0 \\1',
        ],
    201 =>
        [
            'match'   => '0B26 (0B3C (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0A1 \\1',
        ],
    202 =>
        [
            'match'   => '0B27 (0B3C (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0A2 \\1',
        ],
    203 =>
        [
            'match'   => '0B28 (0B3C (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0A3 \\1',
        ],
    204 =>
        [
            'match'   => '0B2A (0B3C (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0A4 \\1',
        ],
    205 =>
        [
            'match'   => '0B2B (0B3C (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0A5 \\1',
        ],
    206 =>
        [
            'match'   => '0B2C (0B3C (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0A6 \\1',
        ],
    207 =>
        [
            'match'   => '0B2D (0B3C (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0A7 \\1',
        ],
    208 =>
        [
            'match'   => '0B2E (0B3C (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0A8 \\1',
        ],
    209 =>
        [
            'match'   => '0B2F (0B3C (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0A9 \\1',
        ],
    210 =>
        [
            'match'   => '0B32 (0B3C (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0AA \\1',
        ],
    211 =>
        [
            'match'   => '0B33 (0B3C (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0AB \\1',
        ],
    212 =>
        [
            'match'   => '0B35 (0B3C (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0AC \\1',
        ],
    213 =>
        [
            'match'   => '0B36 (0B3C (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0AD \\1',
        ],
    214 =>
        [
            'match'   => '0B37 (0B3C (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0AE \\1',
        ],
    215 =>
        [
            'match'   => '0B38 (0B3C (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0AF \\1',
        ],
    216 =>
        [
            'match'   => '0B39 (0B3C (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0B0 \\1',
        ],
    217 =>
        [
            'match'   => 'E003 (0B3C (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0B1 \\1',
        ],
    218 =>
        [
            'match'   => 'E004 (0B3C (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0B2 \\1',
        ],
    219 =>
        [
            'match'   => '0B15 (E069 (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E090 \\1',
        ],
    220 =>
        [
            'match'   => '0B16 (E069 (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E091 \\1',
        ],
    221 =>
        [
            'match'   => '0B17 (E069 (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E092 \\1',
        ],
    222 =>
        [
            'match'   => '0B18 (E069 (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E093 \\1',
        ],
    223 =>
        [
            'match'   => '0B19 (E069 (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E094 \\1',
        ],
    224 =>
        [
            'match'   => '0B1A (E069 (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E095 \\1',
        ],
    225 =>
        [
            'match'   => '0B1B (E069 (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E096 \\1',
        ],
    226 =>
        [
            'match'   => '0B1C (E069 (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E097 \\1',
        ],
    227 =>
        [
            'match'   => '0B1D (E069 (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E098 \\1',
        ],
    228 =>
        [
            'match'   => '0B1E (E069 (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E099 \\1',
        ],
    229 =>
        [
            'match'   => '0B1F (E069 (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E09A \\1',
        ],
    230 =>
        [
            'match'   => '0B20 (E069 (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E09B \\1',
        ],
    231 =>
        [
            'match'   => '0B21 (E069 (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E09C \\1',
        ],
    232 =>
        [
            'match'   => '0B22 (E069 (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E09D \\1',
        ],
    233 =>
        [
            'match'   => '0B23 (E069 (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E09E \\1',
        ],
    234 =>
        [
            'match'   => '0B24 (E069 (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E09F \\1',
        ],
    235 =>
        [
            'match'   => '0B25 (E069 (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0A0 \\1',
        ],
    236 =>
        [
            'match'   => '0B26 (E069 (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0A1 \\1',
        ],
    237 =>
        [
            'match'   => '0B27 (E069 (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0A2 \\1',
        ],
    238 =>
        [
            'match'   => '0B28 (E069 (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0A3 \\1',
        ],
    239 =>
        [
            'match'   => '0B2A (E069 (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0A4 \\1',
        ],
    240 =>
        [
            'match'   => '0B2B (E069 (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0A5 \\1',
        ],
    241 =>
        [
            'match'   => '0B2C (E069 (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0A6 \\1',
        ],
    242 =>
        [
            'match'   => '0B2D (E069 (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0A7 \\1',
        ],
    243 =>
        [
            'match'   => '0B2E (E069 (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0A8 \\1',
        ],
    244 =>
        [
            'match'   => '0B2F (E069 (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0A9 \\1',
        ],
    245 =>
        [
            'match'   => '0B32 (E069 (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0AA \\1',
        ],
    246 =>
        [
            'match'   => '0B33 (E069 (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0AB \\1',
        ],
    247 =>
        [
            'match'   => '0B35 (E069 (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0AC \\1',
        ],
    248 =>
        [
            'match'   => '0B36 (E069 (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0AD \\1',
        ],
    249 =>
        [
            'match'   => '0B37 (E069 (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0AE \\1',
        ],
    250 =>
        [
            'match'   => '0B38 (E069 (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0AF \\1',
        ],
    251 =>
        [
            'match'   => '0B39 (E069 (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0B0 \\1',
        ],
    252 =>
        [
            'match'   => 'E003 (E069 (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0B1 \\1',
        ],
    253 =>
        [
            'match'   => 'E004 (E069 (E07B|E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E11E|E11F|E120|E121|E122|E11B|E11C))',
            'replace' => 'E0B2 \\1',
        ],
    254 =>
        [
            'match'   => '0B25 0B3F',
            'replace' => 'E02D',
        ],
    255 =>
        [
            'match'   => '0B27 0B3F',
            'replace' => 'E02E',
        ],
    256 =>
        [
            'match'   => '0B16 0B3F',
            'replace' => 'E02F',
        ],
    257 =>
        [
            'match'   => '(0B3C) 0B4D',
            'replace' => '\\1 E063',
        ],
    258 =>
        [
            'match'   => '(0B3C) E075',
            'replace' => '\\1 E076',
        ],
    259 =>
        [
            'match'   => '2018',
            'replace' => '0B4D',
        ],
    260 =>
        [
            'match'   => '2019',
            'replace' => '0B4D',
        ],
    261 =>
        [
            'match'   => '((0B16|0B17|0B18|0B1F|0B23|0B25|0B27|0B2A|0B2B|0B2E|0B2F|0B37|0B38)) 0B01',
            'replace' => '\\1 E039',
        ],
    262 =>
        [
            'match'   => '((E003|E006|E007|E008|E019|E01A|E00F|E010|E015|E01B|E024|E026|E027|E029|E14A|E02B|E02C|E02D|E02E|E02F|E030|E031|E032|E034|E035|E036|E037)) 0B01',
            'replace' => '\\1 E039',
        ],
    263 =>
        [
            'match'   => '0B3F 0B01',
            'replace' => 'E041',
        ],
    264 =>
        [
            'match'   => 'E03F 0B01',
            'replace' => 'E041',
        ],
    265 =>
        [
            'match'   => 'E040 0B01',
            'replace' => 'E042',
        ],
    266 =>
        [
            'match'   => '0B40 0B01',
            'replace' => 'E044',
        ],
    267 =>
        [
            'match'   => '0B57 0B01',
            'replace' => 'E068',
        ],
    268 =>
        [
            'match'   => '0B56 0B01',
            'replace' => 'E064',
        ],
    269 =>
        [
            'match'   => 'E05D 0B01',
            'replace' => 'E064',
        ],
    270 =>
        [
            'match'   => 'E05E 0B01',
            'replace' => 'E065',
        ],
    271 =>
        [
            'match'   => 'E05F 0B01',
            'replace' => 'E066',
        ],
    272 =>
        [
            'match'   => 'E060 0B01',
            'replace' => 'E067',
        ],
    273 =>
        [
            'match'   => 'E06D 0B01',
            'replace' => 'E089',
        ],
    274 =>
        [
            'match'   => 'E06E 0B01',
            'replace' => 'E08A',
        ],
    275 =>
        [
            'match'   => 'E070 0B01',
            'replace' => 'E08C',
        ],
    276 =>
        [
            'match'   => 'E071 0B01',
            'replace' => 'E08D',
        ],
    277 =>
        [
            'match'   => 'E072 0B01',
            'replace' => 'E08E',
        ],
    278 =>
        [
            'match'   => 'E074 0B01',
            'replace' => 'E08F',
        ],
    279 =>
        [
            'match'   => '((E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FC|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E07B|E084)) 0B3C',
            'replace' => '\\1 E03C',
        ],
    280 =>
        [
            'match'   => '((E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FC|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E07B|E084)) 0B41',
            'replace' => '\\1 E048',
        ],
    281 =>
        [
            'match'   => '((E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FC|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E07B|E084)) 0B42',
            'replace' => '\\1 E04B',
        ],
    282 =>
        [
            'match'   => '((E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FC|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E07B|E084)) 0B43',
            'replace' => '\\1 E04F',
        ],
    283 =>
        [
            'match'   => '((E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FC|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E07B|E084)) E053',
            'replace' => '\\1 E054',
        ],
    284 =>
        [
            'match'   => '((E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FC|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E07B|E084)) E056',
            'replace' => '\\1 E057',
        ],
    285 =>
        [
            'match'   => '((E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FC|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E07B|E084)) E059',
            'replace' => '\\1 E05A',
        ],
    286 =>
        [
            'match'   => '((E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FC|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E07B|E084)) 0B4D',
            'replace' => '\\1 E062',
        ],
    287 =>
        [
            'match'   => '((E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FC|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E07B|E084)) E075',
            'replace' => '\\1 E076',
        ],
    288 =>
        [
            'match'   => '((E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FC|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E07B|E084)) E07B',
            'replace' => '\\1 E07C',
        ],
    289 =>
        [
            'match'   => '((E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FC|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E07B|E084)) E07D',
            'replace' => '\\1 E07F',
        ],
    290 =>
        [
            'match'   => '((E0F6|E0F7|E0F8|E0F9|E0FA|E0FB|E0FC|E0FD|E0FE|E0FF|E100|E101|E102|E103|E104|E105|E106|E107|E108|E109|E10A|E10B|E10C|E10D|E10E|E10F|E110|E111|E112|E113|E114|E115|E116|E117|E118|E119|E11A|E11B|E11C|E11D|E07B|E084)) E082',
            'replace' => '\\1 E083',
        ],
    291 =>
        [
            'match'   => '((E00E|E00F|E010|E011|E012|E013|E01B|E01C|E01E|E01F|E020|E023|E024|E025|E026|E027|E028|E029|E02A|E02B|E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037)) 0B3C',
            'replace' => '\\1 E03C',
        ],
    292 =>
        [
            'match'   => '((E00E|E00F|E010|E011|E012|E013|E01B|E01C|E01E|E01F|E020|E023|E024|E025|E026|E027|E028|E029|E02A|E02B|E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037)) 0B41',
            'replace' => '\\1 E048',
        ],
    293 =>
        [
            'match'   => '((E00E|E00F|E010|E011|E012|E013|E01B|E01C|E01E|E01F|E020|E023|E024|E025|E026|E027|E028|E029|E02A|E02B|E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037)) 0B42',
            'replace' => '\\1 E04B',
        ],
    294 =>
        [
            'match'   => '((E00E|E00F|E010|E011|E012|E013|E01B|E01C|E01E|E01F|E020|E023|E024|E025|E026|E027|E028|E029|E02A|E02B|E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037)) 0B43',
            'replace' => '\\1 E04F',
        ],
    295 =>
        [
            'match'   => '((E00E|E00F|E010|E011|E012|E013|E01B|E01C|E01E|E01F|E020|E023|E024|E025|E026|E027|E028|E029|E02A|E02B|E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037)) E053',
            'replace' => '\\1 E054',
        ],
    296 =>
        [
            'match'   => '((E00E|E00F|E010|E011|E012|E013|E01B|E01C|E01E|E01F|E020|E023|E024|E025|E026|E027|E028|E029|E02A|E02B|E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037)) E056',
            'replace' => '\\1 E057',
        ],
    297 =>
        [
            'match'   => '((E00E|E00F|E010|E011|E012|E013|E01B|E01C|E01E|E01F|E020|E023|E024|E025|E026|E027|E028|E029|E02A|E02B|E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037)) E059',
            'replace' => '\\1 E05A',
        ],
    298 =>
        [
            'match'   => '((E00E|E00F|E010|E011|E012|E013|E01B|E01C|E01E|E01F|E020|E023|E024|E025|E026|E027|E028|E029|E02A|E02B|E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037)) 0B4D',
            'replace' => '\\1 E062',
        ],
    299 =>
        [
            'match'   => '((E00E|E00F|E010|E011|E012|E013|E01B|E01C|E01E|E01F|E020|E023|E024|E025|E026|E027|E028|E029|E02A|E02B|E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037)) E075',
            'replace' => '\\1 E076',
        ],
    300 =>
        [
            'match'   => '((E00E|E00F|E010|E011|E012|E013|E01B|E01C|E01E|E01F|E020|E023|E024|E025|E026|E027|E028|E029|E02A|E02B|E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037)) E07B',
            'replace' => '\\1 E07C',
        ],
    301 =>
        [
            'match'   => '((E00E|E00F|E010|E011|E012|E013|E01B|E01C|E01E|E01F|E020|E023|E024|E025|E026|E027|E028|E029|E02A|E02B|E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037)) E07D',
            'replace' => '\\1 E07F',
        ],
    302 =>
        [
            'match'   => '((E00E|E00F|E010|E011|E012|E013|E01B|E01C|E01E|E01F|E020|E023|E024|E025|E026|E027|E028|E029|E02A|E02B|E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037)) E082',
            'replace' => '\\1 E083',
        ],
    303 =>
        [
            'match'   => '((E00E|E00F|E010|E011|E012|E013|E01B|E01C|E01E|E01F|E020|E023|E024|E025|E026|E027|E028|E029|E02A|E02B|E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037) E069) 0B3C',
            'replace' => '\\1 E03C',
        ],
    304 =>
        [
            'match'   => '((E00E|E00F|E010|E011|E012|E013|E01B|E01C|E01E|E01F|E020|E023|E024|E025|E026|E027|E028|E029|E02A|E02B|E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037) E069) 0B41',
            'replace' => '\\1 E048',
        ],
    305 =>
        [
            'match'   => '((E00E|E00F|E010|E011|E012|E013|E01B|E01C|E01E|E01F|E020|E023|E024|E025|E026|E027|E028|E029|E02A|E02B|E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037) E069) 0B42',
            'replace' => '\\1 E04B',
        ],
    306 =>
        [
            'match'   => '((E00E|E00F|E010|E011|E012|E013|E01B|E01C|E01E|E01F|E020|E023|E024|E025|E026|E027|E028|E029|E02A|E02B|E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037) E069) 0B43',
            'replace' => '\\1 E04F',
        ],
    307 =>
        [
            'match'   => '((E00E|E00F|E010|E011|E012|E013|E01B|E01C|E01E|E01F|E020|E023|E024|E025|E026|E027|E028|E029|E02A|E02B|E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037) E069) E053',
            'replace' => '\\1 E054',
        ],
    308 =>
        [
            'match'   => '((E00E|E00F|E010|E011|E012|E013|E01B|E01C|E01E|E01F|E020|E023|E024|E025|E026|E027|E028|E029|E02A|E02B|E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037) E069) E056',
            'replace' => '\\1 E057',
        ],
    309 =>
        [
            'match'   => '((E00E|E00F|E010|E011|E012|E013|E01B|E01C|E01E|E01F|E020|E023|E024|E025|E026|E027|E028|E029|E02A|E02B|E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037) E069) E059',
            'replace' => '\\1 E05A',
        ],
    310 =>
        [
            'match'   => '((E00E|E00F|E010|E011|E012|E013|E01B|E01C|E01E|E01F|E020|E023|E024|E025|E026|E027|E028|E029|E02A|E02B|E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037) E069) 0B4D',
            'replace' => '\\1 E062',
        ],
    311 =>
        [
            'match'   => '((E00E|E00F|E010|E011|E012|E013|E01B|E01C|E01E|E01F|E020|E023|E024|E025|E026|E027|E028|E029|E02A|E02B|E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037) E069) E075',
            'replace' => '\\1 E076',
        ],
    312 =>
        [
            'match'   => '((E00E|E00F|E010|E011|E012|E013|E01B|E01C|E01E|E01F|E020|E023|E024|E025|E026|E027|E028|E029|E02A|E02B|E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037) E069) E07B',
            'replace' => '\\1 E07C',
        ],
    313 =>
        [
            'match'   => '((E00E|E00F|E010|E011|E012|E013|E01B|E01C|E01E|E01F|E020|E023|E024|E025|E026|E027|E028|E029|E02A|E02B|E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037) E069) E07D',
            'replace' => '\\1 E07F',
        ],
    314 =>
        [
            'match'   => '((E00E|E00F|E010|E011|E012|E013|E01B|E01C|E01E|E01F|E020|E023|E024|E025|E026|E027|E028|E029|E02A|E02B|E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037) E069) E082',
            'replace' => '\\1 E083',
        ],
    315 =>
        [
            'match'   => '((E00E|E00F|E010|E011|E012|E013|E01B|E01C|E01E|E01F|E020|E023|E024|E025|E026|E027|E028|E029|E02A|E02B|E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037) E069 0B01) 0B3C',
            'replace' => '\\1 E03C',
        ],
    316 =>
        [
            'match'   => '((E00E|E00F|E010|E011|E012|E013|E01B|E01C|E01E|E01F|E020|E023|E024|E025|E026|E027|E028|E029|E02A|E02B|E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037) E069 0B01) 0B41',
            'replace' => '\\1 E048',
        ],
    317 =>
        [
            'match'   => '((E00E|E00F|E010|E011|E012|E013|E01B|E01C|E01E|E01F|E020|E023|E024|E025|E026|E027|E028|E029|E02A|E02B|E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037) E069 0B01) 0B42',
            'replace' => '\\1 E04B',
        ],
    318 =>
        [
            'match'   => '((E00E|E00F|E010|E011|E012|E013|E01B|E01C|E01E|E01F|E020|E023|E024|E025|E026|E027|E028|E029|E02A|E02B|E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037) E069 0B01) 0B43',
            'replace' => '\\1 E04F',
        ],
    319 =>
        [
            'match'   => '((E00E|E00F|E010|E011|E012|E013|E01B|E01C|E01E|E01F|E020|E023|E024|E025|E026|E027|E028|E029|E02A|E02B|E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037) E069 0B01) E053',
            'replace' => '\\1 E054',
        ],
    320 =>
        [
            'match'   => '((E00E|E00F|E010|E011|E012|E013|E01B|E01C|E01E|E01F|E020|E023|E024|E025|E026|E027|E028|E029|E02A|E02B|E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037) E069 0B01) E056',
            'replace' => '\\1 E057',
        ],
    321 =>
        [
            'match'   => '((E00E|E00F|E010|E011|E012|E013|E01B|E01C|E01E|E01F|E020|E023|E024|E025|E026|E027|E028|E029|E02A|E02B|E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037) E069 0B01) E059',
            'replace' => '\\1 E05A',
        ],
    322 =>
        [
            'match'   => '((E00E|E00F|E010|E011|E012|E013|E01B|E01C|E01E|E01F|E020|E023|E024|E025|E026|E027|E028|E029|E02A|E02B|E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037) E069 0B01) 0B4D',
            'replace' => '\\1 E062',
        ],
    323 =>
        [
            'match'   => '((E00E|E00F|E010|E011|E012|E013|E01B|E01C|E01E|E01F|E020|E023|E024|E025|E026|E027|E028|E029|E02A|E02B|E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037) E069 0B01) E075',
            'replace' => '\\1 E076',
        ],
    324 =>
        [
            'match'   => '((E00E|E00F|E010|E011|E012|E013|E01B|E01C|E01E|E01F|E020|E023|E024|E025|E026|E027|E028|E029|E02A|E02B|E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037) E069 0B01) E07B',
            'replace' => '\\1 E07C',
        ],
    325 =>
        [
            'match'   => '((E00E|E00F|E010|E011|E012|E013|E01B|E01C|E01E|E01F|E020|E023|E024|E025|E026|E027|E028|E029|E02A|E02B|E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037) E069 0B01) E07D',
            'replace' => '\\1 E07F',
        ],
    326 =>
        [
            'match'   => '((E00E|E00F|E010|E011|E012|E013|E01B|E01C|E01E|E01F|E020|E023|E024|E025|E026|E027|E028|E029|E02A|E02B|E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037) E069 0B01) E082',
            'replace' => '\\1 E083',
        ],
    327 =>
        [
            'match'   => '(E02B) 0B3C',
            'replace' => '\\1 E03C',
        ],
    328 =>
        [
            'match'   => '(E02B) 0B41',
            'replace' => '\\1 E048',
        ],
    329 =>
        [
            'match'   => '(E02B) 0B42',
            'replace' => '\\1 E04B',
        ],
    330 =>
        [
            'match'   => '(E02B) 0B43',
            'replace' => '\\1 E04F',
        ],
    331 =>
        [
            'match'   => '(E02B) E053',
            'replace' => '\\1 E054',
        ],
    332 =>
        [
            'match'   => '(E02B) E056',
            'replace' => '\\1 E057',
        ],
    333 =>
        [
            'match'   => '(E02B) E059',
            'replace' => '\\1 E05A',
        ],
    334 =>
        [
            'match'   => '(E02B) 0B4D',
            'replace' => '\\1 E062',
        ],
    335 =>
        [
            'match'   => '(E02B) E075',
            'replace' => '\\1 E076',
        ],
    336 =>
        [
            'match'   => '(E02B) E07B',
            'replace' => '\\1 E07C',
        ],
    337 =>
        [
            'match'   => '(E02B) E07D',
            'replace' => '\\1 E07F',
        ],
    338 =>
        [
            'match'   => '(E02B) E082',
            'replace' => '\\1 E083',
        ],
    339 =>
        [
            'match'   => '(E02C) 0B3C',
            'replace' => '\\1 E03C',
        ],
    340 =>
        [
            'match'   => '(E02C) 0B41',
            'replace' => '\\1 E048',
        ],
    341 =>
        [
            'match'   => '(E02C) 0B42',
            'replace' => '\\1 E04B',
        ],
    342 =>
        [
            'match'   => '(E02C) 0B43',
            'replace' => '\\1 E04F',
        ],
    343 =>
        [
            'match'   => '(E02C) E053',
            'replace' => '\\1 E054',
        ],
    344 =>
        [
            'match'   => '(E02C) E056',
            'replace' => '\\1 E057',
        ],
    345 =>
        [
            'match'   => '(E02C) E059',
            'replace' => '\\1 E05A',
        ],
    346 =>
        [
            'match'   => '(E02C) 0B4D',
            'replace' => '\\1 E062',
        ],
    347 =>
        [
            'match'   => '(E02C) E075',
            'replace' => '\\1 E076',
        ],
    348 =>
        [
            'match'   => '(E02C) E07B',
            'replace' => '\\1 E07C',
        ],
    349 =>
        [
            'match'   => '(E02C) E07D',
            'replace' => '\\1 E07F',
        ],
    350 =>
        [
            'match'   => '(E02C) E082',
            'replace' => '\\1 E083',
        ],
    351 =>
        [
            'match'   => '(E06B) 0B3C',
            'replace' => '\\1 E03C',
        ],
    352 =>
        [
            'match'   => '(E06B) 0B41',
            'replace' => '\\1 E048',
        ],
    353 =>
        [
            'match'   => '(E06B) 0B42',
            'replace' => '\\1 E04B',
        ],
    354 =>
        [
            'match'   => '(E06B) 0B43',
            'replace' => '\\1 E04F',
        ],
    355 =>
        [
            'match'   => '(E06B) E053',
            'replace' => '\\1 E054',
        ],
    356 =>
        [
            'match'   => '(E06B) E056',
            'replace' => '\\1 E057',
        ],
    357 =>
        [
            'match'   => '(E06B) E059',
            'replace' => '\\1 E05A',
        ],
    358 =>
        [
            'match'   => '(E06B) 0B4D',
            'replace' => '\\1 E062',
        ],
    359 =>
        [
            'match'   => '(E06B) E075',
            'replace' => '\\1 E076',
        ],
    360 =>
        [
            'match'   => '(E06B) E07B',
            'replace' => '\\1 E07C',
        ],
    361 =>
        [
            'match'   => '(E06B) E07D',
            'replace' => '\\1 E07F',
        ],
    362 =>
        [
            'match'   => '(E06B) E082',
            'replace' => '\\1 E083',
        ],
    363 =>
        [
            'match'   => '(0B3C) 0B3C',
            'replace' => '\\1 E03C',
        ],
    364 =>
        [
            'match'   => '(0B3C) 0B41',
            'replace' => '\\1 E048',
        ],
    365 =>
        [
            'match'   => '(0B3C) 0B42',
            'replace' => '\\1 E04B',
        ],
    366 =>
        [
            'match'   => '(0B3C) 0B43',
            'replace' => '\\1 E04F',
        ],
    367 =>
        [
            'match'   => '(0B3C) E053',
            'replace' => '\\1 E054',
        ],
    368 =>
        [
            'match'   => '(0B3C) E056',
            'replace' => '\\1 E057',
        ],
    369 =>
        [
            'match'   => '(0B3C) E059',
            'replace' => '\\1 E05A',
        ],
    370 =>
        [
            'match'   => '(0B3C) 0B4D',
            'replace' => '\\1 E062',
        ],
    371 =>
        [
            'match'   => '(0B3C) E075',
            'replace' => '\\1 E076',
        ],
    372 =>
        [
            'match'   => '(0B3C) E07B',
            'replace' => '\\1 E07C',
        ],
    373 =>
        [
            'match'   => '(0B3C) E07D',
            'replace' => '\\1 E07F',
        ],
    374 =>
        [
            'match'   => '(0B3C) E082',
            'replace' => '\\1 E083',
        ],
    375 =>
        [
            'match'   => '(E075) 0B3C',
            'replace' => '\\1 E03C',
        ],
    376 =>
        [
            'match'   => '(E075) 0B41',
            'replace' => '\\1 E048',
        ],
    377 =>
        [
            'match'   => '(E075) 0B42',
            'replace' => '\\1 E04B',
        ],
    378 =>
        [
            'match'   => '(E075) 0B43',
            'replace' => '\\1 E04F',
        ],
    379 =>
        [
            'match'   => '(E075) E053',
            'replace' => '\\1 E054',
        ],
    380 =>
        [
            'match'   => '(E075) E056',
            'replace' => '\\1 E057',
        ],
    381 =>
        [
            'match'   => '(E075) E059',
            'replace' => '\\1 E05A',
        ],
    382 =>
        [
            'match'   => '(E075) 0B4D',
            'replace' => '\\1 E062',
        ],
    383 =>
        [
            'match'   => '(E075) E075',
            'replace' => '\\1 E076',
        ],
    384 =>
        [
            'match'   => '(E075) E07B',
            'replace' => '\\1 E07C',
        ],
    385 =>
        [
            'match'   => '(E075) E07D',
            'replace' => '\\1 E07F',
        ],
    386 =>
        [
            'match'   => '(E075) E082',
            'replace' => '\\1 E083',
        ],
    387 =>
        [
            'match'   => '(E075) 0B41',
            'replace' => '\\1 E048',
        ],
    388 =>
        [
            'match'   => '(E075) 0B42',
            'replace' => '\\1 E04B',
        ],
    389 =>
        [
            'match'   => '(E075) 0B43',
            'replace' => '\\1 E04F',
        ],
    390 =>
        [
            'match'   => '(E075) E053',
            'replace' => '\\1 E054',
        ],
    391 =>
        [
            'match'   => '(E075) E056',
            'replace' => '\\1 E057',
        ],
    392 =>
        [
            'match'   => '(E075) E059',
            'replace' => '\\1 E05A',
        ],
    393 =>
        [
            'match'   => 'E14D 0B01',
            'replace' => 'E08B',
        ],
    394 =>
        [
            'match'   => 'E14D E038',
            'replace' => 'E08B',
        ],
    395 =>
        [
            'match'   => 'E14D E039',
            'replace' => 'E08B',
        ],
    396 =>
        [
            'match'   => 'E14D E149',
            'replace' => 'E08B',
        ],
    397 =>
        [
            'match'   => '((E00E|E00F|E010|E011|E012|E013|E01B|E01C|E01E|E01F|E020|E023|E024|E025|E026|E027|E028|E029|E02A|E02B|E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037)) E0FD',
            'replace' => '\\1 E11E',
        ],
    398 =>
        [
            'match'   => '((E00E|E00F|E010|E011|E012|E013|E01B|E01C|E01E|E01F|E020|E023|E024|E025|E026|E027|E028|E029|E02A|E02B|E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037)) E109',
            'replace' => '\\1 E121',
        ],
    399 =>
        [
            'match'   => '((E00E|E00F|E010|E011|E012|E013|E01B|E01C|E01E|E01F|E020|E023|E024|E025|E026|E027|E028|E029|E02A|E02B|E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037)) E110',
            'replace' => '\\1 E11F',
        ],
    400 =>
        [
            'match'   => '((E00E|E00F|E010|E011|E012|E013|E01B|E01C|E01E|E01F|E020|E023|E024|E025|E026|E027|E028|E029|E02A|E02B|E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037)) E111',
            'replace' => '\\1 E120',
        ],
    401 =>
        [
            'match'   => '((E00E|E00F|E010|E011|E012|E013|E01B|E01C|E01E|E01F|E020|E023|E024|E025|E026|E027|E028|E029|E02A|E02B|E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037)) E11C',
            'replace' => '\\1 E122',
        ],
    402 =>
        [
            'match'   => '(E076) 0B41',
            'replace' => '\\1 E048',
        ],
    403 =>
        [
            'match'   => '(E076) 0B42',
            'replace' => '\\1 E04B',
        ],
    404 =>
        [
            'match'   => '(E076) 0B43',
            'replace' => '\\1 E04F',
        ],
    405 =>
        [
            'match'   => '(E076) E053',
            'replace' => '\\1 E054',
        ],
    406 =>
        [
            'match'   => '(E076) E056',
            'replace' => '\\1 E057',
        ],
    407 =>
        [
            'match'   => '(E076) E059',
            'replace' => '\\1 E05A',
        ],
    408 =>
        [
            'match'   => '(E076) 0B4D',
            'replace' => '\\1 E062',
        ],
    409 =>
        [
            'match'   => '(E090) E10E',
            'replace' => '\\1 E11C',
        ],
    410 =>
        [
            'match'   => '(E0B1) E10E',
            'replace' => '\\1 E11C',
        ],
    411 =>
        [
            'match'   => '(E09F) E10E',
            'replace' => '\\1 E11C',
        ],
    412 =>
        [
            'match'   => '(E092) E10E',
            'replace' => '\\1 E11C',
        ],
    413 =>
        [
            'match'   => '(E0A8) E10E',
            'replace' => '\\1 E11C',
        ],
    414 =>
        [
            'match'   => '(E0A3) E106',
            'replace' => '\\1 E0FC',
        ],
    415 =>
        [
            'match'   => '(E0AF) E106',
            'replace' => '\\1 E0FC',
        ],
    416 =>
        [
            'match'   => '((0B16|0B17|0B18|0B1F|0B23|0B25|0B27|0B2A|0B2B|0B2E|0B2F|0B37|0B38)) 0B01',
            'replace' => '\\1 E039',
        ],
    417 =>
        [
            'match'   => '(0B10) 0B01',
            'replace' => '\\1 E149',
        ],
    418 =>
        [
            'match'   => '(0B14) 0B01',
            'replace' => '\\1 E149',
        ],
    419 =>
        [
            'match'   => '(E069) 0B01',
            'replace' => '\\1 E149',
        ],
    420 =>
        [
            'match'   => '(E06A) 0B01',
            'replace' => '\\1 E149',
        ],
    421 =>
        [
            'match'   => '(E06B) 0B01',
            'replace' => '\\1 E149',
        ],
    422 =>
        [
            'match'   => '(E06C) 0B01',
            'replace' => '\\1 E149',
        ],
    423 =>
        [
            'match'   => '0B21 0B4D (E035)',
            'replace' => 'E12F \\1',
        ],
    424 =>
        [
            'match'   => ' (E035)',
            'replace' => ' \\1',
        ],
    425 =>
        [
            'match'   => ' (E035)',
            'replace' => ' \\1',
        ],
    426 =>
        [
            'match'   => ' (E035)',
            'replace' => ' \\1',
        ],
    427 =>
        [
            'match'   => ' (E035)',
            'replace' => ' \\1',
        ],
    428 =>
        [
            'match'   => ' (E035)',
            'replace' => ' \\1',
        ],
];
?>