<?php
ob_start();

	/**
	* 
	*/
	class MailController extends Controller
	{
		public function EnviaEmail($para, $assunto, $mensagem)
		{
		
				error_reporting(E_ALL);
				ini_set("display_errors", 1 );
						 

			// Inicia a classe PHPMailer
			require_once 'PHPMailer-master/PHPMailerAutoload.php';

			$mail = new PHPMailer;

			$mail->SMTPDebug = 3;                               // Enable verbose debug output

			$mail->isSMTP();                                      // Set mailer to use SMTP
			$mail->Host = 'smtp.fsa.br';  // Specify main and backup SMTP servers
			$mail->SMTPAuth = true;                               // Enable SMTP authentication
			$mail->Username = 'email@dominio.com.br';                 // SMTP username
			$mail->Password = 'senha';                           // SMTP password
			$mail->SMTPSecure = '';                            // Enable TLS encryption, `ssl` also accepted
			$mail->Port = 25;                                    // TCP port to connect to

			$mail->From = 'emaildeorigem@dominio.com.br';
			$mail->FromName = 'Remetente';
			$mail->addAddress($para, $para);     // Add a recipient
			//$mail->addAddress('ellen@example.com');               // Name is optional
			$mail->addReplyTo('emaildecopia@dominio.com.br', 'emaildecopia2@dominio.com.br');
			//$mail->addCC('cc@example.com');
			//$mail->addBCC('bcc@example.com');

			//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
			//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
			$mail->isHTML(true);                                  // Set email format to HTML

			$mail->Subject = $assunto;
			$mail->Body    = (utf8_decode($mensagem));
			$mail->AltBody = (utf8_decode($mensagem));

			// Envia o e-mail
			$enviado = $mail->Send();
			// Limpa os destinatários e os anexos
			$mail->ClearAllRecipients();
			$mail->ClearAttachments();
			// Exibe uma mensagem de resultado
	
			if ($enviado) {
				return true;
			} else {
				echo "Não foi possível enviar o e-mail.<br /><br />";
				echo "<b>Informações do erro:</b> <br />" . $mail->ErrorInfo;
				die();
			}
					
		}
	}