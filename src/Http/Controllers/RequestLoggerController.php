<?php

namespace Dmeys\RequestLogger\Http\Controllers;

use Dmeys\RequestLogger\Services\ClearLogs;
use Dmeys\RequestLogger\Support\BaseController;
use Dmeys\RequestLogger\Models\RequestLog;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RequestLoggerController extends BaseController
{
	/**
	 * @param Request $request
	 * @return Application|Factory|View
	 */
	public function index(Request $request)
	{
		$request->merge([
			'exclude_urls' => array_filter($request->input('exclude_urls', [])),
			'exclude_fingerprints' => array_filter($request->input('exclude_fingerprints', [])),
		]);

		$logs = RequestLog::query()
			->joinFingerprint()
			->adminFiltering($request)
			->paginate(50);

		return view('request-logs::index', [
			'logs' => $logs,
			'total' => $logs->total(),
			'request' => $request,
		]);
	}

	/**
	 * @param int $id
	 * @return StreamedResponse
	 */
	public function download(int $id)
	{
		$request_log = RequestLog::query()->findOrFail($id);
		return Storage::download($request_log->log_file);
	}

	/**
	 * @param int $id
	 * @return string
	 */
	public function show(int $id)
	{
		$request_log = RequestLog::query()->findOrFail($id);
		return Storage::get($request_log->log_file);
	}

	/**
	 * @param ClearLogs $clear_logs
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function clearAllLogs(ClearLogs $clear_logs)
	{
		$clear_logs->all();
		return redirect()->route('request-logs.index');
	}
}
