<?php

namespace Dmeys\RequestLogger\Models;

use Illuminate\Database\Eloquent\Model;

class RequestLogFingerprint extends Model
{
    protected $fillable = [
        'fingerprint',
        'repeating',
    ];

    public function getTable(): string
    {
        return config('request-logger.table_name', parent::getTable()) . '_fingerprints';
    }

    public function requestLogs()
    {
        return $this->hasMany(RequestLog::class, 'fingerprint_id');
    }
}
