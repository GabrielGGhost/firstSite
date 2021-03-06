<?php if(!class_exists('Rain\Tpl')){exit;}?><!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Lista de Receitas
  </h1>
  <ol class="breadcrumb">
    <li><a href="/admin"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active"><a href="/admin/recipes">Receitas</a></li>
  </ol>
</section>

<!-- Main content -->
  <div class="row">
  <section class="content">
  	<div class="col-md-12">
  		<div class="box box-primary">
            <div class="box-header">
              <a href="/admin/recipes/create" class="btn btn-success">Cadastrar receitas</a>
            </div>
            <?php if( $createSuccess != '' ){ ?>
              <div class="alert alert-success">
                <?php echo htmlspecialchars( $createSuccess, ENT_COMPAT, 'UTF-8', FALSE ); ?>
              </div>
            <?php } ?>
            <div class="box-body no-padding">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th style="width: 10px">#</th>
                    <th>Nome</th>
                    <th style="width: 210px">&nbsp;</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $counter1=-1;  if( isset($recipes) && ( is_array($recipes) || $recipes instanceof Traversable ) && sizeof($recipes) ) foreach( $recipes as $key1 => $value1 ){ $counter1++; ?>
                  <tr>
                    <td><?php echo htmlspecialchars( $value1["idRecipe"], ENT_COMPAT, 'UTF-8', FALSE ); ?> </td>
                    <td><?php echo htmlspecialchars( $value1["recipeName"], ENT_COMPAT, 'UTF-8', FALSE ); ?></td>
                    <td>
                      <a href="/admin/recipes/<?php echo htmlspecialchars( $value1["idRecipe"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i> Editar</a>
                      <a href="/admin/recipes/<?php echo htmlspecialchars( $value1["idRecipe"], ENT_COMPAT, 'UTF-8', FALSE ); ?>/images" class="btn btn-secondary btn-xs"><i class="fa fa-edit"></i> Fotos</a>
                      <a href="/admin/recipes/<?php echo htmlspecialchars( $value1["idRecipe"], ENT_COMPAT, 'UTF-8', FALSE ); ?>/des-active" onclick="return confirm('Deseja realmente desativar esta receita?')" class="btn btn-danger btn-xs"><i class="fa fa-power-off"></i> <?php if( $value1["active"] == 1 ){ ?>Desativar<?php }else{ ?>Ativar<?php } ?></a>
                    </td>
                  </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
  	</div>
  </div>

</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->