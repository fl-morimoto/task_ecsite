<?php
namespace App\Lib;

class CSV
{
	public function __construct()
	{
	}
	/*
	 * CSVダウンロード
	 * @param array $list
	 * @param array $header
	 * @param string $filename
	 * @return \Illuminate\Http\Response
	 */
	public function download($rows, $header, $filename)
	{
		if (0 < count($header)) {
			array_unshift($rows, $header);
		}
		$stream = fopen('php://temp', 'r+b');
		foreach ($rows as $row) {
			fputcsv($stream, $row);
		}
		rewind($stream);
		$csv = str_replace(PHP_EOL, "\r\n", stream_get_contents($stream));
		$csv = mb_convert_encoding($csv, 'SJIS-win', 'UTF-8');
		fclose($stream);
		return response($csv)
			->header('Content-Type', 'text/csv')
			->header('Content-Disposition', "attachment;filename=$filename");
	}
}


