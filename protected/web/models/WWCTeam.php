<?php

class WWCTeam extends WCTeam
{
	/**
	 * @return static[]
	 */
	public static function getAllTeam()
	{
		$models = array();
		foreach (self::$list_countries as $key => $data) {
			$model = new WWCTeam();
			$model->code = $key;
			$model->name = $data['name'];
			$model->flag = $data['flag'];
			$models[] = $model;
		}
		return $models;
	}

	public static function getTeam($code)
	{
		$model = null;
		$data = self::getAllTeam();
		foreach ($data as $item){
			if($item->code == $code){
				$model = $item;
				break;
			}
		}
		return $model;
	}

	public static function getTeamName($code){
		$name = null;
		$data = self::getAllTeam();
		foreach ($data as $item){
			if($item->code == $code){
				$name = $item->name;
				break;
			}
		}
		return $name;
	}

}
