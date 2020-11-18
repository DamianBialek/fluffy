<?php


namespace App\Pdf;


use Illuminate\Support\Facades\View;
use Mpdf\Mpdf;

class Pdf extends Mpdf
{
    public function __construct(array $config = [])
    {
        parent::__construct(array_merge([
            'margin_left' => 20,
            'margin_right' => 15,
            'margin_top' => 48,
            'margin_bottom' => 25,
            'margin_header' => 10,
            'margin_footer' => 10
        ], $config));

        $this->SetProtection(["print"]);
        $this->SetDisplayMode("fullpage");
    }

    public static function loadView($view, $data = [], $mergeData = [], $config = [])
    {
        $mpdf = new static($config);
        $html = view($view, $data, $mergeData)->render();
        $mpdf->WriteHTML($html);

        return $mpdf;
    }
}
