<?php
// src/Service/PdfService.php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Dompdf\Dompdf;
use Dompdf\Options;

class PdfService
{
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function generatePdfAndSave($html, $invoice)
    {
        // Configure Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('defaultFont', 'Arial');

        // Instantiate Dompdf with options
        $domPdf = new Dompdf($options);

        // Load HTML content
        $domPdf->loadHtml($html);

        // Render PDF (outputs as string)
        $domPdf->render();

        // Get the generated PDF content
        $pdfOutput = $domPdf->output();

        // Path to save the PDF file (in public directory)
        $pdfPath = $this->params->get('kernel.project_dir').'/public/pdf/details_commande_'.$invoice.'.pdf';

      
        

        // Write PDF content to a file
        file_put_contents($pdfPath, $pdfOutput);

        // Return the path to the saved PDF file
        return $pdfPath;
    }
}
