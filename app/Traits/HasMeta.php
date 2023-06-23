<?php

namespace App\Traits;

trait HasMeta
{

    public function addMetaArray($array)
    {
        $meta = $this->getMeta();
        $meta = array_merge($meta, $array);
        $this->meta = json_encode($meta);
        $this->save();
    }

    public function getMeta()
    {
        $meta = json_decode($this->meta, true);

        return $meta ? $meta : [];
    }

    public function setMeta($key, $value)
    {
        $this->addMeta($key, $value);
    }

    public function addMeta($key, $value)
    {
        $meta = $this->getMeta();
        $meta[$key] = $value;
        $this->meta = json_encode($meta);
        $this->save();
    }

    public function getMetaByKey($key)
    {
        $meta = $this->getMeta();
        return $meta[$key] ?? null;
    }
}