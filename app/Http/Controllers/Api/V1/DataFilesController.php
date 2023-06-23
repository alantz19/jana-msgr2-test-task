<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\DataFileStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\DataFileResource;
use App\Imports\ContactsImport;
use App\Jobs\DataFileImportJob;
use App\Models\DataFile;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DataFilesController extends Controller
{
    public function uploadContacts(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:txt,csv,xlsx,xls|max:' . (50 * 1024),
        ]);

        $file = $request->file('file');
        $filePath = $file->store('teams/' . auth()->user()->current_team_id . '/data-files');

        $dataFile = DataFile::create([
            'team_id' => auth()->user()->current_team_id,
            'path' => $filePath,
            'name' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'status_id' => DataFileStatusEnum::pending()->value,
            'meta' => [],
        ]);

        return response(new DataFileResource($dataFile), Response::HTTP_CREATED);
    }

    public function sample($id)
    {
        $dataFile = DataFile::findOrFail($id);

        AuthService::isModelOwner($dataFile);

        $contacts = new ContactsImport($dataFile);

        return $contacts->getSampleRows();
    }

    public function startImport($id, Request $request)
    {
        $dataFile = DataFile::findOrFail($id);

        AuthService::isModelOwner($dataFile);

        if (!$dataFile->isPending()) {
            return response([
                'message' => 'Data file is not in pending status.'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $request->validate([
            'columns' => 'required|array',
            'columns.number' => 'required_without:columns.email|numeric|min:0',
            'columns.country' => 'required_without:columns.email|numeric|min:0',
            'columns.email' => 'required_without:columns.number|email',
            'columns.*' => 'distinct|numeric|min:0',
            'list_name' => 'prohibits:list_id|required_without:list_id|string',
            'list_id' => 'prohibits:list_name|required_without:list_name|uuid|exists:lists,id',
        ]);

        $dataFile->meta = array_merge($dataFile->meta, [
            'list_name' => $request->get('list_name') ?? null,
            'list_id' => $request->get('list_id') ?? null,
            'columns' => $request->get('columns'),
        ]);
        $dataFile->saveOrFail();

        DataFileImportJob::dispatch($dataFile->id)->onQueue('data_file_import');

        return response(new DataFileResource($dataFile), Response::HTTP_OK);
    }
}
