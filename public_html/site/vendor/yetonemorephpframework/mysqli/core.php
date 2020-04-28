<?php
$sql_debug = false;
abstract class mysqli_core extends db
{
	protected $sqlConnection = null;
	protected $hadErrors = false;
	protected $needToSave = false;
	public $lastSql;
	protected $track_table_select_access = false;
	protected $track_select_from_tables = array();
	protected $last_error = "No error logged";

	function __destruct()
	{
		if($this->sqlConnection != null)
		{
			if(($this->hadErrors == false) && ($this->needToSave == true)) $this->sqlConnection->commit();
			else $this->sqlConnection->rollback();
			$this->sqlStop();
		}
	}
	public function get_last_sql()
	{
		return $this->lastSql;
	}
    protected function sqlStart() : bool
    {
        return false;
    }
    protected function sqlStop() : bool
    {
        return false;
    }

}
?>
