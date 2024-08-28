<?php

namespace Dmeys\RequestLogger\Writers;

use Dmeys\RequestLogger\LogInfo;

class LogWriter implements Writer
{
    /** @var DbWriter $db_writer */
    protected $db_writer;

    /** @var FileWriter $file_writer */
    protected $file_writer;

    /**
     * @param DbWriter $db_writer
     * @param FileWriter $file_writer
     */
    public function __construct(DbWriter $db_writer, FileWriter $file_writer)
    {
        $this->db_writer = $db_writer;
        $this->file_writer = $file_writer;
    }

    /**
     * @param LogInfo $logInfo
     */
    public function write(LogInfo $logInfo)
    {
        $this->db_writer->write($logInfo);
        $this->file_writer->write($logInfo);
    }
}
