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
            <form action="/profiles/update/<?= strip_tags($profile['id']) ?>" method="POST" class="form-profile">
                <div class="form-profile-inner">
                    <div class="full-name-container">
                        <label for="name">Имя</label>
                        <input type="text" name="name" id="name" value="<?= strip_tags($profile['name']) ?>">
                        <label for="patronymic">Отчество</label>
                        <input type="text" name="patronymic" id="patronymic"
                               value="<?= strip_tags($profile['patronymic']) ?>">
                        <label for="surname">Фамилия</label>
                        <input type="text" name="surname" id="surname" value="<?= strip_tags($profile['surname']) ?>">
                        <label for="email">Email</label>
                    </div>
                    <?php
                    $emailNumber = 0;
                    foreach ($allEmailsByProfile as $email):
                        ?>
                        <div class="email-container">
                            <input type="text" name="email[]" id="email" value="<?= strip_tags($email['title']) ?>">
                            <input type="radio" name="main-email" value="<?= $emailNumber ?>" <?= $email['main'] ? 'checked' : '' ?>>
                            <b>выбрать основным</b>
                        </div>
                        <?php
                        $emailNumber++;
                    endforeach;
                    ?>
                    <span class="add-email">Добавить email</span>
                    <span class="delete-email">Удалить email</span>
                    <div>
                        <label for="telephone">Телефон</label>
                    </div>
                    <?php
                    $accountPhoneNumber = 0;
                    foreach ($allTelephonesByProfile as $telephone):
                        ?>
                        <div class="telephone-container">
                            <input type="text" name="telephone[]" id="telephone" placeholder="8**********" value="<?= $telephone['title'] ?>">
                            <select name="telephone-type[]">
                                <option value="1" <?= $telephone['telephone_type_id'] == 1 ? 'selected' : '' ?>>Мобильный</option>
                                <option value="2" <?= $telephone['telephone_type_id'] == 2 ? 'selected' : '' ?>>Рабочий</option>
                                <option value="3" <?= $telephone['telephone_type_id'] == 3 ? 'selected' : '' ?>>Домашний</option>
                            </select>
                            <input type="radio" name="main-telephone" value="<?= $accountPhoneNumber ?>" <?= $telephone['main'] ? 'checked' : '' ?>>
                            <b>выбрать основным</b>
                        </div>
                        <?php
                        $accountPhoneNumber++;
                    endforeach;
                    ?>
                    <span class="add-telephone">Добавить телефон</span>
                    <span class="delete-telephone">Удалить телефон</span>
                    <input type="submit" value="Редактировать профиль" class="button">
                </div>
            </form>
        </div>
    </div>
</div>

<link rel="stylesheet" href="/template/css/edit.css">
<script defer src="/template/js/add-email.js"></script>
<script defer src="/template/js/add-telephone.js"></script>
<script defer src="/template/js/delete-email.js"></script>
<script defer src="/template/js/delete-telephone.js"></script>

