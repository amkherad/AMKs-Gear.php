<?php
//$SOURCE_LICENSE$

/*<requires>*/
//IGearAuthDataInterface
//GearAuthenticationResult
//IGearAuthSessionService
//IGearAuthCookieService
//IGearPasswordHashProvider
/*</requires>*/

/*<namespace.current>*/
namespace gear\auth;
    /*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\core\GearInvalidOperationException;
use gear\arch\core\IGearClassLoader;
use gear\auth\datainterface\IGearAuthDataInterface;
use gear\auth\meta\GearAuthenticationResult;
use gear\auth\services\GearDefaultPasswordHashProvider;
use gear\auth\services\IGearAuthSessionService;
use gear\auth\services\IGearAuthCookieService;
use gear\auth\services\IGearPasswordHashProvider;
use gear\auth\services\IGearAuthSuccessfulHandler;
use gear\auth\services\GearDefaultWebSuccessfulHandler;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class GearAuthenticationManager
{
    const PasswordHashAlgorithm = 'sha256';
    const AuthUserNotFound = 'user-not-found';
    const AuthUserWrongPassword = 'wrong-password';
    const AuthUserSuccessful = 'success';

    /** @var IGearClassLoader */
    private $userEntityLoader;
    /** @var IGearAuthDataInterface */
    private $dataInterface;
    /** @var IGearPasswordHashProvider */
    private $passwordHashProvider;

    /** @var IGearAuthSuccessfulHandler */
    private $successfulHandler;

    /**
     * Creates an instance of GearAuthManager
     *
     * @param $userEntityLoader IGearClassLoader Creates a new instance of user entity.
     * @param $dataInterface IGearAuthDataInterface Provides an abstract layer to read and store data in database.
     * @param $passHashProvider IGearPasswordHashProvider Provides a mechanism to create hash-code from password.
     * @param $successfulHandler IGearAuthSuccessfulHandler Provides a callback when authentication is successful.
     *
     * @throws GearInvalidOperationException
     */
    public function __construct(
        $userEntityLoader,
        $dataInterface,
        $passHashProvider = null,
        $successfulHandler = null)
    {
        if ($dataInterface == null) {
            throw new GearInvalidOperationException();
        }

        $this->userEntityLoader = $userEntityLoader;

        $this->dataInterface = $dataInterface;

        $this->successfulHandler = $successfulHandler == null
            ? new GearDefaultWebSuccessfulHandler()
            : $successfulHandler;

        $this->passwordHashProvider = $passHashProvider == null
            ? new GearDefaultPasswordHashProvider(self::PasswordHashAlgorithm)
            : $passHashProvider;
    }

    /**
     * Returns underlying data interface.
     *
     * @return IGearAuthDataInterface
     */
    public function getDataInterface()
    {
        return $this->dataInterface;
    }

    /**
     * @param $username string
     * @param $password string
     * @param $params array dictionary of custom parameters.
     *
     * @return GearAuthenticationResult
     */
    public function login($username, $password, $params)
    {
        $passwordHash = $this->passwordHashProvider->getPasswordUsernameHash($password, $username);

        $reason = '';
        $user = $this->dataInterface->findUsernameAndPassword($username, $passwordHash, $reason);

        $result = $this->_login($user, $reason, $username, $password, $params);
        return $result;
    }

    /**
     * Registers a new user with given arguments.
     * @param $username
     * @param $password
     * @param $params
     *
     * @return GearAuthenticationResult
     */
    public function register($username, $password, $params)
    {
        $passwordHash = $this->passwordHashProvider->getPasswordUsernameHash($password, $username);

        $user = $this->userEntityLoader->createInstance([
            'username' => $username,
            'password' => $password,
            'params' => $params
        ]);

        $user->username = $username;
        $user->passwordHash = $passwordHash;

        $reason = '';
        $user = $this->dataInterface->createUser($user, $reason);

        $result = $this->_login($user, $reason, $username, $password, $params);
        return $result;
    }

    private function _login($user, $reason, $username, $password, $params)
    {
        if ($user == null) {
            return new GearAuthenticationResult(
                false,
                "User with specified username and password has not found.",
                $reason,
                null,
                $user
            );
        } else {
            $result = $this->successfulHandler->onAuthSuccessful($user, $username, $password, $params);
            return new GearAuthenticationResult(
                true,
                "Authentication is successful.",
                self::AuthUserSuccessful,
                $result,
                $user
            );
        }
    }
}
/*</module>*/
?>