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
            <?php for ($i = 0; $i < count($profiles); $i++): ?>
                <tr>
                    <td><?= strip_tags($profiles[$i]['surname'] . ' ' . $profiles[$i]['name'] . ' ' . $profiles[$i]['patronymic']) ?></td>
                    <td><?= strip_tags($profileMainEmailTitles[$i]) ?></td>
                    <td><?= strip_tags($profileMainTelephoneTitles[$i]) ?></td>
                    <td>
                        <a href="/profiles/show/<?= strip_tags($profiles[$i]['id']) ?>">Просмотреть</a>
                        <a href="/profiles/edit/<?= strip_tags($profiles[$i]['id']) ?>">Редактировать</a>
                        <a href="/profiles/delete/<?= strip_tags($profiles[$i]['id']) ?>"
                           onclick="return (confirm('Вы уверены, что хотите удалить профиль?'))">Удалить</a>
                    </td>
                </tr>
            <?php endfor; ?>
        </table>
    </div>
</div>

<link rel="stylesheet" href="/template/css/index.css">

