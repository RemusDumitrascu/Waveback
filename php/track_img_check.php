<?php
	function check_type($extensions, $type){
		for ($i = 0; $i< count($extensions); $i++) 
			if($type == $extensions[$i])
				return 1;
		return 0;
	}
	if(isset($_FILES['img']['name'])){
		$extensions = array('jpg', 'png', 'gif', 'jpeg' );
		$check = getimagesize($_FILES['img']['tmp_name']);
		$type = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
			if(check_type($extensions, $type) && $_FILES['img']['size'] <= 2100000 && $check['mime'])
				echo 'ok';
			else
				echo 'no';
	}

?>