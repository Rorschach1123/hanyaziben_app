<?php
	
	header("Content-type:text/html;charset=utf-8");

	// 数据库连接信息
 
	$mysql_server_name1 = '106.2.178.118';

	$mysql_server_name2 = '192.168.1.248';

	$mysql_username1 = 'root';

	$mysql_username2 = 'root';

	$mysql_password1 = 'lzd0921';

	$mysql_password2 = 'hyro%0924';

	$mysql_database1 = 'CompanyWebsite';

	$mysql_database2 = 'hanyaziben';

	$link1 = @mysql_connect($mysql_server_name1,$mysql_username1,$mysql_password1) or die('erro connect');

	// $link2 = @mysql_connect($mysql_server_name2,$mysql_username2,$mysql_password2) or die('erro connect');

	mysql_query("set names 'utf8'");

	mysql_select_db($mysql_database1);


	/*****积分扣除*****/
	
	// 用户id及所扣积分
	// $user_id = 66;
	// $amount = 172;

	/* 首先对注册积分进行扣除 */
	$sql = "select * from `pt_register_points` where `user_id` = '{$user_id}' and `is_effective` != 0";

	$result = mysql_query($sql,$link1);

	while ($row = mysql_fetch_assoc($result)) {
		$register_arr = $row;
	}

	// echo '<pre>';
	// print_r($register_arr);
	
	if (!empty($register_arr)) {

		$id = $register_arr['id'];

		if ($register_arr['rest_points'] <= $amount) {
		$sql = "update `pt_register_points` set `is_effective` = 0 where `id` = $id";
		mysql_query($sql,$link1);
		$amount -= $register_arr['rest_points'];
		}
		else {
			$rest_points = $register_arr['rest_points'] - $amount;
			$sql = "update `pt_register_points` set `rest_points` = $rest_points where `id` = $id";
			mysql_query($sql,$link);
			$amount = 0;
		}
	}
	// echo '注册积分扣除完毕，待扣除积分：'.$amount.'<br>';

	/* 其次扣除签到积分 */
	if ($amount > 0) {

		// 
		$sql = "select * from `pt_sign_points` where `user_id` = '{$user_id}' and `is_effective` != 0 order by `sign_date` asc";
		$result = mysql_query($sql,$link1);
		while ($row = mysql_fetch_assoc($result)) {
			$sign_arr[] = $row;
		}
		// echo "<pre>";
		// print_r($sign_arr);

		if (!empty($sign_arr)) {
			for ($i=0; $i < count($sign_arr); $i++) {
				$id = $sign_arr[$i]['id'];
				if ($sign_arr[$i]['rest_points'] <= $amount) {
					$sql = "update `pt_sign_points` set `is_effective` = 0 where `id` = $id";
					mysql_query($sql,$link1);
					$amount -= $sign_arr[$i]['rest_points'];
				}
				else {
					$rest_points = $sign_arr[$i]['rest_points'] - $amount;
					$sql = "update `pt_sign_points` set `rest_points` = $rest_points where `id` = $id";
					mysql_query($sql,$link1);
					$amount = 0;
				}
			}
		}
	}
// 	echo '签到积分扣除完毕，待扣除积分：'.$amount.'<br>';

	/* 最后扣除产品积分 */

	// 测试
	if ($amount > 0) {
		$sql = "select * from `pt_investment_points` where `user_id` = '{$user_id}' and `pt_investment_points`.`is_effective` != 0 order by `pt_investment_points`.`expiration_date` asc";
		$result = mysql_query($sql,$link1);
		while ($row = mysql_fetch_assoc($result)) {
			$investment_new_arr[] = $row;
		}
		for ($i=0; $i < count($investment_new_arr); $i++) {
			$id = $investment_new_arr[$i]['id'];
			if ($investment_new_arr[$i]['rest_points'] <= $amount) {
				$sql = "update `pt_investment_points` set `pt_investment_points`.`is_effective` = 0 where `pt_investment_points`.`id` = $id";
				mysql_query($sql,$link1);
				$amount -= $investment_new_arr[$i]['rest_points'];
			}
			else {
				$rest_points = $investment_new_arr[$i]['rest_points'] - $amount;
				$sql = "update `pt_investment_points` set `pt_investment_points`.`rest_points` = $rest_points where `pt_investment_points`.`id` = $id";
				mysql_query($sql,$link1);
				$amount = 0;
			}
		}
	}



	/*
	if ($amount > 0) {

		

		// 查询当前用户的产品积分
		
		$sql = "select * from `pt_investment_points` where `user_id` = $user_id";
		$result = mysql_query($sql,$link1);
		$count_caifu = mysql_num_rows($result);

		// echo '瀚亚财富当前用户产品记录：'.$count_caifu.'<br>';

		// 查询当前用户核心库中的产品明细
		
		$sql = "select * from `cw_user` where `id` = $user_id";
		$result = mysql_query($sql,$link1);
		while ($row = mysql_fetch_assoc($result)) {
			$user_arr = $row;
		}
		$id_number = $user_arr['idnumber'];

		mysql_close($link1);

		$link2 = @mysql_connect($mysql_server_name2,$mysql_username2,$mysql_password2) or die('erro connect');

		mysql_select_db($mysql_database2);

		$sql = "select * from `hy_information` where `id_number` = '{$id_number}'";
		$result = mysql_query($sql,$link2);
		$count_ziben = mysql_num_rows($result);

		// echo '瀚亚资本当前用户产品记录：'.$count_ziben.'<br>';
		
		// exit();

		// 判断两个数据库的数据条数是否一致，不一致则向数据库添加数据
		if ($count_ziben === $count_caifu) {
			// echo "记录已存在，无需新增<br>";
		}
		else {
			// 如果不一致，确定出哪一条需要新增

			$sql = "select * from `hy_information` where `id_number` = '{$id_number}'";
			$result = mysql_query($sql,$link2);
			$info_arr = array();
			while ($row = mysql_fetch_assoc($result)) {
				$info_arr[] = $row;
			}

			$contract_ziben = array();
			for ($i=0; $i < count($info_arr); $i++) {
				$contract_ziben[] = $info_arr[$i]['contract_number'];
			}

			// echo '<pre>';
			// print_r($contract_ziben);

			mysql_close($link2);
			$link1 = @mysql_connect($mysql_server_name1,$mysql_username1,$mysql_password1) or die('erro connect');
			mysql_select_db($mysql_database1);

			$sql="select * from `pt_investment_points` where `user_id` = $user_id";
			$result = mysql_query($sql,$link1);
			$inv_arr = array();
			while ($row = mysql_fetch_assoc($result)) {
				$inv_arr[] = $row; 
			}
			$contract_caifu = array();
			for ($i=0; $i < count($inv_arr); $i++) {
				$contract_caifu[] = $inv_arr[$i]['contract_number'];
			}

			// echo "<pre>";
			// print_r($contract_caifu);

			$contract_diff = array_diff($contract_ziben,$contract_caifu);
			
			// echo "<pre>";
			// print_r($contract_diff);

			// 开始添加新的数据
			if (!empty($contract_diff)) {
				for ($i=0; $i < count($contract_diff); $i++) {
					$contract_number = $contract_diff[$i];
					// echo $contract_number;

					mysql_close($link1);

					$link2 = @mysql_connect($mysql_server_name2,$mysql_username2,$mysql_password2) or die('erro connect');

					mysql_select_db($mysql_database2);

					$sql = "select * from `hy_information` where `contract_number` = '$contract_number'";
					$result = mysql_query($sql,$link);
					while ($row = mysql_fetch_assoc($result)) {
						$investment_arr = $row;
					}
					
					// echo "<pre>";
					// print_r($investment_arr);

					$investment_contract_number = $investment_arr['contract_number'];
					$investment_points = intval(floor($investment_arr['investment_amount']/100));
					$investment_expiration_date = $investment_arr['expiration_date'];
					$investment_update_date = date('Y-m-d',time());

					mysql_close($link2);
					$link1 = @mysql_connect($mysql_server_name1,$mysql_username1,$mysql_password1) or die('erro connect');
					mysql_select_db($mysql_database1);

					$sql = "insert into `pt_investment_points` (`user_id`,`points`,`rest_points`,`contract_number`,`expiration_date`,`update_date`) values ({$user_id},{$investment_points},{$investment_points},'{$investment_contract_number}','{$investment_expiration_date}','{$investment_update_date}')";
					
					mysql_query($sql,$link1);
					// var_dump($investment_contract_number);
					// var_dump($investment_points);
					// var_dump($investment_expiration_date);
					// var_dump($investment_update_date);
				}
			}
		}
		// 数据同步完成，开始扣除积分
		
		// mysql_close($link2);
		// $link1 = @mysql_connect($mysql_server_name1,$mysql_username1,$mysql_password1) or die('erro connect');
		// mysql_select_db($mysql_database1);
		$sql = "select * from `pt_investment_points` where `user_id` = $user_id and `pt_investment_points`.`is_effective` != 0 order by `pt_investment_points`.`expiration_date` asc";
		$result = mysql_query($sql,$link1);
		while ($row = mysql_fetch_assoc($result)) {
			$investment_new_arr[] = $row;
		}
		for ($i=0; $i < count($investment_new_arr); $i++) {
			$id = $investment_new_arr[$i]['id'];
			if ($investment_new_arr[$i]['rest_points'] <= $amount) {
				$sql = "update `pt_investment_points` set `pt_investment_points`.`is_effective` = 0 where `pt_investment_points`.`id` = $id";
				mysql_query($sql,$link1);
				$amount -= $investment_new_arr[$i]['rest_points'];
			}
			else {
				$rest_points = $investment_new_arr[$i]['rest_points'] - $amount;
				$sql = "update `pt_investment_points` set `pt_investment_points`.`rest_points` = $rest_points where `pt_investment_points`.`id` = $id";
				mysql_query($sql,$link1);
				$amount = 0;
			}
		}
	}

	*/

	// echo "积分扣除完毕";

	/******积分扣除完毕******/
	


?>