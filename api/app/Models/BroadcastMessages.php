<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BroadcastMessages extends Model
{
    
    use SoftDeletes;

    protected $table = "broadcast_messages";

    protected $fillable = [
        "type",
        "message",
        "publish_at",
        "expires_at",
        "unpublished_at",
    ];

    protected $visible = [
        "id",
        "type",
        "message"
    ];

    public static $types = [
        "ok",
        "notice",
        "warning",
        "red_alert"
    ];

}
