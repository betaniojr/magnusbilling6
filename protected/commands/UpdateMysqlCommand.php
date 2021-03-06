
<?php
/**
 * =======================================
 * ###################################
 * MagnusBilling
 *
 * @package MagnusBilling
 * @author Adilson Leffa Magnus.
 * @copyright Copyright (C) 2005 - 2016 MagnusBilling. All rights reserved.
 * ###################################
 *
 * This software is released under the terms of the GNU Lesser General Public License v2.1
 * A copy of which is available from http://www.gnu.org/copyleft/lesser.html
 *
 * Please submit bug reports, patches, etc to https://github.com/magnusbilling/mbilling/issues
 * =======================================
 * Magnusbilling.com <info@magnusbilling.com>
 *
 */
class UpdateMysqlCommand extends ConsoleCommand
{

    public function run($args)
    {

        $version  = $this->config['global']['version'];
        $language = $this->config['global']['base_language'];

        echo $version;

        if ($version == '5.3.4') {

            $sql = "ALTER TABLE  `pkg_did` ADD  `send_to_callback_1` TINYINT( 1 ) NOT NULL DEFAULT  '0',
			ADD  `send_to_callback_2` TINYINT( 1 ) NOT NULL DEFAULT  '0',
			ADD  `send_to_callback_3` TINYINT( 1 ) NOT NULL DEFAULT  '0'";
            $this->executeDB($sql);
            $version = '5.3.5';
            $sql     = "UPDATE pkg_configuration SET config_value = '" . $version . "' WHERE config_key = 'version' ";
            Yii::app()->db->createCommand($sql)->execute();
        }

        if ($version == '5.3.5') {

            $sql = "ALTER TABLE  `pkg_sip` ADD  `ringfalse` TINYINT( 1 ) NOT NULL DEFAULT  '0'";
            $this->executeDB($sql);
            $version = '5.3.6';
            $sql     = "UPDATE pkg_configuration SET config_value = '" . $version . "' WHERE config_key = 'version' ";
            Yii::app()->db->createCommand($sql)->execute();
        }

        if ($version == '5.3.6') {

            $sql = "ALTER TABLE  `pkg_user`
			ADD  `state_number` VARCHAR( 40 ) DEFAULT NULL AFTER  `company_website` ,

			ADD  `disk_space` INT( 10 ) NOT NULL DEFAULT  '-1',
			ADD  `sipaccountlimit` INT( 10 ) NOT NULL DEFAULT  '-1',
			ADD  `calllimit` INT( 10 ) NOT NULL DEFAULT  '-1',
			ADD mix_monitor_format VARCHAR(5) DEFAULT 'gsm';
			ALTER TABLE  `pkg_sip` ADD  `record_call` TINYINT( 1 ) NOT NULL DEFAULT  '0';
			";
            $this->executeDB($sql);

            $sql = 'INSERT INTO pkg_templatemail VALUES

				(NULL, 1, \'services_unpaid\', \'usuario\', \'VoIP\', \'Aviso de Vencimento de serviço\', \'<p>Olá $firstname$ $lastname$, </p>\r\n<p>Você tem serviços com vencimento em aberto e não possiu saldo para o pagamento. Por favor entre no link $service_pending_url$ para iniciar o pagamento. </p>\r\n<br> \r\n<p>Atenciosamente,<br>\r\n \', \'br\'),
				(NULL, 1, \'services_unpaid\', \'usuario\', \'VoIP\', \'Aviso de Vencimiento de servicio\', \'<p>Hola $firstname$ $lastname$, </p>\r\n<p>Usted tien servicios por vencer o vencido. Por favor entre en este link $service_pending_url$ para iniciar el pago.</p> \r\n<p>Saludos,<br>\r\n \', \'es\'),
				(NULL, 1, \'services_unpaid\', \'username\', \'VoIP\', \'Balance Due Alert for your\', \'<p>Hello $firstname$ $lastname$, </p>\r\n<p>You have services pendent. Please use this link $service_pending_url$ to start the payment</p>\r\n\r\n<br> \r\n<p>Best Regards<br>\r\n \', \'en\'),

				(NULL, 1, \'services_activation\', \'usuario\', \'VoIP\', \'Ativação de serviço\', \'<p>Olá $firstname$ $lastname$, </p>\r\n<p>Foi ativado o serviço $service_name$ com valor de $service_price$. </p>\r\n<br>\r\n\r\n<p>Este valor sera descontado do credito de sua conta automaticamente todos os meses.</p>\r\n\r\n<br> \r\n<p>Atenciosamente,<br>\r\n \', \'br\'),
				(NULL, 1, \'services_activation\', \'usuario\', \'VoIP\', \'Activacion de servicio\', \'<p>Hola $firstname$ $lastname$, </p>\r\n<p>Fue activado el servicio $service_name$ con importe $service_price$.</p>\r\n<br>\r\n\r\n<p>Este importe sera descontado del credito de su cuenta automaticamente todos los meses..</p>\r\n\r\n<br> \r\n<p>Saludos,<br>\r\n \', \'es\'),
				(NULL, 1, \'services_activation\', \'username\', \'VoIP\', \'Service activation\', \'<p>Hello $firstname$ $lastname$, </p>\r\n<p>The service $service_name$ was activated. Service price: $service_price$ .</p>\r\n<br>\r\n\r\n<p>This amount will be charged of your account every month.</p>\r\n\r\n<br> \r\n<p>Best Regards<br>\r\n \', \'en\'),

				(NULL, 1, \'services_pending\', \'usuario\', \'VoIP\', \'Serviço pendente de pagamento\', \'<p>Olá $firstname$ $lastname$, </p>\r\n<p>Foi solicitado o serviço $service_name$ com valor de $service_price$. </p>\r\n
				<p>A ativaçao do serviço esta pendente de pagamento.</p>\r\n
				<p>Link para pagamento $service_pending_url$.</p>\r\n
				<br>\r\n\r\n<p></p>\r\n\r\n<br> \r\n<p>Atenciosamente,<br>\r\n \', \'br\'),
				(NULL, 1, \'services_pending\', \'usuario\', \'VoIP\', \'Servicio pendente de pagao\', \'<p>Hola $firstname$ $lastname$, </p>\r\n<p>Fue solicitado la activacion del servicio $service_name$ con importe $service_price$.</p>\r\n<p>La activacion del servicio esta pendiente de pago.</p>\r\n
				<p>Link para el pago: $service_pending_url$.</p>\r\n<br>\r\n\r\n<p>.</p>\r\n\r\n<br> \r\n<p>Saludos,<br>\r\n \', \'es\'),
				(NULL, 1, \'services_pending\', \'username\', \'VoIP\', \'Service pending\', \'<p>Hello $firstname$ $lastname$, </p>\r\n<p>The service $service_name$ was pending. Service price: $service_price$ .</p>\r\n
				<p>Please make the payment to active the service.</p>\r\n
				<p>Payment Link:  $service_pending_url$.</p>\r\n
				<br>\r\n\r\n<br> \r\n<p>Best Regards<br>\r\n \', \'en\'),

				(NULL, 1, \'services_released\', \'usuario\', \'VoIP\', \'Cancelamento de serviço\', \'<p>Olá $firstname$ $lastname$, </p>\r\n<p>Foi desativado o serviço $service_name$ com valor de $service_price$. </p>\r\n<br>\r\n\r\n<p></p>\r\n\r\n<br> \r\n<p>Atenciosamente,<br>\r\n \', \'br\'),
				(NULL, 1, \'services_released\', \'usuario\', \'VoIP\', \'Baja de servicio\', \'<p>Hola $firstname$ $lastname$, </p>\r\n<p>Fue dado de baja el servicio $service_name$ con importe $service_price$.</p>\r\n<br>\r\n\r\n<p>.</p>\r\n\r\n<br> \r\n<p>Saludos,<br>\r\n \', \'es\'),
				(NULL, 1, \'services_released\', \'username\', \'VoIP\', \'Service canceled\', \'<p>Hello $firstname$ $lastname$, </p>\r\n<p>The service $service_name$ was canceled. Service price: $service_price$ .</p>\r\n<br>\r\n\r\n<br> \r\n<p>Best Regards<br>\r\n \', \'en\'),

				(NULL, 1, \'services_paid\', \'usuario\', \'VoIP\', \'Serviço Pago\', \'<p>Olá $firstname$ $lastname$, </p>\r\n<p>Foi pago o serviço $service_name$ com valor de $service_price$. </p>\r\n<br>\r\n\r\n<p></p>\r\n\r\n<br> \r\n<p>Atenciosamente,<br>\r\n \', \'br\'),
				(NULL, 1, \'services_paid\', \'usuario\', \'VoIP\', \'Servicio pago\', \'<p>Hola $firstname$ $lastname$, </p>\r\n<p>Fue pago el servicio $service_name$ con importe $service_price$.</p>\r\n<br>\r\n\r\n<p>.</p>\r\n\r\n<br> \r\n<p>Saludos,<br>\r\n \', \'es\'),
				(NULL, 1, \'services_paid\', \'username\', \'VoIP\', \'Service paid\', \'<p>Hello $firstname$ $lastname$, </p>\r\n<p>The service $service_name$ was paid. Service price: $service_price$ .</p>\r\n<br>\r\n\r\n<br> \r\n<p>Best Regards<br>\r\n \', \'en\'),


				(NULL, 1, \'user_disk_space\', \'usuario\', \'VoIP\', \'Armazenamento em disco superado\', \'<p>Olá $firstname$ $lastname$, </p>\r\n<p>Sua conta VoIP número $cardnumber$ superou o limite de $disk_usage_limit$ GB.</p>\r\n<br>\r\n\r\n<p>Para manter o serviço foi deletado automaticamente os audios anteriores a $time_deleted$.</p>\r\n\r\n<br> \r\n<p>Atenciosamente,<br>\r\n \', \'br\'),
				(NULL, 1, \'user_disk_space\', \'usuario\', \'VoIP\', \'Armazenamento en disco superado\', \'<p>Hola $firstname$ $lastname$, </p>\r\n<p>Su cuenta VoIP número $cardnumber$ supero el limite de $disk_usage_limit$ GB.</p>\r\n<br>\r\n\r\n<p>Para mantener el servicio fue borrado automaticamente los audios anteriores a $time_deleted$.</p>\r\n\r\n<br> \r\n<p>Saludos,<br>\r\n \', \'es\'),
				(NULL, 1, \'user_disk_space\', \'username\', \'VoIP\', \'Disk space surpassed\', \'<p>Hello $firstname$ $lastname$, </p>\r\n<p>Your account $cardnumber$ surpassed the disk space limit of $disk_usage_limit$ GB.</p>\r\n<br>\r\n\r\n<p>To keep the service was deleted the records before than $time_deleted$.</p>\r\n\r\n<br> \r\n<p>Best Regards<br>\r\n \', \'en\');';
            $this->executeDB($sql);

            $sql = "
			ALTER TABLE  `pkg_campaign` ADD  `id_plan` INT( 11 ) NULL DEFAULT NULL AFTER  `id_user`;
			ALTER TABLE `pkg_campaign`  ADD CONSTRAINT `fk_pkg_plan_pkg_campaign` FOREIGN KEY (`id_plan`) REFERENCES `pkg_plan` (`id`);
			INSERT INTO pkg_configuration VALUES
				(NULL, 'Link to signup terms', 'accept_terms_link', '', 'Set a link to signup terms', 'global', '1'),
				(NULL, 'Auto gernerate user in Signup form', 'auto_generate_user_signup', '1', 'Auto gernerate user in Signup form', 'global', '1'),
				(NULL, 'Notificação de  Pagamento de serviços', 'service_daytopay', '5', 'Total Dias anterior ao vencimento que o MagnusBilling avisara o cliente para pagar os serviços', 'global', '1');
				";
            $this->executeDB($sql);

            $sql = "CREATE TABLE IF NOT EXISTS `pkg_estados` (
					  `id` int(11) NOT NULL,
					  `nome` varchar(45) NOT NULL,
					  `sigla` varchar(2) NOT NULL,
					  PRIMARY KEY (`id`,`sigla`),
					  UNIQUE KEY `sigla_UNIQUE` (`sigla`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8;

					INSERT INTO `pkg_estados` (`id`, `nome`, `sigla`) VALUES
					(1, 'Acre', 'AC'),
					(2, 'Alagoas', 'AL'),
					(3, 'Amazonas', 'AM'),
					(4, 'Amapá', 'AP'),
					(5, 'Bahia', 'BA'),
					(6, 'Ceará', 'CE'),
					(7, 'Distrito Federal', 'DF'),
					(8, 'Espírito Santo', 'ES'),
					(9, 'Goiás', 'GO'),
					(10, 'Maranhão', 'MA'),
					(11, 'Minas Gerais', 'MG'),
					(12, 'Mato Grosso do Sul', 'MS'),
					(13, 'Mato Grosso', 'MT'),
					(14, 'Pará', 'PA'),
					(15, 'Paraíba', 'PB'),
					(16, 'Pernambuco', 'PE'),
					(17, 'Piauí', 'PI'),
					(18, 'Paraná', 'PR'),
					(19, 'Rio de Janeiro', 'RJ'),
					(20, 'Rio Grande do Norte', 'RN'),
					(21, 'Rondônia', 'RO'),
					(22, 'Roraima', 'RR'),
					(23, 'Rio Grande do Sul', 'RS'),
					(24, 'Santa Catarina', 'SC'),
					(25, 'Sergipe', 'SE'),
					(26, 'São Paulo', 'SP'),
					(27, 'Tocantins', 'TO');";
            $this->executeDB($sql);

            $sql = "INSERT INTO pkg_module VALUES (NULL, 't(''Services'')', NULL, 'prefixs', NULL)";
            $this->executeDB($sql);
            $idServiceModule = Yii::app()->db->lastInsertID;

            $sql = "INSERT INTO pkg_group_module VALUES ((SELECT id FROM pkg_group_user WHERE id_user_type = 1 LIMIT 1), '" . $idServiceModule . "', 'crud', '1', '1', '1');";
            $this->executeDB($sql);

            $sql = "INSERT INTO pkg_module VALUES (NULL, 't(''Services'')', 'services', 'offer', '" . $idServiceModule . "')";
            $this->executeDB($sql);
            $idSubModule = Yii::app()->db->lastInsertID;

            $sql = "INSERT INTO pkg_group_module VALUES ((SELECT id FROM pkg_group_user WHERE id_user_type = 1 LIMIT 1), '" . $idSubModule . "', 'crud', '1', '1', '1');";
            $this->executeDB($sql);

            $sql = "INSERT INTO pkg_module VALUES (NULL, 't(''Services Use'')', 'servicesuse', 'offer', '" . $idServiceModule . "')";
            $this->executeDB($sql);
            $idSubModule = Yii::app()->db->lastInsertID;

            $sql = "INSERT INTO pkg_group_module VALUES ((SELECT id FROM pkg_group_user WHERE id_user_type = 1 LIMIT 1), '" . $idSubModule . "', 'crud', '1', '1', '1');";
            $this->executeDB($sql);

            $sql = "
				CREATE TABLE IF NOT EXISTS `pkg_services` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `name` varchar(100) NOT NULL,
					  `type` varchar(50) NOT NULL,
					  `status` tinyint(1) NOT NULL DEFAULT '1',
					  `price` decimal(15,4) NOT NULL DEFAULT '0.0000',
					  `description` text,
					  `disk_space` int(11) DEFAULT NULL,
					  `sipaccountlimit` int(11) DEFAULT NULL,
					  `calllimit` int(11) DEFAULT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

				CREATE TABLE IF NOT EXISTS `pkg_services_module` (
				  `id_services` int(11) NOT NULL,
				  `id_module` int(11) NOT NULL,
				  `action` varchar(45) NOT NULL,
				  `show_menu` tinyint(1) NOT NULL DEFAULT '1',
				  `createShortCut` tinyint(1) NOT NULL DEFAULT '0',
				  `createQuickStart` tinyint(1) NOT NULL DEFAULT '0',
				  PRIMARY KEY (`id_services`,`id_module`),
				  KEY `fk_pkg_services_module_pkg_module` (`id_module`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;


				ALTER TABLE `pkg_services_module`
				  ADD CONSTRAINT `fk_pkg_services_pkg_services_module` FOREIGN KEY (`id_services`) REFERENCES `pkg_services` (`id`),
				  ADD CONSTRAINT `fk_pkg_services_module_pkg_module` FOREIGN KEY (`id_module`) REFERENCES `pkg_module` (`id`);



				CREATE TABLE IF NOT EXISTS `pkg_services_use` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `id_user` int(11) DEFAULT NULL,
				  `id_services` int(11) NOT NULL,
				  `reservationdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				  `releasedate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
				  `status` int(11) DEFAULT '0',
				  `month_payed` int(11) DEFAULT '0',
				  `reminded` tinyint(4) NOT NULL DEFAULT '0',
				  `id_method` int(11) DEFAULT NULL,
				  PRIMARY KEY (`id`),
				  KEY `fk_pkg_user_pkg_services_use` (`id_user`),
				  KEY `fk_pkg_services_pkg_services_use` (`id_services`)
				) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

				ALTER TABLE `pkg_services_use`
				  ADD CONSTRAINT `fk_pkg_services_pkg_services_use` FOREIGN KEY (`id_services`) REFERENCES `pkg_services` (`id`),
				  ADD CONSTRAINT `fk_pkg_user_pkg_services_use` FOREIGN KEY (`id_user`) REFERENCES `pkg_user` (`id`);


				CREATE TABLE IF NOT EXISTS `pkg_services_plan` (
				  `id_services` int(11) NOT NULL,
				  `id_plan` int(11) NOT NULL,
				  PRIMARY KEY (`id_services`,`id_plan`),
				  KEY `fk_pkg_services_pkg_services_plan` (`id_services`),
				  KEY `fk_pkg_plan_pkg_services_plan` (`id_plan`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;


				ALTER TABLE `pkg_services_plan`
				  ADD CONSTRAINT `fk_pkg_services_pkg_services_plan` FOREIGN KEY (`id_services`) REFERENCES `pkg_services` (`id`) ON DELETE CASCADE,
				  ADD CONSTRAINT `fk_pkg_plan_pkg_services_plan` FOREIGN KEY (`id_plan`) REFERENCES `pkg_plan` (`id`) ON DELETE CASCADE;";
            $this->executeDB($sql);

            $version = '5.4.0';
            $sql     = "UPDATE pkg_configuration SET config_value = '" . $version . "' WHERE config_key = 'version' ";
            $this->executeDB($sql);
        }
        if ($version == '5.4.0') {
            $sql = "CREATE TABLE IF NOT EXISTS `pkg_group_user_group` (
					`id` int(11) NOT NULL AUTO_INCREMENT,
				  	`id_group_user` int(11) NOT NULL,
				  	`id_group` int(11) NOT NULL,
				  	PRIMARY KEY (`id`),
				  	KEY `fk_pkg_pkg_group_user_pkg_group` (`id_group_user`),
				  	KEY `fk_pkg_group_pkg_pkg_group_user_group` (`id_group`)
					) 	ENGINE=InnoDB DEFAULT CHARSET=utf8;


				ALTER TABLE `pkg_group_user_group`
				  ADD CONSTRAINT `fk_pkg_pkg_group_user_pkg_group` FOREIGN KEY (`id_group_user`) REFERENCES `pkg_group_user` (`id`) ON DELETE CASCADE,
				  ADD CONSTRAINT `fk_pkg_group_pkg_pkg_group_user_group` FOREIGN KEY (`id_group`) REFERENCES `pkg_group_user` (`id`) ON DELETE CASCADE;";

            $this->executeDB($sql);

            $sql = "INSERT INTO pkg_module VALUES (NULL, 't(''Group to Admins'')', 'groupusergroup', 'prefixs', 12)";
            try {
                Yii::app()->db->createCommand($sql)->execute();
            } catch (Exception $e) {

            }
            $idServiceModule = Yii::app()->db->lastInsertID;

            $sql = "INSERT INTO pkg_group_module VALUES ((SELECT id FROM pkg_group_user WHERE id_user_type = 1 LIMIT 1), '" . $idServiceModule . "', 'crud', '1', '1', '1');";
            $this->executeDB($sql);

            $sql = "ALTER TABLE  `pkg_user` CHANGE  `address`  `address` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;";
            $this->executeDB($sql);
            $sql = "INSERT INTO `pkg_configuration`  VALUES (NULL, 'Start User Call Limit', 'start_user_call_limit', '-1', 'Default call limit for new user', 'global', '0');";
            $this->executeDB($sql);
            $version = '5.4.1';
            $sql     = "UPDATE pkg_configuration SET config_value = '" . $version . "' WHERE config_key = 'version' ";
            $this->executeDB($sql);
        }
        if ($version == '5.4.1') {
            $sql = "ALTER TABLE  `pkg_did` ADD  `cbr` TINYINT( 1 ) NOT NULL DEFAULT  '0' AFTER  `billingtype`,
			ADD `cbr_em` TINYINT( 1 ) NOT NULL DEFAULT  '0' AFTER  `initblock`,
			ADD `cbr_ua` TINYINT( 1 ) NOT NULL DEFAULT  '0' AFTER  `initblock`,
			ADD  `TimeOfDay_monFri` VARCHAR( 150 ) NULL DEFAULT NULL ,
			ADD  `TimeOfDay_sat` VARCHAR( 150 ) NULL DEFAULT NULL ,
			ADD  `TimeOfDay_sun` VARCHAR(150 ) NULL DEFAULT NULL ,
			ADD  `workaudio` VARCHAR( 150 ) NULL DEFAULT NULL ,
			ADD  `noworkaudio` VARCHAR( 150 ) NULL DEFAULT NULL;
			UPDATE  `pkg_did` SET  `TimeOfDay_monFri` =  '09:00-12:00|14:00-18:00';
			UPDATE  `pkg_did` SET  `TimeOfDay_sat` =  '09:00-12:00';
			UPDATE  `pkg_did` SET  `TimeOfDay_sun` =  '00:00';
			ALTER TABLE  `pkg_callback` ADD  `id_did` INT( 11 ) NOT NULL AFTER  `id_user`;
			";
            $this->executeDB($sql);
            $version = '5.4.2';
            $sql     = "UPDATE pkg_configuration SET config_value = '" . $version . "' WHERE config_key = 'version' ";
            $this->executeDB($sql);
        }

        if (preg_match("/^5\./", $version)) {
            $sql = "
				ALTER TABLE  `pkg_iax` CHANGE  `nat`  `nat` VARCHAR( 25 ) NULL DEFAULT  'force_rport,comedia';
				ALTER TABLE  `pkg_sip` CHANGE  `nat`  `nat` VARCHAR( 25 ) NULL DEFAULT  'force_rport,comedia';

				ALTER TABLE  `pkg_trunk` ADD  `register_string` VARCHAR( 300 ) NOT NULL DEFAULT  '';

				CREATE TABLE IF NOT EXISTS `pkg_log_actions` (
					  `id` int(11) NOT NULL,
					  `name` varchar(20) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=latin1;

				INSERT INTO `pkg_log_actions` (`id`, `name`) VALUES
				(1, 'Login'),
				(2, 'Edit'),
				(3, 'Delete'),
				(4, 'New'),
				(5, 'Import'),
				(6, 'UpdateAll'),
				(7, 'Export'),
				(8, 'Logout');

				DROP TABLE pkg_log;

				CREATE TABLE IF NOT EXISTS `pkg_log` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `id_user` int(11) DEFAULT NULL,
				  `id_log_actions` int(11) DEFAULT NULL,
				  `description` mediumtext CHARACTER SET utf8 COLLATE utf8_bin,
				  `username` varchar(50) DEFAULT NULL,
				  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				  `ip` varchar(50) DEFAULT NULL,
				  PRIMARY KEY (`id`),
				  KEY `fk_pkg_log_actions_pkg_log` (`id_log_actions`)
				) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


				ALTER TABLE  `pkg_campaign_poll` ADD  `id_user` INT( 11 ) NOT NULL AFTER  `id`;
				UPDATE pkg_campaign_poll SET id_user = (SELECT id_user FROM pkg_campaign WHERE id = id_campaign);

				ALTER TABLE  `pkg_plan` CHANGE  `id_user`  `id_user` INT( 11 ) NULL DEFAULT NULL ;

			";
            $this->executeDB($sql);

            $sql    = "SELECT prefix FROM pkg_prefix group by prefix having count(*) >= 2";
            $result = Yii::app()->db->createCommand($sql)->queryAll();
            for ($i = 0; $i < count($result); $i++) {
                $ids = array();

                $sql          = "SELECT id FROM pkg_prefix WHERE prefix = " . $result[$i]['prefix'];
                $resultPrefix = Yii::app()->db->createCommand($sql)->queryAll();
                $firstPrefix  = $resultPrefix[0]['id'];
                unset($resultPrefix[0]);

                foreach ($resultPrefix as $key => $deletePrefix) {
                    $ids[] = $deletePrefix['id'];
                }
                $ids = implode(',', $ids);

                $sql = "UPDATE pkg_rate SET id_prefix = $firstPrefix WHERE id_prefix IN ($ids);
						UPDATE pkg_rate_agent SET id_prefix = $firstPrefix WHERE id_prefix IN ($ids);
						DELETE FROM pkg_prefix WHERE id IN ($ids)";
                $this->executeDB($sql);

            }

            $sql = "
			CREATE TABLE IF NOT EXISTS `pkg_prefix_length` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `code` int(11) NOT NULL,
			  `length` int(11) NOT NULL,
			  PRIMARY KEY (`id`),
			    UNIQUE KEY `code` (`code`)
			) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ;
				ALTER TABLE  `pkg_prefix` ADD UNIQUE (`prefix`)";
            $this->executeDB($sql);

            $version = '6.0.0';
            $sql     = "UPDATE pkg_configuration SET config_value = '" . $version . "' WHERE config_key = 'version' ";
            $this->executeDB($sql);
        }

        if ($version == '6.0.0') {

            $sql = "ALTER TABLE  `pkg_method_pay` ADD  `min` INT( 11 ) NOT NULL DEFAULT  '10', ADD  `max` INT( 11 ) NOT NULL DEFAULT  '500';
			ALTER TABLE  `pkg_trunk` ADD  `transport` VARCHAR( 3 ) NOT NULL DEFAULT  'no', ADD  `encryption` VARCHAR( 3 ) NOT NULL DEFAULT  'no', ADD  `port` VARCHAR( 5 ) NOT NULL DEFAULT  '5060';
			";
            $this->executeDB($sql);
            $version = '6.0.1';
            $sql     = "UPDATE pkg_configuration SET config_value = '" . $version . "' WHERE config_key = 'version' ";
            Yii::app()->db->createCommand($sql)->execute();
        }
        if ($version == '6.0.1') {

            $sql = "
		        ALTER TABLE  `pkg_method_pay` ADD  `showFields` TEXT NULL DEFAULT NULL;
		        INSERT INTO pkg_method_pay VALUES (NULL, '1', 'MercadoPago', 'MercadoPago', 'Brasil', '0', '0', NULL, '', '', '', '0', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '8:10:15:20:2:30:50:100:200', '10', '500', 'payment_method,show_name,id_user,country,active,min,max,username,pagseguro_TOKEN');
		        UPDATE pkg_method_pay SET showFields = 'payment_method,show_name,id_user,country,active,min,max,boleto_convenio,boleto_inicio_nosso_numeroa,boleto_banco,boleto_agencia,boleto_conta_corrente,boleto_carteira,boleto_taxa,boleto_instrucoes,boleto_nome_emp,boleto_end_emp,boleto_cidade_emp,boleto_estado_emp,boleto_cpf_emp' WHERE payment_method = 'BoletoBancario';
		        UPDATE pkg_method_pay SET showFields = 'payment_method,show_name,id_user,country,active,min,max,username,url' WHERE payment_method = 'CuentaDigital';
		        UPDATE pkg_method_pay SET showFields = 'payment_method,show_name,id_user,country,active,min,max,min,max,username,url' WHERE payment_method = 'DineroMail';
		        UPDATE pkg_method_pay SET showFields = 'payment_method,id_user,show_name,country,active,min,max,username,url' WHERE payment_method = 'Moip';
		        UPDATE pkg_method_pay SET showFields = 'payment_method,show_name,id_user,country,active,min,max,username,pagseguro_TOKEN' WHERE payment_method = 'Pagseguro';
		        UPDATE pkg_method_pay SET showFields = 'payment_method,show_name,id_user,country,active,min,max,username,url,fee' WHERE payment_method = 'Paypal';
		        UPDATE pkg_method_pay SET showFields = 'payment_method,show_name,id_user,country,active,min,max,username,pagseguro_TOKEN'  WHERE payment_method = 'IcePay';
		        UPDATE pkg_method_pay SET showFields = 'payment_method,show_name,id_user,country,active,min,max,username,url,pagseguro_TOKEN'  WHERE payment_method = 'Payulatam';
		        UPDATE pkg_method_pay SET showFields = 'payment_method,show_name,id_user,country,active,min,max,username,pagseguro_TOKEN' WHERE payment_method = 'AuthorizeNet';
		        UPDATE pkg_method_pay SET showFields = 'payment_method,show_name,id_user,country,active,min,max,P2P_CustomerSiteID,P2P_KeyID,P2P_Passphrase,P2P_RecipientKeyID,P2P_tax_amount' WHERE payment_method = 'PlacetoPay';
		        UPDATE pkg_method_pay SET showFields = 'payment_method,show_name,id_user,country,active,min,max,client_id,client_secret' WHERE payment_method = 'GerenciaNet';
		        UPDATE pkg_method_pay SET showFields = 'payment_method,show_name,id_user,country,active,min,max,SLIdProduto,SLAppToken,SLAccessToken,SLvalidationtoken' WHERE payment_method = 'SuperLogica';";
            $this->executeDB($sql);
            $version = '6.0.2';
            $sql     = "UPDATE pkg_configuration SET config_value = '" . $version . "' WHERE config_key = 'version' ";
            Yii::app()->db->createCommand($sql)->execute();
        }
        if ($version == '6.0.2') {

            $sql = "ALTER TABLE  `pkg_sip` ADD  `voicemail` TINYINT( 1 ) NOT NULL DEFAULT  '0'";
            $this->executeDB($sql);
            $version = '6.0.3';
            $sql     = "UPDATE pkg_configuration SET config_value = '" . $version . "' WHERE config_key = 'version' ";
            Yii::app()->db->createCommand($sql)->execute();
        }
    }

    public function executeDB($sql)
    {
        try {
            Yii::app()->db->createCommand($sql)->execute();
        } catch (Exception $e) {

        }
    }
}