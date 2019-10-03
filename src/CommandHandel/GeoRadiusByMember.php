<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class GeoRadiusByMember extends AbstractCommandHandel
{
	public $commandName = 'GeoRadiusByMember';


	public function getCommand(...$data)
	{
		$key=array_shift($data);
		$location=array_shift($data);
		$radius=array_shift($data);
		$unit=array_shift($data);
		$withCoord=array_shift($data);
		$withDist=array_shift($data);
		$withHash=array_shift($data);
		$count=array_shift($data);
		$sort=array_shift($data);
		$storeKey=array_shift($data);
		$storeDistKey=array_shift($data);


		        
		        if ($withCoord === true) {
		            $data[] = 'WITHCOORD';
		        }
		        if ($withDist === true) {
		            $data[] = 'WITHDIST';
		        }
		        if ($withHash === true) {
		            $data[] = 'WITHHASH';
		        }
		        if ($count !== null) {
		            $data[] = $count;
		        }
		        if ($sort !== null) {
		            $data[] = $sort;
		        }
		        if ($storeKey !== null) {
		            $data[] = $storeKey;
		        }
		        if ($storeDistKey !== null) {
		            $data[] = $storeDistKey;
		        }


		$command = [CommandConst::GEORADIUSBYMEMBER,$key,$location,$radius,$unit,$withCoord,$withDist,$withHash,$count,$sort,$storeKey,$storeDistKey];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function getData(Response $recv)
	{
		return $recv->getData();
	}
}
