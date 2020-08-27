<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/template/css/layout.css">
    <link rel="stylesheet" href="/template/css/index.css">
    <title>Document</title>
</head>
<body>
    <div class="wrapper">
        <div class="flex-container">
            <div>
                <a href="/profiles/create" class="button">Создать профиль</a>
                <table>
                    <tr>
                        <th>ФИО</th>
                        <th>Основной email</th>
                        <th>Основной телефон</th>
                        <th>Действия</th>
                    </tr>
                    <?php foreach ($profiles as $profile): ?>
                    <tr>
                        <td><?= $profile['surname'] . ' ' . $profile['name'] . ' ' . $profile['patronymic'] ?></td>
                        <td><?= $emailModel->getMainEmailTitleByProfileId($profile['id']) ?></td>
                        <td><?= $telephoneModel->getMainTelephoneTitleByProfileId($profile['id']) ?></td>
                        <td>
                            <a href="/profiles/show/<?= $profile['id'] ?>">Просмотреть</a>
                            <a href="/profiles/edit/<?= $profile['id'] ?>">Редактировать</a>
                            <a href="/profiles/delete/<?= $profile['id'] ?>"
                               onclick="return (confirm('Вы уверены, что хотите удалить профиль?'))">Удалить</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
