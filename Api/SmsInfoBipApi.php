<?php
/**
 * @package     Mautic
 * @copyright   2016 Mautic Contributors. All rights reserved.
 * @author      Mautic
 * @link        http://mautic.org
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
namespace MauticPlugin\InfoBipSmsBundle\Api;


use Mautic\CoreBundle\Factory\MauticFactory;
use Mautic\CoreBundle\Helper\PhoneNumberHelper;
use Mautic\PageBundle\Model\TrackableModel;
use MauticPlugin\InfoBipSmsBundle\Api\AbstractSmsApi;

class SmsInfoBipApi extends AbstractSmsApi
{
	private $username;
	private $password;
	
    public function __construct(TrackableModel $pageTrackableModel = null, MauticFactory $factory, PhoneNumberHelper $phoneNumberHelper = null, $sendingPhoneNumber, $username, $password)
    {
        $this->username = $username;
        $this->password = $password;

        parent::__construct($pageTrackableModel);
    }

    public function sendSms($number, $content)
    {
		$number = '+55' . $number;
		
    	$url = "http://api.infobip.com/sms/1/text/single";
		$curl = curl_init();
		
		$headers = [
			'Authorization: Basic '. base64_encode("{$this->username}:{$this->password}"),
			'Content-Type:application/json',
			'Accept: application/json'
		];
		
		$data = [
			'from' => "InfoSMS",
			'to' => $number,
			'text' => $content
		];
		
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
		curl_exec($curl);
		curl_close($curl);

		return true;
    }
}
