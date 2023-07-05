<?php

namespace App\Http\Controllers;

use App\Enums\JqBuilderFieldEnum;
use App\Enums\JqBuilderOperatorEnum;
use App\Enums\SegmentStatusEnum;
use App\Enums\SegmentTypeEnum;
use App\Http\Resources\ContactSmsResource;
use App\Http\Resources\JqFieldResource;
use App\Http\Resources\SegmentResource;
use App\Models\CustomField;
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

        return SegmentResource::collection($segments);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:' . implode(',', SegmentTypeEnum::toLabels()),
            /**
             * @var array<string, string> $query
             * @example {"condition":"AND","rules":[{"id":"clicked_count","field":"clicked_count","type":"integer","input":"number","operator":"greater","value":0},{"id":"country_id","field":"country_id","type":"integer","input":"select","operator":"equal","value":225},{"condition":"OR","rules":[{"id":"leads_count","field":"leads_count","type":"integer","input":"number","operator":"equal","value":1},{"id":"sales_count","field":"sales_count","type":"integer","input":"number","operator":"equal","value":1}]},{"id":"date_created","field":"date_created","type":"date","input":"text","operator":"equal","value":"2023-07-05"}]}
             */
            'query' => ['required', 'array', new JqQueryGroup],
        ]);

        $segment = Segment::create([
            'team_id' => auth()->user()->current_team_id,
            'type' => SegmentTypeEnum::from($request->get('type'))->value,
            'name' => $request->get('name'),
            'meta' => [
                'query' => $request->get('query'),
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
            /**
             * @var array<string, string> $query
             * @example {"condition":"AND","rules":[{"id":"clicked_count","field":"clicked_count","type":"integer","input":"number","operator":"greater","value":0},{"id":"country_id","field":"country_id","type":"integer","input":"select","operator":"equal","value":225},{"condition":"OR","rules":[{"id":"leads_count","field":"leads_count","type":"integer","input":"number","operator":"equal","value":1},{"id":"sales_count","field":"sales_count","type":"integer","input":"number","operator":"equal","value":1}]},{"id":"date_created","field":"date_created","type":"date","input":"text","operator":"equal","value":"2023-07-05"}]}
             */
            'query' => ['required', 'array', new JqQueryGroup],
        ]);

        $segment->update([
            'name' => $request->get('name'),
            'meta' => [
                'query' => $request->get('query'),
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
            /**
             * @var array<string, string> $query
             * @example {"condition":"AND","rules":[{"id":"clicked_count","field":"clicked_count","type":"integer","input":"number","operator":"greater","value":0},{"id":"country_id","field":"country_id","type":"integer","input":"select","operator":"equal","value":225},{"condition":"OR","rules":[{"id":"leads_count","field":"leads_count","type":"integer","input":"number","operator":"equal","value":1},{"id":"sales_count","field":"sales_count","type":"integer","input":"number","operator":"equal","value":1}]},{"id":"date_created","field":"date_created","type":"date","input":"text","operator":"equal","value":"2023-07-05"}]}
             */
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
            'total' => (int) $total,
            /** @var array<ContactSmsResource> $rows */
            'rows' => $rows,
            /** @var array<string, string> $stats only for admins (elapsed, rows_read, bytes_read) (null for users) */
            'stats' => null,
            /** @var string $sql only for admins (null for users) */
            'sql' => null,
        ];

        if (AuthService::isAdmin()) {
            $response['stats'] = $stats;
            $response['sql'] = $builder->toSql();
        }

        return response()->json($response);
    }

    public function fields()
    {
        $customFields = CustomField::whereTeamId(auth()->user()->current_team_id)
            ->get()
            ->map(function ($field) {
                $arr = [];
                $arr[$field->field_key] = $field->field_name;
                return $arr;
            })
            ->flatMap(fn ($item) => $item)
            ->toArray();

        $fields = collect(JqBuilderFieldEnum::toLabels())
            ->map(function ($fieldName) use ($customFields) {
                $field = JqBuilderFieldEnum::from($fieldName);

                return $field->toJqRule($customFields);
            });
        $operators = JqBuilderOperatorEnum::toLabels();

        return response()->json([
            /** @var array<JqFieldResource> $fields */
            'fields' => JqFieldResource::collection($fields),
            /** @var array<string> $operators */
            'operators' => $operators,
        ]);
    }
}
