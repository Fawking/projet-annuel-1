<?php
class Stripe {
	public function __construct ($api_key){
		$this->api_key = $api_key;
	}

	public function api(string $endpoint, array $data) : stdClass{

		$ch = curl_init();

		curl_setopt_array($ch, [
			CURLOPT_URL => 'https://api.stripe.com/v1/'.$endpoint,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_USERPWD => $this->api_key,
			CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
			
		]);

		if(!is_null($data)){
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
		}

		/* if using localhost : uncomment this
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		  */




		$response = json_decode(curl_exec($ch));

		if (curl_errno($ch)) { 

			print curl_error($ch); 

		}else{

			if(property_exists($response, 'error')){
				throw new Exception($response->error->message);
			}

		}

		curl_close($ch);


		return $response;

	}
}
?>