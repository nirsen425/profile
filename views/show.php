<div class="flex-container">
    <div>
        <a href="/profiles" class="button">Список профилей</a>
        <div class="profile-info">
            <div class="title">ФИО</div>
            <div><?= strip_tags($profile['surname'] . ' ' . $profile['name'] . ' ' . $profile['patronymic']) ?></div>
            <div class="title">Почты:</div>
            <?php foreach ($allEmailsByProfile as $email): ?>
                <div class="email">
                    <div><?= strip_tags($email['title']) ?></div>
                    <div class="main"><?= $email['main'] ? 'основная' : '' ?></div>
                </div>
            <?php endforeach; ?>
            <div class="title">Телефоны:</div>
            <?php for ($i = 0; $i < count($allTelephonesByProfile); $i++): ?>
                <div class="telephone">
                    <div><?= strip_tags($allTelephonesByProfile[$i]['title']) ?></div>
                    <div><?= strip_tags($telephoneTypeTitles[$i]) ?></div>
                    <div class="main"><?= $allTelephonesByProfile[$i]['main'] ? 'основной' : '' ?></div>
                </div>
            <?php endfor; ?>
        </div>
    </div>
</div>
<link rel="stylesheet" href="/template/css/show.css">

