<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\DataFileStatusEnum;
use App\Enums\DataFileTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\DataFileResource;
use App\Jobs\DataFileImportJob;
use App\Models\DataFile;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DataFilesController extends Controller
{
    public function index()
    {
        $dataFiles = DataFile::where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->paginate(25);

        return DataFileResource::collection($dataFiles);
    }

    public function uploadContacts(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:txt,csv,xlsx,xls|max:' . (50 * 1024),
            'type' => 'required|string|in:' . implode(',', DataFileTypeEnum::toArray()),
        ]);

        $file = $request->file('file');
        $filePath = $file->store('users/' . auth()->id() . '/data-files');

        $dataFile = DataFile::create([
            'user_id' => auth()->id(),
            'type' => DataFileTypeEnum::from($request->get('type'))->value,
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

        $this->authorize('view', $dataFile);

        $import = match ($dataFile->type) {
            DataFileTypeEnum::numbers()->value => new \App\Imports\NumbersFileImport($dataFile),
            DataFileTypeEnum::emails()->value => new \App\Imports\EmailFileImport($dataFile),
        };

        return $import->getSampleRows();
    }

    public function startImport($id, Request $request)
    {
        $dataFile = DataFile::findOrFail($id);

        $this->authorize('update', $dataFile);

        if (!$dataFile->isPending()) {
            return response([
                'message' => 'Data file is not in pending status.'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $rules = match ($dataFile->type) {
            DataFileTypeEnum::numbers()->value => [
                'columns' => 'required|array',
                'columns.number' => 'required|numeric|min:0',
                'columns.country' => 'required|numeric|min:0',
                'columns.*' => 'distinct|numeric|min:0',
            ],
            DataFileTypeEnum::emails()->value => [
                'columns' => 'required|array',
                'columns.email' => 'required|numeric',
                'columns.*' => 'numeric',
            ],
        };

        $request->validate(array_merge($rules, [
            'list_name' => 'prohibits:list_id|required_without:list_id|string',
            'list_id' => 'prohibits:list_name|required_without:list_name|uuid|exists:lists,id',
        ]));

        $dataFile->status_id = DataFileStatusEnum::queued()->value;
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
