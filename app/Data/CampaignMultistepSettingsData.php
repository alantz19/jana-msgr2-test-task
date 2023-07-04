<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class CampaignMultistepSettingsData extends Data
{
    //'step_size' => 100,
    //                'min_ctr' => 0.04,
    //                'optimise_texts' => true,
    //                'optimise_sender_ids' => true,
    //                'optimise_segments' => true,
    //                'optimise_routing_plan' => true,
    //                'optimise_hours' => true,
    //                'optimise_days' => true,
    //                'optimise_countries' => true,
    //                'optimise_carriers' => true,
    public function __construct(
        //difference between optimisation steps
        public int   $step_size = 200,
        //for example - 3% ctr is 3
        public float $min_ctr = 3,
        public bool  $optimise_texts = true,
//        public bool $is_auto_expand_texts,
        public bool  $optimise_sender_ids = true,
        public bool  $optimise_segments = true,
        public bool  $optimise_routes = true,
        public bool  $optimise_countries = true,
        public bool  $optimise_carriers = true,
    )
    {
    }
}
