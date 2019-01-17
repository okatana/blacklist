<?php

/**
 * Created by PhpStorm.
 * User: Katanugina
 * Date: 17.01.2019
 * Time: 15:17
 */

require_once dirname(__FILE__) . '/PHPExcel/Classes/PHPExcel.php';

class BlackListExcel
{
    const FONT_COLOR = 'AA000080';
    const FONT_COLOR_WHITE = 'FFFFFF';
    const BG_COLOR1 = 'AAd3d3d3';
    const BG_COLOR2 = 'AAecebeb';
    const  BG_COLOR3 = '960835';
    const BG_COLOR4 = 'F2E7A7';

    const TXT_COLOR3 = 'FFFFFF';

    private $phpexcel;
    private $objWriter;
    private $model;
    private $pageCount;
    private $lastRowIndex;
    private $tmpdir;
    private $vids;
    function __construct($tmpdir)
    {
        // $this->logger = new \guideh\MyLogger($config['logger']);
        $this->tmpdir = $tmpdir;
        $this->phpexcel = new PHPExcel(); // Создаём объект PHPExcel
        $this->objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel2007');
        $this->phpexcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);
    }

    function toExcel($model,$vids) {
        $this->model = $model;
        $this->vids = $vids;
        $this->prepareExcel();
    }

    private function prepareExcel() {
        $this->prepareTable();
        $now_date =  date('Y-m-d');
        $this->downloadExcel('blacklist' . $now_date);
    }




    private  function prepareTable(){
        $page = $this->createPage();
        $this->preparePageHeader($page);
        $this->preparePageBody($page);
    }

    private function createPage()
    {
        if ($this->pageCount++ == 0) {
            $page = $this->phpexcel->getSheet(0);
//      $rus = 'абвгдеёжзийклмнопрстуфхцчшщъыьэюя';
//      $rus = 'вгдеёжзийклмнопрстуфхцчшщъыьэюя';
//      $rusu = 'ВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ';
//      $title = $rusu;
        } else
            $page = $this->phpexcel->createSheet();
        $title = 'Blacklist';
        $page->setTitle($title);
        $this->lastRowIndex = 1;
        return $page;
    }


    private  function  preparePageHeader($page){
        $this->lastRowIndex++;
        $page->setCellValue('A1', 'ID');
        $page->setCellValue('B1', 'ФАМИЛИЯ');
        $page->setCellValue('C1', 'ИМЯ');
        $page->setCellValue('D1', 'ОТЧЕСТВО');
        $page->setCellValue('E1', 'ДАТА РОЖДЕНИЯ');
        $page->setCellValue('F1', 'ВИД СТРАХОВАНИЯ');
        $page->setCellValue('G1', 'КОММЕНТАРИЙ');
        $page->setCellValue('H1', 'МЕНЕДЖЕР');
        $page->setCellValue('I1', 'EMAIL МЕНЕДЖЕРА');

        $this->lastRowIndex = 1;
            // autosize the columns
            // $page->getColumnDimension('A')->setAutoSize(true);
        $page->getColumnDimension('A')->setWidth(5);
        $page->getColumnDimension('B')->setWidth(30);
        $page->getColumnDimension('C')->setWidth(30);
        $page->getColumnDimension('D')->setWidth(30);
        $page->getColumnDimension('E')->setWidth(90);
        $page->getColumnDimension('F')->setWidth(30);
        $page->getColumnDimension('G')->setWidth(30);
        $page->getColumnDimension('H')->setWidth(30);
        $page->getColumnDimension('I')->setWidth(30);

        $page->getStyle('A1:I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $page->getStyle('A1:I1')->getFont()->setColor(new PHPExcel_Style_Color(self::FONT_COLOR_WHITE));

        $fill = $page->getStyle('A1:I1')->getFill();
        $fill->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $fill->setStartColor(new PHPExcel_Style_Color(self::BG_COLOR3));
    }

        private function preparePageBody($page){
            $tableData = $this->model->getClientsByVids($this->vids);

            foreach ($tableData as $tableRow) {
                $this->preparePageTableRow($page, $tableRow);
            }

        }

    private function preparePageTableRow($page, $tableRow)
    {
        $this->lastRowIndex++;
        $page->setCellValue($this->getRow('A'), $tableRow->id);
        $page->setCellValue($this->getRow('B'), $tableRow->lastname);
        $page->setCellValue($this->getRow('C'), $tableRow->firstname);
        $page->setCellValue($this->getRow('D'), $tableRow->midname);
        $page->setCellValue($this->getRow('E'), $tableRow->birthday);
        $page->setCellValue($this->getRow('F'), $tableRow->name);
        $page->setCellValue($this->getRow('G'), $tableRow->comment);
        $page->setCellValue($this->getRow('H'), $tableRow->manager);
        $page->setCellValue($this->getRow('I'), $tableRow->email);


        $page->getStyle($this->getRow('B', 'D'))->getAlignment()->
        setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $page->getColumnDimension('D')->setWidth(50);
    }

    private function getRow($letterFirst, $letterSecond = null, $numFirst = 0, $numSecond = 0)
    {
        if ($letterSecond)
            return '' . $letterFirst . ($this->lastRowIndex + $numFirst) . ':' . $letterSecond . ($this->lastRowIndex + $numSecond);
        else
            return '' . $letterFirst . ($this->lastRowIndex + $numFirst);
    }

    private function downloadExcel($excel_title)
    {
        ob_end_clean();//очистка всех предустановок в header
//Setting the header type
        header("Pragma: no-cache");
//  header("Content-type: application/vnd.ms-excel;charset:UTF-8");
//  header('Content-length: '.strlen($content));
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset:UTF-8');
        header('Content-Disposition: attachment;filename="' . $excel_title . '.xlsx"');
        // header('Content-Transfer-Encoding: binary');
        header('Cache-Control: max-age=0');
//  header('Content-Encoding:gzip ');

//  $objWriter->save('php://output');
        $tmp_dir = $this->get_temp_dir();
//  $file = $upload_dir['path'] . time() . '.requests.xlsx';

//  $filePath = sys_get_temp_dir() . "/" . rand(0, getrandmax()) . rand(0, getrandmax()) . ".tmp";
//  $filePath = $upload_dir['path'] . rand(0, getrandmax()) . rand(0, getrandmax()) . ".xlsx";
        $filePath = $tmp_dir . rand(0, getrandmax()) . ".xlsx"; //имя файла для временного сохранения в папке tmp
//  $filePath = $upload_dir['url'] . time() . '.test1.xlsx';
        $this->objWriter->save($filePath);
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        unlink($filePath); //удаление временного файла
        exit();//без этого сервер добавит в конец парочку лишних байтов и excel будет открываться с сообщением об ошибке
    }

    private function get_temp_dir()
    {
        if ($this->tmpdir == '')
            $this->tmpdir = sys_get_temp_dir();  //может зависеть от сайта  но на локалке сюда не попадаем - см кофигурационный файл и конструктор - 

        return $this->tmpdir;
//    return = (wp_upload_dir())['path']; для WP
    }




    private function log($message) {
        file_put_contents(self::LOG_FILE, $message.PHP_EOL, FILE_APPEND);
    }

}

