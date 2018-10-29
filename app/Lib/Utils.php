<?php

class Utils {
    /**
     * ciphering input string w/ Security.salt as key, base64-encoded
     * and / are replaced by _ and + by - .
     *
     * @param  string $string
     *
     * @return string
     */
    public static function tokenize($string) {
        return str_replace(['/', '+', '='], ['_', '-', '~'], base64_encode(Security::cipher($string, Configure::read('Security.salt'))));
    }

    /**
     * reverts tokenize.
     *
     * @param  string $string
     *
     * @return string
     */
    public static function detokenize($string) {
        return Security::cipher(base64_decode(str_replace(['_', '-', '~'], ['/', '+', '='], $string)), Configure::read('Security.salt'));
    }

    /**
     * Creates markup for excel downloads, prevents Excel warning about corrupt file
     * by using xls-specific XML markup
     *
     * @param string $worksheetName
     * @param string $tableMarkup
     *
     * @return string
     */
    public static function htmlToXls($worksheetName, $tableMarkup) {
        $data = '<html xmlns:x="urn:schemas-microsoft-com:office:excel">
		<head>
		    <!--[if gte mso 9]>
		    <xml>
		        <x:ExcelWorkbook>
		            <x:ExcelWorksheets>
		                <x:ExcelWorksheet>
		                    <x:Name>:name</x:Name>
		                    <x:WorksheetOptions>
		                        <x:Print>
		                            <x:ValidPrinterInfo/>
		                        </x:Print>
		                    </x:WorksheetOptions>
		                </x:ExcelWorksheet>
		            </x:ExcelWorksheets>
		        </x:ExcelWorkbook>
		    </xml>
		    <![endif]-->
		</head>

		<body>
		   :table
		</body></html>';

        return String::insert($data, [
            'name'  => $worksheetName,
            'table' => $tableMarkup,
        ]);
    }

    /**
     * Searches a folder for a file with the given $filename by a case-insensitive
     * comparison. Will return the correct filename or false if no file is found
     *
     * @param string $path
     * @param string $filename
     *
     * @return mixed
     */
    public static function findCorrectFilename($path, $filename) {
        App::uses('Folder', 'Utility');
        $f = new Folder($path);
        $list = $f->find();

        foreach ($list as $file) {
            if (strcasecmp($filename, $file) == 0) {
                return $file;
            }
        }

        return false;
    }

    /**
     * calculates the final score and winner of a given match
     *
     * @param  array $match
     *
     * @return array
     */
    public static function calculateFinalScore($match) {
        if (isset($match['Match'])) {
            $match = $match['Match'];
        }
        if (in_array($match['status'], [Status::MATCH_PRE_MATCH, Status::MATCH_REVOKED, Status::MATCH_WITHDRAWN, Status::MATCH_POSTPONED, Status::MATCH_CANCELED, Status::MATCH_DISCARDED])) {
            return [
                'teamHomeTotal' => 0,
                'teamAwayTotal' => 0,
                'winner'        => false,
            ];
        }
        $teamHomeTotal = $teamAwayTotal = 0;
        for ($i = 1; $i < 6; $i++) {
            $teamHomeTotal += $match['team_home_score' . $i];
            $teamAwayTotal += $match['team_away_score' . $i];
        }
        $scores = [
            'teamHomeTotal' => $teamHomeTotal,
            'teamAwayTotal' => $teamAwayTotal,
        ];
        if ($teamHomeTotal > $teamAwayTotal) {
            $scores['winner'] = $match['team_home_id'];
            $scores['looser'] = $match['team_away_id'];
        } else if ($teamHomeTotal < $teamAwayTotal) {
            $scores['winner'] = $match['team_away_id'];
            $scores['looser'] = $match['team_home_id'];
        } else {
            $scores['winner'] = [$match['team_home_id'], $match['team_away_id']];
        }

        return $scores;
    }
}