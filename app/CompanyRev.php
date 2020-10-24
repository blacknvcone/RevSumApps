<?php

namespace App;

use Jenssegers\Model\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use League\Csv\Writer;
use League\Csv\Reader;
use League\Csv\Statement;
use League\Csv\CannotInsertRecord;
use League\Csv\Exception;
use App\Libraries\CSV_Data;


class CompanyRev extends Model{
    /**
     * Fillable attributes
     *
     * @var array
     */

    /*===================== KEY FIELD =============================
     * CMGUnmaskedID,
     * CMGUnmaskedName,
     * ClientTier,
     * GCPStream,
     * GCPBusiness,
     * CMGGlobalBU,
     * CMGSegmentName,
     * GlobalControlPoint,
     * GCPGeography,
     * GlobalRelationshipManagerName,
     * REVENUE_FY14,
     * REVENUE_FY15,
     * Deposits_EOP_FY14,
     * Deposits_EOP_FY15x,
     * TotalLimits_EOP_FY14,
     * TotalLimits_EOP_FY15,
     * TotalLimits_EOP_FY15x,
     * RWAFY15,
     * RWAFY14,
     * REV/RWA FY14,
     * REV/RWA FY15,
     * NPAT_AllocEq_FY14,
     * NPAT_AllocEq_FY15X,
     * Company_Avg_Activity_FY14,
     * Company_Avg_Activity_FY15,
     * ROE_FY14,ROE_FY15
     * =============================================================
     */

    //
    protected $fillable = [];

    /**
     * Csv filename
     *
     * @var const
     */
    const FILENAME = "companies.csv";

    /**
     * Get storage path.
     *
     * @return string
     */
    public static function getStoragePath(){
        return storage_path('app/public').'/'.self::FILENAME;
    }

    /**
     * Get total number of records in csv file.
     *
     * @return int
     */
    public static function getTotal()
    {
        $total = 0;
        if (File::exists(self::getStoragePath())) {
            $reader = Reader::createFromPath(self::getStoragePath(), 'r');
            $reader->setHeaderOffset(0); //set the CSV header offset to 0

            $total =  count($reader); // return total number of records
            // unset to close the file resource
            unset($reader);
        }

        return $total;
    }

    /**
     * Get records from csv file.
     *
     * @param int $offset
     * @param int $limit
     * @return \App\CompanyRev[]
     */
    public static function getRecords($offset=0, $limit=null, $reqFilter)
    {
        $companies = [];
        if (File::exists(self::getStoragePath())) {
            $reader = Reader::createFromPath(self::getStoragePath(), 'r');
            $reader->setHeaderOffset(0); //set the CSV header offset to 0

            if ($limit > 0) {
                $stmt = (new Statement())
                    ->offset($offset)
                    ->limit($limit);

                $result = $stmt->process($reader);

                foreach ($result->getRecords() as $index => $record) {
                    $instance = new static($record);
                    $instance->setAttribute('offset', $index);

                    if($reqFilter['CMGSegmentName'] == null &&
                        $reqFilter['CMGUnmaskedName'] == null &&
                            $reqFilter['ClientTier'] == null){

                        // All Field Null
                        $companies[] = $instance;

                    }else{
                        if($reqFilter['CMGSegmentName'] != null){
                            if(false !== strpos($record['CMGSegmentName'], $reqFilter['CMGSegmentName'])){
                                $companies[] = $instance;
                            }else if ($reqFilter['CMGUnmaskedName'] != null){
                                if(false !== strpos($record['CMGUnmaskedName'], $reqFilter['CMGUnmaskedName'])){
                                    $companies[] = $instance;
                                } else if($reqFilter['ClientTier'] != null){
                                    if(false !== strpos($record['ClientTier'], $reqFilter['ClientTier'])){
                                        $companies[] = $instance;
                                    }
                                }
                            }

                        }else if ($reqFilter['CMGUnmaskedName'] != null){
                            if(false !== strpos($record['CMGUnmaskedName'], $reqFilter['CMGUnmaskedName'])){
                                $companies[] = $instance;
                            } else if($reqFilter['ClientTier'] != null){
                                if(false !== strpos($record['ClientTier'], $reqFilter['ClientTier'])){
                                    $companies[] = $instance;
                                }
                            }
                        }if($reqFilter['ClientTier'] != null){
                            if(false !== strpos($record['ClientTier'], $reqFilter['ClientTier'])){
                                $companies[] = $instance;
                            }
                        }

                    }
                }
            } else {
                foreach ($reader->getRecords() as $index => $record) {
                    $instance = new static($record);
                    $instance->setAttribute('offset', $index);
                    $companies[] = $instance;
                }
            }

            // unset to close the file resource
            unset($reader);
        }
        return $companies;
    }


    /**
     * Get One records from csv file.
     *
     * @param int $offset
     * @return \App\CompanyRev|false;
     */
    public static function getOne($offset)
    {
        if (File::exists(self::getStoragePath())) {
            $reader = Reader::createFromPath(self::getStoragePath(), 'r');
            $reader->setHeaderOffset(0); // Set header offset always to 0

            // $offset is the nth offset of the record in csv file
            $stmt = (new Statement())
                ->offset($offset-1) // need to decrement 1 since we set header offset 0
                ->limit(1);

            // access the 0th record from the recordset (indexing starts at 0)
            $record = $stmt->process($reader)->fetchOne(0);

            // unset to close the file resource
            unset($reader);

            if (!empty($record)) {
                $instance = new static($record);
                $instance->setAttribute('offset', $offset);
                return $instance;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Get and Update One records from csv file.
     *
     * @param int $offset
     * @return \App\CompanyRev|false;
     */
    public static function updateOne(int $offset, $request)
    {
        $dtOld = self::getOne($offset);

        try {
            $editorCSV = new CSV_Data(self::getStoragePath());
            $editorCSV->edit("ROE_FY14",$dtOld['ROE_FY14'],$request['ROE_FY14']);
            $editorCSV->edit("ROE_FY15",$dtOld['ROE_FY15'],$request['ROE_FY15']);
            $editorCSV->edit("REVENUE_FY14",$dtOld['REVENUE_FY14'],$request['REVENUE_FY14']);
            $editorCSV->edit("REVENUE_FY15",$dtOld['REVENUE_FY15'],$request['REVENUE_FY15']);
            $editorCSV->edit("REV/RWA FY14",$dtOld['REV/RWA FY14'],$request['REV_RWA_FY14']);
            $editorCSV->edit("REV/RWA FY15",$dtOld['REV/RWA FY15'],$request['REV_RWA_FY15']);
            $editorCSV->edit(" TotalLimits_EOP_FY14",$dtOld[' TotalLimits_EOP_FY14'],$request['TotalLimits_EOP_FY14']);
            $editorCSV->edit(" TotalLimits_EOP_FY15",$dtOld[' TotalLimits_EOP_FY15'],$request['TotalLimits_EOP_FY15']);
            $editorCSV->edit("Company_Avg_Activity_FY14",$dtOld['Company_Avg_Activity_FY14'],$request['Company_Avg_Activity_FY14']);
            $editorCSV->edit("Company_Avg_Activity_FY15",$dtOld['Company_Avg_Activity_FY15'],$request['Company_Avg_Activity_FY15']);


            $editorCSV->save();

            return true;
        }catch (\Exception $e){
            return false;
        }

    }
}
