<?php

namespace Application\Sonata\UserBundle\Service;

use Symfony\Component\Security\Core\Encoder\BasePasswordEncoder;

//use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

/** * PasswordEncoder uses a message digest algorithm */
class PasswordEncoder extends BasePasswordEncoder //implements PasswordEncoderInterface
{
    private $algorithm;
    private $encodeHashAsBase64;
    private $iterations;

    /** * Constructor. * *  @param string $algorithm The digest algorithm to use * @param Boolean $encodeHashAsBase64 Whether to base64 encode the password hash * @param integer $iterations The number of iterations to use to stretch the password hash */
    public function __construct($algorithm = 'md5', $encodeHashAsBase64 = true, $iterations = 5000)
    {
        $this->algorithm = $algorithm;
        $this->encodeHashAsBase64 = $encodeHashAsBase64;
        $this->iterations = $iterations;
    }

    /** * {@inheritdoc} */
    public function encodePassword($raw, $salt)
    {
        if (!in_array($this->algorithm, hash_algos(), true)) {
            throw new \LogicException(sprintf('The algorithm "%s" is not supported.', $this->algorithm));
        }

        $salted = $this->mergePasswordAndSalt($raw, $salt);

        if( $this->algorithm == "md5" ) {  // Old website or Sf 1.4
            return md5( $salted );
        /*}elseif( $this->algorithm == "sha1" || $this->algorithm == "md5" ) {  

            $digest = hash($this->algorithm, $salted, true);

            $digest = hash($this->algorithm, $digest.$salted, true);

            return $this->encodeHashAsBase64 ? base64_encode($digest) : bin2hex($digest);*/
        }else{ // Sf 2.1
            $digest = hash($this->algorithm, $salted, true);

            for ($i = 1; $i < $this->iterations; $i++) {
                $digest = hash($this->algorithm, $digest.$salted, true);
            }

            return $this->encodeHashAsBase64 ? base64_encode($digest) : bin2hex($digest);
        }
        /*
        $digest = hash($this->algorithm, $salted, true);

        for ($i = 1; $i < $this->iterations; $i++) {
            $digest = hash($this->algorithm, $digest.$salted, true);
        }

        return $this->encodeHashAsBase64 ? base64_encode($digest) : bin2hex($digest);*/
    }

        /** * Merges a password and a salt. * * @param string $password the password to be used * @param string $salt the salt to be used * * @return string a merged password and salt */
    protected function mergePasswordAndSalt($password, $salt)
    {
        if (empty($salt)) { // Old website
            return $password;
        }elseif($this->algorithm == "sha1" || $this->algorithm == "md5") { // Sf 1.4
            return $salt.$password;
        }else{ // Sf 2.1
            return $password."{".$salt."}";
        }
    }

    public function isPasswordValid($encoded, $raw, $salt)
    {
        return $this->comparePasswords($encoded, $this->encodePassword($raw, $salt));
    }
}

?>