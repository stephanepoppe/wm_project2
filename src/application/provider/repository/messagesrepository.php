<?php 

	namespace Application\Provider\Repository;

	class MessagesRepository extends \Knp\Repository {
	    
		public function getTableName() { return 'messages'; }



		public function getMessagesByUserId($userId){
			
			return $this->db->fetchAll('SELECT messages.id, 
				message, 
				users.name, 
				users.mail  
				FROM `messages` 
				INNER JOIN users on `sender_id` = users.id  
				WHERE `receiver_id` = '.$userId.' AND status = "unread" ');
		}

		public function setMessageStatus($id, $status){
			return $this->db->update('messages', array('status' => $status), array('id' =>$id));
		}

	}