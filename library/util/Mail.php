<?php
/**
 * Guild - Topic Daily Build System.
 *
 * @link       http://git.intra.weibo.com/huati/daily-build
 * @copyright  Copyright (c) 2009-2016 Weibo Inc. (http://weibo.com)
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt   GPL License
 */

namespace Library\Util;

use Library\Model\MailModel;
use Library\Util\Config;

class Mail
{

	/**
	 * Send.
	 *
	 * @param $params array
	 * @return bool
	 */
	public static function send($to, $cc = "", $subject, $body, $attachment = "")
	{
		$result = true;
		try {
			$mail = new \PHPMailer(true); 
			$mail->IsSMTP(); 
			$mail->CharSet='UTF-8'; 
			$mail->SMTPAuth = true;
			$mail->Port = 25; 
			$mail->Host = Config::get("mail.sender.host");
			$mail->Username = Config::get("mail.sender.username");
			$mail->Password = Config::get("mail.sender.password");
			$mail->From = Config::get("mail.sender.mail_address");
			$mail->FromName = Config::get("mail.sender.name");
			$tos = explode(",", $to);
			foreach ($tos as  $to) {
				$mail->AddAddress($to);
			}
			if ($cc) {
				$ccs = explode(",", $cc);
				foreach ($ccs as $cc) {
					$mail->addCC($cc);
				}
			}
			$mail->Subject = $subject; 
			$mail->Body = $body; 
			$mail->AltBody = "To view the message, please use an HTML compatible email viewer!";
			if ($attachment) {
				$mail->AddAttachment($attachment);
			}
			$mail->WordWrap = 80; 
			$mail->IsHTML(true); 
			$mail->Send(); 
		} catch (Exception $e) {
			$result = false;
			if (Helper::isDebug()) {
				var_dump($e);	
			}
		}
		return $result;
	}
}
