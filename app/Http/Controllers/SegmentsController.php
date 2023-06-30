<?php

namespace App\Http\Controllers;

use App\Enums\SegmentStatusEnum;
use App\Enums\SegmentTypeEnum;
use App\Http\Resources\ContactSmsResource;
use App\Http\Resources\SegmentCollection;
use App\Http\Resources\SegmentResource;
use App\Models\Segment;
use App\Rules\JqQueryGroup;
use App\Services\AuthService;
use Illuminate\Http\Request;

class SegmentsController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'type' => 'sometimes|in:' . implode(',', SegmentTypeEnum::toLabels()),
        ]);

        $segments = Segment::whereTeamId(auth()->user()->current_team_id)
            ->whereStatusId(SegmentStatusEnum::active()->value)
            ->when($request->has('type'), function ($query) use ($request) {
                $query->whereType(SegmentTypeEnum::from($request->get('type'))->value);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return new SegmentCollection($segments);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:' . implode(',', SegmentTypeEnum::toLabels()),
            'query' => ['required', 'array', new JqQueryGroup],
            'sql' => 'sometimes|array',
            'sql.sql' => 'sometimes|string',
            'sql.params' => 'sometimes|array',
        ]);

        $segment = Segment::create([
            'team_id' => auth()->user()->current_team_id,
            'type' => SegmentTypeEnum::from($request->get('type'))->value,
            'name' => $request->get('name'),
            'meta' => [
                'query' => $request->get('query'),
                'sql' => $request->get('sql'),
            ],
            'status_id' => SegmentStatusEnum::active()->value,
        ]);

        return new SegmentResource($segment);
    }

    public function update(Request $request, $id)
    {
        $segment = Segment::findOrFail($id);

        AuthService::isModelOwner($segment);

        $request->validate([
            'name' => 'required|string|max:255',
            'query' => ['required', 'array', new JqQueryGroup],
            'sql' => 'sometimes|array',
            'sql.sql' => 'sometimes|string',
            'sql.params' => 'sometimes|array',
        ]);

        $segment->update([
            'name' => $request->get('name'),
            'meta' => [
                'query' => $request->get('query'),
                'sql' => $request->get('sql'),
            ],
        ]);

        return new SegmentResource($segment);
    }

    public function destroy($id)
    {
        $segment = Segment::findOrFail($id);

        AuthService::isModelOwner($segment);

        $segment->delete();

        return response()->noContent();
    }

    public function preview(Request $request)
    {
        $request->validate([
            'type' => 'required|in:' . implode(',', SegmentTypeEnum::toLabels()),
            'query' => ['required', 'array', new JqQueryGroup],
            'page' => 'sometimes|integer|min:1',
            'per_page' => 'sometimes|integer|min:1|max:100',
        ]);

        $segment = Segment::make([
            'team_id' => auth()->user()->current_team_id,
            'type' => SegmentTypeEnum::from($request->get('type'))->value,
            'name' => 'Preview',
            'meta' => [
                'query' => $request->get('query'),
                'sql' => $request->get('sql'),
            ],
            'status_id' => SegmentStatusEnum::active()->value,
        ]);

        $builder = $segment->getBuilder();
        $total = $builder->get()->count();
        $stats = $builder->get()->statistics();

        $perPage = $request->get('per_page', 10);
        $page = $request->get('page', 1);
        $offset = ($page - 1) * $perPage;
        $query = $builder->limit($perPage, $offset);
        $rows = match ($segment->type) {
            SegmentTypeEnum::numbers()->value => ContactSmsResource::collection($query->getRows()),
//            SegmentTypeEnum::emails()->value => new ContactSmsCollection($query->getRows()),
        };

        $response = [
            'total' => $total,
            'statistics' => $stats,
            'rows' => $rows,
        ];

        // @TODO replace to user role or something
        if (app()->environment('local')) {
            $response['sql'] = $builder->toSql();
        }

        return response()->json($response);
    }
}
