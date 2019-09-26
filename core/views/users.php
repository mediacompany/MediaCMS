<?php include 'header.php'; ?>
<div class="col-sm-6">
	<h1 class="section_title"><?php echo $section_title; ?></h1>
</div>
<div class="col-sm-6">
	<a href="<?php echo SITE; ?>/admin/user/0" class="btn btn_new"><i class="fa fa-plus" aria-hidden="true"></i> Nuevo Usuario</a>
</div>
<hr>
<div class="row custom_table">
	<div class="col-sm-12 table_element table_element_header text-left">
        <div class="col-sm-1">ID</div>
        <div class="col-sm-2">Nombre</div>
        <div class="col-sm-3">Email</div>
        <div class="col-sm-2">Rol</div>
        <div class="col-sm-2">Registrado</div>
        <div class="col-sm-2">Acciones</div>
    </div>
    <?php
    if (!empty($core->get_all('users'))) {
        foreach ($core->get_all('users') as $user) { ?>
        	<div class="col-sm-12 table_element text-left" id="delitem<?php echo $user->ID; ?>">
        	    <div class="col-sm-1"><a href="<?php echo SITE.'/admin/user/'.$user->ID; ?> " class="btn"><?php echo $user->ID; ?>#</a></div>
        	    <div class="col-sm-2"><?php echo $user->user_name; ?></div>
        	    <div class="col-sm-3"><?php echo $user->user_email; ?></div>
                <div class="col-sm-2"><?php echo $roles[$user->user_level]; ?></div>
                <div class="col-sm-2"><?php echo date('d/m/Y',strtotime($user->user_register)); ?></div>
        	    <div class="col-sm-2 actions_col">
        	    	<a href="#" class="btn btn_delete media-table-delete-item" data-todelete="<?php echo $user->ID; ?>:ID,users,delitem<?php echo $user->ID; ?>,¿Deseas eliminar este Usuario?"><i class="fa fa-trash" aria-hidden="true"></i></a>
        	    	<a href="<?php echo SITE.'/admin/user/'.$user->ID; ?> " class="btn"><i class="fa fa-pencil" aria-hidden="true"></i></a>
        	    </div>
        	</div>
        <?php } 
    }else{
        echo "No users on DB";
    }
?>
</div>
<?php include 'footer.php'; ?>