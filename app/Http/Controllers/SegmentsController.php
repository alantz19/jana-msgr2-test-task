<?php

namespace App\Http\Controllers;

use App\Enums\SegmentStatusEnum;
use App\Enums\SegmentTypeEnum;
use App\Http\Resources\SegmentCollection;
use App\Models\Segment;
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
}
