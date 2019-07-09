<?php

namespace api\modules\order\controllers;

class TransactionController extends Order
{
    public function actionList()
    {
    	$data = [
    		'total' =>20,
    		'items' =>[
    			[
	    			'order_no' => 'fsafa',
	    			'timestamp' => time(),
	    			'username' => 'avc',
	    			'price' => '10',
            		'status'=> 'success'
	    		],
	    		[
	    			'order_no' => 'hhghr',
	    			'timestamp' => time(),
	    			'username' => 'bbb',
	    			'price' => '10',
            		'status'=> 'pending'
	    		]
    		]
    	];
        return $this->success($data);
    }

}
