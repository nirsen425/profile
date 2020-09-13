<div class="flex-container">
    <div>
        <a href="/profiles" class="button">Список профилей</a>
        <div class="form-container">
            <div class="errors">
                <?php
                if (isset($_SESSION['errors'])):
                    foreach ($_SESSION['errors'] as $error):
                        ?>
                        <div class="error"><?= $error ?></div>
                    <?php   endforeach;
                    unset($_SESSION['errors']);
                endif;
                ?>
            </div>
            <form action="/profiles/store" method="POST" class="form-profile">
                <div class="full-name-container">
                    <label for="name">Имя</label>
                    <input type="text" name="name" id="name" value="<?= strip_tags($_SESSION['profile-create']['name']) ?? '' ?>">
                    <label for="patronymic">Отчество</label>
                    <input type="text" name="patronymic" id="patronymic"
                           value="<?= strip_tags($_SESSION['profile-create']['patronymic']) ?? '' ?>">
                    <label for="surname">Фамилия</label>
                    <input type="text" name="surname" id="surname" value="<?= strip_tags($_SESSION['profile-create']['surname']) ?? '' ?>">
                    <label for="email">Email</label>
                </div>
                <?php
                if (isset($_SESSION['profile-create']['email']) and count($_SESSION['profile-create']['email']) > 0):
                    foreach ($_SESSION['profile-create']['email'] as $emailNumber => $email):
                        ?>
                        <div class="email-container">
                            <input type="text" name="email[]" id="email" value="<?= strip_tags($email) ?>">
                            <input type="radio" name="main-email"
                                   value="<?= $emailNumber ?>"
                                <?php
                                if (isset($_SESSION['profile-create']['main-email'])):
                                    echo $_SESSION['profile-create']['main-email'] == $emailNumber ? 'checked' : '';
                                endif;
                                ?>>
                            <b>выбрать основным</b>
                        </div>
                    <?php
                    endforeach;
                else:
                    ?>
                    <div class="email-container">
                        <input type="text" name="email[]" id="email">
                        <input type="radio" name="main-email" value="0" checked>
                        <b>выбрать основным</b>
                    </div>
                <?php
                endif;
                ?>
                <span class="add-email">Добавить email</span>
                <span class="delete-email">Удалить email</span>
                <div>
                    <label for="telephone">Телефон</label>
                </div>
                <?php
                if (isset($_SESSION['profile-create']['telephone']) and count($_SESSION['profile-create']['telephone']) > 0):
                    foreach ($_SESSION['profile-create']['telephone'] as $accountPhoneNumber => $telephone):
                        ?>
                        <div class="telephone-container">
                            <input type="text" name="telephone[]" id="telephone" placeholder="8**********" value="<?= strip_tags($telephone) ?>">
                            <select name="telephone-type[]">
                                <option value="1" <?= $_SESSION['profile-create']['telephone-type'][$accountPhoneNumber] == 1 ? 'selected' : ''?>>Мобильный</option>
                                <option value="2" <?= $_SESSION['profile-create']['telephone-type'][$accountPhoneNumber] == 2 ? 'selected' : ''?>>Рабочий</option>
                                <option value="3" <?= $_SESSION['profile-create']['telephone-type'][$accountPhoneNumber] == 3 ? 'selected' : ''?>>Домашний</option>
                            </select>
                            <input type="radio" name="main-telephone" value="<?= $accountPhoneNumber ?>"
                                <?php
                                if (isset($_SESSION['profile-create']['main-telephone'])):
                                    echo $_SESSION['profile-create']['main-telephone'] == $accountPhoneNumber ? 'checked' : '';
                                endif;
                                ?>>
                            <b>выбрать основным</b>
                        </div>
                    <?php
                    endforeach;
                else:
                    ?>
                    <div class="telephone-container">
                        <input type="text" name="telephone[]" id="telephone" placeholder="8**********">
                        <select name="telephone-type[]">
                            <option value="1">Мобильный</option>
                            <option value="2">Рабочий</option>
                            <option value="3">Домашний</option>
                        </select>
                        <input type="radio" name="main-telephone" value="0" checked>
                        <b>выбрать основным</b>
                    </div>
                <?php
                endif;
                ?>
                <span class="add-telephone">Добавить телефон</span>
                <span class="delete-telephone">Удалить телефон</span>
                <input type="submit" value="Создать профиль" class="button">
            </form>
        </div>
    </div>
</div>

<link rel="stylesheet" href="/template/css/create.css">
<script defer src="/template/js/add-email.js"></script>
<script defer src="/template/js/add-telephone.js"></script>
<script defer src="/template/js/delete-email.js"></script>
<script defer src="/template/js/delete-telephone.js"></script>


