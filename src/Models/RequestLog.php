<?php

namespace Dmeys\RequestLogger\Models;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;

class RequestLog extends Model
{
    protected $fillable = [
        'fingerprint_id',
        'user_id',
        'method',
        'url',
        'duration_ms',
        'ip',
        'log_file',
        'date',
        'response_status_code',
        'memory',
    ];

    public $timestamps = false;

    public function getTable()
    {
        return config('request-logger.table_name', parent::getTable());
    }

    public function scopeAdminFiltering($query, Request $request)
    {
        if ($request->filled('url')) {
            $query->where('url', 'like', '%' . $request->input('url') . '%');
        }
        if ($request->filled('exclude_urls')) {
            foreach ($request->input('exclude_urls', []) as $url) {
                $query->where('url', 'not like', '%' . $url . '%');
            }
        }
        if ($request->filled('methods')) {
            $query->whereIn('method', $request->input('methods'));
        }
        if ($request->filled('response_status_code')) {
            $query->where('response_status_code', $request->input('response_status_code'));
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }
        if ($request->filled('fingerprint')) {
            $query->where('fingerprint', $request->input('fingerprint'));
        }
        if ($request->filled('exclude_fingerprints')) {
            $query->whereNotIn('fingerprint', $request->input('exclude_fingerprints', []));
        }
        if ($request->filled('date_from')) {
            $query->where('date', '>=', $request->input('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->where('date', '<=', $request->input('date_to'));
        }
        if ($request->filled('order')) {
            list($order, $dir) = explode('|', $request->input('order', ''));
            $query->orderBy($order, $dir);
        } else {
            $query->orderByDesc('date');
        }

        return $query;
    }

    public function scopeJoinFingerprint($query)
    {
        $request_logs_table = (new RequestLog())->getTable();
        $request_log_fingerprints_table = (new RequestLogFingerprint())->getTable();

        return $query
            ->select([
                "$request_logs_table.*",
                "$request_log_fingerprints_table.fingerprint",
                "$request_log_fingerprints_table.repeating",
            ])
            ->join(
                $request_log_fingerprints_table,
                "$request_log_fingerprints_table.id",
                '=',
                "$request_logs_table.fingerprint_id"
            );
    }

    public function fingerprint(): BelongsTo
    {
        return $this->belongsTo(RequestLogFingerprint::class, 'fingerprint_id');
    }

    public function getUrlDecodedAttribute()
    {
        $parse_url = parse_url(urldecode($this->url));
        $url = $parse_url['path'] ?? '';
        if (isset($parse_url['query'])) {
            $url .= "?{$parse_url['query']}";
        }
        return $url;
    }

    public function getStatusClassAttribute(): string
    {
        $class = 'bg-danger';
        if ($this->response_status_code < 300) {
            $class = 'bg-success';
        } else if ($this->response_status_code >= 300 && $this->response_status_code < 500) {
            $class = 'bg-warning';
        }
        return $class;
    }

    public function getMethodClassAttribute(): string
    {
        $class = '';
        switch (strtoupper($this->method)) {
            case 'GET':
                $class = 'text-success';
                break;
            case 'POST':
                $class = 'text-warning';
                break;
            case 'PUT':
                $class = 'text-primary';
                break;
            case 'DELETE':
                $class = 'text-danger';
                break;
        }

        return $class;
    }

    public function getDateStringAttribute(): string
    {
        return (new DateTime($this->date))->format(config('request-logger.date_format'));
    }

    public function getTimeStringAttribute(): string
    {
        return (new DateTime($this->date))->format(config('request-logger.time_format'));
    }
}
