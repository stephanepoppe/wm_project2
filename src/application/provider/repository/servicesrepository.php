<?php 

	namespace Application\Provider\Repository;

	class ServicesRepository extends \Knp\Repository {
	    
		public function getTableName() { return 'services'; }
	
		public function getAllServices(){
			return $this->db->fetchAll('SELECT services.id, 
				services.title, 
				services.description, 
				services.reward, 
				services.location_name, 
				services.location_latitude,
				services.location_longitude,
				services.deadline, 
  				users.name as users_name, 
  				services.author_id, 
  				categories.name as categories_name, 
  				services.categories_id 
 			FROM services
			INNER JOIN users ON services.author_id = users.id
			INNER JOIN categories ON categories.id = services.categories_id
			ORDER BY deadline');
		}

		public function getAllServicesByCategories($categories = null, $offset = 0, $pagesize = 10){

			$where = '';

			$query = 'SELECT services.id, 
				services.title, 
				services.description, 
				services.reward, 
				services.location_name, 
				services.location_latitude,
				services.location_longitude,
				services.deadline, 
  				users.name as users_name, 
  				services.author_id, 
  				categories.name as categories_name, 
  				services.categories_id 
 			FROM services
			INNER JOIN users ON services.author_id = users.id
			INNER JOIN categories ON categories.id = services.categories_id';

			if (!empty($categories)){
				$where = ' WHERE (';
				$i = 1;
				foreach ($categories as $categorie) {
					$where .= ' categories_id = ' .$categorie;
					if (count($categories) > 1 && $i < count($categories)){
						$where .= ' OR ';
					} 
					$i++;
				}
				$where .= ') AND (status = "pending")';
				$query .= $where;
			}
			else {
				$query .= ' WHERE status = pending';
			}

			$query .= ' ORDER BY deadline ';
			$query .= 'limit ' . $offset .', ' . $pagesize;


			return $this->db->fetchAll($query);
		}


		public function assignService($id, $executor_id){
			//UPDATE `services` SET `status`="assigned", `executor_id`=1 WHERE `id` = 6
			return $this->db->update('services', array('status' => 'assigned', 
				'executor_id' => $executor_id), array('id' => $id));

		}


		public function getAuthorId($serviceId){
			return $this->db->fetchColumn('SELECT author_id FROM services WHERE id = ?', array($serviceId));
		}

	}