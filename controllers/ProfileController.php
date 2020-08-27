<?php


class ProfileController
{
    private $profile;

    public function __construct()
    {
        $this->profile = new Profile();
    }

    public function actionIndex()
    {
        $profiles = $this->profile->getAllProfiles();
        $emailModel = new Email();
        $telephoneModel = new Telephone();
        require_once ROOT . '/views/index.php';
        return true;
    }

    public function actionCreate()
    {
        Helper::startSession();
        require_once ROOT . '/views/create.php';

        return true;
    }

    public function actionStore()
    {
        $clearData = Helper::getClearData($_POST);
        $this->profile->saveFullyCompletedProfile($clearData);
        header("Location: /profiles");

        return true;
    }

    public function actionEdit($profileId)
    {
        Helper::startSession();
        $profile = $this->profile->getProfileById($profileId);
        if (!$profile) {
            Helper::abort();
        }
        $emailModel = new Email();
        $telephoneModel = new Telephone();
        $telephoneTypeModel = new TelephoneType();

        require_once ROOT . '/views/edit.php';

        return true;
    }

    public function actionUpdate($profileId)
    {
        $clearData = Helper::getClearData($_POST);
        $this->profile->updateFullyCompletedProfile($profileId, $clearData);
        header("Location: /profiles");

        return true;
    }

    public function actionShow($profileId)
    {
        $profile = $this->profile->getProfileById($profileId);
        if (!$profile) {
            Helper::abort();
        }
        $emailModel = new Email();
        $telephoneModel = new Telephone();
        $telephoneTypeModel = new TelephoneType();
        require_once ROOT . '/views/show.php';
        return true;
    }

    public function actionDelete($profileId)
    {
        $this->profile->deleteFullyCompletedProfile($profileId);
        header("Location: /profiles");

        return true;
    }
}