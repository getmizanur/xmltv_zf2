<?php
/**
 * Mizanur Rahman
 *
 * @link      https://www.linkedin.com/pub/mizanur-rahman/32/b15/248
 * @copyright N/A
 */

namespace Mm\Util;

class DbUtil
{
    protected $dsn;
    protected $username;
    protected $password;

    public function __construct($adapter) 
    {
        $adapterArr = $adapter->getDriver()
            ->getConnection()
            ->getConnectionParameters();

        $this->dsn = $adapterArr['dsn'];
        $this->username = $adapterArr['username'];
        $this->password = $adapterArr['password'];    
    }

    public function getHandler()
    {
        $handler = null;
        try {
            $handler =
                new \PDO($this->dsn, $this->username, $this->password,
                    array(\PDO::ATTR_PERSISTENT => true));
            $handler->setAttribute(\PDO::ATTR_ERRMODE,
                \PDO::ERRMODE_EXCEPTION);
        }catch(\PDOException $e) {
            trigger_error($e->getMessage(), E_USER_ERROR);
        }

        return $handler;
    }

    public function getAll($sqlQuery, $params = null,
        $fetchStyle = \PDO::FETCH_ASSOC)
    {
        $result = null;
        try {
            $databaseHandler = self::etHandler();

            $statementHandler = $databaseHandler->prepare($sqlQuery);
            $statementHandler->execute($params);

            $result = $statementHandler->fetchAll($fetchStyle);
        }catch(PDOException $e){
            trigger_error($e->getMessage(), E_USER_ERROR);
        }

        return $result;
    }

    public function getRow($sqlQuery, $params = null,
        $fetchStyle = \PDO::FETCH_ASSOC)
    {
        $result = null;
        try {
            $databaseHandler = self::getHandler();

            $statementHandler = $databaseHandler->prepare($sqlQuery);
            $statementHandler->execute($params);

            $result = $statementHandler->fetch($fetchStyle);
        }catch(PDOException $e){
            trigger_error($e->getMessage(), E_USER_ERROR);
        }

        return $result;
    }

    public function GetOne($sqlQuery, $params = null)
    {
        $result = null;
        try {
            $databaseHandler = self::getHandler();

            $statementHandler = $databaseHandler->prepare($sqlQuery);
            $statementHandler->execute($params);

            $result = $statementHandler->fetch(PDO::FETCH_NUM);
            $result = $result[0];
        }catch(PDOException $e){
            trigger_error($e->getMessage(), E_USER_ERROR);
        }

        return $result;
    }

    public function Execute($sqlQuery, $params = null)
    {
        try {
            $databaseHandler = self::getHandler();

            $statementHandler = $databaseHandler->prepare($sqlQuery);
            $statementHandler->execute($params);
        }catch(PDOException $e) {
            //trigger_error($e->getMessage(), E_USER_ERROR);
            return false;
        }

        return true;
    }

    public static function showStatus($done, $total, $message, $size = 30) 
    {
        static $start_time;

        if($done > $total) return;

        if(empty($start_time)) $start_time=time();
        $now = time();

        $perc=(double)($done/$total);

        $bar=floor($perc*$size);

        $status_bar="\r[";
        $status_bar.=str_repeat("=", $bar);
        if($bar<$size){
            $status_bar.=">";
            $status_bar.=str_repeat(" ", $size-$bar);
        } else {
            $status_bar.="=";
        }

        $disp=number_format($perc*100, 0);

        $status_bar.="] $disp%  $done/$total";

        //$rate = ($now-$start_time)/$done;
        //$left = $total - $done;
        //$eta = round($rate * $left, 2);

        $elapsed = $now - $start_time;

        $status_bar.= $message;

        echo "$status_bar  ";

        flush();

        if($done == $total) {
            echo "\n";
        }
    }

}
