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
 * @property string firstName
 * @property string lastName
 */
interface IGearAuthUser extends IGearIdEntity
{
    //public
    //    $username,
    //    $mobile,
    //    $email,
    //    $firstName,
    //    $lastName,
    //
    //    $passwordHash
    //;

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
     * Returns passwordHash field.
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
     * Evaluates lastName field.
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
}
/*</module>*/
?>