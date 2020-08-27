<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/template/css/layout.css">
    <link rel="stylesheet" href="/template/css/show.css">
    <title>Document</title>
</head>
<body>
    <div class="flex-container">
        <div>
            <a href="/profiles" class="button">Список профилей</a>
            <div class="profile-info">
                <div class="title">ФИО</div>
                <div><?= $profile['surname'] . ' ' . $profile['name'] . ' ' . $profile['patronymic'] ?></div>
                <div class="title">Почты:</div>
                <?php foreach ($emailModel->getAllEmailsByProfileId($profile['id']) as $email): ?>
                    <div class="email">
                        <div><?= $email['title'] ?></div>
                        <div class="main"><?= $email['main'] ? 'основная' : '' ?></div>
                    </div>
                <?php endforeach; ?>
                <div class="title">Телефоны:</div>
                <?php foreach ($telephoneModel->getAllTelephonesByProfileId($profile['id']) as $telephone): ?>
                    <div class="telephone">
                        <div><?= $telephone['title'] ?></div>
                        <div><?= $telephoneTypeModel->getTitleByTelephoneTypeId($telephone['telephone_type_id']) ?></div>
                        <div class="main"><?= $telephone['main'] ? 'основной' : '' ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</body>
</html>
