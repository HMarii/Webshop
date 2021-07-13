

<?=template_header('Bejelentkezés')?>


<div class="regcontent">
    <h2>Bejelentkezési adatok</h2>
    <?php 
				if(isset($errors) && count($errors) > 0)
				{

					foreach($errors as $error_msg)
					{
						echo '<div class="alert alert-danger">'.$error_msg.'</div>';
					}
				}
			?>
    <hr>
        <form action="login.php" method="post">
            <div class="form-input">
            <label for="email">Email cím </label>
            <input type="email" placeholder="janos.koszegi@gmail.com" name="email" required>
            </div>
            <div class="form-input">
            <label for="password">Jelszó </label>
            <input type="password" name="password" placeholder="******" required>
            </div>
                <div class="buttons">
                    <input type="submit" value="Bejelentkezés" name="login">
                </div>

            </div>
        </form>
</div>

<?=template_footer()?>