<?php

include("NodeFlags.php");
include("NodeStatistics.php");
include("NodeInfo.php");

class Node
{

    private $firstseen;
    private $lastseen;
    private $flag;
    private $statistics;
    private $nodeinfo;

    /**
     * Node constructor.
     * @param $firstseen
     * @param $lastseen
     * @param $flag
     * @param $statistics
     * @param $nodeinfo
     */
    public function __construct($firstseen, $lastseen, $flag, $statistics, $nodeinfo)
    {
        $this->firstseen = $firstseen;
        $this->lastseen = $lastseen;
        $this->flag = $flag;
        $this->statistics = $statistics;
        $this->nodeinfo = $nodeinfo;

        $this->fillRRDData();

        $this->makeGraph();
    }

    private function fillRRDData(){
        if(!$this->checkRRDFileExists()){
            echo "create";
            echo "<br><br>";
            $this->createRRDFile();
            echo "<br><br>";
        }
        //TODO: Memory Usage
        //TODO: clients
        //TODO: rootfs_usage
        //TODO: loadavg
        //TODO: traffic
    }

    private function checkRRDFileExists(){
        return file_exists($this->getRRDFileName());
    }

    private function createRRDFile(){
        $options = array(
            "--step", "60",            // Use a step-size of 5 minutes
            "--start", "-6 months",     // this rrd started 6 months ago
            "DS:memoryUsage:ABSOLUTE:600:0:U",
            "DS:clients:ABSOLUTE:600:0:U",
            "DS:rootfsUsage:ABSOLUTE:600:0:U",
            "DS:loadavg:ABSOLUTE:600:0:U",
            "DS:trafficRx:ABSOLUTE:600:0:U",
            "DS:trafficMgmtRx:ABSOLUTE:600:0:U",
            "DS:trafficTx:ABSOLUTE:600:0:U",
            "DS:trafficMgmtTx:ABSOLUTE:600:0:U",
            "DS:trafficForwarded:ABSOLUTE:600:0:U",
            "RRA:AVERAGE:0.5:1:288",
            "RRA:AVERAGE:0.5:12:168",
            "RRA:AVERAGE:0.5:228:365",
        );
        echo $this->getRRDFileName();
        $ret = rrd_create($this->getRRDFileName(), $options);
        if($ret){
            echo "error: <br>";
            print_r(rrd_error());
        }

    }

    public function getRRDFileName(){
        return "rrdData/nodes/".$this->nodeinfo->getNodeId().".rrd";
    }

    private function makeGraph(){

    }

    /**
     * @return mixed
     */
    public function getFirstseen()
    {
        return $this->firstseen;
    }

    /**
     * @param mixed $firstseen
     */
    public function setFirstseen($firstseen)
    {
        $this->firstseen = $firstseen;
    }

    /**
     * @return mixed
     */
    public function getLastseen()
    {
        return $this->lastseen;
    }

    /**
     * @param mixed $lastseen
     */
    public function setLastseen($lastseen)
    {
        $this->lastseen = $lastseen;
    }

    /**
     * @return mixed
     */
    public function getFlag()
    {
        return $this->flag;
    }

    /**
     * @param mixed $flag
     */
    public function setFlag($flag)
    {
        $this->flag = $flag;
    }

    /**
     * @return mixed
     */
    public function getStatistics()
    {
        return $this->statistics;
    }

    /**
     * @param mixed $statistics
     */
    public function setStatistics($statistics)
    {
        $this->statistics = $statistics;
    }

    /**
     * @return mixed
     */
    public function getNodeinfo()
    {
        return $this->nodeinfo;
    }

    /**
     * @param mixed $nodeinfo
     */
    public function setNodeinfo($nodeinfo)
    {
        $this->nodeinfo = $nodeinfo;
    }


}