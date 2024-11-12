<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\PdfToText\Pdf;
use App\Models\ExtractedData;
use Illuminate\Support\Facades\Log;

class PdfController extends Controller
{
    public function index()
    {
        return view('upload-pdf');
    }

    // public function uploadPdf(Request $request)
    // {
    //     // Validate incoming request
    //     $request->validate([
    //         'pdf_file' => 'required|mimes:pdf|max:2048',
    //     ]);

    //     // Store the PDF file in the 'public' directory
    //     $pdfFile = $request->file('pdf_file');
    //     $pdfPath = $pdfFile->storeAs('pdfs', $pdfFile->getClientOriginalName(), 'public');

    //     // Extract text from the PDF
    //     $extractedText = Pdf::getText(storage_path('app/public/' . $pdfPath));

    //     // Process extracted text to find names and NIKs
    //     $data = $this->processExtractedText($extractedText);

    //     // Uncomment the following line to use dd() and see the extracted data
    //     dd($data);

    //     // Save data to database
    //     // foreach ($data as $item) {
    //     //     ExtractedData::create([
    //     //         'nama' => $item['nama'],
    //     //         'nik' => $item['nik'],
    //     //         'pdf_file_path' => $pdfPath, // Store PDF file path
    //     //     ]);
    //     // }

    //     // return response()->json(['message' => 'Data extracted and saved successfully!'], 200);
    // }
    public function uploadPdf(Request $request)
    {
        // Validate incoming request
        $request->validate([
            'pdf_file' => 'required|mimes:pdf|max:2048',
        ]);

        // Store the PDF file in the 'public' directory
        $pdfFile = $request->file('pdf_file');
        $pdfPath = $pdfFile->storeAs('pdfs', $pdfFile->getClientOriginalName(), 'public');

        // Extract text from the PDF
        try {
            $extractedText = Pdf::getText(storage_path('app/public/' . $pdfPath));
        } catch (\Exception $e) {
            // Log the error and return a response
            \Log::error('PDF extraction error: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to extract text from the PDF.'], 500);
        }

        // Process extracted text to find names and NIKs
        $data = $this->processExtractedText($extractedText);

        // Uncomment the following line to use dd() and see the extracted data
        dd($data);

        // Save data to database
        foreach ($data as $item) {
            ExtractedData::create([
                'nama' => $item['nama'],
                'nik' => $item['nik'],
                'pdf_file_path' => $pdfPath, // Store PDF file path
            ]);
        }

        return response()->json(['message' => 'Data extracted and saved successfully!'], 200);
    }


    private function processExtractedText($text)
    {
        // Example: Extract names and NIKs using regular expressions
        $pattern = '/\bNama:\s*(.+?)\s+NIK:\s*(\d+)\b/';
        preg_match_all($pattern, $text, $matches, PREG_SET_ORDER);

        $data = [];

        foreach ($matches as $match) {
            $data[] = [
                'nama' => $match[1],
                'nik' => $match[2],
            ];
        }

        return $data;
    }
}
