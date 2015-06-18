<?php namespace App\Service;
	
use PHPExcel;
use PHPExcel_IOFactory;
use App\Service\Upload;

class Reader{

    protected $phpexcel;
    protected $path;
    protected $uploader;
    protected $code;

    public $csv;
    public $validityDate;

    public function __construct()
    {
        $this->phpexcel = new PHPExcel();
        $this->path     = 'csv/';
        $this->uploader = new Upload();

        $this->base_url = ($_SERVER['HTTP_HOST'] == 'http://lux.local' ? 'http://lux.local' : 'http://www.publications-droit.ch/fileadmin/lux');
    }

    public function uploadFile()
    {
        try
        {
            $this->uploader->doUpload();

            if($this->uploader->uploadStatus)
            {
                $this->csv = $this->uploader->fileName;

                return $this;
            }
            else
            {
                throw new \Exception($this->uploader->errorMsg);
            }
        }
        catch (\Exception $e)
        {
            $location = $this->base_url.'/error?message='.$e->getMessage();

            header('Location: '.$location);
            exit;
        }

    }

    public function readFile()
    {
        $inputFileName = $this->path.$this->csv;
        $objPHPExcel   = PHPExcel_IOFactory::load($inputFileName);
        $objWorksheet  = $objPHPExcel->getActiveSheet();

        $data = array();
        $i    = 1;

        foreach ($objWorksheet->getRowIterator() as $row)
        {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(FALSE); // This loops through all cells,
            // even if a cell value is not set. By default, only cells that have a value set will be iterated.
            foreach ($cellIterator as $cell)
            {
                $data[$i][] = $cell->getValue();
            }

            $i++;
        }

        return $data;
    }

    public function createExcel($data)
    {
        $objPHPExcel = PHPExcel_IOFactory::load($this->path.'usersToAdd.xlsx');
        $objPHPExcel->setActiveSheetIndex(0);
        $row = $objPHPExcel->getActiveSheet()->getHighestRow()+1;

        $objPHPExcel->getActiveSheet()->fromArray($data, NULL, 'A'.$row);

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
        $objWriter->save($this->path.'usersToAdd.xlsx');
    }

}	
	