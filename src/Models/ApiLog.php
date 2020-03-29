<?php

namespace Kielabokkie\Apise\Models;

use Illuminate\Database\Eloquent\Model;

class ApiLog extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'apise_logs';

    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = null;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
}
