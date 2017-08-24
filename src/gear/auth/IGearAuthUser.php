<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\auth;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\data\core\entity\IGearIdEntity;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
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
/*</module>*/
?>