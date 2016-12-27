<?php
$volt = [
    0   =>
        [
            'match'   => '0C4D 0C30 ((0C15|0C16|0C17|0C18|0C19|0C1A|0C1B|0C1C|0C1D|0C1E|0C1F|0C20|0C21|0C22|0C23|0C24|0C25|0C26|0C27|0C28|0C2A|0C2B|0C2C|0C2D|0C2E|0C2F|0C30|0C31|0C32|0C33|0C35|0C36|0C37|0C38|0C39))',
            'replace' => 'E046 \\1',
        ],
    1   =>
        [
            'match'   => '0C4D 200D',
            'replace' => '00C9',
        ],
    2   =>
        [
            'match'   => '0C4D 200C',
            'replace' => '00D0',
        ],
    3   =>
        [
            'match'   => '200D 0C4D',
            'replace' => '00D1',
        ],
    4   =>
        [
            'match'   => '((0C15|0C16|0C17|0C18|0C19|0C1A|0C1B|0C1C|0C1D|0C1E|0C1F|0C20|0C21|0C22|0C23|0C24|0C25|0C26|0C27|0C28|0C2A|0C2B|0C2C|0C2D|0C2E|0C2F|0C30|0C31|0C32|0C33|0C35|0C36|0C37|0C38|0C39)) 0C4D',
            'replace' => '\\1 00D1',
        ],
    5   =>
        [
            'match'   => '((0C3E|0C3F|0C40|0C46|0C47|0C56|0C4A|0C4B|0C4C)) 0C4D',
            'replace' => '\\1 00D1',
        ],
    6   =>
        [
            'match'   => '((0C41|0C42|0C43|0C44)) 0C4D',
            'replace' => '\\1 00D1',
        ],
    7   =>
        [
            'match'   => '(0020) 0C4D',
            'replace' => '\\1 00D1',
        ],
    8   =>
        [
            'match'   => '(25CC) 0C4D',
            'replace' => '\\1 00D1',
        ],
    9   =>
        [
            'match'   => '0C15 00D1 0C37',
            'replace' => 'E078',
        ],
    10  =>
        [
            'match'   => '0C36 00D1 0C1C',
            'replace' => 'E079',
        ],
    11  =>
        [
            'match'   => '00D1 0C15',
            'replace' => 'E02C',
        ],
    12  =>
        [
            'match'   => '00D1 0C16',
            'replace' => 'E02D',
        ],
    13  =>
        [
            'match'   => '00D1 0C17',
            'replace' => 'E02E',
        ],
    14  =>
        [
            'match'   => '00D1 0C18',
            'replace' => 'E02F',
        ],
    15  =>
        [
            'match'   => '00D1 0C19',
            'replace' => 'E030',
        ],
    16  =>
        [
            'match'   => '00D1 0C1A',
            'replace' => 'E031',
        ],
    17  =>
        [
            'match'   => '00D1 0C1B',
            'replace' => 'E032',
        ],
    18  =>
        [
            'match'   => '00D1 0C1C',
            'replace' => 'E033',
        ],
    19  =>
        [
            'match'   => '00D1 0C1D',
            'replace' => 'E034',
        ],
    20  =>
        [
            'match'   => '00D1 0C1E',
            'replace' => 'E035',
        ],
    21  =>
        [
            'match'   => '00D1 0C1F',
            'replace' => 'E036',
        ],
    22  =>
        [
            'match'   => '00D1 0C20',
            'replace' => 'E037',
        ],
    23  =>
        [
            'match'   => '00D1 0C21',
            'replace' => 'E038',
        ],
    24  =>
        [
            'match'   => '00D1 0C22',
            'replace' => 'E039',
        ],
    25  =>
        [
            'match'   => '00D1 0C23',
            'replace' => 'E03A',
        ],
    26  =>
        [
            'match'   => '00D1 0C24',
            'replace' => 'E03B',
        ],
    27  =>
        [
            'match'   => '00D1 0C25',
            'replace' => 'E03C',
        ],
    28  =>
        [
            'match'   => '00D1 0C26',
            'replace' => 'E03D',
        ],
    29  =>
        [
            'match'   => '00D1 0C27',
            'replace' => 'E03E',
        ],
    30  =>
        [
            'match'   => '00D1 0C28',
            'replace' => 'E03F',
        ],
    31  =>
        [
            'match'   => '00D1 0C2A',
            'replace' => 'E040',
        ],
    32  =>
        [
            'match'   => '00D1 0C2B',
            'replace' => 'E041',
        ],
    33  =>
        [
            'match'   => '00D1 0C2C',
            'replace' => 'E042',
        ],
    34  =>
        [
            'match'   => '00D1 0C2D',
            'replace' => 'E043',
        ],
    35  =>
        [
            'match'   => '00D1 0C2E',
            'replace' => 'E044',
        ],
    36  =>
        [
            'match'   => '00D1 0C2F',
            'replace' => 'E045',
        ],
    37  =>
        [
            'match'   => '00D1 0C30',
            'replace' => 'E046',
        ],
    38  =>
        [
            'match'   => '00D1 0C31',
            'replace' => 'E047',
        ],
    39  =>
        [
            'match'   => '00D1 0C32',
            'replace' => 'E048',
        ],
    40  =>
        [
            'match'   => '00D1 0C33',
            'replace' => 'E049',
        ],
    41  =>
        [
            'match'   => '00D1 0C35',
            'replace' => 'E04A',
        ],
    42  =>
        [
            'match'   => '00D1 0C36',
            'replace' => 'E04B',
        ],
    43  =>
        [
            'match'   => '00D1 0C37',
            'replace' => 'E04C',
        ],
    44  =>
        [
            'match'   => '00D1 0C38',
            'replace' => 'E04D',
        ],
    45  =>
        [
            'match'   => '00D1 0C39',
            'replace' => 'E04E',
        ],
    46  =>
        [
            'match'   => '((E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E)) E02C',
            'replace' => '\\1 E04F',
        ],
    47  =>
        [
            'match'   => '((E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E)) E02D',
            'replace' => '\\1 E050',
        ],
    48  =>
        [
            'match'   => '((E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E)) E02E',
            'replace' => '\\1 E051',
        ],
    49  =>
        [
            'match'   => '((E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E)) E02F',
            'replace' => '\\1 E052',
        ],
    50  =>
        [
            'match'   => '((E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E)) E030',
            'replace' => '\\1 E053',
        ],
    51  =>
        [
            'match'   => '((E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E)) E031',
            'replace' => '\\1 E054',
        ],
    52  =>
        [
            'match'   => '((E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E)) E032',
            'replace' => '\\1 E055',
        ],
    53  =>
        [
            'match'   => '((E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E)) E033',
            'replace' => '\\1 E056',
        ],
    54  =>
        [
            'match'   => '((E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E)) E034',
            'replace' => '\\1 E057',
        ],
    55  =>
        [
            'match'   => '((E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E)) E035',
            'replace' => '\\1 E058',
        ],
    56  =>
        [
            'match'   => '((E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E)) E036',
            'replace' => '\\1 E059',
        ],
    57  =>
        [
            'match'   => '((E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E)) E037',
            'replace' => '\\1 E05A',
        ],
    58  =>
        [
            'match'   => '((E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E)) E038',
            'replace' => '\\1 E05B',
        ],
    59  =>
        [
            'match'   => '((E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E)) E039',
            'replace' => '\\1 E05C',
        ],
    60  =>
        [
            'match'   => '((E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E)) E03A',
            'replace' => '\\1 E05D',
        ],
    61  =>
        [
            'match'   => '((E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E)) E03B',
            'replace' => '\\1 E05E',
        ],
    62  =>
        [
            'match'   => '((E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E)) E03C',
            'replace' => '\\1 E05F',
        ],
    63  =>
        [
            'match'   => '((E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E)) E03D',
            'replace' => '\\1 E060',
        ],
    64  =>
        [
            'match'   => '((E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E)) E03E',
            'replace' => '\\1 E061',
        ],
    65  =>
        [
            'match'   => '((E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E)) E03F',
            'replace' => '\\1 E062',
        ],
    66  =>
        [
            'match'   => '((E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E)) E040',
            'replace' => '\\1 E063',
        ],
    67  =>
        [
            'match'   => '((E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E)) E041',
            'replace' => '\\1 E064',
        ],
    68  =>
        [
            'match'   => '((E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E)) E042',
            'replace' => '\\1 E065',
        ],
    69  =>
        [
            'match'   => '((E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E)) E043',
            'replace' => '\\1 E066',
        ],
    70  =>
        [
            'match'   => '((E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E)) E044',
            'replace' => '\\1 E067',
        ],
    71  =>
        [
            'match'   => '((E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E)) E045',
            'replace' => '\\1 E068',
        ],
    72  =>
        [
            'match'   => '((E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E)) E046',
            'replace' => '\\1 E069',
        ],
    73  =>
        [
            'match'   => '((E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E)) E047',
            'replace' => '\\1 E06A',
        ],
    74  =>
        [
            'match'   => '((E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E)) E048',
            'replace' => '\\1 E06B',
        ],
    75  =>
        [
            'match'   => '((E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E)) E049',
            'replace' => '\\1 E06C',
        ],
    76  =>
        [
            'match'   => '((E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E)) E04A',
            'replace' => '\\1 E06D',
        ],
    77  =>
        [
            'match'   => '((E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E)) E04B',
            'replace' => '\\1 E06E',
        ],
    78  =>
        [
            'match'   => '((E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E)) E04C',
            'replace' => '\\1 E06F',
        ],
    79  =>
        [
            'match'   => '((E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E)) E04D',
            'replace' => '\\1 E070',
        ],
    80  =>
        [
            'match'   => '((E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E)) E04E',
            'replace' => '\\1 E071',
        ],
    81  =>
        [
            'match'   => '((E04F|E050|E051|E052|E053|E054|E055|E056|E057|E058|E059|E05A|E05B|E05C|E05D|E05E|E05F|E060|E061|E062|E063|E064|E065|E066|E067|E068|E069|E06A|E06B|E06C|E06D|E06E|E06F|E070|E071)) E02C',
            'replace' => '\\1 E072',
        ],
    82  =>
        [
            'match'   => '((E04F|E050|E051|E052|E053|E054|E055|E056|E057|E058|E059|E05A|E05B|E05C|E05D|E05E|E05F|E060|E061|E062|E063|E064|E065|E066|E067|E068|E069|E06A|E06B|E06C|E06D|E06E|E06F|E070|E071)) E03F',
            'replace' => '\\1 E073',
        ],
    83  =>
        [
            'match'   => '((E04F|E050|E051|E052|E053|E054|E055|E056|E057|E058|E059|E05A|E05B|E05C|E05D|E05E|E05F|E060|E061|E062|E063|E064|E065|E066|E067|E068|E069|E06A|E06B|E06C|E06D|E06E|E06F|E070|E071)) E044',
            'replace' => '\\1 E074',
        ],
    84  =>
        [
            'match'   => '((E04F|E050|E051|E052|E053|E054|E055|E056|E057|E058|E059|E05A|E05B|E05C|E05D|E05E|E05F|E060|E061|E062|E063|E064|E065|E066|E067|E068|E069|E06A|E06B|E06C|E06D|E06E|E06F|E070|E071)) E045',
            'replace' => '\\1 E075',
        ],
    85  =>
        [
            'match'   => '((E04F|E050|E051|E052|E053|E054|E055|E056|E057|E058|E059|E05A|E05B|E05C|E05D|E05E|E05F|E060|E061|E062|E063|E064|E065|E066|E067|E068|E069|E06A|E06B|E06C|E06D|E06E|E06F|E070|E071)) E04A',
            'replace' => '\\1 E076',
        ],
    86  =>
        [
            'match'   => '((E04F|E050|E051|E052|E053|E054|E055|E056|E057|E058|E059|E05A|E05B|E05C|E05D|E05E|E05F|E060|E061|E062|E063|E064|E065|E066|E067|E068|E069|E06A|E06B|E06C|E06D|E06E|E06F|E070|E071)) E046',
            'replace' => '\\1 E077',
        ],
    87  =>
        [
            'match'   => '00C9',
            'replace' => '0C4D',
        ],
    88  =>
        [
            'match'   => '00D0',
            'replace' => '0C4D',
        ],
    89  =>
        [
            'match'   => '00D1',
            'replace' => '0C4D',
        ],
    90  =>
        [
            'match'   => '0C2A ((0C3E|0C4A|0C4B|0C4C))',
            'replace' => 'E028 \\1',
        ],
    91  =>
        [
            'match'   => '0C2B ((0C3E|0C4A|0C4B|0C4C))',
            'replace' => 'E029 \\1',
        ],
    92  =>
        [
            'match'   => '0C37 ((0C3E|0C4A|0C4B|0C4C))',
            'replace' => 'E02A \\1',
        ],
    93  =>
        [
            'match'   => '0C38 ((0C3E|0C4A|0C4B|0C4C))',
            'replace' => 'E02B \\1',
        ],
    94  =>
        [
            'match'   => 'E07B ((0C3E|0C4A|0C4B|0C4C))',
            'replace' => 'E0B2 \\1',
        ],
    95  =>
        [
            'match'   => '((E028|E029|E02A|E02B)) 0C4A',
            'replace' => '\\1 E009',
        ],
    96  =>
        [
            'match'   => '((E028|E029|E02A|E02B)) 0C4B',
            'replace' => '\\1 E00A',
        ],
    97  =>
        [
            'match'   => '((E028|E029|E02A|E02B)) 0C4C',
            'replace' => '\\1 E00B',
        ],
    98  =>
        [
            'match'   => '0C15 0C41',
            'replace' => 'E07F',
        ],
    99  =>
        [
            'match'   => '0C15 0C42',
            'replace' => 'E080',
        ],
    100 =>
        [
            'match'   => '0C16 0C3F',
            'replace' => 'E081',
        ],
    101 =>
        [
            'match'   => '0C16 0C40',
            'replace' => 'E082',
        ],
    102 =>
        [
            'match'   => '0C18 0C4A',
            'replace' => 'E083',
        ],
    103 =>
        [
            'match'   => '0C18 0C4B',
            'replace' => 'E084',
        ],
    104 =>
        [
            'match'   => '0C19 0C41',
            'replace' => 'E085',
        ],
    105 =>
        [
            'match'   => '0C19 0C42',
            'replace' => 'E086',
        ],
    106 =>
        [
            'match'   => '0C1A 0C3F',
            'replace' => 'E087',
        ],
    107 =>
        [
            'match'   => '0C1A 0C40',
            'replace' => 'E088',
        ],
    108 =>
        [
            'match'   => '0C1B 0C3F',
            'replace' => 'E089',
        ],
    109 =>
        [
            'match'   => '0C1B 0C40',
            'replace' => 'E08A',
        ],
    110 =>
        [
            'match'   => '0C1C 0C3F',
            'replace' => 'E08B',
        ],
    111 =>
        [
            'match'   => '0C1C 0C40',
            'replace' => 'E08C',
        ],
    112 =>
        [
            'match'   => '0C1C 0C41',
            'replace' => 'E08D',
        ],
    113 =>
        [
            'match'   => '0C1C 0C42',
            'replace' => 'E08E',
        ],
    114 =>
        [
            'match'   => '0C1D 0C4A',
            'replace' => 'E08F',
        ],
    115 =>
        [
            'match'   => '0C1D 0C4B',
            'replace' => 'E090',
        ],
    116 =>
        [
            'match'   => '0C24 0C3F',
            'replace' => 'E091',
        ],
    117 =>
        [
            'match'   => '0C24 0C40',
            'replace' => 'E092',
        ],
    118 =>
        [
            'match'   => '0C28 0C3F',
            'replace' => 'E093',
        ],
    119 =>
        [
            'match'   => '0C28 0C40',
            'replace' => 'E094',
        ],
    120 =>
        [
            'match'   => '0C2C 0C3F',
            'replace' => 'E095',
        ],
    121 =>
        [
            'match'   => '0C2C 0C40',
            'replace' => 'E096',
        ],
    122 =>
        [
            'match'   => '0C2D 0C3F',
            'replace' => 'E097',
        ],
    123 =>
        [
            'match'   => '0C2D 0C40',
            'replace' => 'E098',
        ],
    124 =>
        [
            'match'   => '0C2E 0C3F',
            'replace' => 'E099',
        ],
    125 =>
        [
            'match'   => '0C2E 0C40',
            'replace' => 'E09A',
        ],
    126 =>
        [
            'match'   => '0C2E 0C4A',
            'replace' => 'E09B',
        ],
    127 =>
        [
            'match'   => '0C2E 0C4B',
            'replace' => 'E09C',
        ],
    128 =>
        [
            'match'   => '0C2F 0C3F',
            'replace' => 'E09D',
        ],
    129 =>
        [
            'match'   => '0C2F 0C40',
            'replace' => 'E09E',
        ],
    130 =>
        [
            'match'   => '0C2F 0C4A',
            'replace' => 'E09F',
        ],
    131 =>
        [
            'match'   => '0C2F 0C4B',
            'replace' => 'E0A0',
        ],
    132 =>
        [
            'match'   => '0C32 0C3F',
            'replace' => 'E0A1',
        ],
    133 =>
        [
            'match'   => '0C32 0C40',
            'replace' => 'E0A2',
        ],
    134 =>
        [
            'match'   => '0C33 0C3F',
            'replace' => 'E0A3',
        ],
    135 =>
        [
            'match'   => '0C33 0C40',
            'replace' => 'E0A4',
        ],
    136 =>
        [
            'match'   => '0C35 0C3F',
            'replace' => 'E0A5',
        ],
    137 =>
        [
            'match'   => '0C35 0C40',
            'replace' => 'E0A6',
        ],
    138 =>
        [
            'match'   => '0C36 0C41',
            'replace' => 'E0A7',
        ],
    139 =>
        [
            'match'   => '0C36 0C42',
            'replace' => 'E0A8',
        ],
    140 =>
        [
            'match'   => '0C36 0C3F',
            'replace' => 'E0A9',
        ],
    141 =>
        [
            'match'   => '0C36 0C40',
            'replace' => 'E0AA',
        ],
    142 =>
        [
            'match'   => '0C39 0C3E',
            'replace' => 'E0AB',
        ],
    143 =>
        [
            'match'   => '0C39 0C41',
            'replace' => 'E0AC',
        ],
    144 =>
        [
            'match'   => '0C39 0C42',
            'replace' => 'E0AD',
        ],
    145 =>
        [
            'match'   => 'E078 0C41',
            'replace' => 'E0AE',
        ],
    146 =>
        [
            'match'   => 'E078 0C42',
            'replace' => 'E0AF',
        ],
    147 =>
        [
            'match'   => 'E07A 0C48',
            'replace' => 'E0B0',
        ],
    148 =>
        [
            'match'   => 'E07B 0C48',
            'replace' => 'E0B1',
        ],
    149 =>
        [
            'match'   => 'E07A 0C3F',
            'replace' => 'E0DA',
        ],
    150 =>
        [
            'match'   => 'E07A 0C40',
            'replace' => 'E0DB',
        ],
    151 =>
        [
            'match'   => 'E07B 0C3F',
            'replace' => 'E0DC',
        ],
    152 =>
        [
            'match'   => 'E07B 0C40',
            'replace' => 'E0DD',
        ],
    153 =>
        [
            'match'   => '0C15 ((0C3E|0C3F|0C40|0C46|0C47|0C56|0C4A|0C4B|0C4C))',
            'replace' => 'E00D \\1',
        ],
    154 =>
        [
            'match'   => '0C17 ((0C3E|0C3F|0C40|0C46|0C47|0C56|0C4A|0C4B|0C4C))',
            'replace' => 'E00E \\1',
        ],
    155 =>
        [
            'match'   => '0C18 ((0C3E|0C3F|0C40|0C46|0C47|0C56|0C4A|0C4B|0C4C))',
            'replace' => 'E00F \\1',
        ],
    156 =>
        [
            'match'   => '0C1A ((0C3E|0C3F|0C40|0C46|0C47|0C56|0C4A|0C4B|0C4C))',
            'replace' => 'E010 \\1',
        ],
    157 =>
        [
            'match'   => '0C1B ((0C3E|0C3F|0C40|0C46|0C47|0C56|0C4A|0C4B|0C4C))',
            'replace' => 'E011 \\1',
        ],
    158 =>
        [
            'match'   => '0C1C ((0C3E|0C3F|0C40|0C46|0C47|0C56|0C4A|0C4B|0C4C))',
            'replace' => 'E012 \\1',
        ],
    159 =>
        [
            'match'   => '0C1D ((0C3E|0C3F|0C40|0C46|0C47|0C56|0C4A|0C4B|0C4C))',
            'replace' => 'E013 \\1',
        ],
    160 =>
        [
            'match'   => '0C20 ((0C3E|0C3F|0C40|0C46|0C47|0C56|0C4A|0C4B|0C4C))',
            'replace' => 'E014 \\1',
        ],
    161 =>
        [
            'match'   => '0C21 ((0C3E|0C3F|0C40|0C46|0C47|0C56|0C4A|0C4B|0C4C))',
            'replace' => 'E015 \\1',
        ],
    162 =>
        [
            'match'   => '0C22 ((0C3E|0C3F|0C40|0C46|0C47|0C56|0C4A|0C4B|0C4C))',
            'replace' => 'E016 \\1',
        ],
    163 =>
        [
            'match'   => '0C24 ((0C3E|0C3F|0C40|0C46|0C47|0C56|0C4A|0C4B|0C4C))',
            'replace' => 'E017 \\1',
        ],
    164 =>
        [
            'match'   => '0C25 ((0C3E|0C3F|0C40|0C46|0C47|0C56|0C4A|0C4B|0C4C))',
            'replace' => 'E018 \\1',
        ],
    165 =>
        [
            'match'   => '0C26 ((0C3E|0C3F|0C40|0C46|0C47|0C56|0C4A|0C4B|0C4C))',
            'replace' => 'E019 \\1',
        ],
    166 =>
        [
            'match'   => '0C27 ((0C3E|0C3F|0C40|0C46|0C47|0C56|0C4A|0C4B|0C4C))',
            'replace' => 'E01A \\1',
        ],
    167 =>
        [
            'match'   => '0C28 ((0C3E|0C3F|0C40|0C46|0C47|0C56|0C4A|0C4B|0C4C))',
            'replace' => 'E01B \\1',
        ],
    168 =>
        [
            'match'   => '0C2A ((0C3E|0C3F|0C40|0C46|0C47|0C56|0C4A|0C4B|0C4C))',
            'replace' => 'E01C \\1',
        ],
    169 =>
        [
            'match'   => '0C2B ((0C3E|0C3F|0C40|0C46|0C47|0C56|0C4A|0C4B|0C4C))',
            'replace' => 'E01D \\1',
        ],
    170 =>
        [
            'match'   => '0C2D ((0C3E|0C3F|0C40|0C46|0C47|0C56|0C4A|0C4B|0C4C))',
            'replace' => 'E01E \\1',
        ],
    171 =>
        [
            'match'   => '0C2E ((0C3E|0C3F|0C40|0C46|0C47|0C56|0C4A|0C4B|0C4C))',
            'replace' => 'E01F \\1',
        ],
    172 =>
        [
            'match'   => '0C2F ((0C3E|0C3F|0C40|0C46|0C47|0C56|0C4A|0C4B|0C4C))',
            'replace' => 'E020 \\1',
        ],
    173 =>
        [
            'match'   => '0C30 ((0C3E|0C3F|0C40|0C46|0C47|0C56|0C4A|0C4B|0C4C))',
            'replace' => 'E021 \\1',
        ],
    174 =>
        [
            'match'   => '0C33 ((0C3E|0C3F|0C40|0C46|0C47|0C56|0C4A|0C4B|0C4C))',
            'replace' => 'E022 \\1',
        ],
    175 =>
        [
            'match'   => '0C35 ((0C3E|0C3F|0C40|0C46|0C47|0C56|0C4A|0C4B|0C4C))',
            'replace' => 'E023 \\1',
        ],
    176 =>
        [
            'match'   => '0C36 ((0C3E|0C3F|0C40|0C46|0C47|0C56|0C4A|0C4B|0C4C))',
            'replace' => 'E024 \\1',
        ],
    177 =>
        [
            'match'   => '0C37 ((0C3E|0C3F|0C40|0C46|0C47|0C56|0C4A|0C4B|0C4C))',
            'replace' => 'E025 \\1',
        ],
    178 =>
        [
            'match'   => '0C38 ((0C3E|0C3F|0C40|0C46|0C47|0C56|0C4A|0C4B|0C4C))',
            'replace' => 'E026 \\1',
        ],
    179 =>
        [
            'match'   => '0C39 ((0C3E|0C3F|0C40|0C46|0C47|0C56|0C4A|0C4B|0C4C))',
            'replace' => 'E027 \\1',
        ],
    180 =>
        [
            'match'   => 'E078 ((0C3E|0C3F|0C40|0C46|0C47|0C56|0C4A|0C4B|0C4C))',
            'replace' => 'E07C \\1',
        ],
    181 =>
        [
            'match'   => 'E07A ((0C3E|0C3F|0C40|0C46|0C47|0C56|0C4A|0C4B|0C4C))',
            'replace' => 'E07D \\1',
        ],
    182 =>
        [
            'match'   => 'E07B ((0C3E|0C3F|0C40|0C46|0C47|0C56|0C4A|0C4B|0C4C))',
            'replace' => 'E07E \\1',
        ],
    183 =>
        [
            'match'   => '0C15 (0C3E)',
            'replace' => 'E00D \\1',
        ],
    184 =>
        [
            'match'   => '0C17 (0C3E)',
            'replace' => 'E00E \\1',
        ],
    185 =>
        [
            'match'   => '0C18 (0C3E)',
            'replace' => 'E00F \\1',
        ],
    186 =>
        [
            'match'   => '0C1A (0C3E)',
            'replace' => 'E010 \\1',
        ],
    187 =>
        [
            'match'   => '0C1B (0C3E)',
            'replace' => 'E011 \\1',
        ],
    188 =>
        [
            'match'   => '0C1C (0C3E)',
            'replace' => 'E012 \\1',
        ],
    189 =>
        [
            'match'   => '0C1D (0C3E)',
            'replace' => 'E013 \\1',
        ],
    190 =>
        [
            'match'   => '0C20 (0C3E)',
            'replace' => 'E014 \\1',
        ],
    191 =>
        [
            'match'   => '0C21 (0C3E)',
            'replace' => 'E015 \\1',
        ],
    192 =>
        [
            'match'   => '0C22 (0C3E)',
            'replace' => 'E016 \\1',
        ],
    193 =>
        [
            'match'   => '0C24 (0C3E)',
            'replace' => 'E017 \\1',
        ],
    194 =>
        [
            'match'   => '0C25 (0C3E)',
            'replace' => 'E018 \\1',
        ],
    195 =>
        [
            'match'   => '0C26 (0C3E)',
            'replace' => 'E019 \\1',
        ],
    196 =>
        [
            'match'   => '0C27 (0C3E)',
            'replace' => 'E01A \\1',
        ],
    197 =>
        [
            'match'   => '0C28 (0C3E)',
            'replace' => 'E01B \\1',
        ],
    198 =>
        [
            'match'   => '0C2A (0C3E)',
            'replace' => 'E01C \\1',
        ],
    199 =>
        [
            'match'   => '0C2B (0C3E)',
            'replace' => 'E01D \\1',
        ],
    200 =>
        [
            'match'   => '0C2D (0C3E)',
            'replace' => 'E01E \\1',
        ],
    201 =>
        [
            'match'   => '0C2E (0C3E)',
            'replace' => 'E01F \\1',
        ],
    202 =>
        [
            'match'   => '0C2F (0C3E)',
            'replace' => 'E020 \\1',
        ],
    203 =>
        [
            'match'   => '0C30 (0C3E)',
            'replace' => 'E021 \\1',
        ],
    204 =>
        [
            'match'   => '0C33 (0C3E)',
            'replace' => 'E022 \\1',
        ],
    205 =>
        [
            'match'   => '0C35 (0C3E)',
            'replace' => 'E023 \\1',
        ],
    206 =>
        [
            'match'   => '0C36 (0C3E)',
            'replace' => 'E024 \\1',
        ],
    207 =>
        [
            'match'   => '0C37 (0C3E)',
            'replace' => 'E025 \\1',
        ],
    208 =>
        [
            'match'   => '0C38 (0C3E)',
            'replace' => 'E026 \\1',
        ],
    209 =>
        [
            'match'   => '0C39 (0C3E)',
            'replace' => 'E027 \\1',
        ],
    210 =>
        [
            'match'   => 'E078 (0C3E)',
            'replace' => 'E07C \\1',
        ],
    211 =>
        [
            'match'   => 'E07A (0C3E)',
            'replace' => 'E07D \\1',
        ],
    212 =>
        [
            'match'   => 'E07B (0C3E)',
            'replace' => 'E07E \\1',
        ],
    213 =>
        [
            'match'   => '0C15 (0C4C)',
            'replace' => 'E00D \\1',
        ],
    214 =>
        [
            'match'   => '0C17 (0C4C)',
            'replace' => 'E00E \\1',
        ],
    215 =>
        [
            'match'   => '0C18 (0C4C)',
            'replace' => 'E00F \\1',
        ],
    216 =>
        [
            'match'   => '0C1A (0C4C)',
            'replace' => 'E010 \\1',
        ],
    217 =>
        [
            'match'   => '0C1B (0C4C)',
            'replace' => 'E011 \\1',
        ],
    218 =>
        [
            'match'   => '0C1C (0C4C)',
            'replace' => 'E012 \\1',
        ],
    219 =>
        [
            'match'   => '0C1D (0C4C)',
            'replace' => 'E013 \\1',
        ],
    220 =>
        [
            'match'   => '0C20 (0C4C)',
            'replace' => 'E014 \\1',
        ],
    221 =>
        [
            'match'   => '0C21 (0C4C)',
            'replace' => 'E015 \\1',
        ],
    222 =>
        [
            'match'   => '0C22 (0C4C)',
            'replace' => 'E016 \\1',
        ],
    223 =>
        [
            'match'   => '0C24 (0C4C)',
            'replace' => 'E017 \\1',
        ],
    224 =>
        [
            'match'   => '0C25 (0C4C)',
            'replace' => 'E018 \\1',
        ],
    225 =>
        [
            'match'   => '0C26 (0C4C)',
            'replace' => 'E019 \\1',
        ],
    226 =>
        [
            'match'   => '0C27 (0C4C)',
            'replace' => 'E01A \\1',
        ],
    227 =>
        [
            'match'   => '0C28 (0C4C)',
            'replace' => 'E01B \\1',
        ],
    228 =>
        [
            'match'   => '0C2A (0C4C)',
            'replace' => 'E01C \\1',
        ],
    229 =>
        [
            'match'   => '0C2B (0C4C)',
            'replace' => 'E01D \\1',
        ],
    230 =>
        [
            'match'   => '0C2D (0C4C)',
            'replace' => 'E01E \\1',
        ],
    231 =>
        [
            'match'   => '0C2E (0C4C)',
            'replace' => 'E01F \\1',
        ],
    232 =>
        [
            'match'   => '0C2F (0C4C)',
            'replace' => 'E020 \\1',
        ],
    233 =>
        [
            'match'   => '0C30 (0C4C)',
            'replace' => 'E021 \\1',
        ],
    234 =>
        [
            'match'   => '0C33 (0C4C)',
            'replace' => 'E022 \\1',
        ],
    235 =>
        [
            'match'   => '0C35 (0C4C)',
            'replace' => 'E023 \\1',
        ],
    236 =>
        [
            'match'   => '0C36 (0C4C)',
            'replace' => 'E024 \\1',
        ],
    237 =>
        [
            'match'   => '0C37 (0C4C)',
            'replace' => 'E025 \\1',
        ],
    238 =>
        [
            'match'   => '0C38 (0C4C)',
            'replace' => 'E026 \\1',
        ],
    239 =>
        [
            'match'   => '0C39 (0C4C)',
            'replace' => 'E027 \\1',
        ],
    240 =>
        [
            'match'   => 'E078 (0C4C)',
            'replace' => 'E07C \\1',
        ],
    241 =>
        [
            'match'   => 'E07A (0C4C)',
            'replace' => 'E07D \\1',
        ],
    242 =>
        [
            'match'   => 'E07B (0C4C)',
            'replace' => 'E07E \\1',
        ],
    243 =>
        [
            'match'   => '0C15 (0C4D)',
            'replace' => 'E00D \\1',
        ],
    244 =>
        [
            'match'   => '0C17 (0C4D)',
            'replace' => 'E00E \\1',
        ],
    245 =>
        [
            'match'   => '0C18 (0C4D)',
            'replace' => 'E00F \\1',
        ],
    246 =>
        [
            'match'   => '0C1A (0C4D)',
            'replace' => 'E010 \\1',
        ],
    247 =>
        [
            'match'   => '0C1B (0C4D)',
            'replace' => 'E011 \\1',
        ],
    248 =>
        [
            'match'   => '0C1C (0C4D)',
            'replace' => 'E012 \\1',
        ],
    249 =>
        [
            'match'   => '0C1D (0C4D)',
            'replace' => 'E013 \\1',
        ],
    250 =>
        [
            'match'   => '0C20 (0C4D)',
            'replace' => 'E014 \\1',
        ],
    251 =>
        [
            'match'   => '0C21 (0C4D)',
            'replace' => 'E015 \\1',
        ],
    252 =>
        [
            'match'   => '0C22 (0C4D)',
            'replace' => 'E016 \\1',
        ],
    253 =>
        [
            'match'   => '0C24 (0C4D)',
            'replace' => 'E017 \\1',
        ],
    254 =>
        [
            'match'   => '0C25 (0C4D)',
            'replace' => 'E018 \\1',
        ],
    255 =>
        [
            'match'   => '0C26 (0C4D)',
            'replace' => 'E019 \\1',
        ],
    256 =>
        [
            'match'   => '0C27 (0C4D)',
            'replace' => 'E01A \\1',
        ],
    257 =>
        [
            'match'   => '0C28 (0C4D)',
            'replace' => 'E01B \\1',
        ],
    258 =>
        [
            'match'   => '0C2A (0C4D)',
            'replace' => 'E01C \\1',
        ],
    259 =>
        [
            'match'   => '0C2B (0C4D)',
            'replace' => 'E01D \\1',
        ],
    260 =>
        [
            'match'   => '0C2D (0C4D)',
            'replace' => 'E01E \\1',
        ],
    261 =>
        [
            'match'   => '0C2E (0C4D)',
            'replace' => 'E01F \\1',
        ],
    262 =>
        [
            'match'   => '0C2F (0C4D)',
            'replace' => 'E020 \\1',
        ],
    263 =>
        [
            'match'   => '0C30 (0C4D)',
            'replace' => 'E021 \\1',
        ],
    264 =>
        [
            'match'   => '0C33 (0C4D)',
            'replace' => 'E022 \\1',
        ],
    265 =>
        [
            'match'   => '0C35 (0C4D)',
            'replace' => 'E023 \\1',
        ],
    266 =>
        [
            'match'   => '0C36 (0C4D)',
            'replace' => 'E024 \\1',
        ],
    267 =>
        [
            'match'   => '0C37 (0C4D)',
            'replace' => 'E025 \\1',
        ],
    268 =>
        [
            'match'   => '0C38 (0C4D)',
            'replace' => 'E026 \\1',
        ],
    269 =>
        [
            'match'   => '0C39 (0C4D)',
            'replace' => 'E027 \\1',
        ],
    270 =>
        [
            'match'   => 'E078 (0C4D)',
            'replace' => 'E07C \\1',
        ],
    271 =>
        [
            'match'   => 'E07A (0C4D)',
            'replace' => 'E07D \\1',
        ],
    272 =>
        [
            'match'   => 'E07B (0C4D)',
            'replace' => 'E07E \\1',
        ],
    273 =>
        [
            'match'   => 'E00F (0C3E)',
            'replace' => '0C18 \\1',
        ],
    274 =>
        [
            'match'   => 'E013 (0C3E)',
            'replace' => '0C1D \\1',
        ],
    275 =>
        [
            'match'   => 'E01F (0C3E)',
            'replace' => '0C2E \\1',
        ],
    276 =>
        [
            'match'   => 'E020 (0C3E)',
            'replace' => '0C2F \\1',
        ],
    277 =>
        [
            'match'   => 'E027 (0C3E)',
            'replace' => '0C39 \\1',
        ],
    278 =>
        [
            'match'   => 'E00F (0C4C)',
            'replace' => '0C18 \\1',
        ],
    279 =>
        [
            'match'   => 'E013 (0C4C)',
            'replace' => '0C1D \\1',
        ],
    280 =>
        [
            'match'   => 'E01F (0C4C)',
            'replace' => '0C2E \\1',
        ],
    281 =>
        [
            'match'   => 'E020 (0C4C)',
            'replace' => '0C2F \\1',
        ],
    282 =>
        [
            'match'   => 'E027 (0C4C)',
            'replace' => '0C39 \\1',
        ],
    283 =>
        [
            'match'   => '((E00F|0C1F|E01C|E01D|E025|E026|E027)) 0C46',
            'replace' => '\\1 E007',
        ],
    284 =>
        [
            'match'   => '((E00F|0C1F|E01C|E01D|E025|E026|E027)) 0C47',
            'replace' => '\\1 E008',
        ],
    285 =>
        [
            'match'   => '((0C16|0C18|0C1F|0C2C|E01E|0C2E|0C2F|0C32|0C1D)) 0C3E',
            'replace' => '\\1 E002',
        ],
    286 =>
        [
            'match'   => '((0C19|0C1C)) 0C3E',
            'replace' => '\\1 E003',
        ],
    287 =>
        [
            'match'   => '((0C1E|E022)) 0C3E',
            'replace' => '\\1 E004',
        ],
    288 =>
        [
            'match'   => '((E02C|E02D|E02E|E02F|E030|E031|E032|E033|E034|E035|E036|E037|E038|E039|E03A|E03B|E03C|E03D|E03E|E03F|E040|E041|E042|E043|E044|E045|E046|E047|E048|E049|E04A|E04B|E04C|E04D|E04E)) 0C56',
            'replace' => '\\1 E00C',
        ],
    289 =>
        [
            'match'   => '((E04F|E050|E051|E052|E053|E054|E055|E056|E057|E058|E059|E05A|E05B|E05C|E05D|E05E|E05F|E060|E061|E062|E063|E064|E065|E066|E067|E068|E069|E06A|E06B|E06C|E06D|E06E|E06F|E070|E071)) 0C56',
            'replace' => '\\1 E00C',
        ],
    290 =>
        [
            'match'   => '(0C33) 0C41',
            'replace' => '\\1 E005',
        ],
    291 =>
        [
            'match'   => '(0C33) 0C42',
            'replace' => '\\1 E0DE',
        ],
    292 =>
        [
            'match'   => '((0C2A|0C2B|0C35)) 0C41',
            'replace' => '\\1 E006',
        ],
    293 =>
        [
            'match'   => '((0C2A|0C2B|0C35)) 0C42',
            'replace' => '\\1 E0DF',
        ],
    294 =>
        [
            'match'   => 'E046 (E07C 0C46 0C56)',
            'replace' => 'E077 \\1',
        ],
    295 =>
        [
            'match'   => 'E046 ((0C15|E00D|0C16|0C17|E00E|0C18|E00F|0C19|0C1A|E010|0C1B|E011|0C1C|E012|0C1D|E013|0C1E|0C1F|0C20|E014|0C21|E015|0C22|E016|0C23|0C24|E017|0C25|E018|0C26|E019|0C27|E01A|0C28|E01B|0C2A|E01C|0C2B|E01D|0C2C|0C2D|E01E|0C2E|E01F|0C2F|E020|0C30|E021|0C32|0C33|E022|0C35|E023|0C36|E024|0C37|E025|0C38|E026|0C39|E027|E078|E07C|E079) 0C46 0C56)',
            'replace' => 'E069 \\1',
        ],
    296 =>
        [
            'match'   => '0C4D',
            'replace' => 'E0E0',
        ],
];
?>