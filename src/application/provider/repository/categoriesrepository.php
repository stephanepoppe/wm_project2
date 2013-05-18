<?php	
	namespace Application\Provider\Repository;

	class CategoriesRepository extends \Knp\Repository {
	    
		public function getTableName() { return 'categories'; }
	
	}