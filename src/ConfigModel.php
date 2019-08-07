<?php

namespace Encore\Admin\Config;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class ConfigModel extends Model
{
    /**
     * Settings constructor.
     *
     * @param array $attributes
     */
    public function __construct($attributes = [])
    {
        parent::__construct($attributes);

        $this->setConnection(config('admin.database.connection') ?: config('database.default'));

        $this->setTable(config('admin.extensions.config.table', 'admin_config'));
    }

    public function getImageUrlAttribute()
    {
        if (Str::startsWith($this->image, ['http://', 'https://'])) {
            return $this->image;
        }
        return \Storage::disk('public')->url($this->image);
    }
}
