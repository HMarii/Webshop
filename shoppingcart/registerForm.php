<?=template_header('Regisztráció')?>


<div class="regcontent">
    <h2>Regisztrációs adatok</h2>
    <hr>
        <form action="register.php" method="post">
            <div class="form-input">
            <label for="email">Email cím </label>
            <input type="email" name="email" placeholder="janos.koszegi@gmail.com" required>
            </div>
            <div class="form-input">
            <label for="password">Jelszó </label>
            <input type="password" name="password" placeholder="******" required>
            </div>
            <div class="form-input">
            <label for="fullname">Teljes név </label>
            <input type="text" name="fullname" placeholder="Bevásárló János" required>
            </div>
            <div class="form-input">
            <label for="address">Lakcím </label>
            <input type="text" name="address" placeholder="Árpád tér 1" required>

                <div class="buttons">
                    <input type="submit" value="Regisztráció" name="register">
                </div>

            </div>
        </form>
</div>

<?=template_footer()?>