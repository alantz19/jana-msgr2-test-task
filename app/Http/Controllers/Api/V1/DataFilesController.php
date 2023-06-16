<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\DataFile;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DataFilesController extends Controller
{
    public function uploadContacts(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:txt,csv,xlsx,xls|max:' . (50 * 1024),
            'type' => 'required|numeric|in:' . DataFile::TYPE_NUMBERS_FILE . ',' . DataFile::TYPE_EMAIL_FILE,
        ]);

        $file = $request->file('file');
        $filePath = $file->store('users/' . auth()->id() . '/data-files');

        $type = (int) $request->get('type');

        $dataFile = DataFile::create([
            'user_id' => auth()->id(),
            'type' => $type,
            'path' => $filePath,
            'name' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'meta' => [],
        ]);

        $ext = $file->getClientOriginalExtension();

        if (in_array($ext, ['xlsx', 'xls'])) {
            $import = match ($type) {
                DataFile::TYPE_NUMBERS_FILE => new \App\Imports\NumbersFileImport($dataFile),
//                DataFile::TYPE_EMAIL_FILE => new \App\Imports\EmailFileImport($dataFile),
            };

            $csvFile = $import->xls2csv();

            $dataFile->meta = array_merge($dataFile->meta, [
                'csv_file' => str_replace(storage_path('app/'), '', $csvFile),
            ]);
            $dataFile->save();
        }

        return response($dataFile, Response::HTTP_CREATED);
    }
}
