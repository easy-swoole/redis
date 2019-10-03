<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class ZRange extends AbstractCommandHandel
{
	public $commandName = 'ZRange';


	public function getCommand(...$data)
	{
		$key=array_shift($data);
		$start=array_shift($data);
		$stop=array_shift($data);
		$withScores=array_shift($data);


		        
		        if ($withScores == true) {
		            $data[] = 'WITHSCORES';
		        }

		$command = [CommandConst::ZRANGE,$key,$start,$stop,$withScores];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function getData(Response $recv)
	{
		$data = $recv->getData();
		        if ($withScores == true) {
		            $result = [];
		            foreach ($data as $k => $va) {
		                if ($k % 2 == 0) {
		                    $result[$this->unSerialize($va)] = 0;
		                } else {
		                    $result[$this->unSerialize($data[$k - 1])] = $va;
		                }
		            }
		        } else {
		            $result = [];
		            foreach ($data as $k => $va) {
		                $result[$k] = $this->unSerialize($va);
		            }
		        }

		        return $result;
	}
}
