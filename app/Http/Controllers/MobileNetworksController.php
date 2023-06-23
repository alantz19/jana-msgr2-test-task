<?php

namespace App\Http\Controllers;

use App\Http\Resources\MobileNetworkResource;
use App\Models\MobileNetwork;
use Illuminate\Http\Request;

class MobileNetworksController extends Controller
{
    /**
     * @param int $filter_country_id
     */
    public function index(Request $request)
    {
        $request->validate([
            'filter_country_id' => 'sometimes|exists:countries,id',
        ]);

        return MobileNetworkResource::collection(MobileNetwork::query()
            ->when($request->has('filter_country_id'),
                function ($query) use ($request) {
                    $query->where(['country_id' => $request->get('filter_country_id')]);
                })
            ->get()
        );
    }

    public function store(Request $request)
    {
    }

    public function show(MobileNetwork $network)
    {
    }

    public function update(Request $request, MobileNetwork $network)
    {
    }

    public function destroy(MobileNetwork $network)
    {
    }
}
