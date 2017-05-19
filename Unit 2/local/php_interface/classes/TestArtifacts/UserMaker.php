<?php
/**
 * @author Maxim Sokolovsky <sokolovsky@worksolutions.ru>
 */

namespace Domain\TestArtifacts;

class UserMaker {

    private $email;

    public function __construct($email) {
        $this->email = $email;
    }

    public function groups() {
    }

    public function name() {
    }

    /**
     * @return int
     * @throws \Exception
     */
    public function create() {
        $gw = new \CUser();
         $id = $gw->Add(
            array(
                "LOGIN" => $this->email,
                "EMAIL" => $this->email,
                "ACTIVE" => "Y",
                "PASSWORD" => $this->password(),
                "CONFIRM_PASSWORD" => $this->password()
            )
        );
         if (!$id) {
             throw new \Exception("User {$this->email} didn't create, error {$gw->LAST_ERROR}");
         }
        return $id;
    }
    
    public function get() {
        $arUser = \CUser::GetByLogin($this->email)->Fetch();
        if ($arUser) {
            return $arUser['ID'];
        }
        return $this->create();
    }

    /**
     * @return bool|string
     */
    private function password() {
        return substr(md5($this->email), 0, 10);
    }
}
