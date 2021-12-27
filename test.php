<?php

// -------------------------1 QUESTION----------------------------------------

interface ScoreDataIndexerInterface
{
 /**
 * Returns count of users having score withing the interval.
 *
 * @param int $rangeStart
 * @param int $rangeEnd
 * @return int
 */
 public function getCountOfUsersWithinScoreRange(
    int $rangeStart,
    int $rangeEnd
 ): int;
 /**
 * Returns count of users meet input condition.
 *
 * @param string $region
 * @param string $gender
 * @param bool $hasLegalAge
 * @param bool $hasPositiveScore
 * @return int
 */
 public function getCountOfUsersByCondition(
    string $region,
    string $gender,
    bool $hasLegalAge,
    bool $hasPositiveScore
 ): int;
}



class Statistics implements ScoreDataIndexerInterface
{
    private $csvFileAddress;
    private $csvData = [];
    public function __construct() {
        $this->csvFileAddress = './test.csv';
        $this->readCsvFile();
    }

    // USER COUNT BY SCORE ( INTERFACE )
    public function getCountOfUsersWithinScoreRange( int $rangeStart, int $rangeEnd ):int{
        $counter = 0;
        foreach ($this->csvData as $key => $value) {
            if ($value['Score'] >= $rangeStart && $value['Score'] <= $rangeEnd) {
                $counter++;
            }
        }
       return $counter;
    }

    // USER COUNT BY CONDITION ( INTERFACE )
    public function getCountOfUsersByCondition( string $region, string $gender, bool $hasLegalAge, bool $hasPositiveScore ):int{
        $counter = 0;
        foreach ($this->csvData as $key => $value) {
            $legalAge = $value['Age'] > 0 && $value['Age'] < 100 ? true : false;
            $positiveScore = $value['Score'] > 0 ? true : false;
            if ($value['Region'] == $region && 
                $value['Gender'] == $gender && 
                $hasLegalAge == $legalAge && 
                $hasPositiveScore == $positiveScore
            ) {
                $counter++;
            }
        }
    }

    // READ CSV FILE
    public function readCsvFile(){
        $CSVfp = fopen($this->csvFileAddress, "r");
        if($CSVfp !== FALSE) {
        while(! feof($CSVfp)) {
            $data = fgetcsv($CSVfp, 1000, ",");
                $this->csvData[] = $data;
            }
        }
        fclose($CSVfp);
    }
}
 

$index = new Statistics();
$index->getCountOfUsersWithinScoreRange(200, 200);
$index->getCountOfUsersByCondition('CA','w',false,false);











// -------------------------2 QUESTION----------------------------------------



"   SELECT branch.country, branch.state, avg(loan.value) FROM branch
    INNER JOIN loan ON branch.id == loan.branch_id
    GROUP BY branch.id
    HAVING loan.is_active = 1;
";