<?php if(!class_exists('Rain\Tpl')){exit;}?><!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Lista de Usuários
  </h1>
  <ol class="breadcrumb">
    <li><a href="/admin"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="/admin/users">Usuários</a></li>
    <li class="active"><a href="/admin/users/create">Cadastrar</a></li>
  </ol>
</section>

<!-- Main content -->
<section class="content">

  <div class="row">
  	<div class="col-md-12">
  		<div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Novo Usuário</h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <form role="form" action="/admin/users/create" method="post">
          <div class="box-body">
             <?php if( $createError != '' ){ ?>
               <div class="alert alert-danger">
                 <?php echo htmlspecialchars( $createError, ENT_COMPAT, 'UTF-8', FALSE ); ?>
               </div>
              <?php } ?>
            <div class="form-group">
              <div class="form-double">
                <label for="desperson">Nome</label> 
                <input type="text" class="form-control" id="desperson" name="name" placeholder="Digite o nome" value="<?php echo htmlspecialchars( $UserRegisterError["name"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" maxlength="25">
              </div>
              <div class="form-double">
              <label for="desperson">Sobrenome</label>
              <input type="text" class="form-control" id="desperson" name="surname" placeholder="Digite o sobrenome" value="<?php echo htmlspecialchars( $UserRegisterError["surname"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" maxlength="50">  
              </div>
            </div>
            <div class="form-group">
              <div class="form-double">
                <label for="deslogin">Login</label>
                <input type="text" class="form-control" id="deslogin" name="login" placeholder="Digite o login" value="<?php echo htmlspecialchars( $UserRegisterError["login"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" maxlength="15">
              </div>
              <div class="form-double">
                <label for="despassword">Senha</label>
                <input type="password" class="form-control" id="despassword" name="password" placeholder="Digite a senha" maxlength="15">
              </div>
            </div>
            <div class="form-group">
              <label for="nrphone">Telefone</label>
              <input type="tel" class="form-control" id="nrphone" name="phone" placeholder="Digite o telefone" value="<?php echo htmlspecialchars( $UserRegisterError["phone"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" maxlength="15">
            </div>
            <div class="form-group">
              <label for="desemail">E-mail</label>
              <input type="email" class="form-control" id="desemail" name="email" placeholder="Digite o e-mail" value="<?php echo htmlspecialchars( $UserRegisterError["email"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" maxlength="126">
            </div>
            <div class="checkbox">
              <label>
                <input type="checkbox" name="inadmin" value="1"> Acesso de Administrador
              </label>
            </div>
          </div>
          <!-- /.box-body -->
          <div class="box-footer">
            <button type="submit" class="btn btn-success">Cadastrar</button>
          </div>
        </form>
      </div>
  	</div>
  </div>

</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->