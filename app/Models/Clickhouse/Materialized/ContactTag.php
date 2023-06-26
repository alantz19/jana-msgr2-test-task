<?php

namespace App\Models\Clickhouse\Materialized;

use PhpClickHouseLaravel\BaseModel;
use PhpClickHouseLaravel\Builder;
use PhpClickHouseLaravel\RawColumn;

class ContactTag extends BaseModel
{
    protected $table = 'contact_tags_materialized';

    public static function findAll(string $teamId, string $contactId): ?array
    {
        $sub = (new static())::select([
            'contact_id',
            'tag',
            new RawColumn('max(is_deleted)', 'is_deleted'),
        ])
            ->where('team_id', $teamId)
            ->where('contact_id', $contactId)
            ->groupBy(['contact_id', 'tag']);

        $query = (new Builder())::select([
            'contact_id',
            new RawColumn('groupArray(tag)', 'tags'),
        ])
            ->from(function($from) use ($sub) {
                $from->query($sub);
            })
            ->where('is_deleted', 0)
            ->groupBy(['contact_id']);

        if ($arr = $query->get()->fetchOne()) {
            return $arr;
        }

        return null;
    }
}
