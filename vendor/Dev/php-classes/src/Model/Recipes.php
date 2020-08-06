<?php 

namespace Dev\Model;

use Dev\Model;
use Dev\DB\Sql;


class Recipes extends Model {

	private $listedIngredients = [];
	private $listedSteps = [];

	const INGREDIENTS_LISTED = "ingredients";
	const STEPS_LISTED = "steps";
	const SESSION_ERROR = "RecipeError";
	const SESSION_SUCCESS = "RecipeSuccess";
	const REGISTER_VALUES = 'recipeRegisterValues';

	public function listAll(){

		$sql = new Sql();

		$results = $sql->select("SELECT * FROM tb_recipes");

		return $results;
	}

	public static function setError($msg) {

		$_SESSION[Recipes::SESSION_ERROR] = $msg;
	}

	public static function getError(){

		$msg = (isset($_SESSION[Recipes::SESSION_ERROR])) ? $_SESSION[Recipes::SESSION_ERROR] : "";
		
		Recipes::clearMsgError();

		return $msg;
	}

	public static function clearMsgError(){
		$_SESSION[Recipes::SESSION_ERROR] = NULL;
	}
///////////////////////////////////////////////////////////////////
	public static function setSuccess($msg) {

		$_SESSION[Recipes::SESSION_SUCCESS] = $msg;
	}

	public static function getSuccess(){

		$msg = (isset($_SESSION[Recipes::SESSION_SUCCESS])) ? $_SESSION[Recipes::SESSION_SUCCESS] : "";
		
		Recipes::clearMsgSuccess();

		return $msg;
	}

	public static function clearMsgSuccess(){
		$_SESSION[Recipes::SESSION_SUCCESS] = NULL;
	}

	public function saveRecipe(){

		$sql = new Sql();

		$results = $sql->select("CALL sp_recipe_save(:RECIPENAME,
														:YIELD,
														:IDYIELD,
														:PREPARARIONTIME,
														:IDDIFFICULT,
														:IDAUTHOR)", [
														 	':RECIPENAME'=>utf8_decode($this->getrecipeName()),
														 	':YIELD'=>$this->getyield(),
														 	':IDYIELD'=>$this->getidYield(),
														 	':PREPARARIONTIME'=>$this->getpreparationTime(),
														 	':IDDIFFICULT'=>$this->getidDifficult(),
														 	':IDAUTHOR'=>$this->getidAuthor()
														 ]);

		$data = $results[0];

		$data['recipeName'] = utf8_encode($data['recipeName']);

		$this->setData($data);
	}

	public function update(){

		$sql = new Sql();

		$results = $sql->select("call sp_yieldType_update(:IDTYPE,:NAME)",[
															':IDTYPE'=>$this->getidType(),
														 	':NAME'=>utf8_decode($this->getname())
														 ]);

;

		$data = $results[0];

		$data['name'] = utf8_encode($data['name']);

		$this->setData($data);
	}

	public static function verifyYield($type){

		$sql = new Sql();

		$type = utf8_decode($type);

		$results = $sql->select("SELECT * 
									FROM tb_yieldType
										WHERE name = :NAME", [
											':NAME'=>$type
										]);

		if (count($results) === 0) return false;

		return true;
	}




	public function des_active(){

		$sql = new Sql();

		$sql->query("UPDATE tb_yieldType
						SET active = :STATUS
							WHERE idType = :IDMEASURE", [
							':STATUS'=>!$this->getactive(),
							':IDMEASURE'=>$this->getidType()
						]);
	}

	public static function getComboBox($query){

		$sql = new Sql();

		switch ($query) {
			case 'yt':
				$data = $sql->select("SELECT * FROM tb_yieldType WHERE active = 1");
				break;
			case 'diff':
				$data = $sql->select("SELECT * FROM tb_difficult WHERE active = 1");
				break;
			case 'meas':
				$data = $sql->select("SELECT * FROM tb_measure WHERE active = 1");
				break;
			case 'ing':
				$data = $sql->select("SELECT * FROM tb_ingredient WHERE active = 1 ORDER BY singularName");
				break;
			default:
				# code...
				break;
		}

		return $data;
	}

	public function setlistedIngredients($data){

		$arr = 0;
		$i = 0;
		foreach ($data as $key => $value) {
			switch (substr($key, 0, 3)) {
				case 'qua':
					$this->listedIngredients[$arr]['quantityId'] = $key;
					$this->listedIngredients[$arr]['quantity'] = $value;
					break;
				case 'mea':

					$this->listedIngredients[$arr]['measureId'] = $key;
					$this->listedIngredients[$arr]['measure'] = $value;
					break;
				case 'com':
					$this->listedIngredients[$arr]['complementId'] = $key;
					$this->listedIngredients[$arr]['complement'] = $value;
					break;
				case 'ing':
					$this->listedIngredients[$arr]['ingredientId'] = $key;
					$this->listedIngredients[$arr]['ingredient'] = $value;
					break;
				case 'plu':
					$this->listedIngredients[$arr]['pluralId'] = $key;
					$this->listedIngredients[$arr]['plural'] = $value;
					$arr++;
					break;
			}
		}

		$_SESSION[Recipes::INGREDIENTS_LISTED] = $this->listedIngredients;
	}

	public function setlistedSteps($data){

		$arr = 0;
		foreach ($data as $key => $value) {
			switch (substr($key, 0, 3)) {
			case 'ste':
				$this->listedSteps[$arr]['stepId'] = $key;
				$this->listedSteps[$arr]['description'] = $value;
				$arr++;
				break;
			}
		}
		
		$_SESSION[Recipes::STEPS_LISTED] = $this->listedSteps;
	}

	public function checkIngredients($ingredients){

		foreach ($ingredients as $key => $arr) {
			foreach ($arr as $index => $value) {
				
				switch ($index) {
					case 'quantity':

						if(!isset($value) || $value === '' || (int)$value < 1) {

							Recipes::setError("O ingrediente númeroº" . ($key + 1) . " precisa e uma quantidade maior do que 0");
							header("Location: /admin/recipes/create");
							exit;
						}
						break;
				}
			}
		}
	}

	public function checkSteps($steps){

		foreach ($steps as $key => $arr) {
			foreach ($arr as $index => $value) {

				switch ($index) {
					case 'description':

						if(!isset($value) || $value === '') {

							Recipes::setError("O passo númeroº" . ($key + 1) . " precisa de uma descrição");
							header("Location: /admin/recipes/create");
							exit;
						}
						break;
				}
			}
		}
	}

	public function checkAuthor(){

		$sql = new Sql();

		$result = $sql->select("SELECT COUNT(*)
									FROM tb_users
										WHERE idUser = :IDUSER", [
											':IDUSER'=>$this->getidAuthor()
										]);
		
		$result = $result[0];

		if((int)$result['COUNT(*)'] > 0) {
			return true;
		}

		return false;
	}

	public function saveIngredients(){

		$sql = new Sql();

		$ingredients = $_SESSION[Recipes::INGREDIENTS_LISTED];

		foreach ($ingredients as $key => $arr) {

			$sql->select("CALL sp_recipe_ingredient_save(:IDRECIPE,
														:IDINGREDIENT,
														:QUANTITY,
														:MEASURETYPE,
														:COMPLEMENT,
														:PLURAL)", [
														 	':IDRECIPE'=>utf8_decode($this->getidRecipe()),
															':IDINGREDIENT'=>$arr['ingredient'],
														 	':QUANTITY'=>$arr['quantity'],
														 	':MEASURETYPE'=>$arr['measure'],
														 	':COMPLEMENT'=>$arr['complement'],
														 	':PLURAL'=>$arr['plural']
																	]);
		}
	}

	public function saveSteps(){

		$sql = new Sql();

		$steps = $_SESSION[Recipes::STEPS_LISTED];


		$i = 1;
		foreach ($steps as $key => $arr) {

			$sql->select("CALL sp_recipe_step_save(:IDRECIPE,
													:STEP,
													:DESCRIPTION)", [
													 	':IDRECIPE'=>utf8_decode($this->getidRecipe()),
														':STEP'=>$i++,
													 	':DESCRIPTION'=>utf8_decode($arr['description'])
																	]);
		}
	}

	public static function getRecipe($idRecipe){

		$sql = new Sql();

		$results = $sql->select("SELECT *
									FROM tb_recipes
										WHERE idRecipe = :IDRECIPE", [
											':IDRECIPE'=>$idRecipe]);

		return $results[0];
	}

	public static function getRecipeIngredients($idRecipe){

		$sql = new Sql();

		$results = $sql->select("SELECT *
									FROM tb_recipe_ingredients
										WHERE idRecipe = :IDRECIPE", [
											':IDRECIPE'=>$idRecipe]);

		return Recipes::linkIngredients($results);
	}

	public static function linkIngredients($ingredients){
		
		$i = 1;
		foreach ($ingredients as $key => &$arr) {

			$arr['quantityId'] = "quantity_" . $i;
			$arr['measureId'] = "measure_" . $i;
			$arr['complementId'] = "complement_" . $i;
			$arr['ingredientId'] = "ingredient_" . $i;
			$arr['pluralId'] = "plural_" . $i++;
		}

		var_dump($ingredients);
		exit;
	}

}


?>