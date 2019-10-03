<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class SRandMemBer extends AbstractCommandHandel
{
	public $commandName = 'SRandMemBer';


	public function getCommand(...$data)
	{
		$key=array_shift($data);
		$count=array_shift($data);


		        
		        if ($count !== null) {
		            $data[] = $count;
		        }

		$command = [CommandConst::SRANDMEMBER,$key,$count];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function getData(Response $recv)
	{
		$data = $recv->getData();
		        foreach ($data as $key => $value) {
		            $data[$key] = $this->unSerialize($value);
		        }
		        return $data;
	}
}
