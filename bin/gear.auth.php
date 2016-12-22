<?php
//Bundle: Gear.Authentication

/* Dependencies: */


/* Modules: */
class GearAuthenticationResult
{
    /** @var bool */
    var $isSuccessful;
    /** @var string */
    var $message;
    /** @var string */
    var $reason;
    /** @var object */
    var $result;
    /** @var IGearAuthUser */
    var $user;

    /**
     * Creates an instance of GearAuthenticationResult
     *
     * @param $isSuccessful bool Indicates the result of authentication.
     * @param $message string Provides a message for end-user
     * @param $reason string Provides a reason for status query.
     * @param $result string Stores the result returned by successful handler.
     * @param $user IGearAuthUser Stores the user retrieved from database for login and register.
     */
    public function __construct($isSuccessful, $message, $reason, $result, $user)
    {
        $this->isSuccessful = $isSuccessful;
        $this->message = $message;
        $this->reason = $reason;
        $this->result = $result;
        $this->user = $user;
    }

    /**
     * Indicates that an authentication is successful.
     *
     * @return bool
     */
    public function getIsSuccessful()
    {
        return $this->isSuccessful;
    }

    /**
     * Gets the end-user message.
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Determines the reason of unsuccessful authentication, invalid-username or invalid-password.
     *
     * @return string
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * Returns pure authentication result.
     *
     * @return object
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @return IGearAuthUser
     */
    public function getUser()
    {
        return $this->user;
    }
}
class GearDefaultPasswordHashProvider implements IGearPasswordHashProvider
{
    /** @var string */
    private $algorithm;

    /**
     * GearDefaultPasswordHashProvider constructor.
     * @param $algorithm string Sets the hashing algorithm.
     */
    public function __construct($algorithm)
    {
        $this->algorithm = $algorithm;
    }

    public function getPasswordUsernameHash($password, $username)
    {
        return hash($this->algorithm, "$password|$username", false);
    }
    public function getPasswordHash($password)
    {
        return hash($this->algorithm, $password, false);
    }
}
interface IGearAuthCookieService
{
    /**
     * @param $name string
     * @param $value mixed
     * @return mixed
     */
    function setRawCookieVariable($name, $value);

    /**
     * @param $name string
     * @return mixed
     */
    function getRawCookieVariable($name);
}
interface IGearAuthDataInterface
{
    /**
     * Creates a user.
     * @param $userModel IGearAuthUser
     * @param $reason
     * @return IGearAuthUser
     */
    function createUser($userModel, &$reason);

    /**
     * Finds a user with given username and password.
     * @param $username
     * @param $password
     * @param $reason
     * @return mixed
     */
    function findUsernameAndPassword($username, $password, &$reason);

    /**
     * Finds a user with given username.
     * @param $username
     * @param $reason
     * @return mixed
     */
    function findUsername($username, &$reason);
}
interface IGearAuthSessionService
{
    /**
     * @param $name string
     * @param $value mixed
     * @return mixed
     */
    function setRawSessionVariable($name, $value);

    /**
     * @param $name string
     * @return mixed
     */
    function getRawSessionVariable($name);
}
interface IGearAuthSuccessfulHandler
{
    /**
     * @param $userObject
     * @param $username
     * @param $password
     * @param $params
     *
     * @return mixed Target result.
     */
    function onAuthSuccessful($userObject, $username, $password, $params);
}
/**
 * @property int id
 * @property string username
 * @property string passwordHash
 * @property string email
 * @property string mobile
 * @property string tell
 * @property string firstName
 * @property string lastName
 * @property string middleName
 */
interface IGearAuthUser extends IGearIdEntity
{
    /**
     * Returns username field.
     * @return string
     */
    function getUsername();
    /**
     * Evaluates username field.
     * @param $username string
     * @return void
     */
    function setUsername($username);

    /**
     * Returns passwordHash field.
     * @return string
     */
    function getPasswordHash();
    /**
     * Evaluates passwordHash field.
     * @param $passwordHash string
     * @return void
     */
    function setPasswordHash($passwordHash);

    /**
     * Returns mobile field.
     * @return string
     */
    function getMobile();
    /**
     * Evaluates mobile field.
     * @param $mobile string
     * @return void
     */
    function setMobile($mobile);

    /**
     * Returns tell field.
     * @return string
     */
    function getTell();
    /**
     * Evaluates tell field.
     * @param $tell string
     * @return void
     */
    function setTell($tell);

    /**
     * Returns email field.
     * @return string
     */
    function getEmail();
    /**
     * Evaluates email field.
     * @param $email string
     * @return void
     */
    function setEmail($email);
    /**
     * Returns firstName field.
     * @return string
     */
    function getFirstName();
    /**
     * Evaluates firstName field.
     * @param $firstName string
     * @return void
     */
    function setFirstName($firstName);
    /**
     * Returns lastName field.
     * @return string
     */
    function getLastName();
    /**
     * Evaluates lastName field.
     * @param $lastName string
     * @return void
     */
    function setLastName($lastName);
    /**
     * Returns middleName field.
     * @return string
     */
    function getMiddleName();
    /**
     * Evaluates middleName field.
     * @param $middleName string
     * @return void
     */
    function setMiddleName($middleName);
}
interface IGearPasswordHashProvider
{
    function getPasswordUsernameHash($password, $username);
    function getPasswordHash($password);
}
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
class GearDefaultAuthCookieService implements IGearAuthCookieService
{
    public function setRawCookieVariable($name, $value)
    {
        $_COOKIE[$name] = $value;
    }

    public function getRawCookieVariable($name)
    {
        return isset($_COOKIE[$name])
            ? $_COOKIE[$name]
            : null;
    }
}
class GearDefaultAuthSessionService implements IGearAuthSessionService
{
    public function setRawSessionVariable($name, $value)
    {
        session_start();
        $_SESSION[$name] = $value;
    }

    public function getRawSessionVariable($name)
    {
        return isset($_SESSION[$name])
            ? $_SESSION[$name]
            : null;
    }
}
class GearDefaultWebSuccessfulHandler implements IGearAuthSuccessfulHandler
{
    /** @var IGearAuthSessionService */
    private $sessionService;
    /** @var IGearAuthCookieService */
    private $cookieService;

    /**
     * GearDefaultWebSuccessfulHandler constructor.
     * @param $sessionService IGearAuthSessionService
     * @param $cookieService IGearAuthCookieService
     */
    public function __construct($sessionService = null, $cookieService = null)
    {
        $this->sessionService = $sessionService == null
            ? new GearDefaultAuthSessionService()
            : $sessionService;

        $this->cookieService = $cookieService == null
            ? new GearDefaultAuthCookieService()
            : $cookieService;
    }

    public function onAuthSuccessful($userObject, $username, $password, $params)
    {

    }
}


/* Generals: */

