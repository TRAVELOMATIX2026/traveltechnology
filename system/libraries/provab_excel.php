<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once 'PHPExcel/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class Provab_Excel
{
    protected $CI;

    public function __construct()
    {
        $this->CI = &get_instance();
    }

    /**
     * Excel Export using PhpSpreadsheet
     */
    public function excel_export($headings, $fields, $export_data, $excel_sheet_properties)
    {
        // Create Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();

        /** ============================
         *  HEADER SETUP
         *  ============================ */
        foreach ($headings as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }

        // First & last header cells
        $firstCell = array_key_first($headings);
        $lastCell  = array_key_last($headings);
        $headerRange = $firstCell . ':' . $lastCell;

        // Header style
        $sheet->getStyle($headerRange)->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('FFFF00');

        $sheet->getStyle($headerRange)->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // Row height
        $sheet->getRowDimension(1)->setRowHeight(25);

        // Auto column width
        foreach ($headings as $cell => $value) {
            $colLetter = preg_replace('/[0-9]+/', '', $cell);
            $sheet->getColumnDimension($colLetter)->setAutoSize(true);
        }

        /** ============================
         *  CONTENT / BODY
         *  ============================ */
        $row = 2;
        $serial = 1;

        foreach ($export_data as $data) {

            $firstField = true; // first field is serial no.

            foreach ($fields as $cell => $field) {

                if ($firstField) {
                    // Serial number
                    $sheet->setCellValue($cell . $row, $serial);
                    $firstField = false;
                } else {
                    // Other cell values
                    $value = isset($data[$field]) ? str_replace(['<br/>', 'br/>'], "", $data[$field]) : '';
                    $sheet->setCellValue($cell . $row, $value);
                }

                $sheet->getRowDimension($row)->setRowHeight(20);
            }

            $serial++;
            $row++;
        }

        // Center align first column (A column)
        $sheet->getStyle("A2:A" . ($row - 1))
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        /** ============================
         *  META DATA
         *  ============================ */
        $spreadsheet->getProperties()
            ->setCreator($excel_sheet_properties['creator'])
            ->setTitle($excel_sheet_properties['title'])
            ->setDescription($excel_sheet_properties['description'])
            ->setCategory('programming');

        // Sheet title
        $sheet->setTitle($excel_sheet_properties['sheet_title']);

        /** ============================
         *  OUTPUT DOWNLOAD
         *  ============================ */
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $excel_sheet_properties['title'] . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
?>
