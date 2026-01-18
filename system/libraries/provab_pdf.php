<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Include Composer autoload
require_once 'mpdf/autoload.php'; // adjust path if needed

// Import the Mpdf class
use Mpdf\Mpdf;

class Provab_Pdf {

    /**
     * Create PDF
     * @param string $data HTML to be converted to PDF
     * @param string $view Output type: 'show', 'D' (download), or file save
     * @param string $file_name Optional filename
     */
    public function create_pdf($data, $view = 'show', $file_name = '')
    {
        // Determine filename
        if (strcmp($view, 'D') == 0) {
            $output_pdf = empty($file_name) ? date('d-M-Y') . ".pdf" :
                ucwords(str_replace('_', ' ', strtolower($file_name))) . '-' . date('d-M-Y') . ".pdf";
        } else if (is_dir(DOMAIN_PDF_DIR)) {
            $output_pdf = empty($file_name) ? DOMAIN_PDF_DIR . time() . ".pdf" :
                DOMAIN_PDF_DIR . $file_name . ".pdf";
        } else {
            $output_pdf = 'output.pdf';
        }

        // Generate PDF based on view type
        if ($view == 'show') {
            return $this->createPDF($data);
        } else if (strcmp($view, 'D') == 0) {
            $this->downloadPDF($data, $output_pdf);
        } else {
            return $this->savePDF($data, $output_pdf);
        }
    }

    // Display PDF in browser
    public function createPDF($html)
    {
        $mpdf = new Mpdf(); // <- use Mpdf, not MPDF
        $mpdf->WriteHTML($html);
        $mpdf->Output();
        return $mpdf;
    }
    
   /*  public function createPDF($html)
    {
        $mpdf = new \Mpdf\Mpdf([
            'format' => 'A4',
            'default_font' => 'dejavusans',
            'margin_left' => 5,
            'margin_right' => 5,
            'margin_top' => 10,
            'margin_bottom' => 10
        ]);

        // ✅ Define all your CSS files here (in order)
        $css_files = [
            '/circleholidays/extras/system/library/bootstrap/css/font-awesome.min.css',
'/circleholidays/extras/system/library/bootstrap/css/bootstrap.min.css',
'/circleholidays/extras/system/template_list/template_v1/css/shared.css',
'/circleholidays/extras/system/template_list/template_v3/css/flags32.css',
'/circleholidays/extras/system/template_list/template_v3/css/flags16.css',
'/circleholidays/extras/system/library/javascript/jquery-ui-1.11.2.custom/jquery-ui.theme.min.css',
'/circleholidays/extras/system/library/datetimepicker/jquery.datetimepicker.css',
'/circleholidays/extras/system/library/javascript/jquery-ui-1.11.2.custom/jquery-ui.structure.min.css',
'/circleholidays/extras/system/template_list/template_v3/css/front_end.css',
'/circleholidays/extras/system/template_list/template_v3/css/theme_style.css?v=1',
'/circleholidays/extras/system/template_list/template_v3/css/media.css'
        ];

        // ✅ Load each CSS file if it exists
        foreach ($css_files as $css_file) {
            if (file_exists($css_file)) {
                $stylesheet = file_get_contents($css_file);
                $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
            }
        }

        // ✅ Then add your main HTML body
        $mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);

        // ✅ Clean any previous output buffer
        if (ob_get_length()) ob_clean();

        // ✅ Display PDF in browser
        $mpdf->Output('voucher.pdf', 'I');
        exit;
    } */


    // Download PDF
    public function downloadPDF($html, $filename)
    {
        $mpdf = new Mpdf([
            'format' => 'A4',
            'default_font' => 'dejavusans'
        ]);
        $mpdf->WriteHTML($html);
        $mpdf->Output($filename, 'D');
        return $mpdf;
    }

    // Save PDF to server
    public function savePDF($html, $filename)
    {
        $mpdf = new Mpdf([
            'format' => 'A4',
            'default_font' => 'dejavusans'
        ]);
        $mpdf->WriteHTML($html);
        $mpdf->Output($filename, 'F');
        return $filename;
    }
}
