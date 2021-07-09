<?=template_header('Regisztráció')?>


<div class="regcontent">
    <h2>Regisztrációs adatok</h2>
    <hr>
        <form action="submit">
            <div class="form-input">
            <label for="email">Email cím </label>
            <input type="email" placeholder="janos.koszegi@gmail.com" required>
            </div>
            <div class="form-input">
            <label for="password">Jelszó </label>
            <input type="password" placeholder="******" required>
            </div>
            <div class="form-input">
            <label for="fullname">Teljes név </label>
            <input type="text" placeholder="Bevásárló János" required>
            </div>
            <div class="form-input">
            <label for="address">Lakcím </label>
            <input type="text" placeholder="Árpád tér 1" required>
            </div>
        </form>
</div>

<?=template_footer()?>