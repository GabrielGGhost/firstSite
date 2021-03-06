<?php if(!class_exists('Rain\Tpl')){exit;}?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Editar Receita
  </h1>
</section>

<!-- Main content -->
<section class="content">

  <div class="row">
  	<div class="col-md-12">
  		<div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Editar Usuário</h3>
        </div>
        <!-- /.box-header -->
        <?php if( $createError != '' ){ ?>
          <div class="alert alert-danger">
            <?php echo htmlspecialchars( $createError, ENT_COMPAT, 'UTF-8', FALSE ); ?>
          </div>
        <?php } ?>
        <?php if( $createSuccess != '' ){ ?>
          <div class="alert alert-success">
            <?php echo htmlspecialchars( $createSuccess, ENT_COMPAT, 'UTF-8', FALSE ); ?>
          </div>
        <?php } ?>
        <!-- form start -->
        <form role="form" action="/admin/recipes/<?php echo htmlspecialchars( $recipeData["idRecipe"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" method="post">
          <div class="box-body">
            <div class="form-group">
              <div class="form-double">
                <label for="recipeName">Nome</label>
                <input type="text" class="form-control" id="recipeName" name="recipeName" placeholder="Digite o nome da receita" maxlength="30" value="<?php echo htmlspecialchars( $recipeData["recipeName"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
              </div>
              <div class="form-double">
                <div class="form-double">
                  <label for="yield">Rendimento</label>
                  <input type="number" class="form-control" id="yield" name="yield" placeholder="Digite o nome da receita" maxlength="10" value="<?php echo htmlspecialchars( $recipeData["amount"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
                </div>
                <div class="form-double">
                  <div class="form-double">
                    <label for="idYield">tipo</label>
                    <select class="form-control" id="idYield" name="idYield">
                      <?php $a = $recipeData["idYield"]; ?>
                      <?php $counter1=-1;  if( isset($yieldTypes) && ( is_array($yieldTypes) || $yieldTypes instanceof Traversable ) && sizeof($yieldTypes) ) foreach( $yieldTypes as $key1 => $value1 ){ $counter1++; ?>
                        <option value="<?php echo htmlspecialchars( $value1["idType"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" <?php if( $value1["idType"] == $a ){ ?>Selected<?php } ?>><?php echo htmlspecialchars( $value1["singularName"], ENT_COMPAT, 'UTF-8', FALSE ); ?></option>
                      <?php } ?>
                    </select>
                  </div>
                  <div class="form-double">
                    <label for="preparationTime">T.Preparo</label>
                    <input type="time" class="form-control" id="preparationTime" name="preparationTime" placeholder="Digite o nome da receita" maxlength="30" value="<?php echo htmlspecialchars( $recipeData["preparationTime"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="form-double">
                <label for="idDifficult">Dificuldade</label>
                <select class="form-control" id="idDifficult" name="idDifficult">
                  <?php $b=$recipeData["idDifficult"]; ?>
                  <?php $counter1=-1;  if( isset($difficults) && ( is_array($difficults) || $difficults instanceof Traversable ) && sizeof($difficults) ) foreach( $difficults as $key1 => $value1 ){ $counter1++; ?>
                    <option value="<?php echo htmlspecialchars( $value1["idDifficult"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" <?php if( $value1["idDifficult"] == $b ){ ?>Selected<?php } ?>><?php echo htmlspecialchars( $value1["difficultLevel"], ENT_COMPAT, 'UTF-8', FALSE ); ?></option>
                  <?php } ?>
                </select>
              </div>
              <div class="form-double">
                <div class="form-double">
                  <label for="idAuthor">Id Autor</label>
                  <input type="number" class="form-control" id="idAuthor" name="idAuthor" placeholder="Digite o ID" maxlength="30" value="<?php echo htmlspecialchars( $recipeData["idAuthor"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
                </div>
                <div class="form-double">
                  <label for="recipeName">Autor</label>
                  <input type="text" class="form-control" id="recipeName" name="recipeName" maxlength="30" disabled>
                </div>
              </div>
            </div>
            <div class="form-group" id="ingredients">
              <?php $counter1=-1;  if( isset($ingredientsList) && ( is_array($ingredientsList) || $ingredientsList instanceof Traversable ) && sizeof($ingredientsList) ) foreach( $ingredientsList as $key1 => $value1 ){ $counter1++; ?>
               <div id="ingredientLine_1" class="ingredientList">      
                <div class="form-double">
                  <div class="form-double">
                    <label for="<?php echo htmlspecialchars( $value1["quantityId"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">Quantidade</label>
                    <input type="number" class="form-control" id="<?php echo htmlspecialchars( $value1["quantityId"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" name="<?php echo htmlspecialchars( $value1["quantityId"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" maxlength="30" value="<?php echo htmlspecialchars( $value1["quantity"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
                  </div>
                  <div class="form-double">
                    <label for="<?php echo htmlspecialchars( $value1["measureId"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">Medida</label>
                    <select class="form-control" id="<?php echo htmlspecialchars( $value1["measureId"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" name="<?php echo htmlspecialchars( $value1["measureId"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
                      <option value="0"></option>
                      <?php $a = $value1["measuretype"]; ?>
                      <?php $counter2=-1;  if( isset($measures) && ( is_array($measures) || $measures instanceof Traversable ) && sizeof($measures) ) foreach( $measures as $key2 => $value2 ){ $counter2++; ?>
                        <option value="<?php echo htmlspecialchars( $value2["idType"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" <?php if( $value2["idType"] == $a ){ ?> selected <?php } ?>><?php echo htmlspecialchars( $value2["singularName"], ENT_COMPAT, 'UTF-8', FALSE ); ?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-double" style="margin-bottom: 10px">
                  <div class="form-double">
                    <label for="<?php echo htmlspecialchars( $value1["complementId"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">Complemento</label>
                    <input type="text" class="form-control" id="<?php echo htmlspecialchars( $value1["complementId"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" name="<?php echo htmlspecialchars( $value1["complementId"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" maxlength="30" value="<?php echo htmlspecialchars( $value1["complement"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
                  </div>
                  <div class="form-double">
                    <div class="form-double" style="width: 70%">
                      <label for="">Ingrediente</label>
                      <select class="form-control" id="<?php echo htmlspecialchars( $value1["ingredientId"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" name="<?php echo htmlspecialchars( $value1["ingredientId"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
                        <?php $i = $value1["idIngredient"]; ?>
                        <?php $counter2=-1;  if( isset($ingredients) && ( is_array($ingredients) || $ingredients instanceof Traversable ) && sizeof($ingredients) ) foreach( $ingredients as $key2 => $value2 ){ $counter2++; ?>
                        <option value="<?php echo htmlspecialchars( $value2["idIngredient"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" <?php if( $value2["idIngredient"] == $i ){ ?>Selected<?php } ?>><?php echo htmlspecialchars( $value2["singularName"], ENT_COMPAT, 'UTF-8', FALSE ); ?></option>
                        <?php } ?>
                      </select>
                    </div>
                    <div class="form-double" style="width: 30%">
                      <label for="<?php echo htmlspecialchars( $value1["pluralId"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">Plural</label>
                      <select class="form-control" id="<?php echo htmlspecialchars( $value1["pluralId"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" name="<?php echo htmlspecialchars( $value1["pluralId"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
                        <option value="0" <?php if( $value1["plural"] == 0 ){ ?> selected <?php } ?>>Não</option>
                        <option value="1" <?php if( $value1["plural"] == 1 ){ ?> selected <?php } ?>>Sim</option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
            <?php } ?>
            </div>
            <button type="button" id="addIngredient" class="btn btn-primary">+</button>
            <button type="button" id="removeIngredient" class="btn btn-primary">-</button>
            <div class="form-group">
              <label for="recipeName">Passos</label>
              <div id="steps">
              <?php $counter1=-1;  if( isset($stepsList) && ( is_array($stepsList) || $stepsList instanceof Traversable ) && sizeof($stepsList) ) foreach( $stepsList as $key1 => $value1 ){ $counter1++; ?>
                <div id="<?php echo htmlspecialchars( $value1["stepId"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" class="stepList">
                  <input type="text" class="form-control stepInput" id="<?php echo htmlspecialchars( $value1["stepId"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" name="<?php echo htmlspecialchars( $value1["stepId"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" placeholder="Passo Nº<?php echo htmlspecialchars( $value1["step"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" maxlength="200" value="<?php echo htmlspecialchars( $value1["description"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
                </div>
              <?php } ?>
              </div>
            </div>
            <button type="button" id="addStep" class="btn btn-primary">+</button>
            <button type="button" id="removeStep" class="btn btn-primary">-</button>
          </div> 
          <!-- /.box-body -->
          <div class="box-footer">
            <button type="submit" class="btn btn-primary">Salvar</button>
          </div>
        </form>
      </div>
  	</div>
  </div>
</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script src="/res/admin/dist/js/addInput.js"></script>
