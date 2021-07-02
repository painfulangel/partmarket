<?php
	/* Command for cross table cron */
	class ProcessCrossFiles extends CConsoleCommand {
		public function run($args) {
			$cross = Crosses::model()->find('processed = 0 AND file_count > 0');
	    	
	    	if (is_object($cross)) {
	    		//file_put_contents('/var/www/partexpert_beta/data/www/beta.partexpert.ru/cron.txt', "1", FILE_APPEND);
	    		$cross->isProgramWork = false;
	    		$cross->process();
	    		//file_put_contents('/var/www/partexpert_beta/data/www/beta.partexpert.ru/cron.txt', "2", FILE_APPEND);
	    	} else {
	    		//Удалить задачу планировщика
	    		$cron = new Crontab('my_crontab', Yii::app()->basePath . '/runtime/');
	    			
	    		$jobs_obj = $cron->getJobs();
	    			
	    		foreach ($jobs_obj as $key => $value) {
	    			if ($value->getCommandName() == 'ProcessCrossFiles')
	    				$cron->removeJob($key);
	    		}
	    		
	    		$cron->saveCronFile(); // save to my_crontab cronfile
	    		$cron->saveToCrontab(); // adds all my_crontab jobs to system (replacing previous my_crontab jobs)
	    	}
		}
	}