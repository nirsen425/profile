<?php

/**
 * Класс модель Email
 */
class Email
{
    /** @var PDO */
    private $db;

    /**
     * Email конструктор
     */
    public function __construct()
    {
        $this->db = Db::getConnection();
    }

    /**
     * Сохранение нескольких email'ов для профиля
     *
     * @param integer $profileId id профиля
     * @param array $emailsList список email'ов
     * @param integer $mainEmailNumber значение ключа основного email'а в массиве $emailsList
     */
    public function saveEmails($profileId, $emailsList, $mainEmailNumber)
    {
        $query = "INSERT INTO emails (title, main, profile_id) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($query);
        foreach ($emailsList as $number => $email) {
            //> Определение главного email'а
            $isMain = 0;
            if ($number  == $mainEmailNumber) {
                $isMain = 1;
            }
            //<
            $stmt->execute([$email, $isMain, $profileId]);
        }
    }

    /**
     * Возвращает имя основного email'а для профиля
     *
     * @param integer $profileId id профиля
     * @return string имя основного email'а для профиля
     */
    public function getMainEmailTitleByProfileId($profileId)
    {
        $query = "SELECT title FROM emails WHERE profile_id = ? and main = 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(1, $profileId, PDO::PARAM_INT);
        $stmt->execute();
        $emailTitle = $stmt->fetch(PDO::FETCH_ASSOC)['title'];

        return $emailTitle;
    }

    /**
     * Возвращает массив email'ов для профиля
     *
     * @param integer $profileId id профиля
     * @return array массив email'ов для профиля
     */
    public function getAllEmailsByProfileId($profileId)
    {
        $query = "SELECT id, title, main FROM emails WHERE profile_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(1, $profileId, PDO::PARAM_INT);
        $stmt->execute();
        $emails = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $emails;
    }

    /**
     * Обновляет, удаляет или добавляет email'ы
     *
     * Если email'а нет в бд для профиля, а в $emailList он есть - email добавляется в бд
     * Если email'а нет в бд для профиля, а в массиве $emailsList он есть - email удаляется из бд
     *
     * @param integer $profileId id профиля
     * @param array $emailsList список email'ов
     * @param integer $mainEmailNumber значение ключа основного email'а в массиве $emailsList
     */
    public function changeEmailTable($profileId, $emailsList, $mainEmailNumber)
    {
        $emailsByProfileId = $this->getAllEmailsByProfileId($profileId);

        /*
         * Получаем массив имен email'ов, соответствуйющий структуре $emailsList
         * для дальнешего использования в array_diff(), array_intersect().
         */
        $emailsTitle = array_map(function ($email) {
            return $email['title'];
        }, $emailsByProfileId);

        /*
         * Находим email'ы на удаление, добавление, оставшиеся обновляем для
         * изменеия статуса главного email'а
         */
        $emailsForDelete = array_diff($emailsTitle, $emailsList);
        $this->deleteEmailsByTitle($emailsForDelete);

        $emailsForUpdate = array_intersect($emailsList, $emailsTitle);
        $this->changeMainEmail($emailsForUpdate, $mainEmailNumber);

        $emailsForAdd = array_diff($emailsList, $emailsTitle);
        $this->saveEmails($profileId, $emailsForAdd, $mainEmailNumber);
    }

    /**
     * Удаляет все email'ы для профиля
     *
     * @param integer $profileId id профиля
     */
    public function deleteEmails($profileId)
    {
        $query = "DELETE FROM emails WHERE profile_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(1, $profileId, PDO::PARAM_INT);
        $stmt->execute();
    }

    /**
     * Изменяет свойство main у существующих в бд email'ов
     *
     * @param array $emailsList список email'ов
     * @param integer $mainEmailNumber значение ключа основного email'а в массиве $emailsList
     */
    private function changeMainEmail($emailsList, $mainEmailNumber)
    {
        $query = "UPDATE emails SET main = ? WHERE title = ?";
        $stmt = $this->db->prepare($query);

        foreach ($emailsList as $number => $email) {
            //> Определение главного email'а
            $isMain = 0;
            if ($number  == $mainEmailNumber) {
                $isMain = 1;
            }
            //<

            $stmt->execute([$isMain, $email]);
        }
    }

    /**
     * Удаляет email'ы по заголовку
     *
     * @param array $emailList список email'ов
     */
    public function deleteEmailsByTitle($emailList)
    {
        $query = "DELETE FROM emails WHERE title = ?";
        $stmt = $this->db->prepare($query);

        foreach ($emailList as $emailForDelete) {
            $stmt->bindValue(1, $emailForDelete, PDO::PARAM_STR);
            $stmt->execute();
        }
    }
}