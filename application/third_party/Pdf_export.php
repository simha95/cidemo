<?php
require_once('tcpdf/config/tcpdf_config.php');

require_once 'tcpdf/tcpdf.php';

// extend TCPF with custom functions
class PDF_Export extends TCPDF
{

    public function setCIObject()
    {
        $this->CI = & get_instance();
    }

    public function initialize($header = 'Records')
    {
        $this->setCIObject();
        // set document information
        $this->SetCreator(PDF_CREATOR);
        $this->SetAuthor(PDF_AUTHOR);
        $this->SetTitle(PDF_TITLE);
        $this->SetSubject(PDF_SUBJECT);
        $this->SetKeywords(PDF_KEYWORDS);

        // set default header data
        $this->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $header, '', array(0, 0, 0), array(255, 255, 255));
        #$this->setFooterData(array(0, 0, 0), array(255, 255, 255));
        // set header and footer fonts
        $this->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $this->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $this->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $this->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $this->SetHeaderMargin(PDF_MARGIN_HEADER);
        $this->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $this->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $this->setImageScale(PDF_IMAGE_SCALE_RATIO);

        if ($this->CI->config->item('MULTI_LINGUAL_PROJECT') == "Yes") {

//            if (in_array($this->CI->session->userdata('DEFAULT_LANG'), array("AR", "FA"))) {
            // set some language dependent data:
            $lg = array();
            $lg['a_meta_charset'] = 'UTF-8';
            $lg['a_meta_dir'] = 'rtl';
            $lg['a_meta_language'] = 'fa';
            $lg['w_page'] = 'page';

            // set some language-dependent strings (optional)
            $this->setLanguageArray($lg);

            //After Write
            $this->setRTL(false);

            // set font
            $this->SetFont('dejavusans', '', 12);
//            } else {
//
//                // set font
//                $this->SetFont('helvetica', '', 12);
//            }
        } else {

            // set font
            $this->SetFont('helvetica', '', 12);
        }

        // add zoom
        $this->SetDisplayMode(100);
        // add page
        $this->AddPage();
    }

    public function writeGridTable($headers = array(), $data = array(), $widths = array(), $aligns = array())
    {
        $this->setCIObject();
        $widths = $this->getProperWidths($widths);
        //header html
        $headers_html = '
    <tr>';
        for ($i = 0; $i < count($headers); $i++) {
            $aligns[$i] = in_array(strtolower($aligns[$i]), array("center", "right")) ? strtolower($aligns[$i]) : "left";
            $headers_html .= '
        <th width="' . $widths[$i] . '%" align="' . $aligns[$i] . '" style="font-size:15px;" bgcolor="#EAEAEA" border="1"><b>' . $headers[$i] . '</b></th>';
        }
        $headers_html .= '
    </tr>';

        //data html
        $data_html = '';
        for ($i = 0; $i < count($data); $i++) {
            $data_html .= '
    <tr>';
            $j = 0;
            foreach ($data[$i] as $key => $val) {
                if (is_array($val) && $val['file']) {
                    if ($val['file'] == 1) {
                        $value = '<img src="' . $val['data'] . '" width="' . $val['width'] . '" height="' . $val['height'] . '" />';
                    } elseif ($val['file'] == 2) {
                        $value = '<a href="' . $val['data'] . '" color="#01bbe4">Download</a>';
                    } else {
                        $value = $val['data'];
                    }
                } else {
                    $value = $val;
                }
                $data_html .= '
        <td width="' . $widths[$j] . '%" align="' . $aligns[$j] . '" border="1">' . $value . '</td>';
                $j++;
            }
            $data_html .= '
    </tr>';
        }

        // table html
        $table_html = '
<table width="100%" cellpadding="4" cellspacing="0" border="0">
    ' . $headers_html . '
    ' . $data_html . '
</table>';

        $this->writeHTML($table_html);
    }

    public function getProperWidths($widths = array(), $limit = 100)
    {
        $sum = array_sum($widths);
        for ($i = 0; $i < count($widths); $i++) {
            if ($sum != $limit) {
                $widths[$i] = round(($widths[$i] / $sum) * $limit);
            }
        }
        $tot = array_sum($widths);
        if ($tot > $limit) {
            $widths[count($widths) - 1] = end($widths) - ($tot - $limit);
        }
        return $widths;
    }
}
