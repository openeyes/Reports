<?php
class Report_CataractOutcomesReportCommand extends CConsoleCommand {

	public function getHelp() {
		$help = $help='Usage: '.$this->getCommandRunner()->getScriptName().' '.$this->getName().' [username] [from_date] [to_date]';
		return $help;
	}

	public function run($args)
	{
		if(count($args) != 3) {
			$this->usageError('Incorrect arguments');
		}
		$surgeon_username = $args[0];
		$start_date = $args[1];
		$end_date = $args[2];

		if(!$surgeon = User::model()->find('username like ?', array($surgeon_username))) {
			$this->usageError('Unknown username');
		}
		echo "\nFetching results for ".$surgeon->getReversedFullName()." from $start_date to $end_date inclusive...";
		
		$end_date = date('Y-m-d',strtotime($end_date) + 86400);
		$procedures = array(
			361191005, // Phakoemulsification
			415089008, // Phakoemulsification and IOL
			13793006, // Extracapsular cataract extraction
			308694002, // Extracapsular cataract extraction and insertion of IOL
		);

		$data = array(
			'cataracts' => 0,
			'sum_age' => 0,
			'min_age' => 0,
			'max_age' => 0,
			'mean_age' => 0,
			'left' => 0,
			'right' => 0,
			'complications' => 0,
			'complications%' => 0,
			'pcr' => 0,
			'pcr%' => 0,
			'total_cataracts' => 0,
			'total_complications' => 0,
			'total_complications%' => 0,
			'total_pcr' => 0,
			'total_pcr%' => 0,
		);

		// Total cataracts 
		$command = Yii::app()->db->createCommand()
		->selectDistinct('count(ev.id)')
		->from('event ev')
		->join('et_ophtroperationnote_procedurelist pl', 'pl.event_id = ev.id')
		->join('et_ophtroperationnote_procedurelist_procedure_assignment pla', 'pla.procedurelist_id = pl.id')
		->join('proc p', 'p.id = pla.proc_id')
		->where('
			p.snomed_code IN (\''.implode("','",$procedures).'\')
			and ev.deleted = 0
		');
		$data['total_cataracts'] = $command->queryScalar();
		
		// Total complications
		$command = Yii::app()->db->createCommand()
		->selectDistinct('count(ev.id)')
		->from('event ev')
		->join('et_ophtroperationnote_procedurelist pl', 'pl.event_id = ev.id')
		->join('et_ophtroperationnote_procedurelist_procedure_assignment pla', 'pla.procedurelist_id = pl.id')
		->join('proc p', 'p.id = pla.proc_id')
		->join('et_ophtroperationnote_cataract ca', 'ca.event_id = ev.id')
		->join('et_ophtroperationnote_cataract_complication caco', 'caco.cataract_id = ca.id')
		->join('et_ophtroperationnote_cataract_complications co', 'co.id = caco.complication_id')
		->where('
			p.snomed_code IN (\''.implode("','",$procedures).'\')
			and ev.deleted = 0
		');
		$data['total_complications'] = $command->queryScalar();
		
		// Total PCR
		$command = Yii::app()->db->createCommand()
		->selectDistinct('count(ev.id)')
		->from('event ev')
		->join('et_ophtroperationnote_procedurelist pl', 'pl.event_id = ev.id')
		->join('et_ophtroperationnote_procedurelist_procedure_assignment pla', 'pla.procedurelist_id = pl.id')
		->join('proc p', 'p.id = pla.proc_id')
		->join('et_ophtroperationnote_cataract ca', 'ca.event_id = ev.id')
		->join('et_ophtroperationnote_cataract_complication caco', 'caco.cataract_id = ca.id')
		->join('et_ophtroperationnote_cataract_complications co', 'co.id = caco.complication_id')
		->where('
			p.snomed_code IN (\''.implode("','",$procedures).'\')
			and ev.deleted = 0
			and co.name like :pcr
		');
		$data['total_pcr'] = $command->queryScalar(array(':pcr' => 'PC rupture'));
		
		$data['total_pcr%'] = round($data['total_pcr'] * 100 / $data['total_cataracts'],1);		
		$data['total_complications%'] = round($data['total_complications'] * 100 / $data['total_cataracts'],1);		

		// Surgeon data for date range
		$command = Yii::app()->db->createCommand()
		->selectDistinct('ev.id')
		->from('event ev')
		->join('et_ophtroperationnote_procedurelist pl', 'pl.event_id = ev.id')
		->join('et_ophtroperationnote_surgeon sur', 'sur.event_id = ev.id')
		->join('user u', 'u.id = sur.surgeon_id')
		->join('et_ophtroperationnote_procedurelist_procedure_assignment pla', 'pla.procedurelist_id = pl.id')
		->join('proc p', 'p.id = pla.proc_id')
		->where('
			ev.created_date >= :start_date
			and ev.created_date <= :end_date
			and p.snomed_code IN (\''.implode("','",$procedures).'\')
			and ev.deleted = 0
			and u.id = :user_id
		');
		$params = array(
                        ':start_date' => $start_date,
                        ':end_date' => $end_date,
			':user_id' => $surgeon->id,
                );
		$event_ids = $command->queryColumn($params);

		foreach($event_ids as $event_id) {
			$surgeon_element = ElementSurgeon::model()->with(array('event.episode.patient'))->findByAttributes(array('event_id' => $event_id));
			$event = $surgeon_element->event;
			
			// Surgeon cataracts
			$data['cataracts']++;

			// Ages
			$data['sum_age'] += $event->episode->patient->age;
			$data['mean_age'] = round($data['sum_age'] / $data['cataracts']);
			if(!$data['min_age'] || $event->episode->patient->age < $data['min_age']) {
				$data['min_age'] = $event->episode->patient->age;
			}
			if($event->episode->patient->age > $data['max_age']) {
				$data['max_age'] = $event->episode->patient->age;
			}

			// Eyes
			$procedure_list = ElementProcedureList::model()->findByAttributes(array('event_id' => $event_id));
			$data[strtolower($procedure_list->eye->name)]++;

			// Complications / PCR
			if($complications = CataractComplication::model()->with(array('complication','cataract'))->findAll('cataract.event_id = :event_id', array(':event_id' => $event_id))) {
				$data['complications']++;
				foreach($complications as $complication) {
					if($complication->complication->name == 'PC rupture') {
						$data['pcr']++;
					}
				}
			}
			
		}
	
		// Percentages
		$data['pcr%'] = round($data['pcr'] * 100 / $data['cataracts'],1);		
		$data['complications%'] = round($data['complications'] * 100 / $data['cataracts'],1);		
	
		echo "done.\n\n";
		echo "Results\n-------\n";
		foreach($data as $label => $value) {
			echo "$label: $value\n";
		}
		echo "\n";
	}

}
