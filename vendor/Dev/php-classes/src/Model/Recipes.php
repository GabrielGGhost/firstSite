<?php 

namespace Dev\Model;

use Dev\Model;
use Dev\DB\Sql;
use \CoffeeCode\Cropper\Cropper;
use \Faker\Factory;

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

		$results = $sql->select("SELECT *
									FROM tb_recipes AS r
										INNER JOIN tb_yield AS y
											ON r.idYield = y.idYeld");

		return $results;
	}

	public function listAllActived(){

		$sql = new Sql();

		$results = $sql->select("SELECT r.idRecipe,
										r.recipeName,
										TIME_FORMAT(r.preparationTime, '%H:%i') AS preparationTime,
										d.difficultLevel,
										u.login,
										y.amount,
										yt.singularName,
										yt.pluralName
									FROM tb_recipes AS r
										INNER JOIN tb_yield AS y
											ON r.idYield = y.idYeld
										INNER JOIN tb_difficult AS d
											ON r.idDifficult = d.idDifficult
										INNER JOIN tb_users AS u
											ON r.idAuthor = u.idUser
										INNER JOIN tb_yieldType as yt
											ON y.idType = yt.idType
												WHERE r.active = true;");

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
														 	':RECIPENAME'=>ucfirst(utf8_decode($this->getrecipeName())),
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

	public function updateRecipe(){

		$sql = new Sql();

		$results = $sql->select("call sp_recipe_update(:IDRECIPE,
														:RECIPENAME,
														:YIELD,
														:IDYIELD,
														:PREPARARIONTIME,
														:IDDIFFICULT,
														:IDAUTHOR)", [
															':IDRECIPE'=>$this->getidRecipe(),
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

		$sql->query("UPDATE tb_recipes
						SET active = :STATUS
							WHERE idRecipe = :IDRECIPE", [
							':STATUS'=>!$this->getactive(),
							':IDRECIPE'=>$this->getidRecipe()
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

	public function checkIngredients($ingredients, $create = true){

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

		$sql->query("DELETE FROM tb_recipe_ingredients WHERE idRecipe = :IDRECIPE",[
			'IDRECIPE'=>$this->getidRecipe()]);

		$ingredients = $_SESSION[Recipes::INGREDIENTS_LISTED];

		foreach ($ingredients as $key => $arr) {

			if($arr['measure'] == "") $arr['measure'] = NULL;

			$results = $sql->select("CALL sp_recipe_ingredient_save(:IDRECIPE,
																	:IDINGREDIENT,
																	:QUANTITY,
																	:MEASURETYPE,
																	:COMPLEMENT,
																	:PLURAL)", [
																	 	':IDRECIPE'=>$this->getidRecipe(),
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

		$sql->query("DELETE FROM tb_prepare WHERE idRecipe = :IDRECIPE",[
			'IDRECIPE'=>$this->getidRecipe()]);

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
									FROM tb_recipes AS r
										INNER JOIN tb_yield AS y
											ON r.idYield = y.idYeld
												WHERE idRecipe = :IDRECIPE", [
													':IDRECIPE'=>$idRecipe]);

		$data = $results[0];

		$data['recipeName'] = utf8_decode($data['recipeName']);

		return $data;
	}

	public function getRecipeData($idRecipe){

		$sql = new Sql();

		$results = $sql->select("SELECT *
									FROM tb_recipes
										WHERE idRecipe = :IDRECIPE", [
											':IDRECIPE'=>$idRecipe]);

		$data = $results[0];

		$data['recipeName'] = utf8_decode($data['recipeName']);

		$this->setData($data);
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

		return $ingredients;
	}

	public static function getRecipeSteps($idRecipe){

		$sql = new Sql();

		$results = $sql->select("SELECT *
									FROM tb_prepare
										WHERE idRecipe = :IDRECIPE", [
											':IDRECIPE'=>$idRecipe]);

		return Recipes::linkSteps($results);
	}

	public static function linkSteps($steps){
		
		$i = 1;
		foreach ($steps as $key => &$arr) {

			$arr['stepId'] = "step_" . $i;
			$arr['step'] = $i++;
		}

		return $steps;
	}

	public function checkPhoto($destination) {

		switch ($destination) {
			case 'thumb':
					if(file_exists(
						$_SERVER['DOCUMENT_ROOT'] .
						DIRECTORY_SEPARATOR .
						"res" .
						DIRECTORY_SEPARATOR .
						"site" .
						DIRECTORY_SEPARATOR .
						"img" .
						DIRECTORY_SEPARATOR .
						"recipe_thumbs" .
						DIRECTORY_SEPARATOR .
						$this->getpictureId() .
						".jpg"
					)) {

						$url = "/res/site/img/recipe_thumbs/" . $this->getpictureId() . ".jpg";
					} else {

						$url = "/res/site/img/defaults/ingredient-default.jpg";

					}
				break;
			
			case 'hd':
					if(file_exists(
						$_SERVER['DOCUMENT_ROOT'] .
						DIRECTORY_SEPARATOR .
						"res" .
						DIRECTORY_SEPARATOR .
						"site" .
						DIRECTORY_SEPARATOR .
						"img" .
						DIRECTORY_SEPARATOR .
						"recipe_hd" .
						DIRECTORY_SEPARATOR .
						$this->getpictureId() .
						".jpg"
					)) {

						$url = "/res/site/img/recipe_hd/" . $this->getpictureId() . ".jpg";
					} else {

						$url = "/res/site/img/defaults/ingredient-default.jpg";

					}
				break;
		}



		$this->setpathPhoto($url);

		$this->resizePicture($destination);
	}

	public function setPhoto($file){

		$extension = explode('.', $file["name"]);
		$extension = end($extension);

		switch ($extension) {
			case 'jpg':
			case 'pjpeg';
			case 'jpeg':
				$image = imagecreatefromjpeg($file["tmp_name"]);
				break;

			case 'gif':
				$image = imagecreatefromgif($file["tmp_name"]);
				break;

			case 'png':
				$image = imagecreatefrompng($file["tmp_name"]);
				break;
			default:
				Recipes::setError("Formtato de imagem não aceito!");
				header("Location: /admin/recipes/" . $this->getidRecipe() . "/images");
				exit;

		}

		$picture_name = $this->getidRecipe() . "_" . uniqid() .	".jpg";
		$this->createImage('hd', $picture_name, $image);
		$this->createImage('thumb', $picture_name, $image);
		imagedestroy($image);

	}

	public function createImage($destination, $picture_name, $image){

		switch ($destination) {
			case 'thumb':

				$destFolder = $_SERVER['DOCUMENT_ROOT'] .
										DIRECTORY_SEPARATOR .
										"res" . 
										DIRECTORY_SEPARATOR .
										"site" .
										DIRECTORY_SEPARATOR .
										"img" .
										DIRECTORY_SEPARATOR .
										"recipe_thumbs" .
										DIRECTORY_SEPARATOR .
										$picture_name;
				break;

			case 'hd':
				$destFolder = $_SERVER['DOCUMENT_ROOT'] .
										DIRECTORY_SEPARATOR .
										"res" . 
										DIRECTORY_SEPARATOR .
										"site" .
										DIRECTORY_SEPARATOR .
										"img" .
										DIRECTORY_SEPARATOR .
										"recipe_hd" .
										DIRECTORY_SEPARATOR .
										$picture_name;
				break;
		}

		

		imagejpeg($image, $destFolder);

		$this->setpictureId($picture_name);
		$this->checkPhoto($destination);
	}

	public function insertPictureDB($picturePath, $idIngredient, $idRecipe, $type){

		$sql = new Sql();

		$sql->select("CALL sp_insertPath(:PATH,
										 :IDRECIPE,
										 :IDINGREDIENT,
										 :IDTYPE)", [
							':PATH'=>$picturePath,
							':IDRECIPE'=>$idRecipe,
							':IDINGREDIENT'=>$idIngredient,
							':IDTYPE'=>$type
						]);

	}

	public function unlinkImage(){

		$file = $_SERVER['DOCUMENT_ROOT'] .
					DIRECTORY_SEPARATOR .
					"res" . 
					DIRECTORY_SEPARATOR .
					"site" .
					DIRECTORY_SEPARATOR .
					"img" .
					DIRECTORY_SEPARATOR .
					"recipe_thumbs" .
					DIRECTORY_SEPARATOR .
					$this->getpath() .
					".jpg";

		if(file_exists($file)) unlink($file);

		$this->deletePath();
	}

	public function deletePath(){
		$sql = new Sql();

		$sql->select("DELETE 
						FROM tb_picturepath
							WHERE idRecipe = :IDRECIPE
								AND pathId = :PATHID", [
								':IDRECIPE'=>$this->getidRecipe(),
								':PATHID'=>$this->getpath()
							]);
	}


	public function resizePicture($destination){

		switch ($destination) {
			case 'thumbs':
				$path = $_SERVER['DOCUMENT_ROOT'] .
									DIRECTORY_SEPARATOR .
									"res" .
									DIRECTORY_SEPARATOR .
									"site" .
									DIRECTORY_SEPARATOR .
									"img" .
									DIRECTORY_SEPARATOR .
									"recipe_thumbs" .
									DIRECTORY_SEPARATOR .
									$this->getpictureId();

						$dir = $_SERVER['DOCUMENT_ROOT'] .
									DIRECTORY_SEPARATOR .
									"res" .
									DIRECTORY_SEPARATOR .
									"site" .
									DIRECTORY_SEPARATOR .
									"img" .
									DIRECTORY_SEPARATOR .
									"recipe_thumbs";

				$c = new Cropper($dir);

				$c->make($path, 300, $this->getidRecipe(), "recipe-thumb",  200);
				break;
			
			case 'hd':
				$path = $_SERVER['DOCUMENT_ROOT'] .
									DIRECTORY_SEPARATOR .
									"res" .
									DIRECTORY_SEPARATOR .
									"site" .
									DIRECTORY_SEPARATOR .
									"img" .
									DIRECTORY_SEPARATOR .
									"recipe_hd" .
									DIRECTORY_SEPARATOR .
									$this->getpictureId();

						$dir = $_SERVER['DOCUMENT_ROOT'] .
									DIRECTORY_SEPARATOR .
									"res" .
									DIRECTORY_SEPARATOR .
									"site" .
									DIRECTORY_SEPARATOR .
									"img" .
									DIRECTORY_SEPARATOR .
									"recipe_hd";

				$c = new Cropper($dir);

				$c->make($path, 1920, $this->getidRecipe(), "recipe-hd",  1080);

				break;
		}

	}

	public static function getPathes($idRecipe){

		$sql = new Sql();

		$results = $sql->select("SELECT * 
									FROM tb_picturepath
										WHERE idRecipe = :IDRECIPE
											ORDER BY idPath
												DESC", [
											':IDRECIPE'=>$idRecipe
										]);

		return $results;
	}

	public static function getPreviewImages($data){

		foreach ($data as $index => &$arr) {
			foreach ($arr as $key => $value) {
				$sql = new Sql();

				$results = $sql->select("SELECT *
											from tb_picturepath
												WHERE idRecipe = :IDRECIPE
													LIMIT 1", [
															':IDRECIPE'=>$arr['idRecipe']
														]);

				if(count($results) > 0){

					$arr['pathId'] = $results[0]['pathId'];

				} else {

					$arr['pathId'] = "";
				}

			}

		}

		return $data;
	}

	public function getRecipeIngredientes($idRecipe){
		$sql = new Sql();

		return $sql->select("SELECT i.singularName, i.pluralName, ri.quantity, ri.complement, ri.plural, m.singularName, m.pluralName
								FROM tb_recipe_ingredients as ri
									INNER JOIN tb_ingredient AS i
										USING(idIngredient)
									INNER JOIN tb_measure as m
										ON ri.measureType = m.idType
											WHERE idRecipe = :IDRECIPE
									", [
										':IDRECIPE'=>$idRecipe
									]);
	}

}


?>