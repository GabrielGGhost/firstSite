<?php if(!class_exists('Rain\Tpl')){exit;}?><div class="container-ingredients">
  <div class="container">
  	<h3 class="RecipeName"><?php echo htmlspecialchars( $recipe["recipeName"], ENT_COMPAT, 'UTF-8', FALSE ); ?></h3>
    <div class="recipe_images">
        <img src="\res\site\img\recipe_hd\60_5f3c3c253974a.jpg" id="recipe_hdImage" class="recipe_hdImage">
    </div>
    <hr style="width: 100%;">
    <h3 class="ingredientTitle">Ingredientes</h3>
    <div class="recipeIngredientList" >
		<p>Para esta receita você vai precisar:</p>

		<ul style="padding-left: 20px; line-height: 30px;">
			<li class="item">3 colheres de manteiga</li>
			<li class="item">2 bananas congeladas</li>
			<li class="item">300mg de farinha de trigo</li>
			<li class="item">150mg de nescau</li>
			<li class="item">2 copos de óleo</li>
		</ul>	
    </div>
    <hr>
    <h3 class="ingredientTitle">Modo de preparo</h3>
    <div class="recipeStepsList">
		<ol style="padding-left: 20px; line-height: 30px;">
	    	<li class="item">Coloque na bacia as duas coisas</li>
	    	<li class="item">Coloque o óleo aos poucos</li>
	    	<li class="item">Quebre os ovos e faça assim</li>
	    	<li class="item">Bata no liquidificador</li>
	    </ol>
    </div>
  </div>
</div>

<script src="/res/site/js/checkItens.js"></script>
