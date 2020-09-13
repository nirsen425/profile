<?php
/**
 * Класс контроллер профилей
 */

class ProfileController
{
    /** @var Profile */
    private $profile;
    /** @var Email */
    private $email;
    /** @var Telephone */
    private $telephone;
    /** @var TelephoneType */
    private $telephoneType;

    /**
     * Конструктор ProfileController
     */
    public function __construct()
    {
        $this->profile = new Profile();
        $this->email = new Email();
        $this->telephone = new Telephone();
        $this->telephoneType = new TelephoneType();
    }

    /**
     * Показывает список всех профилей
     *
     * @return bool
     */
    public function actionIndex()
    {
        // Получение данных для отрисовки в шаблоне
        $profiles = $this->profile->getAllProfiles();

        foreach ($profiles as $profile) {
            $profileMainEmailTitles[] = $this->email->getMainEmailTitleByProfileId($profile['id']);
            $profileMainTelephoneTitles[] = $this->telephone->getMainTelephoneTitleByProfileId($profile['id']);
        }

        // Отрисовка страницы шаблонизатором
        $content = View::render('index', [
            'profiles' => $profiles,
            'profileMainEmailTitles' => $profileMainEmailTitles,
            'profileMainTelephoneTitles' => $profileMainTelephoneTitles
        ]);

        echo View::render('layout', [
            'content' => $content,
            'title' => 'Главная'
        ]);

        return true;
    }

    /**
     * Показывает страницу создания профиля
     *
     * @return bool
     */
    public function actionCreate()
    {
        // Стартуем сессию для вывода в шаблоне ошибок и ранее введенных данных из сессии
        Helper::startSession();

        // Отрисовка страницы шаблонизатором
        $content = View::render('create');

        echo View::render('layout', [
            'content' => $content,
            'title' => 'Создание'
        ]);

        return true;
    }

    /**
     * Сохраняет полностью заполненный профиль(ФИО, email'ы, телефоны)
     *
     * @return bool
     * @throws Exception
     */
    public function actionStore()
    {
        // Сохраняем информацию из формы в сессию, для вывода ее в форме, в случае непрохождения валидации
        Session::saveFormDataInSession('profile-create');

        // Очищаем post информацию от пустых пробелов, переводим теги в html эквиваленты, валидируем
        $clearData = Helper::getClearData($_POST);
        $errors = (new CreateProfileFormValidator($clearData))->validateData();

        if (isset($errors)) {
            // Записываем ошибки в сессию для дальнешего вывода на странице и перенаправляем обратно
            Session::writeArrayInSessionByKey($errors, 'errors');
            header("Location: {$_SERVER['HTTP_REFERER']}");
        } else {
            // Сохраняем отвалидированные данные профиля
            $hasBeenSaved = $this->profile->saveFullyCompletedProfile($clearData);

            if ($hasBeenSaved) {
                // Удаляем из сессии информацию для вывода в форме, в случае непрохождения валидации
                Session::deleteFormDataInSession('profile-create');
            }
            header("Location: /profiles");
        }

        return true;
    }

    /**
     * Показывает страницу редактирования профиля
     *
     * @param integer $profileId id профиля
     * @return bool
     */
    public function actionEdit($profileId)
    {
        // Стартуем сессию для вывода в шаблоне ошибок из сессии
        Helper::startSession();

        // Получение данных для отрисовки в шаблоне
        $profile = $this->profile->getProfileById($profileId);

        if (!$profile) {
            Helper::abort();
        }

        $allEmailsByProfile = $this->email->getAllEmailsByProfileId($profileId);
        $allTelephonesByProfile = $this->telephone->getAllTelephonesByProfileId($profileId);

        // Отрисовка страницы шаблонизатором
        $content = View::render('edit', [
            'profile' => $profile,
            'allEmailsByProfile' => $allEmailsByProfile,
            'allTelephonesByProfile' => $allTelephonesByProfile
        ]);

        echo View::render('layout', [
            'content' => $content,
            'title' => 'Редактирование'
        ]);

        return true;
    }

    /**
     * Полностью обновляет профиль(ФИО, email'ы, телефоны)
     *
     * @param integer $profileId id профиля
     * @return bool
     * @throws Exception
     */
    public function actionUpdate($profileId)
    {
        // Очищаем post информацию от пустых пробелов, переводим теги в html эквиваленты, валидируем
        $clearData = Helper::getClearData($_POST);
        $errors = (new UpdateProfileFormValidator($clearData, $profileId))->validateData();

        if (isset($errors)) {
            // Записываем ошибки в сессию для дальнешего вывода на странице и перенаправляем обратно
            Session::writeArrayInSessionByKey($errors, 'errors');
            header("Location: {$_SERVER['HTTP_REFERER']}");
        } else {
            // Обновляем отвалидированные данные профиля
            $this->profile->updateFullyCompletedProfile($profileId, $clearData);

            header("Location: /profiles");
        }

        return true;
    }

    /**
     * Показывает страницу конкретного профиля
     *
     * @param integer $profileId id профиля
     * @return bool
     */
    public function actionShow($profileId)
    {
        // Получение данных для отрисовки в шаблоне
        $profile = $this->profile->getProfileById($profileId);

        if (!$profile) {
            Helper::abort();
        }

        $allEmailsByProfile = $this->email->getAllEmailsByProfileId($profileId);
        $allTelephonesByProfile = $this->telephone->getAllTelephonesByProfileId($profileId);
        foreach ($allTelephonesByProfile as $telephone) {
            $telephoneTypeTitles[] = $this->telephoneType->getTitleByTelephoneTypeId($telephone['telephone_type_id']);
        }

        // Отрисовка страницы шаблонизатором
        $content = View::render('show', [
            'profile' => $profile,
            'allEmailsByProfile' => $allEmailsByProfile,
            'allTelephonesByProfile' => $allTelephonesByProfile,
            'telephoneTypeTitles' => $telephoneTypeTitles
        ]);

        echo View::render('layout', [
            'content' => $content,
            'title' => 'Просмотр'
        ]);

        require_once ROOT . '/views/show.php';
        return true;
    }

    /**
     * Полностью удаляет профиль, связанные с ним email'ы и телефоны
     *
     * @param integer $profileId id профиля
     * @return bool
     * @throws Exception
     */
    public function actionDelete($profileId)
    {
        $this->profile->deleteFullyCompletedProfile($profileId);
        header("Location: /profiles");

        return true;
    }
}