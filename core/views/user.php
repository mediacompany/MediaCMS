<?php include 'header.php'; ?>
<div class="row">
    <div class="col-sm-6">
        <h1 class="section_title"><?php echo $section_title; ?></h1>
    </div>
    <div class="col-md-offset-1 col-md-10 white_box">
        <form method="post">
            <div class="form-element col-sm-4">
                <label>Nombre</label>
                <input type="text" name="user_name" value="<?php echo $data->user_name; ?>" required>
            </div>
            <div class="form-element col-sm-4">
                <label>Email</label>
                <input type="email" name="user_email" value="<?php echo $data->user_email; ?>" required>
            </div>
            <div class="form-element col-sm-4">
                <label>Teléfono</label>
                <input type="text" name="user_phone" value="<?php echo $data->user_phone; ?>" required>
            </div>
            <div class="form-element col-sm-3">
                <label>Cumpleaños</label>
                <input type="text" name="user_birthday" class="datepicker-here" data-language='es' data-date-format="yyyy-mm-dd" value="<?php echo $data->user_birthday; ?>" 
                auto-complete="off"
                required>
            </div>
            <div class="form-element col-sm-3">
                <label>Rol</label>
                <div class="form-select">
                    <select name="user_level">
                        <?php foreach (ROL as $key => $value) { ?>
                            <option value="<?php echo $key; ?>" <?php selected($data->user_level,$key); ?>><?php echo $value; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="form-element col-sm-3">
                <label>Usuario</label>
                <input type="text" name="user_user" value="<?php echo $data->user_user; ?>" required autocomplete="new-user">
            </div>
            <div class="form-element col-sm-3">
                <label>Contraseña</label>
                <input type="password" name="user_password" value="" autocomplete="new-password" <?php echo ($id == 0)? 'required':''; ?>>
            </div>
            <div class="form-element col-sm-12">
                <label>Información Extra</label>
                <textarea name="user_info" onkeyup="textAreaAdjust(this)"><?php echo $data->user_info; ?></textarea>
            </div>
            <div class="form-element radio radio-group col-sm-2 resource_mod">
                <label>Activo</label>
                <input type="radio" name="user_ustate" value="1" id="user_ustate1" required <?php checked($data->user_ustate,1); ?>>
                <label for="user_ustate1">SI</label>
                <input type="radio" name="user_ustate" value="2" id="user_ustate2" required <?php checked($data->user_ustate,2); ?>>
                <label for="user_ustate2">NO</label>
            </div>
            <div class="form-element mt20 col-sm-12">
                <button type="submit" class="btn btn_primary center-block">Guardar</button>
            </div>
        </form>
    </div>
</div>
<?php include 'footer.php'; ?>