<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\plugins\orm\redbeanphp\auth\datainterface;
    /*</namespace.current>*/
/*<namespace.use>*/
use gear\auth\datainterface\IGearAuthDataInterface;
use gear\auth\GearAuthenticationManager;
use gear\auth\IGearAuthUser;
use gear\data\orm\redbeanphp\entity\GearRedBeanEntity;
use gear\plugins\orm\redbeanphp\auth\entity\GearRedBeanAuthUser;
use gear\plugins\orm\redbeanphp\datainterface\GearRedBeanDataInterface;

/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/

class GearRedBeanAuthDataInterface implements IGearAuthDataInterface
{
    private
        $namespace,
        $entityName;

    /**
     * GearRedBeanAuthDataInterface constructor.
     * @param $namespace string
     * @param $entityName string
     */
    public function __construct($namespace, $entityName)
    {
        $this->namespace = $namespace;
        $this->entityName = $entityName;
    }

    /**
     * @param GearRedBeanAuthUser $userModel
     * @param $password
     *
     * @return mixed|void
     */
    public function createUser($userModel, $password)
    {
        $userModel->setPasswordHash($password);

        $service = new GearRedBeanDataInterface($this->entityName);
        try {
            $service->insert($userModel);
        } finally {
            $service->dispose();
        }

        return $userModel;
    }

    public function findUsernameAndPassword($username, $password, &$reason)
    {
        $service = new GearRedBeanDataInterface($this->entityName);
        try {
            /** @var $user IGearAuthUser */
            $user = $service->query()
                ->isEqual('username', $username)
                ->selectOne();
        } finally {
            $service->dispose();
        }

        if ($user == null) {
            $reason = GearAuthenticationManager::AuthUserNotFound;
            return null;
        } else {
            $pass = $user->getPassword();

            if ($pass == $password) {
                $reason = GearAuthenticationManager::AuthUserSuccessful;
                return $user;
            } else {
                $reason = GearAuthenticationManager::AuthUserWrongPassword;
                return null;
            }
        }
    }

    public function findUsername($username)
    {
        $service = new GearRedBeanDataInterface($this->entityName);
        try {
            /** @var $user IGearAuthUser */
            $user = $service->query()
                ->isEqual('username', $username)
                ->selectOne();
        } finally {
            $service->dispose();
        }

        if ($user == null) {
            $reason = GearAuthenticationManager::AuthUserNotFound;
            return null;
        } else {
            $reason = GearAuthenticationManager::AuthUserSuccessful;
            return $user;
        }
    }
}

/*</module>*/
?>